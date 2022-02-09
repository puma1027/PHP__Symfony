<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';

/**
 * 購入履歴 のページクラス(拡張).
 *
 * LC_Page_Mypage_History をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @author REMISE Corp.
 * @version $Id: LC_Page_Mypage_History_Ex.php 21420 2012-01-22 19:49:37Z Seasoft $
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
        unset($_SESSION['mode']);
        if (isset($_SESSION['remise_ac_update'])) {
            $this->tpl_onload = 'alert("カード更新手続きが正常に完了しました。");';
        }
        else if (isset($_SESSION['remise_ac_refusal'])) {
            $this->tpl_onload = 'alert("退会手続きは正常に完了しました。");';
        }
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
        unset($_SESSION['remise_ac_update']);
        unset($_SESSION['remise_ac_refusal']);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        parent::destroy();
    }
}
