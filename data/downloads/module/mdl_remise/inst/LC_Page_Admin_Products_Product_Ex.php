<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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

require_once CLASS_REALDIR . 'pages/admin/products/LC_Page_Admin_Products_Product.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';

/**
 * 商品登録 のページクラス(拡張).
 *
 * LC_Page_Admin_Products_Product をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @author REMISE Corp.
 * @version $Id: LC_Page_Admin_Products_Product_Ex.php 21420 2012-01-22 19:49:37Z Seasoft $
 */
class LC_Page_Admin_Products_Product_Ex extends LC_Page_Admin_Products_Product
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $objFormParam = new SC_FormParam_Ex();
        $this->arrFirstInterval = array();
        $this->arrFirstInterval['--'] = "--";
        for ($i = 1; $i <= 12; $i++) {
            $this->arrFirstInterval[$i] = $i;
        }
        $this->arrNextdate = array();
        $this->arrNextdate['--'] = "--";
        for ($i = 1; $i <= 31; $i++) {
            $this->arrNextdate[$i] = $i;
        }
        $this->arrInterval = array();
        $this->arrInterval['--'] = "--";
        for ($i = 1; $i <= 12; $i++) {
            $this->arrInterval[$i] = $i;
        }
        $this->arrNotAllow = array();
        $this->arrNotAllow['--'] = "--";
        for ($i = 0; $i <= 12; $i++) {
            $this->arrNotAllow[$i] = $i;
        }
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    function action()
    {
        $objFormParam = new SC_FormParam_Ex();

        // アップロードファイル情報の初期化
        $objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        $this->lfInitFile($objUpFile);
        $objUpFile->setHiddenFileList($_POST);

        // ダウンロード販売ファイル情報の初期化
        $objDownFile = new SC_UploadFile_Ex(DOWN_TEMP_REALDIR, DOWN_SAVE_REALDIR);
        $this->lfInitDownFile($objDownFile);
        $objDownFile->setHiddenFileList($_POST);

        // 検索パラメーター引き継ぎ
        $this->arrSearchHidden = $this->lfGetSearchParam($_POST);

        $mode = $this->getMode();
        switch ($mode) {
            case 'pre_edit':
            case 'copy' :
                // パラメーター初期化(商品ID)
                $this->lfInitFormParam_PreEdit($objFormParam, $_POST);
                // エラーチェック
                $this->arrErr = $objFormParam->checkError();
                if (count($this->arrErr) > 0) {
                    trigger_error('', E_USER_ERROR);
                }

                // 商品ID取得
                $product_id = $objFormParam->getValue('product_id');
                // 商品データ取得
                $arrForm = $this->lfGetFormParam_PreEdit($objUpFile, $objDownFile, $product_id);
                if ($arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
                    $arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL ||
                    $_POST['plg_remiseautocharge_total'] != "") {
                    $this->lfInitFormParam_AC($objFormParam, $_POST);
                    $arrForm += $objFormParam->getHashArray();
                }
                // 複製の場合は、ダウンロード商品情報部分はコピーしない
                if ($mode == 'copy') {
                    // ダウンロード商品ファイル名をunset
                    $arrForm['down_filename'] = '';

                    // $objDownFile->setDBDownFile()でsetされたダウンロードファイル名をunset
                    unset($objDownFile->save_file[0]);
                }

                // ページ表示用パラメーター設定
                $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);

                // 商品複製の場合、画像ファイルコピー
                if ($mode == 'copy') {
                    $this->arrForm['copy_product_id'] = $this->arrForm['product_id'];
                    $this->arrForm['product_id'] = '';
                    // 画像ファイルのコピー
                    $this->lfCopyProductImageFiles($objUpFile);
                }

                // ページonload時のJavaScript設定
                $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage();
                break;

            case 'edit':
                // パラメーター初期化, 取得
                $this->lfInitFormParam($objFormParam, $_POST);
                $arrForm = $objFormParam->getHashArray();
                if ($arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
                    $arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL ||
                    $_POST['plg_remiseautocharge_total'] != "") {
                    $this->lfInitFormParam_AC($objFormParam, $_POST);
                    $arrForm += $objFormParam->getHashArray();
                }
                // エラーチェック
                $this->arrErr = $this->lfCheckError_Edit($objFormParam, $objUpFile, $objDownFile, $arrForm);
                if (count($this->arrErr) == 0) {
                    // 確認画面表示設定
                    $this->tpl_mainpage = 'products/confirm.tpl';
                    $this->arrCatList = $this->lfGetCategoryList_Edit();
                    $this->arrForm = $this->lfSetViewParam_ConfirmPage($objUpFile, $objDownFile, $arrForm);
                } else {
                    // 入力画面表示設定
                    $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);
                    // ページonload時のJavaScript設定
                    $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage();
                }
                break;

            case 'complete':
                // パラメーター初期化, 取得
                $this->lfInitFormParam($objFormParam, $_POST);
                if ($arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
                    $arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL ||
                    $_POST['plg_remiseautocharge_total'] != "") {
                    $this->lfInitFormParam_AC($objFormParam, $_POST);
                }
                $arrForm = $this->lfGetFormParam_Complete($objFormParam);
                // エラーチェック
                $this->arrErr = $this->lfCheckError_Edit($objFormParam, $objUpFile, $objDownFile, $arrForm);
                if (count($this->arrErr) == 0) {
                    // DBへデータ登録
                    $product_id = $this->lfRegistProduct($objUpFile, $objDownFile, $arrForm);

                    // 件数カウントバッチ実行
                    $objQuery =& SC_Query_Ex::getSingletonInstance();
                    $objDb = new SC_Helper_DB_Ex();
                    $objDb->sfCountCategory($objQuery);
                    $objDb->sfCountMaker($objQuery);

                    // ダウンロード商品の複製時に、ダウンロード商品用ファイルを
                    // 変更すると、複製元のファイルが削除されるのを回避。
                    if (!empty($arrForm['copy_product_id'])) {
                        $objDownFile->save_file = array();
                    }

                    // 一時ファイルを本番ディレクトリに移動する
                    $this->lfSaveUploadFiles($objUpFile, $objDownFile, $product_id);

                    $this->tpl_mainpage = 'products/complete.tpl';
                    $this->arrForm['product_id'] = $product_id;
                } else {
                    // 入力画面表示設定
                    $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);
                    // ページonload時のJavaScript設定
                    $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage();
                }
                break;

            // 画像のアップロード
            case 'upload_image':
            case 'delete_image':
                // パラメーター初期化
                $this->lfInitFormParam_UploadImage($objFormParam);
                $this->lfInitFormParam($objFormParam, $_POST);
                $arrForm = $objFormParam->getHashArray();
                if ($arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
                    $arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL ||
                    $_POST['plg_remiseautocharge_total'] != "") {
                    $this->lfInitFormParam_AC($objFormParam, $_POST);
                    $arrForm += $objFormParam->getHashArray();
                }

                switch ($mode) {
                    case 'upload_image':
                        // ファイルを一時ディレクトリにアップロード
                        $this->arrErr[$arrForm['image_key']] = $objUpFile->makeTempFile($arrForm['image_key'], IMAGE_RENAME);
                        if ($this->arrErr[$arrForm['image_key']] == '') {
                            // 縮小画像作成
                            $this->lfSetScaleImage($objUpFile, $arrForm['image_key']);
                        }
                        break;
                    case 'delete_image':
                        // ファイル削除
                        $this->lfDeleteTempFile($objUpFile, $arrForm['image_key']);
                        break;
                    default:
                        break;
                }

                // 入力画面表示設定
                $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);
                // ページonload時のJavaScript設定
                $anchor_hash = $this->getAnchorHash($arrForm['image_key']);
                $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage($anchor_hash);
                break;

            // ダウンロード商品ファイルアップロード
            case 'upload_down':
            case 'delete_down':
                // パラメーター初期化
                $this->lfInitFormParam_UploadDown($objFormParam);
                $this->lfInitFormParam($objFormParam, $_POST);
                $arrForm = $objFormParam->getHashArray();
                if ($arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
                    $arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL ||
                    $_POST['plg_remiseautocharge_total'] != "") {
                    $this->lfInitFormParam_AC($objFormParam, $_POST);
                    $arrForm += $objFormParam->getHashArray();
                }

                switch ($mode) {
                    case 'upload_down':
                        // ファイルを一時ディレクトリにアップロード
                        $this->arrErr[$arrForm['down_key']] = $objDownFile->makeTempDownFile();
                        break;
                    case 'delete_down':
                        // ファイル削除
                        $objDownFile->deleteFile($arrForm['down_key']);
                        break;
                    default:
                        break;
                }

                // 入力画面表示設定
                $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);
                // ページonload時のJavaScript設定
                $anchor_hash = $this->getAnchorHash($arrForm['down_key']);
                $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage($anchor_hash);
                break;

            // 関連商品選択
            case 'recommend_select' :
                // パラメーター初期化
                $this->lfInitFormParam_RecommendSelect($objFormParam);
                $this->lfInitFormParam($objFormParam, $_POST);
                $arrForm = $objFormParam->getHashArray();
                if ($arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
                    $arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL ||
                    $_POST['plg_remiseautocharge_total'] != "") {
                    $this->lfInitFormParam_AC($objFormParam, $_POST);
                    $arrForm += $objFormParam->getHashArray();
                }

                // 入力画面表示設定
                $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);

                // 選択された関連商品IDがすでに登録している関連商品と重複していないかチェック
                $this->lfCheckError_RecommendSelect($this->arrForm, $this->arrErr);

                // ページonload時のJavaScript設定
                $anchor_hash = $this->getAnchorHash($this->arrForm['anchor_key']);
                $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage($anchor_hash);
                break;

            // 確認ページからの戻り
            case 'confirm_return':
                // パラメーター初期化
                $this->lfInitFormParam($objFormParam, $_POST);
                $arrForm = $objFormParam->getHashArray();
                if ($arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
                    $arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
                    $this->lfInitFormParam_AC($objFormParam, $_POST);
                    $arrForm += $objFormParam->getHashArray();
                }
                // 入力画面表示設定
                $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);
                // ページonload時のJavaScript設定
                $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage();
                break;

            default:
                // 入力画面表示設定
                $arrForm = array();
                $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);
                // ページonload時のJavaScript設定
                $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage();
                break;
        }

        // 関連商品の読み込み
        $this->arrRecommend = $this->lfGetRecommendProducts($this->arrForm);
    }

    /**
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @param array $arrPost $_POSTデータ
     * @return void
     */
    function lfInitFormParam(&$objFormParam, $arrPost)
    {
        $objFormParam->addParam('商品ID',                           'product_id',                   INT_LEN,        'n',    array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品名',                           'name',                         STEXT_LEN,      'KVa',  array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品カテゴリ',                     'category_id',                  INT_LEN,        'n',    array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('公開・非公開',                     'status',                       INT_LEN,        'n',    array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品ステータス',                   'product_status',               INT_LEN,        'n',    array('NUM_CHECK', 'MAX_LENGTH_CHECK'));

        // 新規登録, 規格なし商品の編集の場合
        if (!$arrPost['has_product_class']) {
            $objFormParam->addParam('商品種別',                     'product_type_id',              INT_LEN,        'n',    array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('ダウンロード商品ファイル名',   'down_filename',                STEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('ダウンロード商品実ファイル名', 'down_realfilename',            MTEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('temp_down_file',               'temp_down_file',               '',             '',     array());
            $objFormParam->addParam('save_down_file',               'save_down_file',               '',             '',     array());
            $objFormParam->addParam('商品コード',                   'product_code',                 STEXT_LEN,      'KVna', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam(NORMAL_PRICE_TITLE,             'price01',                      PRICE_LEN,      'n',    array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam(SALE_PRICE_TITLE,               'price02',                      PRICE_LEN,      'n',    array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
            if (OPTION_PRODUCT_TAX_RULE && substr(ECCUBE_VERSION,0,4) != '2.12') {
                $objFormParam->addParam('消費税率',                 'tax_rate',                     PERCENTAGE_LEN, 'n',    array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
            }
            $objFormParam->addParam('在庫数',                       'stock',                        AMOUNT_LEN,     'n',    array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('在庫無制限',                   'stock_unlimited',              INT_LEN,        'n',    array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        }
        $objFormParam->addParam('商品送料',                         'deliv_fee',                    PRICE_LEN,      'n',    array('NUM_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ポイント付与率',                   'point_rate',                   PERCENTAGE_LEN, 'n',    array('EXIST_CHECK', 'NUM_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('発送日目安',                       'deliv_date_id',                INT_LEN,        'n',    array('NUM_CHECK'));
        $objFormParam->addParam('販売制限数',                       'sale_limit',                   AMOUNT_LEN,     'n',    array('SPTAB_CHECK', 'ZERO_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('メーカー',                         'maker_id',                     INT_LEN,        'n',    array('NUM_CHECK'));
        $objFormParam->addParam('メーカーURL',                      'comment1',                     URL_LEN,        'a',    array('SPTAB_CHECK', 'URL_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('検索ワード',                       'comment3',                     LLTEXT_LEN,     'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('備考欄(SHOP専用)',                 'note',                         LLTEXT_LEN,     'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('一覧-メインコメント',              'main_list_comment',            MTEXT_LEN,      'KVa',  array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('詳細-メインコメント',              'main_comment',                 LLTEXT_LEN,     'KVa',  array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('save_main_list_image',             'save_main_list_image',         '',             '',     array());
        $objFormParam->addParam('save_main_image',                  'save_main_image',              '',             '',     array());
        $objFormParam->addParam('save_main_large_image',            'save_main_large_image',        '',             '',     array());
        $objFormParam->addParam('temp_main_list_image',             'temp_main_list_image',         '',             '',     array());
        $objFormParam->addParam('temp_main_image',                  'temp_main_image',              '',             '',     array());
        $objFormParam->addParam('temp_main_large_image',            'temp_main_large_image',        '',             '',     array());

        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $objFormParam->addParam('詳細-サブタイトル' . $cnt,     'sub_title' . $cnt,             STEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('詳細-サブコメント' . $cnt,     'sub_comment' . $cnt,           LLTEXT_LEN,     'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('save_sub_image' . $cnt,        'save_sub_image' . $cnt,        '',             '',     array());
            $objFormParam->addParam('save_sub_large_image' . $cnt,  'save_sub_large_image' . $cnt,  '',             '',     array());
            $objFormParam->addParam('temp_sub_image' . $cnt,        'temp_sub_image' . $cnt,        '',             '',     array());
            $objFormParam->addParam('temp_sub_large_image' . $cnt,  'temp_sub_large_image' . $cnt,  '',             '',     array());
        }

        for ($cnt = 1; $cnt <= RECOMMEND_PRODUCT_MAX; $cnt++) {
            $objFormParam->addParam('関連商品コメント' . $cnt,      'recommend_comment' . $cnt,     LTEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('関連商品ID' . $cnt,            'recommend_id' . $cnt,          INT_LEN,        'n',    array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('recommend_delete' . $cnt,      'recommend_delete' . $cnt,      '',             'n',    array());
        }

        $objFormParam->addParam('商品ID',                           'copy_product_id',              INT_LEN,        'n',    array('NUM_CHECK', 'MAX_LENGTH_CHECK'));

        $objFormParam->addParam('has_product_class',                'has_product_class',            INT_LEN,        'n',    array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('product_class_id',                 'product_class_id',             INT_LEN,        'n',    array('NUM_CHECK', 'MAX_LENGTH_CHECK'));

        $objFormParam->setParam($arrPost);
        $objFormParam->convParam();
    }

    /**
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @param array $arrPost $_POSTデータ
     * @return void
     */
    function lfInitFormParam_AC(&$objFormParam, $arrPost)
    {
        $objFormParam->addParam("初回課金金額", "plg_remiseautocharge_total",               INT_LEN,    "KVa",  array('EXIST_CHECK', "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("初回課金間隔", "plg_remiseautocharge_first_interval",      INT_LEN,    "KVa",  array('EXIST_CHECK', "NUM_CHECK"));
        $objFormParam->addParam("次回課金日",   "plg_remiseautocharge_next_date",           INT_LEN,    "KVa",  array('EXIST_CHECK', "NUM_CHECK"));
        $objFormParam->addParam("決済間隔",     "plg_remiseautocharge_interval",            INT_LEN,    "KVa",  array('EXIST_CHECK', "NUM_CHECK"));
        $objFormParam->addParam("最低利用期間", "plg_remiseautocharge_refusal_not_allow",   INT_LEN,    "KVa",  array('EXIST_CHECK', "NUM_CHECK"));
        $objFormParam->setParam($arrPost);
        $objFormParam->convParam();
    }

    /**
     * フォーム入力パラメーターのエラーチェック
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @param object $objUpFile SC_UploadFileインスタンス
     * @param object $objDownFile SC_UploadFileインスタンス
     * @param array $arrForm フォーム入力パラメーター配列
     * @return array エラー情報を格納した連想配列
     */
    function lfCheckError_Edit(&$objFormParam, &$objUpFile, &$objDownFile, $arrForm)
    {
        $objErr = new SC_CheckError_Ex($arrForm);
        $arrErr = array();

        // 入力パラメーターチェック
        $arrErr = $objFormParam->checkError();

        // アップロードファイル必須チェック
        $arrErr = array_merge((array)$arrErr, (array)$objUpFile->checkEXISTS());

        // HTMLタグ許可チェック
        $objErr->doFunc(array('詳細-メインコメント', 'main_comment', $this->arrAllowedTag), array('HTML_TAG_CHECK'));
        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $objErr->doFunc(array('詳細-サブコメント' . $cnt, 'sub_comment' . $cnt, $this->arrAllowedTag), array('HTML_TAG_CHECK'));
        }

        // 規格情報がない商品の場合のチェック
        if ($arrForm['has_product_class'] != true) {
            // 在庫必須チェック(在庫無制限ではない場合)
            if ($arrForm['stock_unlimited'] != UNLIMITED_FLG_UNLIMITED) {
                $objErr->doFunc(array('在庫数', 'stock'), array('EXIST_CHECK'));
            }
            // ダウンロード商品ファイル必須チェック(ダウンロード商品の場合)
            if ($arrForm['product_type_id'] == PRODUCT_TYPE_DOWNLOAD || $arrForm['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
                $arrErr = array_merge((array)$arrErr, (array)$objDownFile->checkEXISTS());
                $objErr->doFunc(array('ダウンロード商品ファイル名', 'down_filename'), array('EXIST_CHECK'));
            }
        }
        $arrErr = array_merge((array)$arrErr, (array)$objErr->arrErr);
        return $arrErr;
    }

    /**
     * DBに商品データを登録する
     *
     * @param object $objUpFile SC_UploadFileインスタンス
     * @param object $objDownFile SC_UploadFileインスタンス
     * @param array $arrList フォーム入力パラメーター配列
     * @return integer 登録商品ID
     */
    function lfRegistProduct(&$objUpFile, &$objDownFile, $arrList)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();

        // 配列の添字を定義
        $checkArray = array('name', 'status',
                            'main_list_comment', 'main_comment',
                            'deliv_fee', 'comment1', 'comment2', 'comment3',
                            'comment4', 'comment5', 'comment6', 'main_list_comment',
                            'sale_limit', 'deliv_date_id', 'maker_id', 'note');
        $arrList = SC_Utils_Ex::arrayDefineIndexes($arrList, $checkArray);

        // INSERTする値を作成する。
        $sqlval['name']                 = $arrList['name'];
        $sqlval['status']               = $arrList['status'];
        $sqlval['main_list_comment']    = $arrList['main_list_comment'];
        $sqlval['main_comment']         = $arrList['main_comment'];
        $sqlval['comment1']             = $arrList['comment1'];
        $sqlval['comment2']             = $arrList['comment2'];
        $sqlval['comment3']             = $arrList['comment3'];
        $sqlval['comment4']             = $arrList['comment4'];
        $sqlval['comment5']             = $arrList['comment5'];
        $sqlval['comment6']             = $arrList['comment6'];
        $sqlval['main_list_comment']    = $arrList['main_list_comment'];
        $sqlval['deliv_date_id']        = $arrList['deliv_date_id'];
        $sqlval['maker_id']             = $arrList['maker_id'];
        $sqlval['note']                 = $arrList['note'];
        $sqlval['update_date']          = 'CURRENT_TIMESTAMP';
        $sqlval['creator_id']           = $_SESSION['member_id'];

        // 定期課金商品用設定
        if ($arrList['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
            $arrList['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL ||
            $arrList['plg_remiseautocharge_total'] != "") {
            $sqlval['plg_remiseautocharge_total']               = $arrList['plg_remiseautocharge_total'];
            $sqlval['plg_remiseautocharge_first_interval']      = $arrList['plg_remiseautocharge_first_interval'];
            $sqlval['plg_remiseautocharge_next_date']           = $arrList['plg_remiseautocharge_next_date'];
            $sqlval['plg_remiseautocharge_interval']            = $arrList['plg_remiseautocharge_interval'];
            $sqlval['plg_remiseautocharge_refusal_not_allow']   = $arrList['plg_remiseautocharge_refusal_not_allow'];
        }
        $arrRet = $objUpFile->getDBFileList();
        $sqlval = array_merge($sqlval, $arrRet);

        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $sqlval['sub_title' . $cnt]     = $arrList['sub_title' . $cnt];
            $sqlval['sub_comment' . $cnt]   = $arrList['sub_comment' . $cnt];
        }

        $objQuery->begin();

        // 新規登録(複製時を含む)
        if ($arrList['product_id'] == '') {
            $product_id = $objQuery->nextVal('dtb_products_product_id');
            $sqlval['product_id'] = $product_id;

            // INSERTの実行
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert('dtb_products', $sqlval);

            $arrList['product_id'] = $product_id;

            // カテゴリを更新
            $objDb->updateProductCategories($arrList['category_id'], $product_id);

            // 複製商品の場合には規格も複製する
            if ($arrList['copy_product_id'] != '' && SC_Utils_Ex::sfIsInt($arrList['copy_product_id'])) {
                if (!$arrList['has_product_class']) {
                    // 規格なしの場合、複製は価格等の入力が発生しているため、その内容で追加登録を行う
                    $this->lfCopyProductClass($arrList, $objQuery);
                } else {
                    // 規格がある場合の複製は複製元の内容で追加登録を行う
                    $dbFactory = SC_DB_DBFactory_Ex::getInstance();
                    $arrColList = $objQuery->listTableFields('dtb_products_class');
                    $arrColList_tmp = array_flip($arrColList);

                    // 複製しない列
                    unset($arrColList[$arrColList_tmp['product_class_id']]);    // 規格ID
                    unset($arrColList[$arrColList_tmp['product_id']]);          // 商品ID
                    unset($arrColList[$arrColList_tmp['create_date']]);

                    // 複製元商品の規格データ取得
                    $col = SC_Utils_Ex::sfGetCommaList($arrColList);
                    $table = 'dtb_products_class';
                    $where = 'product_id = ?';
                    $objQuery->setOrder('product_class_id');
                    $arrProductsClass = $objQuery->select($col, $table, $where, array($arrList['copy_product_id']));

                    // 規格データ登録
                    $objQuery =& SC_Query_Ex::getSingletonInstance();
                    foreach ($arrProductsClass as $arrData) {
                        $sqlval = $arrData;
                        $sqlval['product_class_id'] = $objQuery->nextVal('dtb_products_class_product_class_id');
                        $sqlval['product_id']       = $product_id;
                        $sqlval['create_date']      = 'CURRENT_TIMESTAMP';
                        $sqlval['update_date']      = 'CURRENT_TIMESTAMP';
                        $objQuery->insert($table, $sqlval);
                    }
                }
            }
        }
        // 更新
        else {
            $product_id = $arrList['product_id'];
            // 削除要求のあった既存ファイルの削除
            $arrRet = $this->lfGetProductData_FromDB($arrList['product_id']);
            // TODO: SC_UploadFile::deleteDBFileの画像削除条件見直し要
            $objImage = new SC_Image_Ex($objUpFile->temp_dir);
            $arrKeyName = $objUpFile->keyname;
            $arrSaveFile = $objUpFile->save_file;
            $arrImageKey = array();
            foreach ($arrKeyName as $key => $keyname) {
                if ($arrRet[$keyname] && !$arrSaveFile[$key]) {
                    $arrImageKey[] = $keyname;
                    $has_same_image = $this->lfHasSameProductImage($arrList['product_id'], $arrImageKey, $arrRet[$keyname]);
                    if (!$has_same_image) {
                        $objImage->deleteImage($arrRet[$keyname], $objUpFile->save_dir);
                    }
                }
            }
            $objDownFile->deleteDBDownFile($arrRet);
            // UPDATEの実行
            $where = 'product_id = ?';
            $objQuery->update('dtb_products', $sqlval, $where, array($product_id));

            // カテゴリを更新
            $objDb->updateProductCategories($arrList['category_id'], $product_id);
        }

        // 商品登録の時は規格を生成する。複製の場合は規格も複製されるのでこの処理は不要。
        if ($arrList['copy_product_id'] == '') {
            // 規格登録
            if ($objDb->sfHasProductClass($product_id)) {
                // 規格あり商品（商品規格テーブルのうち、商品登録フォームで設定するパラメーターのみ更新）
                $this->lfUpdateProductClass($arrList);
            } else {
                // 規格なし商品（商品規格テーブルの更新）
                $this->lfInsertDummyProductClass($arrList);
            }
        }

        // 定期課金商品について、購入制限を設定
        if ($arrList['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
            $arrList['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL ||
            $arrList['plg_remiseautocharge_total'] != "") {
            $arrList2['sale_limit']                             = '1';
            $arrList2['deliv_fee']                              = $arrList['deliv_fee'];
            $arrList2['point_rate']                             = $arrList['point_rate'];
            $arrList2['plg_remiseautocharge_total']             = $arrList['plg_remiseautocharge_total'];
            $arrList2['plg_remiseautocharge_first_interval']    = $arrList['plg_remiseautocharge_first_interval'];
            $arrList2['plg_remiseautocharge_next_date']         = $arrList['plg_remiseautocharge_next_date'];
            $arrList2['plg_remiseautocharge_interval']          = $arrList['plg_remiseautocharge_interval'];
            $arrList2['plg_remiseautocharge_refusal_not_allow'] = $arrList['plg_remiseautocharge_refusal_not_allow'];
            $where = 'product_id = ?';
            $objQuery->update('dtb_products_class', $arrList2, $where, array($arrList['product_id']));
        }

        // 商品ステータス設定
        $objProduct = new SC_Product_Ex();
        $objProduct->setProductStatus($product_id, $arrList['product_status']);

        // 税情報設定
        if (OPTION_PRODUCT_TAX_RULE && substr(ECCUBE_VERSION,0,4) != '2.12') {
            SC_Helper_TaxRule_Ex::setTaxRuleForProduct($arrList['tax_rate'], $arrList['product_id'], $arrList['product_class_id']);
        }

        // 関連商品登録
        $this->lfInsertRecommendProducts($objQuery, $arrList, $product_id);

        $objQuery->commit();
        return $product_id;
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        parent::destroy();
    }
}
?>
