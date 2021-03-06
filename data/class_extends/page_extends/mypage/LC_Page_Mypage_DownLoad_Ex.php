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

require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_DownLoad.php';

/**
 * ダウンロード商品ダウンロード のページクラス(拡張).
 *
 * LC_Page_Mypage_Download をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id: LC_Page_Mypage_Download_Ex.php $
 */
class LC_Page_Mypage_Download_Ex extends LC_Page_Mypage_Download
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
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

    // 20200520 ishibashi 独自の判断
    /**
     *
     * PageのResponse.
     * todo たいした処理ではないのに異常に処理が重い
     * @return void
     */
     function sendResponse()
     {
        parent::sendResponse();
        $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
     }
}
