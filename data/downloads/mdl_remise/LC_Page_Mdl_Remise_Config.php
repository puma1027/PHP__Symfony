<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
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

// {{{ requires
require_once(CLASS_PATH . "pages/LC_Page.php");
require_once(realpath(dirname( __FILE__)) . "/include.php");

/**
 * ペイジェント決済モジュールのページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mdl_Remise_Config extends LC_Page {
     var $objFormParam;
     var $arrErr;
     var $objQuery;
     var $module_name;
     var $module_title;

     /**
     * コンストラクタ
     *
     * @return void
     */
     function LC_Page_Mdl_Remise_Config() {
        $this->module_name = MDL_REMISE_CODE;
        $this->module_title = "ルミーズ決済モジュール";
        $this->objQuery = new SC_Query();
        $this->objSess = new SC_Session();
        $this->objFormParam = new SC_FormParam();
        $this->arrUpdateFile = array(
            array("src" => MODULE_REALDIR . $this->module_name . "/remise_recv.php",
                  "dst" => USER_REALDIR . 'remise_recv.php'),
            array("src" => MODULE_REALDIR . $this->module_name . "/load_payment_module_pc.php",
                  "dst" => HTML_REALDIR . 'shopping/load_payment_module.php'),
            array("src" => MODULE_REALDIR . $this->module_name . "/load_payment_module_pc.php",
                  "dst" => HTML_REALDIR . 'shopping/load_payment_module.php'),
            array("src" => MODULE_REALDIR . $this->module_name . "/load_payment_module_mobile.php",
                  "dst" => HTML_REALDIR . 'mobile/shopping/load_payment_module.php')
        );
     }

     /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = MODULE_REALDIR . $this->module_name. "/config.tpl";
        $this->tpl_subtitle = $this->module_title;
        $this->arrErr = array();
        global $arrPayment;
        $this->arrPayment = $arrPayment;
        global $arrCredit;
        $this->arrCredit = $arrCredit;
        // 不足しているカラムがあれば追加する。
        $this->updateTable();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_AdminView();
        $objQuery = new SC_Query();

        // パラメータ管理クラス
        $this->initParam();
        // POST値の取得
        $this->objFormParam->setParam($_POST);

        switch (isset($_POST['mode']) ? $_POST['mode'] : "") {
        case 'edit':
            // 入力エラー判定
            $this->arrErr = $this->checkError();

            // エラーなしの場合にはデータを更新
            if (count($this->arrErr) == 0) {
                // 支払い方法登録
                $this->setPaymentDB();
                // 設定情報登録
                $this->setConfig();
                if ($this->updateFile()) {
                    // javascript実行
                    $this->tpl_onload = 'alert("登録完了しました。\n基本情報＞支払方法設定より詳細設定をしてください。"); window.close();';
                } else {
                    // javascript実行
                    foreach($this->arrUpdateFile as $array) {
                        if(!is_writable($array['dst'])) {
                            $alert = $array['dst'] . "に書き込み権限を与えてください。";
                            $this->tpl_onload.= "alert(\"". $alert. "\");";
                        }
                    }
                }
            }
            break;
        case 'module_del':
            // 汎用項目の存在チェック
            $objDB = new SC_Helper_DB_Ex();
            if ($objDB->sfColumnExists("dtb_payment", "memo01")) {
                // 支払方法の削除フラグを立てる
                $arrDel = array('del_flg' => "1");
                $this->objQuery->update("dtb_payment", $arrDel, " module_code = ?", array($this->module_name));
            }
            break;
        default:
            // データのロード
            $arrConfig = $this->getConfig();
            $this->objFormParam->setParam($arrConfig);
            break;
        }

        $this->arrForm = $this->objFormParam->getFormParamList();

        $objView->assignobj($this);                    //変数をテンプレートにアサインする
        $objView->display($this->tpl_mainpage);        //テンプレートの出力
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
      *  パラメータ情報の初期化
      */
    function initParam() {
        $this->objFormParam->addParam("加盟店コード", "code", INT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("ホスト番号", "host_id", INT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("クレジット接続先URL(PC)", "credit_url", URL_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "URL_CHECK"));
        $this->objFormParam->addParam("クレジット接続先URL(モバイル)", "mobile_credit_url", "KVa", array("MAX_LENGTH_CHECK", "URL_CHECK"));
        $this->objFormParam->addParam("支払い方法", "credit_method", "", "", array("EXIST_CHECK"));
        $this->objFormParam->addParam("オプション", "payment");
        $this->objFormParam->addParam("コンビニ接続先URL(PC)", "convenience_url", URL_LEN, "KVa", array("MAX_LENGTH_CHECK", "URL_CHECK"));
        $this->objFormParam->addParam("コンビニ接続先URL(モバイル)", "mobile_convenience_url", "KVa", array("MAX_LENGTH_CHECK", "URL_CHECK"));
    }

    /**
     * エラーチェック
     */
    function checkError(){
        $arrErr = $this->objFormParam->checkError();

        // 利用コンビニ
        if (isset($_POST["payment"])) {
            if ($_POST["convenience_url"] == "" && $_POST["mobile_convenience_url"] == "") {
                $arrErr["convenience_url"] = "コンビニ接続先URL(PC)またはコンビニ接続先URL(モバイル)が入力されていません。<br />";
            }
        }

        return $arrErr;
    }

    /**
     * 設定を保存
     */
    function setConfig() {
        $sqlval = array();
        $arrConfig = $this->objFormParam->getHashArray();
        $sqlval['sub_data'] = serialize($arrConfig);
        $this->objQuery->update("dtb_module", $sqlval, "module_code = ?", array($this->module_name));
    }

    /**
     * 設定を取得
     */
    function getConfig() {
        $arrRet = $this->objQuery->select("sub_data", "dtb_module", "module_code = ?", array($this->module_name));
        $arrConfig = unserialize($arrRet[0]['sub_data']);
        return $arrConfig;
    }

    /**
     * 支払方法DBからデータを取得
     */
    function getPaymentDB($type){
        $arrRet = array();
        $sql = "SELECT module_code, memo01, memo02, memo03, memo04, memo05, memo06, memo07, memo08, memo09, memo10
                FROM dtb_payment WHERE module_code = ? AND memo03 = ?";
        $arrRet = $this->objQuery->getall($sql, array($this->module_name,(string)$type));
        return $arrRet;
    }

    /**
     * データの更新処理
     */
    function setPaymentDB(){
        // 関連する支払方法の削除フラグを立てる
        $arrDel = array('del_flg' => "1");
        $this->objQuery->update("dtb_payment", $arrDel, " module_code = ?", array($this->module_name));

        $arrEntry = array('1');
        if (count($_POST["payment"]) > 0) {
            $arrEntry[] = '2';
        }

        // データ登録
        foreach ($arrEntry as $key => $val) {
            // クレジット登録
            if ($val == PAY_REMISE_CREDIT) {
                // 支払い方法にチェックが入っている場合は、ハイフン区切りに編集する
                $convCnt = count($_POST["credit_method"]);
                if ($convCnt > 0) {
                    $credit_method = $_POST["credit_method"][0];
                    for ($i = 1 ; $i < $convCnt ; $i++) {
                        $credit_method .= "-" . $_POST["credit_method"][$i];
                    }
                }
                $arrData = array(
                    "payment_method" => "remiseクレジット"
                    ,"module_path" => MODULE_PATH . "mdl_remise/remise_card.php"
                    ,"charge_flg" => "2"
                    ,"upper_rule" => REMISE_CREDIT_UPPER
                    ,"upper_rule_max" => REMISE_CREDIT_UPPER
                    ,"memo04" => $_POST["credit_url"]
                    ,"memo06" => $_POST["mobile_credit_url"]
                    ,"memo08" => $credit_method
                    ,"memo09" => REMISE_PAYMENT_DIVIDE_MAX
                );
            }

            // コンビニ登録
            if ($val == PAY_REMISE_CONVENI) {
                $arrData = array(
                    "payment_method" => "remiseコンビニ"
                    ,"module_path" => MODULE_PATH . "mdl_remise/remise_conveni.php"
                    ,"charge_flg" => "1"
                    ,"rule" => REMISE_CONVENIENCE_BOTTOM
                    ,"rule_min" => REMISE_CONVENIENCE_BOTTOM
                    ,"upper_rule" => REMISE_CONVENIENCE_UPPER
                    ,"upper_rule_max" => REMISE_CONVENIENCE_UPPER
                    ,"memo05" => $_POST["convenience_url"]
                    ,"memo07" => $_POST["mobile_convenience_url"]
                );
            }

            $arrData['fix'] = 3;
            $arrData['creator_id'] = $this->objSess->member_id;
            $arrData['create_date'] = "now()";
            $arrData['update_date'] = "now()";
            $arrData['module_code'] = $this->module_name;
            $arrData['memo01'] = $_POST["code"];
            $arrData['memo02'] = $_POST["host_id"];
            $arrData['memo03'] = $val;
            $arrData['del_flg'] = "0";

            // 支払方法データを取得
            $arrPayment = $this->getPaymentDB($val);
            // 支払方法データが存在すればUPDATE
            if (count($arrPayment) > 0) {
                $this->objQuery->update("dtb_payment", $arrData, "module_code = ? AND memo03 = ?", array($this->module_name,(string)$val));
            // 支払方法データが無ければINSERT
            } else {
                // ランクの最大値を取得
                $max_rank = $this->objQuery->getone("SELECT max(rank) FROM dtb_payment");
                $arrData["rank"] = $max_rank + 1;
                $this->objQuery->insert("dtb_payment", $arrData);
            }
        }
    }

    /**
     * テーブルを更新
     */
    function updateTable(){
        $objDB = new SC_Helper_DB_Ex();
        $objDB->sfColumnExists(
            'dtb_payment', 'module_code', 'text', "", $add = true
        );
    }

    /**
     * ファイルをコピーする
     *
     * @return boolean
     */
    function updateFile() {
        foreach($this->arrUpdateFile as $file) {
            $dst_file = $file['dst'];
            $src_file = $file['src'];
            // ファイルがない、またはファイルはあるが異なる場合
            if(!file_exists($dst_file) || sha1_file($src_file) != sha1_file($dst_file)) {
                if(is_writable($dst_file) || is_writable(dirname($dst_file))) {
                    copy($src_file, $dst_file);
                } else {
                    return false;
                }
            }
        }
        return true;
    }
    
}
?>
