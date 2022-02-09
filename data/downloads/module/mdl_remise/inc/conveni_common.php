<?php
/**
 * ルミーズ決済モジュール（マルチ決済・入力チェッククラス）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version conveni_common.php,v 3.0
 *
 */

/**
 * 配列作成
 *
 * @param string $name
 * @param string $value
 * @return array コンビニ用配列
 */
function lfSetConvMSG($name, $value)
{
    return array("name" => $name, "value" => $value);
}

/**
 * URLを生成する
 *
 * @param $val
 */
function getUrl($params = array(), $paramURL = MDL_REMISE_RETURL)
{
    $url = new Net_URL($paramURL);

    // 携帯ならセッションを付加する。
    if (Net_UserAgent_Mobile::isMobile()) {
        $url->addQueryString(session_name(), session_id());
    }

    foreach ($params as $key => $val) {
        $url->addQueryString($key, $val);
    }

    return $url->getURL();
}

/**
 * ログを出力する
 *
 * @param string $msg 出力メッセージ
 */
function printLog($msg, $raw = false)
{
    $path = DATA_REALDIR . 'logs/remise_conveni.log';

    if (!$raw && is_array($msg)) {
        $keys = array('CARD');
        foreach ($keys as $key) {
            if (isset($msg[$key])) {
                // $msg[$key] = ereg_replace(".", "*", $msg[$key]); // ishibashi
                $msg[$key] = preg_replace(".", "*", $msg[$key]);
            }
        }
        $msg = print_r($msg, true);
    }
    GC_Utils_Ex::gfPrintLog($msg, $path);
}
?>
