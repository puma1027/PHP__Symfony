<?php
/**
 * ルミーズ決済モジュール（2クリック機能・メッセージ管理）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version twoclick_info.php,v 3.0
 *
 */

define('ERR_AC_PRODUCT', '※定期購買商品はかんたん決済の対象外です。<br />
                          一旦定期購買商品を削除するか、通常購入をお願いします。');

define('ERR_OTHER_PRODUCTTYPE', '※違う種類の商品がカゴに入っているので、かんたん決済をご利用いただけません。');

define('ERR_MULTI_PRODUCT', '※２種類以上の商品が入っているので、かんたん決済をご利用いただけません。');

define('ERR_ORDER_EMPTY', '※対象となる決済がございませんため、現在かんたん決済をご利用いただけません。');

define('ERR_PRODUCTTYPE_DIFF', 'カゴの中の商品は、今回のご注文ではかんたん決済をご利用いただけません。');

define('ERR_DELIV_LOST', '※前回のお買い物の際の配送方法が利用不可のため、かんたん決済をご利用いただけません。');

define('ERR_MULTI_SHIPPING', '※前回のお買い物の際、複数の配送先が選択されたため、かんたん決済をご利用いただけません。');

define('ERR_NOT_PAYREMISE', '※直前のお支払方法はかんたん決済対象外のため、現在かんたん決済はご利用いただけません。');

define('ERR_CARD_NOTFOUND', '※かんたん決済でご利用可能なクレジットカード情報の登録がなく、かんたん決済できません。');

define('ERR_CARD_EXPIRE', '※かんたん決済にてご登録のカードの有効期限が切れており、かんたん決済できません。');

define('ERR_CTYPE＿GATEWAY', '※現在の設定では、かんたん決済できません。');

define('ERR_CANNOT_PAYQUICK', '※現在の設定では、かんたん決済ができません。');

define('INFO_TWOCLICK_BTN', 'かんたん決済をご利用の場合、「かんたん決済」ボタンをクリックしてください。<br />
                             お支払方法の変更等をご希望の場合は「購入手続きへ」ボタンをクリックしてください。');
?>
