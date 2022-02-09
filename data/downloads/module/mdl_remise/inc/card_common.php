<?php
/**
 * ルミーズ決済モジュール（カード決済・入力チェッククラス）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version card_common.php,v 3.0
 *
 */

require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/errinfo.php';

/**
 * クレジットカード決済・入力チェッククラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version card_common,v2.1
 */
class card_common
{
    var $arrErr;

    /**
     * コンストラクタ
     *
     * @return void
     */
    function card_common()
    {
    }

    /**
     * 入力チェック (ダイレクト or ゲートウェイ時)
     *
     * @param array $arrPayment
     * @param bool $use_token
     *
     */
    // update start 2017/06/29
    //function ErrorCheck($arrPayment)
    function ErrorCheck($arrPayment, $use_token = false)
    // update end 2017/06/29
    {
        $objErrInfo = new errinfo();

        // add start 2017/06/29
        if (!$use_token) {
        // add end 2017/06/29

            // カード番号入力チェック
            if (!isset ($this->arrErr['card']) && (!is_numeric($_POST['card']) || strlen($_POST['card']) < 10)) {
                $this->arrErr['card'] = $objErrInfo->getErrCdInput('card');
            }

            // セキュリティコード入力チェック
            if (!isset ($this->arrErr['securitycodedata']) &&
                    (!is_numeric($_POST['securitycodedata']) || strlen($_POST['securitycodedata']) < 3 ) &&
                        $arrPayment[0]['securitycode'] == REMISE_OPTION_USE) {
                $this->arrErr['securitycodedata'] = $objErrInfo->getErrCdInput('securitycode');
            }

            // 有効期限入力チェック
            $now = date("y"). date("m");
            $expire = $_POST['expire_yy'] . $_POST['expire_mm'];
            if (!isset ($this->arrErr['expire_mm'])) {
                if ($_POST['expire_mm'] == "--") {
                    $this->arrErr['expire'] = $objErrInfo->getErrCdInput('expire');
                    $this->arrErr['expire_mm'] = "ERROR";
                }
                if ($_POST['expire_yy'] == "--") {
                    $this->arrErr['expire'] = $objErrInfo->getErrCdInput('expire');
                    $this->arrErr['expire_yy'] = "ERROR";
                }
                if ($_POST['expire_mm'] != "--" && $_POST['expire_yy'] != "--" && $now > $expire) {
                    $this->arrErr['expire'] = $objErrInfo->getErrCdInput('expire');
                    $this->arrErr['expire_mm'] = "ERROR";
                    $this->arrErr['expire_yy'] = "ERROR";
                }
            }

            // 名義人入力チェック
            if (!isset ($this->arrErr['name']) &&
                    (!preg_match('/^[a-zA-Z ]+$/', $_POST['name']))) {
                $this->arrErr['name'] = $objErrInfo->getErrCdInput('name');
            }

        // add start 2017/06/29
        }
        // add end 2017/06/29

        // 分割回数
        $this->ptimesErrorCheck();
    }

    /**
     * ペイクイック利用時の入力チェック
     *
     * @return void
     */
    function PayQuickErrorCheck()
    {
        $objErrInfo = new errinfo();

        // カード番号入力チェック
        if (!isset ($this->arrErr['card']) && $_POST["card"] != "") {
            $this->arrErr['card'] = $objErrInfo->getErrCdInput('payquick');
        }

        // セキュリティコード入力チェック
        if (!isset ($this->arrErr['securitycodedata']) && $_POST["securitycodedata"] != "") {
            $this->arrErr['securitycodedata'] = $objErrInfo->getErrCdInput('payquick');
        }

        // 有効期限入力チェック
        if (!isset ($this->arrErr['expire_mm'])) {
            if (Net_UserAgent_Mobile::isMobile()) {
                if ($_POST["expire_mm"] != "--") {
                    $this->arrErr['expire'] = $objErrInfo->getErrCdInput('payquick');
                    $this->arrErr['expire_mm'] = "ERROR";
                }
                if ($_POST["expire_yy"] != "--") {
                    $this->arrErr['expire'] = $objErrInfo->getErrCdInput('payquick');
                    $this->arrErr['expire_yy'] = "ERROR";
                }
            } else {
                if ($_POST["expire_mm"] != "" && $_POST["expire_mm"] != "--") {
                    $this->arrErr['expire'] = $objErrInfo->getErrCdInput('payquick');
                    $this->arrErr['expire_mm'] = "ERROR";
                }
                if ($_POST["expire_yy"] != "" && $_POST["expire_yy"] != "--") {
                    $this->arrErr['expire'] = $objErrInfo->getErrCdInput('payquick');
                    $this->arrErr['expire_yy'] = "ERROR";
                }
            }
        }

        // 名義人入力チェック
        if ($_POST["name"] != "") {
            $this->arrErr['name'] = $objErrInfo->getErrCdInput('payquick');
        }

        // 分割回数
        $this->ptimesErrorCheck();
    }

    /**
     * 入力の分割回数をチェックする
     *
     * @param void
     */
    function ptimesErrorCheck()
    {
        $objErrInfo = new errinfo();

        // 分割回数
        if ($_POST['METHOD'] == REMISE_PAYMENT_METHOD_DIVIDE && $_POST['PTIMES'] == "") {
            $this->arrErr['PTIMES'] = $objErrInfo->getErrCdInput('PTIMES');
        }
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
        $path = DATA_REALDIR . 'logs/remise_card.log';

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

    /**
     * エラー情報のセット
     *
     * @param string $val エラー情報
     */
    function setErr($val)
    {
        $this->arrErr = $val;
    }

    /**
     * エラー情報の取得
     *
     * @return array $arrErr エラー情報
     */
    function getErr()
    {
        return $this->arrErr;
    }
}
?>
