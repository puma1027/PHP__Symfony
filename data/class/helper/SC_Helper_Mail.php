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
 * メール関連 のヘルパークラス.
 *
 * @package Helper
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class SC_Helper_Mail
{
    /** メールテンプレートのパス */
    public $arrMAILTPLPATH;

    /**
     * LC_Pageオブジェクト.
     *
     * @var LC_Page
     */
    protected $objPage;

    /**
     * コンストラクタ.
     */
    public function __construct()
    {
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrMAILTPLPATH =  $masterData->getMasterData('mtb_mail_tpl_path');
        $this->arrPref = $masterData->getMasterData('mtb_pref');
        $this->arrCountry = $masterData->getMasterData('mtb_country');
    }

    /**
     * LC_Pageオブジェクトをセットします.
     *
     * @param LC_Page $objPage
     */
    public function setPage(LC_Page $objPage)
    {
        $this->objPage = $objPage;
    }

    /**
     * LC_Pageオブジェクトを返します.
     *
     * @return LC_Page
     */
    public function getPage()
    {
        return $this->objPage;
    }

    /* DBに登録されたテンプレートメールの送信 */

    /**
     * @param string $to_name
     * @param integer $template_id
     * @param LC_Page_Contact $objPage
     */
    public function sfSendTemplateMail($to, $to_name, $template_id, &$objPage, $from_address = '', $from_name = '', $reply_to = '', $bcc = '')
    {
        // メールテンプレート情報の取得
        $objMailtemplate = new SC_Helper_Mailtemplate_Ex();
        $mailtemplate = $objMailtemplate->get($template_id);
        $objPage->tpl_header = $mailtemplate['header'];
        $objPage->tpl_footer = $mailtemplate['footer'];
        $tmp_subject = $mailtemplate['subject'];

        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();

        $objMailView = new SC_SiteView_Ex();
        $objMailView->setPage($this->getPage());
        // メール本文の取得
        $objMailView->assignobj($objPage);
        $body = $objMailView->fetch($this->arrMAILTPLPATH[$template_id]);

        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        if ($from_address == '') $from_address = $arrInfo['email03'];
        if ($from_name == '') $from_name = $arrInfo['shop_name'];
        if ($reply_to == '') $reply_to = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $tosubject = $this->sfMakeSubject($tmp_subject, $objMailView);

        $objSendMail->setItem('', $tosubject, $body, $from_address, $from_name, $reply_to, $error, $error, $bcc);
        $objSendMail->setTo($to, $to_name);
        $objSendMail->sendMail();    // メール送信
    }

    /* 受注完了メール送信 */
    // ishibashi
//    public function sfSendOrderMail($order_id, $template_id, $subject = "", $header = "", $footer = "", $send = true) {
//
//        $objPage = new LC_Page();
//        $objSiteInfo = new SC_Helper_DB();
//        $arrInfo = $objSiteInfo->sfGetBasisData();
//        $objPage->arrInfo = $arrInfo;
//
//        $objQuery = new SC_Query();
//
//        $body = "";
//        if($subject == "" && $header == "" && $footer == "") {
//            // メールテンプレート情報の取得
//            $where = "template_id = ?";
//            $arrRet = $objQuery->select("subject, header, footer", "dtb_mailtemplate", $where, array($template_id));
//            $body .= $arrRet[0]['header'];
//            $body .= $arrRet[0]['footer'];
//            $tmp_subject = $arrRet[0]['subject'];
//        } else {
//            $body .= $header;
//            $body .= $footer;
//            $tmp_subject = $subject;
//        }
//
//        // 受注情報の取得
//        $where = "order_id = ?";
//        $arrRet = $objQuery->select("*", "dtb_order", $where, array($order_id));
//        $arrOrder = $arrRet[0];
//        $arrOrderDetail = $objQuery->select("*", "dtb_order_detail", $where, array($order_id));
//
//        $where .= " and reserved_type = ?";
//        $arrReserved = $objQuery->select("*", "dtb_products_reserved", $where, array($order_id, RESERVED_TYPE_SETTING));
//
//        $objPage->writer_select_tmp = $arrOrder['writer_select'];//::N00039 Add 20130513
//
//        $objPage->Message_tmp = $arrOrder['message'];
//
//        // 顧客情報の取得
//        $customer_id = $arrOrder['customer_id'];
//        $arrRet = $objQuery->select("point", "dtb_customer", "customer_id = ?", array($customer_id));
//        $arrCustomer = isset($arrRet[0]) ? $arrRet[0] : "";
//
//        $objPage->arrCustomer = $arrCustomer;
//        $objPage->arrOrder = $arrOrder;
//
//        //その他決済情報
//        if($arrOrder['memo02'] != "") {
//            $arrOther = unserialize($arrOrder['memo02']);
//
//            foreach($arrOther as $other_key => $other_val){
//                if(SC_Utils_Ex::sfTrim($other_val["value"]) == ""){
//                    $arrOther[$other_key]["value"] = "";
//                }
//            }
//
//            $objPage->arrOther = $arrOther;
//        }
//        // 発送日・ご利用日・ご返却日の取得
//        $arrSubjectDate = $this->lfMakeSubjectDate($arrOrder['deliv_date']);
//        $objPage->arrSubjectDate = $arrSubjectDate;
//
//        // 都道府県変換
//        $objPage->arrOrder['deliv_pref'] = $this->arrPref[$objPage->arrOrder['deliv_pref']];
//
//        //::N00083 Add 20131201 B00064 Change 20140402
//        $objSiteInfo = new SC_SiteInfo();
//        $arrInfo = $objSiteInfo->data;
//
//        $set_price=0;
//        $dress_position=0;
//        $set_flag = FALSE;
//        $without_bag = FALSE;
//        foreach ($arrOrderDetail as $key=>$val) {
//            $val['price'] = SC_Utils_Ex::sfPreTax($val["price"], $arrInfo['tax'], $arrInfo['tax_rule']);
//            $arrOrderDetail[$key]['price'] = $val['price'];
//            //セット商品か検査
//            if (!empty($val['set_pid'])) {
//                $set_price += $val['price'];
//                //セット商品のドレスにセット商品価格を代入する準備を行う
//                if ($val['set_pid'] == $val['product_id']) {
//                    $dress_position = $key;
//                    $set_flag = TRUE;
//                    //バッグ無しセットの場合は1円足さない
//                    if ($val['set_ptype'] == 4) {
//                        $without_bag = TRUE;
//                    }
//                } else {
//                    //セット商品のドレス以外は消す
//                    unset($arrOrderDetail[$key]);
//                }
//            }
//        }
//        //セット商品のドレスにセット商品価格を代入する
//        if ($set_flag) {
//            ////バッグ有りセットの場合は1円足す
//            //if ($without_bag == TRUE) {
//            //    $set_price = $set_price+1;
//            //}
//            $arrOrderDetail[$dress_position]['price'] = $set_price;
//        }
//        //歯抜けの配列だとメールで空表示になってしまうので詰める
//        $arrOrderDetail = array_merge($arrOrderDetail);
//        //::N00083 end 20131201 B00064 Change 20140402
//
//
//        $objPage->arrOrderDetail = $arrOrderDetail;
//
//        $objCustomer = new SC_Customer();
//        $objPage->tpl_user_point = $objCustomer->getValue('point');
//
//        $objMailView = new SC_SiteView();
//        // メール本文の取得
//        $objMailView->assignobj($objPage);
//        $order_detail = $objMailView->fetch("mail_templates/order_detail.tpl");
//        $order_detail2 = $objMailView->fetch("mail_templates/order_detail2.tpl");
//
//        // today
//        $ary_week = array(0=>"日",1=>"月",2=>"火",3=>"水",4=>"木",5=>"金",6=>"土",);
//        $week = date("w");
//        $today = date("Y年m月d日")."（".$ary_week[$week]."）";
//
//        // replace
//        $body = str_ireplace("[[user_name]]", $arrOrder['order_name01']." ".$arrOrder['order_name02'], $body);
//        $body = str_ireplace("[[delivdate_data]]", $arrOrder['deliv_date'], $body);
//        $body = str_ireplace("[[return_data]]", $arrSubjectDate['return_date'], $body);
//        $body = str_ireplace("[[current_date]]", $today, $body);
//        $body = str_ireplace("[[cancel_fee]]", "￥ ".number_format($arrOrder['charge']), $body);
//        $body = str_ireplace("[[order_detail]]", $order_detail, $body);
//        $body = str_ireplace("[[order_detail2]]", $order_detail2, $body);
//
//		$torikesi_pro = "";
//		$torikesi_point = "";
//        if(count($arrReserved) > 0){
//        	$arrOrderDetailTemp = array();
//        	foreach ($arrOrderDetail as $key=>$row) {
//        		$arrOrderDetailTemp[$row['product_id']] = $row;
//        	}
//
//        	foreach ($arrReserved as $key=>$row) {
//        		$product_id = $row['product_id'];
//        		if(isset($arrOrderDetailTemp[$product_id])){
//        			if(!empty($torikesi_pro)){
//        				$torikesi_pro .= "、";
//        			}
//        			$torikesi_pro .= $arrOrderDetailTemp[$product_id]['product_code']."／".$arrOrderDetailTemp[$product_id]['product_name'];
//        		}
//        	}
//
//        	$torikesi_point = $arrOrder['add_point'];
//        }
//        $body = str_ireplace("[[torikesi_pro]]", $torikesi_pro, $body);
//        $body = str_ireplace("[[torikesi_point]]", $torikesi_point, $body);
//
//        // メール送信処理
//        $objSendMail = new SC_SendMail_Ex();
//        $bcc = $arrInfo['email01'];
//        $from = $arrInfo['email03'];
//        $error = $arrInfo['email04'];
//
//        $tosubject = $this->sfMakeSubject($objQuery, $objMailView,
//                                             $objPage, $tmp_subject);
//
//        $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
//        $objSendMail->setTo($arrOrder["order_email"], $arrOrder["order_name01"] . " ". $arrOrder["order_name02"] ." 様");
//
//        // 送信フラグ:trueの場合は、送信する。
//        if($send) {
//            if ($objSendMail->sendMail()) {
//                $this->sfSaveMailHistory($order_id, $template_id, $tosubject, $body);
//            }
//        }
//
//        return $objSendMail;
//    }


    /* 受注完了メール送信(添付ファイルあり) */
  function sfSendOrderMail2($order_id, $template_id, $subject = "", $header = "", $footer = "", $send = true, $attach_files = null) {

        $objPage = new LC_Page();
        $objSiteInfo = new SC_Helper_DB();
        $arrInfo = $objSiteInfo->sfGetBasisData();
        $objPage->arrInfo = $arrInfo;

        $objQuery = new SC_Query();

        $body = "";
        if($subject == "" && $header == "" && $footer == "") {
            // メールテンプレート情報の取得
            $where = "template_id = ?";
            $arrRet = $objQuery->select("subject, header, footer", "dtb_mailtemplate", $where, array($template_id));
            $body .= $arrRet[0]['header'];
            $body .= $arrRet[0]['footer'];
            $tmp_subject = $arrRet[0]['subject'];
        } else {
            $body .= $header;
            $body .= $footer;
            $tmp_subject = $subject;
        }
        
        
        /* 受注情報の取得 add ishibashi 20201208 */
        //$where = 'order_id = ? AND del_flg = 0';
        //$arrOrder = $objQuery->getRow('*', 'dtb_order', $where, array($order_id));
        $where = "order_id = ?";
        $arrRet = $objQuery->select("*", "dtb_order", $where, array($order_id));
        $arrOrder = $arrRet[0];
        $arrOrderDetail = $objQuery->select("*", "dtb_order_detail", $where, array($order_id));
        $mail_discount = $arrOrder['discount'] + $arrOrder['use_point'];
        $objPage->mail_discount = $mail_discount;

        if (empty($arrOrder)) {
            trigger_error("該当する受注が存在しない。(注文番号: $order_id)", E_USER_ERROR);
        }
        /* end */

        $where = "order_id = ? and reserved_type = ?";
        $arrReserved = $objQuery->select("*", "dtb_products_reserved", $where, array($order_id, RESERVED_TYPE_SETTING));

        $objPage->writer_select_tmp = $arrOrder['writer_select'];//::N00039 Add 20130513

        $objPage->Message_tmp = $arrOrder['message'];

        // 顧客情報の取得
        $customer_id = $arrOrder['customer_id'];
        $arrRet = $objQuery->select("point", "dtb_customer", "customer_id = ?", array($customer_id));
        $arrCustomer = isset($arrRet[0]) ? $arrRet[0] : "";

        $objPage->arrCustomer = $arrCustomer;
        $objPage->arrOrder = $arrOrder;

        //その他決済情報
        if($arrOrder['memo02'] != "") {
            $arrOther = unserialize($arrOrder['memo02']);
            foreach($arrOther as $other_key => $other_val){
                if(SC_Utils_Ex::sfTrim($other_val["value"]) == ""){
                    $arrOther[$other_key]["value"] = "";
                }
            }

            $objPage->arrOther = $arrOther;
        }
        // 発送日・ご利用日・ご返却日の取得
        $arrSubjectDate = $this->lfMakeSubjectDate($arrOrder['deliv_date']);
        $objPage->arrSubjectDate = $arrSubjectDate;

        // 都道府県変換
        $objPage->arrOrder['deliv_pref'] = $this->arrPref[$objPage->arrOrder['deliv_pref']];

        //::N00083 Add 20131201 B00064 Change 20140402
        $objSiteInfo = new SC_SiteInfo();
        $arrInfo = $objSiteInfo->data;

        $set_price=0;
        $dress_position=0;
        $set_flag = FALSE;
        $without_bag = FALSE;
        foreach ($arrOrderDetail as $key=>$val) {
            $val['price'] = SC_Utils_Ex::sfPreTax($val["price"], $arrInfo['tax'], $arrInfo['tax_rule']);
            $arrOrderDetail[$key]['price'] = $val['price'];
            //セット商品か検査
            if (!empty($val['set_pid'])) {
                $set_price += $val['price'];
                //セット商品のドレスにセット商品価格を代入する準備を行う
                if ($val['set_pid'] == $val['product_id']) {
                    $dress_position = $key;
                    $set_flag = TRUE;
                    //バッグ無しセットの場合は1円足さない
                    if ($val['set_ptype'] == 4) {
                        $without_bag = TRUE;
                    }
                } else {
                    //セット商品のドレス以外は消す
                    unset($arrOrderDetail[$key]);
                }
            }
        }
        //セット商品のドレスにセット商品価格を代入する
        if ($set_flag) {
            ////バッグ有りセットの場合は1円足す
            //if ($without_bag == TRUE) {
            //    $set_price = $set_price+1;
            //}
            $arrOrderDetail[$dress_position]['price'] = $set_price;
        }
        //歯抜けの配列だとメールで空表示になってしまうので詰める
        $arrOrderDetail = array_merge($arrOrderDetail);
        //::N00083 end 20131201 B00064 Change 20140402

        $objPage->arrOrderDetail = $arrOrderDetail;

        $objCustomer = new SC_Customer();
        $objPage->tpl_user_point = $objCustomer->getValue('point');

        $objMailView = new SC_SiteView();
        // メール本文の取得
        $objMailView->assignobj($objPage);
        $order_detail = $objMailView->fetch("mail_templates/order_detail.tpl");
        $order_detail2 = $objMailView->fetch("mail_templates/order_detail2.tpl");

        // today
        $ary_week = array(0=>"日",1=>"月",2=>"火",3=>"水",4=>"木",5=>"金",6=>"土",);
        $week = date("w");
        $today = date("Y年m月d日")."（".$ary_week[$week]."）";

        // replace
        $body = str_ireplace("[[user_name]]", $arrOrder['order_name01']." ".$arrOrder['order_name02'], $body);
        $body = str_ireplace("[[delivdate_data]]", $arrOrder['deliv_date'], $body);
        $body = str_ireplace("[[return_data]]", $arrSubjectDate['return_date'], $body);
        $body = str_ireplace("[[current_date]]", $today, $body);
        $body = str_ireplace("[[cancel_fee]]", "￥ ".number_format($arrOrder['charge']), $body);
        $body = str_ireplace("[[order_detail]]", $order_detail, $body);
        $body = str_ireplace("[[order_detail2]]", $order_detail2, $body);

    $torikesi_pro = "";
    $torikesi_point = "";
        if(count($arrReserved) > 0){
          $arrOrderDetailTemp = array();
          foreach ($arrOrderDetail as $key=>$row) {
            $arrOrderDetailTemp[$row['product_id']] = $row;
          }

          foreach ($arrReserved as $key=>$row) {
            $product_id = $row['product_id'];
            if(isset($arrOrderDetailTemp[$product_id])){
              if(!empty($torikesi_pro)){
                $torikesi_pro .= "、";
              }
              $torikesi_pro .= $arrOrderDetailTemp[$product_id]['product_code']."／".$arrOrderDetailTemp[$product_id]['product_name'];
            }
          }

          $torikesi_point = $arrOrder['add_point'];
        }
        $body = str_ireplace("[[torikesi_pro]]", $torikesi_pro, $body);
        $body = str_ireplace("[[torikesi_point]]", $torikesi_point, $body);

        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];

        //$tosubject = $this->sfMakeSubject($objQuery, $objMailView,
        //                                     $objPage, $tmp_subject);
                                             
        $tosubject = $this->sfMakeSubject($tmp_subject);

        if ($attach_files !== null){
            $objSendMail->setAttachment($attach_files);
        }

        $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->setTo($arrOrder["order_email"], $arrOrder["order_name01"] . " ". $arrOrder["order_name02"] ." 様");
        

        // 送信フラグ:trueの場合は、送信する。
        if($send) {
            if ($objSendMail->sendMail()) {
                $this->sfSaveMailHistory($order_id, $template_id, $tosubject, $body);
            }
        }

        return $objSendMail;
    }

    /* 注文受付メール送信 */
    public function sfSendOrderMail($order_id, $template_id, $subject = '', $header = '', $footer = '', $send = true)
    {
        
        $arrTplVar = new stdClass();
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $arrTplVar->arrInfo = $arrInfo;

        $objQuery = SC_Query_Ex::getSingletonInstance();

        if ($subject == '' && $header == '' && $footer == '') {
            // メインテンプレート情報の取得
            $objMailtemplate = new SC_Helper_Mailtemplate_Ex();
            $mailtemplate = $objMailtemplate->get($template_id);
            $arrTplVar->tpl_header = $mailtemplate['header'];
            $arrTplVar->tpl_footer = $mailtemplate['footer'];
            $tmp_subject = $mailtemplate['subject'];
        } else {
            $arrTplVar->tpl_header = $header;
            $arrTplVar->tpl_footer = $footer;
            $tmp_subject = $subject;
        }

        // 受注情報の登録
        $where = 'order_id = ? AND del_flg = 0';
        $arrOrder = $objQuery->getRow('*', 'dtb_order', $where, array($order_id));
        
        if (empty($arrOrder)) {
            trigger_error("該当する受注が存在しない。(注文番号: $order_id)", E_USER_ERROR);
        }

        $where = 'order_id = ?';
        $objQuery->setOrder('order_detail_id');
        $arrTplVar->arrOrderDetail = $objQuery->select('*', 'dtb_order_detail', $where, array($order_id));
        
        //$where .= " and reserved_type = ?"; // 20200715 ishibashi
        //$arrReserved = $objQuery->select("*", "dtb_products_reserved", $where, array($order_id, RESERVED_TYPE_SETTING)); //20200715 ishibashi

        //$objPage->writer_select_tmp = $arrOrder['writer_select'];//::N00039 Add 20130513  // 20200715 ishibashi
        $arrTplVar->writer_select_tmp = $arrOrder['writer_select']; // ishibashi

        // 配送情報の取得
        $arrTplVar->arrShipping = $this->sfGetShippingData($order_id);

        $arrTplVar->Message_tmp = $arrOrder['message'];

        // 会員情報の取得
        $customer_id = $arrOrder['customer_id'];
        $objQuery->setOrder('customer_id');
        $arrRet = $objQuery->select('point', 'dtb_customer', 'customer_id = ?', array($customer_id));
        $arrCustomer = isset($arrRet[0]) ? $arrRet[0] : '';
        
        $arrTplVar->arrCustomer = $arrCustomer;
        $arrTplVar->arrOrder = $arrOrder;
        
        //その他決済情報
        if ($arrOrder['memo02'] != '') {
            $arrOther = unserialize($arrOrder['memo02']);
            
            foreach ($arrOther as $other_key => $other_val) {
                if (SC_Utils_Ex::sfTrim($other_val['value']) == '') {
                    $arrOther[$other_key]['value'] = '';
                }
            }
            
            $arrTplVar->arrOther = $arrOther;
        }

        // 20200714 ishibashi 古い方から抜粋 
        // 発送日・ご利用日・ご返却日の取得
        $arrSubjectDate = $this->lfMakeSubjectDate($arrOrder['deliv_date']);
        $arrTplVar->arrSubjectDate = $arrSubjectDate;

        // 都道府県変換
        $arrTplVar->arrOrder['deliv_pref'] = $this->arrPref[$arrTplVar->arrOrder['deliv_pref']]; // 20200715 ishibashi旧コードから参考

        // 都道府県変換
        //$arrTplVar->arrPref = $this->arrPref; 20200715 ishibashi
        // 国変換
        //$arrTplVar->arrCountry = $this->arrCountry; 20200715 ishibashi

        $objCustomer = new SC_Customer_Ex();
        $arrTplVar->tpl_user_point = $objCustomer->getValue('point');
        
        $objMailView = null;
        // 20200716 ishibashi
        //// 注文受付メール(携帯)
        //if ($template_id == 2) {
        //    $objMailView = new SC_MobileView_Ex();
        //} else {
        //    $objMailView = new SC_SiteView_Ex();
        //}

        $objMailView = new SC_SiteView_Ex(); // 20200716 ishibashi

        // メール本文の取得
        $objMailView->setPage($this->getPage());
        $objMailView->assignobj($arrTplVar);
        // 20200715 ishibashi Smartyのmailtemplate/order_mail.tplの本文（order_detailが被るため）削除してます。DBのdtb_mailtemplateを参考に下記にorder_detail2を使用。
        $body = $objMailView->fetch($this->arrMAILTPLPATH[$template_id]);

        // 20200715 ishibashi ここから▼
        // テンプレメールの[[order_detail2]]の読み込み
        $order_detail2 = $objMailView->fetch("mail_templates/order_detail2.tpl");

        // today
        $ary_week = array(0=>"日",1=>"月",2=>"火",3=>"水",4=>"木",5=>"金",6=>"土",);
        $week = date("w");
        $today = date("Y年m月d日")."（".$ary_week[$week]."）";

        // replace
        $body = str_ireplace("[[user_name]]", $arrOrder['order_name01']." ".$arrOrder['order_name02'], $body);
        $body = str_ireplace("[[delivdate_data]]", $arrOrder['deliv_date'], $body);
        $body = str_ireplace("[[return_data]]", $arrSubjectDate['return_date'], $body);
        $body = str_ireplace("[[current_date]]", $today, $body);
        $body = str_ireplace("[[cancel_fee]]", "￥ ".number_format($arrOrder['charge']), $body);
        $body = str_ireplace("[[order_detail]]", $order_detail, $body);
        $body = str_ireplace("[[order_detail2]]", $order_detail2, $body);

		$torikesi_pro = "";
		$torikesi_point = "";
        if(count($arrReserved) > 0){
        	$arrOrderDetailTemp = array();
        	foreach ($arrOrderDetail as $key=>$row) {
        		$arrOrderDetailTemp[$row['product_id']] = $row;
        	}

        	foreach ($arrReserved as $key=>$row) {
        		$product_id = $row['product_id'];
        		if(isset($arrOrderDetailTemp[$product_id])){
        			if(!empty($torikesi_pro)){
        				$torikesi_pro .= "、";
        			}
        			$torikesi_pro .= $arrOrderDetailTemp[$product_id]['product_code']."／".$arrOrderDetailTemp[$product_id]['product_name'];
        		}
        	}

        	$torikesi_point = $arrOrder['add_point'];
        }
        $body = str_ireplace("[[torikesi_pro]]", $torikesi_pro, $body);
        $body = str_ireplace("[[torikesi_point]]", $torikesi_point, $body);

        // ishibashi ここまで▲
        

        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $tosubject = $this->sfMakeSubject($tmp_subject, $objMailView);
        
        $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->setTo($arrOrder['order_email'], $arrOrder['order_name01'] . ' '. $arrOrder['order_name02'] .' 様');
        
        // 送信フラグ:trueの場合は、送信する。
        if ($send) {
            if ($objSendMail->sendMail()) {
                $this->sfSaveMailHistory($order_id, $template_id, $tosubject, $body);
            }
        }
        
        return $objSendMail;
    }




    public function sfSendOrderMail_Real($order_id, $template_id, $subject = '', $header = '', $footer = '', $send = true)
    {
        $arrTplVar = new stdClass();
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $arrTplVar->arrInfo = $arrInfo;

        $objQuery = SC_Query_Ex::getSingletonInstance();

        if ($subject == '' && $header == '' && $footer == '') {
            // メールテンプレート情報の取得
            $objMailtemplate = new SC_Helper_Mailtemplate_Ex();
            $mailtemplate = $objMailtemplate->get($template_id);
            $arrTplVar->tpl_header = $mailtemplate['header'];
            $arrTplVar->tpl_footer = $mailtemplate['footer'];
            $tmp_subject = $mailtemplate['subject'];
        } else {
            $arrTplVar->tpl_header = $header;
            $arrTplVar->tpl_footer = $footer;
            $tmp_subject = $subject;
        }

        // 受注情報の取得
        $where = 'order_id = ? AND del_flg = 0';
        $arrOrder = $objQuery->getRow('*', 'dtb_order', $where, array($order_id));

        if (empty($arrOrder)) {
            trigger_error("該当する受注が存在しない。(注文番号: $order_id)", E_USER_ERROR);
        }

        $where = 'order_id = ?';
        $objQuery->setOrder('order_detail_id');
        $arrTplVar->arrOrderDetail = $objQuery->select('*', 'dtb_order_detail', $where, array($order_id));

        // 配送情報の取得
        $arrTplVar->arrShipping = $this->sfGetShippingData($order_id);

        $arrTplVar->Message_tmp = $arrOrder['message'];

        // 会員情報の取得
        $customer_id = $arrOrder['customer_id'];
        $objQuery->setOrder('customer_id');
        $arrRet = $objQuery->select('point', 'dtb_customer', 'customer_id = ?', array($customer_id));
        $arrCustomer = isset($arrRet[0]) ? $arrRet[0] : '';

        $arrTplVar->arrCustomer = $arrCustomer;
        $arrTplVar->arrOrder = $arrOrder;

        //その他決済情報
        if ($arrOrder['memo02'] != '') {
            $arrOther = unserialize($arrOrder['memo02']);

            foreach ($arrOther as $other_key => $other_val) {
                if (SC_Utils_Ex::sfTrim($other_val['value']) == '') {
                    $arrOther[$other_key]['value'] = '';
                }
            }

            $arrTplVar->arrOther = $arrOther;
        }

        // 都道府県変換
        $arrTplVar->arrPref = $this->arrPref;
        // 国変換
        $arrTplVar->arrCountry = $this->arrCountry;

        $objCustomer = new SC_Customer_Ex();
        $arrTplVar->tpl_user_point = $objCustomer->getValue('point');

        $objMailView = null;
        // 注文受付メール(携帯)
        if ($template_id == 2) {
            $objMailView = new SC_MobileView_Ex();
        } else {
            $objMailView = new SC_SiteView_Ex();
        }
        // メール本文の取得
        $objMailView->setPage($this->getPage());
        $objMailView->assignobj($arrTplVar);
        $body = $objMailView->fetch($this->arrMAILTPLPATH[$template_id]);

        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $tosubject = $this->sfMakeSubject($tmp_subject, $objMailView);

        $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->setTo($arrOrder['order_email'], $arrOrder['order_name01'] . ' '. $arrOrder['order_name02'] .' 様');

        // 送信フラグ:trueの場合は、送信する。
        if ($send) {
            if ($objSendMail->sendMail()) {
                $this->sfSaveMailHistory($order_id, $template_id, $tosubject, $body);
            }
        }

        return $objSendMail;
    }

    /**
     * 配送情報の取得
     *
     * @param integer $order_id 受注ID
     * @return array 配送情報を格納した配列
     */
    function sfGetShippingData($order_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $objQuery->setOrder('shipping_id');
        $arrRet = $objQuery->select('*', 'dtb_shipping', 'order_id = ?', array($order_id));
        foreach ($arrRet as $key => $value) {
            $col = 's_i.*, tax_rate, tax_rule';
            $from = 'dtb_shipment_item AS s_i JOIN dtb_order_detail AS o_d
                ON s_i.order_id = o_d.order_id AND s_i.product_class_id = o_d.product_class_id';
            $where = 'o_d.order_id = ? AND shipping_id = ?';
            $arrWhereVal = array($order_id, $arrRet[$key]['shipping_id']);
            $objQuery->setOrder('order_detail_id');
            $arrItems = $objQuery->select($col, $from, $where, $arrWhereVal);
            $arrRet[$key]['shipment_item'] = $arrItems;
        }

        return $arrRet;
    }

    //KHS ADD 2014.3.16
        /**
     * 返却メール一括送信
     * made by RCHJ 2011/11/05
     * @param $send_data array データ
     *         (template_id, use_status, dirt_detail, insertdata_one, insertdata_two,
     *         [[hoped_data]], [[delay_payment]], [[shipdate_data]], [[delivdate_data]], [[usedate_data]],
     *         [[return_data]], [[order_detail_template_id]], attach_files)
     */
    function sfSendOrderMailReturn($order_id, $send_data = array(), $send = true) {
        $objPage = new LC_Page();
        $objSiteInfo = new SC_Helper_DB_Ex();
        $arrInfo = $objSiteInfo->sfGetBasisData();
        $objPage->arrInfo = $arrInfo;

        $objQuery = & SC_Query_Ex::getSingletonInstance();

        // 受注情報の取得
        $where = "order_id = ?";
        $arrRet = $objQuery->select("*", "dtb_order", $where, array($order_id));
        $arrOrder = $arrRet[0];
        $arrOrderDetail = $objQuery->select("*", "dtb_order_detail", $where, array($order_id));

        $objPage->writer_select_tmp = $arrOrder['writer_select'];//::N00039 Add 20130513

        $objPage->Message_tmp = $arrOrder['message'];

     // 顧客情報の取得
        $customer_id = $arrOrder['customer_id'];
        $arrRet = $objQuery->select("point", "dtb_customer", "customer_id = ?", array($customer_id));
        $arrCustomer = isset($arrRet[0]) ? $arrRet[0] : "";

        $objPage->arrCustomer = $arrCustomer;
        $objPage->arrOrder = $arrOrder;
         //その他決済情報
      if($arrOrder['memo02'] != "") {
            $arrOther = unserialize($arrOrder['memo02']);

            foreach($arrOther as $other_key => $other_val){
                if(SC_Utils_Ex::sfTrim($other_val["value"]) == ""){
                    $arrOther[$other_key]["value"] = "";
                }
            }

            $objPage->arrOther = $arrOther;
        }

        // 都道府県変換
        $objPage->arrOrder['deliv_pref'] = $this->arrPref[$objPage->arrOrder['deliv_pref']];

        //::N00083 Add 20131201 B00064 Change 20140402
        $objSiteInfo = new SC_SiteInfo();
        $arrInfo = $objSiteInfo->data;

        $set_price=0;
        $dress_position=0;
        $set_flag = FALSE;
        foreach ($arrOrderDetail as $key=>$val) {
            $val['price'] = SC_Utils_Ex::sfPreTax($val["price"], $arrInfo['tax'], $arrInfo['tax_rule']);
            $arrOrderDetail[$key]['price'] = $val['price'];
            //セット商品か検査
            if (!empty($val['set_pid'])) {
                $set_price += $val['price'];
                //セット商品のドレスにセット商品価格を代入する準備を行う
                if ($val['set_pid'] == $val['product_id']) {
                    $dress_position = $key;
                    $set_flag = TRUE;
                } else {
                    //セット商品のドレス以外は消す
                    unset($arrOrderDetail[$key]);
                }
            }
        }
        //セット商品のドレスにセット商品価格を代入する
        if ($set_flag) {
            $arrOrderDetail[$dress_position]['price'] = $set_price;
        }
        //歯抜けの配列だとメールで空表示になってしまうので詰める
        $arrOrderDetail = array_merge($arrOrderDetail);
        //::N00083 end 20131201

        $objPage->arrOrderDetail = $arrOrderDetail;

        $objCustomer = new SC_Customer();
        $objPage->tpl_user_point = $objCustomer->getValue('point');

        if(!empty($objPage->arrOrder['include_orderid'])){
            $objPage->tpl_dongbong_order_no = $objPage->arrOrder['include_orderid'];
        }

        $objPage->tpl_user_name = true;

       // ================= メール本文の取得 =================
        $body = "";
        // order_detailメール本文の取得
        $objMailView = new SC_SiteView();
        $objMailView->assignobj($objPage);
        $other_body = $objMailView->fetch($this->arrMAILTPLPATH[$send_data['order_detail_template_id']]);
        // get dtb_mailtemplate_batch data
        $where = "template_id = ?";
        $arrRet = $objQuery->select("*", "dtb_mailtemplate_batch", $where, array($send_data['template_id']));
        $arrTemplateData = $arrRet[0];
        // subject
        $tosubject = $arrTemplateData['subject'];//$tosubject = $this->sfMakeSubject($objQuery, $objMailView, $objPage, $tmp_subject);
        // make mail body
        $body = $arrTemplateData['body'];
        // ***********replace mail body symbols
        // use_status, dirt_detail
        $body = str_ireplace("[[use_status]]", $send_data['use_status'], $body);
        $body = str_ireplace("[[dirt_details]]", $send_data['dirt_detail'], $body);
        // insertdata_one, insertdata_two, hoped_data, delay_payment
        $insert1 = $send_data['insertdata_one'];
        $insert2 = $send_data['insertdata_two'];
        $insert3 = $send_data['insertdata_three'];//::N00026 Add 20130401
        $insert4 = $send_data['insertdata_four'];//::N00026 Add 20130401
        if(!empty($body)){
            if(strpos($body, "[[calc_data]]")!==false){
                $calc_data = $insert1 * $arrOrder['subtotal'];
            }
            $body = str_ireplace("[[small_sum]]", $arrOrder['subtotal'], $body);
            $body = str_ireplace("[[calc_data]]", $calc_data, $body);

            if(strpos($body, "[[hoped_data]]")!==false){
                $hoped_data = "";
                if(!empty($insert2)){
                    $hoped_data = $arrTemplateData['input3'];
                }
                $body = str_ireplace("[[hoped_data]]", $hoped_data, $body);
            }
        }
        $body = str_ireplace("[[insertdata_one]]", $insert1, $body);
        $body = str_ireplace("[[insertdata_two]]", $insert2, $body);
        $body = str_ireplace("[[insertdata_three]]", $insert3, $body);//::N00026 Add 20130401
        $body = str_ireplace("[[insertdata_four]]", $insert4, $body);//::N00026 Add 20130401

        // shipdate_data, delivdate_data, usedate_data, return_data
        $body = str_ireplace("[[shipdate_data]]", $send_data['shipdate_data'], $body);
        $body = str_ireplace("[[delivdate_data]]", $send_data['delivdate_data'], $body);
        $body = str_ireplace("[[usedate_data]]", $send_data['usedate_data'], $body);
        $body = str_ireplace("[[return_data]]", $send_data['return_data'], $body);
        // other_body
        $body = str_ireplace("[[order_detail]]", $other_body, $body);
        // user_name
        $body = str_ireplace("[[user_name]]", $arrOrder['order_name01']." ".$arrOrder['order_name02'], $body);
        // ================ end ================
        //::N00050 Add 20130516
        $prize_max = $objQuery->max("prize_id","dtb_dresser_prize");
        $body = str_ireplace("[[new_dresser_prize_id]]", $prize_max, $body);
        //::N00050 end 20130516

        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];

        if(isset($send_data['attach_files'])){
            $blnEmpty = true;
            foreach ($send_data['attach_files'] as $value){
                if(!empty($value)){
                    $blnEmpty = false;
                    break;
                }
            }
            if(!$blnEmpty){
                $objSendMail->setAttachment($send_data['attach_files']);
            }
        }

       $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->setTo($arrOrder["order_email"], $arrOrder["order_name01"] . " ". $arrOrder["order_name02"] ." 様");

        // 送信フラグ:trueの場合は、送信する。
        if($send) {
            if ($objSendMail->sendMail()) {
                $save_body = $body;
                $save_body .= "\r\n";
                $arrUrlFiles = $send_data['attach_url_files'];
                foreach ($arrUrlFiles as $value) {
                    if(!empty($value)){
                        $save_body .= "<a href=".SITE_URL.$value.">添付ファイル</a>\r\n";
                    }
                }

                $this->sfSaveMailHistory($order_id, $send_data['template_id'], $tosubject, $save_body);
            }
        }

        return $objSendMail;
    }

    /**
     * メール一括送信
     * made by KACR 2011/11/03
     * @param $replace_data array 置換するデータ
     */
    function sfSendOrderMail_ALL($order_id, $template_id, $subject = "", $body = "", $replace_data = array(), $send = true) {

        $objPage = new LC_Page();
        $objSiteInfo = new SC_SiteInfo();
        $arrInfo = $objSiteInfo->data;
        $objPage->arrInfo = $arrInfo;

        $objQuery = new SC_Query();

        if($subject == "" && $body == "" ) {
	        // get dtb_mailtemplate_batch data
	        $where = "template_id = ?";
	        $arrRet = $objQuery->select("*", "dtb_mailtemplate_batch", $where, array($template_id));
	        $arrTemplateData = $arrRet[0];
	        // subject
	        $tosubject = $arrTemplateData['subject'];
	        // make mail body
	        $body = $arrTemplateData['body'];
        }else {
        	$tosubject = $subject;
        }
        // 受注情報の取得
        $where = "order_id = ?";
        $arrRet = $objQuery->select("*", "dtb_order", $where, array($order_id));
        $arrOrder = $arrRet[0];
        $arrOrderDetail = $objQuery->select("*", "dtb_order_detail", $where, array($order_id));

        $objPage->writer_select_tmp = $arrOrder['writer_select'];//::N00039 Add 20130513

        $objPage->Message_tmp = $arrOrder['message'];

        // 顧客情報の取得
        $customer_id = $arrOrder['customer_id'];
        $arrRet = $objQuery->select("point", "dtb_customer", "customer_id = ?", array($customer_id));
        $arrCustomer = isset($arrRet[0]) ? $arrRet[0] : "";

        $objPage->arrCustomer = $arrCustomer;
        $objPage->arrOrder = $arrOrder;

        // 発送日・ご利用日・ご返却日の取得
        $arrSubjectDate = $this->lfMakeSubjectDate($arrOrder['deliv_date']);
        $objPage->arrSubjectDate = $arrSubjectDate;

        //その他決済情報
        if($arrOrder['memo02'] != "") {
            $arrOther = unserialize($arrOrder['memo02']);

            foreach($arrOther as $other_key => $other_val){
                if(SC_Utils_Ex::sfTrim($other_val["value"]) == ""){
                    $arrOther[$other_key]["value"] = "";
                }
            }

            $objPage->arrOther = $arrOther;
        }

        // 都道府県変換
        $objPage->arrOrder['deliv_pref'] = $this->arrPref[$objPage->arrOrder['deliv_pref']];

        //::N00083 Add 20131201 B00064 Change 20140402
        $objSiteInfo = new SC_SiteInfo();
        $arrInfo = $objSiteInfo->data;

        $set_price=0;
        $dress_position=0;
        $set_flag = FALSE;
        foreach ($arrOrderDetail as $key=>$val) {
            $val['price'] = SC_Utils_Ex::sfPreTax($val["price"], $arrInfo['tax'], $arrInfo['tax_rule']);
            $arrOrderDetail[$key]['price'] = $val['price'];
            //セット商品か検査
            if (!empty($val['set_pid'])) {
                $set_price += $val['price'];
                //セット商品のドレスにセット商品価格を代入する準備を行う
                if ($val['set_pid'] == $val['product_id']) {
                    $dress_position = $key;
                    $set_flag = TRUE;
                } else {
                    //セット商品のドレス以外は消す
                    unset($arrOrderDetail[$key]);
                }
            }
        }
        //セット商品のドレスにセット商品価格を代入する
        if ($set_flag) {
            $arrOrderDetail[$dress_position]['price'] = (string)$set_price;
        }
        //歯抜けの配列だとメールで空表示になってしまうので詰める
        $arrOrderDetail = array_merge($arrOrderDetail);
        //::N00083 end 20131201 B00064 Change 20140402

        $objPage->arrOrderDetail = $arrOrderDetail;

        $objCustomer = new SC_Customer();
        $objPage->tpl_user_point = $objCustomer->getValue('point');

    	if(!empty($objPage->arrOrder['include_orderid'])){
        	$objPage->tpl_dongbong_order_no = $objPage->arrOrder['include_orderid'];
        }

        $objPage->tpl_user_name = true;

        $objMailView = new SC_SiteView();
        // メール本文の取得
        $objMailView->assignobj($objPage);
        $other_body = $objMailView->fetch($this->arrMAILTPLPATH[5]);//$template_id
        $orderdetail2_body = $objMailView->fetch("mail_templates/order_detail2.tpl");

        if (!empty($replace_data)){
        	if (isset($replace_data['p_num1'])){
		        $body = str_replace("[[tracking_number1]]", "追跡番号1 : ".$replace_data['p_num1'], $body);
        	}
        	if (isset($replace_data['p_num2'])){//&& !empty($replace_data['p_num2'])
		        $body = str_replace("[[tracking_number2]]", "追跡番号2 : ".$replace_data['p_num2'], $body);
        	}
        	if (isset($replace_data['delive_date'])){
        		$body = str_ireplace("[[delivdate_data]]", $replace_data['delive_date'], $body);
        	}
        	if (isset($replace_data['return_date'])){
        		$body = str_ireplace("[[return_data]]", $replace_data['return_date'], $body);
        	}
        }
		// user_name
        $body = str_ireplace("[[user_name]]", $arrOrder['order_name01']." ".$arrOrder['order_name02'], $body);

        $body = str_ireplace("[[order_detail]]", $other_body, $body);

        $body = str_ireplace("[[order_detail2]]", $orderdetail2_body, $body);

        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];

        //$tosubject = $this->sfMakeSubject($objQuery, $objMailView, $objPage, $tmp_subject);

        $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->setTo($arrOrder["order_email"], $arrOrder["order_name01"] . " ". $arrOrder["order_name02"] ." 様");


        // 送信フラグ:trueの場合は、送信する。
        if($send) {
            if ($objSendMail->sendMail()) {
                $this->sfSaveMailHistory($order_id, $template_id, $tosubject, $body);
                //$this->sfSaveMailHistory($order_id, 5, $tosubject, $body);
            }
        }

        return $objSendMail;
    }


    // テンプレートを使用したメールの送信
    public function sfSendTplMail($to, $tmp_subject, $tplpath, &$objPage)
    {
        $objMailView = new SC_SiteView_Ex();
        $objMailView->setPage($this->getPage());
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        // メール本文の取得
        $objPage->tpl_shopname=$arrInfo['shop_name'];
        $objPage->tpl_infoemail = $arrInfo['email02'];
        $objMailView->assignobj($objPage);
        $body = $objMailView->fetch($tplpath);
        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $to = mb_encode_mimeheader($to);
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $tosubject = $this->sfMakeSubject($tmp_subject, $objMailView);

        $objSendMail->setItem($to, $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->sendMail();
    }

    // 通常のメール送信
    public function sfSendMail($to, $tmp_subject, $body)
    {
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $tosubject = $this->sfMakeSubject($tmp_subject);

        $objSendMail->setItem($to, $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->sendMail();
    }

//    //件名にテンプレートを用いる
//    function sfMakeSubject(&$objQuery, &$objMailView, &$objPage, $subject){
//
//        $arrInfo = $objQuery->select("*","dtb_baseinfo");
//        $arrInfo = $arrInfo[0];
//        $objPage->tpl_shopname=$arrInfo['shop_name'];
//        $objPage->tpl_infoemail=$subject;
//        $objMailView->assignobj($objPage);
//        $mailtitle = $objMailView->fetch('mail_templates/mail_title.tpl');
//        $ret = $mailtitle.$subject;
//       return $ret;
//    }

    //件名にテンプレートを用いる
    /**
     * @param SC_SiteView_Ex $objMailView
     */
    public function sfMakeSubject($subject, &$objMailView = NULL)
    {
        if (empty($objMailView)) {
            $objMailView = new SC_SiteView_Ex();
            $objMailView->setPage($this->getPage());
        }
        $objTplAssign = new stdClass;

        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $objTplAssign->tpl_shopname=$arrInfo['shop_name'];
        $objTplAssign->tpl_infoemail=$subject; // 従来互換
        $objTplAssign->tpl_mailtitle=$subject;
        $objMailView->assignobj($objTplAssign);
        $subject = $objMailView->fetch('mail_templates/mail_title.tpl');
        // #1940 (SC_Helper_Mail#sfMakeSubject 先頭に改行を含む値を返す) 対応
        $subject = trim($subject);

        return $subject;
    }

    /**
     * @param SC_SiteView_Ex $objMailView
     */
    public function sfMakeSubjectReal($subject, &$objMailView = NULL)
    {
        if (empty($objMailView)) {
            $objMailView = new SC_SiteView_Ex();
            $objMailView->setPage($this->getPage());
        }
        $objTplAssign = new stdClass;

        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $objTplAssign->tpl_shopname=$arrInfo['shop_name'];
        $objTplAssign->tpl_infoemail=$subject; // 従来互換
        $objTplAssign->tpl_mailtitle=$subject;
        $objMailView->assignobj($objTplAssign);
        $subject = $objMailView->fetch('mail_templates/mail_title.tpl');
        // #1940 (SC_Helper_Mail#sfMakeSubject 先頭に改行を含む値を返す) 対応
        $subject = trim($subject);

        return $subject;
    }

    // メール配信履歴への登録

    /**
     * @param string $subject
     */
    public function sfSaveMailHistory($order_id, $template_id, $subject, $body)
    {
        $sqlval = array();
        $sqlval['subject'] = $subject;
        $sqlval['order_id'] = $order_id;
        $sqlval['template_id'] = $template_id;
        $sqlval['send_date'] = 'CURRENT_TIMESTAMP';
        if (!isset($_SESSION['member_id'])) $_SESSION['member_id'] = '';
        if ($_SESSION['member_id'] != '') {
            $sqlval['creator_id'] = $_SESSION['member_id'];
        } else {
            $sqlval['creator_id'] = '0';
        }
        $sqlval['mail_body'] = $body;

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $sqlval['send_id'] = $objQuery->nextVal('dtb_mail_history_send_id');
        $objQuery->insert('dtb_mail_history', $sqlval);
    }

    /* 会員登録があるかどうかのチェック(仮会員を含まない) */
    public function sfCheckCustomerMailMaga($email)
    {
        $col = 'email, mailmaga_flg, customer_id';
        $from = 'dtb_customer';
        $where = '(email = ? OR email_mobile = ?) AND status = 2 AND del_flg = 0';
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrRet = $objQuery->select($col, $from, $where, array($email));
        // 会員のメールアドレスが登録されている
        if (!empty($arrRet[0]['customer_id'])) {
            return true;
        }

        return false;
    }

    /**
     * 登録メールを送信する。
     *
     * @param  string  $secret_key  会員固有キー
     * @param  integer $customer_id 会員ID
     * @param  boolean $is_mobile   false(default):PCアドレスにメールを送る true:携帯アドレスにメールを送る
     * @param $resend_flg true  仮登録メール再送
     * @return boolean true:成功 false:失敗
     *  
     */
    public function sfSendRegistMail($secret_key, $customer_id = '', $is_mobile = false, $resend_flg = false)
    {
        // 会員データの取得
        if (SC_Utils_Ex::sfIsInt($customer_id)) {
            $arrCustomerData = SC_Helper_Customer_Ex::sfGetCustomerDataFromId($customer_id);
        } else {
            $arrCustomerData = SC_Helper_Customer_Ex::sfGetCustomerDataFromId('', 'secret_key = ?', array($secret_key));
        }
        if (SC_Utils_Ex::isBlank($arrCustomerData)) {
            return false;
        }

        $CONF = SC_Helper_DB_Ex::sfGetBasisData();

        $objMailText = new SC_SiteView_Ex();
        $objMailText->setPage($this->getPage());
        $objMailText->assign('CONF', $CONF);
        $objMailText->assign('name01', $arrCustomerData['name01']);
        $objMailText->assign('name02', $arrCustomerData['name02']);
        $objMailText->assign('uniqid', $arrCustomerData['secret_key']);
        $objMailText->assignobj($arrCustomerData);
        $objMailText->assignobj($this);

        $objHelperMail  = new SC_Helper_Mail_Ex();
        // 仮会員が有効の場合    
        if (CUSTOMER_CONFIRM_MAIL == true and $arrCustomerData['status'] == 1 or $arrCustomerData['status'] == 1 and $resend_flg == true) {
            $subject        = $objHelperMail->sfMakeSubjectReal('会員登録のご確認', $objMailText);
            $toCustomerMail = $objMailText->fetch('mail_templates/customer_mail.tpl');
        } else {
            $subject        = $objHelperMail->sfMakeSubjectReal('会員登録のご完了', $objMailText);
            $toCustomerMail = $objMailText->fetch('mail_templates/customer_regist_mail.tpl');
            
        }

        $objMail = new SC_SendMail_Ex();
        $objMail->setItem(
            '',                     // 宛先
            $subject,               // サブジェクト
            $toCustomerMail,        // 本文
            $CONF['email03'],       // 配送元アドレス
            $CONF['shop_name'],     // 配送元 名前
            $CONF['email03'],       // reply_to
            $CONF['email04'],       // return_path
            $CONF['email04'],       // Errors_to
            $CONF['email01']        // Bcc
        );
        // 宛先の設定
        if ($is_mobile) {
            $to_addr = $arrCustomerData['email_mobile'];
        } else {
            $to_addr = $arrCustomerData['email'];
        }
        $objMail->setTo($to_addr, $arrCustomerData['name01'] . $arrCustomerData['name02'] .' 様');

        $objMail->sendMail();

        return true;
    }

    /**
     * 保存されているメルマガテンプレートの取得
     * @param integer 特定IDのテンプレートを取り出したい時はtemplate_idを指定。未指定時は全件取得
     * @return　array メールテンプレート情報を格納した配列
     * @todo   表示順も引数で変更できるように
     */
    public function sfGetMailmagaTemplate($template_id = null)
    {
        // 初期化
        $where = '';
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 条件文
        $where = 'del_flg = ?';
        $arrValues[] = 0;
        //template_id指定時
        if (SC_Utils_Ex::sfIsInt($template_id) === true) {
            $where .= ' AND template_id = ?';
            $arrValues[] = $template_id;
        }

        // 表示順
        $objQuery->setOrder('create_date DESC');

        $arrResults = $objQuery->select('*', 'dtb_mailmaga_template', $where, $arrValues);

        return $arrResults;
    }

    /**
     * 保存されているメルマガ送信履歴の取得
     * @param integer 特定の送信履歴を取り出したい時はsend_idを指定。未指定時は全件取得
     * @return　array 送信履歴情報を格納した配列
     */
    public function sfGetSendHistory($send_id = null)
    {
        // 初期化
        $where = '';
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 条件文
        $where = 'del_flg = ?';
        $arrValues[] = 0;

        //send_id指定時
        if (SC_Utils_Ex::sfIsInt($send_id) === true) {
            $where .= ' AND send_id = ?';
            $arrValues[] = $send_id;
        }

        // 表示順
        $objQuery->setOrder('create_date DESC');

        $arrResults = $objQuery->select('*', 'dtb_send_history', $where, $arrValues);

        return $arrResults;
    }

    /**
     * 指定したIDのメルマガ配送を行う
     *
     * @param integer $send_id dtb_send_history の情報
     * @return　void
     */
    public function sfSendMailmagazine($send_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();
        $objSite = $objDb->sfGetBasisData();
        $objMail = new SC_SendMail_Ex();

        $where = 'del_flg = 0 AND send_id = ?';
        $arrMail = $objQuery->getRow('*', 'dtb_send_history', $where, array($send_id));

        // 対象となる$send_idが見つからない
        if (SC_Utils_Ex::isBlank($arrMail)) return;

        // 送信先リストの取得
        $arrDestinationList = $objQuery->select(
            '*',
            'dtb_send_customer',
            'send_id = ? AND (send_flag = 2 OR send_flag IS NULL)',
            array($send_id)
        );

        // 現在の配信数
        $complete_count = $arrMail['complete_count'];
        if (SC_Utils_Ex::isBlank($arrMail)) {
            $complete_count = 0;
        }

        foreach ($arrDestinationList as $arrDestination) {
            // お名前の変換
            $customerName = trim($arrDestination['name']);
            $subjectBody = preg_replace('/{name}/', $customerName, $arrMail['subject']);
            $mailBody = preg_replace('/{name}/', $customerName, $arrMail['body']);

            $objMail->setItem(
                $arrDestination['email'],
                $subjectBody,
                $mailBody,
                $objSite['email03'],      // 送信元メールアドレス
                $objSite['shop_name'],    // 送信元名
                $objSite['email03'],      // reply_to
                $objSite['email04'],      // return_path
                $objSite['email04']       // errors_to
            );

            // テキストメール配信の場合
            if ($arrMail['mail_method'] == 2) {
                $sendResut = $objMail->sendMail();
            // HTMLメール配信の場合
            } else {
                $sendResut = $objMail->sendHtmlMail();
            }

            // 送信完了なら1、失敗なら2をメール送信結果フラグとしてDBに挿入
            if (!$sendResut) {
                $sendFlag = '2';
            } else {
                // 完了を 1 増やす
                $sendFlag = '1';
                $complete_count++;
            }

            // 送信結果情報を更新
            $objQuery->update('dtb_send_customer',
                              array('send_flag'=>$sendFlag),
                              'send_id = ? AND customer_id = ?',
                              array($send_id,$arrDestination['customer_id']));
        }

        // メール全件送信完了後の処理
        $objQuery->update('dtb_send_history',
                          array('end_date'=>'CURRENT_TIMESTAMP', 'complete_count'=>$complete_count),
                          'send_id = ?',
                          array($send_id));

        // 送信完了　報告メール
        $compSubject = date('Y年m月d日H時i分') . '  下記メールの配信が完了しました。';
        // 管理者宛に変更
        $objMail->setTo($objSite['email03']);
        $objMail->setSubject($compSubject);

        // テキストメール配信の場合
        if ($arrMail['mail_method'] == 2) {
            $sendResut = $objMail->sendMail();
        // HTMLメール配信の場合
        } else {
            $sendResut = $objMail->sendHtmlMail();
        }

        return;
    }


    function lfMakeSubjectDate($deliv_date) {
       $weekday = array("日" => "previous Sunday", "月" => "previous Monday", "火" => "previous Tuesday",
                                    "水" => "previous Wednesday", "木" => "next Thursday", "金" => "next Friday","土" => "previous Saturday");
        // 曜日を取得
        $_arrWday = $this->lfGetWday(mb_substr($deliv_date, mb_strlen($deliv_date)-2, 1));
        // 曜日部分「(日)」とか切り捨てる
        $deliv_date = $deliv_date = mb_substr($deliv_date, 0, mb_strlen($deliv_date) - 3);
        $return_caption = RETURN_TIME . "まで";
        // 年を取得
        $year = date('Y');
        // 置換
        $deliv_date = str_replace("月", "-", $deliv_date);
        $deliv_date = str_replace("日", "", $deliv_date);
        // YYYY-DD-MMの書式を作成
        $deliv_date = $year .'-' .$deliv_date;
        // お届け日のタイムスタンプ
        $target_time = strtotime($deliv_date);
        $arrSubjectDate = array();
        // 発送日
        $arrSubjectData['deliv_date'] = date("n月j日",strtotime("-1 day" ,$target_time))."(".$_arrWday[3].")";
        // ご利用日
        $arrSubjectData['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        // ご返却日
        $arrSubjectData['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        return $arrSubjectData;
    }

    /**
     * お届け日から4日間の曜日を返す 最後の要素にお届け日の前日の曜日が入る(発送日対応)
     * $deliv_day_of_the_week お届け曜日
     */
    function lfGetWday($deliv_day_of_the_week) {
        $ret = array();
        switch ($deliv_day_of_the_week) {
            case "日":
                $ret[0] = "月";
                $ret[1] = "火";
                $ret[2] = "水";
                $ret[3] = "土";
                break;
            case "月":
                $ret[0] = "火";
                $ret[1] = "水";
                $ret[2] = "木";
                $ret[3] = "日";
                break;
            case "火":
                $ret[0] = "水";
                $ret[1] = "木";
                $ret[2] = "金";
                $ret[3] = "月";
                break;
            case "水":
                $ret[0] = "木";
                $ret[1] = "金";
                $ret[2] = "土";
                $ret[3] = "火";
                 break;
            case "木":
                $ret[0] = "金";
                $ret[1] = "土";
                $ret[2] = "日";
                $ret[3] = "水";
                break;
            case "金":
                $ret[0] = "土";
                $ret[1] = "日";
                $ret[2] = "月";
                $ret[3] = "木";
                break;
            case "土":
                $ret[0] = "日";
                $ret[1] = "月";
                $ret[2] = "火";
                $ret[3] = "金";
                break;
            default:
                break;
        }
        return $ret;
    }


}
