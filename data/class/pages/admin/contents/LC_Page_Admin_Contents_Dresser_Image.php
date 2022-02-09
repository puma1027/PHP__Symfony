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

/**
 * コンテンツ管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Contents_Dresser_Image extends LC_Page_Admin_Ex
{
	/**
	 * Page を初期化する.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->tpl_mainpage = 'contents/dresser_image.tpl';
		$this->tpl_mainno = 'contents';
		$this->tpl_subno = "dresser_image";
        $this->tpl_maintitle = 'コンテンツ管理';
		$this->tpl_subtitle = 'ベストドレッサー＞ベストドレッサー賞・画像管理';
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
		
		// フォーム操作クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();
		$objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, DRESSER_IMAGE_DIR );
		$this->lfInitUpFile($objUpFile);
		$image_id = $objFormParam->getValue('image_id');

		switch ($this->getMode()) {
            // 登録/編集
            case 'edit':
				// Hiddenからのデータを引き継ぐ
				$objUpFile->setHiddenFileList($_POST);
				$this->arrErr = $objFormParam->checkError();
				$this->arrErr = array_merge($this->arrErr, $objUpFile->checkEXISTS());

                if (!SC_Utils_Ex::isBlank($this->arrErr['image_id'])) {
                    trigger_error('', E_USER_ERROR);
                    return;
                }

                if (count($this->arrErr) <= 0) {

					$result = $this->doConfirm($objFormParam, $objUpFile);
					if ($result !== false) {
//						SC_Response_Ex::reload($arrPram, true);
//						SC_Response_Ex::actionExit();
						$result = $this->lfGetDresserImage($objFormParam, $result);
						if ($result != false) {
							$objUpFile->deleteKikakuFile("image_path");
							$objUpFile->setDBFileList(array('image_path' => $objFormParam->getValue('image_path')));
						}
						$this->tpl_onload = "window.alert('登録が完了しました。');";
					} else {
						$this->tpl_onload = "window.alert('登録が失敗しました。');";
					}
				}
                break;
			// 初期表示
			case 'pre_edit':
				if (!$is_error && $image_id !== '') {
					$result = $this->lfGetDresserImage($objFormParam, $image_id);
					if ($result != false) {
						$objUpFile->setDBFileList(array('image_path' => $objFormParam->getValue('image_path')));
					} else {
						//error
					}
				}
				break;
            // 削除
            case 'delete':
				if (!$is_error) {
					$this->doDelete($objFormParam);
					SC_Response_Ex::reload($arrPram, true);
					SC_Response_Ex::actionExit();
				}
                break;
			case 'upload_image':
				// ファイル存在チェック
				$this->arrErr = array_merge($this->arrErr, $objUpFile->checkEXISTS($objFormParam->getValue('image_key')));
				// 画像保存処理
				$this->arrErr[$objFormParam->getValue('image_key')] = $objUpFile->makeTempFile($objFormParam->getValue('image_key'),IMAGE_RENAME);
				if(SC_Utils_Ex::isBlank($this->arrErr)) {
					$this->tpl_onload = "window.alert('ファイルが成功裏にアップロードされる。');";
				}
				break;
			case 'delete_image':
				$objUpFile->deleteFile($objFormParam->getValue('image_key'));
				if($_POST['temp_image_path']){
					@unlink(IMAGE_TEMP_REALDIR.$_POST['temp_image_path']);
				}
				break;
            default:
                break;
        }
		$this->arrHidden = $objUpFile->getHiddenFileList();
		$this->image_list = $this->lfGetDresserImageList();
		$this->arrFile = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, DRESSER_IMAGE_URL);
		$this->arrForm = $objFormParam->getFormParamList();
	}

    /**
     * 初期化を行う.
     *
     * @param  SC_FormParam $objFormParam SC_FormParamインスタンス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
		$objFormParam->addParam('画像ID', 'image_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
		$objFormParam->addParam('イメージキー', 'image_key', STEXT_LEN, '', array('GRAPH_CHECK', 'MAX_LENGTH_CHECK'));
		$objFormParam->addParam('画像名', 'image_name', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
		$objFormParam->addParam('画像 ', 'image_path', STEXT_LEN, 'a', array('MAX_LENGTH_CHECK'));
		$objFormParam->addParam('臨時画像', 'temp_image_path', STEXT_LEN, 'a', array('MAX_LENGTH_CHECK'));
    }

    /**
     * 初期化を行う.
     *
     * @param  SC_UploadFile $objUpFile SC_UploadFileインスタンス
     * @return void
     */
	function lfInitUpFile(&$objUpFile)
	{
		$objUpFile->addFile("画像ファイル", 'image_path', array('jpg', 'gif', 'png'), IMAGE_SIZE, true,DRESSER_IMAGE_WIDTH,DRESSER_IMAGE_HEIGHT);
	}

    /**
     * 社員の情報を取得する.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param  int $image_id SC_FormParam 社員ID
     * @return boolean
     */
	public function lfGetDresserImage(&$objFormParam, $image_id)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();

        $dresser_image = $objQuery->select('*', 'dtb_dresser_image', 'image_id = ?', array($image_id));
		if(!SC_Utils_Ex::isBlank($dresser_image[0])) {
			$objFormParam->setParam($dresser_image[0]);
		} else {
			return false;
		}

		return true;
	}

    /**
     * 社員の情報を取得する.
     *
     * @return array   社員の配列
     */
	public function lfGetDresserImageList()
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$objQuery->setOrder('image_id DESC');

        return $objQuery->select('*', 'dtb_dresser_image');
	}

    /**
     * 社員の登録.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param  SC_UploadFile $objUpFile SC_UploadFile インスタンス
	 *
     * @return multiple 登録成功:社員ID, 失敗:FALSE
     */
    public function doConfirm(&$objFormParam, &$objUpFile)
    {

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        $table = 'dtb_dresser_image';

        // image_id が空の場合は新規登録
        $is_new = SC_Utils_Ex::isBlank($objFormParam->getValue('image_id'));
        // 既存データの重複チェック
        if (!$is_new) {
            $arrExists = $objQuery->select('*', $table, 'image_id = ?', array($objFormParam->getValue('image_id')));

/* Delete MGN_0320
            // 既存のファイルが存在する場合は削除しておく
            $exists_file = DRESSER_IMAGE_DIR  . $arrExists[0]['image_path'];
            if (file_exists($exists_file)) {
                unlink($exists_file);
            }
*/
        }
        $arrValues = $objQuery->extractOnlyColsOf($table, $objFormParam->getHashArray());
		$arrUpFileVal = $objUpFile->getDBFileList();

		$arrValues['image_path'] = $arrUpFileVal['image_path'];

        $arrValues['update_date'] = 'CURRENT_TIMESTAMP';
        // 新規登録
        if ($is_new || SC_Utils_Ex::isBlank($arrExists)) {
            $objQuery->setOrder('');
            $arrValues['image_id'] = 1 + $objQuery->max('image_id', $table);
            $arrValues['create_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert($table, $arrValues);
        }
        // 更新
        else {
            $objQuery->update($table, $arrValues, 'image_id = ?', array($arrValues['image_id']));
        }

		$objUpFile->moveTempFile();

        $objQuery->commit();

        return $arrValues['image_id'];
    }
	
    /**
     * 社員の削除.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
	 *
     * @return null
     */
	function doDelete(&$objFormParam)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$table = 'dtb_dresser_image';
        $result = $objQuery->getOne('select image_path from dtb_dresser_image where image_id = ?', array($objFormParam->getValue('image_id')));

		if($result){
			@unlink(IMAGE_TEMP_REALDIR.$result);
			@unlink(DRESSER_IMAGE_DIR .$result);
		}

		$objQuery->delete($table, 'image_id = ?', array($objFormParam->getValue('image_id')));
	}
}
