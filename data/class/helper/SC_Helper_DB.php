<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * DB関連のヘルパークラス.
 *
 * @package Helper
 * @author EC-CUBE CO.,LTD.
 * @version $Id:SC_Helper_DB.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class SC_Helper_DB
{
    /** ルートカテゴリ取得フラグ */
    public $g_root_on;

    /** ルートカテゴリID */
    public $g_root_id;

    /** 選択中カテゴリ取得フラグ */
    public $g_category_on;

    /** 選択中カテゴリID */
    public $g_category_id;

    /**
     * カラムの存在チェックと作成を行う.
     *
     * チェック対象のテーブルに, 該当のカラムが存在するかチェックする.
     * 引数 $add が true の場合, 該当のカラムが存在しない場合は, カラムの生成を行う.
     * カラムの生成も行う場合は, $col_type も必須となる.
     *
     * @param  string $tableName  テーブル名
     * @param  string $colType    カラムのデータ型
     * @param  string $dsn         データソース名
     * @param  bool   $add         カラムの作成も行う場合 true
     * @return bool   カラムが存在する場合とカラムの生成に成功した場合 true,
     *               テーブルが存在しない場合 false,
     *               引数 $add == false でカラムが存在しない場合 false
     */
    public function sfColumnExists($tableName, $colName, $colType = '', $dsn = '', $add = false)
    {
        $dbFactory = SC_DB_DBFactory_Ex::getInstance();
        $dsn = $dbFactory->getDSN($dsn);

        $objQuery = SC_Query_Ex::getSingletonInstance($dsn);

        // テーブルが無ければエラー
        if (!in_array($tableName, $objQuery->listTables())) return false;

        // 正常に接続されている場合
        if (!$objQuery->isError()) {
            // カラムリストを取得
            $columns = $objQuery->listTableFields($tableName);

            if (in_array($colName, $columns)) {
                return true;
            }
        }

        // カラムを追加する
        if ($add) {
            return $this->sfColumnAdd($tableName, $colName, $colType);
        }

        return false;
    }

    /**
     * @param string $colType
     * @param string $tableName
     */
    public function sfColumnAdd($tableName, $colName, $colType)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        return $objQuery->query("ALTER TABLE $tableName ADD $colName $colType ");
    }

    /**
     * データの存在チェックを行う.
     *
     * @param  string $tableName   テーブル名
     * @param  string $where       データを検索する WHERE 句
     * @param  array  $arrWhereVal WHERE句のプレースホルダ値
     * @return bool   データが存在する場合 true, データの追加に成功した場合 true,
     *               $add == false で, データが存在しない場合 false
     */
    public static function sfDataExists($tableName, $where, $arrWhereVal)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $exists = $objQuery->exists($tableName, $where, $arrWhereVal);

        return $exists;
    }

    /**
     * 店舗基本情報を取得する.
     *
     * 引数 $force が false の場合は, 初回のみ DB 接続し,
     * 2回目以降はキャッシュされた結果を使用する.
     *
     * @param  boolean $force 強制的にDB取得するか
     * @return array   店舗基本情報の配列
     */
    public function sfGetBasisData($force = false)
    {
        static $arrData = null;

        if ($force || is_null($arrData)) {
            $objQuery = SC_Query_Ex::getSingletonInstance();

            $arrData = $objQuery->getRow('*', 'dtb_baseinfo');
        }

        return $arrData;
    }

    /**
     * 基本情報のキャッシュデータを取得する
     *
     * @param  boolean $generate キャッシュファイルが無い時、DBのデータを基にキャッシュを生成するか
     * @return array   店舗基本情報の配列
     */
    public function sfGetBasisDataCache($generate = false)
    {
        // テーブル名
        $name = 'dtb_baseinfo';
        // キャッシュファイルパス
        $filepath = MASTER_DATA_REALDIR . $name . '.serial';
        // ファイル存在確認
        if (!file_exists($filepath) && $generate) {
            // 存在していなければキャッシュ生成
            $this->sfCreateBasisDataCache();
        }
        // 戻り値初期化
        $cacheData = array();
        // キャッシュファイルが存在すれば読み込む
        if (file_exists($filepath)) {
            // キャッシュデータファイルを読み込みアンシリアライズした配列を取得
            $cacheData = unserialize(file_get_contents($filepath));
        }
        // return
        return $cacheData;
    }

    /**
     * 基本情報のキャッシュデータファイルを生成する
     * データはsfGetBasisDataより取得。
     *
     * このメソッドが直接呼ばれるのは、
     *「基本情報管理＞SHOPマスター」の更新完了後。
     * sfGetBasisDataCacheでは、
     * キャッシュデータファイルが無い場合に呼ばれます。
     *
     * @return bool キャッシュデータファイル生成結果
     */
    public function sfCreateBasisDataCache()
    {
        // テーブル名
        $name = 'dtb_baseinfo';
        // キャッシュファイルパス
        $filepath = MASTER_DATA_REALDIR . $name . '.serial';
        // データ取得
        $arrData = $this->sfGetBasisData(true);
        // シリアライズ
        $data = serialize($arrData);
        // ファイルを書き出しモードで開く
        $handle = fopen($filepath, 'w');
        if (!$handle) {
            // ファイル生成失敗
            return false;
        }
        // ファイルの内容を書き出す.
        $res = fwrite($handle, $data);
        // ファイルを閉じる
        fclose($handle);
        if ($res === false) {
            // ファイル生成失敗
            return false;
        }
        // ファイル生成成功
        return true;
    }

    /**
     * 基本情報の登録数を取得する
     *
     * @return int
     * @deprecated
     */
    public function sfGetBasisCount()
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        return $objQuery->count('dtb_baseinfo');
    }

    /**
     * 基本情報の登録有無を取得する
     *
     * @return boolean 有無
     */
    public function sfGetBasisExists()
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        return $objQuery->exists('dtb_baseinfo');
    }

    /**
     * 選択中のアイテムのルートカテゴリIDを取得する
     * @deprecated 本体で使用されていないため非推奨
     */
    public function sfGetRootId()
    {
        if (!$this->g_root_on) {
            $this->g_root_on = true;

            if (!isset($_GET['product_id'])) $_GET['product_id'] = '';
            if (!isset($_GET['category_id'])) $_GET['category_id'] = '';

            if (!empty($_GET['product_id']) || !empty($_GET['category_id'])) {
                // 選択中のカテゴリIDを判定する
                $category_id = $this->sfGetCategoryId($_GET['product_id'], $_GET['category_id']);
                // ROOTカテゴリIDの取得
                if (count($category_id) > 0) {
                    $arrRet = $this->sfGetParentsArray('dtb_category', 'parent_category_id', 'category_id', $category_id);
                    $root_id = isset($arrRet[0]) ? $arrRet[0] : '';
                } else {
                    $root_id = '';
                }
            } else {
                // ROOTカテゴリIDをなしに設定する
                $root_id = '';
            }
            $this->g_root_id = $root_id;
        }

        return $this->g_root_id;
    }

    /**
     * 受注番号、最終ポイント、加算ポイント、利用ポイントから「オーダー前ポイント」を取得する
     *
     * @param  integer $order_id     受注番号
     * @param  integer $use_point    利用ポイント
     * @param  integer $add_point    加算ポイント
     * @param  integer $order_status 対応状況
     * @return array   オーダー前ポイントの配列
     */
    public static function sfGetRollbackPoint($order_id, $use_point, $add_point, $order_status)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrRet = $objQuery->select('customer_id', 'dtb_order', 'order_id = ?', array($order_id));
        $customer_id = $arrRet[0]['customer_id'];
        if ($customer_id != '' && $customer_id >= 1) {
            $arrRet = $objQuery->select('point', 'dtb_customer', 'customer_id = ?', array($customer_id));
            $point = $arrRet[0]['point'];
            $rollback_point = $arrRet[0]['point'];

            // 対応状況がポイント利用対象の場合、使用ポイント分を戻す
            if (SC_Helper_Purchase_Ex::isUsePoint($order_status)) {
                $rollback_point += $use_point;
            }

            // 対応状況がポイント加算対象の場合、加算ポイント分を戻す
            if (SC_Helper_Purchase_Ex::isAddPoint($order_status)) {
                $rollback_point -= $add_point;
            }
        } else {
            $rollback_point = '';
            $point = '';
        }

        return array($point, $rollback_point);
    }

    /**
     * カテゴリツリーの取得を行う.
     *
     * @param  integer $parent_category_id 親カテゴリID
     * @param  bool    $count_check        登録商品数のチェックを行う場合 true
     * @return array   カテゴリツリーの配列
     */
    public static function sfGetCatTree($parent_category_id, $count_check = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '';
        $col .= ' cat.category_id,';
        $col .= ' cat.category_name,';
        $col .= ' cat.parent_category_id,';
        $col .= ' cat.level,';
        $col .= ' cat.rank,';
        $col .= ' cat.creator_id,';
        $col .= ' cat.create_date,';
        $col .= ' cat.update_date,';
        $col .= ' cat.del_flg, ';
        $col .= ' ttl.product_count';
        $from = 'dtb_category as cat left join dtb_category_total_count as ttl on ttl.category_id = cat.category_id';
        // 登録商品数のチェック
        if ($count_check) {
            $where = 'del_flg = 0 AND product_count > 0';
        } else {
            $where = 'del_flg = 0';
        }
        $objQuery->setOption('ORDER BY rank DESC');
        $arrRet = $objQuery->select($col, $from, $where);

        $arrParentID = SC_Utils_Ex::getTreeTrail($parent_category_id, 'category_id', 'parent_category_id', $arrRet);

        foreach ($arrRet as $key => $array) {
            foreach ($arrParentID as $val) {
                if ($array['category_id'] == $val) {
                    $arrRet[$key]['display'] = 1;
                    break;
                }
            }
        }

        return $arrRet;
    }

    /**
     * カテゴリツリーを走査し, パンくずリスト用の配列を生成する.
     *
     * @param array カテゴリの配列
     * @param integer $parent 上位カテゴリID
     * @param array パンくずリスト用の配列
     * @result void
     * @see sfGetCatTree()
     * @deprecated 本体で使用されていないため非推奨
     */
    public function findTree(&$arrTree, $parent, &$result)
    {
        if ($result[count($result) - 1]['parent_category_id'] === 0) {
            return;
        } else {
            foreach ($arrTree as $val) {
                if ($val['category_id'] == $parent) {
                    $result[] = array(
                        'category_id' => $val['category_id'],
                        'parent_category_id' => (int) $val['parent_category_id'],
                        'category_name' => $val['category_name'],
                    );
                    $this->findTree($arrTree, $val['parent_category_id'], $result);
                }
            }
        }
    }

    /**
     * カテゴリツリーの取得を複数カテゴリで行う.
     *
     * @param  integer $product_id  商品ID
     * @param  bool    $count_check 登録商品数のチェックを行う場合 true
     * @return array   カテゴリツリーの配列
     */
    public static function sfGetMultiCatTree($product_id, $count_check = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '';
        $col .= ' cat.category_id,';
        $col .= ' cat.category_name,';
        $col .= ' cat.parent_category_id,';
        $col .= ' cat.level,';
        $col .= ' cat.rank,';
        $col .= ' cat.creator_id,';
        $col .= ' cat.create_date,';
        $col .= ' cat.update_date,';
        $col .= ' cat.del_flg, ';
        $col .= ' ttl.product_count';
        $from = 'dtb_category as cat left join dtb_category_total_count as ttl on ttl.category_id = cat.category_id';
        // 登録商品数のチェック
        if ($count_check) {
            $where = 'del_flg = 0 AND product_count > 0';
        } else {
            $where = 'del_flg = 0';
        }
        $objQuery->setOption('ORDER BY rank DESC');
        $arrRet = $objQuery->select($col, $from, $where);

        $arrCategory_id = SC_Helper_DB_Ex::sfGetCategoryId($product_id);

        $arrCatTree = array();
        foreach ($arrCategory_id as $pkey => $parent_category_id) {
            $arrParentID = SC_Helper_DB_Ex::sfGetParentsArray('dtb_category', 'parent_category_id', 'category_id', $parent_category_id);

            foreach ($arrParentID as $pid) {
                foreach ($arrRet as $key => $array) {
                    if ($array['category_id'] == $pid) {
                        $arrCatTree[$pkey][] = $arrRet[$key];
                        break;
                    }
                }
            }
        }

        return $arrCatTree;
    }

    /**
     * 親カテゴリを連結した文字列を取得する.
     *
     * @param  integer $category_id カテゴリID
     * @return string  親カテゴリを連結した文字列
     * @deprecated 本体で使用されていないため非推奨
     */
    public function sfGetCatCombName($category_id)
    {
        // 商品が属するカテゴリIDを縦に取得
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrCatID = $this->sfGetParentsArray('dtb_category', 'parent_category_id', 'category_id', $category_id);
        $ConbName = '';

        // カテゴリ名称を取得する
        foreach ($arrCatID as $val) {
            $sql = 'SELECT category_name FROM dtb_category WHERE category_id = ?';
            $arrVal = array($val);
            $CatName = $objQuery->getOne($sql, $arrVal);
            $ConbName .= $CatName . ' | ';
        }
        // 最後の ｜ をカットする
        $ConbName = substr_replace($ConbName, '', strlen($ConbName) - 2, 2);

        return $ConbName;
    }

    /**
     * 指定したカテゴリIDの大カテゴリを取得する.
     *
     * @param  integer $category_id カテゴリID
     * @return array   指定したカテゴリIDの大カテゴリ

     * @deprecated 本体で使用されていないため非推奨
     */
    public function sfGetFirstCat($category_id)
    {
        // 商品が属するカテゴリIDを縦に取得
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrRet = array();
        $arrCatID = $this->sfGetParentsArray('dtb_category', 'parent_category_id', 'category_id', $category_id);
        $arrRet['id'] = $arrCatID[0];

        // カテゴリ名称を取得する
        $sql = 'SELECT category_name FROM dtb_category WHERE category_id = ?';
        $arrVal = array($arrRet['id']);
        $arrRet['name'] = $objQuery->getOne($sql, $arrVal);

        return $arrRet;
    }

    /**
     * カテゴリツリーの取得を行う.
     *
     * $products_check:true商品登録済みのものだけ取得する
     *
     * @param  string $addwhere       追加する WHERE 句
     * @param  bool   $products_check 商品の存在するカテゴリのみ取得する場合 true
     * @param  string $head           カテゴリ名のプレフィックス文字列
     * @return array  カテゴリツリーの配列
     */
    public function sfGetCategoryList($addwhere = '', $products_check = false, $head = CATEGORY_HEAD)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = 'del_flg = 0';

        if ($addwhere != '') {
            $where.= " AND $addwhere";
        }

        $objQuery->setOption('ORDER BY rank DESC');

        if ($products_check) {
            $col = 'T1.category_id, category_name, level';
            $from = 'dtb_category AS T1 LEFT JOIN dtb_category_total_count AS T2 ON T1.category_id = T2.category_id';
            $where .= ' AND product_count > 0';
        } else {
            $col = 'category_id, category_name, level';
            $from = 'dtb_category';
        }

        $arrRet = $objQuery->select($col, $from, $where);

        $max = count($arrRet);
        $arrList = array();
        for ($cnt = 0; $cnt < $max; $cnt++) {
            $id = $arrRet[$cnt]['category_id'];
            $name = $arrRet[$cnt]['category_name'];
            $arrList[$id] = str_repeat($head, $arrRet[$cnt]['level']) . $name;
        }

        return $arrList;
    }

    /**
     * カテゴリツリーの取得を行う.
     *
     * 親カテゴリの Value=0 を対象とする
     *
     * @param  bool  $parent_zero 親カテゴリの Value=0 の場合 true
     * @return array カテゴリツリーの配列
     */
    public static function sfGetLevelCatList($parent_zero = true)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // カテゴリ名リストを取得
        $col = 'category_id, parent_category_id, category_name';
        $where = 'del_flg = 0';
        $objQuery->setOption('ORDER BY level');
        $arrRet = $objQuery->select($col, 'dtb_category', $where);
        $arrCatName = array();
        foreach ($arrRet as $arrTmp) {
            $arrCatName[$arrTmp['category_id']] =
                (($arrTmp['parent_category_id'] > 0)?
                    $arrCatName[$arrTmp['parent_category_id']] : '')
                . CATEGORY_HEAD . $arrTmp['category_name'];
        }

        $col = 'category_id, parent_category_id, category_name, level';
        $where = 'del_flg = 0';
        $objQuery->setOption('ORDER BY rank DESC');
        $arrRet = $objQuery->select($col, 'dtb_category', $where);
        $max = count($arrRet);

        $arrValue = array();
        $arrOutput = array();
        for ($cnt = 0; $cnt < $max; $cnt++) {
            if ($parent_zero) {
                if ($arrRet[$cnt]['level'] == LEVEL_MAX) {
                    $arrValue[$cnt] = $arrRet[$cnt]['category_id'];
                } else {
                    $arrValue[$cnt] = '';
                }
            } else {
                $arrValue[$cnt] = $arrRet[$cnt]['category_id'];
            }

            $arrOutput[$cnt] = $arrCatName[$arrRet[$cnt]['category_id']];
        }

        return array($arrValue, $arrOutput);
    }

    /**
     * 選択中の商品のカテゴリを取得する.
     *
     * 引数のカテゴリIDが有効な場合は, カテゴリIDを含んだ配列を返す
     * 引数のカテゴリIDが無効な場合, dtb_product_categories にレコードが存在する場合は, カテゴリIDを含んだ配列を返す
     *
     * @param  integer $product_id  プロダクトID
     * @param  integer $category_id カテゴリID
     * @param   bool $closed 引数のカテゴリIDが無効な場合で, 非表示の商品を含む場合はtrue
     * @return array   選択中の商品のカテゴリIDの配列
     */
    public function sfGetCategoryId($product_id, $category_id = 0, $closed = false)
    {
        if ($closed) {
            $status = '';
        } else {
            $status = 'status = 1';
        }
        $category_id = (int) $category_id;
        $product_id = (int) $product_id;
        $objCategory = new SC_Helper_Category_Ex();
        // XXX SC_Helper_Category::isValidCategoryId() で使用している SC_Helper_DB::sfIsRecord() が内部で del_flg = 0 を追加するため, $closed は機能していない
        if ($objCategory->isValidCategoryId($category_id, $closed)) {
            $category_id = array($category_id);
        } elseif (SC_Utils_Ex::sfIsInt($product_id) && $product_id != 0 && SC_Helper_DB_Ex::sfIsRecord('dtb_products','product_id', $product_id, $status)) {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $category_id = $objQuery->getCol('category_id', 'dtb_product_categories', 'product_id = ?', array($product_id));
        } else {
            // 不正な場合は、空の配列を返す。
            $category_id = array();
        }

        return $category_id;
    }

    /**
     * 商品をカテゴリの先頭に追加する.
     *
     * @param  integer $category_id カテゴリID
     * @param  integer $product_id  プロダクトID
     * @return void
     */
    public function addProductBeforCategories($category_id, $product_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $sqlval = array('category_id' => $category_id,
                        'product_id' => $product_id);

        $arrSql = array();
        $arrSql['rank'] = '(SELECT COALESCE(MAX(rank), 0) FROM dtb_product_categories sub WHERE category_id = ?) + 1';

        $from_and_where = $objQuery->dbFactory->getDummyFromClauseSql();
        $from_and_where .= ' WHERE NOT EXISTS(SELECT * FROM dtb_product_categories WHERE category_id = ? AND product_id = ?)';
        $objQuery->insert('dtb_product_categories', $sqlval, $arrSql, array($category_id), $from_and_where, array($category_id, $product_id));
    }

    /**
     * 商品をカテゴリの末尾に追加する.
     *
     * @param  integer $category_id カテゴリID
     * @param  integer $product_id  プロダクトID
     * @return void
     * @deprecated 本体で使用されていないため非推奨
     */
    public function addProductAfterCategories($category_id, $product_id)
    {
        $sqlval = array('category_id' => $category_id,
                        'product_id' => $product_id);

        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 現在の商品カテゴリを取得
        $arrCat = $objQuery->select('product_id, category_id, rank',
                                    'dtb_product_categories',
                                    'category_id = ?',
                                    array($category_id));

        $min = 0;
        foreach ($arrCat as $val) {
            // 同一商品が存在する場合は登録しない
            if ($val['product_id'] == $product_id) {
                return;
            }
            // 最下位ランクを取得
            $min = ($min < $val['rank']) ? $val['rank'] : $min;
        }
        $sqlval['rank'] = $min;
        $objQuery->insert('dtb_product_categories', $sqlval);
    }

    /**
     * 商品をカテゴリから削除する.
     *
     * @param  integer $category_id カテゴリID
     * @param  integer $product_id  プロダクトID
     * @return void
     */
    public function removeProductByCategories($category_id, $product_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->delete('dtb_product_categories',
                          'category_id = ? AND product_id = ?', array($category_id, $product_id));
    }

    /**
     * 商品カテゴリを更新する.
     *
     * @param  array   $arrCategory_id 登録するカテゴリIDの配列
     * @param  integer $product_id     プロダクトID
     * @return void
     */
    public function updateProductCategories($arrCategory_id, $product_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 現在のカテゴリ情報を取得
        $arrCurrentCat = $objQuery->getCol('category_id',
                                           'dtb_product_categories',
                                           'product_id = ?',
                                           array($product_id));

        // 登録するカテゴリ情報と比較
        foreach ($arrCurrentCat as $category_id) {
            // 登録しないカテゴリを削除
            if (!in_array($category_id, $arrCategory_id)) {
                $this->removeProductByCategories($category_id, $product_id);
            }
        }

        // カテゴリを登録
        foreach ($arrCategory_id as $category_id) {
            $this->addProductBeforCategories($category_id, $product_id);
            SC_Utils_Ex::extendTimeOut();
        }
    }

    /**
     * カテゴリ数の登録を行う.
     *
     *
     * @param  SC_Query $objQuery           SC_Query インスタンス
     * @param  boolean  $is_force_all_count 全カテゴリの集計を強制する場合 true
     * @param bool $is_nostock_hidden 在庫切れの商品は非表示にする場合 true
     *
     * @return void
     */
    public function sfCountCategory($objQuery = NULL, $is_force_all_count = false, $is_nostock_hidden = NOSTOCK_HIDDEN)
    {
        $objProduct = new SC_Product_Ex();

        if ($objQuery == NULL) {
            $objQuery = SC_Query_Ex::getSingletonInstance();
        }

        $is_out_trans = false;
        if (!$objQuery->inTransaction()) {
            $objQuery->begin();
            $is_out_trans = true;
        }

        //共通のfrom/where文の構築
        $sql_where = SC_Product_Ex::getProductDispConditions('alldtl');
        // 在庫無し商品の非表示
        if ($is_nostock_hidden) {
            $where_products_class = '(stock >= 1 OR stock_unlimited = 1)';
            $from = $objProduct->alldtlSQL($where_products_class);
        } else {
            $from = 'dtb_products as alldtl';
        }

        //dtb_category_countの構成
        // 各カテゴリに所属する商品の数を集計。集計対象には子カテゴリを含まない。

        //まずテーブル内容の元を取得
        if (!$is_force_all_count) {
            $arrCategoryCountOld = $objQuery->select('category_id,product_count', 'dtb_category_count');
        } else {
            $arrCategoryCountOld = array();
        }

        //各カテゴリ内の商品数を数えて取得
        $sql = <<< __EOS__
            SELECT T1.category_id, count(T2.category_id) as product_count
            FROM dtb_category AS T1
                LEFT JOIN dtb_product_categories AS T2
                    ON T1.category_id = T2.category_id
                LEFT JOIN $from
                    ON T2.product_id = alldtl.product_id
            WHERE $sql_where
            GROUP BY T1.category_id, T2.category_id
__EOS__;

        $arrCategoryCountNew = $objQuery->getAll($sql);
        // 各カテゴリに所属する商品の数を集計。集計対象には子カテゴリを「含む」。
        //差分を取得して、更新対象カテゴリだけを確認する。

        //各カテゴリ毎のデータ値において以前との差を見る
        //古いデータの構造入れ替え
        $arrOld = array();
        foreach ($arrCategoryCountOld as $item) {
            $arrOld[$item['category_id']] = $item['product_count'];
        }
        //新しいデータの構造入れ替え
        $arrNew = array();
        foreach ($arrCategoryCountNew as $item) {
            $arrNew[$item['category_id']] = $item['product_count'];
        }

        unset($arrCategoryCountOld);
        unset($arrCategoryCountNew);

        $arrDiffCategory_id = array();
        //新しいカテゴリ一覧から見て商品数が異なるデータが無いか確認
        foreach ($arrNew as $cid => $count) {
            if ($arrOld[$cid] != $count) {
                $arrDiffCategory_id[] = $cid;
            }
        }
        //削除カテゴリを想定して、古いカテゴリ一覧から見て商品数が異なるデータが無いか確認。
        foreach ($arrOld as $cid => $count) {
            if ($arrNew[$cid] != $count && $count > 0) {
                $arrDiffCategory_id[] = $cid;
            }
        }

        //対象IDが無ければ終了
        if (count($arrDiffCategory_id) == 0) {
            if ($is_out_trans) {
                $objQuery->commit();
            }

            return;
        }

        //差分対象カテゴリIDの重複を除去
        $arrDiffCategory_id = array_unique($arrDiffCategory_id);

        //dtb_category_countの更新 差分のあったカテゴリだけ更新する。
        foreach ($arrDiffCategory_id as $cid) {
            $sqlval = array();
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $sqlval['product_count'] = (string) $arrNew[$cid];
            if ($sqlval['product_count'] =='') {
                $sqlval['product_count'] = (string) '0';
            }
            if (isset($arrOld[$cid])) {
                $objQuery->update('dtb_category_count', $sqlval, 'category_id = ?', array($cid));
            } else {
                if ($is_force_all_count) {
                    $ret = $objQuery->update('dtb_category_count', $sqlval, 'category_id = ?', array($cid));
                    if ($ret > 0) {
                        continue;
                    }
                }
                $sqlval['category_id'] = $cid;
                $objQuery->insert('dtb_category_count', $sqlval);
            }
        }

        unset($arrOld);
        unset($arrNew);

        //差分があったIDとその親カテゴリIDのリストを取得する
        $arrTgtCategory_id = array();
        foreach ($arrDiffCategory_id as $parent_category_id) {
            $arrTgtCategory_id[] = $parent_category_id;
            $arrParentID = $this->sfGetParentsArray('dtb_category', 'parent_category_id', 'category_id', $parent_category_id);
            $arrTgtCategory_id = array_unique(array_merge($arrTgtCategory_id, $arrParentID));
        }

        unset($arrDiffCategory_id);

        //dtb_category_total_count 集計処理開始
        //更新対象カテゴリIDだけ集計しなおす。
        $arrUpdateData = array();
        $where_products_class = '';
        if ($is_nostock_hidden) {
            $where_products_class .= '(stock >= 1 OR stock_unlimited = 1)';
        }
        $from = $objProduct->alldtlSQL($where_products_class);
        foreach ($arrTgtCategory_id as $category_id) {
            $arrWhereVal = array();
            list($tmp_where, $arrTmpVal) = $this->sfGetCatWhere($category_id);
            if ($tmp_where != '') {
                $sql_where_product_ids = 'product_id IN (SELECT product_id FROM dtb_product_categories WHERE ' . $tmp_where . ')';
                $arrWhereVal = $arrTmpVal;
            } else {
                $sql_where_product_ids = '0<>0'; // 一致させない
            }
            $where = "($sql_where) AND ($sql_where_product_ids)";

            $arrUpdateData[$category_id] = $objQuery->count($from, $where, $arrWhereVal);
        }

        unset($arrTgtCategory_id);

        // 更新対象だけを更新。
        foreach ($arrUpdateData as $cid => $count) {
            $sqlval = array();
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $sqlval['product_count'] = $count;
            if ($sqlval['product_count'] =='') {
                $sqlval['product_count'] = (string) '0';
            }
            $ret = $objQuery->update('dtb_category_total_count', $sqlval, 'category_id = ?', array($cid));
            if (!$ret) {
                $sqlval['category_id'] = $cid;
                $objQuery->insert('dtb_category_total_count', $sqlval);
            }
        }
        // トランザクション終了処理
        if ($is_out_trans) {
            $objQuery->commit();
        }
    }

    /**
     * 子IDの配列を返す.
     *
     * @param string  $table    テーブル名
     * @param string  $pid_name 親ID名
     * @param string  $id_name  ID名
     * @param integer $id       ID
     * @param array 子ID の配列
     * @deprecated 本体で使用されていないため非推奨
     */
    public function sfGetChildsID($table, $pid_name, $id_name, $id)
    {
        $arrRet = $this->sfGetChildrenArray($table, $pid_name, $id_name, $id);

        return $arrRet;
    }

    /**
     * 階層構造のテーブルから子ID配列を取得する.
     *
     * @param  string  $table    テーブル名
     * @param  string  $pid_name 親ID名
     * @param  string  $id_name  ID名
     * @param  integer $id       ID番号
     * @return array   子IDの配列
     */
    public function sfGetChildrenArray($table, $pid_name, $id_name, $id)
    {
        $arrChildren = array();
        $arrRet = array($id);

        while (count($arrRet) > 0) {
            $arrChildren = array_merge($arrChildren, $arrRet);
            $arrRet = SC_Helper_DB_Ex::sfGetChildrenArraySub($table, $pid_name, $id_name, $arrRet);
        }

        return $arrChildren;
    }

    /**
     * 親ID直下の子IDを全て取得する.
     *
     * @param  string $pid_name 親ID名
     * @param  string $id_name  ID名
     * @param  array  $arrPID   親IDの配列
     * @param string $table
     * @return array  子IDの配列
     */
    public function sfGetChildrenArraySub($table, $pid_name, $id_name, $arrPID)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $where = "$pid_name IN (" . SC_Utils_Ex::repeatStrWithSeparator('?', count($arrPID)) . ')';

        $return = $objQuery->getCol($id_name, $table, $where, $arrPID);

        return $return;
    }

    /**
     * 所属する全ての階層の親IDを配列で返す.
     *
     * @param  string   $table    テーブル名
     * @param  string   $pid_name 親ID名
     * @param  string   $id_name  ID名
     * @param  integer  $id       ID
     * @return array    親IDの配列
     * @deprecated SC_Helper_DB::sfGetParentsArray() を使用して下さい
     */
    public function sfGetParents($table, $pid_name, $id_name, $id)
    {
        $arrRet = SC_Helper_DB_Ex::sfGetParentsArray($table, $pid_name, $id_name, $id);

        return $arrRet;
    }

    /**
     * 階層構造のテーブルから親ID配列を取得する.
     *
     * @param  string  $table    テーブル名
     * @param  string  $pid_name 親ID名
     * @param  string  $id_name  ID名
     * @param  integer $id       ID
     * @return array   親IDの配列
     */
    public function sfGetParentsArray($table, $pid_name, $id_name, $id)
    {
        $arrParents = array();
        $ret = $id;

        $loop_cnt = 1;
        while ($ret != '0' && !SC_Utils_Ex::isBlank($ret)) {
            // 無限ループの予防
            if ($loop_cnt > LEVEL_MAX) {
                trigger_error('最大階層制限到達', E_USER_ERROR);
            }

            $arrParents[] = $ret;
            $ret = SC_Helper_DB_Ex::sfGetParentsArraySub($table, $pid_name, $id_name, $ret);

            ++$loop_cnt;
        }

        $arrParents = array_reverse($arrParents);

        return $arrParents;
    }

    /* 子ID所属する親IDを取得する */

    /**
     * @param string $table
     * @param string $pid_name
     * @param string $id_name
     */
    public function sfGetParentsArraySub($table, $pid_name, $id_name, $child)
    {
        if (SC_Utils_Ex::isBlank($child)) {
            return false;
        }
        $objQuery = SC_Query_Ex::getSingletonInstance();
        if (!is_array($child)) {
            $child = array($child);
        }
        $parent = $objQuery->get($pid_name, $table, "$id_name = ?", $child);

        return $parent;
    }

    /**
     * カテゴリから商品を検索する場合のWHERE文と値を返す.
     *
     * @param  integer $category_id カテゴリID
     * @return array   商品を検索する場合の配列
     */
    public function sfGetCatWhere($category_id)
    {
        // 子カテゴリIDの取得
        $arrRet = SC_Helper_DB_Ex::sfGetChildrenArray('dtb_category', 'parent_category_id', 'category_id', $category_id);

        $where = 'category_id IN (' . SC_Utils_Ex::repeatStrWithSeparator('?', count($arrRet)) . ')';

        return array($where, $arrRet);
    }

    /**
     * SELECTボックス用リストを作成する.
     *
     * @param  string $table       テーブル名
     * @param  string $keyname     プライマリーキーのカラム名
     * @param  string $valname     データ内容のカラム名
     * @param  string $where       WHERE句
     * @param  array  $arrVal プレースホルダ
     * @return array  SELECT ボックス用リストの配列
     */
    public static function sfGetIDValueList($table, $keyname, $valname, $where = '', $arrVal = array())
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "$keyname, $valname";
        $objQuery->setWhere('del_flg = 0');
        $objQuery->setOrder('rank DESC');
        $arrList = $objQuery->select($col, $table, $where, $arrVal);
        $count = count($arrList);
        $arrRet = array();
        for ($cnt = 0; $cnt < $count; $cnt++) {
            $key = $arrList[$cnt][$keyname];
            $val = $arrList[$cnt][$valname];
            $arrRet[$key] = $val;
        }

        return $arrRet;
    }

    /**
     * ランキングを上げる.
     *
     * @param  string         $table    テーブル名
     * @param  string         $colname  カラム名
     * @param  integer $id       テーブルのキー
     * @param  string         $andwhere SQL の AND 条件である WHERE 句
     * @return void
     */
    public function sfRankUp($table, $colname, $id, $andwhere = '')
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $where = "$colname = ?";
        if ($andwhere != '') {
            $where.= " AND $andwhere";
        }
        // 対象項目のランクを取得
        $rank = $objQuery->get('rank', $table, $where, array($id));
        // ランクの最大値を取得
        $maxrank = $objQuery->max('rank', $table, $andwhere);
        // ランクが最大値よりも小さい場合に実行する。
        if ($rank < $maxrank) {
            // ランクが一つ上のIDを取得する。
            $where = 'rank = ?';
            if ($andwhere != '') {
                $where.= " AND $andwhere";
            }
            $uprank = $rank + 1;
            $up_id = $objQuery->get($colname, $table, $where, array($uprank));

            // ランク入れ替えの実行
            $where = "$colname = ?";
            if ($andwhere != '') {
                $where .= " AND $andwhere";
            }

            $sqlval = array(
                'rank' => $rank + 1,
            );
            $arrWhereVal = array($id);
            $objQuery->update($table, $sqlval, $where, $arrWhereVal);

            $sqlval = array(
                'rank' => $rank,
            );
            $arrWhereVal = array($up_id);
            $objQuery->update($table, $sqlval, $where, $arrWhereVal);
        }
        $objQuery->commit();
    }

    /**
     * ランキングを下げる.
     *
     * @param  string         $table    テーブル名
     * @param  string         $colname  カラム名
     * @param  integer $id       テーブルのキー
     * @param  string         $andwhere SQL の AND 条件である WHERE 句
     * @return void
     */
    public function sfRankDown($table, $colname, $id, $andwhere = '')
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $where = "$colname = ?";
        if ($andwhere != '') {
            $where.= " AND $andwhere";
        }
        // 対象項目のランクを取得
        $rank = $objQuery->get('rank', $table, $where, array($id));

        // ランクが1(最小値)よりも大きい場合に実行する。
        if ($rank > 1) {
            // ランクが一つ下のIDを取得する。
            $where = 'rank = ?';
            if ($andwhere != '') {
                $where.= " AND $andwhere";
            }
            $downrank = $rank - 1;
            $down_id = $objQuery->get($colname, $table, $where, array($downrank));

            // ランク入れ替えの実行
            $where = "$colname = ?";
            if ($andwhere != '') {
                $where .= " AND $andwhere";
            }

            $sqlval = array(
                'rank' => $rank - 1,
            );
            $arrWhereVal = array($id);
            $objQuery->update($table, $sqlval, $where, $arrWhereVal);

            $sqlval = array(
                'rank' => $rank,
            );
            $arrWhereVal = array($down_id);
            $objQuery->update($table, $sqlval, $where, $arrWhereVal);
        }
        $objQuery->commit();
    }

    /**
     * 指定順位へ移動する.
     *
     * @param  string         $tableName   テーブル名
     * @param  string         $keyIdColumn キーを保持するカラム名
     * @param  integer $keyId       キーの値
     * @param  integer        $pos         指定順位
     * @param  string         $where       SQL の AND 条件である WHERE 句
     * @return void
     */
    public function sfMoveRank($tableName, $keyIdColumn, $keyId, $pos, $where = '')
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        // 自身のランクを取得する
        if ($where != '') {
            $getWhere = "$keyIdColumn = ? AND " . $where;
        } else {
            $getWhere = "$keyIdColumn = ?";
        }
        $oldRank = $objQuery->get('rank', $tableName, $getWhere, array($keyId));

        $max = $objQuery->max('rank', $tableName, $where);

        // 更新するランク値を取得
        $newRank = $this->getNewRank($pos, $max);
        // 他のItemのランクを調整する
        $ret = $this->moveOtherItemRank($newRank, $oldRank, $objQuery, $tableName, $where);
        if (!$ret) {
            // 他のランク変更がなければ処理を行わない
            return;
        }

        // 指定した順位へrankを書き換える。
        $sqlval = array(
            'rank' => $newRank,
        );
        $str_where = "$keyIdColumn = ?";
        if ($where != '') {
            $str_where .= " AND $where";
        }
        $arrWhereVal = array($keyId);
        $objQuery->update($tableName, $sqlval, $str_where, $arrWhereVal);

        $objQuery->commit();
    }

    /**
     * 指定された位置の値をDB用のRANK値に変換する
     * 指定位置が1番目に移動なら、newRankは最大値
     * 指定位置が1番下へ移動なら、newRankは1
     *
     * @param  int $position 指定された位置
     * @param  int $maxRank  現在のランク最大値
     * @return int $newRank DBに登録するRANK値
     */
    public function getNewRank($position, $maxRank)
    {
        if ($position > $maxRank) {
            $newRank = 1;
        } elseif ($position < 1) {
            $newRank = $maxRank;
        } else {
            $newRank = $maxRank - $position + 1;
        }

        return $newRank;
    }

    /**
     * 指定した順位の商品から移動させる商品までのrankを１つずらす
     *
     * @param  int     $newRank
     * @param  int     $oldRank
     * @param  SC_Query  $objQuery
     * @param string $tableName
     * @param string $addWhere
     * @return boolean
     */
    public function moveOtherItemRank($newRank, $oldRank, &$objQuery, $tableName, $addWhere)
    {
        $sqlval = array();
        $arrRawSql = array();
        $where = 'rank BETWEEN ? AND ?';
        if ($addWhere != '') {
            $where .= " AND $addWhere";
        }
        if ($newRank > $oldRank) {
            //位置を上げる場合、他の商品の位置を1つ下げる（ランクを1下げる）
            $arrRawSql['rank'] = 'rank - 1';
            $arrWhereVal = array($oldRank + 1, $newRank);
        } elseif ($newRank < $oldRank) {
            //位置を下げる場合、他の商品の位置を1つ上げる（ランクを1上げる）
            $arrRawSql['rank'] = 'rank + 1';
            $arrWhereVal = array($newRank, $oldRank - 1);
        } else {
            //入れ替え先の順位が入れ替え元の順位と同じ場合なにもしない
            return false;
        }

        return $objQuery->update($tableName, $sqlval, $where, $arrWhereVal, $arrRawSql);
    }

    /**
     * ランクを含むレコードを削除する.
     *
     * レコードごと削除する場合は、$deleteをtrueにする
     *
     * @param string         $table    テーブル名
     * @param string         $colname  カラム名
     * @param integer $id       テーブルのキー
     * @param string         $andwhere SQL の AND 条件である WHERE 句
     * @param bool           $delete   レコードごと削除する場合 true,
     *                     レコードごと削除しない場合 false
     * @return void
     */
    public function sfDeleteRankRecord($table, $colname, $id, $andwhere = '',
                                $delete = false) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // 削除レコードのランクを取得する。
        $where = "$colname = ?";
        if ($andwhere != '') {
            $where.= " AND $andwhere";
        }
        $rank = $objQuery->get('rank', $table, $where, array($id));

        if (!$delete) {
            // ランクを最下位にする、DELフラグON
            $sqlval = array(
                'rank'      => 0,
                'del_flg'   => 1,
            );
            $where = "$colname = ?";
            $arrWhereVal = array($id);
            $objQuery->update($table, $sqlval, $where, $arrWhereVal);
        } else {
            $objQuery->delete($table, "$colname = ?", array($id));
        }

        // 追加レコードのランクより上のレコードを一つずらす。
        $sqlval = array();
        $where = 'rank > ?';
        if ($andwhere != '') {
            $where .= " AND $andwhere";
        }
        $arrWhereVal = array($rank);
        $arrRawSql = array(
            'rank' => '(rank - 1)',
        );
        $objQuery->update($table, $sqlval, $where, $arrWhereVal, $arrRawSql);
        $objQuery->commit();
    }

    public function sfDeleteRankRecord2($table, $colname, $id, $andwhere = '',
                                $delete = false) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // 削除レコードのランクを取得する。
        $where = "$colname = ?";
        if ($andwhere != '') {
            $where.= " AND $andwhere";
        }
        $rank = $objQuery->get('rank', $table, $where, array($id));

        if (!$delete) {
            // ランクを最下位にする、DELフラグON
            $sqlval = array(
                'rank'      => 0,
                'del_flg'   => 1,
            );
            $where = "$colname = ?";
            $arrWhereVal = array($id);
            $objQuery->update($table, $sqlval, $where, $arrWhereVal);
        } else {
            $objQuery->delete($table, "$colname = ?", array($id));
        }
/* // Delete MGN_20140311
        // 追加レコードのランクより上のレコードを一つずらす。
        $sqlval = array();
        $where = 'rank > ?';
        if ($andwhere != '') {
            $where .= " AND $andwhere";
        }
        $arrWhereVal = array($rank);
        $arrRawSql = array(
            'rank' => '(rank - 1)',
        );
        $objQuery->update($table, $sqlval, $where, $arrWhereVal, $arrRawSql);
*/
        $objQuery->commit();
    }

    /**
     * 親IDの配列を元に特定のカラムを取得する.
     *
     * @param  SC_Query $objQuery SC_Query インスタンス
     * @param  string   $table    テーブル名
     * @param  string   $id_name  ID名
     * @param  string   $col_name カラム名
     * @param  array    $arrId    IDの配列
     * @return array    特定のカラムの配列
     * @deprecated 本体で使用されていないため非推奨
     */
    public function sfGetParentsCol($objQuery, $table, $id_name, $col_name, $arrId)
    {
        $col = $col_name;
        $len = count($arrId);
        $where = '';

        for ($cnt = 0; $cnt < $len; $cnt++) {
            if ($where == '') {
                $where = "$id_name = ?";
            } else {
                $where.= " OR $id_name = ?";
            }
        }

        $objQuery->setOrder('level');
        $arrRet = $objQuery->select($col, $table, $where, $arrId);

        return $arrRet;
    }

    /**
     * カテゴリ変更時の移動処理を行う.
     *
     * ※この関数って、どこからも呼ばれていないのでは？？
     *
     * @param  SC_Query $objQuery  SC_Query インスタンス
     * @param  string   $table     テーブル名
     * @param  string   $id_name   ID名
     * @param  string   $cat_name  カテゴリ名
     * @param  integer  $old_catid 旧カテゴリID
     * @param  integer  $new_catid 新カテゴリID
     * @param  integer  $id        ID
     * @return void
     * @deprecated 本体で使用されていないため非推奨
     */
    public function sfMoveCatRank($objQuery, $table, $id_name, $cat_name, $old_catid, $new_catid, $id)
    {
        if ($old_catid == $new_catid) {
            return;
        }
        // 旧カテゴリでのランク削除処理
        // 移動レコードのランクを取得する。
        $sqlval = array();
        $where = "$id_name = ?";
        $rank = $objQuery->get('rank', $table, $where, array($id));
        // 削除レコードのランクより上のレコードを一つ下にずらす。
        $where = "rank > ? AND $cat_name = ?";
        $arrWhereVal = array($rank, $old_catid);
        $arrRawSql = array(
            'rank' => '(rank - 1)',
        );
        $objQuery->update($table, $sqlval, $where, $arrWhereVal, $arrRawSql);

        // 新カテゴリでの登録処理
        // 新カテゴリの最大ランクを取得する。
        $max_rank = $objQuery->max('rank', $table, "$cat_name = ?", array($new_catid)) + 1;
        $sqlval = array(
            'rank' => $max_rank,
        );
        $where = "$id_name = ?";
        $arrWhereVal = array($id);
        $objQuery->update($table, $sqlval, $where, $arrWhereVal);
    }

    /**
     * レコードの存在チェックを行う.
     *
     * TODO SC_Query に移行するべきか？
     *
     * @param  string $table    テーブル名
     * @param  string $col      カラム名
     * @param  array  $arrVal   要素の配列
     * @param  string  $addwhere SQL の AND 条件である WHERE 句
     * @return bool   レコードが存在する場合 true
     * @deprecated SC_Query::exists() を使用してください
     */
    public static function sfIsRecord($table, $col, $arrVal, $addwhere = '')
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrCol = preg_split('/[, ]/', $col);

        $where = 'del_flg = 0';

        if ($addwhere != '') {
            $where.= " AND $addwhere";
        }

        foreach ($arrCol as $val) {
            if ($val != '') {
                if ($where == '') {
                    $where = "$val = ?";
                } else {
                    $where.= " AND $val = ?";
                }
            }
        }
        $ret = $objQuery->get($col, $table, $where, $arrVal);

        if ($ret != '') {
            return true;
        }

        return false;
    }

    /**
     * メーカー商品数数の登録を行う.
     *
     * @param  SC_Query $objQuery SC_Query インスタンス
     * @return void
     */
    public function sfCountMaker($objQuery)
    {
        //テーブル内容の削除
        $objQuery->query('DELETE FROM dtb_maker_count');

        //各メーカーの商品数を数えて格納
        $sql = ' INSERT INTO dtb_maker_count(maker_id, product_count, create_date) ';
        $sql .= ' SELECT T1.maker_id, count(T2.maker_id), CURRENT_TIMESTAMP ';
        $sql .= ' FROM dtb_maker AS T1 LEFT JOIN dtb_products AS T2';
        $sql .= ' ON T1.maker_id = T2.maker_id ';
        $sql .= ' WHERE T2.del_flg = 0 AND T2.status = 1 ';
        $sql .= ' GROUP BY T1.maker_id, T2.maker_id ';
        $objQuery->query($sql);
    }

    /**
     * 選択中の商品のメーカーを取得する.
     *
     * @param  integer $product_id プロダクトID
     * @param  integer $maker_id   メーカーID
     * @return array   選択中の商品のメーカーIDの配列
     *
     */
    public function sfGetMakerId($product_id, $maker_id = 0, $closed = false)
    {
        if ($closed) {
            $status = '';
        } else {
            $status = 'status = 1';
        }

        if (!$this->g_maker_on) {
            $this->g_maker_on = true;
            $maker_id = (int) $maker_id;
            $product_id = (int) $product_id;
            if (SC_Utils_Ex::sfIsInt($maker_id) && $maker_id != 0 && $this->sfIsRecord('dtb_maker', 'maker_id', $maker_id)) {
                $this->g_maker_id = array($maker_id);
            } elseif (SC_Utils_Ex::sfIsInt($product_id) && $product_id != 0 && $this->sfIsRecord('dtb_products', 'product_id', $product_id, $status)) {
                $objQuery = SC_Query_Ex::getSingletonInstance();
                $maker_id = $objQuery->getCol('maker_id', 'dtb_products', 'product_id = ?', array($product_id));
                $this->g_maker_id = $maker_id;
            } else {
                // 不正な場合は、空の配列を返す。
                $this->g_maker_id = array();
            }
        }

        return $this->g_maker_id;
    }

    /**
     * メーカーの取得を行う.
     *
     * $products_check:true商品登録済みのものだけ取得する
     *
     * @param  string $addwhere       追加する WHERE 句
     * @param  bool   $products_check 商品の存在するカテゴリのみ取得する場合 true
     * @return array  カテゴリツリーの配列
     */
    public function sfGetMakerList($addwhere = '', $products_check = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = 'del_flg = 0';

        if ($addwhere != '') {
            $where.= " AND $addwhere";
        }

        $objQuery->setOption('ORDER BY rank DESC');

        if ($products_check) {
            $col = 'T1.maker_id, name';
            $from = 'dtb_maker AS T1 LEFT JOIN dtb_maker_count AS T2 ON T1.maker_id = T2.maker_id';
            $where .= ' AND product_count > 0';
        } else {
            $col = 'maker_id, name';
            $from = 'dtb_maker';
        }

        $arrRet = $objQuery->select($col, $from, $where);

        $max = count($arrRet);
        $arrList = array();
        for ($cnt = 0; $cnt < $max; $cnt++) {
            $id = $arrRet[$cnt]['maker_id'];
            $name = $arrRet[$cnt]['name'];
            $arrList[$id] = $name;
        }

        return $arrList;
    }

    /**
     * 店舗基本情報に基づいて税金額を返す
     *
     * @param  integer $price 計算対象の金額
     * @return double 税金額
     * @deprecated SC_Helper_TaxRule::sfTax() を使用してください
     */
    public function sfTax($price)
    {
        // 店舗基本情報を取得
        $CONF = SC_Helper_DB_Ex::sfGetBasisData();

        return SC_Utils_Ex::sfTax($price, $CONF['tax'], $CONF['tax_rule']);
    }

    /**
     * 店舗基本情報に基づいて税金付与した金額を返す
     * SC_Utils_Ex::sfCalcIncTax とどちらか統一したほうが良い
     *
     * @param  int $price 計算対象の金額
     * @param  int $tax
     * @param  int $tax_rule
     * @return double 税金付与した金額
     * @deprecated SC_Helper_TaxRule::sfCalcIncTax() を使用してください
     */
    public static function sfCalcIncTax($price, $tax = null, $tax_rule = null)
    {
        // 店舗基本情報を取得
        $CONF = SC_Helper_DB_Ex::sfGetBasisData();
        $tax      = $tax      === null ? $CONF['tax']      : $tax;
        $tax_rule = $tax_rule === null ? $CONF['tax_rule'] : $tax_rule;

        return SC_Utils_Ex::sfCalcIncTax($price, $tax, $tax_rule);
    }

    /**
     * 店舗基本情報に基づいて加算ポイントを返す
     *
     * @param  integer $totalpoint
     * @param  integer $use_point
     * @return integer 加算ポイント
     */
    public static function sfGetAddPoint($totalpoint, $use_point)
    {
        // 店舗基本情報を取得
        $CONF = SC_Helper_DB_Ex::sfGetBasisData();

        return SC_Utils_Ex::sfGetAddPoint($totalpoint, $use_point, $CONF['point_rate']);
    }

    /**
     * 指定ファイルが存在する場合 SQL として実行
     *
     * XXX プラグイン用に追加。将来消すかも。
     *
     * @param  string $sqlFilePath SQL ファイルのパス
     * @return void
     * @deprecated 本体で使用されていないため非推奨
     */
    public function sfExecSqlByFile($sqlFilePath)
    {
        if (file_exists($sqlFilePath)) {
            $objQuery = SC_Query_Ex::getSingletonInstance();

            $sqls = file_get_contents($sqlFilePath);
            if ($sqls === false) trigger_error('ファイルは存在するが読み込めない', E_USER_ERROR);

            foreach (explode(';', $sqls) as $sql) {
                $sql = trim($sql);
                if (strlen($sql) == 0) continue;
                $objQuery->query($sql);
            }
        }
    }

    /**
     * 商品規格を設定しているか
     *
     * @param  integer $product_id 商品ID
     * @return bool    商品規格が存在する場合:true, それ以外:false
     */
    public function sfHasProductClass($product_id)
    {
        if (!SC_Utils_Ex::sfIsInt($product_id)) return false;

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = 'product_id = ? AND del_flg = 0 AND (classcategory_id1 != 0 OR classcategory_id2 != 0)';
        $exists = $objQuery->exists('dtb_products_class', $where, array($product_id));

        return $exists;
    }

    /**
     * 店舗基本情報を登録する
     *
     * @param  array $arrData 登録するデータ
     * @return void
     */
    public static function registerBasisData($arrData)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $arrData = $objQuery->extractOnlyColsOf('dtb_baseinfo', $arrData);

        if (isset($arrData['regular_holiday_ids']) && is_array($arrData['regular_holiday_ids'])) {
            // 定休日をパイプ区切りの文字列に変換
            $arrData['regular_holiday_ids'] = implode('|', $arrData['regular_holiday_ids']);
        }

        $arrData['update_date'] = 'CURRENT_TIMESTAMP';

        // UPDATEの実行
        $ret = $objQuery->update('dtb_baseinfo', $arrData);
        GC_Utils_Ex::gfPrintLog('dtb_baseinfo に UPDATE を実行しました。');

        // UPDATE できなかった場合、INSERT
        if ($ret == 0) {
            $arrData['id'] = 1;
            $objQuery->insert('dtb_baseinfo', $arrData);
            GC_Utils_Ex::gfPrintLog('dtb_baseinfo に INSERT を実行しました。');
        }
    }

    /**
     * レコード件数を計算.
     *
     * @param  string  $table
     * @param  string  $where
     * @param  array   $arrval
     * @return integer レコード件数
     * @deprecated SC_Query::count() を使用してください
     */
    public function countRecords($table, $where = '', $arrval = array())
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = 'COUNT(*)';

        return $objQuery->get($col, $table, $where, $arrval);
    }

	 /**
     * 配送時間を取得する.
     *
     * @param integer $payment_id 支払い方法ID
     * @return array 配送時間の配列
     */
    function sfGetDelivTime($payment_id = "") {
        $objQuery = new SC_Query();

        $deliv_id = "";
        $arrRet = array();

        if($payment_id != "") {
            $where = "del_flg = 0 AND payment_id = ?";
            $arrRet = $objQuery->select("deliv_id", "dtb_payment", $where, array($payment_id));
            $deliv_id = $arrRet[0]['deliv_id'];
        }

        if($deliv_id != "") {
            $objQuery->setorder("time_id");
            $where = "deliv_id = ?";
            $arrRet= $objQuery->select("time_id, deliv_time", "dtb_delivtime", $where, array($deliv_id));
        }

        return $arrRet;
    }

	 /**
     * 注文番号、利用ポイント、加算ポイントから最終ポイントを取得する.
     *
     * @param integer $order_id 注文番号
     * @param integer $use_point 利用ポイント
     * @param integer $add_point 加算ポイント
     * @return array 最終ポイントの配列
     */
    function sfGetCustomerPoint($order_id, $use_point, $add_point) {
        $objQuery = new SC_Query();
        $arrRet = $objQuery->select("customer_id", "dtb_order", "order_id = ?", array($order_id));
        $customer_id = $arrRet[0]['customer_id'];
        if($customer_id != "" && $customer_id >= 1) {
            if (USE_POINT === true) {
                $arrRet = $objQuery->select("point", "dtb_customer", "customer_id = ?", array($customer_id));
                $point = $arrRet[0]['point'];
                $total_point = $arrRet[0]['point'] - $use_point + $add_point;
            } else {
                $total_point = "";
                $point = "";
            }
        } else {
            $total_point = 0;
            $point = 0;
        }
        return array($point, $total_point);
    }

    /**
     * 顧客番号、利用ポイント、加算ポイントから最終ポイントを取得する.
     *
     * @param integer $customer_id 顧客番号
     * @param integer $use_point 利用ポイント
     * @param integer $add_point 加算ポイント
     * @return array 最終ポイントの配列
     */
    function sfGetCustomerPointFromCid($customer_id, $use_point, $add_point) {
        $objQuery = new SC_Query();
        if (USE_POINT === true) {
                $arrRet = $objQuery->select("point", "dtb_customer", "customer_id = ?", array($customer_id));
                $point = $arrRet[0]['point'];
                $total_point = $arrRet[0]['point'] - $use_point + $add_point;
        } else {
            $total_point = 0;
            $point = 0;
        }
        return array($point, $total_point);
    }
	 /**
     * 会員編集登録処理を行う.
     *
     * @param array $array パラメータの配列
     * @param array $arrRegistColumn 登録するカラムの配列
     * @return void
     */
    function sfEditCustomerData($array, $arrRegistColumn) {
        $objQuery = new SC_Query();

        foreach ($arrRegistColumn as $data) {
            if ($data["column"] != "password") {
                if($array[ $data['column'] ] != "") {
                    $arrRegist[ $data["column"] ] = $array[ $data["column"] ];
                } else {
                    $arrRegist[ $data['column'] ] = NULL;
                }
            }
        }
        if (strlen($array["year"]) > 0 && strlen($array["month"]) > 0 && strlen($array["day"]) > 0) {
            $arrRegist["birth"] = $array["year"] ."/". $array["month"] ."/". $array["day"] ." 00:00:00";
        } else {
            $arrRegist["birth"] = NULL;
        }
          /* 20141112 Repair */      
        //-- パスワードの更新がある場合は暗号化。（更新がない場合はUPDATE文を構成しない）
        //if ($array["password"] != DEFAULT_PASSWORD) $arrRegist["password"] = hash_hmac(PASSWORD_HASH_ALGOS, $array["password"] . ':' . AUTH_MAGIC, $array["salt"]);
        if ($array["password"] != DEFAULT_PASSWORD) {
            if (empty($salt)) {               
                $arrRegist["password"] = sha1($array["password"] . ':' . AUTH_MAGIC);
            } else {                                                                     
                $arrRegist["password"] = SC_Utils_Ex::sfGetHashString($array["password"], $array["salt"]);
            }
        }
		$old_version_flag = false;
		// salt値の生成(insert時)または取得(update時)。
        if (is_numeric($array['customer_id'])) {
            $salt = $objQuery->get('salt', 'dtb_customer', 'customer_id = ? ', array($array['customer_id']));

            // 旧バージョン(2.11未満)からの移行を考慮
            if (strlen($salt) === 0) {
                $old_version_flag = true;
            }
        } else {
            $salt = SC_Utils_Ex::sfGetRandomString(10);
            $arrRegist['salt'] = $salt;
        }
        //-- パスワードの更新がある場合は暗号化
        if ($array['password'] == DEFAULT_PASSWORD or $array['password'] == '') {
		
        } else {
            // 旧バージョン(2.11未満)からの移行を考慮
            if ($old_version_flag) {
                $is_password_updated = true;
                $salt = SC_Utils_Ex::sfGetRandomString(10);
                $arrRegist['salt'] = $salt;
            }

            $arrRegist['password'] = SC_Utils_Ex::sfGetHashString($array['password'], $salt);
        }
        /* 20141112 Repair */ 
        $arrRegist["update_date"] = "NOW()";

        //-- 編集登録実行
        $objQuery->begin();
        $objQuery->update("dtb_customer", $arrRegist, "customer_id = ? ", array($array['customer_id']));
        $objQuery->commit();
    }
	
	 /**
     * 商品規格情報を取得する.
     *
     * @param array $arrID 規格ID
     * @return array 規格情報の配列
     */
    function sfGetProductsClass($arrID) {
        list($product_id, $classcategory_id1, $classcategory_id2) = $arrID;

        if($classcategory_id1 == "") {
            $classcategory_id1 = '0';
        }
        if($classcategory_id2 == "") {
            $classcategory_id2 = '0';
        }

        // 商品規格取得
        $objQuery = new SC_Query();
        $col = "product_id, deliv_fee, name, product_code, main_list_image, main_image, price01, price02, point_rate, product_class_id, classcategory_id1, classcategory_id2, class_id1, class_id2, stock, stock_unlimited, sale_limit, sale_unlimited,product_type";
        $table = "vw_product_class AS prdcls";
        $where = "product_id = ? AND classcategory_id1 = ? AND classcategory_id2 = ?";
        $objQuery->setorder("rank1 DESC, rank2 DESC");
        $arrRet = $objQuery->select($col, $table, $where, array($product_id, $classcategory_id1, $classcategory_id2));
        return $arrRet[0];
    }

	 /**
     * カート内商品の集計処理を行う.
     *
     * @param LC_Page $objPage ページクラスのインスタンス
     * @param SC_CartSession $objCartSess カートセッションのインスタンス
     * @param array $arrInfo 商品情報の配列
     * @return LC_Page 集計処理後のページクラスインスタンス
     */
    function sfTotalCart(&$objPage, $objCartSess, $arrInfo, $bln_holiday = false) {

        // 規格名一覧
        $arrClassName = $this->sfGetIDValueList("dtb_class", "class_id", "name");
        // 規格分類名一覧
        $arrClassCatName = $this->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");

        $objPage->tpl_total_pretax = 0;		// 費用合計(税込み)
        $objPage->tpl_total_tax = 0;		// 消費税合計
        if (USE_POINT === true) {
            $objPage->tpl_total_point = 0;		// ポイント合計
        }

        // カート内情報の取得
        $arrCart = $objCartSess->getCartList();
        $max = count($arrCart);
        $cnt = 0;
        $total_pretax = 0;
        $insert_pos = 0;
        $set_cnt = 0;

        for ($i = 0; $i < $max; $i++) {
            // 商品規格情報の取得
            $arrData = $this->sfGetProductsClass($arrCart[$i]['id']);
            $limit = "";
            // DBに存在する商品
            if (count($arrData) > 0) {

                // 購入制限数を求める。
                if ($arrData['stock_unlimited'] != '1' && $arrData['sale_unlimited'] != '1') {
                    if($arrData['sale_limit'] < $arrData['stock']) {
                        $limit = $arrData['sale_limit'];
                    } else {
                        $limit = $arrData['stock'];
                    }
                } else {
                    if ($arrData['sale_unlimited'] != '1') {
                        $limit = $arrData['sale_limit'];
                    }
                    if ($arrData['stock_unlimited'] != '1') {
                        $limit = $arrData['stock'];
                    }
                }

                if($limit != "" && $limit < $arrCart[$i]['quantity']) {
                	$quantity = 1;
                } else {
                    $quantity = $arrCart[$i]['quantity'];
                }

                $objPage->arrProductsClass[$cnt] = $arrData;
                $objPage->arrProductsClass[$cnt]['quantity'] = $quantity;
                $objPage->arrProductsClass[$cnt]['cart_no'] = $arrCart[$i]['cart_no'];
                $objPage->arrProductsClass[$cnt]['class_name1'] =
                    isset($arrClassName[$arrData['class_id1']])
                        ? $arrClassName[$arrData['class_id1']] : "";

                $objPage->arrProductsClass[$cnt]['class_name2'] =
                    isset($arrClassName[$arrData['class_id2']])
                        ? $arrClassName[$arrData['class_id2']] : "";

                $objPage->arrProductsClass[$cnt]['classcategory_name1'] =
                    $arrClassCatName[$arrData['classcategory_id1']];

                $objPage->arrProductsClass[$cnt]['classcategory_name2'] =
                    $arrClassCatName[$arrData['classcategory_id2']];

                // 画像サイズ
                $main_image_path = IMAGE_SAVE_REALDIR . basename($objPage->arrProductsClass[$cnt]["main_image"]);
                if(file_exists($main_image_path)) {
                    list($image_width, $image_height) = getimagesize($main_image_path);
                } else {
                    $image_width = 0;
                    $image_height = 0;
                }

                $objPage->arrProductsClass[$cnt]["tpl_image_width"] = $image_width + 60;
                $objPage->arrProductsClass[$cnt]["tpl_image_height"] = $image_height + 80;
                // 価格の登録
                if ($arrData['price02'] != "") {
                    $objCartSess->setProductValue($arrCart[$i]['id'], 'price', $arrData['price02']);
                    $objPage->arrProductsClass[$cnt]['uniq_price'] = $arrData['price02'];
                } else {
                    $objCartSess->setProductValue($arrCart[$i]['id'], 'price', $arrData['price01']);
                    $objPage->arrProductsClass[$cnt]['uniq_price'] = $arrData['price01'];
                }
                // ポイント付与率の登録
                if (USE_POINT === true) {
                    $objCartSess->setProductValue($arrCart[$i]['id'], 'point_rate', $arrData['point_rate']);
                }

                // 2020.09.10 hori 単品ドレスのときだけ消費税10%で計算
                //if ($arrData['product_type'] === '2') {
                //    $arrInfo['dress_tmp_tax'] = '10';
                //}

                // 商品ごとの合計金額
                $total_pretax_temp = $objCartSess->getProductTotal($arrInfo, $arrCart[$i]['id']);

                // 単品ドレス用の税率削除
                unset($arrInfo['dress_tmp_tax']);

                //セット商品のドレスの商品欄に計算したセット商品価格をセットするため、セット商品の価格を合計する。
                if (!empty($arrCart[$i]['set_pid'])) {
                    $total_pretax += ($bln_holiday)?($total_pretax_temp * 0.1 + $total_pretax_temp) : $total_pretax_temp;
                    //セット商品のドレスの商品欄に計算したセット商品価格をセットするため、位置を記憶する。
                    if ($arrCart[$i]['id'][0] == $arrCart[$i]['set_pid']) {
                        $insert_pos = $cnt;
                        $set_cnt = $arrCart[$i]['set_ptype'];
                        $set_flg = TRUE;
                    }
                } else {
                    $objPage->arrProductsClass[$cnt]['total_pretax'] = ($bln_holiday)?($total_pretax_temp * 0.1 + $total_pretax_temp) : $total_pretax_temp;
                }

                //セット商品にドレスのproduct_idを設定してセット商品の判別をしやすくする
                if ($arrCart[$i]['id'][0] == $objPage->arrProductsClass[$cnt]['product_id']) {
                    $objPage->arrProductsClass[$cnt]['set_pid'] = $arrCart[$i]['set_pid'];
                    $objPage->arrProductsClass[$cnt]['set_ptype'] = $arrCart[$i]['set_ptype'];
                }

				// 送料の合計を計算する
                $objPage->tpl_total_deliv_fee+= ($arrData['deliv_fee'] * $arrCart[$i]['quantity']);
                $cnt++;
            } else {
                // DBに商品が見つからない場合はカート商品の削除
                $objCartSess->delProductKey('id', $arrCart[$i]['id']);
            }
        }

        //add 201812
        $productcode = $objPage->arrProductsClass[$insert_pos]['product_code'];
        $str_cm = substr($productcode, 7,3);
        if ($set_flg) {
            $objPage->arrProductsClass[$insert_pos]['total_pretax'] = $total_pretax;
            $objPage->arrProductsClass[$insert_pos]['set_cnt'] = $set_cnt;
            
            //セレスーツはセットとして認識したいので$cntを３にする add 201812
            if($str_cm){ $cnt += 1; }

            //お届け予定日とご返却日のrowspan用(セット商品はドレスだけ表示させる)
            $objPage->arrProductsClass[0]['view_cnt'] = $cnt-($set_cnt-1);
        } else {
            if ($cnt != 0) {
                //お届け予定日とご返却日のrowspan用
                $objPage->arrProductsClass[0]['view_cnt'] = $cnt;
            }
        }
      
        // 全商品合計金額(税込み)
        //$total_pretax_temp = $objCartSess->getAllProductsTotal($arrInfo); // ishibashi
        $total_pretax_temp = $objCartSess->getAllProductsTotal(); 

        $objPage->tpl_total_pretax = ($bln_holiday)?($total_pretax_temp * 0.1 + $total_pretax_temp) : $total_pretax_temp;
        // 全商品合計消費税
        //$objPage->tpl_total_tax = $objCartSess->getAllProductsTax($arrInfo); // ishibashi
        $objPage->tpl_total_tax = $objCartSess->getAllProductsTax();
        // 全商品合計ポイント
        If (USE_POINT === true) {
          $objPage->tpl_total_point = $objCartSess->getAllProductsPoint();
        }

        return $objPage;
    }

	/**
     * 集計情報を元に最終計算を行う.*
     * @param array $arrData 各種情報
     * @param LC_Page $objPage LC_Page インスタンス
     * @param SC_CartSession $objCartSess SC_CartSession インスタンス
     * @param array $arrInfo 店舗情報の配列
     * @param SC_Customer $objCustomer SC_Customer インスタンス
     * @return array 最終計算後の配列
     */
    // 20200708 ishibashi 2.17では記載なく、calculateで計算処理を行っているため、それを使用したい
    function sfTotalConfirm($arrData, &$objPage, &$objCartSess, $arrInfo, $objCustomer = "") {
        // 未定義変数を定義
        if (!isset($arrData['deliv_pref'])) $arrData['deliv_pref'] = "";
        if (!isset($arrData['payment_id'])) $arrData['payment_id'] = "";
        if (!isset($arrData['charge'])) $arrData['charge'] = "";
        if (!isset($arrData['use_point'])) $arrData['use_point'] = "";

        // 商品の合計個数
        $total_quantity = $objCartSess->getTotalQuantity(true);

        // 税金の取得
        $arrData['tax'] = $objPage->tpl_total_tax;
        // 小計の取得
        $arrData['subtotal'] = $objPage->tpl_total_pretax;

        // 合計送料の取得
        $arrData['deliv_fee'] = 0;

        // 商品ごとの送料が有効の場合
        if (OPTION_PRODUCT_DELIV_FEE == 1) {
            $arrData['deliv_fee']+= $objCartSess->getAllProductsDelivFee();
        }

        // 配送業者の送料が有効の場合
        if (OPTION_DELIV_FEE == 1) {
            // 送料の合計を計算する
            $arrData['deliv_fee'] += $this->sfGetDelivFee($arrData);
        }

        // 送料無料の購入数が設定されている場合
        if(DELIV_FREE_AMOUNT > 0) {
            if($total_quantity >= DELIV_FREE_AMOUNT) {
                $arrData['deliv_fee'] = 0;
            }
        }

        // 送料無料条件が設定されている場合
        if($arrInfo['free_rule'] > 0) {
            // 小計が無料条件を超えている場合
            if($arrData['subtotal'] >= $arrInfo['free_rule']) {
                $arrData['deliv_fee'] = 0;
            }
        }

    	// 同梱場合の処理
        if(isset($_SESSION["cart"]["dongbong_info"]) && $_SESSION["cart"]["dongbong_info"]["flag"]){
            $arrData['deliv_fee'] = 0;
        }
        
        // 合計の計算
        $arrData['total'] = $objPage->tpl_total_pretax;	// 商品合計
        $arrData['total']+= $arrData['deliv_fee'];		// 送料
        $arrData['total']+= $arrData['relief_value'];      // あんしん保証
        $arrData['total']+= $arrData['charge'];			// 手数料
        // お支払い合計
        $arrData['payment_total'] = $arrData['total'] - ($arrData['use_point'] * POINT_VALUE);
        // 加算ポイントの計算
        if (USE_POINT === false) {
            $arrData['add_point'] = 0;
        } else {
            $arrData['add_point'] = SC_Utils::sfGetAddPoint($objPage->tpl_total_point, $arrData['use_point'], $arrInfo['point_rate']);

            if($objCustomer != "") {
                // 誕生日月であった場合
                if($objCustomer->isBirthMonth()) {
                    $arrData['birth_point'] = BIRTH_MONTH_POINT;
                    $arrData['add_point'] += $arrData['birth_point'];
                }
            }
        }

        if($arrData['add_point'] < 0) {
            $arrData['add_point'] = 0;
        }

        return $arrData;
    }

	 /**
     * 都道府県、支払い方法から配送料金を取得する.
     *
     * @param integer $pref 都道府県ID
     * @param integer $payment_id 支払い方法ID
     * @return string 指定の都道府県, 支払い方法の配送料金
     */
    function sfGetDelivFee($arrData) {
        $pref = $arrData['deliv_pref'];
        $payment_id = isset($arrData['payment_id']) ? $arrData['payment_id'] : "";

        $objQuery = new SC_Query();

        $deliv_id = "";

        // 支払い方法が指定されている場合は、対応した配送業者を取得する
        if($payment_id != "") {
            $where = "del_flg = 0 AND payment_id = ?";
            $arrRet = $objQuery->select("deliv_id", "dtb_payment", $where, array($payment_id));
            $deliv_id = $arrRet[0]['deliv_id'];
        // 支払い方法が指定されていない場合は、先頭の配送業者を取得する
        } else {
            $where = "del_flg = 0";
            $objQuery->setOrder("rank DESC");
            $objQuery->setLimitOffset(1);
            $arrRet = $objQuery->select("deliv_id", "dtb_deliv", $where);
            $deliv_id = $arrRet[0]['deliv_id'];
        }

        // 配送業者から配送料を取得
        if($deliv_id != "") {

            // 都道府県が指定されていない場合は、東京都の番号を指定しておく
            if($pref == "") {
                $pref = 13;
            }

            $objQuery = new SC_Query();
            $where = "deliv_id = ? AND pref = ?";
            $arrRet= $objQuery->select("fee", "dtb_delivfee", $where, array($deliv_id, $pref));
        }
        return $arrRet[0]['fee'];
    }
    
    
     //{{add BHM_201403013 
        /* 検品画像情報の読み込み */
    function sfGetInspectImages($image_type = ''){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "image_id, image_name";
        $table = "dtb_inspect_image";
        $where = "del_flg = ?";
        $ary_cond = array(OFF);
        if(!empty($image_type)){
            $where .= " and image_type = ?";
            $ary_cond[] = $image_type;
        }
        $objQuery->setOrder("rank");
        
        $ary_image_data = $objQuery->select($col, $table, $where, $ary_cond);
        
        $ary_result = array();
        foreach ($ary_image_data as $row) {
            $ary_result[$row["image_id"]] = $row["image_name"];
        }
        
        return $ary_result;
    }
    
    /* 商品・検品画像情報の読み込み */
    function sfGetProductsInspectImages($product_id, $image_type = ''){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $table = "dtb_products_inspectimage";
        $where = "product_id = ?  and del_flg = ?";
        $ary_cond = array($product_id, OFF);
        if(!empty($image_type)){
            $where .= " and image_type = ?";
            $ary_cond[] = $image_type;
        }
        
        return $objQuery->select($col, $table, $where, $ary_cond);
    }
    
     /**
     * カテゴリ数の登録を行う.
     *
     * @param SC_Query $objQuery SC_Query インスタンス
     * @return void
     */
    function sfCategory_Count($objQuery){
        $sql = "";

        //テーブル内容の削除
        $objQuery->query("DELETE FROM dtb_category_count");
        $objQuery->query("DELETE FROM dtb_category_total_count");

        //各カテゴリ内の商品数を数えて格納
        $sql = " INSERT INTO dtb_category_count(category_id, product_count, create_date) ";
        $sql .= " SELECT T1.category_id, count(T2.category_id), now() ";
        $sql .= " FROM dtb_category AS T1 LEFT JOIN dtb_product_categories AS T2";
        $sql .= " ON T1.category_id = T2.category_id ";
        $sql .= " LEFT JOIN dtb_products AS T3";
        $sql .= " ON T2.product_id = T3.product_id";
        $sql .= " WHERE T3.del_flg = 0 AND T3.status = 1 ";
        $sql .= " GROUP BY T1.category_id, T2.category_id ";
        $objQuery->query($sql);

        //子カテゴリ内の商品数を集計する
        $arrCat = $objQuery->getAll("SELECT * FROM dtb_category");

        $sql = "";
        foreach($arrCat as $key => $val){

            // 子ID一覧を取得
            $arrRet = $this->sfGetChildrenArray('dtb_category', 'parent_category_id', 'category_id', $val['category_id']);
            $line = SC_Utils_Ex::sfGetCommaList($arrRet);

            $sql = " INSERT INTO dtb_category_total_count(category_id, product_count, create_date) ";
            $sql .= " SELECT ?, SUM(product_count), now() FROM dtb_category_count ";
            $sql .= " WHERE category_id IN (" . $line . ")";

            $objQuery->query($sql, array($val['category_id']));
        }
    }
    //}}add BHM_201403013 


	 /**
     * 受注一時テーブルへの書き込み処理を行う.
     *
     * @param string $uniqid ユニークID
     * @param array $sqlval SQLの値の配列
     * @return void
     */
    function sfRegistTempOrder($uniqid, $sqlval) {

        if($uniqid != "") {          
            // 既存データのチェック
            $objQuery =  SC_Query_Ex::getSingletonInstance();
            $where = "order_temp_id = ?";
            $cnt = $objQuery->count("dtb_order_temp", $where, array($uniqid));
                                       
            // 既存データがない場合
            if ($cnt == 0) {
                // 初回書き込み時に会員の登録済み情報を取り込む
                $sqlval = $this->sfGetCustomerSqlVal($uniqid, $sqlval);
                $sqlval['create_date'] = "now()";
                $objQuery->insert("dtb_order_temp", $sqlval);
            } else { 

                $objQuery->update('dtb_order_temp', $sqlval, $where, array($uniqid));
            }
        }
    }

	 /**
     * 会員情報から SQL文の値を生成する.
     *
     * @param string $uniqid ユニークID
     * @param array $sqlval SQL の値の配列
     * @return array 会員情報を含んだ SQL の値の配列
     */
    function sfGetCustomerSqlVal($uniqid, $sqlval) {
        $objCustomer = new SC_Customer();
        // 会員情報登録処理
        if ($objCustomer->isLoginSuccess(true)) {
            // 登録データの作成
            $sqlval['order_temp_id'] = $uniqid;
            $sqlval['update_date'] = 'Now()';
            $sqlval['customer_id'] = $objCustomer->getValue('customer_id');
            $sqlval['order_name01'] = $objCustomer->getValue('name01');
            $sqlval['order_name02'] = $objCustomer->getValue('name02');
            $sqlval['order_kana01'] = $objCustomer->getValue('kana01');
            $sqlval['order_kana02'] = $objCustomer->getValue('kana02');
            $sqlval['order_sex'] = $objCustomer->getValue('sex');
            $sqlval['order_zip01'] = $objCustomer->getValue('zip01');
            $sqlval['order_zip02'] = $objCustomer->getValue('zip02');
            $sqlval['order_pref'] = $objCustomer->getValue('pref');
            $sqlval['order_addr01'] = $objCustomer->getValue('addr01');
            $sqlval['order_addr02'] = $objCustomer->getValue('addr02');
            $sqlval['order_tel01'] = $objCustomer->getValue('tel01');
            $sqlval['order_tel02'] = $objCustomer->getValue('tel02');
            $sqlval['order_tel03'] = $objCustomer->getValue('tel03');
            if (defined('MOBILE_SITE')) {
                $email_mobile = $objCustomer->getValue('email_mobile');
                if (empty($email_mobile)) {
                    $sqlval['order_email'] = $objCustomer->getValue('email');
                } else {
                    $sqlval['order_email'] = $email_mobile;
                }
            } else {
                $sqlval['order_email'] = $objCustomer->getValue('email');
            }
            $sqlval['order_job'] = $objCustomer->getValue('job');
            $sqlval['order_birth'] = $objCustomer->getValue('birth');
        }
        return $sqlval;
    }

	 /**
     * 受注一時テーブルから情報を取得する.
     *
     * @param integer $order_temp_id 受注一時ID
     * @return array 受注一時情報の配列
     */
    function sfGetOrderTemp($order_temp_id) {
        // ishibashi order_temp_idを決済前と同じにしなければエラー
        $objQuery = new SC_Query();
        $where = "order_temp_id = ?";
        $arrRet = $objQuery->select("*", "dtb_order_temp", $where, array($order_temp_id));
        return $arrRet[0];
    }

	function sfGetPayment() {
        $objQuery = new SC_Query();
        // 購入金額が条件額以下の項目を取得
        $where = "del_flg = 0";
        $objQuery->setorder("fix, rank DESC");
        $arrRet = $objQuery->select("payment_id, payment_method, rule", "dtb_payment", $where);
        return $arrRet;
    }

    // 20200701 ishibashi
    function getOrderTemp($order_id) {
        $objQuery = new SC_Query();
        $where = 'order_id = ?';
        $arrRet = $objQuery->select('*', 'dtb_order_temp', $where, array($order_id));
        return $arrRet[0];
    }
}
