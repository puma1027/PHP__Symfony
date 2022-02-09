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

require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_Change.php';

/**
 * 登録内容変更 のページクラス(拡張).
 *
 * LC_Page_Mypage_Change をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_Change_Ex extends LC_Page_Mypage_Change
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        // 20200519 ishibashi
//        $masterData = new SC_DB_MasterData_Ex();
//        $this->arrReminder = $masterData->getMasterData("mtb_reminder");
//        $this->arrPref = $masterData->getMasterData("mtb_pref");//, array("pref_id", "pref_name", "rank")
//        $this->arrJob = $masterData->getMasterData("mtb_job");
//        $this->arrMAILMAGATYPE = $masterData->getMasterData("mtb_mail_magazine_type");
//        $this->arrSex = $masterData->getMasterData("mtb_sex");
//        $this->allowClientCache();
//        $this->arrVisitRouteName = array("日テレ1分間の深イイ話から","NHK東京カワイイTVから","「結婚式の服装マナー」のページから","「結婚式二次会のマナー」のページから","「マタニティドレス」のページから","「レンタルドレス」で検索したら出てきた","知人・友人の紹介");

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
