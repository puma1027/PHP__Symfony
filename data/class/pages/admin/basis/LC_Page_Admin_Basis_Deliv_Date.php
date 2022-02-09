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
 * 配送方法設定 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Basis_Deliv_Date extends LC_Page_Admin_Ex
{         
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        
        parent::init();  
                                                            
        $this->tpl_maintitle = '基本情報管理';
//        die("bbbbbbbbbb");        
        $this->tpl_mainpage = 'basis/deliv_date.tpl';  
        $this->tpl_subno = 'deliv_date';
        $this->tpl_subtitle = 'お届け日管理';
        $this->tpl_mainno = 'basis';
        
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
            

            
            $objFormParam->setParam($_POST);
            $objFormParam->convParam();  
            $this->arrErr = $objFormParam->checkError();
            $post = $objFormParam->getHashArray();
        } 
           
         switch($_POST['mode']) {
        // 更新処理
            case 'update':
            {         
                  if(count($this->arrErr) <= 0) {
                   // 更新       
                    $this->lfUpdateClass(); 
                    $this->tpl_onload = "window.alert('更新処理が完了しました');";
                   } else {          
                   }
                  break;
            }
            default:
                break;
        }
        
        // お届け日の取得   
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '*';                                                 
        $objQuery->setOrder("id asc");          
        $this->arrDelivDate = $objQuery->select($col, 'dtb_delivdate_ext');
 
    }
    
    public function lfUpdateClass() {   
        // 更新データの作成
        $arrData = $this->lfMakeUpdateData();
         
        // UPDATEの実行
        $result = $this->lfExecUpdate($arrData);
        return $result;
    }
    
    public function lfMakeUpdateData($array) {
        foreach ($_POST as $key => $val) {
            if (preg_match("/^update_data_/", $key)) {
                // _(アンダースコア)で区切ってある
                $update_list[] = explode("_" , $val);
            }
        }   
        return $update_list;
    }
    
    public function lfExecUpdate($array) { 
        
        $return_val = true;
        $table = "dtb_delivdate_ext";
        $update_list = $array;
        $where = "id = ?";   
        foreach ($update_list as $val) {      
            // 更新データ
            $sqlVal = array();
            // ご利用日
            $sqlVal['use_day'] = $val[1];
            $sqlVal['deliv_day_of_the_week'] = $val[2];
            $sqlVal['rank'] = $val[3];
            $sqlVal['status'] = $val[4];                      
            $objQuery = new SC_Query();   
            $objQuery->begin();        
            $result = $objQuery->update($table,$sqlVal,$where,array($val[0]));  
            if (!$result) {
                $return_val = $result;
            }
            $objQuery->commit();
        }
        return $return_val;
    }
    


}
