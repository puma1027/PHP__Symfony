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

/**
 * 休日を管理するヘルパークラス.
 *
 * @package Helper
 * @author pineray
 * @version $Id$
 */
class SC_Helper_Restday
{
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
        $objQuery->setOrder("year");
        $table = 'dtb_otherday';  
        $arrRet = $objQuery->select($col, $table, $where,array(RESERVE_PATTEN_RESTDAY));            

        return $arrRet;
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

    /**
     * 休日の削除.
     *
     * @param  integer $day_id 休日ID
     * @return void
     */
    public function delete($day_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $objQuery->delete('dtb_otherday', "day_id = ?", array($day_id));
        $objQuery->commit();                 
        
    }





    /**
     * 同じ日付の休日が存在するか確認.
     *
     * @param  integer $month
     * @param  integer $day
     * @param  integer $day_id
     * @return boolean 同日付の休日が存在:true
     */
    public function isDateExist($year, $month, $day, $day_id = NULL)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = 'day_flg = '.RESERVE_PATTEN_RESTDAY.' AND year = ? AND month = ? AND day = ?';
        $arrVal = array($year, $month, $day);
        if (!SC_Utils_Ex::isBlank($day_id)) {
            $where .= ' AND day_id <> ?';
            
            $arrVal[] = $day_id;
        }
        $arrRet = $objQuery->select('day_id, title', 'dtb_otherday', $where, $arrVal);
        return !SC_Utils_Ex::isBlank($arrRet);
    }
}
