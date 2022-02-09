<?php
/**
 * ルミーズ決済モジュール（定数定義）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version include.php,v 3.1
 *
 */

// 【共通定義】
// ルミーズモジュールバージョン
define('MDL_REMISE_VERSION', '3.0.14');
// ルミーズモジュールID
define("MDL_REMISE_CODE", "mdl_remise");
// ルミーズへのデータ送信用エンコード
define("REMISE_SEND_ENCODE", 'SJIS-win');
// ルミーズからの通信のIP制御(0:しない 1:する)
define("REMISE_IP_ADDRESS_DENY", 0);
// ルミーズサーバのIPアドレス(実際に使用する場合は最新のIPアドレス帯域を確認してください)
define("REMISE_IP_ADDRESS_S", "210.160.253.128");
define("REMISE_IP_ADDRESS_E", "210.160.253.192");
// 代理店コード
define('MDL_REMISE_POST_VALUE', 'A0000155');
// 画面制御
define('REMISE_CONFIRM', 'remise');
define('REMISE_CANCEL', 'cancel');
define("REMISE_DISPLAY_INPUT", 0);
define("REMISE_DISPLAY_CONFIRM", 1);
// 支払方法区分
define('PAY_REMISE_CREDIT', 1);
define('PAY_REMISE_CONVENI', 2);
// 接続形態
define('REMISE_CONNECT_TYPE_WEB', 1);
define('REMISE_CONNECT_TYPE_GATEWAY', 2);
$GLOBALS["arrConnect"] = array (
    REMISE_CONNECT_TYPE_WEB     => "リンク式",
    REMISE_CONNECT_TYPE_GATEWAY => "トークン式<span class=\"red\">（オプション）</span>",
);
// ダイレクトモード
define('REMISE_DIRECT_ON', 'ON');
define('REMISE_DIRECT_OFF', 'OFF');
$GLOBALS["arrDirect"] = array (
    REMISE_DIRECT_OFF   => "リモート",
    REMISE_DIRECT_ON    => "ローカル<span class=\"red\">（オプション）</span>",
);
// オプションサービスの利用制御(ペイクイック)
define('REMISE_OPTION_USE',1);
$GLOBALS["arrUse"] = array (
    "1" => "利用する",
    "0" => "利用しない",
);
// エラー内容
$GLOBALS["arrRemiseErrorWord"] = array (
    "OK"        => "0:0000",
    "NORMAL"    => "0",
);

// 【共通パス定義】
// ファイルパス
define('MDL_REMISE_PATH', MODULE_REALDIR. MDL_REMISE_CODE . '/');
define('MDL_REMISE_CLASS_PATH', MDL_REMISE_PATH . 'class/');
define('MDL_REMISE_TEMPLATE_PATH', MDL_REMISE_PATH . 'tmp/');
define('MDL_REMISE_RETURL', HTTPS_URL . 'shopping/load_payment_module.php');
define('MDL_REMISE_AC_TEMPLATE_PATH', MDL_REMISE_PATH . 'tmp_ac/');
define('MDL_REMISE_AC_UPDATE_RETURL', HTTPS_URL . 'user_data/remise_ac_update.php');
define('MDL_REMISE_AC_REFUSAL_RETURL', HTTPS_URL . 'user_data/remise_ac_refusal.php');
// イメージパス
define('IMG_URL', 'user_data/' . MDL_REMISE_CODE . '/img/');

// 【クレジットカード決済関連定義】
// 名称
define('REMISE_CREDIT_NAME', 'カード決済');
// 決済上下限金額
define("REMISE_CREDIT_UPPER", 999999);
define("REMISE_CREDIT_BOTTOM", 1);
// 処理区分
define('REMISE_PAYMENT_JOB_CAPTURE',    'CAPTURE');
define('REMISE_PAYMENT_JOB_AUTH',       'AUTH');
define('REMISE_PAYMENT_JOB_SALES',      'SALES');
define('REMISE_PAYMENT_JOB_VOID',       'VOID');
define('REMISE_PAYMENT_JOB_RETURN',     'RETURN');
$GLOBALS["arrJob"] = array (
    REMISE_PAYMENT_JOB_CAPTURE  => "売上",
    REMISE_PAYMENT_JOB_AUTH     => "仮売上",
    REMISE_PAYMENT_JOB_SALES    => "実売上",
    REMISE_PAYMENT_JOB_VOID     => "即日取消",
    REMISE_PAYMENT_JOB_RETURN   => "返品",
);
// 利用JOBコード
$GLOBALS["arrPaymentJob"] = array (
    REMISE_PAYMENT_JOB_AUTH    => "AUTH(仮売上)",
    REMISE_PAYMENT_JOB_CAPTURE => "CAPTURE(売上)",
);
// 支払区分
define("REMISE_PAYMENT_METHOD_LUMP", 10);
define("REMISE_PAYMENT_METHOD_DIVIDE", 61);
define("REMISE_PAYMENT_METHOD_REVO", 80);
$GLOBALS["arrCredit"] = array (
    REMISE_PAYMENT_METHOD_LUMP      => "一括払い",
    REMISE_PAYMENT_METHOD_DIVIDE    => "分割払い",
    REMISE_PAYMENT_METHOD_REVO      => "リボルビング払い",
);
// 分割回数
define("REMISE_PAYMENT_DIVIDE_MAX", 2);
$GLOBALS["arrCreditDivide"] = array (
    REMISE_PAYMENT_DIVIDE_MAX   => "2",
);
// ペイクイック登録
$GLOBALS["arrPayquickFlg"] = array (
    "1" => "　登録する",
);
// 利用カードブランド
$GLOBALS["arrUseCardBrand"] = array (
    VISA    => array (CNAME => "VISA",          IMAGE   => "visa.gif"),
    MASTER  => array (CNAME => "MasterCard",    IMAGE   => "master.gif"),
    JCB     => array (CNAME => "JCB",           IMAGE   => "jcb.gif"),
    AMEX    => array (CNAME => "AMEX",          IMAGE   => "amex.gif"),
    MUFG    => array (CNAME => "MUFG",          IMAGE   => "mufg.gif"),
    NICOS   => array (CNAME => "ニコス",        IMAGE   => "nicos.gif"),
    DC      => array (CNAME => "DC",            IMAGE   => "dc.gif"),
    UFJ     => array (CNAME => "UFJ",           IMAGE   => "ufj.gif"),
    TOYOTA  => array (CNAME => "TS CUBIC",      IMAGE   => "toyota.gif"),
    DINERS  => array (CNAME => "Diners",        IMAGE   => "diners.gif"),
    AEON    => array (CNAME => "イオン",        IMAGE   => "aeon.gif"),
    RAKUTEN => array (CNAME => "楽天",          IMAGE   => "rakuten.gif"),
    ORICO   => array (CNAME => "オリコ",        IMAGE   => "orico.gif"),
    LIFE    => array (CNAME => "ライフ",        IMAGE   => "life.gif"),
    SAISON  => array (CNAME => "セゾン",        IMAGE   => "saison.gif"),
    DISCOVER=> array (CNAME => "DISCOVER",      IMAGE   => "discover.gif"),
    CEDYNA  => array (CNAME => "セディナ",      IMAGE   => "cedyna.gif"),
);

// 結果通知プログラム
define('RESULT_RECEIVE_PG', "user_data/remise_recv.php");
// 結果通知ステータス
define("REMISE_PAYMENT_CHARGE_OK", "<SDBKDATA>STATUS=800</SDBKDATA>");
// 結果通知ステータス(モバイル)
define("REMISE_PAYMENT_CHARGE_OK_MOBILE", "<SDBKDATA>STATUS=900</SDBKDATA>");
// 結果通知ログパス
define("REMISE_LOG_PATH_CARD_RET", DATA_REALDIR . "logs/remise_card_result.log");
// 結果通知ログパス(拡張セット)
define("REMISE_LOG_PATH_EXTSET_RET", DATA_REALDIR . "logs/remise_extset_result.log");
// カードイメージ
define('IMG_CARD_URL', IMG_URL . 'card_img/');

// 最大コミット回数
define("REMISE_MAX_COMMIT_COUNT", 2);

// 定期購買商品ID
define('PRODUCT_TYPE_AC_REMISE', 3);

// 定期購買商品（DL）ID
define('PRODUCT_TYPE_AC_REMISE_DL', 4);

// CSVデータチェック用（3.0.2追加）
define('MEMBER_ID_COUNT', 14);
define('TRANID_COUNT', 28);

// ルミーズ側データとの不整合判定用のステータス（3.0.2追加）
define('ECCUBE_ORDER_PENDING', 8);
define('ECCUBE_ORDER_PENDING_NAME', '受注未確定');
define('ECCUBE_ORDER_PENDING_COLOR', '#BFD0CC');
define('ECCUBE_CUSTOMER_ORDER_PENDING_NAME', '入金確認中');

// 定期購買注文編集ページ
define('ADMIN_AC_ORDER_EDIT_URLPATH', ROOT_URLPATH . ADMIN_DIR . "order/remise_ac_order_edit.php");

// 定期購買退会通信用　3.0.3追加
define('ADMIN_AC_ORDER_EDIT_ALLOW_PATH', "order/remise_ac_order_edit.php");

define('REMISE_MEMBER_ID_LEN', 14);

// 【マルチ決済関連定義】
// 名称
define('REMISE_CONVENIENCE_NAME', 'コンビニ・電子マネー・銀行決済');
// 決済上下限金額
define("REMISE_CONVENIENCE_UPPER", 999999);
define("REMISE_CONVENIENCE_BOTTOM", 1);
// コンビニの種類
$GLOBALS["arrConvenience"] = array (
    D001 => array (
        PAY_WAY => "002",
        NAME    => "セブンイレブン",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
    D002 => array (
        PAY_WAY => "003",
        NAME    => "ローソン",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
    D030 => array (
        PAY_WAY => "006",
        NAME    => "ファミリーマート",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
//     D004 => array (
//         PAY_WAY => "004",
//         NAME    => "サークルＫ",
//         MIN     => "200",
//         MAX     => "299999",
//         PC      => false,
//         MBL     => false,
//     ),
//     D003 => array (
//         PAY_WAY => "004",
//         NAME    => "サンクス",
//         MIN     => "200",
//         MAX     => "299999",
//         PC      => false,
//         MBL     => false,
//     ),
    D010 => array (
        PAY_WAY => "004",
        NAME    => "デイリーヤマザキ",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
    D011 => array (
        PAY_WAY => "004",
        NAME    => "ヤマザキデイリーストア",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
    D005 => array (
        PAY_WAY => "004",
        NAME    => "ミニストップ",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
    D015 => array (
        PAY_WAY => "004",
        NAME    => "セイコーマート",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),

    // PayPal追加
    C511 => array (
        PAY_WAY => "500",
        NAME    => "ＰａｙＰａｌ",
        MIN     => "1",
        MAX     => null,
        PC      => true,
        MBL     => false,
    ),

    D401 => array (
        PAY_WAY => "400",
        NAME    => "楽天Ｅｄｙ",
        MIN     => "1",
        MAX     => "50000",
        PC      => true,
        MBL     => false,
    ),
//     D402 => array (
//         PAY_WAY => "400",
//         NAME    => "モバイルＥｄｙ",
//         MIN     => "1",
//         MAX     => "50000",
//         PC      => false,
//         MBL     => true,
//     ),
//     D403 => array (
//         PAY_WAY => "400",
//         NAME    => "モバイルＳｕｉｃａ",
//         MIN     => "1",
//         MAX     => "20000",
//         PC      => false,
//         MBL     => true,
//     ),
    D405 => array (
        PAY_WAY => "400",
        NAME    => "ペイジー",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
    D406 => array (
        PAY_WAY => "400",
        NAME    => "ジャパンネット銀行",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
    D407 => array (
        PAY_WAY => "400",
        NAME    => "Ｓｕｉｃａインターネットサービス",
        MIN     => "1",
        MAX     => "20000",
        PC      => true,
        MBL     => false,
    ),
    D404 => array (
        PAY_WAY => "400",
        NAME    => "楽天銀行",
        MIN     => "1",
        MAX     => "299999",
        PC      => true,
        MBL     => false,
    ),
    D451 => array (
        PAY_WAY => "400",
        NAME    => "ウェブマネー",
        MIN     => "1",
        MAX     => "500000",
        PC      => true,
        MBL     => false,
    ),
    D452 => array (
        PAY_WAY => "400",
        NAME    => "ビットキャッシュ",
        MIN     => "1",
        MAX     => "200000",
        PC      => true,
        MBL     => false,
    ),
    D453 => array (
        PAY_WAY => "400",
        NAME    => "ＪＣＢプレモカード",
        MIN     => "1",
        MAX     => "50000",
        PC      => true,
        MBL     => false,
    ),
    P901 => array (
        PAY_WAY => "900",
        NAME    => "コンビニ払込票",
        MIN     => "1",
        MAX     => "300000",
        PC      => true,
        MBL     => false,
    ),
    P902 => array (
        PAY_WAY => "900",
        NAME    => "コンビニ払込票（郵便局・ゆうちょ銀行）",
        MIN     => "1",
        MAX     => "999999",
        PC      => true,
        MBL     => false,
    ),
    C501 => array (
        PAY_WAY => "500",
        NAME    => "クレジットカード",
        MIN     => "1",
        MAX     => null,
        PC      => true,
        MBL     => false,
    ),
    /* AUTH(仮売上)で決済する場合、C501を無効にし、C502を有効にしてください。
    C502 => array (
        PAY_WAY => "500",
        NAME    => "クレジットカード",
        MIN     => "1",
        MAX     => null,
        PC      => true,
        MBL     => true,
    ),
    */
    M601 => array (
        PAY_WAY => "600",
        NAME    => "ドコモ払い（都度決済）",
        MIN     => "1",
        MAX     => null,
        PC      => true,
        MBL     => false,
    ),
    /* AUTH(仮売上)で決済する場合、M601を無効にし、M602を有効にしてください。
    M602 => array (
        PAY_WAY => "600",
        NAME    => "ドコモ払い（都度決済）",
        MIN     => "1",
        MAX     => null,
        PC      => true,
        MBL     => false,
    ),
    */
    M611 => array (
        PAY_WAY => "600",
        NAME    => "auかんたん決済/au WALLET（都度決済）",
        MIN     => "1",
        MAX     => "50000",
        PC      => true,
        MBL     => false,
    ),
    /* AUTH(仮売上)で決済する場合、M611を無効にし、M612を有効にしてください。
    M612 => array (
        PAY_WAY => "600",
        NAME    => "auかんたん決済/au WALLET（都度決済）",
        MIN     => "1",
        MAX     => "50000",
        PC      => true,
        MBL     => false,
    ),
    */
    M621 => array (
        PAY_WAY => "600",
        NAME    => "ソフトバンクまとめて支払い（都度決済）",
        MIN     => "1",
        MAX     => null,
        PC      => true,
        MBL     => false,
    ),
    /* AUTH(仮売上)で決済する場合、M621を無効にし、M622を有効にしてください。
    M622 => array (
        PAY_WAY => "600",
        NAME    => "ソフトバンクまとめて支払い（都度決済）",
        MIN     => "1",
        MAX     => null,
        PC      => true,
        MBL     => false,
    ),
    */
);

$GLOBALS["arrACStatus"] = array (
    autocharge  => "継続中",
    stop        => "停止",
);

// お支払方法ご案内URL
define('REMISE_DSK_URL', "http://www.remise.jp/data/paycvs/dsk/index.html");
// お支払方法ご案内URL(モバイル)
define('REMISE_DSK_MOBILE_URL', "http://www.remise.jp/data/paycvs/paycvs2_m.htm");
// 収納通知受信フラグ(0:自動受信しない 1:自動受信する)
define("REMISE_CONVENIENCE_RECIVE", 1);
// 収納済フラグ
define("REMISE_CONVENIENCE_CHARGE", 1);
// 収納通知ステータス
define("REMISE_CONVENIENCE_CHARGE_OK", "<SDBKDATA>STATUS=800</SDBKDATA>");
// 収納通知メール
$GLOBALS["arrReceiptMailTemplate"] = array (
    NAME    => "入金お知らせメール(REMISEマルチ決済)",
    PATH    => "mail_templates/remise_receipt_mail.tpl",
    SUBJECT => "ご入金が確認できました",
    HEADER  => "以下のご注文につきまして、ご入金が確認できました。" . PHP_EOL .
               "これより商品出荷のお手続きに入らせていただきます。",
    FOOTER  => "============================================" . PHP_EOL .
               PHP_EOL .
               "ご質問やご不明な点がございましたら、こちらからお願いいたします。"
);
// 受注キャンセルメール
$GLOBALS["arrOrderCancelMailTemplate"] = array (
    NAME    => "受注キャンセル通知メール",
    PATH    => "mail_templates/remise_order_cancel_mail.tpl",
    SUBJECT => "受注をキャンセルしました",
    HEADER  => "以下のご注文につきまして、カード決済処理中にエラーが発生したため、" . PHP_EOL .
               "キャンセル処理を行いました。",
    FOOTER  => PHP_EOL . "ご確認の上、必要に応じてお客様へのご対応等をお願いいたします。"
);
// 定期購買更新メール
$GLOBALS["arrACCardUpdateMailTemplate"] = array (
    NAME    => "定期購買　カード更新通知メール",
    PATH    => "mail_templates/remise_ac_card_update_mail.tpl",
    SUBJECT => "ご登録カードの更新が完了しました",
    HEADER  => "定期購買にてご登録のカード情報の更新が完了致しました。" . PHP_EOL .
               "今後ともご愛顧賜りますよう、お願いを申し上げます。",
    FOOTER  => PHP_EOL . "なお、詳細につきましては、マイページにてご確認くださいませ。"
);
// 定期購買退会メール
$GLOBALS["arrACOrderRefusalMailTemplate"] = array (
    NAME    => "定期購買　退会通知メール",
    PATH    => "mail_templates/remise_ac_order_refusal_mail.tpl",
    SUBJECT => "定期購買の退会処理が完了しました",
    HEADER  => "以下の定期購買のご注文につきまして、退会処理が完了いたしました。" . PHP_EOL .
               "定期購買サービスをご利用頂き、誠に有難うございました。",
    FOOTER  => PHP_EOL . "またのご利用を心よりお待ちしております。"
);
// 定期購買退会メール
$GLOBALS["arrACRefusalAnotherMailTemplate"] = array (
    NAME    => "定期購買　誤認退会通知メール",
    PATH    => "mail_templates/remise_ac_refusal_another_mail.tpl",
    SUBJECT => "異なる定期購買会員の退会処理が行われました",
    HEADER  => "マイページからの定期購買退会処理時に、異なる定期購買会員が退会されました。" . PHP_EOL .
               "至急「ルミーズ加盟店バックヤードシステム」にてご確認くださいますよう、お願いを申し上げます。",
    FOOTER  => PHP_EOL . "ご確認の上、必要に応じてお客様へのご対応等をお願いいたします。"
);

// 完了通知ログバス(モバイル)
define("REMISE_LOG_PATH_CONVENI_RET", DATA_REALDIR . "logs/remise_cv_finish.log");
// 注文完了メッセージ
define("REMISE_PAY_INFO", "お支払い方法のご案内");
$GLOBALS["arrCvsMsg"] = array (
    "SEVENELEVEN" => array (
        RECEIPT_NO  => "払込票番号",
        PAYMENT_URL => "払込票URL",
        CVS_MSG     => "
上記URLのページを表示の上プリントアウトされるか、払込票番号(13桁)をメモして頂いてセブン－イレブンのレジでお支払いください。
手書きメモの場合、「インターネットの代金支払」とレジにてお申し出の上、払込票番号を記したメモをご提示ください。
"
    ),
    "LAWSON_OTHER" => array (
        RECEIPT_NO  => "受付番号",
        CONFIRM_NO  => "確認番号",
        PAYMENT_URL => "支払方法案内URL",
        CVS_MSG     => "
以下のいずれかの方法で、画面に従って申込券を発行の上、レジへご提示ください。
・Loppi専用バーコードでのお支払いの場合：
　上記支払方法案内URLのページをプリントアウトした書面、もしくは携帯・スマートフォン画面に表示されたLoppi専用バーコードを、
　店頭のLoppi専用バーコードリーダーにかざしてください。
・受付番号と確認番号を利用してのお支払いの場合：
　店頭のLoppi端末のトップ画面から「各種番号をお持ちの方」を選択し、
　番号検索画面で受付番号を入力後、モバライ☆お支払い画面で確認番号を入力してください。
"
    ),
    "SEIKOMART" => array (
        RECEIPT_NO      => "受付番号",
        RECEIPT_TEL_NO  => "登録電話番号",
        PAYMENT_URL     => "支払方法案内URL",
        CVS_MSG         => "
店舗レジにて「インターネット支払い」であることをお申し出いただき、以下のいずれかの方法でお手続きを行ってください。
・番号でのお支払いの場合：
　レジ操作画面にて「受付番号」を入力、次画面で「登録電話番号」をご入力ください。
　確認画面にて内容をご確認のうえ、お支払いください。
・バーコードでのお支払いの場合：
　支払方法案内URLのお支払方法案内画面にバーコードが表示されます。
　バーコードを印刷した紙、またはバーコードを表示したスマートフォンをご提示ください。
　店員がバーコードをスキャン後、レジ操作画面に確認画面が表示されますので、内容をご確認のうえ、お支払いください。
"
    ),
    "CIRCLEK_OTHER" => array (
        RECEIPT_NO      => "受付番号",
        RECEIPT_TEL_NO  => "登録電話番号",
        PAYMENT_URL     => "支払方法案内URL",
        CVS_MSG         => "
店頭のカルワザステーション端末のメニュー画面から「各種支払い」を選択し、次に「6ケタの番号をお持ちの方」を選択してください。
受付番号、登録電話番号を入力して、画面に従い受付票を発行の上、レジへご提示ください。
"
    ),
    "DAILYYAMAZAKI_OTHER" => array (
        RECEIPT_NO  => "オンライン決済番号",
        PAYMENT_URL => "支払方法案内URL",
        CVS_MSG     => "
上記URLのページを表示の上プリントアウトされるか、オンライン決済番号(11桁)をメモして頂いてデイリーヤマザキ・ヤマザキデイリーストアのレジにご提示いただき、手順に従ってお支払いください。
手書きメモの場合、「オンライン決済」とレジにてお申し出の上、オンライン決済番号を記したメモをご提示ください。
"
    ),
    "FAMILYMART" => array (
        COMPANY_CODE    => "企業コード",
        RECEIPT_NO      => "注文番号",
        PAYMENT_URL     => "支払方法案内URL",
        CVS_MSG         => "
店頭のFamiポートの画面から「代金支払い・チャージ」→「収納票発行」を選択してください。
企業コード(5桁)、注文番号(12桁)を入力して申込券を発行し、レジへご提示ください。
"
    ),
    "PAY-EASY" => array (
        RECEIPT_NO  => "確認番号",
        PAYMENT_URL => "支払方法案内URL",
        CVS_MSG     => "
「Pay-easy」マークが貼付されているATM、インターネットバンキング、モバイルバンキングでのお支払いができます。
各収納機関の画面指示に従ってお支払いください。
「収納機関番号」は「58091」、「お客様番号」は「ご登録のお電話番号」となります。
なお、支払方法案内URLからログインされた場合は「収納機関番号」・「お客様番号」・「確認番号」の入力は不要になります。
"
    ),
    "PAPER-PAY" => array (
        RECEIPT_NO  => "受付番号",
        PAYMENT_URL => "支払方法案内URL",
        CVS_MSG     => "
コンビニ払込票は、後日、郵送にてお送りさせて頂きますので、お近くのコンビニエンスストアでお支払手続きをお願いいたします。
なお、ご利用店舗によっては、コンビニ払込票が商品に同梱される場合もございます。予め、ご了承ください。
"
    ),
    "E-MONEY_OTHER" => array (
        RECEIPT_NO  => "受付番号",
        PAYMENT_URL => "支払方法案内URL",
        CVS_MSG     => "
上記URLのページを表示の上、画面の指示に従ってお支払いください。
"
    )
);
?>
