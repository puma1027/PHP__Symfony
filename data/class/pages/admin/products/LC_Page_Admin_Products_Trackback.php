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
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';


/**
 * トラックバック管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_Trackback extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'products/trackback.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'trackback';
        $this->tpl_pager = SMARTY_TEMPLATES_REALDIR . 'admin/pager.tpl';
        $this->tpl_subtitle = 'トラックバック管理';
        $this->tpl_maintitle = '商品管理';  

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPageMax = $masterData->getMasterData("mtb_page_max");
        $this->arrTrackBackStatus = $masterData->getMasterData("mtb_track_back_status");
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process() {
        $this->action();

        $this->sendResponse();
    }
    
    public function action()
    {
                                           
        
        $objQuery = SC_Query_Ex::getSingletonInstance(); 
           
        $objDate = new SC_Date();
        
        // 登録・更新検索開始年
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE("Y"));
        $this->arrStartYear = $objDate->getYear();
        $this->arrStartMonth = $objDate->getMonth();
        $this->arrStartDay = $objDate->getDay();
        // 登録・更新検索終了年
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE("Y"));
        $this->arrEndYear = $objDate->getYear();
        $this->arrEndMonth = $objDate->getMonth();
        $this->arrEndDay = $objDate->getDay();

        // 認証可否の判定
        //$objSess = new SC_Session();
//        SC_Utils_Ex::sfIsSuccess($objSess);
        

        // トラックバック情報のカラムの取得(viewとの結合のため、テーブルをAと定義しておく)
        $select = "A.trackback_id, A.product_id, A.blog_name, A.title, A.url, ";
        $select .= "A.excerpt, A.status, A.create_date, A.update_date, B.name";
        $from = "dtb_trackback AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id ";

        // 検索ワードの引き継ぎ
        foreach ($_POST as $key => $val)
        {
            if (preg_match("/^search_/", $key))
            {
                $this->arrHidden[$key] = $val;
            }
        }

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        // トラックバックの削除
        if ($_POST['mode'] == "delete") {
            $objQuery->exec("UPDATE dtb_trackback SET del_flg = 1, update_date = now() WHERE trackback_id = ?", array($_POST['trackback_id']));
        }

        if ($_POST['mode'] == 'search' || $_POST['mode'] == 'csv' || $_POST['mode'] == 'delete'){

            //削除されていない商品を検索
            $where="A.del_flg = 0 AND B.del_flg = 0";
            $this->arrForm = $_POST;

            //エラーチェック
            $this->arrErr = $this->lfCheckError();

            if (!$this->arrErr) {
                foreach ($_POST as $key => $val) {

                    $val = SC_Utils_Ex::sfManualEscape($val);

                    if ($val == "") {
                        continue;
                    }

                    switch ($key) {

                    case 'search_blog_name':
                        $val = preg_replace("/ /", "%", $val);
                        $val = preg_replace("/　/", "%", $val);
                        $where.= " AND A.blog_name ILIKE ? ";
                        $arrval[] = "%$val%";
                        break;

                    case 'search_blog_title':
                        $val = preg_replace("/ /", "%", $val);
                        $val = preg_replace("/　/", "%", $val);
                        $where.= " AND A.title ILIKE ? ";
                        $arrval[] = "%$val%";
                        break;

                    case 'search_blog_url':
                        $val = preg_replace("/ /", "%", $val);
                        $val = preg_replace("/　/", "%", $val);
                        $where.= " AND A.url ILIKE ? ";
                        $arrval[] = "%$val%";
                        break;

                    case 'search_status':
                        if (isset($_POST['search_status'])) {
                            $where.= " AND A.status = ? ";
                            $arrval[] = $val;
                        }
                        break;

                    case 'search_name':
                        $val = preg_replace("/ /", "%", $val);
                        $val = preg_replace("/　/", "%", $val);
                        $where.= " AND B.name ILIKE ? ";
                        $arrval[] = "%$val%";
                        break;

                    case 'search_product_code':
                        $val = preg_replace("/ /", "%", $val);
                        $val = preg_replace("/　/", "%", $val);
                        $where.= " AND B.product_id IN (SELECT product_id FROM dtb_products_class WHERE product_code ILIKE ? )";
                        $arrval[] = "%$val%";
                        break;

                    case 'search_startyear':
                        if (isset($_POST['search_startyear']) && isset($_POST['search_startmonth']) && isset($_POST['search_startday'])) {
                            $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_startyear'], $_POST['search_startmonth'], $_POST['search_startday']);
                            $where.= " AND A.create_date >= ? ";
                            $arrval[] = $date;
                        }
                        break;

                    case 'search_endyear':
                        if (isset($_POST['search_startyear']) && isset($_POST['search_startmonth']) && isset($_POST['search_startday'])) {
                            $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_endyear'], $_POST['search_endmonth'], $_POST['search_endday']);

                            $end_date = date("Y/m/d",strtotime("1 day" ,strtotime($date)));

                            $where.= " AND A.create_date <= cast('$end_date' as date) ";
                        }
                        break;
                    }

                }

            }

            $order = "A.create_date DESC";

            // ページ送りの処理
            if(is_numeric($_POST['search_page_max'])) {
                $page_max = $_POST['search_page_max'];
            } else {
                $page_max = SEARCH_PMAX;
            }

            if (!isset($arrval)) $arrval = array();

            $linemax = $objQuery->count($from, $where, $arrval);
            $this->tpl_linemax = $linemax;

            $this->tpl_pageno =
                isset($_POST['search_pageno']) ? $_POST['search_pageno'] : "";

            // ページ送りの取得
            $objNavi = new SC_PageNavi($this->tpl_pageno, $linemax, $page_max,
                                       "fnNaviSearchPage", NAVI_PMAX);
            $this->arrPagenavi = $objNavi->arrPagenavi;
            $startno = $objNavi->start_row;



            // 取得範囲の指定(開始行番号、行数のセット)
            $objQuery->setlimitoffset($page_max, $startno);

            // 表示順序
            $objQuery->setOrder($order);

            //検索結果の取得
            $this->arrTrackback = $objQuery->select($select, $from, $where, $arrval);

            //CSVダウンロード
            if ($_POST['mode'] == 'csv'){   
                                          
                    $this->lfDoOutputCsv($where, $arrval);

                    SC_Response_Ex::actionExit();
               

//                $objCSV = new SC_Helper_CSV_Ex();

                // オプションの指定
//               
                // CSV出力タイトル行の作成
//                $arrTitleCol = array('商品名',
//                                     'ブログ名',
//                                     'ブログ記事タイトル',
//                                     'ブログ記事内容',
//                                     '状態',
//                                     '投稿日'
//                                     );
//                $head = SC_Utils_Ex::sfGetCSVList($arrTitleCol);
//                $data = $objCSV->lfGetTrackbackCSV($where, '', $arrval);
//                
//                
                // CSVを送信する。
//                        list($fime_name, $data) = SC_Utils_Ex::sfGetCSVData($head.$data);

//                        $this->sendResponseCSV($fime_name, $data);
//                        SC_Response_Ex::actionExit();                                                                       
//                exit;
            }
        }
                                         
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
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
//        print_r ($where);die();
        $objCSV->sfDownloadCsv('6', $where, $arrWhereVal,  "A.trackback_id", true);  
    }
    // 入力エラーチェック
    function lfCheckError() {
        $objErr = new SC_CheckError();
        switch ($_POST['mode']){
        case 'search':
            $objErr->doFunc(array("投稿者", "search_startyear", "search_startmonth", "search_startday"), array("CHECK_DATE"));
            $objErr->doFunc(array("開始日", "search_startyear", "search_startmonth", "search_startday"), array("CHECK_DATE"));
            $objErr->doFunc(array("終了日", "search_endyear", "search_endmonth", "search_endday"), array("CHECK_DATE"));
            $objErr->doFunc(array("開始日", "終了日", "search_startyear", "search_startmonth", "search_startday", "search_endyear", "search_endmonth", "search_endday"), array("CHECK_SET_TERM"));
            break;

        case 'complete':
            $objErr->doFunc(array("おすすめレベル", "recommend_level"), array("SELECT_CHECK"));
            $objErr->doFunc(array("タイトル", "title", STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("コメント", "comment", LTEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            break;
        }
        return $objErr->arrErr;
    }
}
?>
