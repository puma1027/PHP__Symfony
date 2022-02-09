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

require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_DeliveryAddr.php';

/**
 * お届け先追加 のページクラス(拡張).
 *
 * LC_Page_Mypage_DeliveryAddr をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_DeliveryAddr_Ex extends LC_Page_Mypage_DeliveryAddr
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        // 20200520 ishibashi 独自の判断
//        $this->skip_load_page_layout = true;
//   //     parent::init();
//        // ======= RCHJ remark and add 2013.06.12 =====
        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
        	$this->tpl_mainpage =  SPHONE_TEMPLATE_DIR. 'mypage/delivery_addr.tpl';
        }else{
        	$this->tpl_mainpage = TEMPLATE_REALDIR .'mypage/delivery_addr.tpl';
        }

        $this->tpl_subtitle = "新しいお届け先の追加･変更";
        // ================ End ============
//        $this->tpl_title = "新しいお届け先の追加･変更";
        $masterData = new SC_DB_MasterData_Ex();
//       $this->arrPref= $masterData->getMasterData("mtb_pref",
//                            array("id", "name", "rank"));
//        $this->httpCacheControl('nocache');
// ishibashi

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
        $objView = new SC_SiteView(false);
        $objQuery = new SC_Query();
        $objCustomer = new SC_Customer();
        $ParentPage = MYPAGE_DELIVADDR_URLPATH;

        // GETでページを指定されている場合には指定ページに戻す
        if (isset($_GET['page'])) {
            $ParentPage = htmlspecialchars($_GET['page'],ENT_QUOTES);
        }else if(isset($_POST['ParentPage'])) {
            $ParentPage = htmlspecialchars($_POST['ParentPage'],ENT_QUOTES);
        }
        $this->ParentPage = $ParentPage;

        //ログイン判定
        if (!$objCustomer->isLoginSuccess()){
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }

        if (!isset($_POST['mode'])) $_POST['mode'] = "";
        if (!isset($_GET['other_deliv_id'])) $_GET['other_deliv_id'] = "";

        if ($_POST['mode'] == ""){
            $_SESSION['other_deliv_id'] = $_GET['other_deliv_id'];
        }

        if ($_GET['other_deliv_id'] != ""){
            //不正アクセス判定
            $flag = $objQuery->count("dtb_other_deliv", "customer_id=? AND other_deliv_id=?", array($objCustomer->getValue("customer_id"), $_SESSION['other_deliv_id']));
            if (!$objCustomer->isLoginSuccess() || $flag == 0){
                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
            }
        }

        //別のお届け先ＤＢ登録用カラム配列
        $arrRegistColumn = array(
                                 array(  "column" => "name01",		"convert" => "aKV" ),
                                 array(  "column" => "name02",		"convert" => "aKV" ),
                                 array(  "column" => "kana01",		"convert" => "CKV" ),
                                 array(  "column" => "kana02",		"convert" => "CKV" ),
                                 array(  "column" => "zip01",		"convert" => "n" ),
                                 array(  "column" => "zip02",		"convert" => "n" ),
                                 array(  "column" => "pref",		"convert" => "n" ),
                                 array(  "column" => "addr01",		"convert" => "aKV" ),
                                 array(  "column" => "addr02",		"convert" => "aKV" ),
                                 array(  "column" => "tel01",		"convert" => "n" ),
                                 array(  "column" => "tel02",		"convert" => "n" ),
                                 array(  "column" => "tel03",		"convert" => "n" ),
                                 array(  "column" => "usebox",		"convert" => "n" ),
                                 );

         if ($_GET['other_deliv_id'] != ""){
            //別のお届け先情報取得
            $arrOtherDeliv = $objQuery->select("*", "dtb_other_deliv", "other_deliv_id=? ", array($_SESSION['other_deliv_id']));

            $arrData = $arrOtherDeliv[0];

            $this->usebox_check = "";

            if(strstr($arrData['addr02'],'(不在時宅配ボックス)')){
            	$arrData['addr02'] = str_replace("(不在時宅配ボックス)", "", $arrData['addr02']);
            	$this->usebox_check = "checked";
            }

            $this->arrForm = $arrData;

        }
		
		if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
        	$this->tpl_mainpage =  SPHONE_TEMPLATE_DIR. 'mypage/delivery_addr.tpl';
        }else{
        	$this->tpl_mainpage = TEMPLATE_REALDIR .'mypage/delivery_addr.tpl';
        }

        switch ($_POST['mode']) {					
            case 'edit':
                $_POST = $this->lfConvertParam($_POST,$arrRegistColumn);
                $this->arrErr = $this->lfErrorCheck($_POST);
                if ($this->arrErr){
                    foreach ($_POST as $key => $val){
                        if ($val != "") $this->arrForm[$key] = $val;
                    }
                } else {
                    //別のお届け先登録数の取得
                    $deliv_count = $objQuery->count("dtb_other_deliv", "customer_id=?", array($objCustomer->getValue('customer_id')));
                    if ($deliv_count < DELIV_ADDR_MAX or isset($_POST['other_deliv_id'])){
                        if(strlen($_POST['other_deliv_id'] != 0)){
                            $deliv_count = $objQuery->count("dtb_other_deliv","customer_id=? and other_deliv_id = ?" ,array($objCustomer->getValue('customer_id'), $_POST['other_deliv_id']));
                            if ($deliv_count == 0) {
                                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                            }else{
                                $this->lfRegistData($_POST,$arrRegistColumn, $objCustomer);
                            }
                        }else{
                            $this->lfRegistData($_POST,$arrRegistColumn, $objCustomer);
                        }
                    }
					
					if( $_POST['ParentPage'] == MYPAGE_DELIVADDR_URLPATH || $_POST['ParentPage'] == DELIV_URLPATH ){
						$this->tpl_onload = "fnUpdateParent('". $this->getLocation($_POST['ParentPage']) ."'); window.close();"; 
						 //SC_Response_Ex::sendRedirect(DELIV_URLPATH);
                         //SC_Response_Ex::actionExit();
                    }else{
                        SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                    }
                
                }
                
                break;
            }
            
            $objView->assignobj($this);
        //        $objView->display(SITE_FRAME);
            $objView->display($this->tpl_mainpage);
        }

        // 20200520 ishibashi独自の判断
        /* 登録実行 */
        function lfRegistData($array, $arrRegistColumn, &$objCustomer)
        {
            parent::lfRegistData();
            $objConn = SC_Query_Ex::getSingletonInstance();
            foreach ($arrRegistColumn as $data) {
                if (strlen($array[ $data["column"] ]) > 0) {
                    $arrRegist[ $data["column"] ] = $array[ $data["column"] ];
                }
            }



            $arrRegist['customer_id'] = $objCustomer->getvalue('customer_id');


            if(isset($arrRegist['usebox'])){

                if($arrRegist['usebox'] == "1"){
                    if(strstr($arrRegist['addr02'],'(不在時宅配ボックス)')){

                    }
                    else{
                        $arrRegist['addr02'] = $arrRegist['addr02']."(不在時宅配ボックス)";
                    }
                }
                else{
                    if(strstr($arrRegist['addr02'],'(不在時宅配ボックス)')){
                        $arrRegist['addr02'] = str_replace("(不在時宅配ボックス)", "", $arrRegist['addr02']);
                    }
                }

                unset($arrRegist['usebox']);
            }




            //-- 編集登録実行
            $objConn->query("BEGIN");

            //Y.C add

            $objConn->update("dtb_other_deliv", array('selected_flg'=> '0'), "customer_id = ".SC_Utils_Ex::sfQuoteSmart($objCustomer->getvalue('customer_id')));

           // $objConn->autoExecute("dtb_other_deliv", array('selected_flg'=> '0'),        "customer_id = "          . SC_Utils_Ex::sfQuoteSmart($objCustomer->getvalue('customer_id')));

            $arrRegist['selected_flg'] = 1;
            
            /*if ($array['other_deliv_id'] != ""){
                $objConn->autoExecute("dtb_other_deliv", $arrRegist,
                                      "other_deliv_id = "
                                   . SC_Utils_Ex::sfQuoteSmart($array["other_deliv_id"]));
            }else{
                $objConn->autoExecute("dtb_other_deliv", $arrRegist);
            }*/
             if ($array['other_deliv_id'] != ""){
                $objConn->update("dtb_other_deliv", $arrRegist, "other_deliv_id = " . SC_Utils_Ex::sfQuoteSmart($array["other_deliv_id"]));
            }else{
                $objConn->insert("dtb_other_deliv", $arrRegist);
            }
            $objConn->query("COMMIT");
        }

}
