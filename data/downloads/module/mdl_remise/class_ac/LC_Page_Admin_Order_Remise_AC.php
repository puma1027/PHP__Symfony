<?php
/**
 * ルミーズ決済モジュール　定期購買一覧管理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version LC_Page_Admin_Order_Remise_AC.php,v 3.1
 *
 */

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
// ルミーズ決済モジュール
if (file_exists(MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php')) {
    require_once(MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php');
    require_once(MODULE_REALDIR . 'mdl_remise/class/extsetcard.php');
    require_once(MODULE_REALDIR . 'mdl_remise/inc/include.php');
}

/**
 * 定期購買の受注一覧のクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class LC_Page_Admin_Order_Remise_AC extends LC_Page_Admin_Ex
{
    function LC_Page_Admin_Order_Remise_AC()
    {
    }

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        global $arrACStatus;
        $this->tpl_mainpage = MODULE_REALDIR . 'mdl_remise/tmp_ac/remise_ac_order.tpl';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '定期購買管理';
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrORDERSTATUS = $masterData->getMasterData("mtb_order_status");
        $this->arrORDERSTATUS_COLOR = $masterData->getMasterData("mtb_order_status_color");
        $this->arrACStatus = $arrACStatus;
        $this->arrSex = $masterData->getMasterData("mtb_sex");
        $this->arrPageMax = $masterData->getMasterData("mtb_page_max");

        $objDate = new SC_Date_Ex();
        // 登録・更新日検索用
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y') + 2);
        $this->arrRegistYear = $objDate->getYear();
        // 月日の設定
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();

        // 支払い方法の取得
        $this->arrPayments = SC_Helper_DB_Ex::sfGetIDValueList("dtb_payment", "payment_id", "payment_method");

        $this->httpCacheControl('nocache');

        $this->arrSubnavi = array(
            1 => 'product',
            2 => 'customer',
            3 => 'order',
            4 => 'review',
            5 => 'category',
        );
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $this->arrHidden = $objFormParam->getSearchArray();
        $this->arrForm = $objFormParam->getFormParamList();

        switch ($this->getMode()) {
            // 削除
            case 'delete':
                $this->doDelete('order_id = ?', array($objFormParam->getValue('order_id')));
                // 削除後に検索結果を表示するため breakしない

            // 検索パラメーター生成後に処理実行するため breakしない
            case 'csv':

            // 検索パラメーターの生成
            case 'search':
                $objFormParam->convParam();
                $objFormParam->trimParam();
                $this->arrErr = $this->lfCheckError($objFormParam);
                $arrParam = $objFormParam->getHashArray();

                if (count($this->arrErr) == 0) {
                    // クエリの構築
                    list($where, $arrval, $order) = $this->lfGetQueryParam($objFormParam);
                    $order = 'update_date DESC';

                    // 処理を実行
                    switch ($this->getMode()) {
                        // CSVを送信する。
                        case 'csv':
                            $this->doOutputCSV($where, $arrval, $order);
                            exit;
                            break;

                        // 検索実行
                        default:
                            // 行数の取得
                            $this->tpl_linemax = $this->getNumberOfLines($where, $arrval);
                            // ページ送りの処理
                            $page_max = SC_Utils_Ex::sfGetSearchPageMax($objFormParam->getValue('search_page_max'));
                            // ページ送りの取得
                            $objNavi = new SC_PageNavi_Ex(
                                $this->arrHidden['search_pageno'], $this->tpl_linemax,
                                $page_max, 'fnNaviSearchPage', NAVI_PMAX);
                            $this->arrPagenavi = $objNavi->arrPagenavi;

                            // 検索結果の取得
                            $this->arrResults = $this->findOrders(
                                $where, $arrval, $page_max, $objNavi->start_row, $order);
                            break;
                    }
                }
                break;

            default:
        }
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam)
    {
        $objFormParam->addParam("注文番号1",                "search_order_id1",     INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("注文番号2",                "search_order_id2",     INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("対応状況",                 "search_order_status",  INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("会員番号",                 "search_customer_id",   INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("メンバーID",               "search_member_id",     REMISE_MEMBER_ID_LEN,   'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("注文者 お名前",            "search_order_name",    STEXT_LEN,              'KVa',  array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("注文者 お名前(フリガナ)",  "search_order_kana",    STEXT_LEN,              'KVCa', array("KANA_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("メールアドレス",           "search_order_email",   STEXT_LEN,              'KVa',  array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam('TEL',                      "search_order_tel",     STEXT_LEN,              'KVa',  array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("表示件数",                 "search_page_max",      INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        // 受注日
        $objFormParam->addParam("開始年",                   "search_sorderyear",    INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("開始月",                   "search_sordermonth",   INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("開始日",                   "search_sorderday",     INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("終了年",                   "search_eorderyear",    INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("終了月",                   "search_eordermonth",   INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("終了日",                   "search_eorderday",     INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        // 次回課金日
        $objFormParam->addParam("開始年",                   "search_snextyear",     INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("開始月",                   "search_snextmonth",    INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("開始日",                   "search_snextday",      INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("終了年",                   "search_enextyear",     INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("終了月",                   "search_enextmonth",    INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("終了日",                   "search_enextday",      INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("購入商品",                 "search_product_name",  STEXT_LEN,              'KVa',  array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("購入商品コード",           "search_product_code",  STEXT_LEN,              'KVa',  array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("ページ送り番号",           "search_pageno",        INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("受注ID",                   "order_id",             INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("変更先ステータスID",       "change_order_status",  INT_LEN,                'n',    array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("定期課金ステータスID",     "search_ac_status");
    }

    /**
     * 入力内容のチェックを行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfCheckError(&$objFormParam)
    {
        $objErr = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();

        // 相関チェック
        $objErr->doFunc(array("注文番号1", "注文番号2", "search_order_id1", "search_order_id2"), array("GREATER_CHECK"));
        // 受注日
        $objErr->doFunc(array("開始", "search_sorderyear", "search_sordermonth", "search_sorderday"), array("CHECK_DATE"));
        $objErr->doFunc(array("終了", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_DATE"));
        $objErr->doFunc(array("開始", "終了", "search_sorderyear", "search_sordermonth", "search_sorderday", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_SET_TERM"));
        // 次回課金日
        $objErr->doFunc(array("開始", "search_snextyear", "search_snextmonth", "search_snextday"), array("CHECK_DATE"));
        $objErr->doFunc(array("終了", "search_enextyear", "search_enextmonth", "search_enextday"), array("CHECK_DATE"));
        $objErr->doFunc(array("開始", "終了", "search_snextyear", "search_snextmonth", "search_snextday", "search_enextyear", "search_enextmonth", "search_enextday"), array("CHECK_SET_TERM"));
        return $objErr->arrErr;
    }

    /**
     * 検索クエリパラメーターの構築
     *
     * 検索条件のキーに応じた WHERE 句と, クエリパラメーターを構築する.
     * クエリパラメーターは, SC_FormParam の入力値から取得する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return array($where, $arrVal, $order)
     */
    function lfGetQueryParam(&$objFormParam)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrRemisePaymentIds = $objQuery->getCol('payment_id', 'dtb_payment', "module_code = ? and memo03 = ? and del_flg = 0", array(MDL_REMISE_CODE, PAY_REMISE_CREDIT));
        $arrParam = $objFormParam->getHashArray();
        $where = "dtb_order.del_flg = 0 and (memo10 = 'autocharge' or memo10 = 'stop')";
        $where .= " AND payment_id IN (" . implode(",", $arrRemisePaymentIds) . ")";

        foreach ($arrParam as $key => $val) {
            if ($val == "") {
                continue;
            }
            $this->lfBuildQuery($key, $where, $arrVal, $objFormParam);
        }
        $order = "dtb_order.create_date DESC";
        return array($where, $arrVal, $order);
    }

    /**
     * クエリを構築する.
     *
     * 検索条件のキーに応じた WHERE 句と, クエリパラメーターを構築する.
     * クエリパラメーターは, SC_FormParam の入力値から取得する.
     *
     * 構築内容は, 引数の $where 及び $arrValues にそれぞれ追加される.
     *
     * @param string $key 検索条件のキー
     * @param string $where 構築する WHERE 句
     * @param array $arrValues 構築するクエリパラメーター
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfBuildQuery($key, &$where, &$arrValues, &$objFormParam)
    {
        $dbFactory = SC_DB_DBFactory_Ex::getInstance();

        switch ($key) {
            case 'search_product_name':
                $where .= " AND EXISTS (SELECT 1 FROM dtb_order_detail od WHERE od.order_id = dtb_order.order_id AND od.product_name LIKE ?)";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_product_code':
                $where .= " AND EXISTS (SELECT 1 FROM dtb_order_detail od WHERE od.order_id = dtb_order.order_id AND od.product_code LIKE ?)";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_name':
                $where .= " AND " . $dbFactory->concatColumn(array("order_name01", "order_name02")) . " LIKE ?";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_kana':
                $where .= " AND " . $dbFactory->concatColumn(array("order_kana01", "order_kana02")) . " LIKE ?";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_id1':
                $where .= " AND order_id >= ?";
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_id2':
                $where .= " AND order_id <= ?";
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_tel':
                $where .= " AND (" . $dbFactory->concatColumn(array("order_tel01", "order_tel02", "order_tel03")) . " LIKE ?)";
                $arrValues[] = sprintf('%%%d%%', preg_replace('/[()-]+/', '', $objFormParam->getValue($key)));
                break;
            case 'search_order_email':
                $where .= " AND order_email LIKE ?";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_sorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sorderyear'),
                                                    $objFormParam->getValue('search_sordermonth'),
                                                    $objFormParam->getValue('search_sorderday'));
                $where.= " AND dtb_order.create_date >= ?";
                $arrValues[] = $date;
                break;
            case 'search_eorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eorderyear'),
                                                    $objFormParam->getValue('search_eordermonth'),
                                                    $objFormParam->getValue('search_eorderday'), true);
                $where.= " AND dtb_order.create_date <= ?";
                $arrValues[] = $date;
                break;
            case 'search_snextyear':
                $date = $objFormParam->getValue('search_snextyear') . '年'
                      . str_pad($objFormParam->getValue('search_snextmonth'), 2, "0", STR_PAD_LEFT) . '月'
                      . str_pad($objFormParam->getValue('search_snextday'), 2, "0", STR_PAD_LEFT) . '日';
                $where .= " AND dtb_order.plg_remiseautocharge_next_date >= ?";
                $arrValues[] = $date;
                break;
            case 'search_enextyear':
                $date = $objFormParam->getValue('search_enextyear') . '年'
                      . str_pad($objFormParam->getValue('search_enextmonth'), 2, "0", STR_PAD_LEFT) . '月'
                      . str_pad($objFormParam->getValue('search_enextday'), 2, "0", STR_PAD_LEFT) . '日';
                $where .= " AND dtb_order.plg_remiseautocharge_next_date <= ?";
                $arrValues[] = $date;
                break;
            case 'search_ac_status':
                $where .= " AND dtb_order.memo10 = ?";
                $arrValues[] = $objFormParam->getValue($key);
                break;
            case 'search_customer_id':
                $where .= " AND customer_id LIKE ?";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_member_id':
                $where .= " AND plg_remiseautocharge_member_id LIKE ?";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            default:
                break;
        }
    }

    /**
     * 受注を削除する.
     *
     * @param string $where 削除対象の WHERE 句
     * @param array $arrParam 削除対象の値
     * @return void
     */
    function doDelete($where, $arrParam = array())
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $sqlval['del_flg'] = 1;
        $sqlval['update_date'] = 'now()';
        $objQuery->update("dtb_order", $sqlval, $where, $arrParam);
    }

    /**
     * CSV データを構築して取得する.
     *
     * 構築に成功した場合は, ファイル名と出力内容を配列で返す.
     * 構築に失敗した場合は, false を返す.
     *
     * @param string $where 検索条件の WHERE 句
     * @param array $arrVal 検索条件のパラメーター
     * @param string $order 検索結果の並び順
     * @return void
     */
    function doOutputCSV($where, $arrVal, $order)
    {
        require_once CLASS_EX_REALDIR . 'helper_extends/SC_Helper_CSV_Ex.php';
        if ($where != "") {
            $where = " WHERE $where ";
        }
        $this->sfDownloadCsv_AC("3", $where, $arrVal, $order, true);
    }

    /**
     * 検索結果の行数を取得する.
     *
     * @param string $where 検索条件の WHERE 句
     * @param array $arrValues 検索条件のパラメーター
     * @return integer 検索結果の行数
     */
    function getNumberOfLines($where, $arrValues)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        return $objQuery->count('dtb_order', $where, $arrValues);
    }

    /**
     * 受注を検索する.
     *
     * @param string $where 検索条件の WHERE 句
     * @param array $arrValues 検索条件のパラメーター
     * @param integer $limit 表示件数
     * @param integer $offset 開始件数
     * @param string $order 検索結果の並び順
     * @return array 受注の検索結果
     */
    function findOrders($where, $arrValues, $limit, $offset, $order)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $masterData = new SC_DB_MasterData_Ex();
        $arrPref = $masterData->getMasterData("mtb_pref", array("id", "name", "rank"));

        $objQuery->setLimitOffset($limit, $offset);
        $objQuery->setOrder($order);
        $results =  $objQuery->select('*', 'dtb_order', $where, $arrValues);
        $id = $objQuery->select('order_id', 'dtb_order', $where, $arrValues);
        $i = 0;
        while ($id[$i]) {
            if ($ids == "") {
                $ids = $id[$i]["order_id"];
            } else {
                $ids .= "," . $id[$i]["order_id"];
            }
            $i = $i + 1;
        }
        if ($ids != "") {
            $sql = "SELECT dtb_products.name, dtb_order.order_id FROM dtb_order LEFT OUTER JOIN dtb_order_detail ON dtb_order.order_id = dtb_order_detail.order_id LEFT OUTER JOIN dtb_products ON dtb_order_detail.product_id = dtb_products.product_id WHERE dtb_order_detail.order_id in (" . $ids . ")";
            $result = $objQuery->getAll($sql);
        }
        $j = 0;
        $line = $_REQUEST["search_page_max"] < $this->tpl_linemax ? $_POST["search_page_max"] : $this->tpl_linemax;
        while ($result[$j]) {
            for ($k = 0; $k < $line; $k++) {
                if ($results[$k]["order_id"] == $result[$j]["order_id"]) {
                    $results[$k]["name"] = $result[$j]["name"];
                }
            }
            $j++;
        }

        $m = 0;
        while ($results[$m]) {
            $results[$m]["pref_name"] = $arrPref[$results[$m]["order_pref"]];
            if ($results[$m]["memo10"] == 'autocharge') {
                $results[$m]["status"] = '継続中';
            }
            else if ($results[$m]["memo10"] == 'stop') {
                $results[$m]["status"] = '停止';
            }
            $m++;
        }
        return $results;
    }

    /**
     * CSVファイルを送信する(ルミーズ定期購買情報追加)
     *
     * @param  integer $csv_id      CSVフォーマットID
     * @param  string  $where       WHERE条件文
     * @param  array   $arrVal      プリペアドステートメントの実行時に使用される配列。配列の要素数は、クエリ内のプレースホルダの数と同じでなければなりません。
     * @param  string  $order       ORDER文
     * @param  boolean $is_download true:ダウンロード用出力までさせる false:CSVの内容を返す(旧方式、メモリを食います。）
     * @return mixed   $is_download = true時 成功失敗フラグ(boolean) 、$is_downalod = false時 string
     */
    function sfDownloadCsv_AC($csv_id, $where = '', $arrVal = array(), $order = '', $is_download = false)
    {
        // 実行時間を制限しない
        @set_time_limit(0);
        $objCSV = new SC_Helper_CSV();
        // CSV出力タイトル行の作成
        $arrOutput = SC_Utils_Ex::sfSwapArray($objCSV->sfGetCsvOutput($csv_id, 'status = ' . CSV_COLUMN_STATUS_FLG_ENABLE));
        if (count($arrOutput) <= 0) return false; // 失敗終了
        $arrOutputCols = $arrOutput['col'];
        $count = count($arrOutputCols);
        $add_cols = array("plg_remiseautocharge_total", "plg_remiseautocharge_next_date", "plg_remiseautocharge_interval", "plg_remiseautocharge_member_id", "memo10");
        for ($i = 0; $i < count($add_cols); $i++) {
            $arrOutputCols[$count + $i] = $add_cols[$i];
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->setOrder($order);
        $cols = SC_Utils_Ex::sfGetCommaList($arrOutputCols, true);
        $sql = 'SELECT ' . $cols . ' FROM dtb_order ' . $where;

        $add_header = array('定期購買金額', '次回課金日', '決済間隔（ヶ月）', 'メンバーID', '定期課金の状態');
        $arrHeader = array_merge($arrOutput['disp_name'], $add_header);
        // 固有処理ここまで
        return $objCSV->sfDownloadCsvFromSql($sql, $arrVal, $this->arrSubnavi[$csv_id], $arrHeader, $is_download);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        if (substr(ECCUBE_VERSION,0,4) == '2.12') {
            parent::destroy();
        }
    }
}
?>
