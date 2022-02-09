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

require_once CLASS_REALDIR . 'pages/contact/LC_Page_Contact_Complete.php';

/**
 * お問い合わせ(完了ページ) のページクラス(拡張).
 *
 * LC_Page_Contact_Complete をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Contact_Complete_Ex extends LC_Page_Contact_Complete
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        /* 20200514 sg nakagawa 旧バージョンの処理 */
        // 開始時刻を設定する。
        $this->timeStart = microtime(true);

        $this->tpl_authority = $_SESSION['authority'];

        // ディスプレイクラス生成
        $this->objDisplay = new SC_Display_Ex();

        $this->tpl_force_device = $this->objDisplay->prepareProcess();
        if (!$this->skip_load_page_layout) {
            $layout = new SC_Helper_PageLayout_Ex();

            $layout->sfGetPageLayout($this, false, DEF_LAYOUT,
                    $this->objDisplay->detectDevice());
        }

        // スーパーフックポイントを実行.
        $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
        $objPlugin->doAction('LC_Page_preProcess', array($this));

        // 店舗基本情報取得
        $this->arrSiteInfo = SC_Helper_DB_Ex::sfGetBasisData();


        // トランザクショントークンの検証と生成
        // $this->doValidToken();
        //$this->setTokenTo();

        // ローカルフックポイントを実行.
        $this->doLocalHookpointBefore($objPlugin);

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
