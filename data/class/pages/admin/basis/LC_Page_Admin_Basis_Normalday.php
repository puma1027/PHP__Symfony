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
class LC_Page_Admin_Basis_Normalday extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {     
        parent::init();
        $this->tpl_mainpage = 'basis/normalday.tpl';
        $this->tpl_subno = 'normalday';
        $this->tpl_maintitle = '基本情報管理';
        $this->tpl_subtitle = '平日登録';
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
        

        $objDate = new SC_Date_Ex();
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
                $this->arrErr = $this->lfCheckError($objFormParam);
                if (!SC_Utils_Ex::isBlank($this->arrErr['day_id'])) {
                    trigger_error('', E_USER_ERROR);

                    return;
                }

                if (count($this->arrErr) <= 0) {
                    // POST値の引き継ぎ
                    $arrParam = $objFormParam->getHashArray();
                    // 登録実行
                    $nor_day_id = $this->doRegist($day_id, $arrParam);
                    if ($nor_day_id !== FALSE) {
                        // 完了メッセージ
                        $day_id = $nor_day_id;
                        $this->tpl_onload = "alert('登録が完了しました。');";
                    }
                }
                // POSTデータを引き継ぐ
                $this->tpl_day_id = $day_id;

                break;
            // 削除
            case 'delete':
                $this->delete($day_id);
                break;
            // 編集前処理
            case 'pre_edit':
                // 編集項目を取得する。
                $arrdayData = $this->get($day_id);
                $objFormParam->setParam($arrdayData);

                // POSTデータを引き継ぐ
                $this->tpl_day_id = $day_id;
                break;    
            default:
                break;
        }

        $this->arrForm = $objFormParam->getFormParamList();

        $this->arrNormalday = $this->getList();
        
    }
    
    public function delete($day_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $objQuery->delete('dtb_otherday', "day_id = ?", array($day_id));
        $objQuery->commit();                 
        
    }

    /**
     * 登録処理を実行.
     *
     * @param  integer  $holiday_id
     * @param  array    $sqlval
     * @param  object   $objHoliday
     * @return multiple
     */
    public function doRegist($day_id, $sqlval)
    {
        $sqlval['day_id'] = $day_id;
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['day_flg'] = RESERVE_PATTEN_SPECDAY; 

        return $this->save($sqlval);
    }
    
        /**
     * 休日の登録.
     *
     * @param  array    $sqlval
     * @return multiple 登録成功:休日ID, 失敗:FALSE
     */
    public function save($sqlval)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $day_id = $sqlval['day_id'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        // 新規登録
        if ($day_id == '') {
            // INSERTの実行
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $sqlval['day_id'] = $objQuery->nextVal('dtb_otherday_day_id');
            $ret = $objQuery->insert('dtb_otherday', $sqlval);
        // 既存編集
        } else {
            unset($sqlval['creator_id']);
            unset($sqlval['create_date']);
            $where = 'day_id = ?';
            $ret = $objQuery->update('dtb_otherday', $sqlval, $where, array($day_id));
        }

        return ($ret) ? $sqlval['day_id'] : FALSE;
    }

    public function lfInitParam($mode, &$objFormParam)
    {
        switch ($mode) {
            case 'edit':
            case 'pre_edit':
                $objFormParam->addParam('タイトル', 'title', STEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('月', 'month', INT_LEN, 'n', array('SELECT_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('日', 'day', INT_LEN, 'n', array('SELECT_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
                $objFormParam->addParam('平日ID', 'day_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
                break;
            case 'delete':
            case 'down':
            case 'up':
            default:
                $objFormParam->addParam('平日ID', 'day_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
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
    public function lfCheckError(&$objFormParam)
    {
        $arrErr = $objFormParam->checkError();
        $arrForm = $objFormParam->getHashArray();

        // 編集中のレコード以外に同じ日付が存在する場合
        if ($this->isDateExist($arrForm['month'], $arrForm['day'], $arrForm['day_id'])) {
            $arrErr['date'] = '※ 既に同じ日付の登録が存在します。<br>';
        }

        return $arrErr;
    }
    
        /**
     * 同じ日付の休日が存在するか確認.
     *
     * @param  integer $month
     * @param  integer $day
     * @param  integer $day_id
     * @return boolean 同日付の休日が存在:true
     */
    public function isDateExist( $month, $day, $day_id = NULL)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = 'day_flg = '.RESERVE_PATTEN_SPECDAY.' AND month = ? AND day = ?';
        $arrVal = array( $month, $day);
        if (!SC_Utils_Ex::isBlank($day_id)) {
            $where .= ' AND day_id <> ?';
            
            $arrVal[] = $day_id;
        }
        $arrRet = $objQuery->select('day_id, title', 'dtb_otherday', $where, $arrVal);
        return !SC_Utils_Ex::isBlank($arrRet);
    }
    
        /**
     * 休日の情報を取得.
     *
     * @param  integer $day_id  休日ID
     * @param  boolean $has_deleted 削除された休日も含む場合 true; 初期値 false
     * @return array
     */
    public function get($day_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = 'day_id = ?';       
        $arrRet = $objQuery->select('*', 'dtb_otherday', $where, array($day_id));

        return $arrRet[0];
    }
    
        /**
     * 休日一覧の取得.
     *
     * @param  boolean $has_deleted 削除された休日も含む場合 true; 初期値 false
     * @return array
     */
    public function getList()
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = 'day_id, title,year, month, day';
        $where = "day_flg = ?"; 
        $table = 'dtb_otherday'; 
        
        $objQuery->setorder("month"); 
        $arrRet = $objQuery->select($col, $table, $where,array(RESERVE_PATTEN_SPECDAY));            

        return $arrRet;
    }
}
