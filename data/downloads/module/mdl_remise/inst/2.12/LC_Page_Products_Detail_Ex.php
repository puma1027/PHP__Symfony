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

require_once CLASS_REALDIR . 'pages/products/LC_Page_Products_Detail.php';
require_once CLASS_EX_REALDIR . 'page_extends/cart/LC_Page_Cart_Ex.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/twoclickinfo.php';

/**
 * LC_Page_Products_Detail のページクラス(拡張).
 *
 * LC_Page_Products_Detail をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @author REMISE Corp.
 * @version $Id: LC_Page_Products_Detail_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $
 */
class LC_Page_Products_Detail_Ex extends LC_Page_Products_Detail
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
     * Page のAction.
     *
     * @return void
     */
    function action()
    {
        // 会員クラス
        $objCustomer = new SC_Customer_Ex();

        // パラメーター管理クラス
        $this->objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->arrForm = $this->lfInitParam($this->objFormParam);
        // ファイル管理クラス
        $this->objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        // ファイル情報の初期化
        $this->objUpFile = $this->lfInitFile($this->objUpFile);
        // プロダクトIDの正当性チェック
        $product_id = $this->lfCheckProductId($this->objFormParam->getValue('admin'), $this->objFormParam->getValue('product_id'));
        $this->mode = $this->getMode();
        $objProduct = new SC_Product_Ex();
        $objProduct->setProductsClassByProductIds(array($product_id));

        // 規格1クラス名
        $this->tpl_class_name1 = $objProduct->className1[$product_id];

        // 規格2クラス名
        $this->tpl_class_name2 = $objProduct->className2[$product_id];

        // 規格1
        $this->arrClassCat1 = $objProduct->classCats1[$product_id];

        // 規格1が設定されている
        $this->tpl_classcat_find1 = $objProduct->classCat1_find[$product_id];
        // 規格2が設定されている
        $this->tpl_classcat_find2 = $objProduct->classCat2_find[$product_id];

        $this->tpl_stock_find = $objProduct->stock_find[$product_id];
        $this->tpl_product_class_id = $objProduct->classCategories[$product_id]['__unselected']['__unselected']['product_class_id'];
        $this->tpl_product_type = $objProduct->classCategories[$product_id]['__unselected']['__unselected']['product_type'];

        // 在庫が無い場合は、OnLoadしない。(javascriptエラー防止)
        if ($this->tpl_stock_find) {
            // 規格選択セレクトボックスの作成
            $this->js_lnOnload .= $this->lfMakeSelect();
        }

        $this->tpl_javascript .= 'classCategories = ' . SC_Utils_Ex::jsonEncode($objProduct->classCategories[$product_id]) . ';';
        $this->tpl_javascript .= 'function lnOnLoad() {' . $this->js_lnOnload . '}';
        $this->tpl_onload .= 'lnOnLoad();';

        // モバイル用 規格選択セレクトボックスの作成
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            $this->lfMakeSelectMobile($this, $product_id, $this->objFormParam->getValue('classcategory_id1'));
        }

        // 商品IDをFORM内に保持する
        $this->tpl_product_id = $product_id;

        // 2クリック関連文言
        $this->twoclick_alert = "";
        $this->twoclickexplain = '※「かんたん決済」を押すと、前回と同じ内容でご注文可能です。<br />
※カゴの中身を編集する場合は「カゴに入れる」ボタンを押して、商品を追加してください。';

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objCartSess = new SC_CartSession_Ex();
        $this->cartKeys = $objCartSess->getKeys();
        $other_producttype = '';
        foreach ($this->cartKeys as $key) {
            // 別の種別の商品がカゴに入っている場合、かんたん決済不可
            if ($key != $this->tpl_product_type) {
                $other_producttype = '1';
                $this->twoclick_alert .= $this->fixMessage(ERR_OTHER_PRODUCTTYPE);
            }
        }

        // 定期購買商品はかんたん決済不可
        if ($this->tpl_product_type == PRODUCT_TYPE_AC_REMISE || $this->tpl_product_type == PRODUCT_TYPE_AC_REMISE_DL) {
            $ac_product = '1';
            $this->twoclick_alert .= $this->fixMessage(ERR_AC_PRODUCT);
        } else {
            $ac_product = "";
        }

        $objCart = new LC_Page_Cart_Ex();
        // 最近の受注がない場合も、かんたん決済不可
        $customer_id    = $objCustomer->getValue('customer_id');
        $arrResentOrder = $this->getMaxOrder($customer_id);
        if (empty($arrResentOrder)) {
            $order_empty = '1';
            $this->twoclick_alert .= $this->fixMessage(ERR_ORDER_EMPTY);
        } else {
            $order_empty = "";
        }

        // 前回購入の商品種別と異なる商品なら、かんたん決済不可
        $before_prducttype_id = $this->getProducts_Type_id($arrResentOrder["order_id"]);
        if ($this->tpl_product_type != $before_prducttype_id['product_type_id']) {
            if ($this->tpl_product_type == 1) {
                $this->twoclick_alert .= $this->fixMessage('※通常商品は' . ERR_PRODUCTTYPE_DIFF);
                $producttype_diff = '1';
            }
            else if ($this->tpl_product_type == 2) {
                $this->twoclick_alert .= $this->fixMessage('※ダウンロード商品は' . ERR_PRODUCTTYPE_DIFF);
                $producttype_diff = '1';
            }
            else {
                $this->twoclick_alert .= $this->fixMessage('※この商品は' . ERR_PRODUCTTYPE_DIFF);
                $producttype_diff = '1';
            }
        } else {
            $producttype_diff = "";
        }

        // 配送業者情報がない場合、かんたん決済不可
        $deliv_sql = 'SELECT * FROM dtb_deliv WHERE deliv_id = ? AND del_flg = ?';
        $arrdeliv = $objQuery->getAll($deliv_sql, array($arrResentOrder['deliv_id'], '0'));
        if (empty($arrdeliv)) {
            $deliv_lost = '1';
            $this->twoclick_alert .= $this->fixMessage(ERR_DELIV_LOST);
        } else {
            $deliv_lost = "";
        }

        // 配送先情報が複数ある場合、かんたん決済不可
        $shipping_sql = 'SELECT * FROM dtb_shipping WHERE order_id = ? AND del_flg = ?';
        $arrshipping = $objQuery->getAll($shipping_sql, array($arrResentOrder['order_id'], '0'));
        if (count($arrshipping) > 1) {
            $multi_shipping = '1';
            $this->twoclick_alert .= $this->fixMessage(ERR_MULTI_SHIPPING);
        } else {
            $multi_shipping = "";
        }

        // デフォルトの支払方法はかんたん決済不可
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $sql = 'SELECT * FROM dtb_payment WHERE payment_id = ?';
        $arrPayment = $objQuery->getAll($sql, array($arrResentOrder['payment_id']));
        if ($arrPayment[0]['memo03'] == PAY_REMISE_CREDIT || $arrPayment[0]['memo03'] == PAY_REMISE_CONVENI) {
            $remise_payment_disable = "";
        } else {
            $remise_payment_disable = '1';
            $this->twoclick_alert .= $this->fixMessage(ERR_NOT_PAYREMISE);
        }

        // ゲートウェイ接続利用の場合も、かんたん決済不可
        $payment_sql = 'SELECT * FROM dtb_payment WHERE memo03 = ? AND module_code = ?';
        $arrRemisePayment = $objQuery->getAll($payment_sql, array(PAY_REMISE_CREDIT, 'mdl_remise'));
        if ($arrRemisePayment[0]['connect_type'] == REMISE_CONNECT_TYPE_GATEWAY) {
            $gateway = '1';
            $this->twoclick_alert .= $this->fixMessage(ERR_CTYPE_GATEWAY);
        } else {
            $gateway = "";
        }

        // カード決済の場合、ペイクイックが有効でない場合は、かんたん決済不可

        if ($arrPayment[0]['memo03'] == PAY_REMISE_CREDIT) {
            if ($arrRemisePayment[0]['payquick'] != REMISE_OPTION_USE) {
                $payquick_disable = '1';
                $this->twoclick_alert .= $this->fixMessage(ERR_CANNOT_PAYQUICK);
            } else {
                $payquick_disable = "";
            }

            $customer_sql = 'SELECT * FROM dtb_customer WHERE customer_id = ?';
            $arrCustomer = $objQuery->getAll($customer_sql, array($customer_id));
            $now = date("y"). date("m");
            $expire = substr($arrCustomer[0]["expire"], 2, 2).substr($arrCustomer[0]["expire"], 0, 2);

            // カード決済の場合、カードが登録されていなければかんたん決済不可
            if (($arrCustomer[0]['payquick_id'] == "" || $arrCustomer[0]['card'] == "" || $arrCustomer[0]['expire'] == "") &&
                $arrPayment[0]['memo03'] == PAY_REMISE_CREDIT) {
                $payquick_card_notfound = '1';
                $this->twoclick_alert .= $this->fixMessage(ERR_CARD_NOTFOUND);
            } else {
                $payquick_card_notfound = "";
                unset($_SESSION['disable_by_card_notfound']);
                // カード決済の場合、カードが登録されていても、有効期限が切れていればかんたん決済不可
                if (isset($arrCustomer[0]["expire"]) && isset($arrCustomer[0]["payquick_id"]) && $now <= $expire) {
                    $payquick_card_expired = '';
                } else {
                    $payquick_card_expired = '1';
                    $this->twoclick_alert .= $this->fixMessage(ERR_CARD_EXPIRE);
                }
            }
        }

        // 2クリック関連文言
        if ($this->twoclick_alert != "") {
            unset($_SESSION['remise_twoclick_enable']);
        } else {
            $_SESSION['remise_twoclick_enable'] = '1';
        }

        // GW接続及びペイクイック無効時は、ログインリンクも出さない
        if ($gateway || $payquick_disable) {
            $_SESSION['remise_twoclick_hidden'] = '1';
        } else {
            unset($_SESSION['remise_twoclick_hidden']);
        }

        // バージョン間動作差異解決のため
        if (ECCUBE_VERSION > '2.12.1') {
            switch ($this->mode) {
                case 'cart':
                    $this->arrErr = $this->lfCheckError($this->mode, $this->objFormParam,
                                                        $this->tpl_classcat_find1,
                                                        $this->tpl_classcat_find2);
                    if (count($this->arrErr) == 0) {
                        $objCartSess = new SC_CartSession_Ex();
                        $product_class_id = $this->objFormParam->getValue('product_class_id');

                        $objCartSess->addProduct($product_class_id, $this->objFormParam->getValue('quantity'));

                        SC_Response_Ex::sendRedirect(CART_URLPATH);
                        SC_Response_Ex::actionExit();
                    }
                    break;

                case 'add_favorite':
                    $this->doAddFavorite($objCustomer);
                    break;

                case 'add_favorite_sphone':
                    $this->doAddFavoriteSphone($objCustomer);
                    break;

                case 'remisetwoclick':
                    $this->doCart_twoclick();
                    $this->Remiseaction();
                    break;

                case 'select':
                case 'select2':
                case 'selectItem':
                    /**
                     * モバイルの数量指定・規格選択の際に、
                     * $_SESSION['cart_referer_url'] を上書きさせないために、
                     * 何もせずbreakする。
                     */
                    break;

                default:
                    $this->doDefault();
                    break;
            }

            // モバイル用 ポストバック処理
            if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
                switch ($this->mode) {
                    case 'select':
                        $this->doMobileSelect();
                        break;

                    case 'select2':
                        $this->doMobileSelect2();
                        break;

                    case 'selectItem':
                        $this->doMobileSelectItem();
                        break;

                    case 'cart':
                        $this->doMobileCart();
                        break;

                    default:
                        $this->doMobileDefault();
                        break;
                }
            }

        } else {
            switch ($this->mode) {
                case 'cart':
                    $this->arrErr = $this->lfCheckError($this->mode, $this->objFormParam,
                                                        $this->tpl_classcat_find1,
                                                        $this->tpl_classcat_find2);
                    if (count($this->arrErr) == 0) {
                        $objCartSess = new SC_CartSession_Ex();
                        $product_class_id = $this->objFormParam->getValue('product_class_id');

                        $objCartSess->addProduct($product_class_id, $this->objFormParam->getValue('quantity'));

                        SC_Response_Ex::sendRedirect(CART_URLPATH);
                        SC_Response_Ex::actionExit();
                    }
                    break;

                case 'add_favorite':
                    // ログイン中のユーザが商品をお気に入りにいれる処理
                    if ($objCustomer->isLoginSuccess() === true && $this->objFormParam->getValue('favorite_product_id') > 0) {
                        $this->arrErr = $this->lfCheckError($this->mode, $this->objFormParam);
                        if (count($this->arrErr) == 0) {
                            if (!$this->lfRegistFavoriteProduct($this->objFormParam->getValue('favorite_product_id'), $objCustomer->getValue('customer_id'))) {
                                $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
                                $objPlugin->doAction('LC_Page_Products_Detail_action_add_favorite', array($this));

                                SC_Response_Ex::actionExit();
                            }
                        }
                    }
                    break;

                case 'add_favorite_sphone':
                    // ログイン中のユーザが商品をお気に入りにいれる処理(スマートフォン用)
                    if ($objCustomer->isLoginSuccess() === true && $this->objFormParam->getValue('favorite_product_id') > 0) {
                        $this->arrErr = $this->lfCheckError($this->mode, $this->objFormParam);
                        if (count($this->arrErr) == 0) {
                            if ($this->lfRegistFavoriteProduct($this->objFormParam->getValue('favorite_product_id'), $objCustomer->getValue('customer_id'))) {

                                $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
                                $objPlugin->doAction('LC_Page_Products_Detail_action_add_favorite_sphone', array($this));

                                print 'true';
                                SC_Response_Ex::actionExit();
                            }
                        }
                        print 'error';
                        SC_Response_Ex::actionExit();
                    }
                    break;

                case 'remisetwoclick':
                    $this->doCart_twoclick();
                    $this->Remiseaction();
                    break;

                case 'select':
                case 'select2':
                case 'selectItem':
                    // モバイルの数量指定・規格選択の際に、
                    // $_SESSION['cart_referer_url'] を上書きさせないために、何もせずbreakする。
                    break;

                default:
                    // カート「戻るボタン」用に保持
                    $netURL = new Net_URL();
                    $_SESSION['cart_referer_url'] = $netURL->getURL();
                    break;
            }

            // モバイル用 ポストバック処理
            if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
                switch ($this->mode) {
                    case 'select':
                        // 規格1が設定されている場合
                        if ($this->tpl_classcat_find1) {
                            // templateの変更
                            $this->tpl_mainpage = 'products/select_find1.tpl';
                            break;
                        }

                        // 数量の入力を行う
                        $this->tpl_mainpage = 'products/select_item.tpl';
                        break;

                    case 'select2':
                        $this->arrErr = $this->lfCheckError($this->mode, $this->objFormParam, $this->tpl_classcat_find1, $this->tpl_classcat_find2);

                        // 規格1が設定されていて、エラーを検出した場合
                        if ($this->tpl_classcat_find1 and $this->arrErr['classcategory_id1']) {
                            // templateの変更
                            $this->tpl_mainpage = 'products/select_find1.tpl';
                            break;
                        }

                        // 規格2が設定されている場合
                        if ($this->tpl_classcat_find2) {
                            $this->arrErr = array();

                            $this->tpl_mainpage = 'products/select_find2.tpl';
                            break;
                        }

                    case 'selectItem':
                        $this->arrErr = $this->lfCheckError($this->mode, $this->objFormParam, $this->tpl_classcat_find1, $this->tpl_classcat_find2);

                        // 規格2が設定されていて、エラーを検出した場合
                        if ($this->tpl_classcat_find2 and $this->arrErr['classcategory_id2']) {
                            // templateの変更
                            $this->tpl_mainpage = 'products/select_find2.tpl';
                            break;
                        }

                        $value1 = $this->objFormParam->getValue('classcategory_id1');

                        // 規格2が設定されている場合.
                        if (SC_Utils_Ex::isBlank($this->objFormParam->getValue('classcategory_id2')) == false) {
                            $value2 = '#' . $this->objFormParam->getValue('classcategory_id2');
                        } else {
                            $value2 = '#0';
                        }

                        if (strlen($value1) === 0) {
                            $value1 = '__unselected';
                        }

                        $this->tpl_product_class_id = $objProduct->classCategories[$product_id][$value1][$value2]['product_class_id'];

                        // この段階では、数量の入力チェックエラーを出させない。
                        unset($this->arrErr['quantity']);

                        // 数量の入力を行う
                        $this->tpl_mainpage = 'products/select_item.tpl';
                        break;

                    case 'cart':
                        // この段階でエラーが出る場合は、数量の入力エラーのはず
                        if (count($this->arrErr)) {
                            // 数量の入力を行う
                            $this->tpl_mainpage = 'products/select_item.tpl';
                        }
                        break;

                    default:
                        $this->tpl_mainpage = 'products/detail.tpl';
                        break;
                }
            }
        }

        // 商品詳細を取得
        $this->arrProduct = $objProduct->getDetail($product_id);

        // サブタイトルを取得
        $this->tpl_subtitle = $this->arrProduct['name'];

        // 関連カテゴリを取得
        $this->arrRelativeCat = SC_Helper_DB_Ex::sfGetMultiCatTree($product_id);

        // 商品ステータスを取得
        $this->productStatus = $objProduct->getProductStatus($product_id);

        // 画像ファイル指定がない場合の置換処理
        $this->arrProduct['main_image'] = SC_Utils_Ex::sfNoImageMain($this->arrProduct['main_image']);

        $this->subImageFlag = $this->lfSetFile($this->objUpFile, $this->arrProduct, $this->arrFile);
        // レビュー情報の取得
        $this->arrReview = $this->lfGetReviewData($product_id);

        // 関連商品情報表示
        $this->arrRecommend = $this->lfPreGetRecommendProducts($product_id);

        // ログイン判定
        if ($objCustomer->isLoginSuccess() === true) {
            // お気に入りボタン表示
            $this->tpl_login = true;
            $this->is_favorite = SC_Helper_DB_Ex::sfDataExists('dtb_customer_favorite_products', 'customer_id = ? AND product_id = ?', array($objCustomer->getValue('customer_id'), $product_id));
        }
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function Remiseaction()
    {
        $objFormParam = new SC_FormParam_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objCart     = new LC_Page_Cart_Ex();
        $cartKeys = $objCartSess->getKeys();
        $cartKey = $cartKeys[0];

        // カート内情報の取得
        $cartList = $objCartSess->getCartList($cartKey);
        // カート商品が1件以上存在する場合
        if (count($cartList) > 0) {
            // カートを購入モードに設定
            $objCart->lfSetCurrentCart($objSiteSess, $objCartSess, $cartKey);
            $this->tpl_uniqid = $objSiteSess->getUniqId();
            $arrResult = $this->sfRegisterOrder($this->tpl_uniqid, $objCustomer, $objCartSess, $cartKey);
            if ($_SESSION["product_twoclick"] === "1") {
                $_SESSION['twoclick'] = '1';
                $arrSelectedDeliv = $this->getSelectedDeliv($objPurchase, $arrResult['deliv_id']);
                $this->arrDelivTime = $arrSelectedDeliv['arrDelivTime'];
                $this->saveShippings($objFormParam, $this->arrDelivTime);
                $objPurchase->saveOrderTemp($uniqid, array(), $objCustomer);
                // 購入ページへ
                SC_Response_Ex::sendRedirect($arrResult['redirecturl']);
                exit;
            }
        }
    }

    /**
     * 配送業者IDから, 支払い方法, お届け時間の配列を取得する.
     *
     * - 'arrDelivTime' - お届け時間の配列
     *
     * @param SC_Helper_Purchase $objPurchase SC_Helper_Purchase インスタンス
     * @param integer $deliv_id 配送業者ID
     * @return array 支払い方法, お届け時間を格納した配列
     */
    function getSelectedDeliv(&$objPurchase, $deliv_id)
    {
        $arrResults = array();
        $arrResults['arrDelivTime'] = $objPurchase->getDelivTime($deliv_id);

        return $arrResults;
    }

    /**
     * 配送情報を保存する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrDelivTime 配送時間の配列
     */
    function saveShippings(&$objFormParam, $arrDelivTime)
    {
        // TODO: SC_Purchase::getShippingTemp() で取得して、リファレンスで代入すると, セッションに添字を追加できない？
        foreach (array_keys($_SESSION['shipping']) as $key) {
            $shipping_id = $_SESSION['shipping'][$key]['shipping_id'];
            $time_id = $objFormParam->getValue('deliv_time_id' . $shipping_id);
            $_SESSION['shipping'][$key]['time_id'] = $time_id;
            $_SESSION['shipping'][$key]['shipping_time'] = $arrDelivTime[$time_id];
            $_SESSION['shipping'][$key]['shipping_date'] = $objFormParam->getValue('deliv_date' . $shipping_id);
        }
    }

    /**
     * 指定した顧客IDの最新の受注を取得
     *
     * @param $customer_id 顧客ID
     * @return  array 顧客の最新受注
     */
    function getMaxOrder($customer_id)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "deliv_id, order_id, payment_id, charge, payment_method, memo06";
        $table = "dtb_order";
        $where = "order_id = (select max(order_id) from dtb_order where del_flg = '0' AND customer_id = ? AND status <> ?)";
        $vals = array($customer_id, ORDER_PENDING);

        $arrOrder = $objQuery->getRow($col, $table, $where, $vals);

        return $arrOrder;
    }

    /**
     * 指定した受注の商品種別を取得
     *
     * @param string order_id 受注番号
     * @return array 商品種別
     */
    function getProducts_Type_id($order_id)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "product_type_id";
        $table = "dtb_products_class";
        $where = "product_class_id = (select min(product_class_id) from dtb_order_detail where order_id = ?)";
        $vals = array($order_id);

        $products_Class = $objQuery->getRow($col, $table, $where, $vals);

        return $products_Class;
    }

    /**
     * ２クリック決済用の受注情報を作成する
     *
     * @param string uniqid ユニークキー
     * @param SC_Customer objCustomer ログイン中の SC_Customer インスタンス
     * @param SC_CartSess objCartSess SC_CartSess インスタンス
     * @param string cartKey カートキー
     * @return array 受注情報作成結果
     */
    function sfRegisterOrder($uniqid, &$objCustomer, &$objCartSess, $cartKey)
    {
        // 前回の受注情報を取得する
        $arrOrder = $this->getMaxOrder($objCustomer->getValue('customer_id'));
        // 前回の支払方法が利用できるか判定
        $arrPaymentCheck = $this->sfIsPaymentCheck($arrOrder, $objCartSess, $cartKey);

        // ２クリック決済用受注の作成
        $arrResult = $this->sfRegisterOrderCommit($uniqid, $objCustomer, $arrOrder, $cartKey, $arrPaymentCheck);

        return $arrResult;
    }

    /**
     * ２クリック決済用受注の作成
     *
     * 前回と今回の受注で商品種別が違う場合、
     * 購入フロー・支払方法の関係で処理を変える。
     * 通常-通常                 確認画面
     * 通常-ダウンロード         支払方法選択画面
     * ダウンロード-通常         支払方法選択画面
     * ダウンロード-ダウンロード 確認画面
     *
     * @param string uniqid ユニークキー
     * @param SC_Customer objCustomer ログイン中の SC_Customer インスタンス
     * @param array arrOrder 前回の受注情報配列
     * @param string cartKey カートキー
     * @param array arrPaymentCheck 支払方法利用可否結果
     * @return array 受注作成結果
     */
    function sfRegisterOrderCommit($uniqid, &$objCustomer, $arrOrder, $cartKey, $arrPaymentCheck)
    {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrResult = array();
        $arrResult['redirecturl'] = SHOPPING_CONFIRM_URLPATH;
        $arrResult['old_order_id'] = $arrOrder['order_id'];

        // dtb_products_class取得
        $products_Class = $this->getProducts_Type_id($arrOrder['order_id']);

        // ダウンロード商品の場合は、支払方法のみ取得
        if (($cartKey == PRODUCT_TYPE_DOWNLOAD || $cartKey == PRODUCT_TYPE_AC_REMISE_DL) && $cartKey == $products_Class['product_type_id']) {
            $arrValues = $this->setDownLoadValues($arrOrder, $arrPaymentCheck);
            $arrValues['memo06'] = $arrOrder['memo06'];
            $arrResult['payment_id'] = $arrValues['payment_id'];
            $objPurchase->saveOrderTemp($uniqid, $arrValues, $objCustomer);

        }
        else if ($cartKey == PRODUCT_TYPE_NORMAL || $cartKey == PRODUCT_TYPE_AC_REMISE) {
            $arrShipping = $this->getShippingAddress($arrOrder['order_id']);
            $arrValues = array();

            // 前回の住所がない場合、会員住所を設定
            if (SC_Utils_Ex::isBlank($arrShipping)) {
                $objPurchase->copyFromCustomer($arrValues, $objCustomer, 'shipping');
            } else {
                $objPurchase->copyFromOrder($arrValues, $arrShipping, 'shipping', '');
            }
            // 前回と今回の商品種別が違う場合
            if ($cartKey == $products_Class['product_type_id']) {
                $arrValues = $this->setNormalValues($uniqid, $arrOrder, $arrPaymentCheck, true, $arrValues);
                $arrValues['memo06'] = $arrOrder['memo06'];
                $arrResult['payment_id'] = $arrValues['payment_id'];
                $objPurchase->saveOrderTemp($uniqid, $arrValues, $objCustomer);
            } else {
                $arrValues = $this->setNormalValues($uniqid, $arrOrder, $arrPaymentCheck, false, $arrValues);
                $arrValues['memo06'] = $arrOrder['memo06'];
                $objPurchase->saveOrderTemp($uniqid, $arrValues, $objCustomer);
                unset($_SESSION["twoclick"]);
                $arrResult['redirecturl'] = DELIV_URLPATH;
            }
        }
        else {
            unset($_SESSION["twoclick"]);
            $arrResult['redirecturl'] = DELIV_URLPATH;
        }

        if ($arrPaymentCheck['ischeck'] <= 0) {
            unset($_SESSION["twoclick"]);
            $arrResult['redirecturl'] = DELIV_URLPATH;
        }

        $arrResult['deliv_id'] = $arrOrder['deliv_id'];

        return $arrResult;
    }

    /**
     * ダウンロード用の更新情報設定
     *
     * @param array arrOrder 前回の受注情報配列
     * @param array arrPaymentCheck 支払方法利用可否結果
     * @return 更新情報設定配列
     */
    function setDownLoadValues($arrOrder, $arrPaymentCheck)
    {
        $arrValues['deliv_id'] = $arrOrder['deliv_id'];

        if ($arrPaymentCheck['ischeck'] == 1) {
            $arrValues['charge'] = $arrOrder['charge'];
            $arrValues['payment_id'] = $arrOrder['payment_id'];
            $arrValues['payment_method'] = $arrOrder['payment_method'];
        }
        else if ($arrPaymentCheck['ischeck'] == 2) {
            $arrValues['charge'] = $arrPaymentCheck['charge'];
            $arrValues['payment_id'] = $arrPaymentCheck['payment_id'];
            $arrValues['payment_method'] = $arrPaymentCheck['payment_method'];
        }
        return $arrValues;
    }

    /**
     * 通常用の更新情報設定
     *
     * @param string uniqid ユニークキー
     * @param array arrOrder 前回の受注情報配列
     * @param array arrPaymentCheck 支払方法利用可否結果
     * @param boolean product_type_flg true：前回通常配送、false：前回ダンロード配送
     * @return 更新情報設定配列
     */
    function setNormalValues($uniqid, $arrOrder, $arrPaymentCheck, $product_type_flg, $arrValues)
    {
        $objPurchase = new SC_Helper_Purchase_Ex();

        $arrValues['deliv_id'] = $arrOrder['deliv_id'];

        if ($product_type_flg == true) {
            $objPurchase->saveShippingTemp($arrValues);
            // 前回の支払方法をそのまま使用
            if ($arrPaymentCheck['ischeck'] == 1) {
                $arrValues['payment_id'] = $arrOrder['payment_id'];
                $arrValues['charge'] = $arrOrder['charge'];
                $arrValues['payment_method'] = $arrOrder['payment_method'];
            }
            // 前回の支払方法と同じ種類で、使用条件が異なる場合はそちらを使用
            else if ($arrPaymentCheck['ischeck'] == 2) {
                $arrValues['payment_id'] = $arrPaymentCheck['payment_id'];
                $arrValues['charge'] = $arrPaymentCheck['charge'];
                $arrValues['payment_method'] = $arrPaymentCheck['payment_method'];
            }
            $arrValues['order_temp_id'] = $uniqid;
            $arrValues['use_point'] = 0;
            $arrValues['point_check'] = '2';
            $arrValues['update_date'] = 'Now()';
        } else {
            $objPurchase->saveShippingTemp($arrValues);
        }
        $arrValues['memo06'] = $arrOrder['memo06'];
        return $arrValues;
    }

    /**
     * 前回の支払方法が利用できるか判定
     *
     * @param array $arrPreOrder 前回の受注情報配列
     * @param SC_CartSess objCartSess SC_CartSess インスタンス
     * @param string cartKey カートキー
     * @return array 支払方法利用可否結果
     */
    function sfIsPaymentCheck($arrPreOrder, &$objCartSess, $cartKey)
    {
        $arrResult = array();

        // 前回の支払方法が今回使用可能か
        $paymentFlg = false;
        // 前回の支払方法と同じ種類のものが使用可能か
        $arrResult['ischeck'] = 1;

        // 前回の支払方法情報を取得
        $payment = $this->getPayment($arrPreOrder['payment_id']);

        if (SC_Utils_Ex::isBlank($payment)) {
            $arrResult['ischeck'] = -1;
            return $arrResult;
        }

        // 今回利用可能な決済の取得
        $total = $objCartSess->getAllProductsTotal($cartKey);
        $arrPayment = $this->getPaymentsByPrice($total, $arrPreOrder['deliv_id']);

        // 前回の支払方法が今回使用可能かの判定
        foreach ($arrPayment as $data) {
            if ($data['payment_id'] == $arrPreOrder['payment_id']) {
                $paymentFlg = true;
                break;
            }
        }

        if ($paymentFlg == false) {
            $arrResult['ischeck'] = 0;
            // 前回の支払方法と同じ種類で、使用条件が異なる場合はそちらを使用
            foreach ($arrPayment as $data) {
                if ($payment['memo03'] == $data['memo03']) {
                    $arrResult['ischeck'] = 2;
                    $arrResult['charge'] = $data['charge'];
                    $arrResult['payment_id'] = $data['payment_id'];
                    $arrResult['payment_method'] = $data['payment_method'];
                    break;
                }
            }
        }
        return $arrResult;
    }

    /**
     * 決済方法を取得する
     *
     * @param int payment_id 決済ID
     * @return array 決済方法取得結果
     */
    function getPayment($payment_id)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "memo03, module_code, upper_rule, upper_rule";
        $table = "dtb_payment";
        $where = "payment_id = ?";
        $vals = array($payment_id);

        $payment = $objQuery->getRow($col, $table, $where, $vals);

        return $payment;
    }

    /**
     * 指定した受注の住所情報を取得
     *
     * @param string order_id 受注番号
     * @return array 住所の配列
     *
     */
    function getShippingAddress($order_id)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "shipping_name01 as name01,
                shipping_name02 as name02,
                shipping_kana01 as kana01,
                shipping_kana02 as kana02,
                shipping_tel01 as tel01,
                shipping_tel02 as tel02,
                shipping_tel03 as tel03,
                shipping_pref as pref,
                shipping_zip01 as zip01,
                shipping_zip02 as zip02,
                shipping_addr01 as addr01,
                shipping_addr02 as addr02,
                shipping_time";

        $table = "dtb_shipping";

        $where  = "del_flg = '0'";
        $where .= " AND order_id = ?";

        $order = "shipping_id";

        $vals = array($order_id);

        $objQuery->setOrder($order);
        $arrShipping = $objQuery->getRow($col, $table, $where, $vals);

        return $arrShipping;
    }

    /**
     * 指定したユニークキーの受注tmpを取得
     *
     * @param string uniqid ユニークキー
     * @return array dtb_paymentの情報
     */
    function getOrderTmp($uniqid)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $col = "ot.payment_id, p.memo03";

        $table = "dtb_order_temp ot, dtb_payment p";
        $where = "ot.order_temp_id = ? AND ot.payment_id = p.payment_id";

        $vals = array($uniqid);
        $arrOrderTmp = $objQuery->getRow($col, $table, $where, $vals);

        return $arrOrderTmp;
    }

    /**
     * 購入金額に応じた支払方法を取得する.
     *
     * @param integer $total 購入金額
     * @param integer $deliv_id 配送業者ID
     * @return array 購入金額に応じた支払方法の配列
     */
    function getPaymentsByPrice($total, $deliv_id)
    {
        $objPurchase = new SC_Helper_Purchase_Ex();

        $arrPaymentIds = $objPurchase->getPayments($deliv_id);
        if (SC_Utils_Ex::isBlank($arrPaymentIds)) {
            return array();
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // 削除されていない支払方法を取得
        $where = 'del_flg = 0 AND payment_id IN (' . implode(', ', array_pad(array(), count($arrPaymentIds), '?')) . ')';
        $objQuery->setOrder("rank DESC");
        if (preg_match('/^2\.11/', ECCUBE_VERSION)) {
            $payments = $objQuery->select("payment_id, payment_method, rule, upper_rule, note, payment_image, charge, memo03", "dtb_payment", $where, $arrPaymentIds);
        } else {
            $payments = $objQuery->select("payment_id, payment_method, rule_max as rule, upper_rule, note, payment_image, charge, memo03", "dtb_payment", $where, $arrPaymentIds);
        }
        foreach ($payments as $data) {
            // 下限と上限が設定されている
            if (strlen($data['rule']) != 0 && strlen($data['upper_rule']) != 0) {
                if ($data['rule'] <= $total && $data['upper_rule'] >= $total) {
                    $arrPayment[] = $data;
                }
            }
            // 下限のみ設定されている
            else if (strlen($data['rule']) != 0) {
                if ($data['rule'] <= $total) {
                    $arrPayment[] = $data;
                }
            }
            // 上限のみ設定されている
            else if (strlen($data['upper_rule']) != 0) {
                if ($data['upper_rule'] >= $total) {
                    $arrPayment[] = $data;
                }
            }
            // いずれも設定なし
            else {
                $arrPayment[] = $data;
            }
        }
        return $arrPayment;
    }

    /**
     * 利用端末にあわせ、エラーメッセージを最適化.
     *
     * @param string
     *
     * @return string
     */
    function fixMessage($err)
    {
        switch (SC_Display::detectDevice()) {
            case DEVICE_TYPE_MOBILE:
                $errString = '<font color="#FF0000">' . $err . '<br></font>';
                break;
            case DEVICE_TYPE_SMARTPHONE:
                $errString = '<p class="attentionSt">' . $err . '</p><br />';
                break;
            default:
                $errString = '<p><span class="attentionSt">' . $err . '</span></p>';
                break;
        }
        return $errString;
    }

    /**
     * 2クリック決済にて、カードのページに移動せずに商品を追加
     *
     * @return void
     */
    function doCart_twoclick()
    {
        $this->arrErr = $this->lfCheckError($this->mode, $this->objFormParam,
                                            $this->tpl_classcat_find1,
                                            $this->tpl_classcat_find2);
        if (count($this->arrErr) == 0) {
            $objCartSess = new SC_CartSession_Ex();
            $product_class_id = $this->objFormParam->getValue('product_class_id');

            $objCartSess->addProduct($product_class_id, $this->objFormParam->getValue('quantity'));

            $_SESSION["product_twoclick"] = "1";
        }
    }
}
