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

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * レビュー管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_Review extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'products/review.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'review';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_maintitle = '商品管理';
        $this->tpl_subtitle = 'レビュー管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPageMax = $masterData->getMasterData('mtb_page_max');
        $this->arrRECOMMEND = $masterData->getMasterData('mtb_recommend');
        $this->arrSex = $masterData->getMasterData('mtb_sex');

        $objDate = new SC_Date_Ex();
        // 登録・更新検索開始年
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrStartYear = $objDate->getYear();
        $this->arrStartMonth = $objDate->getMonth();
        $this->arrStartDay = $objDate->getDay();
        // 登録・更新検索終了年
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrEndYear = $objDate->getYear();
        $this->arrEndMonth = $objDate->getMonth();
        $this->arrEndDay = $objDate->getDay();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        // URLを小文字に変換
        $objFormParam->toLower('search_reviewer_url');

        $this->arrForm = $objFormParam->getHashArray();
        $this->arrHidden = $this->lfSetHidden($this->arrForm);

        // 入力パラメーターチェック
        $this->arrErr = $this->lfCheckError($objFormParam);
        if (!SC_Utils_Ex::isBlank($this->arrErr)) {
            return;
        }

        switch ($this->getMode()) {
            case 'delete':
                $this->lfDeleteReview($this->arrForm['review_id']);
            case 'search':
            case 'csv':

                // 検索条件を取得
                list($where, $arrWhereVal) = $this->lfGetWhere($this->arrForm);
                // 検索結果を取得
                $this->arrReview = $this->lfGetReview($this->arrForm, $where, $arrWhereVal);

                //CSVダウンロード
                if ($this->getMode() == 'csv') {
                    $this->lfDoOutputCsv($where, $arrWhereVal);

                    SC_Response_Ex::actionExit();
                }

                break;
            default:
                break;
        }

        // レビューの集計
        if ($_POST['mode'] == 'totalization')
        {
            $this->lfReviewReflection();
            $text = "'集計処理が完了しました。'";
           $this->tpl_onload = "window.alert(".$text.");";
        }

    }

    /**
     * 入力内容のチェックを行う.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function lfCheckError(&$objFormParam)
    {
        // 入力データを渡す。
        $arrRet =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrRet);
        $objErr->arrErr = $objFormParam->checkError();

        switch ($this->getMode()) {
            case 'search':
                $objErr->doFunc(array('開始日', 'search_startyear', 'search_startmonth', 'search_startday'), array('CHECK_DATE'));
                $objErr->doFunc(array('終了日', 'search_endyear', 'search_endmonth', 'search_endday'), array('CHECK_DATE'));
                $objErr->doFunc(array('開始日', '終了日', 'search_startyear', 'search_startmonth', 'search_startday', 'search_endyear', 'search_endmonth', 'search_endday'), array('CHECK_SET_TERM'));
                break;

            case 'complete':
                $objErr->doFunc(array('おすすめレベル', 'recommend_level'), array('SELECT_CHECK'));
                $objErr->doFunc(array('タイトル', 'title', STEXT_LEN), array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
                $objErr->doFunc(array('コメント', 'comment', LTEXT_LEN), array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
                break;
            default:
                break;
        }

        return $objErr->arrErr;
    }

    /**
     * 商品レビューの削除
     *
     * @param  integer $review_id 商品レビューのID
     * @return void
     */
    public function lfDeleteReview($review_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $sqlval['del_flg'] = 1;
        $objQuery->update('dtb_review', $sqlval, 'review_id = ?', array($review_id));
    }

    /**
     * hidden情報の作成
     *
     * @param  array $arrForm フォームデータ
     * @return array hidden情報
     */
    public function lfSetHidden($arrForm)
    {
        $arrHidden = array();
        foreach ($arrForm AS $key=>$val) {
            if (preg_match('/^search_/', $key)) {
                switch ($key) {
                    case 'search_sex':
                        $arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
                        if (!is_array($val)) {
                            $arrForm[$key] = explode('-', $val);
                        }
                        break;
                    default:
                        $arrHidden[$key] = $val;
                        break;
                }
            }
        }

        return $arrHidden;
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        $objFormParam->addParam('投稿者名', 'search_reviewer_name', STEXT_LEN, 'KVas', array('MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('投稿者URL', 'search_reviewer_url', STEXT_LEN, 'KVas', array('MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('商品名', 'search_name', STEXT_LEN, 'KVas', array('MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('商品コード', 'search_product_code', STEXT_LEN, 'KVas', array('MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('性別', 'search_sex', INT_LEN, 'n', array('MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('おすすめレベル', 'search_recommend_level', INT_LEN, 'n', array('MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('投稿年', 'search_startyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '', false);
        $objFormParam->addParam('投稿月', 'search_startmonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '', false);
        $objFormParam->addParam('投稿日', 'search_startday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '', false);
        $objFormParam->addParam('投稿年', 'search_endyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '', false);
        $objFormParam->addParam('投稿月', 'search_endmonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '', false);
        $objFormParam->addParam('投稿日', 'search_endday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '', false);
        $objFormParam->addParam('最大表示件数', 'search_page_max', INT_LEN, 'n', array('MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('ページ番号件数', 'search_pageno', INT_LEN, 'n', array('MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('レビューID', 'review_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK'), '', false);
    }

    /**
     * CSV ファイル出力実行
     *
     * @param  string $where       WHERE文
     * @param  array  $arrWhereVal WHERE文の判定値
     * @return void
     */
    public function lfDoOutputCsv($where, $arrWhereVal)
    {
        $objCSV = new SC_Helper_CSV_Ex();
        $objCSV->sfDownloadCsv('4', $where, $arrWhereVal, '', true);
    }

    /**
     * WHERE文の作成
     *
     * @param  array $arrForm フォームデータ
     * @return array WHERE文、判定値
     */
    public function lfGetWhere($arrForm)
    {
        //削除されていない商品を検索
        $where = 'A.del_flg = 0 AND B.del_flg = 0';
        $arrWhereVal = array();

        foreach ($arrForm AS $key=>$val) {
            if (empty($val)) continue;

            switch ($key) {
                case 'search_reviewer_name':
                    $val = preg_replace('/ /', '%', $val);
                    $where.= ' AND reviewer_name LIKE ? ';
                    $arrWhereVal[] = "%$val%";
                    break;

                case 'search_reviewer_url':
                    $val = preg_replace('/ /', '%', $val);
                    $where.= ' AND reviewer_url LIKE ? ';
                    $arrWhereVal[] = "%$val%";
                    break;

                case 'search_name':
                    $val = preg_replace('/ /', '%', $val);
                    $where.= ' AND name LIKE ? ';
                    $arrWhereVal[] = "%$val%";
                    break;

                case 'search_product_code':
                    $val = preg_replace('/ /', '%', $val);
                    $where.= ' AND A.product_id IN (SELECT product_id FROM dtb_products_class WHERE product_code LIKE ?)';
                    $arrWhereVal[] = "%$val%";
                    break;

                case 'search_sex':
                    $tmp_where = '';
                    //$val=配列の中身,$element=各キーの値(1,2)
                    if (is_array($val)) {
                        foreach ($val as $element) {
                            if ($element != '') {
                                if ($tmp_where == '') {
                                    $tmp_where .= ' AND (sex = ?';
                                } else {
                                    $tmp_where .= ' OR sex = ?';
                                }
                                $arrWhereVal[] = $element;
                            }
                        }
                        if ($tmp_where != '') {
                            $tmp_where .= ')';
                            $where .= " $tmp_where ";
                        }
                    }

                    break;

                case 'search_recommend_level':
                    $where.= ' AND recommend_level = ? ';
                    $arrWhereVal[] = $val;
                    break;

                case 'search_startyear':
                    if (isset($_POST['search_startyear']) && isset($_POST['search_startmonth']) && isset($_POST['search_startday'])) {
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_startyear'], $_POST['search_startmonth'], $_POST['search_startday']);
//                        $where.= ' AND A.create_date >= ? ';   bhm change
						$date = date('Y/m/d',strtotime('0 day' ,strtotime($date)));
                        $where.= " AND C.sending_date >= cast('$date' as date) ";//:: N00004 Change 20130314
                    }
                    break;

                case 'search_endyear':
                    if (isset($_POST['search_startyear']) && isset($_POST['search_startmonth']) && isset($_POST['search_startday'])) {
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_endyear'], $_POST['search_endmonth'], $_POST['search_endday']);
                        $end_date = date('Y/m/d',strtotime('1 day' ,strtotime($date)));
//                        $where.= " AND A.create_date <= cast('$end_date' as date) ";
                        $where.= " AND C.sending_date < cast('$end_date' as date) ";
                    }
                    break;

                default:
                    break;
            }

        }

        return array($where, $arrWhereVal);
    }

    /**
     * レビュー検索結果の取得
     *
     * @param  array  $arrForm     フォームデータ
     * @param  string $where       WHERE文
     * @param  array  $arrWhereVal WHERE文の判定値
     * @return array  レビュー一覧
     */
    public function lfGetReview($arrForm, $where, $arrWhereVal)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where_tmp = $where ;

        // ページ送りの処理
        $page_max = SC_Utils_Ex::sfGetSearchPageMax($arrForm['search_page_max']);

        if (!isset($arrWhereVal)) $arrWhereVal = array();

//        $from = 'dtb_review AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id ';
        $from = " (dtb_review AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id) LEFT JOIN dtb_order AS C ON C.order_id = A.order_id";
       
        //:: N00004 Add 20130314 -------------------------------------------------------------------------
        $where="A.del_flg = 0 AND B.del_flg = 0";
        
            $whereTotalOrder="del_flg = 0";
            $fromTotalOrder = "dtb_order";
            if (isset($_POST['search_startyear']) && isset($_POST['search_startmonth']) && isset($_POST['search_startday'])){
                $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_startyear'], $_POST['search_startmonth'], $_POST['search_startday']);
				$date = date('Y/m/d',strtotime('0 day' ,strtotime($date)));
                $whereTotalOrder.= " AND sending_date >= cast('$date' as date) ";
//                $arrTotalOrder[] = $date;
            }
            if (isset($_POST['search_startyear']) && isset($_POST['search_startmonth']) && isset($_POST['search_startday'])){
                $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_endyear'], $_POST['search_endmonth'], $_POST['search_endday']);
                $end_date = date("Y/m/d",strtotime("1 day" ,strtotime($date)));
                $whereTotalOrder.= " AND sending_date <= cast('$end_date' as date) ";
            }
            $totalOrder = $objQuery->count($fromTotalOrder, $whereTotalOrder, $arrTotalOrder);


            $arr=$arrval;
            if (isset($_POST['search_startyear']) && isset($_POST['search_startmonth']) && isset($_POST['search_startday'])){
                $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_startyear'], $_POST['search_startmonth'], $_POST['search_startday']);
				$date = date('Y/m/d',strtotime('0 day' ,strtotime($date)));
                $where.= " AND C.sending_date >= cast('$date' as date) ";
               
            }
            if (isset($_POST['search_startyear']) && isset($_POST['search_startmonth']) && isset($_POST['search_startday'])){
                $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_endyear'], $_POST['search_endmonth'], $_POST['search_endday']);
                $end_date = date("Y/m/d",strtotime("1 day" ,strtotime($date)));
                $where.= " AND C.sending_date < cast('$end_date' as date) ";
            }
            $tyuumonnMax = $objQuery->count($from, $where, $arr);
            
            $where.= " AND recommend_level = ? ";


            $arr[0]='5';
            $star5 = $objQuery->count($from, $where, $arr);  
            
            $arr[0]='4';
            $star4 = $objQuery->count($from, $where, $arr);
            $arr[0]='3';
            $star3 = $objQuery->count($from, $where, $arr);
            $arr[0]='2';
            $star2 = $objQuery->count($from, $where, $arr);
            $arr[0]='1';
            $star1 = $objQuery->count($from, $where, $arr);

            //::・件数(各種星別の件数)
            $this->valKensuu5 = $star5;
            $this->valKensuu4 = $star4;
            $this->valKensuu3 = $star3;
            $this->valKensuu2 = $star2;
            $this->valKensuu1 = $star1;
            if ($tyuumonnMax != 0) {
                //::・比率(各種レベルの星総注文件数)
                $this->valHiritu5 = number_format(($star5/$tyuumonnMax)*100,1);
                $this->valHiritu4 = number_format(($star4/$tyuumonnMax)*100,1);
                $this->valHiritu3 = number_format(($star3/$tyuumonnMax)*100,1);
                $this->valHiritu2 = number_format(($star2/$tyuumonnMax)*100,1);
                $this->valHiritu1 = number_format(($star1/$tyuumonnMax)*100,1);
                //::・平均(星総点数/総注文件数)
                $this->valHeikinn = number_format((($star5*5)+($star4*4)+($star3*3)+($star2*2)+($star1*1))/$tyuumonnMax,2);
            } else {
                //::・比率(各種レベルの星総注文件数)
                $this->valHiritu5 = number_format(0,1);
                $this->valHiritu4 = number_format(0,1);
                $this->valHiritu3 = number_format(0,1);
                $this->valHiritu2 = number_format(0,1);
                $this->valHiritu1 = number_format(0,1);
                //::・平均(星総点数/総注文件数)
                $this->valHeikinn = number_format(0,2);
            }
            //::・合計(星の合計)
            $this->valKensuuGoukei = $tyuumonnMax;


            //$totalOrder=0ということは、指定された発送期間中に一件も注文がないということなので、$tyuumonnMaxも0件
            if ($totalOrder != 0) {
                $this->valKensuuNashi = $totalOrder-$tyuumonnMax;
                $this->valHirituNashi = number_format((($totalOrder-$tyuumonnMax)/$totalOrder)*100,1);
                //::・総注文件数(指定発送日期間中に総注文件数)
                $this->valKennsuuOrder = $totalOrder;
                //::・回答率(アンケあり/総注文件数)
                $this->valKaitou = number_format(($tyuumonnMax/$totalOrder)*100,1);
            } else {
                $this->valKensuuNashi = 0;
                $this->valHirituNashi = number_format(0,1);
                //::・総注文件数(指定発送日期間中に総注文件数)
                $this->valKennsuuOrder = 0;
                //::・回答率(アンケあり/総注文件数)
                $this->valKaitou = number_format(0,1);
            }
           
        $where =  $where_tmp ;   
//:: N00004 end 20130314 ----    

        $linemax = $objQuery->count($from, $where, $arrWhereVal);      
        $this->tpl_linemax = $linemax;

        $this->tpl_pageno = isset($arrForm['search_pageno']) ? $arrForm['search_pageno'] : '';

        // ページ送りの取得
        $objNavi = new SC_PageNavi_Ex($this->tpl_pageno, $linemax, $page_max,
                                      'eccube.moveNaviPage', NAVI_PMAX);
        $this->arrPagenavi = $objNavi->arrPagenavi;
        $startno = $objNavi->start_row;

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setLimitOffset($page_max, $startno);

        // 表示順序
        $order = 'A.create_date DESC';
        $objQuery->setOrder($order);
        //検索結果の取得
        //レビュー情報のカラムの取得
        // $col = 'review_id, A.product_id, reviewer_name, sex, recommend_level, ';
        // $col .= 'reviewer_url, title, comment, A.status, A.create_date, A.update_date, name';
		$select="review_id, A.product_id, reviewer_name, sex, recommend_level, ";
        $select.="reviewer_url, title, comment, A.status, A.create_date, A.update_date, name, A.order_id, C.sending_date, C.order_id";
        // $from = 'dtb_review AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id '; 

        $arrReview = $objQuery->select($select, $from, $where, $arrWhereVal);

        return $arrReview;
    }
    
    // レビューデータの集計
    function lfReviewReflection() {
        // 男目線
        $objQuery = SC_Query_Ex::getSingletonInstance(); 
        $mens_sql =  <<< EOF
UPDATE dtb_products
    SET mens_review_count = (
        SELECT COUNT(dtr.product_id) FROM dtb_review AS dtr
            WHERE dtr.product_id = dtb_products.product_id AND
            dtr.del_flg = 0 AND
            dtb_products.del_flg = 0 AND
            dtr.sex = 1
            GROUP BY dtb_products.product_id
            ),
    mens_review_avg = (
        SELECT AVG(recommend_level) FROM dtb_review AS dtr
            WHERE dtr.product_id = dtb_products.product_id AND
            dtr.del_flg = 0 AND
            dtb_products.del_flg = 0 AND
            dtr.sex = 1
            GROUP BY dtb_products.product_id
            )
    WHERE dtb_products.del_flg = 0 AND dtb_products.product_id IN (SELECT product_id FROM dtb_review WHERE status = 1 AND del_flg = 0 AND sex = 1 GROUP BY (product_id));
EOF;
        // ユーザレビュー
        $womens_sql =  <<< EOF
UPDATE dtb_products
    SET womens_review_count = (
        SELECT COUNT(dtr.product_id) FROM dtb_review AS dtr
            WHERE dtr.product_id = dtb_products.product_id AND
            dtr.del_flg = 0 AND
            dtb_products.del_flg = 0 AND
            dtr.sex = 2
            GROUP BY dtb_products.product_id
            ),
    womens_review_avg = (
        SELECT AVG(recommend_level) FROM dtb_review AS dtr
            WHERE dtr.product_id = dtb_products.product_id AND
            dtr.del_flg = 0 AND
            dtb_products.del_flg = 0 AND
            dtr.sex = 2
            GROUP BY dtb_products.product_id
            )
    WHERE dtb_products.del_flg = 0 AND dtb_products.product_id IN (SELECT product_id FROM dtb_review WHERE status = 1 AND  del_flg = 0 AND sex = 2 GROUP BY (product_id));
EOF;
        $objQuery->exec($mens_sql );
        $objQuery->exec($womens_sql );
    }
}
