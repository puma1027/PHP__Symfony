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
class LC_Page_Admin_Basis_Sp_Template extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {                                                     
        parent::init();
        $this->tpl_mainpage = 'basis/sp_template.tpl';  
        $this->tpl_mainno = 'basis';
        $this->tpl_subno = 'mail2';         
        $this->tpl_subtitle = 'スマホメール定型文設定';
        $this->arrMailSTEMPLATE = null;
        $this->tpl_maintitle = '基本情報管理';
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
//        $masterData = new SC_DB_MasterData_Ex();             

        $mode = $this->getMode();

        if (!empty($_POST)) {
            $objFormParam = new SC_FormParam_Ex();
            $this->lfInitParam($mode, $objFormParam);
            $objFormParam->setParam($_POST);
            $objFormParam->convParam();

//            $this->arrErr = $objFormParam->checkError();
            $post = $objFormParam->getHashArray();
        }
                                                                              
        
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '*';
        
        $objQuery->setOrder('template_id asc');
                  
        $result = $objQuery->select($col, 'dtb_spmail_template');
        
        $arrdata = array();
           
        for ($cnt = 0; $cnt < count($result); $cnt++) {

            $key = $result[$cnt]["template_id"];
            $value = $result[$cnt]["subject"];

            $arrdata[$key] = $value;
        }
        $this->arrMailSTEMPLATE =$arrdata;

        switch ($mode) {
            case 'id_set':
                {       
                if ( SC_Utils_Ex::sfIsInt( $_POST['template_id']) ){                                                         
                        $objQuery = SC_Query_Ex::getSingletonInstance();
                        $col = '*';
                        $where = 'template_id = ?'; 
                  
                        $result = $objQuery->select($col, 'dtb_spmail_template', $where, array($_POST['template_id']));
                        if ( $result ){
                          $this->arrForm = $result[0];
                           $this->arrForm["body"] = $result[0]["body"];
                        } else {
                            $this->arrForm['template_id'] = $_POST['template_id'];
                        }
                  }
                             
                break;
                }
        
            case 'regist':
            {                                                     
                 $this->arrForm = $post; 
                 $this->arrErr = $this->fnErrorCheck($post); 

				 
                if ( $this->arrErr ){   
                    // エラーメッセージ
                    $this->tpl_msg = "エラーが発生しました";

                } else {
                    // 正常                                      
                    $this->lfRegist($this->arrForm);
                    
                    $this->tpl_onload = "window.alert('メール設定が完了しました。テンプレートを選択して内容をご確認ください。');";

                    unset($this->arrForm);

                    header("Location:sp_template.php");
                    exit();
                }   
                break;
            }
            default:
                break;
        }

    }

   public function lfRegist(  $post ){         
         $this->save($post);  
    }
    
   public function save($sqlval)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $template_id = $sqlval['template_id'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        // 存在確認
        $where = 'template_id = ?';
        $exist = $objQuery->exists('dtb_spmail_template', $where, array($template_id));
        
        // 新規登録
        if (!$exist) {
            // INSERTの実行
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            if (!$sqlval['template_id']) {
                $sqlval['template_id'] = $objQuery->nextVal('dtb_spmail_template_template_id');
            }
            $ret = $objQuery->insert('dtb_spmail_template', $sqlval);
        // 既存編集
        } else {                           
            unset($sqlval['create_date']);
            
            $ret = $objQuery->update('dtb_spmail_template', $sqlval, $where, array($template_id));
        }

        return ($ret) ? $sqlval['template_id'] : FALSE;
    }   

    public function lfInitParam($mode, &$objFormParam)
    {
        switch ($mode) {
            case 'regist':
                $objFormParam->addParam('メールタイトル', 'subject', MTEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('ヘッダー', 'body', LTEXT_LEN, 'KVa', array('SPTAB_CHECK','MAX_LENGTH_CHECK'));     
                $objFormParam->addParam('メールタイトル', 'subject', MTEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('テンプレート', 'template_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
            case 'id_set':
                $objFormParam->addParam('テンプレート', 'template_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
                break;
            default:
                break;
        }
    }
    /* 入力エラーのチェック */
    function fnErrorCheck($array) {

        $objErr = new SC_CheckError($array);

        //$objErr->doFunc(array("テンプレート",'template_id'), array("EXIST_CHECK","NUM_CHECK"));
        
        $objErr->doFunc(array("メールタイトル",'subject',MTEXT_LEN,"BIG"), array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("メール本体",'body',LLTEXT_LEN,"BIG"), array("MAX_LENGTH_CHECK"));

        return $objErr->arrErr;
    }
}
