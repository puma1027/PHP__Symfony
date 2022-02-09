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
 * 社員管理 のページクラス.
 *
 * @package Page
 * @author  CHS
 * @version $Id$
 */
class LC_Page_Admin_Contents_Staff_Register extends LC_Page_Admin_Ex
{
	/**
	 * Page を初期化する.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->tpl_mainpage = 'contents/staff_register.tpl';
		$this->tpl_mainno = 'contents';
		$this->tpl_subno = "staff_register";
        $this->tpl_maintitle = 'コンテンツ管理';
		$this->tpl_subtitle = '社員管理';
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
		$objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, STAFF_IMAGE_DIR);
		$this->lfInitUpFile($objUpFile);

		$staff_id = $objFormParam->getValue('staff_id');

		switch ($this->getMode()) {
            // 登録/編集
            case 'edit':
				$objUpFile->setHiddenFileList($_POST);
				$this->arrErr = $objFormParam->checkError();
				$this->arrErr = array_merge($this->arrErr, $objUpFile->checkEXISTS());
				$is_error = (!SC_Utils_Ex::isBlank($this->arrErr));
				if (!$is_error) {
					$result = $this->doConfirm($objFormParam, $objUpFile);
					if ($result !== false) {
						$arrPram = array(
							'staff_id' => $result,
							'mode' => 'pre_edit',
							'msg' => 'on',
						);

						SC_Response_Ex::reload($arrPram, true);
						SC_Response_Ex::actionExit();
					} else {
						$this->tpl_onload = "window.alert('登録が失敗しました。');";
					}
				}
                break;
            // 削除
            case 'delete':
				if ($staff_id !== '') {
					if($this->doDelete($staff_id)) {
						$arrPram = array(
							'msg' => 'on',
						);
						SC_Response_Ex::reload($arrPram, true);
					}
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
				if($_POST['temp_staff_image']){
					@unlink(IMAGE_TEMP_REALDIR.$_POST['temp_staff_image']);
				}
				break;
			// 初期表示
			case 'pre_edit':
            default:
				if ($staff_id !== '') {
					$result = $this->lfGetStaff($objFormParam, $staff_id);
					if ($result != false) {
						$objUpFile->setDBFileList(array('staff_image' => $objFormParam->getValue('staff_image')));
					}
				}
                if (isset($_GET['msg']) && $_GET['msg'] == 'on') {
                    // 完了メッセージ
                    $this->tpl_onload = "alert('登録が完了しました。');";
                }
                break;
        }

		$this->arrHidden = $objUpFile->getHiddenFileList();
		$this->staff_list = $this->lfGetStaffList();
		
		$this->arrFile = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, STAFF_IMAGE_URL);
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
		$objFormParam->addParam('社員ID', 'staff_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
		$objFormParam->addParam('イメージキー', 'image_key', STEXT_LEN, '', array('GRAPH_CHECK', 'MAX_LENGTH_CHECK'));
		$objFormParam->addParam('社員名', 'staff_name', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
		$objFormParam->addParam('社員画像', 'staff_image', STEXT_LEN, 'a', array('MAX_LENGTH_CHECK'));
		$objFormParam->addParam('臨時社員画像', 'temp_staff_image', STEXT_LEN, 'a', array('MAX_LENGTH_CHECK'));
		$objFormParam->addParam('保存社員画像', 'save_staff_image', STEXT_LEN, 'a', array('MAX_LENGTH_CHECK'));
    }

    /**
     * 初期化を行う.
     *
     * @param  SC_UploadFile $objUpFile SC_UploadFileインスタンス
     * @return void
     */
	function lfInitUpFile(&$objUpFile)
	{
		$objUpFile->addFile("画像ファイル", 'staff_image', array('jpg', 'gif', 'png'), IMAGE_SIZE, true,STAFF_IMAGE_WIDTH,STAFF_IMAGE_HEIGHT);
	}

    /**
     * 社員の情報を取得する.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param  int $staff_id SC_FormParam 社員ID
     * @return boolean
     */
	public function lfGetStaff(&$objFormParam, $staff_id)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();

        $staff = $objQuery->select('*', 'dtb_staff_regist', 'staff_id = ?', array($staff_id));
		if(!SC_Utils_Ex::isBlank($staff[0])) {
			$objFormParam->setParam($staff[0]);
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
	public function lfGetStaffList()
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$objQuery->setOrder('staff_id DESC');

        return $objQuery->select('*', 'dtb_staff_regist');
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
		// Hiddenからのデータを引き継ぐ
		$objUpFile->setHiddenFileList(array('temp_staff_image' => $objFormParam->getValue('temp_staff_image'),
												'save_staff_image' => $objFormParam->getValue('save_staff_image')));

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        $table = 'dtb_staff_regist';

        // staff_id が空の場合は新規登録
        $is_new = SC_Utils_Ex::isBlank($objFormParam->getValue('staff_id'));

        // 既存データの重複チェック
        if (!$is_new) {
            $arrExists = $objQuery->select('*', $table, 'staff_id = ?', array($objFormParam->getValue('staff_id')));

			$is_new_image = (!SC_Utils_Ex::isBlank($objFormParam->getValue('temp_staff_image')));
			if($is_new_image) {
				// 既存のファイルが存在する場合は削除しておく
				$exists_file = STAFF_IMAGE_DIR . $arrExists[0]['staff_image'];

				if (file_exists($exists_file)) {
					unlink($exists_file);
				}
			}
        }

        $arrValues = $objQuery->extractOnlyColsOf($table, $objFormParam->getHashArray());

		$arrUpFileVal = $objUpFile->getDBFileList();
		$arrValues['staff_image'] = $arrUpFileVal['staff_image'];

        $arrValues['update_date'] = 'CURRENT_TIMESTAMP';

        // 新規登録
        if ($is_new || SC_Utils_Ex::isBlank($arrExists)) {
            $objQuery->setOrder('');
            $arrValues['staff_id'] = 1 + $objQuery->max('staff_id', $table);
            $arrValues['create_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert($table, $arrValues);
        }
        // 更新
        else {
            $objQuery->update($table, $arrValues, 'staff_id = ?', array($arrValues['staff_id']));
        }

		$objUpFile->moveTempFile();

        $objQuery->commit();

        return $arrValues['staff_id'];
    }
	
    /**
     * 社員の削除.
     *
     * @param  int $staff_id 社員ID
	 *
     * @return null
     */
	function doDelete($staff_id)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$table = 'dtb_staff_regist';
        $result = $objQuery->getOne('select staff_image from dtb_staff_regist where staff_id = ?', array($staff_id));

		if($result){
			$res = @unlink(IMAGE_TEMP_REALDIR.$result);
			$res |= @unlink(STAFF_IMAGE_DIR.$result);
		}

		$objQuery->delete($table, 'staff_id = ?', array($staff_id));

		return $res;
	}
}
