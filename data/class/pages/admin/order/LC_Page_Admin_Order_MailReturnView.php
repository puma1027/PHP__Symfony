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
 * 受注管理メール確認 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Order_MailReturnView extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/mail_return_view.tpl';

        $this->arrUseStatus = array(1=>"A(大変丁寧にお使いいただきました)",
        							2=>"B(小さな汚れ等がありますが、丁寧にお使いいただきました)",
        							3=>"C(ご利用中に、やや大きな汚れもしくは傷が付きました)",
        							4=>"D(ご利用中に、大きな汚れもしくは傷が付きました)",
        							5=>"E(ご利用中に、大変大きな汚れもしくは傷が付きました)");
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_AdminView();
        $objSess = new SC_Session();
		$objMail = new SC_Helper_Mail_Ex();

        // 認証可否の判定
        $objSess->SetPageShowFlag(true);//::N00001 Add 20130315
        SC_Utils_Ex::sfIsSuccess($objSess);
        if(SC_Utils_Ex::sfIsInt($_GET['order_id'])) {
            if(SC_Utils_Ex::sfIsInt($_POST['template_id'])) {
            	$wday_array = array('1' => '月', '2' => '火', '3' => '水',
					    		'4' => '木', '5' => '金', '6' => '土', '0' => '日' );

				// 注文受付メール(送信なし)
				$m_order_id = $_GET['order_id'];
				$delivdate_data = $_POST['deliv_date'];

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

                //::B00003 Change 20130403
                //dtb_mailtemplate_batch の『アンケートなし』の場合のみ、『協力ポイント200pt』を表示させないために order_detail_template_id をかえる
                if ($_POST['template_id'] != 8) {
                    $template_id = 7;
				} else {
                    $template_id = 8;
                }
                //::B00003 Change 20130403
				$sending_data = array("template_id"=>$_POST['template_id'], "use_status"=>$this->arrUseStatus[$_POST['use_status']],
							"dirt_detail"=>$_POST['dirt_details'], "insertdata_one"=>$_POST['insertdata_one'],
							"insertdata_two"=>$_POST['insertdata_two'],
							"insertdata_three"=>$_POST['insertdata_three'],//::N00026 Add 20130401
							"insertdata_four"=>$_POST['insertdata_four'],//::N00026 Add 20130401
							"shipdate_data"=>$shipdate_data,
							"delivdate_data"=>$delivdate_data_real, "usedate_data"=>$usedate_data, "return_data"=>$return_data,
                            //::"order_detail_template_id"=>7,
                            "order_detail_template_id"=>$template_id,//::B00003 Change 20130403
							"attach_files"=>null,
						);


			    $objSendMail = $objMail->sfSendOrderMailReturn($m_order_id, $sending_data, false);

            	$this->tpl_subject = $_POST['subject'];
            	$this->tpl_body = mb_convert_encoding( $objSendMail->body, CHAR_CODE, "auto" );
            	$this->tpl_file = $_POST['add_file'];
            }else{
            	$this->tpl_onload = "alert('テンプレートを選択ください。');  window.close();";
            	$objView->assignobj($this);
        		$objView->display(MAIN_FRAME);
            	return ;
            }
        }

        $objView->assignobj($this);
        $objView->display($this->tpl_mainpage);
    }

}
?>
