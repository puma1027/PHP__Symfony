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

require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Confirm.php';

require_once MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';

/**
 * 入力内容確認 のページクラス(拡張).
 *
 * LC_Page_Shopping_Confirm をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Shopping_Confirm_Ex extends LC_Page_Shopping_Confirm
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        //::$this->tpl_mainpage = 'shopping/confirm.tpl';//::N00039 Del 20130430
        $this->tpl_mainpage = 'shopping/confirm_wide.tpl';//::N00039 Add 20130430
        $this->tpl_column_num = 1;
        $this->tpl_css = URL_DIR.'css/layout/shopping/confirm.css';
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrMAILMAGATYPE = $masterData->getMasterData("mtb_mail_magazine_type");
        $this->arrReminder = $masterData->getMasterData("mtb_reminder");

//        $this->allowClientCache();
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

    // 旧プロセス
    function action()
    {
        //決済処理中ステータスのロールバック
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        global $objCampaignSess;

        $objView = new SC_SiteView_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objSiteInfo = $objView->objSiteInfo;
        $objSiteSess = new SC_SiteSession_Ex();
        $objCampaignSess = new SC_CampaignSession();
        $objCustomer = new SC_Customer_Ex();

        $arrInfo = $objSiteInfo->data;
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();

        // レイアウトデザインの取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        //$objLayout->sfGetPageLayout($this, false, 'new_cart.php');//::N00039 Change 20130430

        // 前のページで正しく登録手続きが行われた記録があるか判定
        SC_Utils_Ex::sfIsPrePage($objSiteSess);

        // ユーザユニークIDの取得と購入状態の正当性をチェック
        $uniqid = SC_Utils_Ex::sfCheckNormalAccess($objSiteSess, $objCartSess);
        $this->tpl_uniqid = $uniqid;

        $this->cartKey = $objCartSess->getKey();

        // カート内商品のチェック
        $this->tpl_message = $objCartSess->checkProducts($this->cartKey);

        if (!SC_Utils_Ex::isBlank($this->tpl_message)) {
            SC_Response_Ex::sendRedirect(CART_URL);
            SC_Response_Ex::actionExit();
        }

        // ======== 2012.06.04 RCHJ Add
        $objReserveUtil = new SC_Reserve_Utils();
        $ary_rental_day = $objReserveUtil->getRentalDay($_SESSION["cart"]["send_date"]);
        $bln_holiday = false;
        if($ary_rental_day['method'] == RESERVE_PATTEN_HOLIDAY)
        {
            $bln_holiday = true;
        }
        // =========== end ==========

        // カート集計処理
        $objDb->sfTotalCart($this, $objCartSess, $arrInfo, $bln_holiday);
        // 一時受注テーブルの読込
        $arrData = $objDb->sfGetOrderTemp($uniqid);

        // 20210506 add ishibashi
        // 決済戻りの「あんしん保障」の不具合 // リセットする
        // カートの最終計算前にそのまま値として加算されるのを防ぐ
        if ( $arrData['writer_select'] === '5' || $arrData['writer_select'] === '10' )
        {
            if ( isset($arrData['relief_value']) && $arrData['relief_value'] === '500' )
            {
                $arrData['relief_value'] = 0;
                $arrData['payment_total'] -= 500;
            }
        }
        
        // カート集計を元に最終計算
        $arrData = $objDb->sfTotalConfirm($arrData, $this, $objCartSess, $arrInfo, $objCustomer, $objCampaignSess);

        // キャンペーンからの遷移で送料が無料だった場合の処理
        if($objCampaignSess->getIsCampaign()) {
            $deliv_free_flg = $objQuery->get("dtb_campaign", "deliv_free_flg", "campaign_id = ?", array($objCampaignSess->getCampaignId()));
            // 送料無料が設定されていた場合
            if($deliv_free_flg) {
                $arrData['payment_total'] -= $arrData['deliv_fee'];
                $arrData['deliv_fee'] = 0;
            }
        }


        // カート内の商品の売り切れチェック
        $objCartSess->chkSoldOut($objCartSess->getCartList($this->cartKey));

        // 会員ログインチェック
        if($objCustomer->isLoginSuccess(true)) {
            $this->tpl_login = '1';
            $this->tpl_user_point = $objCustomer->getValue('point');
        }

        // add ishibashi 20220121
        foreach ($this->arrProductsClass as $key => $val)
        {
            $this->arrProductsClass[$key] = SC_Utils_Ex::productReplaceWebp($val);
        }

        // 決済区分を取得する
        $payment_type = "";
        if($objDb->sfColumnExists("dtb_payment", "memo01")){
            // MEMO03に値が入っている場合には、モジュール追加されたものとみなす
            $sql = "SELECT memo03 FROM dtb_payment WHERE payment_id = ?";
            $arrPayment = $objQuery->getall($sql, array($arrData['payment_id']));
            $payment_type = $arrPayment[0]["memo03"];
        }
        $this->payment_type = $payment_type;


        //::メッセージカード　ー> あんしん保証申込みの有無
        if ($arrData['writer_select'] == '5')
        {
            $arrData['writer_select'] = '申込む';
            $arrData['relief_value'] = 500;

            $arrData['payment_total'] += 500;
        } else if ($arrData['writer_select'] === '10') { // 20210506 add ishibashi else→elseifで10指定しないと決済戻り時に全てなしに入ってくる

            $arrData['writer_select'] = 'なし';
            $arrData['relief_value'] = 0;
        }

        $this->tpl_mainpage = 'shopping/confirm_wide.tpl';

        // 決済モジュールを使用するかどうか
        $this->use_module = SC_Helper_Payment_Ex::useModule($arrData['payment_id']);

        switch ($this->getMode())
        {
        // 前のページに戻る
        case 'return':
            // 正常な推移であることを記録しておく
            $objSiteSess->setRegistFlag();

			SC_Response_Ex::sendRedirect(SHOPPING_PAYMENT_URLPATH);
            SC_Response_Ex::actionExit();
            break;
        case 'confirm':
        	//$now_day = strtolower(date("D"));
        	//$now_hour = date("G");
        	//$now_minute = date("i");
        	$this->tpl_open = true;
        	/*if($now_day === "wed" && $now_hour == 20
        			&& ($now_minute >=30 && $now_minute<=59)){
        		$this->tpl_open = false;

        		break ;
        	}*/

            // 注文番号を取得
            $arrData["order_id"] = $objPurchase->getNextOrderID();
            $_SESSION['order_id'] = $arrData['order_id'];

            // セッション情報を保持
            $arrData['session'] = serialize($_SESSION);


            // 集計結果を受注一時テーブルに反映
            //$objDb->sfRegistTempOrder($uniqid, $arrData);
            $objPurchase->saveOrderTemp($uniqid, $arrData,
                    $objCustomer);


            // 正常に登録されたことを記録しておく
            $objSiteSess->setRegistFlag();
            // 決済モジュールを使用する場合
            if($this->use_module) {

                // TODO 決済方法のモジュールは Plugin として実装したい
                $_SESSION['payment_id'] = $arrData['payment_id'];
                
                $objPurchase->completeOrder(ORDER_PENDING);

                SC_Response_Ex::sendRedirect(SHOPPING_MODULE_URLPATH);

            // 購入完了ページ
            }else{
                $objPurchase->completeOrder(ORDER_NEW);
                SC_Helper_Purchase_Ex::sendOrderMail($arrData['order_id'], $this);

				SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
            }
            SC_Response_Ex::actionExit();
            break;
        default:
            break;
        }

        $this->arrData = $arrData;
        $this->arrInfo = $arrInfo;
    	if(isset($_SESSION["cart"]["dongbong_info"]) && $_SESSION["cart"]["dongbong_info"]["flag"]){
            $this->arrData["dongbong_order_id"] = $_SESSION["cart"]["dongbong_info"]["order_no"];
        }
        // ご利用日・返却日の取得
        $this->subjectDate = $this->lfMakeSubjectDate($arrData['deliv_date']);
        $this->subjectDateConfirm = $this->lfMakeSubjectDateConfirm($arrData['deliv_date']);//::N00039 Add 20130501
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->init();
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        $objView = new SC_MobileView();
        $objCartSess = new SC_CartSession();
        $objSiteInfo = $objView->objSiteInfo;
        $objSiteSess = new SC_SiteSession();
        $objCustomer = new SC_Customer();
        $arrInfo = $objSiteInfo->data;
        $objQuery = new SC_Query();
        $objDb = new SC_Helper_DB_Ex();

        // 前のページで正しく登録手続きが行われた記録があるか判定
        SC_Utils_Ex::sfIsPrePage($objSiteSess, true);

        // ユーザユニークIDの取得と購入状態の正当性をチェック
        $uniqid = SC_Utils_Ex::sfCheckNormalAccess($objSiteSess, $objCartSess);
        $this->tpl_uniqid = $uniqid;

        // カート集計処理
        $objDb->sfTotalCart($this, $objCartSess, $arrInfo);
        // 一時受注テーブルの読込
        $arrData = $objDb->sfGetOrderTemp($uniqid);
        // カート集計を元に最終計算
        $arrData = $objDb->sfTotalConfirm($arrData, $this, $objCartSess, $arrInfo, $objCustomer);

        // カート内の商品の売り切れチェック
        $objCartSess->chkSoldOut($objCartSess->getCartList());

        // 会員ログインチェック
        if($objCustomer->isLoginSuccess(true)) {

            $this->tpl_login = '1';
            $this->tpl_user_point = $objCustomer->getValue('point');
        }
        // 決済区分を取得する
        $payment_type = "";
        if($objDb->sfColumnExists("dtb_payment", "memo01")){
            // MEMO03に値が入っている場合には、モジュール追加されたものとみなす
            $sql = "SELECT memo03 FROM dtb_payment WHERE payment_id = ?";
            $arrPayment = $objQuery->getall($sql, array($arrData['payment_id']));
            $payment_type = $arrPayment[0]["memo03"];
        }
        $this->payment_type = $payment_type;


        if (!isset($_POST['mode'])) $_POST['mode'] = "";

		//20140114
		// 社員へのメッセージのせいで追加する。
		$arrData['message'] = $_POST['message'];

        switch($_POST['mode']) {
            // 前のページに戻る
        case 'return':
            // 正常な推移であることを記録しておく
            $objSiteSess->setRegistFlag();
            $this->sendRedirect($this->getLocation(MOBILE_URL_SHOP_PAYMENT), true);
            exit;
            break;
        case 'confirm':
        	$now_day = strtolower(date("D"));
        	$now_hour = date("G");
        	$now_minute = date("i");
        	$this->tpl_open = true;
        	/*if($now_day === "wed" && $now_hour == 20
        			&& ($now_minute >=30 && $now_minute<=59)){
        		$this->tpl_open = false;

        		break ;
        	}*/

            // この時点で注文番号を確保しておく（クレジット、コンビニ決済で必要なため）
            // postgresqlとmysqlとで処理を分ける
            if (DB_TYPE == "pgsql") {
                $order_id = $objQuery->nextval("dtb_order","order_id");
            }elseif (DB_TYPE == "mysql") {
                $order_id = $objQuery->get_auto_increment("dtb_order");
            }
            $arrData["order_id"] = $order_id;

            // セッション情報を保持
            $arrData['session'] = serialize($_SESSION);

            // 集計結果を受注一時テーブルに反映
            $objDb->sfRegistTempOrder($uniqid, $arrData);
            // 正常に登録されたことを記録しておく
            $objSiteSess->setRegistFlag();

            // 決済方法により画面切替
            if($payment_type != "") {
                $_SESSION["payment_id"] = $arrData['payment_id'];
                //$this->sendRedirect($this->getLocation(MOBILE_URL_SHOP_MODULE), true);
                $this->sendRedirect($this->getLocation(SHOPPING_MODULE_URLPATH), true);
            }else{
                $this->sendRedirect($this->getLocation(MOBILE_SHOPPING_COMPLETE_URLPATH), true);
            }
            exit;
            break;
        default:
            break;
        }
        $this->arrData = $arrData;
        $this->arrInfo = $arrInfo;
        $objView->assignobj($this);
        $objView->display(SITE_FRAME);
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
     * ご利用日・返却日を生成する
     * @param $deliv_date
     */
    function lfMakeSubjectDate($deliv_date) {
        $weekday = array("日" => "previous Sunday", "月" => "previous Monday", "火" => "previous Tuesday",
                                    "水" => "previous Wednesday", "木" => "next Thursday", "金" => "next Friday","土" => "previous Saturday");
        // 曜日を取得
        $_arrWday = $this->lfGetWday(mb_substr($deliv_date, mb_strlen($deliv_date)-2, 1));
        // 曜日部分「(日)」とか切り捨てる
        $deliv_date = $deliv_date = mb_substr($deliv_date, 0, mb_strlen($deliv_date) - 3);
        $return_caption = RETURN_TIME."まで ポストに投函";
        // 年を取得
        $year = date('Y');
        // 置換
        $deliv_date = str_replace("月", "-", $deliv_date);
        $deliv_date = str_replace("日", "", $deliv_date);
        // YYYY-DD-MMの書式を作成
        $deliv_date = $year .'-' .$deliv_date;
        // お届け日のタイムスタンプ
        $target_time = strtotime($deliv_date);
        $arrSubjectData = array();
        // ご利用日
        $arrSubjectData['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        // ご返却日
        $arrSubjectData['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        return $arrSubjectData;
    }

//::N00039 Add 20130501
    /**
     * ご利用日・返却日を生成する(入力内容ご確認ページ用)
     * @param $deliv_date
     */
    function lfMakeSubjectDateConfirm($deliv_date) {
        $weekday = array("日" => "previous Sunday", "月" => "previous Monday", "火" => "previous Tuesday",
                                    "水" => "previous Wednesday", "木" => "next Thursday", "金" => "next Friday","土" => "previous Saturday");
        // 曜日を取得
        $_arrWday = $this->lfGetWday(mb_substr($deliv_date, mb_strlen($deliv_date)-2, 1));
        // 曜日部分「(日)」とか切り捨てる
        $deliv_date = $deliv_date = mb_substr($deliv_date, 0, mb_strlen($deliv_date) - 3);
        // 年を取得
        $year = date('Y');
        // 置換
        $deliv_date = str_replace("月", "-", $deliv_date);
        $deliv_date = str_replace("日", "", $deliv_date);
        // YYYY-DD-MMの書式を作成
        $deliv_date = $year .'-' .$deliv_date;
        // お届け日のタイムスタンプ
        $target_time = strtotime($deliv_date);
        $arrSubjectData = array();
        // ご利用日
        $arrSubjectData['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        // ご返却日
        $arrSubjectData['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")";
        return $arrSubjectData;
    }
//::N00039 end 20130501

    /**
     * お届け日から3日間の曜日を返す
     * $deliv_day_of_the_week お届け曜日
     */
    function lfGetWday($deliv_day_of_the_week) {
        $ret = array();
        switch ($deliv_day_of_the_week) {
            case "日":
                $ret[0] = "月";
                $ret[1] = "火";
                $ret[2] = "水";
                break;
            case "月":
                $ret[0] = "火";
                $ret[1] = "水";
                $ret[2] = "木";
                break;
            case "火":
                $ret[0] = "水";
                $ret[1] = "木";
                $ret[2] = "金";
                break;
            case "水":
                $ret[0] = "木";
                $ret[1] = "金";
                $ret[2] = "土";
                 break;
            case "木":
                $ret[0] = "金";
                $ret[1] = "土";
                $ret[2] = "日";
                break;
            case "金":
                $ret[0] = "土";
                $ret[1] = "日";
                $ret[2] = "月";
                break;
            case "土":
                $ret[0] = "日";
                $ret[1] = "月";
                $ret[2] = "火";
                break;
            default:
                break;
        }
        return $ret;
    }

}
