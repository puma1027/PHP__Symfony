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
 * 定休日管理のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Basis_Restday extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'basis/restday.tpl';
        $this->tpl_subno = 'restday';
        $this->tpl_maintitle = '基本情報管理';
        $this->tpl_subtitle = '休業日登録';
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
        $objday = new SC_Helper_Restday_Ex();

        $objDate = new SC_Date_Ex();
        $this->arrYear = $objDate->getYear();
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();
         
        $mode = $this->getMode();

        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($mode, $objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $day_id = $objFormParam->getValue('day_id');
         
        // 要求判定
        switch ($mode) {
            // 編集処理
            case 'edit':
                $this->arrErr = $this->lfCheckError($objFormParam, $objday);
                if (!SC_Utils_Ex::isBlank($this->arrErr['day_id'])) {
                    trigger_error('', E_USER_ERROR);

                    return;
                }

                if (count($this->arrErr) <= 0) {
                    // POST値の引き継ぎ
                    $arrParam = $objFormParam->getHashArray();
                    // 登録実行
                    $res_day_id = $this->doRegist($day_id, $arrParam, $objday);
                    if ($res_day_id !== FALSE) {
                        // 完了メッセージ
                        $day_id = $res_day_id;
                        $this->tpl_onload = "alert('登録が完了しました。');";
                    }
                }
                // POSTデータを引き継ぐ
                $this->tpl_day_id = $day_id;

                break;
            // 削除
            case 'delete':
                $objday->delete($day_id);
                break;
            // 編集前処理
            case 'pre_edit':
                // 編集項目を取得する。
                
                $arrdayData = $objday->get($day_id);
                $objFormParam->setParam($arrdayData);

                // POSTデータを引き継ぐ
                $this->tpl_day_id = $day_id;
                break;
            default:
                break;
        }
         
        $this->arrForm = $objFormParam->getFormParamList();
         
        $this->arrRestday = $objday->getList();
    }

    /**
     * 登録処理を実行.
     *
     * @param  integer  $day_id
     * @param  array    $sqlval
     * @param  object   $objday
     * @return multiple
     */
    public function doRegist($day_id, $sqlval, SC_Helper_Restday_Ex $objday)
    {
        $sqlval['day_id'] = $day_id;
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['day_flg'] = RESERVE_PATTEN_RESTDAY;        

        return $objday->save($sqlval);
    }

    public function lfInitParam($mode, &$objFormParam)
    {
        switch ($mode) {
            case 'edit':
            case 'pre_edit':
                $objFormParam->addParam('タイトル', 'title', STEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('年', 'year', INT_LEN, 'n', array('SELECT_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('月', 'month', INT_LEN, 'n', array('SELECT_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('日', 'day', INT_LEN, 'n', array('SELECT_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('休業日ID', 'day_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
                break;
            case 'delete':     
            default:
                $objFormParam->addParam('休業日ID', 'day_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
                break;
        }
    }

    /**
     * 入力エラーチェック
     *
     * @param  object $objFormParam
     * @param  object $objHoliday
     * @return array
     */
    public function lfCheckError(&$objFormParam, SC_Helper_Restday_Ex &$objday)
    {
        $arrErr = $objFormParam->checkError();
        $arrForm = $objFormParam->getHashArray();
         
        // 編集中のレコード以外に同じ日付が存在する場合
        if ($objday->isDateExist($arrForm['year'], $arrForm['month'], $arrForm['day'], $arrForm['day_id'])) { 
            $arrErr['date'] = '※ 既に同じ日付の登録が存在します。<br>';
        }
        return $arrErr;
    }
}
