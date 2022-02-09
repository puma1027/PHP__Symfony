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
class LC_Page_Admin_Basis_Control extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {                                               

        parent::init();      
        $this->tpl_mainpage = 'basis/control.tpl';
        $this->tpl_mainno = 'basis';
        $this->tpl_subno = 'control';
        $this->tpl_maintitle = '基本情報管理';
        $this->tpl_subtitle = 'サイト管理設定';
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
            $this->lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);
            $objFormParam->convParam();

            $this->arrErr = $objFormParam->checkError();
            $post = $objFormParam->getHashArray();
        }
                                                                                  

        switch ($mode) {    
            case 'edit': 
                    $this->arrForm = $post;   
                    if ($this->arrErr) {
                     //    エラーメッセージ
                        $this->tpl_msg = 'エラーが発生しました';
                    } else {
                     //    正常   
                        $this->lfSiteControlData($_POST['control_id'],$post);  
                       //      javascript実行
                        $this->tpl_onload = "alert('更新が完了しました。');";
                       
//                        unset($this->arrForm);
                    }
                break;
            default:
             
                break;
        }
//         die("dff");
        // サイト管理情報の取得
        $arrSiteControlList = $this->lfGetControlList();
        $masterData = new SC_DB_MasterData_Ex();
        
        // プルダウンの作成
        for ($i = 0; $i < count($arrSiteControlList); $i++) {
            switch ($arrSiteControlList[$i]["control_id"]) {
                // トラックバック
                case SITE_CONTROL_TRACKBACK:
                    $arrSiteControlList[$i]["control_area"]
                            = $masterData->getMasterData("mtb_site_control_track_back");
                    break;
                // アフィリエイト
                case SITE_CONTROL_AFFILIATE:
                    $arrSiteControlList[$i]["control_area"]
                            = $masterData->getMasterData("mtb_site_control_affiliate");
                    break;
                default:
                    break;
            }
        }

        $this->arrControlList = $arrSiteControlList;

    }
       /* DBへデータを登録する */
    public function lfSiteControlData($control_id = "",$sqlval) {
        $objQuery = SC_Query_Ex::getSingletonInstance();                    
        $sqlval['update_date'] = 'Now()';
//         die("ff");   
        // 新規登録
        if($control_id == "") {
            // INSERTの実行                                     
            $sqlval['create_date'] = 'Now()';      
            $objQuery->insert("dtb_site_control", $sqlval);
        // 既存編集
        } else {
            $where = "control_id = ?";                         
            $objQuery->update("dtb_site_control", $sqlval, $where, array($control_id));
        }
    }
    
    // サイト管理情報の取得
    public function lfGetControlList() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // サイト管理情報の取得
        
        $arrRet = $objQuery->select("*","dtb_site_control","del_flg = 0");
        return $arrRet;
    }

    public function lfInitParam( &$objFormParam)
    {
        $objFormParam->addParam("設定状況", "control_flg", INT_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
    }
}
