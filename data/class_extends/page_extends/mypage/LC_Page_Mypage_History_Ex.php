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

require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_History.php';

/**
 * 購入履歴 のページクラス(拡張).
 *
 * LC_Page_Mypage_History をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_History_Ex extends LC_Page_Mypage_History
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        // 20200520 ishibashi 
        // ======= RCHJ remark and add 2013.06.12 =====
        //$this->tpl_mainpage = TEMPLATE_REALDIR .'mypage/history.tpl';
        $this->tpl_mainpage =  'mypage/history.tpl';

        //$this->tpl_navi = TEMPLATE_REALDIR . 'mypage/navi.tpl';
        $this->tpl_navi = 'mypage/navi.tpl';

//        $this->tpl_subtitle = "注文内容の確認・変更";
        // ================ End ============
        $this->tpl_title = "MYページ/注文内容の確認・変更";
        $this->tpl_column_num = 1;
        $this->tpl_mainno = 'mypage';
//        $this->tpl_mypageno = 'index';

		//$this->allowClientCache();

        // 2012.05.29 RCHJ Add
        $this->arr_status = array(
        		1=>"注文済み",
        		2=>"返却確認中",
        		3=>"発送済み",
        		4=>"返却済み",
        		5=>"返却不良",
        		6=>"予約取り消し",
        		8=>"キャンセル",
        );
        $this->arr_honshu = Array
		(
		    2 => "青森県",
		    3 => "岩手県",
		    4 => "宮城県",
		    5 => "秋田県",
		    6 => "山形県",
		    7 => "福島県",
		    8 => "茨城県",
		    9 => "栃木県",
		    10 => "群馬県",
		    11 => "埼玉県",
		    12 => "千葉県",
		    13 => "東京都",
		    14 => "神奈川県",
		    15 => "新潟県",
		    16 => "富山県",
		    17 => "石川県",
		    18 => "福井県",
		    19 => "山梨県",
		    20 => "長野県",
		    21 => "岐阜県",
		    22 => "静岡県",
		    23 => "愛知県",
            24 => "三重県",
            25 => "滋賀県",
            26 => "京都府",
            27 => "大阪府",
            28 => "兵庫県",
            29 => "奈良県",
            30 => "和歌山県",
            31 => "鳥取県",
            32 => "島根県",
            33 => "岡山県",
            34 => "広島県",
            35 => "山口県",
        );
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }
}
