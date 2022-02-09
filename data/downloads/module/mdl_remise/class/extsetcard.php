<?php
/**
 * ルミーズ決済モジュール（カード決済拡張セットクラス）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version extsetcard.php,v 3.0
 *
 */

if (ECCUBE_VERSION == '2.11.2' || ECCUBE_VERSION == '2.11.1' || ECCUBE_VERSION == '2.11.0') {
    require_once DATA_REALDIR . 'module/Request.php';
} else {
    require_once DATA_REALDIR . 'module/HTTP/Request.php';
}
require_once MODULE_REALDIR . 'mdl_remise/inc/errinfo.php';
require_once MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php';

/**
 * カード決済拡張セットクラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version extsetcard,v2.1
 */
class extsetcard extends LC_Page_Ex
{
    var $s_torihiki_no;
    var $job;
    var $tranid;
    var $total;
    var $tax;
    var $x_errcode;
    var $x_errinfo;
    var $x_errlevel;
    var $x_r_code;
    var $x_tranid;
    var $errmsg;

    /**
     * コンストラクタ
     *
     * @return void
     */
    function extsetcard()
    {
        $this->s_torihiki_no = "";
        $this->job = "";
        $this->tranid = "";
        $this->total = "";
        $this->tax = "";
        $this->x_errcode = "";
        $this->x_errinfo = "";
        $this->x_tranid = "";
        $this->x_r_code = "9:0000";
        $this->errmsg = "";
    }

    /**
     * Exec 実行メソッド
     *
     * @return string $ret 実行結果
     */
    function exec()
    {
        $objPayment= new LC_Page_Mdl_Remise_Config();
        $objErrInfo = new errinfo();
        $ret = -1;
        $postdata = "";

        // 決済設定情報を取得
        $arrPaymentSetInfo = $objPayment->getPaymentDB(PAY_REMISE_CREDIT);
        $request = new HTTP_Request($arrPaymentSetInfo[0]['extset_url']);
        $request->setMethod(HTTP_REQUEST_METHOD_POST);
        $request->addHeader('User-Agent', 'eccube mdl_remise v.' . MDL_REMISE_VERSION . '(' . $arrPaymentSetInfo[0]['memo01'] . ')');

        // POSTデータ
        $request->addPostData('SHOPCO',         $arrPaymentSetInfo[0]['memo01']);
        $request->addPostData('HOSTID',         $arrPaymentSetInfo[0]['extset_host_id']);
        $request->addPostData('S_TORIHIKI_NO',  $this->s_torihiki_no);
        $request->addPostData('JOB',            $this->job);
        $request->addPostData('TRANID',         $this->tranid);
        $request->addPostData('TOTAL',          $this->total);
        $request->addPostData('TAX',            $this->tax);
        $request->addPostData('RETURL',         'http://www.remise.jp/'); // wait画面よりinputタグを取得するため（ダミー）
        foreach ($request->_postData as $key => $value) {
            $postdata .= $key . "=" . $value . "&";
        }
        GC_Utils::gfDebugLog($postdata);

        $ret = $request->sendRequest();
        if (!PEAR::isError($ret)) {
            $response = $request->getResponseBody();
            $response = mb_convert_encoding($response, CHAR_CODE, "Shift-JIS");
            if (!empty($response)) {
                $this->x_errcode  = $this->getResponseValue($response, 'X-ERRCODE');
                $this->x_errinfo  = $this->getResponseValue($response, 'X-ERRINFO');
                $this->x_errlevel = $this->getResponseValue($response, 'X-ERRLEVEL');
                $this->x_tranid   = $this->getResponseValue($response, 'X-TRANID');
                $this->x_r_code   = $this->getResponseValue($response, 'X-R_CODE');
            }
        }
        GC_Utils::gfPrintLog("拡張セット応答結果：" . $response);

        if (empty($this->x_r_code)) {
            $this->x_r_code = "9:0000";
        }
        $this->errmsg = $objErrInfo->getErrCdXRCode($this->x_r_code);
        if ($this->x_r_code =="0:0000" && $this->x_errlevel != "0") {
           $this->errmsg = $objErrInfo->getErrCdXRCode($this->x_errcode);
        }
        if ($this->x_r_code == "0:0000" && $this->x_errlevel == "0") $ret = 0;

        return $ret;
    }

    /**
     * 請求番号セット
     *
     * @param string $value 請求番号
     * @return void
     */
    function set_s_torihiki_no($value)
    {
        $this->s_torihiki_no = $value;
    }
    /**
     * ジョブコードセット
     *
     * @param string $value ジョブコード
     * @return void
     */
    function set_job($value)
    {
        $this->job = $value;
    }
    /**
     * 拡張セットで処理するトランザクションＩＤセット
     *
     * @param string $value 処理元のトランザクションＩＤ
     * @return void
     */
    function set_tranid($value)
    {
        $this->tranid = $value;
    }
    /**
     * 合計金額セット
     *
     * @param string $value 合計金額
     * @return void
     */
    function set_total($value)
    {
        $this->total = $value;
    }
    /**
     * 税送料セット
     *
     * @param string $value 税送料
     * @return void
     */
    function set_tax($value)
    {
        $this->tax = $value;
    }
    /**
     * 拡張セット処理時・エラーコード取得
     *
     * @return void
     */
    function get_x_errcode()
    {
        return $this->x_errcode;
    }
    /**
     * 拡張セット処理時・エラー詳細コード取得
     *
     * @return void
     */
    function get_x_errinfo()
    {
        return $this->x_errinfo;
    }
    /**
     * 拡張セット処理時・エラーレベル取得
     *
     * @return void
     */
    function get_x_errlevel()
    {
        return $this->x_errlevel;
    }
    /**
     * 拡張セット処理時・エラーコード取得
     *
     * @return void
     */
    function get_x_tranid()
    {
        return $this->x_tranid;
    }
    /**
     * 拡張セット処理時・戻りコード取得
     *
     * @return void
     */
    function get_x_r_code()
    {
        return $this->x_r_code;
    }
    /**
     * エラーメッセージ取得
     *
     * @return void
     */
    function get_errmsg()
    {
        return $this->errmsg;
    }
    /**
     * 拡張セット・レスポンス値取得
     *
     * @param string $response 拡張セットレスポンスデータ
     * @param string $key タグ名
     * @return string $value 対象タグの値
     */
    function getResponseValue($response, $key)
    {
        $pattern = '/<input type="hidden" name="' . $key . '" value="(.+?)">/';
        preg_match($pattern, $response, $value);
        return $value[1];
    }
}
?>
