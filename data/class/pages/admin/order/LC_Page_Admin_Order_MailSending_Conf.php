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
class LC_Page_Admin_Order_MailSending_Conf extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/mail_sending_conf.tpl';
        $this->tpl_subnavi = 'order/subnavi.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'mail_sending';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = 'メール一括送信管理';

        //$masterData = new SC_DB_MasterData_Ex();
        //$this->arrMAILTEMPLATE = $masterData->getMasterData("mtb_mail_template");

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
//KHS Change 2014.3.16
        //$conn = new SC_DbConn();
        $conn = & SC_Query_Ex::getSingletonInstance();
//KHS END
        $objView = new SC_AdminView();
        $objSess = new SC_Session();
        $objSess->SetPageShowFlag(true); // 全てのユーザが閲覧可能(Add By RCHJ)

        SC_Utils_Ex::sfIsSuccess($objSess);

        // 認証可否の判定
        SC_Utils_Ex::sfIsSuccess($objSess);

        // get template id and name
		$sql = "SELECT template_id, template_name FROM dtb_mailtemplate_batch WHERE del_flg = 0 and template_type = 0 order by template_id";
        $templ_result = $conn->getAll($sql);
        $temp = array();
        foreach ($templ_result as $vals){
        	$temp[$vals['template_id']] = $vals['template_name'];
        }
        $this->arrMAILTEMPLATE = $temp;

        // 検索パラメータの引き継ぎ
        foreach ($_POST as $key => $val)
        {
            if (preg_match("/^search_/", $key))
            {
                $this->arrSearchHidden[$key] = $val;
            }
        }
        $this->tpl_order_id = $_POST['order_id'];

        // パラメータ管理クラス
        $objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam($objFormParam);

        $objMail = new SC_Helper_Mail_Ex();
        switch($_POST['mode']) {
        case 'pre_edit':
            break;
        case 'return':
            // POST値の取得
            $objFormParam->setParam($_POST);
            break;
        case 'send':
            // POST値の取得
            $objFormParam->setParam($_POST);
            // 入力値の変換
            $objFormParam->convParam();
            $this->arrErr = $objFormParam->checkerror();
            // メールの送信

            if (count($this->arrErr) == 0) {
            	$in_order_id = "";
                // 注文受付メール
                for ($i=0; $i<count($_POST['select_order_id']); $i++){
                	//$m_order_id = $_POST['select_order_id'][$i];
                    $temp = explode('_',$_POST['select_order_id'][$i]);
                    $m_order_id=$temp[0];
					if (empty($m_order_id)){
						continue;
					}

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

                    //お届け時間を追加
                    $objQuery = SC_Query_Ex::getSingletonInstance();
                    //$objQuery->begin();
                    $result_time = $objQuery->getall("SELECT deliv_time FROM dtb_order WHERE order_id = ? ", array($m_order_id));

					$return_date = date("Y年m月d日", strtotime("+3 days ".$deliv_day));
					$return_date .= "（".$wday_array[date("w", strtotime("+3 days ".$deliv_day))]."）".RETURN_TIME ."まで";

					$delivdate_data = date("Y年m月d日", strtotime($deliv_day));
					$delivdate_data .= "（".$wday_array[date("w", strtotime($deliv_day))]."）" . $result_time[0]['deliv_time'];

//KHS Change 2014.3.16
                    //$conn = new SC_DbConn();
                    $conn = SC_Query_Ex::getSingletonInstance();
//KHS END

					$sql = "SELECT * FROM dtb_order_qnumber WHERE order_id = ?";
					$result = $conn->getAll($sql, array($m_order_id));
					if ($result){
						$sql_where = "order_id = ". $m_order_id;
						$conn->query("UPDATE dtb_order_qnumber SET qnumber1 = ?, qnumber2 = ?,update_date = now() WHERE ".$sql_where, array($_POST['p_num1'][$m_order_id],$_POST['p_num2'][$m_order_id]));
					}
					else{
						$conn->query("INSERT INTO dtb_order_qnumber (order_id,qnumber1,qnumber2,update_date,create_date) values (?,?,?,now(),now())", array($m_order_id,$_POST['p_num1'][$m_order_id],$_POST['p_num2'][$m_order_id]));
					}


					$pursuit_data = array("p_num1"=>$_POST['p_num1'][$m_order_id], "p_num2"=>$_POST['p_num2'][$m_order_id],
						"delive_date"=>$delivdate_data, "return_date"=>$return_date);
	                $objMail->sfSendOrderMail_ALL($m_order_id, $_POST['template_id'], $_POST['subject'], $_POST['body'], $pursuit_data);
                }
// ============2012.06.08 RCHJ Add============
                //$in_order_id .= implode(",", $_POST['select_order_id']);
                //KHS ADD 2014.3.27
                foreach($_POST['select_order_id'] as $var){
                    $temp = explode('_',$var);
                    $temp_id[]=$temp[0];
                }
                $in_order_id .= implode(",", $temp_id);

                if($_POST['template_id'] == 15){
                    //一週間前メールではステータスを変えない
                }else{
                    $query = SC_Query_Ex::getSingletonInstance();
                    $query->update("dtb_order", array("status"=>ORDER_STATUS_DELIV, "update_date"=>"now()"),
                            "order_id in (".$in_order_id.")");
                }
// ==================end============
            }
            $this->sendRedirect($this->getLocation(URL_DIR . "ChlFApkIyT8eBiMz/order/mail_sending.php"));
            exit;
            break;
        case 'confirm':
        	/*
            // POST値の取得
            $objFormParam->setParam($_POST);
            // 入力値の変換
            $objFormParam->convParam();
            // 入力値の引き継ぎ
            $this->arrHidden = $objFormParam->getHashArray();
            $this->arrErr = $objFormParam->checkerror();
            // メールの送信
            if (count($this->arrErr) == 0) {
                // 注文受付メール(送信なし)
                $objSendMail = $objMail->sfSendOrderMail($_POST['order_id'], $_POST['template_id'], $_POST['subject'], $_POST['header'], $_POST['footer'], false);
                // 確認ページの表示
                $this->tpl_subject = $_POST['subject'];
                $this->tpl_body = mb_convert_encoding( $objSendMail->body, CHAR_CODE, "auto" );
                $this->tpl_to = $objSendMail->tpl_to;
                $this->tpl_mainpage = 'order/mail_confirm.tpl';

                $objView->assignobj($this);
                $objView->display(MAIN_FRAME);
                exit;
            }
            break;
            */
        case 'change':
	        // POST値の取得
	        $objFormParam->setParam($_POST);
			// 入力値の変換
			$objFormParam->convParam();
            // 入力値の引き継ぎ
            $this->arrHidden = $objFormParam->getHashArray();
            if(SC_Utils_Ex::sfIsInt($_POST['template_id'])) {
                //$objQuery = new SC_Query();
                //$where = "template_id = ?";
                //$arrRet = $objQuery->select("subject, header, footer", "dtb_mailtemplate", $where, array($_POST['template_id']));
                //$objFormParam->setParam($arrRet[0]);

                $sql = "SELECT * FROM dtb_mailtemplate_batch WHERE template_id = ?";
                $result = $conn->getAll($sql, array($_POST['template_id']) );

                if ( $result ){
                    $objFormParam->setParam($result[0]);
                    //$this->arrForm = $result[0];
                } else {
                    //$this->arrForm['template_id'] = $_POST['template_id'];
                }

            }else{
            	$arrRet = array();
            	$arrRet['subject'] = "";
            	$arrRet['body'] = "";
            	//$arrRet['header'] = "";
            	//$arrRet['footer'] = "";
            	$objFormParam->setParam($arrRet);
            }
            break;

        default:
	        // POST値の取得
	        $objFormParam->setParam($_POST);
			// 入力値の変換
			$objFormParam->convParam();
			// 入力値の引き継ぎ
			$this->arrHidden = $objFormParam->getHashArray();

        }
		//選択された顧客情報一覧
		$customer_data = array();
			for ($i=0; $i<count($_POST['select_order_id']); $i++){
                //KHS ADD 2014.3.27
                //$c_order_id = $_POST['select_order_id'][$i];
                $c_order_id_key = $_POST['select_order_id'][$i];
                $temp = explode('_',$c_order_id_key);
                $c_order_id=$temp[0];
                $sel_key=$temp[1];
                //KHS END
	            $customer_data[$i]['order_id'] = $c_order_id;
                //KHS ADD 2014.3.27
                $sel_name=$_POST['sel_name'][$c_order_id];
                foreach($sel_name as $key=>$val){
                     if($key==$sel_key ){
                        $customer_data[$i]['name'] = $val;
                     }
                }
 			}
        $this->arrSelectCustomer = $customer_data;

        $this->arrForm = $objFormParam->getFormParamList();

        $objView->assignobj($this);
        //$objView->display(MAIN_FRAME);
        $this->sendResponse();
    }



    /* パラメータ情報の初期化 */
    function lfInitParam(&$objFormParam) {

        $objFormParam->addParam("テンプレート", "template_id", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("メールタイトル", "subject", STEXT_LEN, "KVa",  array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));
        //$objFormParam->addParam("ヘッダー", "header", LTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));
        //$objFormParam->addParam("フッター", "footer", LTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));
        $objFormParam->addParam("メール本体", "body", LTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));
        $objFormParam->addParam("追跡番号1", "p_num1");
        $objFormParam->addParam("追跡番号2", "p_num2");
        $objFormParam->addParam("選択された注文番号", "select_order_id");
        $objFormParam->addParam("選択された顧客名", "sel_name");
        $objFormParam->addParam("選択されたお届け日", "sel_deliv_date");

    }
}
?>
