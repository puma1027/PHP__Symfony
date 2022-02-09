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

    // {{{ requires
    require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");
    require_once(CLASS_REALDIR . "SC_Order_PikingList_Pdf.php");
    require_once(CLASS_REALDIR . "SC_Order_DressAccessorySeal_Pdf.php");//::N00062 Add 20130528
    /**
     * 受注管理ピッキングリスト のページクラス.
     *
     * @package Page
     * @author  EC-CUBE CO.,LTD.
     * @version $Id$
     */
    class LC_Page_Admin_Order_Picking_List extends LC_Page_Admin_Ex
    {

        // }}}
        // {{{ functions

        /**
         * Page を初期化する.
         *
         * @return void
         */
        function init()
        {
            parent::init();
            $this->tpl_mainpage = 'order/picking_list.tpl';
//            $this->tpl_subnavi = 'order/subnavi.tpl';
            $this->tpl_mainno = 'order';
            $this->tpl_subno = 'picking_list';
            $this->tpl_pager = 'pager.tpl';
            $this->tpl_maintitle = '受注管理';
            $this->tpl_subtitle = 'ピッキングリスト';

            $masterData = new SC_DB_MasterData_Ex();
            $this->arrORDERSTATUS = $masterData->getMasterData("mtb_order_status");
            $this->arrORDERSTATUS_COLOR = $masterData->getMasterData("mtb_order_status_color");
            $this->arrSex = $masterData->getMasterData("mtb_sex");
            $this->arrPageMax = $masterData->getMasterData("mtb_page_max");

            // カテゴリの読込
            $objDb = new SC_Helper_DB();
            list($this->arrCatVal, $this->arrCatOut) = $objDb->sfGetLevelCatList(false);

            array_unshift($this->arrCatVal, '0');
            array_unshift($this->arrCatOut, 'すべて');

            /* ペイジェント決済モジュール連携用 */
            if (function_exists("sfPaygentOrderPage")) {
                $this->arrDispKind = sfPaygentOrderPage();
            }

            // お届け曜日取得用
            $this->arrWday = $masterData->getMasterData("mtb_wday");

        }

        /**
         * Page のプロセス.
         *
         * @return void
         */
        function process()
        {
            $objView = new SC_AdminView();
            $objDb = new SC_Helper_DB_Ex();
            $objSess = new SC_Session();
            // パラメータ管理クラス
            $this->objFormParam = new SC_FormParam();
            // パラメータ情報の初期化
            $this->lfInitParam();
            $this->objFormParam->setParam($_POST);

            $this->objFormParam->splitParamCheckBoxes('search_order_sex');
            $this->objFormParam->splitParamCheckBoxes('search_payment_id');

            // 検索ワードの引き継ぎ
            foreach ($_POST as $key => $val)
            {
                if (preg_match("/^search_/", $key))
                {
                    switch ($key) {
                        case 'search_order_sex':
                        case 'search_payment_id':
                            $this->arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
                            break;
                        default:
                            $this->arrHidden[$key] = $val;
                            break;
                    }
                }
            }

            // ページ送り用
            $this->arrHidden['search_pageno'] =
                isset($_POST['search_pageno']) ? $_POST['search_pageno'] : "";

            // 認証可否の判定
            SC_Utils_Ex::sfIsSuccess($objSess);

            if (!isset($_POST['mode'])) $_POST['mode'] = "";
            if (!isset($arrRet)) $arrRet = array();

            if ($_POST['mode'] == 'delete') {
                if (SC_Utils_Ex::sfIsInt($_POST['order_id'])) {
                    $objQuery = new SC_Query();
                    $where = "order_id = ?";
                    $sqlval['del_flg'] = '1';
                    $objQuery->update("dtb_order", $sqlval, $where, array($_POST['order_id']));
                }
            }

            switch ($_POST['mode']) {
                case 'delete':
                case 'csv':
                case 'pdf':
                case 'seal'://::N00062 Add 20130617
                case 'delete_all':
                case 'search':
                    // 入力値の変換
                    $this->objFormParam->convParam();
                    $this->arrErr = $this->lfCheckError($arrRet);
                    // 入力なし
                    if (count($this->arrErr) == 0) {
                        $this->search_process();
                    }
                    break;
                default:
                    break;
            }

            $objDate = new SC_Date();
            // 登録・更新日検索用
            $objDate->setStartYear(RELEASE_YEAR);
            $objDate->setEndYear(DATE("Y"));
            $this->arrRegistYear = $objDate->getYear();
            // 生年月日検索用
            $objDate->setStartYear(BIRTH_YEAR);
            $objDate->setEndYear(DATE("Y"));
            $this->arrBirthYear = $objDate->getYear();
            // 月日の設定
            $this->arrMonth = $objDate->getMonth();
            $this->arrDay = $objDate->getDay();

            // 入力値の取得
            $this->arrForm = $this->objFormParam->getFormParamList();

            $search_category_ids = $_POST['search_category_id_unselect'];
            if ($search_category_ids) {
                $this->arrForm['search_category_id'] = $search_category_ids;
            }

            $search_category_vals = explode(",", $_POST['search_category_value']);
            $this->selected_categorys = "";
            foreach ($search_category_vals as $val) {
                $index = 0;
                foreach ($this->arrCatVal as $val1) {
                    if ($val == $val1) {
                        //$this->arrCatOut
                        if ($this->selected_categorys == "") {
                            $this->selected_categorys = $this->arrCatOut[$index];
                        } else {
                            $this->selected_categorys .= "<br/>" . $this->arrCatOut[$index];
                        }
                    }
                    $index++;
                }
            }

            // 支払い方法の取得
            $arrRet = $objDb->sfGetPayment();
            $this->arrPayment = SC_Utils_Ex::sfArrKeyValue($arrRet, 'payment_id', 'payment_method');

            // =============== 2012.05.16 RCHJ Add ================
            $str_temp = "[";
            for ($i = 0; $i <= $_REQUEST["search_send_date_index"]; $i++) {
                $str_temp .= "'" . (isset($_REQUEST["search_txt_send_date" . $i]) ? $_REQUEST["search_txt_send_date" . $i] : '') . "',";
            }
            $str_temp = trim($str_temp, ",");
            $str_temp .= "];";
            $this->tpl_javascript .= "var send_date_value = " . $str_temp;
            // =============== end ================

            $objView->assignobj($this);
            $objView->display(MAIN_FRAME);
        }


        function lfCSVDownload($data)
        {

            if (mb_internal_encoding() == CHAR_CODE) {
                $data = mb_convert_encoding($data, 'SJIS-Win', CHAR_CODE);
            }

            /* データを出力 */
            echo $data;
        }

        function search_process()
        {

            $arrRet = $this->objFormParam->getHashArray();

            $new_where = 'dtb_order.del_flg = 0 ';
            $new_in = '';
            $arrCsvSearchTitle = array();
            $arrCsvSearchValue = array();

            foreach ($arrRet as $key => $val) {
                if ($val == "") {
                    continue;
                }
                $val = SC_Utils_Ex::sfManualEscape($val);

                switch ($key) {
                    case 'search_order_name':
                        if (DB_TYPE == "pgsql") {
                            $new_where .= " AND dtb_order.order_name01||dtb_order.order_name02 ILIKE ?";
                        } elseif (DB_TYPE == "mysql") {
                            $new_where .= " AND concat(dtb_order.order_name01,dtb_order.order_name02) ILIKE ?";
                        }
                        $nonsp_val = preg_replace("/[ 　]+/u", "", $val);
                        $arrval[] = "%$nonsp_val%";
                        $arrCsvSearchTitle[] = "顧客名";
                        $arrCsvSearchValue[] = $nonsp_val;
                        break;
                    case 'search_order_kana':
                        if (DB_TYPE == "pgsql") {
                            $new_where .= " AND dtb_order.order_kana01||dtb_order.order_kana02 ILIKE ?";
                        } elseif (DB_TYPE == "mysql") {
                            $new_where .= " AND concat(dtb_order.order_kana01,dtb_order.order_kana02) ILIKE ?";
                        }
                        $nonsp_val = preg_replace("/[ 　]+/u", "", $val);
                        $arrval[] = "%$nonsp_val%";
                        $arrCsvSearchTitle[] = "顧客名（カナ）";
                        $arrCsvSearchValue[] = $nonsp_val;
                        break;
                    case 'search_order_id1':
                        $new_where .= " AND dtb_order.order_id >= ?";
                        $arrval[] = $val;
                        $arrCsvSearchTitle[] = "注文番号1";
                        $arrCsvSearchValue[] = $val;
                        break;
                    case 'search_order_id2':
                        $new_where .= " AND dtb_order.order_id <= ?";
                        $arrval[] = $val;
                        $arrCsvSearchTitle[] = "注文番号2";
                        $arrCsvSearchValue[] = $val;
                        break;
                    case 'search_order_sex':
                        $tmp_where = "";
                        $tmp_str = array();
                        foreach ($val as $element) {
                            if ($element != "") {
                                if ($tmp_where == "") {
                                    $tmp_where .= " AND (dtb_order.order_sex = ?";
                                } else {
                                    $tmp_where .= " OR dtb_order.order_sex = ?";
                                }
                                $arrval[] = $element;
                                if ($element == 1) {
                                    $tmp_str[] = "男性";
                                } else if ($element == 2) {
                                    $tmp_str[] = "女性";
                                } else{
                                    //$tmp_str[] = "女性";
                                }
                            }
                        }

                        $arrCsvSearchTitle[] = "性別";
                        $arrCsvSearchValue[] = join('、',$tmp_str);

                        if ($tmp_where != "") {
                            $tmp_where .= ")";
                            $new_where .= " $tmp_where ";
                        }
                        break;
                    case 'search_order_tel':
                        if (DB_TYPE == "pgsql") {
                            $new_where .= " AND (dtb_order.order_tel01 || dtb_order.order_tel02 || dtb_order.order_tel03) LIKE ?";
                        } elseif (DB_TYPE == "mysql") {
                            $new_where .= " AND concat(dtb_order.order_tel01,dtb_order.order_tel02,dtb_order.order_tel03) LIKE ?";
                        }
                        $nonmark_val = preg_replace("/[()-]+/", "", $val);
                        $arrval[] = "%$nonmark_val%";
                        $arrCsvSearchTitle[] = "TEL";
                        $arrCsvSearchValue[] = $nonmark_val;
                        break;
                    case 'search_order_email':
                        $new_where .= " AND dtb_order.order_email ILIKE ?";
                        $arrval[] = "%$val%";
                        $arrCsvSearchTitle[] = "メールアドレス";
                        $arrCsvSearchValue[] = $val;
                        break;
                    case 'search_payment_id':
                        $tmp_where = "";
                        $tmp = '';
                        foreach ($val as $element) {
                            if ($element != "") {
                                if ($tmp_where == "") {
                                    $tmp_where .= " AND (dtb_order.payment_id = ?";
                                } else {
                                    $tmp_where .= " OR dtb_order.payment_id = ?";
                                }
                                $arrval[] = $element;
                                if ($tmp == "") {
                                    if ($element == 5) {
                                        $tmp[] = "クレジット";
                                    }else if($element == 7){
                                        $tmp[] = 'クレジット(ゆうパックでお届け)';
                                    } else {
                                        $tmp[] = $element;
                                    }
                                } else {
                                    if ($element == 5) {
                                        $tmp[] = 'クレジット';
                                    }else if($element == 7){
                                        $tmp[] = 'クレジット(ゆうパックでお届け)';
                                    } else {
                                        $tmp[] =  $element;
                                    }
                                }

                            }
                        }
                        $arrCsvSearchTitle[] = '支払方法';
                        $arrCsvSearchValue[] = join('、',$tmp);

                        if ($tmp_where != "") {
                            $tmp_where .= ")";
                            $new_where .= " $tmp_where ";
                        }
                        break;
                    case 'search_total1':
                        $new_where .= " AND dtb_order.total >= ?";
                        $arrval[] = $val;
                        $arrCsvSearchTitle[] = "購入金額 	円 1";
                        $arrCsvSearchValue[] = $val;
                        break;
                    case 'search_total2':
                        $new_where .= " AND dtb_order.total <= ?";
                        $arrval[] = $val;
                        $arrCsvSearchTitle[] = "購入金額 	円 2";
                        $arrCsvSearchValue[] = $val;
                        break;
                    case 'search_sorderyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_sorderyear'], $_POST['search_sordermonth'], $_POST['search_sorderday']);
                        $csvdate = date("Y年m月d日", strtotime($_POST['search_sorderyear'] . '-' . $_POST['search_sordermonth'] . '-' . $_POST['search_sorderday']));
                        $new_where .= " AND dtb_order.create_date >= ?";
                        $arrval[] = $date;
                        $arrCsvSearchTitle[] = "受注日1";
                        $arrCsvSearchValue[] = $csvdate;
                        ;
                        break;
                    case 'search_eorderyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_eorderyear'], $_POST['search_eordermonth'], $_POST['search_eorderday'], true);
                        $csvdate = date("Y年m月d日", strtotime($_POST['search_eorderyear'] . '-' . $_POST['search_eordermonth'] . '-' . $_POST['search_eorderday']));
                        $new_where .= " AND dtb_order.create_date <= ?";
                        $arrval[] = $date;
                        $arrCsvSearchTitle[] = "受注日2";
                        $arrCsvSearchValue[] = $csvdate;
                        break;
                    case 'search_supdateyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_supdateyear'], $_POST['search_supdatemonth'], $_POST['search_supdateday']);
                        $csvdate = date("Y年m月d日", strtotime($_POST['search_supdateyear'] . '-' . $_POST['search_supdatemonth'] . '-' . $_POST['search_supdateday']));
                        $new_where .= " AND dtb_order.update_date >= ?";
                        $arrval[] = $date;
                        $arrCsvSearchTitle[] = "更新日1";
                        $arrCsvSearchValue[] = $csvdate;
                        break;
                    case 'search_eupdateyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_eupdateyear'], $_POST['search_eupdatemonth'], $_POST['search_eupdateday'], true);
                        $csvdate = date("Y年m月d日", strtotime($_POST['search_eupdateyear'] . '-' . $_POST['search_eupdatemonth'] . '-' . $_POST['search_eupdateday']));
                        $new_where .= " AND dtb_order.update_date <= ?";
                        $arrval[] = $date;
                        $arrCsvSearchTitle[] = "更新日2";
                        $arrCsvSearchValue[] = $csvdate;
                        break;
                    case 'search_sbirthyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_sbirthyear'], $_POST['search_sbirthmonth'], $_POST['search_sbirthday']);
                        $csvdate = date("Y年m月d日", strtotime($_POST['search_sbirthyear'] . '-' . $_POST['search_sbirthmonth'] . '-' . $_POST['search_sbirthday']));
                        $new_where .= " AND dtb_order.order_birth >= ?";
                        $arrval[] = $date;
                        $arrCsvSearchTitle[] = "生年月日1";
                        $arrCsvSearchValue[] = $csvdate;
                        break;
                    case 'search_ebirthyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_ebirthyear'], $_POST['search_ebirthmonth'], $_POST['search_ebirthday'], true);
                        $csvdate = date("Y年m月d日", strtotime($_POST['search_ebirthyear'] . '-' . $_POST['search_ebirthmonth'] . '-' . $_POST['search_ebirthday']));
                        $new_where .= " AND dtb_order.order_birth <= ?";
                        $arrval[] = $date;
                        $arrCsvSearchTitle[] = "生年月日2";
                        $arrCsvSearchValue[] = $csvdate;
                        break;
                    case 'search_order_status':
                        $new_where .= " AND dtb_order.status = ?";
                        $arrval[] = $val;
                        $arrCsvSearchTitle[] = '対応状況';
                        $arrCsvSearchValue[] = $this->arrORDERSTATUS[$val];
                        break;
// =============== 2012.05.16 RCHJ Add ================
                    case 'search_txt_send_date0':
                        $new_where .= " AND (dtb_order.sending_date = ?";
                        $arrval[] = $val;
                        $strVal = $val;
                        for ($i = 1; $i < $_REQUEST["search_send_date_index"]; $i++) {
                            $new_where .= " OR dtb_order.sending_date = ? ";
                            $arrval[] = $arrRet["search_txt_send_date" . $i];
                            $strVal .= ',' . $val;
                        }
                        $new_where .= ") ";
                        $arrCsvSearchTitle[] = "発送日";
                        $arrCsvSearchValue[] = $strVal;
                        break;
// ======================== End ================
                    case 'search_product_code':
                        $new_where .= " AND dtb_products_class.product_code like ?";
                        $arrval[] = '%' . $val . '%';
                        $arrCsvSearchTitle[] = "商品コード";
                        $arrCsvSearchValue[] = $val;
                        break;
                    case 'search_order_five_day':
                        if ($val == 1) {
                            $new_in = '  Inner Join dtb_order_detail As D2 on (dtb_order_detail.product_id=D2.product_id
                                and dtb_order_detail.order_id<>D2.order_id)
                            Inner Join dtb_order AS O2 On  D2.order_id = O2.order_id ';

                            $add_where = ' and dtb_order.sending_date < CURRENT_DATE
                            and O2.sending_date > CURRENT_DATE
                            and O2.sending_date - dtb_order.sending_date < 7 ';

							$add_where = ' and O2.del_flg = 0 and O2.sending_date is not null
								and O2.sending_date - dtb_order.sending_date >0
                            and O2.sending_date - dtb_order.sending_date < 7 ';

                            $cnt_where = ' Where EE.max_diff < 7 ';

                            $arrCsvSearchTitle[] = "中5日間後";
                            $arrCsvSearchValue[] = '次の予約が中5日間後の商品';
                        }

                        break;
                    default:
                        if (!isset($arrval)) $arrval = array();
                        break;
                }
            }

            $search_category_vals = explode(",", $_POST['search_category_value']);
            $selected_categorys = "";
            foreach ($search_category_vals as $val) {
                $index = 0;
                foreach ($this->arrCatVal as $val1) {
                    if ($val == $val1) {
                        if ($selected_categorys == "") {
                            $selected_categorys = $this->arrCatOut[$index];
                        } else {
                            $selected_categorys .= "\r\n" . $this->arrCatOut[$index];
                        }
                    }
                    $index++;
                }
            }
            if ($selected_categorys != "") {
                $arrCsvSearchTitle[] = "商品カテゴリ";
                $arrCsvSearchValue[] = $selected_categorys;
            }

            $search_category_ids = $_POST['search_category_value'];

            if ($search_category_ids != null && $search_category_ids != "") {
                if ((strpos($search_category_ids, "0,") !== false && strpos($search_category_ids, "0,") === 0) ||
                    ($search_category_ids == "0")
                ) {

                } else {
                    $new_where .= ' AND dtb_product_categories.category_id in (' . $search_category_ids . ')';
                }
            }

            $new_count_sql = 'SELECT count(*)
            From (Select distinct(dtb_order.order_id) As order_id
                    From dtb_order_detail
                    Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                    Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                    Inner Join dtb_products_class On dtb_products.product_id = dtb_products_class.product_id
                    Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                        ' . $new_in . '
                    Where ' . $new_where . $add_where . '
            ) AS T ';

            $objQuery = new SC_Query();

            // 行数の取得
            $linemax = $objQuery->getone($new_count_sql, $arrval);

            ////////
            if (empty($new_in)) {
                $new_type_count_sql = 'Select product_type,product_code, count(*) as cnt
                    From (
                        Select distinct(dtb_order_detail.product_id), dtb_order.order_id, dtb_products.product_type,dtb_products_class.product_code
                        From dtb_order_detail
                            Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                            Inner Join dtb_products_class On dtb_order_detail.product_id = dtb_products_class.product_id
                            left join dtb_products On dtb_products.product_id =dtb_products_class.product_id
                            Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products.product_id
                        Where ' . $new_where . '
                    )  As EE
                    Group By product_type,product_code
                    Order By product_type';
            } else {
                $new_type_count_sql = 'Select product_type,product_code, count(*) as cnt
                From
                (
                    Select D3.product_id, D3.order_id as order_id,
                        max(D3.product_type) as product_type,
                        min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date-D3.sending_date ELSE 99999 END ) as max_diff
                    From
                    (
                        Select dtb_order_detail.product_id, dtb_order.order_id, dtb_order.sending_date,dtb_products.product_type,dtb_products_class.product_code
                        From dtb_order_detail
                        Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                        Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                        Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                            and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                            and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                        Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                        ' . $new_in . '
                        Where ' . $new_where . $add_where . '
                    ) As D3
                    Left join
                    (
                        Select D2.product_id, O2.order_id, O2.sending_date
                        From dtb_order_detail as D2
                        Inner Join dtb_order AS O2 On D2.order_id=O2.order_id
                        Where O2.del_flg <>1 AND O2.sending_date is not NULL
                    ) As D4
                    ON (D3.product_id=D4.product_id and D3.order_id<>D4.order_id)
                    Group By D3.product_id, D3.order_id
                )  As EE
                ' . $cnt_where . '
                Group By product_type,product_code
                Order By product_type ';
            }

            $type_count = $objQuery->getall($new_type_count_sql, $arrval);
            $this->type_count['dress'] = 0;
            $this->type_count['stole'] = 0;
            $this->type_count['necklace'] = 0;
            $this->type_count['bag'] = 0;

            foreach ($type_count as $item) {
                //::N00083 Change 20131201
                if ($item['product_type'] == ONEPIECE_PRODUCT_TYPE || $item['product_type'] == DRESS_PRODUCT_TYPE) {
                    $this->type_count['dress'] += $item['cnt'];
                } else if ($item['product_type'] == DRESS3_PRODUCT_TYPE) {
                    $this->type_count['dress'] += $item['cnt'];
                    $this->type_count['stole'] += $item['cnt'];
                    $this->type_count['necklace'] += $item['cnt'];
                } else if ($item['product_type'] == DRESS4_PRODUCT_TYPE) {
                    $this->type_count['dress'] += $item['cnt'];
                    $this->type_count['stole'] += $item['cnt'];
                    $this->type_count['necklace'] += $item['cnt'];
                    $this->type_count['bag'] += $item['cnt'];
                } else if ($item['product_type'] == SET_DRESS_PRODUCT_TYPE) {
                    $this->type_count['dress'] += $item['cnt'];
                } else if ($item['product_type'] == STOLE_PRODUCT_TYPE) {
                    $this->type_count['stole'] += $item['cnt'];
                } else if ($item['product_type'] == NECKLACE_PRODUCT_TYPE || $item['product_type'] == OTHERS_PRODUCT_TYPE) {
                    if (strpos($item['product_code'],PCODE_BAG) === false) {
                    $this->type_count['necklace'] += $item['cnt'];
                    } else {
                        $this->type_count['bag'] += $item['cnt'];
                    }
                }
                //::N00083 end 20131201
            }

            switch ($_POST['mode']) {
                case 'csv':
                    $search = SC_Utils_Ex::sfGetCSVList($arrCsvSearchTitle);
                    $searchval = SC_Utils_Ex::sfGetCSVList($arrCsvSearchValue);
                    //"顧客名",
                    $arrCsvOutputTitle = array("注文番号", "商品コード", "商品名　ドレス・ワンピース（" . $this->type_count['dress'] . "件）",
                        "商品コード", "商品名　ストール・ボレロ（" . $this->type_count['stole'] . "件）",
                        "商品コード", "商品名　ネックレス・小物（" . $this->type_count['necklace'] . "件）",
                        "商品コード", "商品名　バッグ（" . $this->type_count['bag'] . "件）");
                    $head = SC_Utils_Ex::sfGetCSVList($arrCsvOutputTitle);

                    $prefix = "";

                    if ($prefix == "") {
                        $dir_name = SC_Utils::sfUpDirName();
                        $file_name = $dir_name . date("ymdHis") . ".csv";
                    } else {
                        $file_name = $prefix . date("ymdHis") . ".csv";
                    }

                    /* HTTPヘッダの出力 */
                    Header("Content-disposition: attachment; filename=${file_name}");
                    Header("Content-type: application/octet-stream; name=${file_name}");
                    Header("Cache-Control: ");
                    Header("Pragma: ");

                    // CSVを送信する。
                    $this->lfCSVDownload($search . $searchval . $head);

                    $CSV_STEP = 1000;
                    for ($i = 0; $i < $linemax; $i = $i + $CSV_STEP) {
                        $new_csv_sql = 'Select (D3.order_id) as order_id,D3.product_id,
                        max(D3.product_type) as product_type, max(D3.product_code) as product_code, max(D3.name) as product_name, max(D3.status) as status,
                        max(D3.sending_date) as sending_date,

                        max(D3.set_pcode_stole) as set_pcode_stole, max(D3.set_pcode_necklace) as set_pcode_necklace, max(D3.set_pcode_bag) as set_pcode_bag,
                        max(D3.set_pid) as set_pid,max(D3.set_ptype) as set_ptype,

                        min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date ELSE null END ) as sending_date1,
                        max(CASE when D3.sending_date > D4.sending_date THEN D4.sending_date ELSE null END ) as sending_date2,
                        min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date-D3.sending_date ELSE 99999 END ) as diff1,
                        min(CASE when D3.sending_date > D4.sending_date THEN D3.sending_date-D4.sending_date ELSE 99999 END ) as diff2
                    From
                    (
                        Select S1.*
                        From
                        (
                            Select dtb_order_detail.product_id, dtb_order.order_id, dtb_order.sending_date, dtb_products.name, dtb_products.product_type,
                                    dtb_products_class.product_code, dtb_order.status

                                    , dtb_products_class.set_pcode_stole, dtb_products_class.set_pcode_necklace, dtb_products_class.set_pcode_bag
                                    , dtb_order_detail.set_pid, dtb_order_detail.set_ptype

                            From dtb_order_detail
                            Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                            Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                            Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                                    and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                                    and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                            Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                            Where ' . $new_where . '
                        ) As S1
                        inner join
                        (
                            Select distinct(dtb_order.order_id) As order_id
                            From dtb_order_detail
                            Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                            Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                            Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                                    and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                                    and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                            Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                            ' . $new_in . '
                            Where ' . $new_where . $add_where . '
                            order by dtb_order.order_id DESC LIMIT ' . $CSV_STEP . ' OFFSET ' . $i . '
                        ) As S2 On S1.order_id=S2.order_id
                    ) As D3
                    Left join
                    (
                        Select D2.product_id, O2.order_id, O2.sending_date
                        From dtb_order_detail as D2
                        Inner Join dtb_order AS O2 On D2.order_id=O2.order_id
                        Where O2.del_flg <>1 and O2.status not in (6,8)
                    ) As D4
                    on (D3.product_id=D4.product_id and D3.order_id<>D4.order_id)
                    Group By D3.product_id, D3.order_id
                    order by order_id Desc, set_pid, product_code;';

                        // 検索結果の取得
                        $results = $objQuery->getall($new_csv_sql, array_merge($arrval,$arrval));
                        $cur_order_id = 0;
                        $cur_no = -1;
                        $order_start_no = 0;
                        $order_product_count = 0;
                        $arrResults = array();
                        foreach ($results as $row) {
                            $infive = -1;
                            if (!empty($row['sending_date']) ) {
                                $infive = -1;
                                if($row['status']!=6 && $row['status']!=8){
                                    if (  $row['diff1'] > 0 && $row['diff1'] < 7  &&  $row['diff2'] > 0 && $row['diff2'] < 7 ) {
                                        $infive = 2;//green
                                    } else if ( $row['diff1'] > 0 && $row['diff1'] < 7 ) {
                                        $infive = 1;//red
                                    } else if ( $row['diff2'] > 0 && $row['diff2'] < 7 ) {
                                        $infive = 0;//blue
                                    }
                                }
                            }

                            if (!empty($_POST['search_order_five_day']) && $infive < 1) {
                                continue;
                            }
                            $val = $row['order_id'];

                            if ($val != $cur_order_id) {
                                $cur_no++;
                                $arrResults[$cur_no]['order_id'] = $row['order_id'];
                                //                    $arrResults[$cur_no]['order_name'] = $row['order_name01'].'　'.$row['order_name02'];
                                $arrResults[$cur_no]['product_code1'] = '';
                                $arrResults[$cur_no]['product_name1'] = '';
                                $arrResults[$cur_no]['product_code2'] = '';
                                $arrResults[$cur_no]['product_name2'] = '';
                                $arrResults[$cur_no]['product_code3'] = '';
                                $arrResults[$cur_no]['product_name3'] = '';
                                $arrResults[$cur_no]['product_code4'] = '';
                                $arrResults[$cur_no]['product_name4'] = '';

                                //$arrResults[$cur_no]['status'] = $row['status'];
                                //$arrResults[$cur_no]['product_count'] = 1;
                                if ($row['product_type'] == ONEPIECE_PRODUCT_TYPE) {
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                } elseif ($row['product_type'] == DRESS_PRODUCT_TYPE) {
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                } elseif ($row['product_type'] == DRESS3_PRODUCT_TYPE) {
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code2'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name2'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                } elseif ($row['product_type'] == DRESS4_PRODUCT_TYPE) {
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code2'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name2'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code4'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name4'] = $row['product_name'];
                                } elseif ($row['product_type'] == STOLE_PRODUCT_TYPE) {
                                    $arrResults[$cur_no]['product_code2'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name2'] = $row['product_name'];
                                } elseif ($row['product_type'] == NECKLACE_PRODUCT_TYPE) {
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                //::N00083 Add 20131201
                                } elseif ($row['product_type'] == SET_DRESS_PRODUCT_TYPE) {
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code2'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name2'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code4'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name4'] = $row['product_name'];
                                //::N00083 end 20131201
                                } elseif ($row['product_type'] == OTHERS_PRODUCT_TYPE) {
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                }

                                $cur_order_id = $val;
                                if ($cur_no > 0) {
                                    //$arrResults[$order_start_no]['product_count'] = $order_product_count;
                                }
                                $order_start_no = $cur_no;
                                $order_product_count = 1;
                            } else {

                                if ($row['product_type'] == ONEPIECE_PRODUCT_TYPE) {
                                    if (!empty($arrResults[$cur_no]['product_code1']) || !empty($arrResults[$cur_no]['product_name1'])) {
                                        $cur_no++;
                                        $order_product_count++;
                                        $arrResults[$cur_no]['order_id'] = "";
                                        $arrResults[$cur_no]['product_code1'] = '';
                                        $arrResults[$cur_no]['product_name1'] = '';
                                        $arrResults[$cur_no]['product_code2'] = '';
                                        $arrResults[$cur_no]['product_name2'] = '';
                                        $arrResults[$cur_no]['product_code3'] = '';
                                        $arrResults[$cur_no]['product_name3'] = '';
                                        $arrResults[$cur_no]['product_code4'] = '';
                                        $arrResults[$cur_no]['product_name4'] = '';
                                    }
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                } elseif ($row['product_type'] == DRESS_PRODUCT_TYPE) {
                                    if (!empty($arrResults[$cur_no]['product_code1']) || !empty($arrResults[$cur_no]['product_name1'])) {
                                        $cur_no++;
                                        $order_product_count++;
                                        $arrResults[$cur_no]['order_id'] = "";
                                        $arrResults[$cur_no]['product_code1'] = '';
                                        $arrResults[$cur_no]['product_name1'] = '';
                                        $arrResults[$cur_no]['product_code2'] = '';
                                        $arrResults[$cur_no]['product_name2'] = '';
                                        $arrResults[$cur_no]['product_code3'] = '';
                                        $arrResults[$cur_no]['product_name3'] = '';
                                        $arrResults[$cur_no]['product_code4'] = '';
                                        $arrResults[$cur_no]['product_name4'] = '';
                                    }
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                } elseif ($row['product_type'] == DRESS3_PRODUCT_TYPE) {
                                    if (!empty($arrResults[$cur_no]['product_code1']) || !empty($arrResults[$cur_no]['product_name1'])
                                        || !empty($arrResults[$cur_no]['product_code2']) || !empty($arrResults[$cur_no]['product_name2'])
                                        || !empty($arrResults[$cur_no]['product_code3']) || !empty($arrResults[$cur_no]['product_name3'])
                                    ) {
                                        $cur_no++;
                                        $order_product_count++;
                                        $arrResults[$cur_no]['order_id'] = "";
                                        $arrResults[$cur_no]['product_code1'] = '';
                                        $arrResults[$cur_no]['product_name1'] = '';
                                        $arrResults[$cur_no]['product_code2'] = '';
                                        $arrResults[$cur_no]['product_name2'] = '';
                                        $arrResults[$cur_no]['product_code3'] = '';
                                        $arrResults[$cur_no]['product_name3'] = '';
                                        $arrResults[$cur_no]['product_code4'] = '';
                                        $arrResults[$cur_no]['product_name4'] = '';
                                    }
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code2'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name2'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                } elseif ($row['product_type'] == DRESS4_PRODUCT_TYPE) {
                                    if (!empty($arrResults[$cur_no]['product_code1']) || !empty($arrResults[$cur_no]['product_name1'])
                                        || !empty($arrResults[$cur_no]['product_code2']) || !empty($arrResults[$cur_no]['product_name2'])
                                        || !empty($arrResults[$cur_no]['product_code3']) || !empty($arrResults[$cur_no]['product_name3'])
                                        || !empty($arrResults[$cur_no]['product_code4']) || !empty($arrResults[$cur_no]['product_name4'])
                                    ) {
                                        $cur_no++;
                                        $order_product_count++;
                                        $arrResults[$cur_no]['order_id'] = "";
                                        $arrResults[$cur_no]['product_code1'] = '';
                                        $arrResults[$cur_no]['product_name1'] = '';
                                        $arrResults[$cur_no]['product_code2'] = '';
                                        $arrResults[$cur_no]['product_name2'] = '';
                                        $arrResults[$cur_no]['product_code3'] = '';
                                        $arrResults[$cur_no]['product_name3'] = '';
                                        $arrResults[$cur_no]['product_code4'] = '';
                                        $arrResults[$cur_no]['product_name4'] = '';
                                    }
                                    $arrResults[$cur_no]['product_code1'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name1'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code2'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name2'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                    $arrResults[$cur_no]['product_code4'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name4'] = $row['product_name'];
                                } elseif ($row['product_type'] == STOLE_PRODUCT_TYPE) {
                                    if (!empty($arrResults[$cur_no]['product_code2']) || !empty($arrResults[$cur_no]['product_name2'])) {
                                        $cur_no++;
                                        $order_product_count++;
                                        $arrResults[$cur_no]['order_id'] = "";
                                        $arrResults[$cur_no]['product_code1'] = '';
                                        $arrResults[$cur_no]['product_name1'] = '';
                                        $arrResults[$cur_no]['product_code2'] = '';
                                        $arrResults[$cur_no]['product_name2'] = '';
                                        $arrResults[$cur_no]['product_code3'] = '';
                                        $arrResults[$cur_no]['product_name3'] = '';
                                        $arrResults[$cur_no]['product_code4'] = '';
                                        $arrResults[$cur_no]['product_name4'] = '';
                                    }
                                    $arrResults[$cur_no]['product_code2'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name2'] = $row['product_name'];
                                } elseif ($row['product_type'] == NECKLACE_PRODUCT_TYPE) {
                                    if (!empty($arrResults[$cur_no]['product_code3']) || !empty($arrResults[$cur_no]['product_name3'])) {
                                        $cur_no++;
                                        $order_product_count++;
                                        $arrResults[$cur_no]['order_id'] = "";
                                        $arrResults[$cur_no]['product_code1'] = '';
                                        $arrResults[$cur_no]['product_name1'] = '';
                                        $arrResults[$cur_no]['product_code2'] = '';
                                        $arrResults[$cur_no]['product_name2'] = '';
                                        $arrResults[$cur_no]['product_code3'] = '';
                                        $arrResults[$cur_no]['product_name3'] = '';
                                        $arrResults[$cur_no]['product_code4'] = '';
                                        $arrResults[$cur_no]['product_name4'] = '';
                                    }
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                //::N00083 Add 20131201
                                } elseif ($row['product_type'] == SET_DRESS_PRODUCT_TYPE) {
                                    if (!empty($arrResults[$cur_no]['product_code3']) || !empty($arrResults[$cur_no]['product_name3'])) {
                                        $cur_no++;
                                        $order_product_count++;
                                        $arrResults[$cur_no]['order_id'] = "";
                                        $arrResults[$cur_no]['product_code1'] = '';
                                        $arrResults[$cur_no]['product_name1'] = '';
                                        $arrResults[$cur_no]['product_code2'] = '';
                                        $arrResults[$cur_no]['product_name2'] = '';
                                        $arrResults[$cur_no]['product_code3'] = '';
                                        $arrResults[$cur_no]['product_name3'] = '';
                                        $arrResults[$cur_no]['product_code4'] = '';
                                        $arrResults[$cur_no]['product_name4'] = '';
                                    }
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                //::N00083 end 20131201
                                } elseif ($row['product_type'] == OTHERS_PRODUCT_TYPE) {
                                    if (!empty($arrResults[$cur_no]['product_code3']) || !empty($arrResults[$cur_no]['product_name3'])) {
                                        $cur_no++;
                                        $order_product_count++;
                                        $arrResults[$cur_no]['order_id'] = "";
                                        $arrResults[$cur_no]['product_code1'] = '';
                                        $arrResults[$cur_no]['product_name1'] = '';
                                        $arrResults[$cur_no]['product_code2'] = '';
                                        $arrResults[$cur_no]['product_name2'] = '';
                                        $arrResults[$cur_no]['product_code3'] = '';
                                        $arrResults[$cur_no]['product_name3'] = '';
                                        $arrResults[$cur_no]['product_code4'] = '';
                                        $arrResults[$cur_no]['product_name4'] = '';
                                    }
                                    $arrResults[$cur_no]['product_code3'] = $row['product_code'];
                                    $arrResults[$cur_no]['product_name3'] = $row['product_name'];
                                }
                            }
                        }

                        if ($cur_no > 0) {
                            //$arrResults[$order_start_no]['product_count'] = $order_product_count;
                        }

                        $data = "";
                        foreach ($arrResults as $item) {
                            $data .= SC_Utils_Ex::sfGetCSVList($item);
                        }

                        // CSVを送信する。
                        $this->lfCSVDownload($data);

                    }
                    exit;
                    break;
                case 'pdf':
                    $new_search_sql = 'Select (D3.order_id) as order_id,D3.product_id,
                max(D3.product_type) as product_type, max(D3.product_code) as product_code, max(D3.name) as product_name, max(D3.status) as status,
                max(D3.sending_date) as sending_date,

                max(D3.set_pcode_stole) as set_pcode_stole, max(D3.set_pcode_necklace) as set_pcode_necklace, max(D3.set_pcode_bag) as set_pcode_bag,
                max(D3.set_pid) as set_pid,max(D3.set_ptype) as set_ptype,max(D3.stock) as stock,

                min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date ELSE null END ) as sending_date1,
                max(CASE when D3.sending_date > D4.sending_date THEN D4.sending_date ELSE null END ) as sending_date2,
                min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date-D3.sending_date ELSE 99999 END ) as diff1,
                min(CASE when D3.sending_date > D4.sending_date THEN D3.sending_date-D4.sending_date ELSE 99999 END ) as diff2,
                MIN(CASE WHEN D3.sending_date < D4.sending_date THEN D4.payment_id ELSE NULL END) AS payment_id1,
                MAX(CASE WHEN D3.sending_date > D4.sending_date THEN D4.payment_id ELSE NULL END) AS payment_id2,
                D3.payment_id
            From
            (
                Select S1.*
                From
                (
                    Select dtb_order_detail.product_id, dtb_order.order_id, dtb_order.sending_date, dtb_products.name, dtb_products.product_type,
                            dtb_products_class.product_code, dtb_order.status, dtb_products_class.stock

                            , dtb_products_class.set_pcode_stole, dtb_products_class.set_pcode_necklace, dtb_products_class.set_pcode_bag
                            , dtb_order_detail.set_pid, dtb_order_detail.set_ptype,dtb_order.payment_id

                    From dtb_order_detail
                    Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                    Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                    Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                            and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                            and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                    Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                    Where ' . $new_where . '
                ) As S1
                inner join
                (
                    Select distinct(dtb_order.order_id) As order_id,dtb_order.payment_id As payment_id
                    From dtb_order_detail
                    Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                    Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                    Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                            and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                            and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                    Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                    ' . $new_in . '
                    Where ' . $new_where . $add_where . '
                    order by dtb_order.order_id DESC
                ) As S2 On S1.order_id=S2.order_id
            ) As D3
            Left join
            (
                Select D2.product_id, O2.order_id, O2.sending_date, ' . "O2.sending_date || '_' || O2.payment_id as payment_id" . '
                From dtb_order_detail as D2
                Inner Join dtb_order AS O2 On D2.order_id=O2.order_id
                Where O2.del_flg <>1 and O2.status not in (6,8)
            ) As D4
            on (D3.product_id=D4.product_id and D3.order_id<>D4.order_id)
            Group By D3.product_id, D3.order_id, D3.payment_id
            order by order_id Desc, set_pid, product_code;';

                    // 検索結果の取得
                    $results = $objQuery->getall($new_search_sql, array_merge($arrval, $arrval));

                    $this->arrResults = $this->getResults($results);

                    $objFpdf = new SC_Order_PikingList_Pdf(0);
                    $objFpdf->setData($this->arrResults, $this->type_count);
                    $objFpdf->createPdf();
                    exit;
                    break;

//::N00062 Add 20130617
                case 'seal':
                    $new_search_sql = 'Select (D3.order_id) as order_id,D3.product_id,
                max(D3.product_type) as product_type, max(D3.product_code) as product_code, max(D3.name) as product_name, max(D3.status) as status,
                max(D3.sending_date) as sending_date,

                max(D3.set_pcode_stole) as set_pcode_stole, max(D3.set_pcode_necklace) as set_pcode_necklace, max(D3.set_pcode_bag) as set_pcode_bag,
                max(D3.stock) as stock,

                min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date ELSE null END ) as sending_date1,
                max(CASE when D3.sending_date > D4.sending_date THEN D4.sending_date ELSE null END ) as sending_date2,
                min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date-D3.sending_date ELSE 99999 END ) as diff1,
                min(CASE when D3.sending_date > D4.sending_date THEN D3.sending_date-D4.sending_date ELSE 99999 END ) as diff2,
                MIN(CASE WHEN D3.sending_date < D4.sending_date THEN D4.payment_id ELSE NULL END) AS payment_id1,
                MAX(CASE WHEN D3.sending_date > D4.sending_date THEN D4.payment_id ELSE NULL END) AS payment_id2,
                D3.payment_id
            From
            (
                Select S1.*
                From
                (
                    Select dtb_order_detail.product_id, dtb_order.order_id, dtb_order.sending_date, dtb_products.name, dtb_products.product_type,
                            dtb_products_class.product_code, dtb_order.status, dtb_category.group_id, dtb_products_class.stock
                            ,dtb_products_class.set_pcode_stole, dtb_products_class.set_pcode_necklace, dtb_products_class.set_pcode_bag,dtb_order.payment_id

                    From dtb_order_detail
                    Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                    Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                    Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                            and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                            and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                    Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                    Inner Join dtb_category ON dtb_product_categories.category_id = dtb_category.category_id
                    Where ' . $new_where . '
                          AND  dtb_category.level = 1
                ) As S1
                inner join
                (
                    Select distinct(dtb_order.order_id) As order_id,dtb_order.payment_id As payment_id
                    From dtb_order_detail
                    Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                    Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                    Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                            and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                            and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                    Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                    Inner Join dtb_category ON dtb_product_categories.category_id = dtb_category.category_id
                    ' . $new_in . '
                    Where ' . $new_where . $add_where . '
                          AND  dtb_category.level = 1
                    order by dtb_order.order_id DESC
                ) As S2 On S1.order_id=S2.order_id
            ) As D3
            Left join
            (
                Select D2.product_id, O2.order_id, O2.sending_date, ' . "O2.sending_date || '_' || O2.payment_id as payment_id" . '
                From dtb_order_detail as D2
                Inner Join dtb_order AS O2 On D2.order_id=O2.order_id
                Where O2.del_flg <>1 and O2.status not in (6,8)
            ) As D4
            on (D3.product_id=D4.product_id and D3.order_id<>D4.order_id)
            Group By D3.product_id, D3.order_id, D3.payment_id
            order by order_id Desc;';

                    // 検索結果の取得
                    $results = $objQuery->getall($new_search_sql, array_merge($arrval, $arrval));
                    $this->arrResults = $this->getSealResults($results);

                    $arrOutBlackRed = $arrOutBlueGreen = array();
                    $black_red_cnt = $blue_green_cnt=0;
                    foreach ( $this->arrResults AS $key=>$val ) {
                        if (($val['infive1'] == 0) || ($val['infive1'] == 2)) {
                            //blue or green
                            $arrOutBlueGreen[$blue_green_cnt]['order_id'] = $val['order_id'];
                            $arrOutBlueGreen[$blue_green_cnt]['infive1'] = $val['infive1'];
                            $blue_green_cnt++;
                        } else {
                            //black or red
                            $arrOutBlackRed[$black_red_cnt]['order_id'] = $val['order_id'];
                            if ($val['infive1'] == 1) {
                                //red
                                $arrOutBlackRed[$black_red_cnt]['infive1'] = $val['infive1'];
                            } else {
                                //black
                                $arrOutBlackRed[$black_red_cnt]['infive1'] = 3;
                            }
                            $black_red_cnt++;
                        }
                    }
                    $arr_deliv_pref = $this->getOrderIdDelivPrefMap($results);
                    $objFpdf = new SC_Order_DressAccessorySeal_Pdf();
                    $i=0; $x=0; $y=0;
                    foreach ( $arrOutBlackRed AS $arr ) {
                        $arrPdfData = $arr;
                        $arrPdfData['order_id'] = $arr['order_id'];
                        $arrPdfData['infive1'] = $arr['infive1'];
                        $objQuery = new SC_Query();
                        $sql = "SELECT *
                                FROM ((          dtb_order_detail       AS A
                                       LEFT JOIN dtb_product_categories AS B ON A.product_id  = B.product_id
                                      )LEFT JOIN dtb_category           AS C ON C.category_id = B.category_id
                                     ) LEFT JOIN dtb_products_ext       AS D ON A.product_id  = D.product_id
                                WHERE      A.order_id = ?
                                      AND  C.level = 1
                                      AND (C.group_id = 'B' OR C.group_id = 'C' OR C.group_id = 'D');";
                        $arrRet = $objQuery->getall($sql, array($arrPdfData['order_id']));
                        foreach ($arrRet as $ret) {
                          if ((strpos($ret['product_code'],PCODE_STOLE) !== false) ||
                          (strpos($ret['product_code'],PCODE_PANNIER) !== false)) {
                            continue;
                          }
                          $objFpdf->setData($arrPdfData,$i,$x,$y,array($ret), $arr_deliv_pref[$arrPdfData['order_id']]);
                          ++$i; ++$x;
                          if ($i == 13) {       $x = 0;$y =  42;}
                          if ($i == 26) {       $x = 0;$y =  84;}
                          if ($i == 39) {       $x = 0;$y = 126;}
                          if ($i == 52) {       $x = 0;$y = 168;}
                          if ($i == 65) {$i = 0;$x = 0;$y =   0;}
                      }
                    }

                    $i=0; $x=0; $y=0;
                    foreach ( $arrOutBlueGreen AS $arr ) {
                        $arrPdfData = $arr;
                        $arrPdfData['order_id'] = $arr['order_id'];
                        $arrPdfData['infive1'] = $arr['infive1'];
                        $objQuery = new SC_Query();
                        $sql = "SELECT *
                                FROM ((          dtb_order_detail       AS A
                                       LEFT JOIN dtb_product_categories AS B ON A.product_id  = B.product_id
                                      )LEFT JOIN dtb_category           AS C ON C.category_id = B.category_id
                                     ) LEFT JOIN dtb_products_ext       AS D ON A.product_id  = D.product_id
                                WHERE      A.order_id = ?
                                      AND  C.level = 1
                                      AND (C.group_id = 'B' OR C.group_id = 'C' OR C.group_id = 'D');";
                        $arrRet = $objQuery->getall($sql, array($arrPdfData['order_id']));
                        foreach ($arrRet as $ret) {
                          if ((strpos($ret['product_code'],PCODE_STOLE) !== false) ||
                          (strpos($ret['product_code'],PCODE_PANNIER) !== false)) {
                            continue;
                          }
                          $objFpdf->setData($arrPdfData,$i,$x,$y,array($ret), $arr_deliv_pref[$arrPdfData['order_id']]);
                          ++$i; ++$x;
                          if ($i == 13) {       $x = 0;$y =  42;}
                          if ($i == 26) {       $x = 0;$y =  84;}
                          if ($i == 39) {       $x = 0;$y = 126;}
                          if ($i == 52) {       $x = 0;$y = 168;}
                          if ($i == 65) {$i = 0;$x = 0;$y =   0;}
                        }
                    }

                    // 2015.09.22 ピッキングリストにボレロを表示する対応 start
                    $i=0; $x=0; $y=0;
                    foreach ( $results AS $row ) {
                    	if($row['product_type'] == STOLE_PRODUCT_TYPE) {
                    		$arrPdfData = $row;
                    		$objFpdf->setBreloAndStoleData($arrPdfData,$i,$x,$y,$arr_deliv_pref[$arrPdfData['order_id']]);
                    		++$i; ++$x;
                    		if ($i == 13) {       $x = 0;$y =  42;}
                    		if ($i == 26) {       $x = 0;$y =  84;}
                    		if ($i == 39) {       $x = 0;$y = 126;}
                    		if ($i == 52) {       $x = 0;$y = 168;}
                    		if ($i == 65) {$i = 0;$x = 0;$y =   0;}
                    	}
                    }
                    // 2015.09.22 ピッキングリストにボレロを表示する対応 end

                    $objFpdf->createPdf();
                    exit;
                    break;
//::N00062 end 20130617

                default:
                    // 検索
                    // 何件が該当しました。表示用
                    $this->tpl_linemax = $linemax;

                    // ページ送りの処理
                    if (is_numeric($_POST['search_page_max'])) {
                        $page_max = $_POST['search_page_max'];
                    } else {
                        $page_max = SEARCH_PMAX;
                    }

                    // ページ送りの取得
                    $objNavi = new SC_PageNavi($this->arrHidden['search_pageno'], $linemax, $page_max,
                        "fnNaviSearchPage", NAVI_PMAX);
                    $startno = $objNavi->start_row;
                    $this->arrPagenavi = $objNavi->arrPagenavi;

                    $new_search_sql = 'Select (D3.order_id) as order_id,D3.product_id,
                max(D3.product_type) as product_type, max(D3.product_code) as product_code, max(D3.name) as product_name, max(D3.status) as status,
                max(D3.sending_date) as sending_date,

                max(D3.set_pcode_stole) as set_pcode_stole, max(D3.set_pcode_necklace) as set_pcode_necklace, max(D3.set_pcode_bag) as set_pcode_bag,
                max(D3.set_pid) as set_pid,max(D3.set_ptype) as set_ptype,max(D3.stock) as stock,

                min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date ELSE null END ) as sending_date1,
                max(CASE when D3.sending_date > D4.sending_date THEN D4.sending_date ELSE null END ) as sending_date2,
                min(CASE when D3.sending_date < D4.sending_date THEN D4.sending_date-D3.sending_date ELSE 99999 END ) as diff1,
                min(CASE when D3.sending_date > D4.sending_date THEN D3.sending_date-D4.sending_date ELSE 99999 END ) as diff2,
                MIN(CASE WHEN D3.sending_date < D4.sending_date THEN D4.payment_id ELSE NULL END) AS payment_id1,
                MAX(CASE WHEN D3.sending_date > D4.sending_date THEN D4.payment_id ELSE NULL END) AS payment_id2,
                D3.payment_id
            From
            (
                Select S1.*
                From
                (
                    Select dtb_order_detail.product_id, dtb_order.order_id, dtb_order.sending_date, dtb_products.name, dtb_products.product_type,
                            dtb_products_class.product_code, dtb_order.status, dtb_products_class.stock

                            , dtb_products_class.set_pcode_stole, dtb_products_class.set_pcode_necklace, dtb_products_class.set_pcode_bag
                            , dtb_order_detail.set_pid, dtb_order_detail.set_ptype,dtb_order.payment_id

                    From dtb_order_detail
                    Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                    Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                    Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                            and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                            and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                    Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                    Where ' . $new_where . '
                ) As S1
                inner join
                (
                    Select distinct(dtb_order.order_id) As order_id,dtb_order.payment_id As payment_id
                    From dtb_order_detail
                    Inner Join dtb_order On dtb_order_detail.order_id=dtb_order.order_id
                    Inner join dtb_products On dtb_products.product_id = dtb_order_detail.product_id
                    Inner Join dtb_products_class On (dtb_products.product_id = dtb_products_class.product_id
                            and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1
                            and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2 )
                    Inner Join dtb_product_categories ON dtb_product_categories.product_id = dtb_products_class.product_id
                    ' . $new_in . '
                    Where ' . $new_where . $add_where . '
                    order by dtb_order.order_id DESC LIMIT ' . $page_max . ' OFFSET ' . $startno . '
                ) As S2 On S1.order_id=S2.order_id
            ) As D3
            Left join
            (
                Select D2.product_id, O2.order_id, O2.sending_date, ' . "O2.sending_date || '_' || O2.payment_id as payment_id" . '
                From dtb_order_detail as D2
                Inner Join dtb_order AS O2 On D2.order_id=O2.order_id
                Where O2.del_flg <>1 and O2.status not in (6,8)
            ) As D4
            on (D3.product_id=D4.product_id and D3.order_id<>D4.order_id)
            Group By D3.product_id, D3.order_id, D3.payment_id
            order by order_id Desc, set_pid, product_code;';

                    // AND O2.status not in (6,8)

                    // 検索結果の取得
                    $results = $objQuery->getall($new_search_sql, array_merge($arrval, $arrval));

                    if (count($results) > 0) {
                        $this->arrResults = $this->getResults($results);
                    }else{
                        $this->arrResults = array();
                    }
            }

        }

        //::N00083 Change 20131201
        function getResults($arr)
        {
            $cur_order_id = 0;
            $cur_no = -1;
            $product_count = array();
            $arrResults = array();
            //同じ発送日の同じ商品をカウントする
            foreach ($arr as $key=>$row) {
                $arr_pcode_cnt[$row['sending_date']][$row['product_code']]++;

            }
            ksort($arr_pcode_cnt);
            foreach ($arr as $key=>$row) {
                $infive = -1;
                if (!empty($row['sending_date']) ) {
                    $infive = -1;
                    //在庫数と同じ数の注文が入っている場合は、色をつける
                    if ($row['stock'] <= $arr_pcode_cnt[$row['sending_date']][$row['product_code']]) {
                    if($row['status']!=6 && $row['status']!=8){
                      $infive = $this->getOrderStatusColor($row);
                    }
                  }
                }

                if (!empty($_POST['search_order_five_day']) && $infive <1) {
                    continue;
                }

                //注文番号が変わった場合
                if ($row['order_id'] != $cur_order_id) {
                    $cur_order_id = $row['order_id'];
                    $cur_no++;

                    $product_count[SET_DRESS_PRODUCT_TYPE] = 0;
                    $product_count[STOLE_PRODUCT_TYPE] = 0;
                    $product_count[NECKLACE_PRODUCT_TYPE] = 0;
                    $product_count[OTHERS_PRODUCT_TYPE] = 0;

                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['order_id'] = $row['order_id'];
                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['order_name'] = $row['order_name01'] . ' ' . $row['order_name02'];
                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['status'] = $row['status'];
                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['diff'] = $row['diff'];
                }

                $new_product_name = '';
                if( strpos($row['product_code'] , 'CM') !== false ){
                    $new_product_name = '●' . $row['product_name'];
                }else{
                    $new_product_name =  $row['product_name'];
                }

                //商品タイプ別に表示位置を振り分ける
                switch ($row['product_type']) {
                case ONEPIECE_PRODUCT_TYPE:
                case DRESS_PRODUCT_TYPE:
                case SET_DRESS_PRODUCT_TYPE:
                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['product_code1'] = $row['product_code'];
                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['product_name1'] = $new_product_name;
                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['product_id1'] = $row['product_id'];
                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['infive1'] = $infive;
                    $arrResults[$cur_no+$product_count[SET_DRESS_PRODUCT_TYPE]]['set_pid'] = $row['set_pid'];
                    $product_count[SET_DRESS_PRODUCT_TYPE]++;
                    break;
                case STOLE_PRODUCT_TYPE:
                    $arrResults[$cur_no+$product_count[STOLE_PRODUCT_TYPE]]['product_code2'] = $row['product_code'];
                    $arrResults[$cur_no+$product_count[STOLE_PRODUCT_TYPE]]['product_name2'] = $new_product_name;
                    $arrResults[$cur_no+$product_count[STOLE_PRODUCT_TYPE]]['product_id2'] = $row['product_id'];
                    $arrResults[$cur_no+$product_count[STOLE_PRODUCT_TYPE]]['infive2'] = $infive;
                    $arrResults[$cur_no+$product_count[STOLE_PRODUCT_TYPE]]['set_pid'] = $row['set_pid'];
                    $product_count[STOLE_PRODUCT_TYPE]++;
                    break;
                case NECKLACE_PRODUCT_TYPE:
                case OTHERS_PRODUCT_TYPE:
                    if (strpos($row['product_code'],PCODE_BAG) === false) {
                        $arrResults[$cur_no+$product_count[NECKLACE_PRODUCT_TYPE]]['product_code3'] = $row['product_code'];
                        $arrResults[$cur_no+$product_count[NECKLACE_PRODUCT_TYPE]]['product_name3'] = $row['product_name'];
                        $arrResults[$cur_no+$product_count[NECKLACE_PRODUCT_TYPE]]['product_id3'] = $row['product_id'];
                        $arrResults[$cur_no+$product_count[NECKLACE_PRODUCT_TYPE]]['infive3'] = $infive;
                        $arrResults[$cur_no+$product_count[NECKLACE_PRODUCT_TYPE]]['set_pid'] = $row['set_pid'];
                        $product_count[NECKLACE_PRODUCT_TYPE]++;
                } else {
                        $arrResults[$cur_no+$product_count[OTHERS_PRODUCT_TYPE]]['product_code4'] = $row['product_code'];
                        $arrResults[$cur_no+$product_count[OTHERS_PRODUCT_TYPE]]['product_name4'] = $row['product_name'];
                        $arrResults[$cur_no+$product_count[OTHERS_PRODUCT_TYPE]]['product_id4'] = $row['product_id'];
                        $arrResults[$cur_no+$product_count[OTHERS_PRODUCT_TYPE]]['infive4'] = $infive;
                        $arrResults[$cur_no+$product_count[OTHERS_PRODUCT_TYPE]]['set_pid'] = $row['set_pid'];
                        $product_count[OTHERS_PRODUCT_TYPE]++;
                        }
                    break;
                default:
                    break;
                }

                if ($arr[$key+1]['order_id'] != $cur_order_id) {
                    $arrResults[$cur_no]['product_count'] = max($product_count);
                    $cur_no += max($product_count)-1;
            }

            }
            //::N00083 end 20131201

            $arr_deliv_pref = $this->getOrderIdDelivPrefMap($arr);
            $delev_pref;

            foreach ($arrResults as &$results) {

              if (isset($arr_deliv_pref[$results['order_id']])) {
                $delev_pref = $arr_deliv_pref[$results['order_id']];
              }
              /*超速*/
              if (!SC_Helper_Delivery_Ex::sfIsNomalArea($delev_pref[0])){
                foreach ($results as $key => &$value) {
                  if (substr($key,  0, strlen("product_name")) === "product_name"){
                    $value = "★".$value;
                  }
                }
              }
              unset($results);
              unset($value);
            }
            return $arrResults;
        }

        /**
         * オーダーIDとお届け先都道府県コードのマップを生成する
         *
         * @param  array  $arr オーダーIDを含む受注情報の配列
         * @return array　$arr_deliv_pref  オーダーIDとお届け先都道府県コードのマップ
         */
        function getOrderIdDelivPrefMap($arr) {
          // オーダーIDを抽出
          $arr_order_id = array();
          foreach ($arr as $key=>$row) {
            $arr_order_id[] = $row['order_id'];
          }
          $arr_order_id = array_unique($arr_order_id);
          $arr_order_id = array_values($arr_order_id);

          // 配送先情報を取得
          $objQuery = new SC_Query();
          $query = "SELECT order_id, deliv_pref, relief_value FROM dtb_order WHERE order_id IN('".implode("','",$arr_order_id)."')";
          $arr_order_pref = $objQuery->getall($query);

          // オーダーIDと配送情報のマップ
          $arr_deliv_pref = array();
          foreach ($arr_order_pref as $key=>$row) {
            $arr_deliv_pref[$row['order_id']] =array($row['deliv_pref'],$row['relief_value']);
          }
          return $arr_deliv_pref;
        }

        function getSealResults($arr)
        {
            $cur_order_id = 0;
            $cur_no = -1;
            $order_start_no = 0;
            $order_product_count = 0;
            $arrResults = array();

            //::N00083 Add 20131201
            //同じ発送日の同じ商品をカウントする
            foreach ($arr as $key=>$row) {
                $arr_pcode_cnt[$row['sending_date']][$row['product_code']]++;
            }
            ksort($arr_pcode_cnt);
            //::N00083 end 20131201

            foreach ($arr as $row) {
                $infive = -1;
                if (!empty($row['sending_date']) ) {
                    $infive = -1;
                    //在庫数と同じ数の注文が入っている場合は、色をつける
                    if ($row['stock'] <= $arr_pcode_cnt[$row['sending_date']][$row['product_code']]) {//::N00083 Add 20131201
                    if($row['status']!=6 && $row['status']!=8){
                      $infive = $this->getOrderStatusColor($row);
                    }
                }
                }

                $val = $row['order_id'];
                $tmp=9999;
                if ($val != $cur_order_id) {
                    //ドレス・ワンピースの他に、ストール・ボレロ、もしくはパニエがあるか検査
                    if (/* (strpos($row['product_code'],FOUR_PIECE_SET1) !== false) ||
                         (strpos($row['product_code'],FOUR_PIECE_SET2) !== false) ||
                         (strpos($row['product_code'],THREE_PIECE_SET1) !== false) ||*///::N00083 Change 20131201
                         (strpos($row['product_code'],PCODE_KIDS_DRESS) !== false) ||
                         (strpos($row['product_code'],PCODE_DRESS) !== false) ||
                         (strpos($row['product_code'],PCODE_SET_DRESS) !== false) ||
                         (strpos($row['product_code'],PCODE_ONEPIECE_ALL) !== false) ||
                         (strpos($row['product_code'],PCODE_ONEPIECE_SUMMER) !== false) ||
                         (strpos($row['product_code'],PCODE_ONEPIECE_WINTER) !== false) ||
                         (strpos($row['product_code'],PCODE_STOLE) !== false) ||
                         (strpos($row['product_code'],PCODE_COAT) !== false) ||
                         (strpos($row['product_code'],PCODE_PANNIER) !== false) ) {
                        $cur_no++;
                        $arrResults[$cur_no]['order_id'] = $row['order_id'];
                        $arrResults[$cur_no]['infive1'] = $infive;
                        $cur_order_id = $val;
                    }

                } else {
                    //ドレス・ワンピースの他に、ストール・ボレロ、もしくはパニエがあるか検査
                    if (/* (strpos($row['product_code'],FOUR_PIECE_SET1) !== false) ||
                         (strpos($row['product_code'],FOUR_PIECE_SET2) !== false) ||
                         (strpos($row['product_code'],THREE_PIECE_SET1) !== false) ||*///::N00083 Change 20131201
                         (strpos($row['product_code'],PCODE_KIDS_DRESS) !== false) ||
                         (strpos($row['product_code'],PCODE_DRESS) !== false) ||
                         (strpos($row['product_code'],PCODE_SET_DRESS) !== false) ||
                         (strpos($row['product_code'],PCODE_ONEPIECE_ALL) !== false) ||
                         (strpos($row['product_code'],PCODE_ONEPIECE_SUMMER) !== false) ||
                         (strpos($row['product_code'],PCODE_ONEPIECE_WINTER) !== false) ||
                         (strpos($row['product_code'],PCODE_STOLE) !== false) ||
                         (strpos($row['product_code'],PCODE_COAT) !== false) ||
                         (strpos($row['product_code'],PCODE_PANNIER) !== false) ) {
                        //注文された商品の中に赤字、青字、緑字の商品があるか検査
                        if (($infive != -1) && ($arrResults[$cur_no]['infive1'] == -1)) {
                            $arrResults[$cur_no]['infive1'] = $infive;
                        }
                    }
                }
            }

            return $arrResults;
        }
//::N00062 end 20130617


        /**
         * デストラクタ.
         *
         * @return void
         */
        function destroy()
        {
            parent::destroy();
        }

        /* パラメータ情報の初期化 */
        function lfInitParam()
        {
            $this->objFormParam->addParam("注文番号1", "search_order_id1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("注文番号2", "search_order_id2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("対応状況", "search_order_status", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("顧客名", "search_order_name", STEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
            $this->objFormParam->addParam("顧客名(カナ)", "search_order_kana", STEXT_LEN, "KVCa", array("KANA_CHECK", "MAX_LENGTH_CHECK"));
            $this->objFormParam->addParam("性別", "search_order_sex", INT_LEN, "n", array("MAX_LENGTH_CHECK"));
            $this->objFormParam->addParam("年齢1", "search_age1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("年齢2", "search_age2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("メールアドレス", "search_order_email", STEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
            $this->objFormParam->addParam("TEL", "search_order_tel", STEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
            $this->objFormParam->addParam("支払い方法", "search_payment_id", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("購入金額1", "search_total1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("購入金額2", "search_total2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("表示件数", "search_page_max", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sorderyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sordermonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sorderday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eorderyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eordermonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eorderday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_supdateyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_supdatemonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_supdateday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eupdateyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eupdatemonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eupdateday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sbirthyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sbirthmonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sbirthday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_ebirthyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_ebirthmonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_ebirthday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            // =============== 2012.05.16 RCHJ Change & Add ================
            for ($i = 0; $i <= $_REQUEST["search_send_date_index"]; $i++) {
                $this->objFormParam->addParam("発送日", "search_txt_send_date" . $i);
            }
            // ====================== end ====================
            //$this->objFormParam->addParam("選択したカテゴリ名", "selected_categorys", STEXT_LEN, "n", array("MAX_LENGTH_CHECK"));
            $this->objFormParam->addParam("商品コード", "search_product_code", STEXT_LEN, "n", array("MAX_LENGTH_CHECK"));
            $this->objFormParam->addParam("中5日間後", "search_order_five_day", INT_LEN, "n", array("NUM_CHECK"));
        }

        /* 入力内容のチェック */
        function lfCheckError()
        {
            // 入力データを渡す。
            $arrRet = $this->objFormParam->getHashArray();
            $objErr = new SC_CheckError($arrRet);
            $objErr->arrErr = $this->objFormParam->checkError();

            // 特殊項目チェック
            $objErr->doFunc(array("注文番号1", "注文番号2", "search_order_id1", "search_order_id2"), array("GREATER_CHECK"));
            $objErr->doFunc(array("年齢1", "年齢2", "search_age1", "search_age2"), array("GREATER_CHECK"));
            $objErr->doFunc(array("購入金額1", "購入金額2", "search_total1", "search_total2"), array("GREATER_CHECK"));
            $objErr->doFunc(array("開始日", "search_sorderyear", "search_sordermonth", "search_sorderday"), array("CHECK_DATE"));
            $objErr->doFunc(array("終了日", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_DATE"));
            $objErr->doFunc(array("開始日", "終了日", "search_sorderyear", "search_sordermonth", "search_sorderday", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_SET_TERM"));

            $objErr->doFunc(array("開始日", "search_supdateyear", "search_supdatemonth", "search_supdateday"), array("CHECK_DATE"));
            $objErr->doFunc(array("終了日", "search_eupdateyear", "search_eupdatemonth", "search_eupdateday"), array("CHECK_DATE"));
            $objErr->doFunc(array("開始日", "終了日", "search_supdateyear", "search_supdatemonth", "search_supdateday", "search_eupdateyear", "search_eupdatemonth", "search_eupdateday"), array("CHECK_SET_TERM"));

            $objErr->doFunc(array("開始日", "search_sbirthyear", "search_sbirthmonth", "search_sbirthday"), array("CHECK_DATE"));
            $objErr->doFunc(array("終了日", "search_ebirthyear", "search_ebirthmonth", "search_ebirthday"), array("CHECK_DATE"));
            $objErr->doFunc(array("開始日", "終了日", "search_sbirthyear", "search_sbirthmonth", "search_sbirthday", "search_ebirthyear", "search_ebirthmonth", "search_ebirthday"), array("CHECK_SET_TERM"));

            return $objErr->arrErr;
        }

        /**
        * 前回、次回の貸出日から商品名に付ける色を返す
        *
        * @param 受注情報の連想配列
        * @return int green=2 red=1 blue=0
        */
        function getOrderStatusColor($order)
        {
          $blue = false;
          $red = false;

          // 次回貸出し日が７日未満
          if ($order['diff1'] > 0 && $order['diff1'] < 7) {
            $red = true;
          }

          // 前回貸出し日が７日未満
          if ($order['diff2'] > 0 && $order['diff2'] < 7) {
            $blue = true;
          }

          // 両方成立
          if ($red && $blue) {
            // green
            return 2;
          }
          // 超速便の場合は7日の場合も色を付ける
          // データ構造が貸出日_支払方法IDなので分割する
          $arrPayment_id1 = explode("_", $order['payment_id1']);
          $payment_id1 = $arrPayment_id1[1];

          $arrPayment_id2 = explode("_", $order['payment_id2']);
          $payment_id2 = $arrPayment_id2[1];

          // 今回が超速便かつ中6日
          if (($order['diff1'] > 0 && $order['diff1'] == 7)
          && ($order['payment_id'] == '12' || $order['payment_id'] == '11'))
          {
            $red = true;
          }

          // 前回の貸出日が超速便
          if (($order['diff2'] > 0 && $order['diff2'] == 7)
          && ($payment_id2 == '12' || $payment_id2 == '11'))
          {
            $blue = true;
          }

          if ($red && $blue) {
            return 2;
          } else if ($red) {
            return 1;
          } else if ($blue) {
            return 0;
          } else {
            return -1;
          }
        }
    }
?>

