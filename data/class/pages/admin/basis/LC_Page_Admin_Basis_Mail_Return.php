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
 * メール設定 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Basis_Mail_Return extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
     public $cn = 0 ;
     
    public function init()
    {
        parent::init();           
        $this->tpl_mainpage = 'basis/mail_return.tpl';
        $this->tpl_mainno = 'basis';
        $this->tpl_subno = 'mail1';
        $this->tpl_maintitle = '基本情報管理';
        $this->tpl_subtitle = '一括メール設定';
        $this->arrTemplateType = array(0=>"発送完了",1=>"返却完了",2=>"返却不良"); 
        $this->arrMailTEMPLATEBatch = null;  
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
        
         
        $mode = $this->getMode();     
        if (!empty($_POST)) {
            $objFormParam = new SC_FormParam_Ex();
            
            $this->lfInitParam($mode, $objFormParam);
            
            $objFormParam->setParam($_POST);
            $objFormParam->convParam();
         
            $this->arrErr = $objFormParam->checkError();
            $post = $objFormParam->getHashArray();
        }         
                          

           if($_POST['mode'] == 'type_set' || $_POST['mode'] == 'id_set'){      
                $template_type = $_POST['template_type'];     
                $this->arrForm['template_type'] = $template_type;
                  
              // get template id and name
                                                         
                $objQuery = SC_Query_Ex::getSingletonInstance();
                $col = 'template_id, template_name';
                $where = 'del_flg = 0 and template_type = ? order by rank'; 
                          
                $mailtemplatebatch = $objQuery->select($col, 'dtb_mailtemplate_batch', $where, array($template_type));
                $temp = array();
                foreach ($mailtemplatebatch as $vals){
                    $temp[$vals['template_id']] = $vals['template_name']; 
                 }
                 $this->arrMailTEMPLATEBatch = $temp;  
                 
                 if ( $_POST['mode'] == 'id_set'){
                 // テンプレートプルダウン変更時    
                     if ( SC_Utils_Ex::sfIsInt( $_POST['template_id']) ){
                          $objQuery = SC_Query_Ex::getSingletonInstance();
                          $col = '*';
                          $where = 'template_id = ?';      
                          $mailtemplatebatch = $objQuery->select($col, 'dtb_mailtemplate_batch', $where, array($post['template_id']));
                          
                          if ($mailtemplatebatch) {
                            $this->arrForm = $mailtemplatebatch[0];
                          } else {
                            $this->arrForm['template_id'] = $post['template_id'];
                          }
                     }    
                 } 
                        
            
           }else if ( $_POST['mode'] == 'regist' && SC_Utils_Ex::sfIsInt( $_POST['template_id']) ){
                    
                                                                          
                         // POSTデータの引き継ぎ
                          $this->arrForm = $post;                              

                           $template_type = $_POST['template_type'];
                          // get template id and name
//                          $sql = "SELECT template_id, template_name FROM dtb_mailtemplate_batch WHERE del_flg = 0 and template_type = ? order by rank";
                          
                          $objQuery = SC_Query_Ex::getSingletonInstance();
                          $col = 'template_id, template_name';
                          $where = 'del_flg = 0 and template_type = ? order by rank'; 
                          
                          $mailtemplatebatch = $objQuery->select($col, 'dtb_mailtemplate_batch', $where, array($template_type));
                          
                          $temp = array();
                          foreach ($mailtemplatebatch as $vals){
                            $temp[$vals['template_id']] = $vals['template_name'];
                          }
                          $this->arrMailTEMPLATEBatch = $temp;

                          if ( $this->arrErr ){
                          // エラーメッセージ
                          $this->tpl_msg = "エラーが発生しました";
                          } else {
                          // 正常
                          $this->lfRegist($this->arrForm);

                          // 完了メッセージ
                          $this->tpl_onload = "window.alert('メール設定が完了しました。テンプレートを選択して内容をご確認ください。');";
//                          unset($this->arrForm);
                          }
                        
                    } 
                    
            
    }

    public function lfRegist(  $post ){

         $post['creator_id'] = $_SESSION['member_id'];
         
         $this->save($post);  
    }
    
   public function save($sqlval)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $template_id = $sqlval['template_id'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        // 存在確認
        $where = 'template_id = ?';
        $exist = $objQuery->exists('dtb_mailtemplate_batch', $where, array($template_id));
        // 新規登録
        if (!$exist) {
            // INSERTの実行
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            if (!$sqlval['template_id']) {
                $sqlval['template_id'] = $objQuery->nextVal('dtb_mailtemplate_batch_template_id');
            }
            $ret = $objQuery->insert('dtb_mailtemplate_batch', $sqlval);
        // 既存編集
        } else {
            unset($sqlval['creator_id']);
            unset($sqlval['create_date']);
            $ret = $objQuery->update('dtb_mailtemplate_batch', $sqlval, $where, array($template_id));
        }

        return ($ret) ? $sqlval['template_id'] : FALSE;
    }   
    

    

    
    public function lfInitParam($mode, &$objFormParam)
    {
        switch ($mode) {
            case 'regist':
                $objFormParam->addParam('メールタイトル', 'subject', MTEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('ヘッダー', 'body', LTEXT_LEN, 'KVa', array('SPTAB_CHECK','MAX_LENGTH_CHECK'));     
                $objFormParam->addParam('ご要望データ', 'input3', LTEXT_LEN, 'KVa', array('SPTAB_CHECK','MAX_LENGTH_CHECK'));     
                $objFormParam->addParam('差込1入力補助文', 'input1', LTEXT_LEN, 'KVa', array('SPTAB_CHECK','MAX_LENGTH_CHECK'));     
                $objFormParam->addParam('差込2入力補助文', 'input2', LTEXT_LEN, 'KVa', array('SPTAB_CHECK','MAX_LENGTH_CHECK'));     
                $objFormParam->addParam('差込3入力補助文', 'input4', LTEXT_LEN, 'KVa', array('SPTAB_CHECK','MAX_LENGTH_CHECK'));     
                $objFormParam->addParam('差込4入力補助文', 'input5', LTEXT_LEN, 'KVa', array('SPTAB_CHECK','MAX_LENGTH_CHECK'));     
                $objFormParam->addParam('テンプレート', 'template_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
                $objFormParam->addParam('メールタイトル', 'template_type', INT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK', 'MAX_LENGTH_CHECK'));
            case 'id_set':
                $objFormParam->addParam('テンプレート', 'template_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
                $objFormParam->addParam('メールタイトル', 'template_type', INT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK', 'MAX_LENGTH_CHECK'));
                break;
            default:
                break;
        }
    }

}
