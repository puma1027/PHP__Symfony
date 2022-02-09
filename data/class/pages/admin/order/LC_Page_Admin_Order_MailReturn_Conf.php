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

/**
 * 受注メール管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Order_MailReturn_Conf extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/mail_return_conf.tpl';
        $this->tpl_subnavi = 'order/subnavi.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'mail_sending';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = 'メール一括送信管理';

        // get template id and name
//KHS Change 2014.3.16
        //$conn = new SC_DbConn();
        $conn = & SC_Query_Ex::getSingletonInstance();
//KHS END
		$sql = "SELECT template_id, template_name, subject, input1, input2, input4, input5 FROM dtb_mailtemplate_batch WHERE del_flg = 0 and template_type = 1 order by template_id";//::N00026 Add 20130401
        $templ_result = $conn->getAll($sql);
        $temp = array();
        $temp1 = array();
        $temp2 = array();
        $temp3 = array();
        $this->first_template_id = $templ_result[0]['template_id'];
        foreach ($templ_result as $vals){
        	$temp[$vals['template_id']] = $vals['template_name'];
        	$temp1[$vals['template_id']] = $vals['subject'];
        	$temp2[$vals['template_id']] = $vals['input1'];
        	$temp3[$vals['template_id']] = $vals['input2'];
        	$temp4[$vals['template_id']] = $vals['input4'];//::N00026 Add 20130401
        	$temp5[$vals['template_id']] = $vals['input5'];//::N00026 Add 20130401
        }
        $this->arrMailTEMPLATE = $temp;
        $this->arrMailSubject = $temp1;
        $this->arrInsertOneDetail = $temp2;
        $this->arrInsertTwoDetail = $temp3;
        $this->arrInsertThreeDetail = $temp4;//::N00026 Add 20130401
        $this->arrInsertFourDetail = $temp5;//::N00026 Add 20130401

        $this->arrUseStatus = array(1=>"A(大変丁寧にお使いいただきました)",
        							2=>"B(小さな汚れ等がありますが、丁寧にお使いいただきました)",
        							3=>"C(ご利用中に、やや大きな汚れもしくは傷が付きました)",
        							4=>"D(ご利用中に、大きな汚れもしくは傷が付きました)",
        							5=>"E(ご利用中に、大変大きな汚れもしくは傷が付きました)");
		$this->dirtDetail = "なし";
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {

        $objView = new SC_AdminView();
        $objSess = new SC_Session();
        $objSess->SetPageShowFlag(true);//::N00001 Add 20130315
        SC_Utils_Ex::sfIsSuccess($objSess);

        $this->tpl_order_id = $_POST['order_id'];

        $objMail = new SC_Helper_Mail_Ex();
      	// パラメータ管理クラス
        $objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam($objFormParam);

        switch($_POST['mode']) {
        case 'pre_edit':
            break;
        case 'return':
            // POST値の取得
            $objFormParam->setParam($_POST);
            break;
        case 'delete_upload':
        	$upload_index = $_POST['upload_index'];

        	$delete_file = $_POST['uploaded_full_hid'.$upload_index];
        	if(!empty($delete_file)){
        		@unlink($delete_file);
        		$_POST['uploaded_full_hid'.$upload_index] = "";
        		$_POST['uploaded_hid'.$upload_index] = "";
        	}

        	// POST値の取得
            $objFormParam->setParam($_POST);
            // 入力値の変換
            $objFormParam->convParam();

        	break;
        case 'send':
        	// POST値の取得
            $objFormParam->setParam($_POST);
            // 入力値の変換
            $objFormParam->convParam();
            $this->arrErr = $objFormParam->checkerror();
            // メールの送信
            if (count($this->arrErr) == 0) {
                // 注文受付メール
                $wday_array = array('1' => '月', '2' => '火', '3' => '水',
					    		'4' => '木', '5' => '金', '6' => '土', '0' => '日' );
                for ($i=0; $i<count($_POST['select_order_id']); $i++){
                	//$m_order_id = $_POST['select_order_id'][$i];
                    $temp = explode('_',$_POST['select_order_id'][$i]);
                    $m_order_id=$temp[0];

					if (empty($m_order_id)){
						continue;
					}
					if(!isset($_POST['chk_'.$m_order_id])){
						continue;
					}
					$delivdate_data = $_POST['sel_deliv_date'][$m_order_id];

					$shipdate_data = date("Y年m月d日", strtotime("-1 days ".$delivdate_data));
					$shipdate_data .= "（".$wday_array[date("w", strtotime("-1 days ".$delivdate_data))]."）";

					$usedate_data = date("Y年m月d日", strtotime("+1 days ".$delivdate_data));
					$usedate_data .= "（".$wday_array[date("w", strtotime("+1 days ".$delivdate_data))]."）";
					$usedate_data .= "～".date("Y年m月d日", strtotime("+2 days ".$delivdate_data));
					$usedate_data .= "（".$wday_array[date("w", strtotime("+2 days ".$delivdate_data))]."）";

					$return_data = date("Y年m月d日", strtotime("+3 days ".$delivdate_data));
					$return_data .= "（".$wday_array[date("w", strtotime("+3 days ".$delivdate_data))]."）".RETURN_TIME ."まで";

					$delivdate_data_real = date("Y年m月d日", strtotime($delivdate_data));
					$delivdate_data_real .= "（".$wday_array[date("w", strtotime($delivdate_data))]."）";

					$attach_file = array(
							$_POST['uploaded_full_hid1_'.$m_order_id],
							$_POST['uploaded_full_hid2_'.$m_order_id],
							$_POST['uploaded_full_hid3_'.$m_order_id],
					);
					$attach_url_file = array(
							$_POST['uploaded_hid1_'.$m_order_id],
							$_POST['uploaded_hid2_'.$m_order_id],
							$_POST['uploaded_hid3_'.$m_order_id],
					);
                //::B00003 Change 20130403
                //dtb_mailtemplate_batch の『アンケートなし』の場合のみ、『協力ポイント200pt』を表示させないために order_detail_template_id をかえる
                if ($_POST['template_id_'.$m_order_id] != 8) {
                    $template_id = 7;
				} else {
                    $template_id = 8;
                }
                //::B00003 Change 20130403
					$sending_data = array("template_id"=>$_POST['template_id_'.$m_order_id], "use_status"=>$this->arrUseStatus[$_POST['use_status_'.$m_order_id]],
							"dirt_detail"=>$_POST['dirt_details_'.$m_order_id], "insertdata_one"=>$_POST['insertdata_one_'.$m_order_id],
							"insertdata_two"=>$_POST['insertdata_two_'.$m_order_id],
							"insertdata_three"=>$_POST['insertdata_three_'.$m_order_id],//::N00026 Add 20130401
							"insertdata_four"=>$_POST['insertdata_four_'.$m_order_id],//::N00026 Add 20130401
							"shipdate_data"=>$shipdate_data,
							"delivdate_data"=>$delivdate_data_real, "usedate_data"=>$usedate_data, "return_data"=>$return_data,
                            //"order_detail_template_id"=>7,
                            "order_detail_template_id"=>$template_id,//::B00003 Change 20130403
							"attach_files"=>$attach_file, "attach_url_files"=>$attach_url_file
					);
	                $objMail->sfSendOrderMailReturn($m_order_id, $sending_data);


	                if($_POST['chk_'.$m_order_id] == 1){
//KHS Change 2014.3.16
                        //$objQuery = new SC_Query();
                        $objQuery = SC_Query_Ex::getSingletonInstance();
//KHS END
	                	$objQuery->update("dtb_order", array("status"=>ORDER_STATUS_RETURN, "update_date"=>"now()"),
	                			"order_id in (".$m_order_id.")");
	                }
                }

// ==================end============
                $this->sendRedirect($this->getLocation(URL_DIR . "ChlFApkIyT8eBiMz/order/mail_sending.php"));
            	exit;
            }
            break;

        default:
        	// get deliv date
        	for ($i=0; $i<count($_POST['select_order_id']); $i++){
        		//$c_order_id = $_POST['select_order_id'][$i];
                $temp = explode('_',$_POST['select_order_id'][$i]);
                $c_order_id=$temp[0];

        		$_POST['sel_deliv_date'][$c_order_id] = $this->getDelivDay($_POST['sel_deliv_date'][$c_order_id]);
        	}
        }

        $this->arrForm = $objFormParam->getFormParamList();

		//選択された顧客情報一覧
		$customer_data = array();
			for ($i=0; $i<count($_POST['select_order_id']); $i++){
	            //$c_order_id = $_POST['select_order_id'][$i];
                //KHS ADD 2014.3.27
                $c_order_id_key = $_POST['select_order_id'][$i];
                $temp = explode('_',$c_order_id_key);
                $c_order_id=$temp[0];
                $sel_key=$temp[1];
                //KHS END
	            $customer_data[$i]['order_id'] = $c_order_id;
	            //$customer_data[$i]['name'] = $_POST['sel_name'][$c_order_id];
                //KHS Add 2014.3.27
                $sel_name=$_POST['sel_name'][$c_order_id];
                foreach($sel_name as $key=>$val){
                     if($key==$sel_key ){
                        $customer_data[$i]['name'] = $val;
                     }
                }
                //KHS END
	            $customer_data[$i]['deliv_date'] = $_POST['sel_deliv_date'][$c_order_id];
	            if(isset($_POST["template_id_".$c_order_id])){
	            	$this->arrForm["msg1_".$c_order_id]['value'] = $this->arrInsertOneDetail[$_POST["template_id_".$c_order_id]];
	            	$this->arrForm["msg2_".$c_order_id]['value'] = $this->arrInsertTwoDetail[$_POST["template_id_".$c_order_id]];
	            	$this->arrForm["msg3_".$c_order_id]['value'] = $this->arrInsertThreeDetail[$_POST["template_id_".$c_order_id]];//::N00026 Add 20130401
	            	$this->arrForm["msg4_".$c_order_id]['value'] = $this->arrInsertFourDetail[$_POST["template_id_".$c_order_id]];//::N00026 Add 20130401
	            }
			}
        $this->arrSelectCustomer = $customer_data;

	    // 検索パラメータの引き継ぎ
        foreach ($_POST as $key => $val)
        {
            if (preg_match("/^search_/", $key))
            {
                $this->arrSearchHidden[$key] = $val;
            }
            if (preg_match("/^sel/", $key))
            {
            	$this->arrHidden[$key] = $val;
            }
        }
        $this->tpl_creator_id = $_SESSION['member_id'];

        $objView->assignobj($this);
        $objView->display(MAIN_FRAME);
    }

	function getDelivDay($delivDate){
    	$deliv_day = preg_replace("/日.{3}/u", "",  $delivDate);
        $deliv_day = preg_replace("/月/u",     "-", $deliv_day);

        $cur_month = date("n");
        list($deliv_month, $deliv_date) = explode('[/.-]', $deliv_day);

$deliv_day = date("Y")."-".$deliv_day;
/*
        if($cur_month == "12" && $deliv_month < $cur_month){
        	$deliv_day = (date("Y")+1)."-".$deliv_day;
        }else{
        	$deliv_day = date("Y")."-".$deliv_day;
        }
*/
        return $deliv_day;
    }


    /* パラメータ情報の初期化 */
    function lfInitParam(&$objFormParam) {
    	for ($i=0; $i<count($_POST['select_order_id']); $i++){
            $temp = explode('_',$_POST['select_order_id'][$i]);
            $post_order_id=$temp[0];

	        $objFormParam->addParam("テンプレート", "template_id_".$post_order_id, INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
	        $objFormParam->addParam("ご利用状態", "use_status_".$post_order_id, INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
	        $objFormParam->addParam("汚れ／傷の詳細", "dirt_details_".$post_order_id, MTEXT_LEN, "KV", array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));
            //::N00123 Change 20140311 MTEXT_LEN -> LLTEXT_LEN
	        $objFormParam->addParam("差込1", "insertdata_one_".$post_order_id, LLTEXT_LEN, "KV", array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));
	        $objFormParam->addParam("差込2", "insertdata_two_".$post_order_id, LLTEXT_LEN, "KV", array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));
	        $objFormParam->addParam("差込3", "insertdata_three_".$post_order_id, LLTEXT_LEN, "KV", array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));//::N00026 Add 20130401
	        $objFormParam->addParam("差込4", "insertdata_four_".$post_order_id, LLTEXT_LEN, "KV", array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));//::N00026 Add 20130401
            //::N00123 end 20140311 MTEXT_LEN -> LLTEXT_LEN
	        $objFormParam->addParam("ファイル添付1", "uploaded_hid1_".$post_order_id);
	        $objFormParam->addParam("ファイル添付1-1", "uploaded_full_hid1_".$post_order_id);
	        $objFormParam->addParam("ファイル添付2", "uploaded_hid2_".$post_order_id);
	        $objFormParam->addParam("ファイル添付2-1", "uploaded_full_hid2_".$post_order_id);
	        $objFormParam->addParam("ファイル添付3", "uploaded_hid3_".$post_order_id);
	        $objFormParam->addParam("ファイル添付3-1", "uploaded_full_hid3_".$post_order_id);
	        $objFormParam->addParam("注文番号", "chk_".$post_order_id);
    	}
    	$objFormParam->addParam("選択された注文番号", "select_order_id");
        $objFormParam->addParam("選択された顧客名", "sel_name");
        $objFormParam->addParam("選択されたお届け日", "sel_deliv_date");
    }
}
?>
