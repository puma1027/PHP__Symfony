<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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

require_once CLASS_REALDIR . 'pages/admin/products/LC_Page_Admin_Products_ProductClass.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';

/**
 * 商品登録(規格) のページクラス(拡張).
 *
 * LC_Page_Admin_Products_ProductClass をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @author REMISE Corp.
 * @version $Id:LC_Page_Admin_Products_Product_Ex.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class LC_Page_Admin_Products_ProductClass_Ex extends LC_Page_Admin_Products_ProductClass
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $objFormParam = new SC_FormParam_Ex();
        $this->arrFirstInterval = array();
        $this->arrFirstInterval['--'] = "--";
        for ($i = 1; $i <= 12; $i++) {
            $this->arrFirstInterval[$i] = $i;
        }
        $this->arrNextdate = array();
        $this->arrNextdate['--'] = "--";
        for ($i = 1; $i <= 31; $i++) {
            $this->arrNextdate[$i] = $i;
        }
        $this->arrInterval = array();
        $this->arrInterval['--'] = "--";
        for ($i = 1; $i <= 12; $i++) {
            $this->arrInterval[$i] = $i;
        }
        $this->arrNotAllow = array();
        $this->arrNotAllow['--'] = "--";
        for ($i = 0; $i <= 12; $i++) {
            $this->arrNotAllow[$i] = $i;
        }
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
        // 商品マスターの検索条件パラメーターを初期化
        $objFormParam = new SC_FormParam_Ex();
        $this->initParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $this->arrSearchHidden = $objFormParam->getSearchArray();

        switch ($this->getMode()) {
            // 編集実行
            case 'edit':
                $this->arrErr = $this->lfCheckProductsClass($objFormParam);
                for ($i = 0; $i < count($_POST["product_type_id"]); $i++) {
                    if ($_POST["product_type_id"][$i] == PRODUCT_TYPE_AC_REMISE ||
                        $_POST["product_type_id"][$i] == PRODUCT_TYPE_AC_REMISE_DL) {
                        $this->initParam_AC($objFormParam);
                        $objFormParam->setParam($_POST);
                        $objFormParam->convParam();
                        $this->arrErr = $this->lfCheckProductsClass($objFormParam);
                    } else {
                        $this->initParam_AC_NoCheck($objFormParam);
                        $objFormParam->setParam($_POST);
                        $objFormParam->convParam();
                        $this->arrErr = $this->lfCheckProductsClass($objFormParam);
                    }
                }
                // エラーの無い場合は確認画面を表示
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $this->tpl_mainpage = 'products/product_class_confirm.tpl';
                    $this->doDisp($objFormParam);
                    $this->fillCheckboxesValue('stock_unlimited', $_POST['total']);
                    $objFormParam->setParam($_POST);
                    $objFormParam->convParam();
                }
                // エラーが発生した場合
                else {
                    $objFormParam->setParam($_POST);
                    $objFormParam->convParam();
                }
                break;

            // 削除
            case 'delete':
                $this->doDelete($objFormParam->getValue('product_id'));
                $objFormParam->setValue('class_id1', '');
                $objFormParam->setValue('class_id2', '');
                $this->doDisp($objFormParam);
                break;

            // 初期表示
            case 'pre_edit':
                $this->doPreEdit_AC($objFormParam);
                break;

            // 「表示する」ボタン押下時
            case 'disp':
                $this->initParam_AC($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->arrErr = $this->lfCheckSelectClass();
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $this->doDisp($objFormParam);
                    $this->initDispParam($objFormParam);
                }
                break;

            // ダウンロード商品ファイルアップロード
            case 'file_upload':
                $this->initParam_AC($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->doFileUpload($objFormParam);
                break;

            // ダウンロードファイルの削除
            case 'file_delete':
                $this->initParam_AC($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->doFileDelete($objFormParam);
                break;

            // 確認画面からの戻り
            case 'confirm_return':
                $this->doPreEdit_AC($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                break;

            case 'complete':
                $this->initParam_AC($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->tpl_mainpage = 'products/product_class_complete.tpl';
                $this->doUploadComplete($objFormParam);
                $this->registerProductClass($objFormParam->getHashArray(),
                                            $objFormParam->getValue('product_id'),
                                            $objFormParam->getValue('total'));
                break;

            default:
                break;
        }

        // 登録対象の商品名を取得
        $objFormParam->setValue('product_name', $this->getProductName($objFormParam->getValue('product_id')));
        $this->arrForm = $objFormParam->getFormParamList();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        parent::destroy();
    }

    /**
     * 規格編集画面を表示する
     *
     * @param integer $product_id 商品ID
     * @param bool $existsValue
     * @param bool $usepostValue
     */
    function doPreEdit_AC(&$objFormParam)
    {
        $objQuery =& SC_Query::getSingletonInstance();
        $product_id = $objFormParam->getValue('product_id');

        $existsProductsClass = $this->getProductsClassFullByProductId_AC($product_id);
        // 規格のデフォルト値(すべての組み合わせ)を取得し, フォームに反映
        // 定期購買の情報がない場合、商品テーブルからの取得を試みる
        if (empty($existsProductsClass[0]['plg_remiseautocharge_total'])) {
            $sql = 'SELECT plg_remiseautocharge_total, plg_remiseautocharge_next_date, plg_remiseautocharge_interval, plg_remiseautocharge_first_interval, plg_remiseautocharge_refusal_not_allow FROM dtb_products WHERE product_id = ?';
            $arrval = $objQuery->getAll($sql, array($$existsProductsClass[0]['product_id']));

            foreach ($arrval[0] as $key => $val) {
                for ($i = 0; $i < count($existsProductsClass); $i++) {
                    $existsProductsClass[$i][$key] = $val;
                }
            }
        }

        $class_id1 = $existsProductsClass[0]['class_id1'];
        $class_id2 = $existsProductsClass[0]['class_id2'];
        $objFormParam->setValue('class_id1', $class_id1);
        $objFormParam->setValue('class_id2', $class_id2);
        $this->initParam_AC($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $this->doDisp($objFormParam);

        // 登録済みのデータで, フォームの値を上書きする.
        $arrKeys = array('classcategory_id1', 'classcategory_id2', 'product_code',
            'classcategory_name1', 'classcategory_name2', 'stock',
            'stock_unlimited', 'price01', 'price02',
            'product_type_id', 'down_filename', 'down_realfilename', 'upload_index', 'tax_rate', 'plg_remiseautocharge_total', 'plg_remiseautocharge_first_interval',
            'plg_remiseautocharge_next_date', 'plg_remiseautocharge_interval', 'plg_remiseautocharge_refusal_not_allow',
        );
        $arrFormValues = $objFormParam->getSwapArray($arrKeys);
        // フォームの規格1, 規格2をキーにした配列を生成
        $arrClassCatKey = array();
        foreach ($arrFormValues as $formValue) {
            $arrClassCatKey[$formValue['classcategory_id1']][$formValue['classcategory_id2']] = $formValue;
        }
        // 登録済みデータをマージ
        foreach ($existsProductsClass as $existsValue) {
            $arrClassCatKey[$existsValue['classcategory_id1']][$existsValue['classcategory_id2']] = $existsValue;
        }

        // 規格のデフォルト値に del_flg をつけてマージ後の1次元配列を生成
        $arrMergeProductsClass = array();
        foreach ($arrClassCatKey as $arrC1) {
            foreach ($arrC1 as $arrValues) {
                $arrValues['del_flg'] = (string) $arrValues['del_flg'];
                if (SC_Utils_Ex::isBlank($arrValues['del_flg']) || $arrValues['del_flg'] === '1') {
                    $arrValues['del_flg'] = '1';
                } else {
                    $arrValues['del_flg'] = '0';
                }

                // 消費税率を設定
                if (OPTION_PRODUCT_TAX_RULE && substr(ECCUBE_VERSION,0,4) != '2.12') {
                    $arrRet = SC_Helper_TaxRule_Ex::getTaxRule($arrValues['product_id'], $arrValues['product_class_id']);
                    $arrValues['tax_rate'] = $arrRet['tax_rate'];
                }
                
                $arrMergeProductsClass[] = $arrValues;
            }
        }

        // 登録済みのデータで上書き
        $objFormParam->setParam(SC_Utils_Ex::sfSwapArray($arrMergeProductsClass));

        // $arrMergeProductsClass で product_id が配列になってしまうため数値で上書き
        $objFormParam->setValue('product_id', $product_id);

        // check を設定
        $arrChecks = array();
        $index = 0;
        foreach ($objFormParam->getValue('del_flg') as $key => $val) {
            if ($val === '0') {
                $arrChecks[$index] = 1;
            }
            $index++;
        }
        $objFormParam->setValue('check', $arrChecks);

        // class_id1, class_id2 を取得値で上書き
        $objFormParam->setValue('class_id1', $class_id1);
        $objFormParam->setValue('class_id2', $class_id2);
    }

    /**
     * ルミーズ定期購買パラメーター初期化
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function initParam_AC(&$objFormParam)
    {
        $objFormParam->addParam("初回課金金額", "plg_remiseautocharge_total",               INT_LEN,    "KVa",  array('EXIST_CHECK', "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("初回課金間隔", "plg_remiseautocharge_first_interval",      INT_LEN,    "KVa",  array("NUM_CHECK"));
        $objFormParam->addParam("次回課金日",   "plg_remiseautocharge_next_date",           INT_LEN,    "KVa",  array("NUM_CHECK"));
        $objFormParam->addParam("決済間隔",     "plg_remiseautocharge_interval",            INT_LEN,    "KVa",  array("NUM_CHECK"));
        $objFormParam->addParam("最低利用期間", "plg_remiseautocharge_refusal_not_allow",   INT_LEN,    "KVa",  array("NUM_CHECK"));
    }

    /**
     * ルミーズ定期購買パラメーター初期化(必須でない場合)
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function initParam_AC_NoCheck(&$objFormParam)
    {
        $objFormParam->addParam("初回課金金額",     "plg_remiseautocharge_total");
        $objFormParam->addParam("初回課金間隔",     "plg_remiseautocharge_first_interval");
        $objFormParam->addParam("次回課金日",       "plg_remiseautocharge_next_date");
        $objFormParam->addParam("決済間隔",         "plg_remiseautocharge_interval");
        $objFormParam->addParam("最低利用期間",     "plg_remiseautocharge_refusal_not_allow");
    }

    /**
     * 規格の登録または更新を行う.
     *
     * @param array $arrList 入力フォームの内容
     * @param integer $product_id 登録を行う商品ID
     */
    function registerProductClass($arrList, $product_id, $total)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();

        $objQuery->begin();

        $arrProductsClass = $objQuery->select('*', 'dtb_products_class', 'product_id = ?', array($product_id));
        $arrExists = array();
        foreach ($arrProductsClass as $val) {
            $arrExists[$val['product_class_id']] = $val;
        }

        // デフォルト値として設定する値を取得しておく
        $arrDefault = $this->getProductsClass($product_id);

        $objQuery->delete('dtb_products_class', 'product_id = ? AND (classcategory_id1 <> 0 OR classcategory_id2 <> 0)', array($product_id));

        for ($i = 0; $i < $total; $i++) {
            $del_flg = SC_Utils_Ex::isBlank($arrList['check'][$i]) ? 1 : 0;
            $stock_unlimited = SC_Utils_Ex::isBlank($arrList['stock_unlimited'][$i]) ? 0 : $arrList['stock_unlimited'][$i];
            $price02 = SC_Utils_Ex::isBlank($arrList['price02'][$i]) ? 0 : $arrList['price02'][$i];
            // dtb_products_class 登録/更新用
            $registerKeys = array(
                'classcategory_id1', 'classcategory_id2',
                'product_code', 'stock', 'price01', 'product_type_id',
                'down_filename', 'down_realfilename', 'plg_remiseautocharge_total', 'plg_remiseautocharge_first_interval',
                'plg_remiseautocharge_next_date', 'plg_remiseautocharge_interval', 'plg_remiseautocharge_refusal_not_allow',
            );

            $arrPC = array();
            foreach ($registerKeys as $key) {
                $arrPC[$key] = $arrList[$key][$i];
            }
            $arrPC['product_id'] = $product_id;
            $arrPC['sale_limit'] = $arrDefault['sale_limit'];
            $arrPC['deliv_fee'] = $arrDefault['deliv_fee'];
            $arrPC['point_rate'] = $arrDefault['point_rate'];
            $arrPC['stock_unlimited'] = $stock_unlimited;
            $arrPC['price02'] = $price02;

            // 該当関数が無いため, セッションの値を直接代入
            $arrPC['creator_id'] = $_SESSION['member_id'];
            $arrPC['update_date'] = 'CURRENT_TIMESTAMP';
            $arrPC['del_flg'] = $del_flg;

            $arrPC['create_date'] = 'CURRENT_TIMESTAMP';
            // 更新の場合は, product_class_id を使い回す
            if (!SC_Utils_Ex::isBlank($arrList['product_class_id'][$i])) {
                $arrPC['product_class_id'] = $arrList['product_class_id'][$i];
            } else {
                $arrPC['product_class_id'] = $objQuery->nextVal('dtb_products_class_product_class_id');
            }

            // チェックを入れない商品は product_type_id が NULL になるので, 0 を入れる
            $arrPC['product_type_id'] = SC_Utils_Ex::isBlank($arrPC['product_type_id']) ? 0 : $arrPC['product_type_id'];

            $objQuery->insert('dtb_products_class', $arrPC);

            // 税情報登録/更新
            if (OPTION_PRODUCT_TAX_RULE && substr(ECCUBE_VERSION,0,4) != '2.12') {
                SC_Helper_TaxRule_Ex::setTaxRuleForProduct($arrList['tax_rate'][$i], $arrPC['product_id'], $arrPC['product_class_id']);
            }
        }

        // 規格無し用の商品規格を非表示に
        $arrBlank['del_flg'] = 1;
        $arrBlank['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->update('dtb_products_class', $arrBlank,
                          'product_id = ? AND classcategory_id1 = 0 AND classcategory_id2 = 0',
                           array($product_id));

        // 件数カウントバッチ実行
        $objDb->sfCountCategory($objQuery);
        $objQuery->commit();
    }

    /**
     * SC_Query インスタンスに設定された検索条件を使用して商品規格を取得する.
     *
     * @param SC_Query $objQuery SC_Queryインスタンス
     * @param array $params 検索パラメーターの配列
     * @return array 商品規格の配列
     */
    function getProductsClassByQuery_AC(&$objQuery, $params)
    {
        // 末端の規格を取得
        $col = <<< __EOS__
            T1.product_id,
            T1.stock,
            T1.stock_unlimited,
            T1.sale_limit,
            T1.price01,
            T1.price02,
            T1.point_rate,
            T1.product_code,
            T1.product_class_id,
            T1.del_flg,
            T1.product_type_id,
            T1.down_filename,
            T1.down_realfilename,
            T1.plg_remiseautocharge_total,
            T1.plg_remiseautocharge_first_interval,
            T1.plg_remiseautocharge_next_date,
            T1.plg_remiseautocharge_interval,
            T1.plg_remiseautocharge_refusal_not_allow,
            T3.name AS classcategory_name1,
            T3.rank AS rank1,
            T4.name AS class_name1,
            T4.class_id AS class_id1,
            T1.classcategory_id1,
            T1.classcategory_id2,
            dtb_classcategory2.name AS classcategory_name2,
            dtb_classcategory2.rank AS rank2,
            dtb_class2.name AS class_name2,
            dtb_class2.class_id AS class_id2
__EOS__;
        $table = <<< __EOS__
            dtb_products_class T1
            LEFT JOIN dtb_classcategory T3
                ON T1.classcategory_id1 = T3.classcategory_id
            LEFT JOIN dtb_class T4
                ON T3.class_id = T4.class_id
            LEFT JOIN dtb_classcategory dtb_classcategory2
                ON T1.classcategory_id2 = dtb_classcategory2.classcategory_id
            LEFT JOIN dtb_class dtb_class2
                ON dtb_classcategory2.class_id = dtb_class2.class_id
__EOS__;

        $objQuery->setOrder('T3.rank DESC, dtb_classcategory2.rank DESC'); // XXX
        $arrRet = $objQuery->select($col, $table, '', $params);

        return $arrRet;
    }

    /**
     * 商品規格IDから商品規格を取得する.
     *
     * 削除された商品規格は取得しない.
     *
     * @param integer $productClassId 商品規格ID
     * @return array 商品規格の配列
     */
    function getProductsClass_AC($productClassId)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->setWhere('product_class_id = ? AND T1.del_flg = 0');
        $arrRes = $this->getProductsClassByQuery_AC($objQuery, $productClassId);
        return (array)$arrRes[0];
    }

    /**
     * 複数の商品IDに紐づいた, 商品規格を取得する.
     *
     * @param array $productIds 商品IDの配列
     * @param boolean $has_deleted 削除された商品規格も含む場合 true; 初期値 false
     * @return array 商品規格の配列
     */
    function getProductsClassByProductIds_AC($productIds = array(), $has_deleted = false)
    {
        if (empty($productIds)) {
            return array();
        }
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = 'product_id IN (' . SC_Utils_Ex::repeatStrWithSeparator('?', count($productIds)) . ')';
        if (!$has_deleted) {
            $where .= ' AND T1.del_flg = 0';
        }
        $objQuery->setWhere($where);
        return $this->getProductsClassByQuery_AC($objQuery, $productIds);
    }

    /**
     * 商品IDに紐づいた, 商品規格をすべての組み合わせごとに取得する.
     *
     * @param array $productId 商品ID
     * @param boolean $has_deleted 削除された商品規格も含む場合 true; 初期値 false
     * @return array すべての組み合わせの商品規格の配列
     */
    function getProductsClassFullByProductId_AC($productId, $has_deleted = false)
    {
        $arrRet = $this->getProductsClassByProductIds_AC(array($productId), $has_deleted);
        return $arrRet;
    }

    /**
     * 商品規格エラーチェック.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return array エラー結果の配列
     */
    function lfCheckProductsClass(&$objFormParam)
    {
        $arrValues = $objFormParam->getHashArray();
        $arrErr = $objFormParam->checkError();
        $total = $objFormParam->getValue('total');

        if (SC_Utils_Ex::isBlank($arrValues['check'])) {
            $arrErr['check_empty'] = '※ 規格が選択されていません。<br />';
        }

        for ($i = 0; $i < $total; $i++) {
            // チェックボックスの入っている項目のみ, 必須チェックを行う.
            if (!SC_Utils_Ex::isBlank($arrValues['check'][$i])) {
                // 定期購買ダウンロード商品の必須チェック
                if ($arrValues['product_type_id'][$i] != PRODUCT_TYPE_AC_REMISE_DL && $arrValues['product_type_id'][$i] != PRODUCT_TYPE_AC_REMISE) {
                    if (!SC_Utils_Ex::isBlank($arrValues['plg_remiseautocharge_total'][$i])) {
                        $arrErr['plg_remiseautocharge_total'][$i] = '※ 定期購買商品でない場合、入力しないでください。<br />';
                    }
                    if ($arrValues['plg_remiseautocharge_next_date'][$i] != '--') {
                        $arrErr['plg_remiseautocharge_next_date'][$i] = '※ 定期購買商品でない場合、入力しないでください。<br />';
                    }
                    if ($arrValues['plg_remiseautocharge_first_interval'][$i] != '--') {
                        $arrErr['plg_remiseautocharge_first_interval'][$i] = '※ 定期購買商品でない場合、入力しないでください。<br />';
                    }
                    if ($arrValues['plg_remiseautocharge_interval'][$i] != '--') {
                        $arrErr['plg_remiseautocharge_interval'][$i] = '※ 定期購買商品でない場合、入力しないでください。<br />';
                    }
                    if ($arrValues['plg_remiseautocharge_refusal_not_allow'][$i] != '--') {
                        $arrErr['plg_remiseautocharge_refusal_not_allow'][$i] = '※ 定期購買商品でない場合、入力しないでください。<br />';
                    }
                }
                // 定期購買ダウンロード商品の必須チェック
                if ($arrValues['product_type_id'][$i] == PRODUCT_TYPE_AC_REMISE_DL) {
                    if (SC_Utils_Ex::isBlank($arrValues['down_filename'][$i])) {
                        $arrErr['down_filename'][$i] = '※ 定期購買のダウンロード商品の場合はダウンロードファイル名を入力してください。<br />';
                    }
                    if (SC_Utils_Ex::isBlank($arrValues['down_realfilename'][$i])) {
                        $arrErr['down_realfilename'][$i] = '※ 定期購買のダウンロード商品の場合はダウンロード商品用ファイルをアップロードしてください。<br />';
                    }
                }
                // 定期購買通常商品チェック
                else if ($arrValues['product_type_id'][$i] == PRODUCT_TYPE_AC_REMISE) {
                    if (!SC_Utils_Ex::isBlank($arrValues['down_filename'][$i])) {
                        $arrErr['down_filename'][$i] = '※ 通常の定期購買商品の場合はダウンロードファイル名を設定できません。<br />';
                    }
                    if (!SC_Utils_Ex::isBlank($arrValues['down_realfilename'][$i])) {
                        $arrErr['down_realfilename'][$i] = '※ 実商品の定期購買の場合はダウンロード商品用ファイルをアップロードできません。<br />ファイルを取り消してください。<br />';
                    }
                }
                // 販売価格の必須チェック
                if (SC_Utils_Ex::isBlank($arrValues['price02'][$i])) {
                    $arrErr['price02'][$i] = '※ ' . SALE_PRICE_TITLE . 'が入力されていません。<br />';
                }
                // 在庫数の必須チェック
                if ((SC_Utils_Ex::isBlank($arrValues['stock_unlimited'][$i]) || $arrValues['stock_unlimited'][$i] != 1)
                    && SC_Utils_Ex::isBlank($arrValues['stock'][$i])) {
                    $arrErr['stock'][$i] = '※ 在庫数が入力されていません。<br />';
                }
                // 消費税率の必須チェック
                if (OPTION_PRODUCT_TAX_RULE && SC_Utils_Ex::isBlank($arrValues['tax_rate'][$i]) && substr(ECCUBE_VERSION,0,4) != '2.12') {
                    $arrErr['tax_rate'][$i] = '※ 消費税率が入力されていません。<br />';
                }
                // 商品種別の必須チェック
                if (SC_Utils_Ex::isBlank($arrValues['product_type_id'][$i])) {
                    $arrErr['product_type_id'][$i] = '※ 商品種別は、いずれかを選択してください。<br />';
                }
                // ダウンロード商品の必須チェック
                if ($arrValues['product_type_id'][$i] == PRODUCT_TYPE_DOWNLOAD) {
                    if (SC_Utils_Ex::isBlank($arrValues['down_filename'][$i])) {
                        $arrErr['down_filename'][$i] = '※ ダウンロード商品の場合はダウンロードファイル名を入力してください。<br />';
                    }
                    if (SC_Utils_Ex::isBlank($arrValues['down_realfilename'][$i])) {
                        $arrErr['down_realfilename'][$i] = '※ ダウンロード商品の場合はダウンロード商品用ファイルをアップロードしてください。<br />';
                    }
                }
                // 通常商品チェック
                else if ($arrValues['product_type_id'][$i] == PRODUCT_TYPE_NORMAL) {
                    if (!SC_Utils_Ex::isBlank($arrValues['down_filename'][$i])) {
                        $arrErr['down_filename'][$i] = '※ 通常商品の場合はダウンロードファイル名を設定できません。<br />';
                    }
                    if (!SC_Utils_Ex::isBlank($arrValues['down_realfilename'][$i])) {
                        $arrErr['down_realfilename'][$i] = '※ 実商品の場合はダウンロード商品用ファイルをアップロードできません。<br />ファイルを取り消してください。<br />';
                    }
                }
            }
        }
        return $arrErr;
    }

    /**
     * 規格の組み合わせ一覧を表示する.
     *
     * 規格1, 規格2における規格分類のすべての組み合わせを取得し,
     * 該当商品の商品規格の内容を取得後, フォームに設定する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function doDisp(&$objFormParam)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $product_id = $objFormParam->getValue('product_id');
        $class_id1 = $objFormParam->getValue('class_id1');
        $class_id2 = $objFormParam->getValue('class_id2');

        // すべての組み合わせを取得し, フォームに設定
        $arrClassCat = $this->getAllClassCategory($class_id1, $class_id2);
        $total = count($arrClassCat);
        $objFormParam->setValue('total', $total);
        $objFormParam->setParam(SC_Utils_Ex::sfSwapArray($arrClassCat));

        // class_id1, class_id2 を, 入力値で上書き
        $objFormParam->setValue('class_id1', $class_id1);
        $objFormParam->setValue('class_id2', $class_id2);

        // 商品情報を取得し, フォームに設定
        $arrProductsClass = $this->getProductsClass($product_id);
        if (empty($_POST['plg_remiseautocharge_total'][0])) {
            $sql = 'SELECT plg_remiseautocharge_total, plg_remiseautocharge_next_date, plg_remiseautocharge_interval, plg_remiseautocharge_first_interval, plg_remiseautocharge_refusal_not_allow FROM dtb_products WHERE product_id = ?';
            $arrdata = $objQuery->getAll($sql, array($_POST['product_id']));
            $arrProductsClass += $arrdata[0];
        }
        foreach ($arrProductsClass as $key => $val) {
            // 組み合わせ数分の値の配列を生成する
            $arrValues = array();
            for ($i = 0; $i < $total; $i++) {
                $arrValues[] = $val;
            }
            $objFormParam->setValue($key, $arrValues);
        }
        // 商品種別を 1 に初期化
        $objFormParam->setValue('product_type_id', array_pad(array(), $total, 1));
    }
}
?>
