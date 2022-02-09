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

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
require_once(CLASS_PATH . "SC_Customer_Questionnaire_Pdf.php");//::KHS Add 2014.3.12

/**
 * 会員管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Customer extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'customer/index.tpl';
        $this->tpl_mainno = 'customer';
        $this->tpl_subno = 'index';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_maintitle = '会員管理';
        $this->tpl_subtitle = '会員マスター';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPref = $masterData->getMasterData('mtb_pref');
        $this->arrJob = $masterData->getMasterData('mtb_job');
        $this->arrJob['不明'] = '不明';
        $this->arrSex = $masterData->getMasterData('mtb_sex');
        $this->arrPageMax = $masterData->getMasterData('mtb_page_max');
        $this->arrStatus = $masterData->getMasterData('mtb_customer_status');
        $this->arrMagazineType = $masterData->getMasterData('mtb_magazine_type');

        // 日付プルダウン設定
        $objDate = new SC_Date_Ex();
        // 登録・更新日検索用
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrRegistYear = $objDate->getYear();
        // 生年月日検索用
        $objDate->setStartYear(BIRTH_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrBirthYear = $objDate->getYear();
        // 月日の設定
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();

        // カテゴリ一覧設定
        $objDb = new SC_Helper_DB_Ex();
        $this->arrCatList = $objDb->sfGetCategoryList();

        $this->httpCacheControl('nocache');
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター設定
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        // パラメーター読み込み
        $this->arrForm = $objFormParam->getFormParamList();
        // 検索ワードの引き継ぎ
        $this->arrHidden = $objFormParam->getSearchArray();

        // 入力パラメーターチェック
        $this->arrErr = $this->lfCheckError($objFormParam);
        if (!SC_Utils_Ex::isBlank($this->arrErr)) {
            return;
        }

        // モードによる処理切り替え
        switch ($this->getMode()) {
            case 'delete':
                $this->is_delete = $this->lfDoDeleteCustomer($objFormParam->getValue('edit_customer_id'));
                list($this->tpl_linemax, $this->arrData, $this->objNavi) = $this->lfDoSearch($objFormParam->getHashArray());
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                break;
            case 'resend_mail':
                $this->is_resendmail = $this->lfDoResendMail($objFormParam->getValue('edit_customer_id'));
                list($this->tpl_linemax, $this->arrData, $this->objNavi) = $this->lfDoSearch($objFormParam->getHashArray());
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                break;
            case 'search':
                list($this->tpl_linemax, $this->arrData, $this->objNavi) = $this->lfDoSearch($objFormParam->getHashArray());
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                break;
            case 'csv':

                $this->lfDoCSV($objFormParam->getHashArray());
                SC_Response_Ex::actionExit();
                break;
//::N00068 Add 20140114
            case 'rental_ticket_download':

                $this->lfDoRentalTicketDownload(array($_POST["download_customer_id"]));
                list($this->tpl_linemax, $this->arrData, $this->objNavi) = $this->lfDoSearch($objFormParam->getHashArray());
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                break;
//::N00068 end 20140114

            default:
                break;
        }

    }

    /**
     * パラメーター情報の初期化
     *
     * @param  SC_FormParam_Ex $objFormParam フォームパラメータークラス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        SC_Helper_Customer_Ex::sfSetSearchParam($objFormParam);
        $objFormParam->addParam('編集対象会員ID', 'edit_customer_id', INT_LEN, 'n', array('NUM_CHECK','MAX_LENGTH_CHECK'));
    }

    /**
     * エラーチェック
     *
     * @param  SC_FormParam_Ex $objFormParam フォームパラメータークラス
     * @return array エラー配列
     */
    public function lfCheckError(&$objFormParam)
    {
        return SC_Helper_Customer_Ex::sfCheckErrorSearchParam($objFormParam);
    }

    /**
     * 会員を削除する処理
     *
     * @param  integer $customer_id 会員ID
     * @return boolean true:成功 false:失敗
     */
    public function lfDoDeleteCustomer($customer_id)
    {
        return SC_Helper_Customer_Ex::delete($customer_id);
    }

    /**
     * 会員に登録メールを再送する処理
     *
     * @param  integer $customer_id 会員ID
     * @return boolean true:成功 false:失敗
     */
    public function lfDoResendMail($customer_id)
    {
        $arrData = SC_Helper_Customer_Ex::sfGetCustomerDataFromId($customer_id);
        if (SC_Utils_Ex::isBlank($arrData) or $arrData['del_flg'] == 1) {
            //対象となるデータが見つからない、または削除済み
            return false;
        }
        //仮登録メール再送
        $resend_flg = true; 
        // 登録メール再送
        $objHelperMail = new SC_Helper_Mail_Ex();
        $objHelperMail->setPage($this);
        $objHelperMail->sfSendRegistMail($arrData['secret_key'], $customer_id, null, $resend_flg);
        return true;
    }

    /**
     * 会員一覧を検索する処理
     *
     * @param  array  $arrParam 検索パラメーター連想配列
     * @return array( integer 全体件数, mixed 会員データ一覧配列, mixed SC_PageNaviオブジェクト)
     */
    public function lfDoSearch($arrParam)
    {
        return SC_Helper_Customer_Ex::sfGetSearchData($arrParam);
    }

    /**
     * 会員一覧CSVを検索してダウンロードする処理
     *
     * @param  array   $arrParam 検索パラメーター連想配列
     * @return boolean|string true:成功 false:失敗
     */
    public function lfDoCSV($arrParam)
    {
        $objSelect = new SC_CustomerList_Ex($arrParam, 'customer');
        $objCSV = new SC_Helper_CSV_Ex();

        $order = 'update_date DESC, customer_id DESC';

        list($where, $arrVal) = $objSelect->getWhere();

        return $objCSV->sfDownloadCsv('2', $where, $arrVal, $order, true);
    }
    
    /** BY KHS create 2013.3.12 
    */
    public function lfDoRentalTicketDownload($arrParam)
    {
        $objQuery=& SC_Query_Ex::getSingletonInstance();
        $objSelect = new SC_CustomerList_Ex($arrParam, 'customer');
        $objQuery->setorder("order_id asc");
        $arrRet = $objQuery->select("order_id", "dtb_order","customer_id = ?", $arrParam);
        $objCusQuepdf = new SC_Customer_Questionnaire_Pdf(0);// PDFのダウンロード形式（0:表示、1:ダウンロード）

        //会員登録してるが注文が一度も無いお客様がいるので、はじく。
        if (!empty($arrRet)) {
            $file_exists_chk_flg = FALSE;
            foreach ($arrRet as $key => $val) {
                $arrFileName = array();
                $arrFileName['customer_id'] = $_POST['download_customer_id'];
                $arrFileName['order_id'] = $val['order_id'];

                //ファイル名は5桁の顧客番号と注文番号なので、5桁未満の場合は0埋めする。
                $customer_val = str_pad($arrFileName['customer_id'], 5, "0", STR_PAD_LEFT);
                $order_val = str_pad($arrFileName['order_id'], 5, "0", STR_PAD_LEFT);
                //$pdfFileName = $customer_val."_".$order_val.".pdf";
                $pdfFileName = $order_val."_".$customer_val.".pdf";

                //ファイルが存在するかチェック
                if ( file_exists(DATA_REALDIR.'pdf/questionnaire/'.$pdfFileName)) {
                    $objCusQuepdf->setData($arrFileName,$pdfFileName);
                    $file_exists_chk_flg = TRUE;
                }
            }

            //レンタル票が1ファイルでもあれば生成する。
            if ($file_exists_chk_flg) {
                $objCusQuepdf->createPdf();
                exit;
            } else {
                $this->tpl_onload = "window.alert('このお客様のレンタル票はまだ一つもデータ化されていません。');";
            }

        } else {
            $this->tpl_onload = "window.alert('会員登録してるが注文が一つも無いお客様です。');";
        }

        /*
         // 行数の取得
         $linemax = $objQuery->getOne( $objSelect->getListCount(), $objSelect->arrVal);
         $this->tpl_linemax = $linemax;              // 何件が該当しました。表示用

         // ページ送りの取得
         $objNavi = new SC_PageNavi($this->arrHidden['search_pageno'],
         $linemax, $page_max,
         "fnCustomerPage", NAVI_PMAX);
         $startno = $objNavi->start_row;
         $this->arrPagenavi = $objNavi->arrPagenavi;
        */
    }
}
