<?php
/**
 *
 * @copyright	2000-2007 EC-CUBE CO.,LTD. All Rights Reserved.
 * @version	CVS: $Id: mdl_remise.inc 7162 2006-11-18 09:53:33Z inoue $
 * @link		http://www.ec-cube.co.jp/
 *
 */
// ルミーズモジュールバージョン
define('MDL_REMISE_VERSION', 1.3);

// ルミーズモジュールID
define("MDL_REMISE_CODE", "mdl_remise");

// カード用結果通知ログパス
define("REMISE_LOG_PATH_CARD_RET", DATA_REALDIR . "logs/remise_card_result.log");

// モバイルコンビニ完了テンプレート用ログバス
define("REMISE_LOG_PATH_CONVENI_RET", DATA_REALDIR . "logs/remise_cv_finish.log");

// EC-CUBE・ルミーズ共通ID
define("MDL_REMISE_POST_VALUE", A0000155);

// ルミーズへのデータ送信用エンコード
define("REMISE_SEND_ENCODE", 'SJIS-win');

// クレジット決済・コンビニ決済上限・下限金額
define("REMISE_CREDIT_UPPER", 500000);
define("REMISE_CONVENIENCE_UPPER", 500000);
define("REMISE_CONVENIENCE_BOTTOM", 200);

// クレジット支払いコード(10:一括 61:分割 80:リボ)
define("REMISE_PAYMENT_METHOD_LUMP", 10);
define("REMISE_PAYMENT_METHOD_DIVIDE", 61);
define("REMISE_PAYMENT_METHOD_REVO", 80);

// クレジット最大支払い回数
// ECサイト様の条件に合わせて変更して下さい
define("REMISE_PAYMENT_DIVIDE_MAX", 2);

// ECサイト様の条件に合わせて変更して下さい
// AUTH(仮売上)
// CAPTURE(実売上)
define("REMISE_PAYMENT_JOB_CODE", "AUTH");

// 分割回数
global $arrCreditDivide;
$arrCreditDivide = array(
	2 => "2"
);

// クレジット結果ステータス
define("REMISE_PAYMENT_CHARGE_OK", "<SDBKDATA>STATUS=800</SDBKDATA>");
// クレジット結果ステータス(モバイル)
define("REMISE_PAYMENT_CHARGE_OK_MOBILE", "<SDBKDATA>STATUS=900</SDBKDATA>");

// コンビニ収納受信フラグ(0:自動受信しない 1:自動受信する)
define("REMISE_CONVENIENCE_RECIVE", 1);

// コンビニ収納済フラグ
define("REMISE_CONVENIENCE_CHARGE", 1);

// コンビニ結果ステータス
define("REMISE_CONVENIENCE_CHARGE_OK", "<SDBKDATA>STATUS=800</SDBKDATA>");

// エラー内容
global $arrRemiseErrorWord;
$arrRemiseErrorWord = array(
	"OK" => "0:0000"
);

// ルミーズからの通信のIP制御(0:しない 1:する)
define("REMISE_IP_ADDRESS_DENY", 0);

// ルミーズサーバのIPアドレス
// 実際に使用する場合は最新のIPアドレス帯域を確認して下さい
define("REMISE_IP_ADDRESS_S", "211.0.149.169");
define("REMISE_IP_ADDRESS_E", "211.0.149.169");

// eLIO決済を使用するかどうかのフラグ(0:しない 1:する)
define('REMISE_ELIO_USE', 1);

define('PAY_REMISE_CREDIT', 1);
define('PAY_REMISE_CONVENI', 2);

// 決済方法
global $arrPayment;
$arrPayment = array(
	1 => "コンビニ"
);

// 支払い方法
global $arrCredit;
$arrCredit = array(
	10 => "一括払い",
	61 => "分割払い",
	80 => "リボルビング払い"
);

// コンビニの種類
global $arrConvenience;
$arrConvenience = array(
	D001 => "セブンイレブン"
	,D002 => "ローソン"
	,D030 => "ファミリーマート"
	,D004 => "サークスＫ"
	,D003 => "サンクス"
	,D015 => "セイコーマート"
	,D005 => "ミニストップ"
	,D010 => "デイリーヤマザキ"
	,D011 => "ヤマザキデイリーストア"
);

// 決済方法方法(モバイル完了処理に使用)
define('REMISE_PAY_TYPE_CONVENI', 1);
define('REMISE_PAY_TYPE_CREDIT', 2);

/**
 * エリオが使用可能かどうかを返す
 *
 * @return boolean
 */
function lfIsRemiseELIOEnable() {
    if (REMISE_ELIO_USE && SC_MobileUserAgent::isNonMobile()) {
        return true;
    }
    return false;
}

/**
 * コンビニ用配列作成
 */
function lfSetConvMSG($name, $value){
    return array("name" => $name, "value" => $value);
}

/**
 * クレジット送信内容
 *
 * @param array $arrData
 * @param array $arrPayment
 * @param string $uniqid
 * @return array
 */
function lfCreateCreditSendData($arrData, $arrPayment, $uniqid) {
    $arrSendData = array(
        'S_TORIHIKI_NO' => $arrData["order_id"],        // オーダー番号
        'MAIL'          => $arrData["order_email"],     // メールアドレス
        'AMOUNT'        => $arrData["payment_total"],   // 金額
        'TAX'           => '0',                         // 送料 + 税
        'TOTAL'         => $arrData["payment_total"],   // 合計金額
        'SHOPCO'        => $arrPayment[0]["memo01"],    // 店舗コード
        'HOSTID'        => $arrPayment[0]["memo02"],    // ホストID
        'JOB'           => REMISE_PAYMENT_JOB_CODE,     // ジョブコード
        'ITEM'          => '0000120',                   // 商品コード(ルミーズ固定)
        'REMARKS3'      => MDL_REMISE_POST_VALUE,
    );

    if (SC_MobileUserAgent::isMobile()) {
        $arrSendData['SEND_URL'] = $arrPayment[0]["memo06"];
        $arrSendData['TMPURL']   = ''; // ルミーズ側で処理を完結させるため、空の値を入れる
        $arrSendData['OPT']      = $uniqid;
        $arrSendData['EXITURL']  = SITE_URL;

    } else {
    	// 2014.6.18 Add TransactionID
        $arrSendData['SEND_URL']  = $arrPayment[0]["memo04"];
        $arrSendData['RETURL']    = HTTPS_URL . 'shopping/load_payment_module.php?'.TRANSACTION_ID_NAME."=".$_REQUEST[TRANSACTION_ID_NAME];
        $arrSendData['NG_RETURL'] = HTTPS_URL . 'shopping/load_payment_module.php?'.TRANSACTION_ID_NAME."=".$_REQUEST[TRANSACTION_ID_NAME];
        $arrSendData['EXITURL']   = HTTPS_URL . 'shopping/load_payment_module.php?'.TRANSACTION_ID_NAME."=".$_REQUEST[TRANSACTION_ID_NAME]."&amp;mode=ret_remise";
    }

    return $arrSendData;
}

/**
 * コンビニ送信内容
 */
function lfCreateConveniSendData($arrData, $arrPayment, $uniqid) {
    // 名前整形
    $name1 = mb_convert_kana($arrData["order_name01"], "ASKHV");
    $name2 = mb_convert_kana($arrData["order_name02"], "ASKHV");
    // 電話番号整形
    $tel = $arrData["order_tel01"].$arrData["order_tel02"].$arrData["order_tel03"];
    // 住所整形
    $masterData = new SC_DB_MasterData_Ex();
    $arrPref = $masterData->getMasterData("mtb_pref", array("id", "name", "rank"));
    $pref = $arrPref[$arrData["order_pref"]];
    $address1 = mb_convert_kana($arrData["order_addr01"], "ASKHV");
    $address2 = mb_convert_kana($arrData["order_addr02"], "ASKHV");

    $arrSendData = array(
        'SHOPCO' => $arrPayment[0]["memo01"],           // 店舗コード
        'HOSTID' => $arrPayment[0]["memo02"],           // ホストID
        'S_TORIHIKI_NO' => $arrData["order_id"],        // 請求番号(EC-CUBE)
        'NAME1' => $name1,                              // ユーザー名1
        'NAME2' => $name2,                              // ユーザー名２
        'YUBIN1' => $arrData["order_zip01"],            // 郵便番号1
        'YUBIN2' => $arrData["order_zip02"],            // 郵便番号2
        'ADD1' => $pref,                                // 住所1
        'TEL' => $tel,                                  // 電話番号
        'MAIL' => $arrData["order_email"],              // メールアドレス
        'TOTAL' => $arrData["payment_total"],           // 合計金額
        'TAX' => '0',                                   // 送料 + 税
        'MNAME_01' => "商品代金",                        // 最大7個のため、商品代金として全体で出力する
        'MSUM_01' => $arrData["payment_total"],         // 商品代金合計
        'REMARKS3' => MDL_REMISE_POST_VALUE
    );

    // モバイルパラメータ
    if (SC_MobileUserAgent::isMobile()) {
        $arrSendData['SEND_URL'] = $arrPayment[0]["memo07"];
        $arrSendData['TMPURL'] = HTTPS_URL . 'user_data/remise_recv.php';
        $arrSendData['OPT'] = $uniqid;
        $arrSendData['EXITURL'] = SITE_URL;
        GC_Utils_Ex::gfPrintLog("uniqid: $uniqid", REMISE_LOG_PATH_CONVENI_RET);
    // PCパラメータ
    } else {
        $arrSendData['SEND_URL'] = $arrPayment[0]["memo05"];
        $arrSendData['RETURL'] = HTTPS_URL . 'shopping/load_payment_module.php';
        $arrSendData['NG_RETURL'] = HTTPS_URL . 'shopping/load_payment_module.php';
        $arrSendData['EXITURL'] = HTTPS_URL . 'shopping/load_payment_module.php';
        $arrSendData['KANA1'] = $arrData["order_kana01"];
        $arrSendData['KANA2'] = $arrData["order_kana02"];
        $arrSendData['ADD2'] = $address1;
        $arrSendData['ADD2'] = $address2;
    }

    return $arrSendData;
}

?>
