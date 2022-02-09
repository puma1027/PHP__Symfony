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
 * 婚活ワンピ のページクラス.
 *
 * @package Page
 * @author  CHS.
 * @version $Id$
 */
class LC_Page_Admin_Contents_Amour_Onepiece extends LC_Page_Admin_Ex
{
	/**
	 * Page を初期化する.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->tpl_mainpage = 'contents/amour_onepiece.tpl';
		$this->tpl_mainno = 'contents';
		$this->tpl_subno = "amour_onepiece";
        $this->tpl_maintitle = 'コンテンツ管理';
		$this->tpl_subtitle = '婚活ワンピ';

		$this->selected_year = date("Y");
		$this->selected_month = date("n");
		$this->selected_day = date("j");
		$this->arrStaff = $this->lfGetStaffList();
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
		
		$objDate = new SC_Date_Ex(RELEASE_YEAR);

		// 日付プルダウン設定
		$this->arrYear = $objDate->getYear();
		$this->arrMonth = $objDate->getMonth();
		$this->arrDay = $objDate->getDay();

		$objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, AMOUR_IMAGE_DIR);
		$this->lfInitUpFile($objUpFile);

		$amour_id = $objFormParam->getValue('amour_id');

		switch ($this->getMode()) {
            // 登録/編集
            case 'edit':
				// Hiddenからのデータを引き継ぐ
				$this->lfSetHiddenFile($objFormParam, $objUpFile);

				$objFormParam->convParam();
				$this->arrErr = $objFormParam->checkError();
				// ファイル存在チェック
				$this->arrErr = array_merge((array)$this->arrErr, (array)$objUpFile->checkEXISTS("report_image"));

				$is_error = (!SC_Utils_Ex::isBlank($this->arrErr));
				if (!$is_error) {
					$result = $this->doConfirm($objFormParam, $objUpFile);
					if ($result !== false) {
                        // 完了メッセージ
                        $amour_id = $result;
                        $this->tpl_onload = "alert('登録が完了しました。');";

					} else {
						$this->tpl_onload = "window.alert('登録が失敗しました。');";
					}
					// Hiddenからのデータを引き継ぐ
					$objUpFile->setHiddenFileList(null);
					// POSTデータを引き継ぐ
				}
                $this->tpl_amour_id = $amour_id;
                break;
            // 削除
            case 'delete':
				if ($amour_id !== '') {
					if($this->doDelete($amour_id)) {
						SC_Response_Ex::reload();
					}
				}
                break;
			case 'upload_image':
				// Hiddenからのデータを引き継ぐ
				$this->lfSetHiddenFile($objFormParam, $objUpFile);
				// ファイル存在チェック
				$this->arrErr = array_merge($this->arrErr, $objUpFile->checkEXISTS($objFormParam->getValue('image_key')));
				// 画像保存処理
				$this->arrErr[$objFormParam->getValue('image_key')] = $objUpFile->makeTempFile($objFormParam->getValue('image_key'),IMAGE_RENAME);
				if(SC_Utils_Ex::isBlank($this->arrErr)) {
					$this->tpl_onload = "window.alert('ファイルが成功裏にアップロードされる。');";
				}
				break;
			case 'delete_image':
				// Hiddenからのデータを引き継ぐ
				$this->lfSetHiddenFile($objFormParam, $objUpFile);
				$objUpFile->deleteFile($objFormParam->getValue('image_key'));
				if($_POST['temp_staff_image']){
					@unlink(IMAGE_TEMP_REALDIR.$_POST['temp_staff_image']);
				}
				break;
			case 'select_product_detail':
				// Hiddenからのデータを引き継ぐ
				$this->lfSetHiddenFile($objFormParam, $objUpFile);
				
				// POST情報で上書き
				if (!SC_Utils_Ex::isBlank($objFormParam->getValue('tmp_product_id'))) {
					$this->lfGetProductDetail($objFormParam);
				}
				break;
			case 'del_recommend_product':
				// Hiddenからのデータを引き継ぐ
				$this->lfSetHiddenFile($objFormParam, $objUpFile);
				
				$no = $_POST['no'];
				if(!SC_Utils_Ex::isBlank($no)){
					$arrVal = array();
					$arrVal['recommend_product_id'.$no] = "";
					$arrVal['recommend_product_image'.$no] = "";
					$objFormParam->setParam($arrVal);
				}
				break;
			// 初期表示
			case 'pre_edit':
            default:
				// Hiddenからのデータを引き継ぐ
				$this->lfSetHiddenFile($objFormParam, $objUpFile);

				if ($amour_id !== '') {
					$result = $this->lfGetDetail($objFormParam, $amour_id);

					if ($result != false) {
						$objUpFile->setDBFileList(array('report_image' => $objFormParam->getValue('report_image')));
						$objUpFile->setDBFileList(array('report_image2' => $objFormParam->getValue('report_image2')));
					}
				}
				$time_count = $objFormParam->getValue('time_count');
				if(SC_Utils_Ex::isBlank($time_count)){
					$max_time_count = $this->lfGetMaxTimeCount();

					//$this->arrForm['time_count']=$max_time_count;
					$objFormParam->setParam(array('time_count'=>$max_time_count));
				}
				$this->arrForm = $objFormParam->getFormParamList();

                if (isset($_GET['msg']) && $_GET['msg'] == 'on') {
                    // 完了メッセージ
                    $this->tpl_onload = "alert('登録が完了しました。');";
                }
                    
                $this->tpl_amour_id = $amour_id;
				break;
        }
		
		$this->arrHidden = $objUpFile->getHiddenFileList();
		// 全データ取得
		$this->list_data = $this->lfGetAmourList();

		// Form用配列を渡す。
		$this->arrFile = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, AMOUR_IMAGE_URL);
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
		$objFormParam->addParam("婚活ワンピID", "amour_id", INT_LEN, "n", array('NUM_CHECK', "MAX_LENGTH_CHECK"));
			
		$objFormParam->addParam("回", "time_count", INT_LEN, "n", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
		$objFormParam->addParam("登場日(年)", "year", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), date("Y"));
		$objFormParam->addParam("登場日(月)", "month", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), date("n"));
		$objFormParam->addParam("登場日(日)", "day", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), date("j"));

		$objFormParam->addParam("公開／非公開 ", "show_flg", INT_LEN, "n", array('NUM_CHECK', "MAX_LENGTH_CHECK"));
		$objFormParam->addParam("タイトル", "report_title", MTEXT_LEN, "KV", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));

		$objFormParam->addParam("写真1", "report_image", MTEXT_LEN, "a", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("写真2", "report_image2", MTEXT_LEN, "a", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("Temp写真", "temp_report_image", MTEXT_LEN, "a", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("Temp写真2", "temp_report_image2", MTEXT_LEN, "a", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("Save写真", "save_report_image", MTEXT_LEN, "a", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("Save写真2", "save_report_image2", MTEXT_LEN, "a", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam('イメージキー', 'image_key', STEXT_LEN, '', array('GRAPH_CHECK', 'MAX_LENGTH_CHECK'));
		
		$objFormParam->addParam("商品ID", "tmp_product_id", INT_LEN, "n", array( "MAX_LENGTH_CHECK", "NUM_CHECK"));
		$objFormParam->addParam("商品コード", "report_product_id", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
		$objFormParam->addParam("商品コード", "report_product_code");
		$objFormParam->addParam("商品名", "report_product_name");
		$objFormParam->addParam("商品画像", "main_image");
		$objFormParam->addParam("商品一覧画像", "main_list_image");
		$objFormParam->addParam("規格1", "classcategory_id1");
		$objFormParam->addParam("規格2", "classcategory_id2");

		$objFormParam->addParam("レポート本文", "report_content", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK"));

		$objFormParam->addParam("作成社員", "create_staff_id", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
		$objFormParam->addParam("レビュー", "review_flg", INT_LEN, "n", array('NUM_CHECK', "MAX_LENGTH_CHECK"));
		$objFormParam->addParam("一言感想", "man_impression", MTEXT_LEN, "KV", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
		$objFormParam->addParam("オトコ目線レビュー本文", "man_review_content", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("まとめ", "summary", MTEXT_LEN, "KV", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));

		$objFormParam->addParam("おすすめ商品1", "recommend_product_id1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("おすすめ画像1", "recommend_product_image1");
		$objFormParam->addParam("おすすめ商品2", "recommend_product_id2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("おすすめ画像2", "recommend_product_image2");
		$objFormParam->addParam("おすすめ商品3", "recommend_product_id3", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("おすすめ画像3", "recommend_product_image3");
    }

    /**
     * 初期化を行う.
     *
     * @param  SC_UploadFile $objUpFile SC_UploadFileインスタンス
     * @return void
     */
	function lfInitUpFile(&$objUpFile)
	{
		$objUpFile->addFile("写真ファイル1", 'report_image', array('jpg', 'gif', 'png'), IMAGE_SIZE, true, NORMAL_IMAGE_WIDTH, NORMAL_IMAGE_HEIGHT);
		$objUpFile->addFile("写真ファイル2", 'report_image2', array('jpg', 'gif', 'png'), IMAGE_SIZE, true, NORMAL_IMAGE_WIDTH, NORMAL_IMAGE_HEIGHT);
	}

    /**
     * Hiddenからのデータを引き継ぐ.
     *
     * @param  SC_FormParam $objFormParam SC_FormParamインスタンス
     * @param  SC_UploadFile $objUpFile SC_UploadFileインスタンス
     * @return void
     */
	function lfSetHiddenFile(&$objFormParam, &$objUpFile)
	{
		$arrFileList = $objFormParam->getHashArray();
		$objUpFile->setHiddenFileList($arrFileList);
	}

    /**
     * 婚活の情報を取得する.
     *
     * @return array   婚活の配列
     */
	public function lfGetAmourList()
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$objQuery->setOrder('amour_id DESC');

        return $objQuery->select('*', 'dtb_amour_onepiece', 'del_flg<>?', array(1));
	}

    /**
     * 婚活の情報を取得する.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param  int $staff_id SC_FormParam 婚活ID
     * @return boolean
     */
	function lfGetDetail(&$objFormParam, $amour_id)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$sql = "select T.*,
                date_part('year',  T.appear_date   ) as year,
                date_part('month',  T.appear_date   ) as month ,
                date_part('day',  T.appear_date   ) as day ,
                T1.name as recommend_product_name1,T1.main_list_image as recommend_product_image1,
                T2.name as recommend_product_name2,T2.main_list_image as recommend_product_image2,
                T3.name as recommend_product_name3 ,T3.main_list_image as recommend_product_image3,
                TTS.staff_image
            from (
                Select D.*, P.name as report_product_name, C.product_code as report_product_code, P.main_list_image, P.main_image
                From dtb_amour_onepiece As D
                Inner Join dtb_products As P ON D.report_product_id=P.product_id
                Inner Join dtb_products_class As C ON D.report_product_id=C.product_id
                Where D.amour_id = ? and P.del_flg<>1
		    ) as T
            left join dtb_products as T1 on T1.product_id = T.recommend_product_id1
            left join dtb_products as T2 on T2.product_id = T.recommend_product_id2
            left join dtb_products as T3 on T3.product_id = T.recommend_product_id3
            left join dtb_staff_regist as TTS on TTS.staff_id = T.create_staff_id";

		$arrResult = $objQuery->getall($sql, array($amour_id));

		if(!SC_Utils_Ex::isBlank($arrResult[0])) {
			$objFormParam->setParam($arrResult[0]);
		} else {
			return false;
		}
		return true;
	}

    /**
     * 商品の情報を取得する.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * 
     * @return null
     */
	function lfGetProductDetail(&$objFormParam)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();

		$product_id = $objFormParam->getValue('tmp_product_id');
		$no = $_POST['no'];

		if (!empty($no) && SC_Utils_Ex::sfIsInt($no)) {
			$col = "product_id as recommend_product_id" . $no . ", name as recommend_product_name" . $no .", main_list_image as recommend_product_image" . $no;
		} else {
			$col = "product_id as report_product_id, name as report_product_name, product_code as report_product_code, main_list_image , main_image ";
		}

		$where = "product_id = ?";
		$arrRet = $objQuery->getall("select ".$col." from vw_products_nonclass where ".$where, array($product_id));

		if (!empty($arrRet[0])) {
			$objFormParam->setParam($arrRet[0]);
		}
	}

    /**
     * 回の最大値を取得する.
     *
     * @return 最大値
     */
	function lfGetMaxTimeCount()
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();

        return $objQuery->max('time_count', 'dtb_amour_onepiece', 'del_flg<>?', array(1)) + 1;
	}

	function lfSetNewsInfo($res){
		$objQuery = new SC_Query();
		$sql = 'SELECT dtb_news.news_id FROM dtb_amour_onepiece left join dtb_news on dtb_amour_onepiece.news_id = dtb_news.news_id WHERE amour_id =? and dtb_news.del_flg <>1 ';
		$news_id = $objQuery->getone($sql,array($res['amour_id']));

		if(empty($res['show_flg'])){
			if(!empty($news_id)){
				$objQuery->delete('dtb_news', "news_id = ?", array($news_id));
			}
			
			return 0;
		}
		
		if(!empty($news_id)){
			$sql = "UPDATE dtb_news SET news_date = ?, news_title = ?, creator_id = ?, update_date = NOW(),  news_url = ?, link_method = ?, news_comment = ? WHERE news_id = ?";
			$arrRegist = array( 
				$res['appear_date'],
				"第".$res['time_count']."回".$res['report_title'],
				$_SESSION['member_id'],
				SITE_URL.'user_data/amour_onepiece.php?amour_id='.$res['amour_id'],
				1,
				'',
				$news_id
			);

			$objQuery->query($sql, $arrRegist);
		}else{
			$news_id = $objQuery->nextval('dtb_news', 'news_id');
			//rankの最大+1を取得する
			$rank_max = $objQuery->getone("SELECT MAX(rank) + 1 FROM dtb_news WHERE del_flg = '0'");

			$sql = "INSERT INTO dtb_news (news_id,news_date, news_title, creator_id, news_url, link_method, news_comment, rank, create_date, update_date)
                        VALUES ( ?,?,?,?,?,?,?,?,now(),now())";
			$arrRegist = array( 
				$news_id,
				$res['appear_date'],
				"第".$res['time_count']."回".$res['report_title'],
				$_SESSION['member_id'],
				SITE_URL.'user_data/amour_onepiece.php?amour_id='.$res['amour_id'],
				1,
				'',
				$rank_max
			);

			$objQuery->query($sql, $arrRegist);

			// 最初の1件目の登録はrankにNULLが入るので対策
			$sql = "UPDATE dtb_news SET rank = 1 WHERE del_flg = 0 AND rank IS NULL";
			$objQuery->query($sql);
		}
		return $news_id;
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

        $table = 'dtb_amour_onepiece';

        // amour_id が空の場合は新規登録
        $is_new = SC_Utils_Ex::isBlank($objFormParam->getValue('amour_id'));echo($is_new."SSS");

        // 既存データの重複チェック
        if (!$is_new) {
			// 既存データの重複チェック
			$arrExists = $objQuery->select('*', $table, 'amour_id = ?', array($objFormParam->getValue('amour_id')));

			$is_new_image = (!SC_Utils_Ex::isBlank($objFormParam->getValue('temp_report_image')));
			if($is_new_image) {
				// 既存のファイルが存在する場合は削除しておく
				$exists_file = AMOUR_IMAGE_DIR . $arrExists[0]['report_image'];

				if (file_exists($exists_file)) {
					unlink($exists_file);
				}
			}
			$is_new_image2 = (!SC_Utils_Ex::isBlank($objFormParam->getValue('temp_report_image2')));
			if($is_new_image2) {
				// 既存のファイルが存在する場合は削除しておく
				$exists_file = AMOUR_IMAGE_DIR . $arrExists[0]['report_image2'];

				if (file_exists($exists_file)) {
					unlink($exists_file);
				}
			}
        }

        $arrValues = $objQuery->extractOnlyColsOf($table, $objFormParam->getHashArray());

		$arrUpFileVal = $objUpFile->getDBFileList();
		$arrValues['report_image'] = $arrUpFileVal['report_image'];
		$arrValues['report_image2'] = $arrUpFileVal['report_image2'];
		$arrValues['appear_date'] = $objFormParam->getValue('year') . '-' . $objFormParam->getValue('month') . '-' . $objFormParam->getValue('day');

        $arrValues['update_date'] = 'CURRENT_TIMESTAMP';

        // 新規登録
        if ($is_new || SC_Utils_Ex::isBlank($arrExists)) {
            $objQuery->setOrder('');
            $arrValues['amour_id'] = 1 + $objQuery->max('amour_id', $table);
            $arrValues['create_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert($table, $arrValues);
        }
        // 更新
        else {
			$objQuery->update($table, $arrValues, 'amour_id = ?', array($arrValues['amour_id']));
        }

		$objUpFile->moveTempFile();

        $objQuery->commit();

        return $arrValues['amour_id'];
    }
	
    /**
     * 社員の削除.
     *
     * @param  int $amour_id 社員ID
	 *
     * @return null
     */
	function doDelete($amour_id)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$sqlval['del_flg'] = '1';
		$objQuery->update('dtb_amour_onepiece', $sqlval, 'amour_id = ?', array($amour_id));

		return true;
	}

    /**
     * 社員の情報を取得する.
     *
     * @return array   社員の配列
     */
	function lfGetStaffList()
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$objQuery->setOrder('staff_id DESC');

        $staffs = $objQuery->select('*', 'dtb_staff_regist');

		// 結果を key => value 形式に格納
		$result = array();
		foreach ($staffs as $row) {
			$result[$row['staff_id']] = $row['staff_name'];
		}

		return $result;
	}
}
