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
class LC_Page_Admin_Order_MailSendingView extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/mail_sending_view.tpl';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_AdminView();
        $objSess = new SC_Session();
        $objSess->SetPageShowFlag(true); // 全てのユーザが閲覧可能(Add By RCHJ)

		$objMail = new SC_Helper_Mail_Ex();

        // 認証可否の判定
        SC_Utils_Ex::sfIsSuccess($objSess);

        if(SC_Utils_Ex::sfIsInt($_GET['order_id'])) {

           // if(SC_Utils_Ex::sfIsInt($_POST['template_id'])) {
				//(送信なし)
				$m_order_id = $_GET['order_id'];

				$deliv_day = preg_replace("/日.{3}/u", "",  $_POST['sel_deliv_date'][$m_order_id]);
				$deliv_day = preg_replace("/月/u",     "-", $deliv_day);

				$cur_month = date("n");
				list($deliv_month, $deliv_date) = explode('[/.-]', $deliv_day);

				if($cur_month == "12" && $deliv_month < $cur_month){
					$deliv_day = (date("Y")+1)."-".$deliv_day;
				}else{
					$deliv_day = date("Y")."-".$deliv_day;
				}

				$wday_array = array('1' => '月', '2' => '火', '3' => '水',
					    		'4' => '木', '5' => '金', '6' => '土', '0' => '日' );

                // お届け時間を追加
                $objQuery = new SC_Query();
                $objQuery->begin();
                $result_time = $objQuery->getall("SELECT deliv_time FROM dtb_order WHERE order_id = ? ", array($m_order_id));

				$return_date = date("Y年m月d日", strtotime("+3 days ".$deliv_day));
				$return_date .= "（".$wday_array[date("w", strtotime("+3 days ".$deliv_day))]."）".RETURN_TIME ."まで";

				$delivdate_data = date("Y年m月d日", strtotime($deliv_day));
				$delivdate_data .= "（".$wday_array[date("w", strtotime($deliv_day))]."）" . $result_time[0]['deliv_time'];



				$pursuit_data = array("p_num1"=>$_POST['p_num1'][$m_order_id], "p_num2"=>$_POST['p_num2'][$m_order_id],
					"delive_date"=>$delivdate_data, "return_date"=>$return_date);
			    $objSendMail = $objMail->sfSendOrderMail_ALL($m_order_id, $_POST['template_id'], $_POST['subject'], $_POST['body'], $pursuit_data, false);

			    $this->tpl_subject = $_POST['subject'];
            	$this->tpl_body = mb_convert_encoding( $objSendMail->body, CHAR_CODE, "auto" );
            //}else{

            //	$this->tpl_onload = "alert('テンプレートを選択ください。');  window.close();";
            //	$objView->assignobj($this);
        	//	$objView->display(MAIN_FRAME);
            //	return ;
           // }

        }

        $objView->assignobj($this);
        $objView->display($this->tpl_mainpage);
    }

}
?>
