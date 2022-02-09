<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2013 REMISE Corp. All Rights Reserved.
 *
 * http://www.remise.jp/
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

require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/twoclickinfo.php';

/**
 * かんたん決済ヘルプ のページクラス.
 *
 * @package Page
 * @author REMISE Corp.
 * @version LC_Page_Remise_TwoClick_Help v 2.2
 */
class LC_Page_Remise_TwoClick_Help extends LC_Page_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        $this->skip_load_page_layout = true;
        parent::init();
        $this->tpl_title = 'かんたん決済ヘルプ';
        $this->tpl_column_num = 1;
        $this->arrPageLayout['header_chk'] = 2;
        $this->arrPageLayout['footer_chk'] = 2;

        $this->tpl_mainno = '';
        $this->httpCacheControl('nocache');
        switch (SC_Display::detectDevice()) {
            case DEVICE_TYPE_MOBILE:
                $this->tpl_mainpage = MDL_REMISE_TEMPLATE_PATH . '/help_mbl.tpl';
                break;
            case DEVICE_TYPE_SMARTPHONE:
                $this->tpl_mainpage = MDL_REMISE_TEMPLATE_PATH . '/help_smp.tpl';
                break;
            default:
                $this->tpl_mainpage = MDL_REMISE_TEMPLATE_PATH . '/help.tpl';
                break;
        }
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
        global $arrConvenience;
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $payment_remise_sql = 'SELECT * FROM dtb_payment WHERE memo03 = ?';
        $arrPaymentCredit = $objQuery->getAll($payment_remise_sql, array(PAY_REMISE_CREDIT));
        $arrPaymentConveni = $objQuery->getAll($payment_remise_sql, array(PAY_REMISE_CONVENI));

        $this->creditname = $arrPaymentCredit[0]["payment_method"];
        if ($arrPaymentConveni[0]["memo05"] != "") {
            $this->conveniname = "及び" . $arrPaymentConveni[0]["payment_method"];
        }

        $this->twoclickinfo = "　前回のご注文のお届け先・配送方法・支払方法を利用して、"
. "2回の操作で決済を行うことができる機能です。<br />"
. $this->creditname . $this->conveniname . "でのお支払いにてご利用いただけます。<br />"
. $this->creditname . "でのお支払いの場合には、カード情報の登録が必要です。";
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        //parent::destroy();
    }
}
