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

require_once CLASS_EX_REALDIR . 'page_extends/admin/order/LC_Page_Admin_Order_Ex.php';

/**
 * 受注メール管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Order_Mail extends LC_Page_Admin_Order_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'order/mail.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'index';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = 'メール配信';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrMAILTEMPLATE = $masterData->getMasterData('mtb_mail_template');
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
        $post = $_POST;

        // アップロードファイル情報の初期化
        $objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        $this->lfInitFile($objUpFile);
        $objUpFile->setHiddenFileList($post);

        //一括送信用の処理
        if (array_key_exists('mail_order_id', $post) and $post['mode'] == 'mail_select') {
            $post['order_id_array'] = implode(',', $post['mail_order_id']);
        } elseif (!array_key_exists('order_id_array', $post)) {
            $post['order_id_array'] = $post['order_id'];
        }

        //一括送信処理変数チェック(ここですべきかは課題)
        if (preg_match("/^[0-9|\,]*$/", $post['order_id_array'])) {
            $this->order_id_array = $post['order_id_array'];
        } else {
            //エラーで元に戻す
            SC_Response_Ex::sendRedirect(ADMIN_ORDER_URLPATH);
            SC_Response_Ex::actionExit();
        }

        //メール本文の確認例は初めの1受注とする
        if (!SC_Utils_Ex::isBlank($this->order_id_array)) {
            $order_id_array = explode(',', $this->order_id_array);
            $post['order_id'] = intval($order_id_array[0]);
            $this->order_id_count = count($order_id_array);
        }

        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);

        // POST値の取得
        $objFormParam->setParam($post);
        $objFormParam->convParam();
        $this->tpl_order_id = $objFormParam->getValue('order_id');

        // 検索パラメーターの引き継ぎ
        $this->arrSearchHidden = $objFormParam->getSearchArray();

        // 履歴を読み込むか
        $load_history = SC_Utils_Ex::sfIsInt($this->tpl_order_id);

        // パラメーター初期化
        $this->lfInitFormParam_UploadImage($objFormParam);
        $this->lfInitFormParam($objFormParam, $post);

        switch ($this->getMode()) {
            case 'confirm':
                $status = $this->confirm($objFormParam, $objUpFile);
                if ($status === true) {
                    $load_history = false;
                } else {
                    $this->arrErr = $status;
                }
                break;

            case 'send':
                $sendStatus = $this->doSend($objFormParam, $objUpFile);
                if ($sendStatus === true) {
                    SC_Response_Ex::sendRedirect(ADMIN_ORDER_URLPATH);
                    SC_Response_Ex::actionExit();
                }
                $this->arrErr = $sendStatus;
                break;

            case 'change':
                $objFormParam =  $this->changeData($objFormParam);
                break;

            // 画像のアップロード
            case 'upload_image':
            case 'delete_image':
                $arrForm = $objFormParam->getHashArray();
                switch ($this->getMode()) {
                    case 'upload_image':
                        // ファイルを一時ディレクトリにアップロード
                        $this->arrErr[$arrForm['image_key']] = $objUpFile->makeTempFile($arrForm['image_key'], IMAGE_RENAME);
                        if ($this->arrErr[$arrForm['image_key']] == '') {
                            // 縮小画像作成
                            // 縮小画像の作成は不要なのでコメントアウト
                            // $this->lfSetScaleImage($objUpFile, $arrForm['image_key']);
                        }
                        break;
                    case 'delete_image':
                        // ファイル削除
                        $this->lfDeleteTempFile($objUpFile, $arrForm['image_key']);
                        break;
                    default:
                        break;
                }
                break;

            case 'pre_edit':
            case 'mail_select':
            case 'return':
            default:
                break;
        }

        // 入力内容の引き継ぎ
        $this->arrForm = $objFormParam->getFormParamList();

        // 入力画面表示設定
        $this->arrForm['arrFile'] = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);

        // ページonload時のJavaScript設定
        $anchor_hash = $this->getAnchorHash($arrForm['image_key']);
        $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage($anchor_hash);

        $this->arrForm['arrHidden'] = $objUpFile->getHiddenFileList();


        if ($load_history) {
            $this->arrMailHistory = $this->getMailHistory($this->tpl_order_id);
        }
    }

    /**
     * 指定された注文番号のメール履歴を取得する。
     * @var int order_id
     */
    public function getMailHistory($order_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = 'send_date, subject, template_id, send_id';
        $where = 'order_id = ?';
        $objQuery->setOrder('send_date DESC');

        return $objQuery->select($col, 'dtb_mail_history', $where, array($order_id));
    }

    /**
     *
     * メールを送る。
     * @param SC_FormParam $objFormParam
     */
    public function doSend(&$objFormParam, $objUpFile)
    {
        $arrErr = $objFormParam->checkerror();

        // メールの送信
        if (count($arrErr) == 0) {
            // 注文受付メール(複数受注ID対応)
            $order_id_array = explode(',', $this->order_id_array);
            foreach ($order_id_array as $order_id) {
                $objMail = new SC_Helper_Mail_Ex();
                $objSendMail = $objMail->sfSendOrderMail2($order_id,
                $objFormParam->getValue('template_id'),
                $objFormParam->getValue('subject'),
                $objFormParam->getValue('header'),
                $objFormParam->getValue('footer'),
                true,
                $this->lfGetAttachFiles($objUpFile));
            }
            // TODO $SC_SendMail から送信がちゃんと出来たか確認できたら素敵。
            return true;
        }

        return $arrErr;
    }

    /**
     * 確認画面を表示する為の準備
     * @param SC_FormParam $objFormParam
     * @param SC_UploadFile $objFormParam
     */
    public function confirm(&$objFormParam, $objUpFile)
    {
        $arrErr = $objFormParam->checkerror();
        // メールの送信
        if (count($arrErr) == 0) {
            // 注文受付メール(送信なし)
            $objMail = new SC_Helper_Mail_Ex();
            $objSendMail = $objMail->sfSendOrderMail2(
                $objFormParam->getValue('order_id'),
                $objFormParam->getValue('template_id'),
                $objFormParam->getValue('subject'),
                $objFormParam->getValue('header'),
                $objFormParam->getValue('footer'),
                false,
                $this->lfGetAttachFiles($objUpFile));

            $this->tpl_subject = $objFormParam->getValue('subject');
            $this->tpl_body = mb_convert_encoding($objSendMail->body, CHAR_CODE, 'auto');
            $this->tpl_to = $objSendMail->tpl_to;
            $this->attach_files = $this->lfGetDispAttachFiles($objUpFile);
            $this->tpl_mainpage = 'order/mail_confirm.tpl';

            return true;
        }

        return $arrErr;
    }

    /**
     *
     * テンプレートの文言をフォームに入れる。
     * @param SC_FormParam $objFormParam
     */
    public function changeData(&$objFormParam)
    {
        $template_id = $objFormParam->getValue('template_id');

        // 未選択時
        if (strlen($template_id) === 0) {
            $mailTemplates = null;
        // 有効選択時
        } elseif (SC_Utils_Ex::sfIsInt($template_id)) {
            $objMailtemplate = new SC_Helper_Mailtemplate_Ex();
            $mailTemplates = $objMailtemplate->get($template_id);
        // 不正選択時
        } else {
            trigger_error('テンプレートの指定が不正。', E_USER_ERROR);
        }

        if (empty($mailTemplates)) {
            foreach (array('subject', 'header', 'footer') as $key) {
                $objFormParam->setValue($key, '');
            }
        } else {
            $objFormParam->setParam($mailTemplates);
        }

        return $objFormParam;
    }

    /**
     * パラメーター情報の初期化
     * @param SC_FormParam $objFormParam
     */
    public function lfInitParam(&$objFormParam)
    {
        // 検索条件のパラメーターを初期化
        parent::lfInitParam($objFormParam);
        $objFormParam->addParam('テンプレート', 'template_id', INT_LEN, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('メールタイトル', 'subject', STEXT_LEN, 'KVa', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'SPTAB_CHECK'));
        $objFormParam->addParam('ヘッダー', 'header', LTEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'));
        $objFormParam->addParam('フッター', 'footer', LTEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'));
    }

    /**
     * アップロードファイルパラメーター情報の初期化
     * - 画像ファイル用
     *
     * @param  object $objUpFile SC_UploadFileインスタンス
     * @return void
     */
    public function lfInitFile(&$objUpFile)
    {
        $objUpFile->addFile('添付ファイル1', 'attachment_image_1', array('jpg', 'gif', 'png'),IMAGE_SIZE, false, 320, 240);
        $objUpFile->addFile('添付ファイル2', 'attachment_image_2', array('jpg', 'gif', 'png'),IMAGE_SIZE, false, 320, 240);
        $objUpFile->addFile('添付ファイル3', 'attachment_image_3', array('jpg', 'gif', 'png'),IMAGE_SIZE, false, 320, 240);
    }

    /**
     * パラメーター情報の初期化
     * - 画像ファイルアップロードモード
     *
     * @param  object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    public function lfInitFormParam_UploadImage(&$objFormParam)
    {
        $objFormParam->addParam('image_key', 'image_key', '', '', array());
    }

    /**
     * 縮小した画像をセットする
     *
     * @param  object $objUpFile SC_UploadFileインスタンス
     * @param  string $image_key 画像ファイルキー
     * @return void
     */
    public function lfSetScaleImage(&$objUpFile, $image_key)
    {
        switch ($image_key) {
        case 'attachment_image_1':
            // 詳細メイン画像
            $this->lfMakeScaleImage($objUpFile, $image_key, 'attachment_image_1');
            break;
        case 'attachment_image_2':
            // 一覧メイン画像
            $this->lfMakeScaleImage($objUpFile, $image_key, 'attachment_image_2');
            break;
        case 'attachment_image_3':
            // 一覧メイン画像
            $this->lfMakeScaleImage($objUpFile, $image_key, 'attachment_image_3');
            break;
        default:
            break;
        }
    }

    /**
     * 縮小画像生成
     *
     * @param  object  $objUpFile SC_UploadFileインスタンス
     * @param  string  $from_key  元画像ファイルキー
     * @param  string  $to_key    縮小画像ファイルキー
     * @param  boolean $forced
     * @return void
     */
    public function lfMakeScaleImage(&$objUpFile, $from_key, $to_key, $forced = false)
    {
        $arrImageKey = array_flip($objUpFile->keyname);
        $from_path = '';

        if ($objUpFile->temp_file[$arrImageKey[$from_key]]) {
            $from_path = $objUpFile->temp_dir . $objUpFile->temp_file[$arrImageKey[$from_key]];
        } elseif ($objUpFile->save_file[$arrImageKey[$from_key]]) {
            $from_path = $objUpFile->save_dir . $objUpFile->save_file[$arrImageKey[$from_key]];
        }

        if (file_exists($from_path)) {
            // 生成先の画像サイズを取得
            $to_w = $objUpFile->width[$arrImageKey[$to_key]];
            $to_h = $objUpFile->height[$arrImageKey[$to_key]];

            if ($forced) {
                $objUpFile->save_file[$arrImageKey[$to_key]] = '';
            }

            if (empty($objUpFile->temp_file[$arrImageKey[$to_key]])
                && empty($objUpFile->save_file[$arrImageKey[$to_key]])
            ) {
                // リネームする際は、自動生成される画像名に一意となるように、Suffixを付ける
                $dst_file = $objUpFile->lfGetTmpImageName(IMAGE_RENAME, '', $objUpFile->temp_file[$arrImageKey[$from_key]]) . $this->lfGetAddSuffix($to_key);
                $path = $objUpFile->makeThumb($from_path, $to_w, $to_h, $dst_file);
                $objUpFile->temp_file[$arrImageKey[$to_key]] = basename($path);
            }
        }
    }

    /**
     * アップロードファイルパラメーター情報から削除
     * 一時ディレクトリに保存されている実ファイルも削除する
     *
     * @param  object $objUpFile SC_UploadFileインスタンス
     * @param  string $image_key 画像ファイルキー
     * @return void
     */
    public function lfDeleteTempFile(&$objUpFile, $image_key)
    {
        // TODO: SC_UploadFile::deleteFileの画像削除条件見直し要
        $arrTempFile = $objUpFile->temp_file;
        $arrKeyName = $objUpFile->keyname;

        foreach ($arrKeyName as $key => $keyname) {
            if ($keyname != $image_key) continue;

            if (!empty($arrTempFile[$key])) {
                $temp_file = $arrTempFile[$key];
                $arrTempFile[$key] = '';

                if (!in_array($temp_file, $arrTempFile)) {
                    $objUpFile->deleteFile($image_key);
                } else {
                    $objUpFile->temp_file[$key] = '';
                    $objUpFile->save_file[$key] = '';
                }
            } else {
                $objUpFile->temp_file[$key] = '';
                $objUpFile->save_file[$key] = '';
            }
        }
    }

    /**
     * アンカーハッシュ文字列を取得する
     * アンカーキーをサニタイジングする
     *
     * @param  string $anchor_key フォーム入力パラメーターで受け取ったアンカーキー
     * @return <type>
     */
    public function getAnchorHash($anchor_key)
    {
        if ($anchor_key != '') {
            return "location.hash='#" . htmlspecialchars($anchor_key) . "'";
        } else {
            return '';
        }
    }

    /**
     * ページonload用JavaScriptを取得する
     * - 入力画面
     *
     * @param  string $anchor_hash アンカー用ハッシュ文字列(省略可)
     * @return string ページonload用JavaScript
     */
    public function lfSetOnloadJavaScript_InputPage($anchor_hash = '')
    {
        return $anchor_hash;
    }

    /**
     * パラメーター情報の初期化
     *
     * @param  object $objFormParam SC_FormParamインスタンス
     * @param  array  $arrPost      $_POSTデータ
     * @return void
     */
    public function lfInitFormParam(&$objFormParam, $arrPost)
    {
        $objFormParam->addParam('save_attachment_image_1', 'save_attachment_image_1', '', '', array());
        $objFormParam->addParam('temp_attachment_image_1', 'temp_attachment_image_1', '', '', array());

        $objFormParam->addParam('save_attachment_image_2', 'save_attachment_image_2', '', '', array());
        $objFormParam->addParam('temp_attachment_image_2', 'temp_attachment_image_2', '', '', array());

        $objFormParam->addParam('save_attachment_image_3', 'save_attachment_image_3', '', '', array());
        $objFormParam->addParam('temp_attachment_image_3', 'temp_attachment_image_3', '', '', array());

        $objFormParam->setParam($arrPost);
        $objFormParam->convParam();
    }

    /**
     * リネームする際は、自動生成される画像名に一意となるように、Suffixを付ける
     *
     * @param  string $to_key
     * @return string
     */
    public function lfGetAddSuffix($to_key)
    {
        if (IMAGE_RENAME === true) return;

        // 自動生成される画像名
        $dist_name = '';
        switch ($to_key) {
          case 'attachment_image_1':
            $dist_name = '_at1';
            break;
          case 'attachment_image_2':
            $dist_name = '_at2';
            break;
          case 'attachment_image_3':
            $dist_name = '_at3';
            break;
          default:
            break;
        }

        return $dist_name;
    }

    public function lfGetAttachFiles($objUpFile) {
      $attach_files = array();
      $files =  $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);
      foreach( $files as $key=>$val ){
        $attach_files[] = $files[$key]['real_filepath'];
      }

      if (empty($attach_files)) {
        $attach_files = null;
      }
      return $attach_files;
    }

    public function lfGetDispAttachFiles($objUpFile) {
      $attach_files = array();
      $files =  $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);
      foreach( $files as $key=>$val ){
        $attach_files[] = $files[$key]['filepath'];
      }

      if (empty($attach_files)) {
        $attach_files = null;
      }
      return $attach_files;
    }
}
