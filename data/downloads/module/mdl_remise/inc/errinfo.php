<?php
/**
 * ルミーズ決済モジュール（エラー情報表示クラス）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version errinfo.php,v 3.0
 *
 */

/**
 * エラー情報表示クラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version errinfo,v2.2
 */
class errinfo extends LC_Page_Ex
{
    // カード決済(web)
    var $_mtb_err_cd_input = array(
        'card'          => '※カード番号にて入力項目エラーが発生しました。<br>',
        'securitycode'  => '※セキュリティコードにて入力項目エラーが発生しました。<br>',
        'expire'        => '※有効期限にて入力項目エラーが発生しました。<br>',
        'name'          => '※名義人にて入力項目エラーが発生しました。<br>',
        'payquick'      => '※前回使用したカードをご利用にならない場合に入力してください。<br>',
        'PTIMES'        => '※分割回数にて入力項目エラーが発生しました。<br>',
    );
    // 結果通知・拡張セット 戻りコード
    var $_mtb_err_cd_xrcode = array(
        '5:1000'    => '結果通知トランザクションにおいて正常なステータスが取得できませんでした。',
        '5:2000'    => '結果通知トランザクションにおいて原因不明なエラーが発生しました。',
        '7:0001'    => '退会対象の定期購買会員情報が見つかりませんでした。',
        '7:0002'    => 'この注文は既に退会処理が完了しています。',
        '7:0003'    => '定期購買の退会処理中にエラーが発生しました。',
        '8:4003'    => 'メンテナンス中により受付できませんでした。',
        '8:5801'    => '取引停止中により受付できませんでした。',
        '8:5804'    => 'お取扱できないカードです。',
        '8:5805'    => 'お取扱できないカードです。',
        '8:5810'    => '売上対象のトランザクションが見つかりませんでした。',
        //'8:5812'   => 'キャンセル対象のトランザクションが見つかりませんでした。',
        '8:5812'    => '処理可能な期間を過ぎているため、処理できません。',
        '8:5819'    => 'このカード番号での送信は制限されました。',
        '8:5820'    => 'この取引は制限されました。既に決済が済んでいます。',
        '8:5821'    => '3-D Secure取引中に原因不明なエラーが発生しました。<br />
                        お客様には大変ご迷惑をおかけしますが、しばらく、時間を置いて、再度お手続きをお願い致します。',
        '8:5822'    => 'オーソリ保持期限を過ぎています。',
        '8:5823'    => '取消可能期限を過ぎています。',
        '8:5826'    => '同一リモートIPアドレスによる送信制限がかかっています。',
        '9:0000'    => '原因不明なエラーが発生しました。',
    );
    // マルチ決済(web)
    var $_mtb_err_cvs_input = array(
        'pay_csv'   => '※ご利用いただけるお支払先がありません。<br>',
    );
    // マルチ決済・戻りコード
    var $_mtb_err_cvs_xrcode = array(
        '1:0000'    => '提携サイトの一時的なサーバ負荷により受付できませんでした。',
        '2:0000'    => '提携サイトのメンテナンス中により受付できませんでした。',
        '8:4003'    => 'メンテナンス中により受付できませんでした。',
        '9:0000'    => '原因不明なエラーが発生しました。',
        '9:0001'    => '原因不明なエラーが発生しました。',
    );
    // カード決済(gateway)
    var $_mtb_err_cd_func = array(
        '801'   => '取引停止中により受付できませんでした。',                            // ルミーズ取引停止中
        '802'   => '送信データにおいて不正なデータが設定されている項目が存在します。',  // 認証エラー
        '803'   => '支払区分が不正です。',                                              // 支払区分不正
        '804'   => 'お取扱できないカードです。',                                        // カード番号不正
        '805'   => 'お取扱できないカードです。',                                        // 限度額オーバー
        '806'   => '原因不明なエラーが発生しました。',                                  // コンポーネントエラー
        '807'   => '原因不明なエラーが発生しました。',                                  // トランザクションエラー
        '809'   => '原因不明なエラーが発生しました。',                                  // トランザクションID不正
        '811'   => '原因不明なエラーが発生しました。',                                  // ジョブ受付エラー
        '813'   => '原因不明なエラーが発生しました。',                                  // コンポーネントエラー
        '814'   => '原因不明なエラーが発生しました。',                                  // コミット失敗
        '815'   => '原因不明なエラーが発生しました。',                                  // ロールバック失敗
        '816'   => '原因不明なエラーが発生しました。',                                  // セッションオーバー
        '817'   => '原因不明なエラーが発生しました。',                                  // サーバ指定エラー
        '818'   => '原因不明なエラーが発生しました。',                                  // 原因不明なエラー
        '819'   => 'このカード番号での送信は制限されました。',                          // 送信制限(カード番号)
        '820'   => 'この取引は制限されました。既に決済が済んでいます。',                // 送信制限(請求番号)
        '821'   => '3-D Secure取引中に原因不明なエラーが発生しました。<br />
                    お客様には大変ご迷惑をおかけしますが、しばらく、時間を置いて、再度お手続きをお願い致します。',  // 3D-Secureエラー
        '826'   => 'この取引は制限されました。既に決済が済んでいます。',                // 送信制限(IPアドレス)
    );
    // マルチ決済(gateway)
    var $_mtb_err_cvs_func = array(
        // webと共用
    );
    // 共通
    var $_mtb_err_common = array(
        'amount'    => '請求金額と支払い金額が違います。',
    );

    /**
     * カード決済・入力エラーメッセージ取得
     *
     * @param string $name 項目名
     * @return string $ret エラーメッセージ
     */
    function getErrCdInput($name)
    {
        if (empty($name)) return "";
        $ret = $this->_mtb_err_cd_input[$name];
        return $ret;
    }

    /**
     * カード決済・戻りコードメッセージ取得
     *
     * @param string $code 戻りコード
     * @return string $ret 戻りコードメッセージ
     */
    function getErrCdXRCode($code)
    {
        if (empty($code) || $code == "0:0000") return "";
        $ret = $this->_mtb_err_cd_xrcode[$code];
        if (empty($ret)) {
            $ret = $this->getErrOther($code);
            if (empty($ret)) {
                $ret = "クレジットカード決済においてエラーが発生しました。";
            }
        }
        $ret = "決済処理エラー：" . $ret . "(" . $code . ")";
        return $ret;
    }

    /**
     * マルチ決済・入力エラーメッセージ取得
     *
     * @param string $name 項目名
     * @return string $ret エラーメッセージ
     */
    function getErrCvsInput($name)
    {
        if (empty($name)) return "";
        $ret = $this->_mtb_err_cvs_input[$name];
        return $ret;
    }

    /**
     * マルチ決済・戻りコードメッセージ取得
     *
     * @param string $code 戻りコード
     * @return string $ret 戻りコードメッセージ
     */
    function getErrCvsXRCode($code)
    {
        if (empty($code) || $code == "0:0000") return "";
        $ret = $this->_mtb_err_cvs_xrcode[$code];
        if (empty($ret)) {
            $ret = $this->getErrOther($code);
        }
        $ret = "決済処理エラー：" . $ret . "(" . $code . ")";
        return $ret;
    }

    /**
     * カード決済・ゲートウェイ関数値エラーメッセージ取得
     *
     * @param string $code 関数値
     * @return string $ret 関数値エラーメッセージ
     */
    function getErrCdFunc($code)
    {
        if (empty($code)) return "";
        $ret = $this->_mtb_err_cd_func[$code];
        if (empty($ret)) {
            // プロパティ設定エラー
            if ($code >= "700" && $code <= "799") {
                $ret = "送信データにおいて不正なデータが設定されている項目が存在します。";
            } else {
                $ret = "クレジットカード決済においてエラーが発生しました。";
            }
        }
        $ret = "決済処理エラー：" . $ret . "(" . $code . ")";
        return $ret;
    }

    /**
     * マルチ決済・ゲートウェイ関数値エラーメッセージ取得
     *
     * @param string $code 関数値
     * @return string $ret 関数値エラーメッセージ
     */
    function getErrCvsFunc($code)
    {
        $ret = $this->_mtb_err_cvs_func[$code];
        return $ret;
    }

    /**
     * カード、マルチ決済共通メッセージ取得
     *
     * @param string $name 項目名
     * @return string $ret メッセージ
     */
    function getErrCommon($name)
    {
        $ret = $this->_mtb_err_common[$code];
        return $ret;
    }

    /**
     * カード、マルチ決済共通・その他メッセージ取得
     *
     * @param string $code 戻りコード
     * @return string $ret 戻りコードメッセージ
     */
    function getErrOther($code)
    {
        switch (substr($code, 0, 3)) {
            case "8:1":
                $ret = "送信データにおいて設定されていない項目が存在します。";
                break;
            case "8:2":
                $ret = "送信データにおいて桁不足もしくは桁あふれの項目が存在します。";
                break;
            case "8:3":
                $ret = "送信データにおいて不正なデータが設定されている項目が存在します。";
        }
        return $ret;
    }
}
