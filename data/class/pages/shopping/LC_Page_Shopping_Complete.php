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

require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * ご注文完了 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.

 * @version $Id:LC_Page_Shopping_Complete.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class LC_Page_Shopping_Complete extends LC_Page_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_title = 'ご注文完了';
        $this->httpCacheControl('nocache');
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
        // プラグインなどで order_id を取得する場合があるため,  ここで unset する
        unset($_SESSION['order_id']);
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        $this->arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $this->tpl_order_id = $_SESSION['order_id'];

        // ▼ishibashi
        $objQuery = new SC_Query();
        $objDb = new SC_Helper_DB_Ex();

        global $objCampaignSess;
        $this->objCampaignSess = new SC_CampaignSession();

        $this->objSiteSess = new SC_SiteSession_Ex();
        $this->objCartSess = new SC_CartSession_Ex();
        $this->objCustomer = new SC_Customer_Ex();

        $this->objPurchase = new SC_Helper_Purchase_Ex();

        // 初回表示のみorder_idがセットされていれば処理
        if (isset($this->tpl_order_id)) {

            $order_id = $this->tpl_order_id;

            // 一時受注テーブルの読み込み
            $arrData = $objDb->getOrderTemp($order_id);
            //$uniqid = $arrData['order_temp_id'];

            // 会員情報登録処理
            if ($this->objCustomer->isLoginSuccess(true)) {
                // 新お届け先の登録
                //$this->lfSetNewAddr($uniqid, $this->objCustomer->getValue('customer_id'));
                // 購入集計を顧客テーブルに反映 ishibashi ここでポイント合算処理される
                $this->lfSetCustomerPurchase($this->objCustomer->getValue('customer_id'), $arrData, $objQuery);
            } else {
                //購入時強制会員登録
                switch(PURCHASE_CUSTOMER_REGIST) {
                //無効
                case '0':
                    // 購入時会員登録
                    if(isset($arrData['member_check']) && $arrData['member_check'] == '1') {
                        // 仮会員登録
                        $customer_id = $this->lfRegistPreCustomer($arrData, $this->arrInfo);
                        // 購入集計を顧客テーブルに反映
                    }
                    break;
                //有効
                case '1':
                    // 仮会員登録
                    $customer_id = $this->lfRegistPreCustomer($arrData, $this->arrInfo);
                    // 購入集計を顧客テーブルに反映 ishibashi ここでポイント合算処理される
                    $this->lfSetCustomerPurchase($customer_id, $arrData, $objQuery);
                    break;
                }
            }


            $arrData['sending_date'] = $_SESSION['cart']['send_date'];
            $send_date = $arrData['sending_date'];
            
            // 一時受注テーブルを受注テーブルに格納する
            //$order_id = $this->lfRegistOrder($objQuery, $arrData);
            
            // 発送日の更新
            $this->lfUpdateSendingDate($objQuery, $send_date, $order_id);

            unset($arrData['sending_date']);
            
            // カート商品を受注詳細テーブルに格納する
            $this->lfRegistOrderDetail($objQuery, $order_id, $this->objCartSess, $arrData);

            // 一時受注テーブルを削除 update？
            $this->lfDeleteTempOrder($objQuery, $order_id);
            
            // キャンペーンからの遷移の場合登録する。
            if (!defined("MOBILE_SITE")) {
                if($this->objCampaignSess->getIsCampaign() and $this->objCartSess->chkCampaign($this->objCampaignSess->getCampaignId())) {
                    $this->lfRegistCampaignOrder($objQuery, $objCampaignSess, $order_id);
                }
            }


            // 商品予約テーブルへ登録
            $this->lfRegistOrderReserved($objQuery, $order_id, $this->objCartSess);

            // 受注完了メールを送信 add ishibashi
            $this->objPurchase->sendOrderMail($order_id);

            // セッションカート内の商品を削除する
            $this->objCartSess->delAllProducts();

            unset($_SESSION['cart']['send_date']);
            

            $arrResults = $objQuery->getall("SELECT memo02, memo05 FROM dtb_order WHERE order_id = ? ", array($order_id));
            if (count($arrResults) > 0) {
                if (isset($arrResults[0]["memo02"]) || isset($arrResults[0]["memo05"])) {
                    $arrOther = unserialize($arrResults[0]["memo02"]);
                    // 完了画面から送信する決済内容
                    $arrModuleParam = unserialize($arrResults[0]["memo05"]);

                    // データを編集
                    foreach($arrOther as $key => $val){
                        // URLの場合にはリンクつきで表示させる 
                        if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $val["value"])) {
                            $arrOther[$key]["value"] = "<a href='#' onClick=\"window.open('". $val["value"] . "'); \" >" . $val["value"] ."</a>";
                        }
                    }

                    $this->arrOther = $arrOther;
                    $this->arrModuleParam = $arrModuleParam;
                }
            }

            // アフィリエイト用コンバージョンタグの設定
            $this->tpl_conv_page = AFF_SHOPPING_COMPLETE;
            $this->tpl_aff_option = "order_id=$order_id";

            //合計価格の取得
            $total = $objQuery->get("total", "dtb_order", "order_id = ? ", array($order_id));
            if($total != "") {
                $this->tpl_aff_option.= "|total=$total";
            }

            // レントラックスアフィリエイトタグ関連
            $subtotal = $objQuery->get("subtotal", "dtb_order", "order_id = ? ", array($order_id));
            if($subtotal != ""){
              $this->subtotal = $subtotal;
            }

              // eコマーストラッキングコード（2012.12.18福田類子）
			$sql_item = "SELECT product_name,product_code,price,quantity FROM dtb_order_detail WHERE order_id = ?";
			$arrItems = $objQuery->getall($sql_item, array($order_id));
			$this->arrItems = $arrItems;
			$sql_order = "SELECT total,tax,deliv_fee,order_pref FROM dtb_order WHERE order_id = ?";
			$arrOrder = array_shift($objQuery->getall($sql_order, array($order_id)));
			$this->orderId = $order_id;
			$this->total = $arrOrder['total'];
			$this->tax = $arrOrder['tax'];
			$this->deliv_fee = $arrOrder['deliv_fee'];
			$this->order_pref = $objQuery->get("name", "mtb_pref", "id = ? ", array($arrOrder['order_pref']));

			// TradeSafe連携用
            if (function_exists('sfTSRequest')) {
                sfTSRequest($order_id);
            }

            // キャンペーンからの遷移かチェック
            $this->is_campaign = $this->objCampaignSess->getIsCampaign();
            $this->campaign_dir = $this->objCampaignSess->getCampaignDir();
            $this->tpl_mainpage = 'shopping/complete_wide.tpl';
            // フレームを選択(キャンペーンページから遷移なら変更
        }
    }

    /**
     * 決済モジュールから遷移する場合があるため, トークンチェックしない.
     *
     * @param  boolean $is_admin 管理画面でエラー表示をする場合 true
     */
    public function doValidToken($is_admin = false)
    {
        // nothing.
    }

    // 20200703 ishibashi 予約情報テーブルに登録
	// 商品予約情報テーブルへ登録
    function lfRegistOrderReserved(&$objQuery, $order_id, &$objCartSess) {

        $objDb = new SC_Helper_DB_Ex();
        // カート内情報の取得
        $arrCart = $objCartSess->getCartList();
        $max = count($arrCart);

        // 既に存在する詳細レコードを消しておく
        $objQuery->delete("dtb_products_reserved", "order_id = $order_id");

        $send_date = $_SESSION["cart"]["send_date"];
        $objReserveUtil = new SC_Reserve_Utils();

        $ary_rental_day = $objReserveUtil->getRentalDay($send_date);

        $temp_send_day_time = strtotime($send_date);
        $reserved_from = date("Y-m-d",strtotime("-5 days", $temp_send_day_time));
        $reserved_to = date("Y-m-d",strtotime("+5 days", $temp_send_day_time));

        for ($i = 0; $i < $max; $i++) {
            // 商品規格情報の取得
            $arrData = $objDb->sfGetProductsClass($arrCart[$i]['id']);
            
            // 存在する商品のみ表示する。
            if($arrData != "") {
                if (DB_TYPE == "pgsql") {
                    $reserved_id  = $objQuery->nextval("dtb_products_reserved_reserved_id","reserved_id");
                }elseif (DB_TYPE == "mysql") {
                    $reserved_id = $objQuery->get_auto_increment("dtb_products_reserved");
                }
                
                $sqlval['order_id'] = $order_id;
                $sqlval['product_id'] = $arrCart[$i]['id'][0];
                $sqlval['reserved_type'] = RESERVED_TYPE_ORDER;
                $sqlval['customer_id'] = $_SESSION['customer']['customer_id'];
                $sqlval['sending_date'] = $send_date;
                $sqlval['reserved_from'] = $reserved_from;
                $sqlval['reserved_to'] = $reserved_to;
                $sqlval['use_memo'] = $ary_rental_day["rental_day"];
                $sqlval['memo'] = "";
                $sqlval['create_date'] = "now()";
                $sqlval['update_date'] = "now()";

                // INSERT|UPDATEの実行
                $cnt = $objQuery->count("dtb_products_reserved", "reserved_id = ?", array($reserved_id));
                if($cnt > 0){
                    $objQuery->update("dtb_products_reserved", $sqlval, "reserved_id = ?", array($reserved_id));
                }else{
                    $objQuery->insert("dtb_products_reserved", $sqlval);
                }
            
            } else {
                if (defined("MOBILE_SITE")) {
                    SC_Utils_Ex::sfDispSiteError(CART_NOT_FOUND, "", false, "", true);
                } else {
                    SC_Utils_Ex::sfDispSiteError(CART_NOT_FOUND);
                }
            }
        }
    }

    // 20200703 ishibashi
    function lfRegistOrder($objQuery, $arrData) {
        $sqlval = $arrData;

        
        // 受注テーブルに書き込まない列を除去
        unset($sqlval['mailmaga_flg']);		// メルマガチェック
        unset($sqlval['deliv_check']);		// 別のお届け先チェック
        unset($sqlval['point_check']);		// ポイント利用チェック
        unset($sqlval['member_check']);		// 購入時会員チェック
        unset($sqlval['password']);			// ログインパスワード
        unset($sqlval['reminder']);			// リマインダー質問
        unset($sqlval['reminder_answer']);	// リマインダー答え
        unset($sqlval['mail_flag']);		// メールフラグ
        unset($sqlval['session']);		    // セッション情報

        // 注文ステータス:指定が無ければ新規受付に設定
        if($sqlval["status"] == ""){
            $sqlval['status'] = '1';
            if(isset($_SESSION["cart"]["dongbong_info"])){
	        	if($_SESSION["cart"]["dongbong_info"]["flag"]){
	        		$sqlval['status'] = '7';
	        	}
            }
        }


        // 別のお届け先を指定していない場合、配送先に登録住所をコピーする。
        if($arrData["deliv_check"] == "-1") {
            $sqlval['deliv_name01'] = $arrData['order_name01'];
            $sqlval['deliv_name02'] = $arrData['order_name02'];
            $sqlval['deliv_kana01'] = $arrData['order_kana01'];
            $sqlval['deliv_kana02'] = $arrData['order_kana02'];
            $sqlval['deliv_pref'] = $arrData['order_pref'];
            $sqlval['deliv_zip01'] = $arrData['order_zip01'];
            $sqlval['deliv_zip02'] = $arrData['order_zip02'];
            $sqlval['deliv_addr01'] = $arrData['order_addr01'];
            $sqlval['deliv_addr02'] = $arrData['order_addr02'];
            $sqlval['deliv_tel01'] = $arrData['order_tel01'];
            $sqlval['deliv_tel02'] = $arrData['order_tel02'];
            $sqlval['deliv_tel03'] = $arrData['order_tel03'];
        }


        $order_id = $arrData['order_id'];		// 注文番号
        $sqlval['create_date'] = 'now()';		// 受注日

        if(isset($_SESSION["cart"]["dongbong_info"])){
	        if($_SESSION["cart"]["dongbong_info"]["flag"]){
	        	$sqlval['include_flag'] = 1;
	        	$sqlval['include_orderid'] = $_SESSION["cart"]["dongbong_info"]["order_no"];		// 同梱受注ID
	        	$objQuery->update("dtb_order", array("include_orderid"=>$order_id), "order_id = ? ", array($_SESSION["cart"]["dongbong_info"]["order_no"]));
	        }
        }
        
        //キャンペーンID
        if (!defined("MOBILE_SITE")) {
            if($objCampaignSess->getIsCampaign()) $sqlval['campaign_id'] = $objCampaignSess->getCampaignId();
        }


        // 20200603 ihibashi
        // 商品の二重登録になる。
        // 商品としては二重登録になるがルミーズ側の決済が入っていないため古い方をアップデート
        //$order_id = $objQuery->nextVal('dtb_order_order_id');
        $sqlval['order_id'] = $order_id;
        $objQuery->insert("dtb_order", $sqlval);


        return $order_id;
    }
    
    // ishibashi  受注詳細テーブルへ登録
    function lfRegistOrderDetail(&$objQuery, $order_id, &$objCartSess, & $arrOrderData = null) {
        $objDb = new SC_Helper_DB_Ex();
        // カート内情報の取得
        $arrCart = $objCartSess->getCartList();
        $max = count($arrCart);
        // 既に存在する詳細レコードを消しておく。
		$objQuery->delete('dtb_order_detail', 'order_id = ?', array($order_id));
        // 規格名一覧
        $arrClassName = $objDb->sfGetIDValueList("dtb_class", "class_id", "name");
        // 規格分類名一覧
        $arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");

        $blnWedOrderFlag = false;
        $orderDate = "";
        if(!empty($arrOrderData) && mb_strpos($arrOrderData["deliv_date"], "金") === false){ // 金曜日以外の場合
        	$blnWedOrderFlag = true;
        	$orderDate = preg_replace("/日.{3}/u", "",  $arrOrderData["deliv_date"]);
        	$orderDate = preg_replace("/月/u",     "-", $orderDate);
        	
	        $cur_month = date("n");
	        list($deliv_month, $deliv_date) = preg_split('/[\/\.\-]/', $orderDate);
        	
	        if($cur_month == "12" && $deliv_month < $cur_month){
	        	$orderDate = (date("Y")+1)."-".$orderDate;
	        }else{
	        	$orderDate = date("Y")."-".$orderDate;
	        }
        }

        $send_date = $_SESSION["cart"]["send_date"]; // 2012.06.05 RCHJ Add

        $CONF = SC_Helper_DB_Ex::sfGetBasisData(); // 2014.6.9 RCHJ Add
        for ($i = 0; $i < $max; $i++) {
            $sqlval = array();//::N00083 Add 20131201
            // 商品規格情報の取得
            $arrData = $objDb->sfGetProductsClass($arrCart[$i]['id']);

            // 存在する商品のみ表示する。
            if($arrData != "") {
                $sqlval['order_id'] = $order_id;
                $sqlval['product_id'] = $arrCart[$i]['id'][0];
                $sqlval['classcategory_id1'] = $arrCart[$i]['id'][1];
                $sqlval['classcategory_id2'] = $arrCart[$i]['id'][2];
                $sqlval['product_name'] = $arrData['name'];
                $sqlval['product_code'] = $arrData['product_code'];
                $sqlval['classcategory_name1'] = $arrClassCatName[$arrData['classcategory_id1']];
                $sqlval['classcategory_name2'] = $arrClassCatName[$arrData['classcategory_id2']];
                $sqlval['point_rate'] = $arrCart[$i]['point_rate'];
                // 20200717 ishibashi 
                //$sqlval['price'] = $arrCart[$i]['price'];
                $sqlval['price'] = $arrData['price02'];
                $sqlval['quantity'] = $arrCart[$i]['quantity'];
                $sqlval['set_pid'] = $arrCart[$i]['set_pid'];
                $sqlval['set_ptype'] = $arrCart[$i]['set_ptype'];
                $sqlval['tax_rate'] = $CONF['tax']; // 2014.6.9 RCHJ Add
                $sqlval['tax_rule'] = $CONF['tax_rule']; // 2014.6.9 RCHJ Add
                $sqlval['product_class_id'] = $arrData['product_class_id'];

				//$this->lfReduceStock($objQuery, $arrCart[$i]['id'], $arrCart[$i]['quantity']);
				$this->lfReduceStock($objQuery, $arrCart[$i]['id'], $send_date);

                $sqlval['order_detail_id'] = 1 + $objQuery->max('order_detail_id', 'dtb_order_detail');

                // INSERTの実行
                $objQuery->insert("dtb_order_detail", $sqlval);
                
                $orderDate = "2014-1-1";
                if($blnWedOrderFlag){
	                $objQuery->update("dtb_products_class", array("wed_flag"=>1, "shipping_date"=>$orderDate, "update_date"=>"now()"),
	                		 "product_id = ? and classcategory_id1 = ? and classcategory_id2 = ?",
	                		array($sqlval['product_id'], $sqlval['classcategory_id1'], $sqlval['classcategory_id2']));
                }
            } else {
                if (defined("MOBILE_SITE")) {
                    SC_Utils_Ex::sfDispSiteError(CART_NOT_FOUND, "", false, "", true);
                } else {
                    SC_Utils_Ex::sfDispSiteError(CART_NOT_FOUND);
                }
            }
        }
    }

    // 20200703 ishibashi
    function lfUpdateSendingDate(&$objQuery, $send_date, $order_id) {
        $where = 'order_id = ?';
        $sqlval['sending_date'] = $send_date;
        $objQuery->update('dtb_order', $sqlval, 'order_id = ?', array($order_id));
    }

    function lfDeleteTempOrder(&$objQuery, $uniqid) {
        $where = "order_temp_id = ?";
        $sqlval['del_flg'] = 1;
        $objQuery->update("dtb_order_temp", $sqlval, $where, array($uniqid));
        // $objQuery->delete("dtb_order_temp", $where, array($uniqid));
    }

    // 会員登録（仮登録）
    function lfRegistPreCustomer($arrData, $arrInfo) {
        // 購入時の会員登録
        $sqlval['name01'] = $arrData['order_name01'];
        $sqlval['name02'] = $arrData['order_name02'];
        $sqlval['kana01'] = $arrData['order_kana01'];
        $sqlval['kana02'] = $arrData['order_kana02'];
        $sqlval['zip01'] = $arrData['order_zip01'];
        $sqlval['zip02'] = $arrData['order_zip02'];
        $sqlval['pref'] = $arrData['order_pref'];
        $sqlval['addr01'] = $arrData['order_addr01'];
        $sqlval['addr02'] = $arrData['order_addr02'];
        $sqlval['email'] = $arrData['order_email'];
        $sqlval['tel01'] = $arrData['order_tel01'];
        $sqlval['tel02'] = $arrData['order_tel02'];
        $sqlval['tel03'] = $arrData['order_tel03'];
        $sqlval['fax01'] = $arrData['order_fax01'];
        $sqlval['fax02'] = $arrData['order_fax02'];
        $sqlval['fax03'] = $arrData['order_fax03'];
        $sqlval['sex'] = $arrData['order_sex'];
        $sqlval['password'] = $arrData['password'];
        $sqlval['reminder'] = $arrData['reminder'];
        $sqlval['reminder_answer'] = $arrData['reminder_answer'];

        // メルマガ配信用フラグの判定
        switch($arrData['mail_flag']) {
        case '1':	// HTMLメール
            $mail_flag = 4;
            break;
        case '2':	// TEXTメール
            $mail_flag = 5;
            break;
        case '3':	// 希望なし
            $mail_flag = 6;
            break;
        default:
            $mail_flag = 6;
            break;
        }
        // メルマガフラグ
        $sqlval['mailmaga_flg'] = $mail_flag;

        // 会員仮登録
        $sqlval['status'] = 1;
        // URL判定用キー
        $sqlval['secret_key'] = SC_Utils_Ex::sfGetUniqRandomId("t");

        $objQuery = new SC_Query();
        $sqlval['create_date'] = "now()";
        $sqlval['update_date'] = "now()";
        $objQuery->insert("dtb_customer", $sqlval);

        // 顧客IDの取得
        $arrRet = $objQuery->select("customer_id", "dtb_customer", "secret_key = ?", array($sqlval['secret_key']));
        $customer_id = $arrRet[0]['customer_id'];

        //　仮登録完了メール送信
        $objMailPage = $this;
        $objMailPage->to_name01 = $arrData['order_name01'];
        $objMailPage->to_name02 = $arrData['order_name02'];
        $objMailPage->CONF = $arrInfo;
        $objMailPage->uniqid = $sqlval['secret_key'];
        $objMailView = new SC_SiteView();
        $objMailView->assignobj($objMailPage);
        $body = $objMailView->fetch("mail_templates/customer_mail.tpl");

        $mailHelper = new SC_Helper_Mail_Ex();

        $objMail = new SC_SendMail();
        $objMail->setItem(
                            ''										//　宛先
                            , $mailHelper->sfMakeSubject($objQuery,$objMailView,$objMailPage,"会員登録のご確認")		//　サブジェクト
                            , $body									//　本文
                            , $arrInfo['email03']					//　配送元アドレス
                            , $arrInfo['shop_name']					//　配送元　名前
                            , $arrInfo["email03"]					//　reply_to
                            , $arrInfo["email04"]					//　return_path
                            , $arrInfo["email04"]					//  Errors_to
                            , $arrInfo["email01"]					//  Bcc
                                                            );
        // 宛先の設定
        $name = $arrData['order_name01'] . $arrData['order_name02'] ." 様";
        $objMail->setTo($arrData['order_email'], $name);
        $objMail->sendMail();

        return $customer_id;

    }

    function lfSetNewAddr($uniqid, $customer_id) {
        $objQuery = new SC_Query();
        $diff = false;
        $find_same = false;

        $col = "deliv_name01,deliv_name02,deliv_kana01,deliv_kana02,deliv_tel01,deliv_tel02,deliv_tel03,deliv_zip01,deliv_zip02,deliv_pref,deliv_addr01,deliv_addr02";
        $where = "order_temp_id = ?";
        $arrRet = $objQuery->select($col, "dtb_order_temp", $where, array($uniqid));

        // 要素名のdeliv_を削除する。
        foreach($arrRet[0] as $key => $val) {
            $keyname = preg_replace("/^deliv_/", "", $key);
            $arrNew[$keyname] = $val;
        }

        // 会員情報テーブルとの比較
        $col = "name01,name02,kana01,kana02,tel01,tel02,tel03,zip01,zip02,pref,addr01,addr02";
        $where = "customer_id = ?";
        $arrCustomerAddr = $objQuery->select($col, "dtb_customer", $where, array($customer_id));

        // 会員情報の住所と異なる場合
        if($arrNew != $arrCustomerAddr[0]) {
            // 別のお届け先テーブルの住所と比較する
            $col = "name01,name02,kana01,kana02,tel01,tel02,tel03,zip01,zip02,pref,addr01,addr02";
            $where = "customer_id = ?";
            $arrOtherAddr = $objQuery->select($col, "dtb_other_deliv", $where, array($customer_id));

            foreach($arrOtherAddr as $arrval) {
                if($arrNew == $arrval) {
                    // すでに同じ住所が登録されている
                    $find_same = true;
                }
            }

            if(!$find_same) {
                $diff = true;
            }
        }

        // 新しいお届け先が登録済みのものと異なる場合は別のお届け先テーブルに登録する
        if($diff) {
            $sqlval = $arrNew;
            $sqlval['customer_id'] = $customer_id;
            $objQuery->insert("dtb_other_deliv", $sqlval);
        }
    }


    // 購入情報を会員テーブルに登録する
    function lfSetCustomerPurchase($customer_id, $arrData, &$objQuery) {
        $col = "first_buy_date, last_buy_date, buy_times, buy_total, point";
        $where = "customer_id = ?";
        $arrRet = $objQuery->select($col, "dtb_customer", $where, array($customer_id));
        $sqlval = $arrRet[0];

        if($sqlval['first_buy_date'] == "") {
            $sqlval['first_buy_date'] = "Now()";
        }
        $sqlval['last_buy_date'] = "Now()";
        $sqlval['buy_times']++;
        $sqlval['buy_total']+= $arrData['total'];
        if (USE_POINT === false) {
            $sqlval['point'] = $sqlval['point'];
        } else {
            //$sqlval['point'] = ($sqlval['point'] - $arrData['use_point']);
            $sqlval['point'] = ($sqlval['point'] + $arrData['add_point'] - $arrData['use_point']);
        }

        $objQuery->update("dtb_customer", $sqlval, $where, array($customer_id));
    }

    
    function lfReduceStock(&$objQuery, $arrID, $send_date) {
        $where = "product_id = ? and (sending_date = ? or (reserved_from <= ? and reserved_to >= ?))";
        $arrval = array($arrID[0], $send_date, $send_date, $send_date);
        
        $arr_rows = $objQuery->select("reserved_id", "dtb_products_reserved", $where, $arrval);
        
        $where = "product_id = ?";
        $arrval = array($arrID[0]);
        $arrRet = $objQuery->select("stock,stock_unlimited", "dtb_products_class", $where, $arrval);
        
        if(count($arr_rows) >= $arrRet[0]['stock'] || empty($send_date)){//::N00083 Change 20131201
            $objQuery->rollback();
            SC_Utils_Ex::sfDispSiteError(SOLD_OUT, "", true);
        }
    }
}
