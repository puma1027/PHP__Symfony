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

require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Confirm.php';
require_once MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Payment.php';
require_once MODULE_REALDIR . 'mdl_remise/class/paycard.php';
require_once MODULE_REALDIR . 'mdl_remise/class/paycvs.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';

/**
 * 入力内容確認 のページクラス(拡張).
 *
 * LC_Page_Shopping_Confirm をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @author REMISE Corp.
 * @version $Id: LC_Page_Shopping_Confirm_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $
 */
class LC_Page_Shopping_Confirm_Ex extends LC_Page_Shopping_Confirm
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
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        parent::destroy();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objHelperMail = new SC_Helper_Mail_Ex();
        $objHelperMail->setPage($this);
        $objCard = new paycard($this->getMode());
        $objCvs = new paycvs($this->getMode());

        $this->is_multiple = $objPurchase->isMultiple();

        // 前のページで正しく登録手続きが行われた記録があるか判定
        if (!$objSiteSess->isPrePage()) {
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, $objSiteSess);
        }
        if ($this->getMode() != 'return') {
            // ユーザユニークIDの取得と購入状態の正当性をチェック
            $this->tpl_uniqid = $objSiteSess->getUniqId();
            $objPurchase->verifyChangeCart($this->tpl_uniqid, $objCartSess);

            $this->cartKey = $objCartSess->getKey();

            // カート内商品のチェック
            $this->tpl_message = $objCartSess->checkProducts($this->cartKey);
            if (!SC_Utils_Ex::isBlank($this->tpl_message)) {
                SC_Response_Ex::sendRedirect(CART_URLPATH);
                SC_Response_Ex::actionExit();
            }

            // カートの商品を取得
            $this->arrShipping = $objPurchase->getShippingTemp($this->is_multiple);
            $this->arrCartItems = $objCartSess->getCartList($this->cartKey);
            // 合計金額
            $this->tpl_total_inctax[$this->cartKey] = $objCartSess->getAllProductsTotal($this->cartKey);
            // 税額
            $this->tpl_total_tax[$this->cartKey] = $objCartSess->getAllProductsTax($this->cartKey);
            // ポイント合計
            $this->tpl_total_point[$this->cartKey] = $objCartSess->getAllProductsPoint($this->cartKey);

            // 一時受注テーブルの読込
            $arrOrderTemp = $objPurchase->getOrderTemp($this->tpl_uniqid);

            // カート集計を元に最終計算
            $arrCalcResults = $objCartSess->calculate($this->cartKey, $objCustomer,
                                                      $arrOrderTemp['use_point'],
                                                      $objPurchase->getShippingPref($this->is_multiple),
                                                      $arrOrderTemp['charge'],
                                                      $arrOrderTemp['discount'],
                                                      $arrOrderTemp['deliv_id']);
            $this->arrForm = array_merge($arrOrderTemp, $arrCalcResults);
        }

        // 会員ログインチェック
        if ($objCustomer->isLoginSuccess(true)) {
            $this->tpl_login = '1';
            $this->tpl_user_point = $objCustomer->getValue('point');
        }

        // 決済モジュールを使用するかどうか
        $this->use_module = $this->useModule($this->arrForm['payment_id']);

        $sql = 'SELECT * FROM dtb_payment WHERE payment_id = ?';
        $arrval = $objQuery->getAll($sql, array($this->arrForm['payment_id']));

        // モバイル用2クリック処理。送信前に受注登録しておく
        if (SC_Display::detectDevice() === DEVICE_TYPE_MOBILE && $_SESSION['twoclick'] === '1' && $this->getMode() != 'return') {
            $this->arrForm['order_id'] = $objQuery->nextval('dtb_order_order_id');
            $_SESSION['order_id'] = $this->arrForm['order_id'];

            // 集計結果を受注一時テーブルに反映
            $objPurchase->saveOrderTemp($this->tpl_uniqid, $this->arrForm, $objCustomer);
            $objPurchase->completeOrder(ORDER_PENDING);
            if ($arrval[0]['memo03'] == PAY_REMISE_CREDIT) {
                $objCard->main();
                $this->arrForm['send_url'] = $objCard->arrForm['send_url']['value'];
                $this->arrSendData = $objCard->arrSendData;
            }
            else if ($arrval[0]['memo03'] == PAY_REMISE_CONVENI) {
                $objCvs->main();
                $this->arrForm['send_url'] = $objCvs->arrForm['send_url']['value'];
                $this->arrSendData = $objCvs->arrSendData;
            }
        }

        // 支払方法セット
        if ($arrval[0]['memo03'] == PAY_REMISE_CREDIT) {
            global $arrCredit;
            $objCard->main();
            $sql = 'SELECT * FROM dtb_payment WHERE payment_id = ?';
            $arrval = $objQuery->getAll($sql, array($this->arrForm['payment_id']));
            if ($this->cartKey == PRODUCT_TYPE_DOWNLOAD || $this->cartKey == PRODUCT_TYPE_AC_REMISE_DL) {
                $this->arrCreMet['value'][REMISE_PAYMENT_METHOD_LUMP] = $arrCredit[REMISE_PAYMENT_METHOD_LUMP];
            } else {
                $this->arrCreMet = $objCard->arrForm['arrCreMet'];
            }
            $_SESSION['remise_credit'] = '1';
        } else {
            unset($_SESSION["remise_credit"]);
        }

        switch ($this->getMode()) {
            // 前のページに戻る
            case 'return':
                // 正常な推移であることを記録しておく
                $objSiteSess->setRegistFlag();

                // モバイル用2クリック処理。登録した受注をキャンセルする
                if (SC_Display::detectDevice() === DEVICE_TYPE_MOBILE && $_SESSION['twoclick'] === '1') {
                    $objSiteSess = new SC_SiteSession_Ex;
                    $objSiteSess->setRegistFlag();
                    $objPurchase = new SC_Helper_Purchase_Ex;
                    $objPurchase->rollbackOrder($_SESSION['order_id']);
                    $objPurchase->registerOrder($_SESSION['order_id'], array('del_flg' => 1));

                    SC_Response_Ex::sendRedirect(CART_URLPATH);
                    exit;
                }
                break;

            case 'confirm':
                // 2クリック時の支払方法入力チェック
                if ($_SESSION['twoclick'] == '1' && $_POST["METHOD"] == "" && $arrval[0]['memo03'] == PAY_REMISE_CREDIT) {
                    $this->arrErr["METHOD"] = "※お支払い方法が入力されていません。<br />";
                    break;
                }

                // 決済モジュールで必要なため, 受注番号を取得
                $this->arrForm['order_id'] = $objQuery->nextval('dtb_order_order_id');
                $_SESSION['order_id'] = $this->arrForm['order_id'];

                // 2クリック利用時、支払方法をセッションに保存
                if ($_SESSION['twoclick'] == '1') {
                    $_SESSION['METHOD'] = $_POST['METHOD'];
                }

                // 集計結果を受注一時テーブルに反映
                $objPurchase->saveOrderTemp($this->tpl_uniqid, $this->arrForm, $objCustomer);

                // 正常に登録されたことを記録しておく
                $objSiteSess->setRegistFlag();

                // 決済モジュールを使用する場合
                if ($this->use_module) {
                    $objPurchase->completeOrder(ORDER_PENDING);

                    SC_Response_Ex::sendRedirect(SHOPPING_MODULE_URLPATH);
                }
                // 購入完了ページ
                else {
                    $objPurchase->completeOrder(ORDER_NEW);
                    $template_id = SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE ? 2 : 1;
                    $objHelperMail->sfSendOrderMail($this->arrForm['order_id'], $template_id);

                    SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                }
                SC_Response_Ex::actionExit();
                break;
            default:
                break;
        }
    }

    /**
     * 決済モジュールを使用するかどうか.
     *
     * dtb_payment.memo03 に値が入っている場合は決済モジュールと見なす.
     *
     * @param integer $payment_id 支払い方法ID
     * @return boolean 決済モジュールを使用する支払い方法の場合 true
     */
    function useModule($payment_id)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $memo03 = $objQuery->get('memo03', 'dtb_payment', 'payment_id = ?', array($payment_id));
        return !SC_Utils_Ex::isBlank($memo03);
    }
}
