<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
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

require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Payment.php';
require_once MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';

/**
 * 支払い方法選択 のページクラス(拡張).
 *
 * LC_Page_Shopping_Payment をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id$
 */
class LC_Page_Shopping_Payment_Ex extends LC_Page_Shopping_Payment
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

    /**
     * 入力内容のチェックを行なう.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param  integer      $subtotal     購入金額の小計
     * @param  integer      $max_point    会員の保持ポイント
     * @return array        入力チェック結果の配列
     */
    public function lfCheckError(&$objFormParam, $subtotal, $max_point)
    {
        $arrErr = parent::lfCheckError($objFormParam, $subtotal, $max_point);

        // 既に支払方法でエラーが設定されている場合は、そのエラーを返却
        if (!empty($arrErr)) {
            if (isset($arrErr['payment_id']) && !empty($arrErr["payment_id"])) return $arrErr;
        }

        // モバイル判定
        if (SC_Display::detectDevice() != DEVICE_TYPE_MOBILE) return $arrErr;

        // トークン決済判定
        $objConfig = new LC_Page_Mdl_Remise_Config();
        $arrConfig = $objConfig->getConfig();
        if (empty($arrConfig)) return $arrErr;
        if (!isset($arrConfig["token_sdk"]) || empty($arrConfig["token_sdk"])) return $arrErr;

        // カード決済判定
        $arrPayment = $objConfig->getPaymentDB(PAY_REMISE_CREDIT);
        if (empty($arrPayment)) return $arrErr;
        if (!isset($arrPayment[0]["payment_id"]) || empty($arrPayment[0]["payment_id"])) return $arrErr;
        if ($objFormParam->getValue('payment_id') != $arrPayment[0]["payment_id"]) return $arrErr;

        $arrErr['payment_id'] = "ご利用の端末では" . $arrPayment[0]["payment_method"] . "をご利用いただけません。<br>";
        return $arrErr;
    }
}
