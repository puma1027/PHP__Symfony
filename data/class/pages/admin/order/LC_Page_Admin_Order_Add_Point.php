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
require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");
require_once(CLASS_EX_REALDIR . "SC_Fpdf_Ex.php");
// 加算
define(MODE_ADD, "0");
// 減算
define(MODE_SUB, "1");
/**
 * 帳票出力 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Order_Add_Point extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/point_input.tpl';
        $this->tpl_subnavi = 'order/subnavi.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'point';
        $this->tpl_subtitle = '一括ポイント付与';
        $this->SHORTTEXT_MAX = STEXT_LEN;
        $this->MIDDLETEXT_MAX = MTEXT_LEN;
        $this->LONGTEXT_MAX = LTEXT_LEN;
        $this->arrType[0]  = "加算";
        $this->arrType[1]  = "減算";
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
//      $conn = new SC_DBConn();
        $objView = new SC_AdminView();
        $objDb = new SC_Helper_DB_Ex();
        $objSess = new SC_Session();
        $objSess->SetPageShowFlag(true); // 全てのユーザが閲覧可能(Add By RCHJ)

        // 認証可否の判定
        SC_Utils_Ex::sfIsSuccess($objSess);
        // 画面遷移の正当性チェック用にuniqidを埋め込む
        $objPage->tpl_uniqid = $objSess->getUniqId();
        // パラメータ管理クラス
        $this->objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam();
        $this->objFormParam->setParam($_POST);

        if (!isset($_POST['mode'])) $_POST['mode'] = "";
        if (!isset($arrRet)) $arrRet = array();
        switch($_POST['mode']) {
            case "confirm":
                // 入力値の変換
                $this->objFormParam->convParam();
                $this->arrErr = $this->lfCheckError($arrRet);
                $arrRet = $this->objFormParam->getHashArray();
                $this->arrForm = $arrRet;
                // エラー入力なし
                if (count($this->arrErr) == 0) {
                    $this->lfUpdatePoint($arrRet);
                    $text = "'ポイント一括処理が完了しました。'";
                    $this->tpl_onload = "window.alert(".$text.");";
                }
                break;
            default:
            // タイトルをセット
            $arrForm['title'] = "レンタル票";
            // 今日の日付をセット
            $arrForm['year']  = date("Y");
            $arrForm['month'] = date("m");
            $arrForm['day']   = date("d");

            // メッセージ
            $arrForm['msg1'] = '必ず返却期日の' . RETURN_TIME . 'までに、郵便ポストにご投函下さい。';
            $arrForm['msg2'] = 'コンビニのポストへの投函は遅延となりますのでご遠慮下さい。';
            $arrForm['msg3'] = 'この商品の返却期日…';
            // 顧客IDの表示
            if (is_array($_POST['point_customer_id'])) {
                sort($_POST['point_customer_id']);
                foreach ($_POST['point_customer_id'] AS $key=>$val) {
                    $arrForm['customer_id'][] = $val;
                }
            }
            $this->arrForm = $arrForm;
            break;
        }
        $objView->assignobj($this);
        $objView->display($this->tpl_mainpage);
    }


    /* パラメータ情報の初期化 */
    function lfInitParam() {
        $this->objFormParam->addParam("顧客番号", "customer_id", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("ポイント", "point_value", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("加算・減算", "type", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
    }

     /* 入力内容のチェック */
    function lfCheckError() {
        // 入力データを渡す。
        $arrRet =  $this->objFormParam->getHashArray();
        $objErr = new SC_CheckError($arrRet);
        $objErr->arrErr = $this->objFormParam->checkError();
        return $objErr->arrErr;
    }

     /* ポイントの更新処理 */
    function lfUpdatePoint($arrRet) {
        $objQuery = new SC_Query();
        $sqlval = array();
        $where = "customer_id = ?";
        foreach ( $arrRet['customer_id'] AS $key=>$val ) {
            // 対象顧客の現在所持ポイント取得
            $arrData = $objQuery->select("point", "dtb_customer", $where, array($val));
            $nPoint = $arrData[0]['point'];
            // 加算？減算?
            if ($arrRet['type'] == MODE_ADD) {
                $nPoint += $arrRet['point_value'];
            } else if ($arrRet['type'] == MODE_SUB) {
                $nPoint -= $arrRet['point_value'];
            }
            // マイナスは認めない
            if ($nPoint < 0) {
                $sqlval['point']  = 0;
            } else {
                $sqlval['point']  = $nPoint;
            }
            // トランザクション開始
            $objQuery->begin();
            // 顧客テーブルの更新
            $objQuery->update("dtb_customer", $sqlval, $where, array($val), "");
            // コミット
            $objQuery->commit();
        }
    }
}
?>
