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
class LC_Page_Admin_Contents_Trouble_Dress extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'contents/trouble_recommended_dress.tpl';
        $this->tpl_subno = 'trouble';
		$this->tpl_subno_dresser = "trouble";
        $this->tpl_mainno = 'contents';
        $this->tpl_maintitle = 'コンテンツ管理';
        $this->tpl_subtitle = '悩み別おすすめドレス＞悩み別おすすめドレス管理';

        //機能リスト
        $this->arrAge        = array('1'=>'10代','2'=>'20前半','3'=>'20代後半','4'=>'30代前半','5'=>'30代後半','6'=>'40代');
        $this->arrScene      = array('1'=>'シーン１','2'=>'シーン２','3'=>'シーン３','4'=>'シーン４','5'=>'シーン５','6'=>'シーン６');
        $this->arrTrouble    = array('1'=>'お悩み１','2'=>'お悩み２','3'=>'お悩み３','4'=>'お悩み４','5'=>'お悩み５','6'=>'お悩み６');
        $this->arrHairColor  = array('1'=>'髪の色１','2'=>'髪の色２','3'=>'髪の色３','4'=>'髪の色４','5'=>'髪の色５','6'=>'髪の色６');
        $this->arrHairLength = array('1'=>'髪の長１','2'=>'髪の長２','3'=>'髪の長３','4'=>'髪の長４','5'=>'髪の長５','6'=>'髪の長６');
        $this->arrSkinColor  = array('1'=>'肌の色１','2'=>'肌の色２','3'=>'肌の色３','4'=>'肌の色４','5'=>'肌の色５','6'=>'肌の色６');
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

		$objDb = new SC_Helper_DB_Ex();

		SC_Utils_Ex::sfIsSuccess(new SC_Session());

		// パラメータ管理クラス
		$this->objFormParam = new SC_FormParam();
		// パラメータ情報の初期化
		$this->lfInitParam();

        // 商品アイコン項目の変換
        if (!isset($_POST['age_list'])) $_POST['age_list'] = "";
        $_POST['age_list'] = SC_Utils_Ex::sfMergeCheckBoxes($_POST['age_list'], count($this->arrAge));
        if (!isset($_POST['scene_list'])) $_POST['scene_list'] = "";
        $_POST['scene_list'] = SC_Utils_Ex::sfMergeCheckBoxes($_POST['scene_list'], count($this->arrScene));
        if (!isset($_POST['trouble_list'])) $_POST['trouble_list'] = "";
        $_POST['trouble_list'] = SC_Utils_Ex::sfMergeCheckBoxes($_POST['trouble_list'], count($this->arrTrouble));
        if (!isset($_POST['haircolor_list'])) $_POST['haircolor_list'] = "";
        $_POST['haircolor_list'] = SC_Utils_Ex::sfMergeCheckBoxes($_POST['haircolor_list'], count($this->arrHairColor));
        if (!isset($_POST['hairlength_list'])) $_POST['hairlength_list'] = "";
        $_POST['hairlength_list'] = SC_Utils_Ex::sfMergeCheckBoxes($_POST['hairlength_list'], count($this->arrHairLength));
        if (!isset($_POST['skincolor_list'])) $_POST['skincolor_list'] = "";
        $_POST['skincolor_list'] = SC_Utils_Ex::sfMergeCheckBoxes($_POST['skincolor_list'], count($this->arrSkinColor));

		// POST情報で上書き
		$this->objFormParam->setParam($_POST);

		if (!isset($_POST['mode'])) $_POST['mode'] = "";
		switch ($_POST['mode']) {
            //おすすめ商品削除時
			case 'del_recommend_product':
				$no = $_POST['no'];
				if(!empty($no)){
					$arrVal = array();
					$arrVal['recommend_product_id'.$no] = "";
					$arrVal['recommend_product_image'.$no] = "";
					$this->objFormParam->setParam($arrVal);
				}
				
				$this->arrForm = $this->objFormParam->getFormParamList();
				
				break;

            //おすすめ商品追加時
			case 'select_product_detail':
				// POST情報で上書き
				if (!empty($_POST['tmp_product_id'])) {
					$arrVal = $this->lfGetProductDetail($_POST['tmp_product_id'], $_POST['no'], $_POST['type']);
					$this->objFormParam->setParam($arrVal);
				}
				$this->arrForm = $this->objFormParam->getFormParamList();
				break;

            //バックナンバーの[編集]ボタン押下時
			case 'pre_edit':
				if (SC_Utils_Ex::sfIsInt($_POST['trouble_recommended_dress_id'])) {
					// パラメータ管理クラス
					$this->objFormParam = new SC_FormParam();
					// パラメータ情報の初期化
					$this->lfInitParam();
					
					$res = $this->lfGetRecommendDetail($_POST['trouble_recommended_dress_id']);

					$this->objFormParam->setParam($res);
					$this->arrForm = $this->objFormParam->getFormParamList();

                    //検索条件データの復元処理
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['age_list']['value'], "age_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['scene_list']['value'], "scene_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['trouble_list']['value'], "trouble_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['haircolor_list']['value'], "haircolor_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['hairlength_list']['value'], "hairlength_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['skincolor_list']['value'], "skincolor_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    // 商品ステータスの分割読込
                    if (isset($this->arrForm['age_list']['value'])) {
                        $this->arrForm['age_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['age_list']['value']);
                    }
                    if (isset($this->arrForm['scene_list']['value'])) {
                        $this->arrForm['scene_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['scene_list']['value']);
                    }
                    if (isset($this->arrForm['trouble_list']['value'])) {
                        $this->arrForm['trouble_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['trouble_list']['value']);
                    }
                    if (isset($this->arrForm['haircolor_list']['value'])) {
                        $this->arrForm['haircolor_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['haircolor_list']['value']);
                    }
                    if (isset($this->arrForm['hairlength_list']['value'])) {
                        $this->arrForm['hairlength_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['hairlength_list']['value']);
                    }
                    if (isset($this->arrForm['skincolor_list']['value'])) {
                        $this->arrForm['skincolor_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['skincolor_list']['value']);
                    }
				}

				break;

            //バックナンバーの[削除]ボタン押下時
			case 'delete':
				if (SC_Utils_Ex::sfIsInt($_POST['trouble_recommended_dress_id'])) {
					$objQuery = SC_Query_Ex::getSingletonInstance();
					$where = "trouble_recommended_dress_id = ?";
					$sqlval['del_flg'] = '1';
					$objQuery->update("dtb_trouble_recommended_dress", $sqlval, $where, array($_POST['trouble_recommended_dress_id']));
				}

				break;

            //[この内容で登録する]ボタン押下時
			case 'edit':
				// 入力値の変換
				$this->objFormParam->convParam();
				$this->arrErr = $this->lfCheckError();
				
				// エラーなしの場合
				if (count($this->arrErr) == 0) {
					$arrRet = $this->objFormParam->getHashArray();
					$trouble_recommended_dress_id = $this->lfRegistRecommend($arrRet);

					// パラメータ管理クラス
					$this->objFormParam = new SC_FormParam();
					// パラメータ情報の初期化
					$this->lfInitParam();

					$objQuery = SC_Query_Ex::getSingletonInstance();
					$sql = "SELECT max(recommend_no)+1 FROM dtb_trouble_recommended_dress WHERE del_flg <> 1 ";
					$max_prizeno = $objQuery->getOne($sql);
					if(empty($max_prizeno)){
						$max_prizeno = 1;
					}
					

					$res = $this->lfGetRecommendDetail($trouble_recommended_dress_id);

					$this->objFormParam->setParam($res);
					$this->arrForm = $this->objFormParam->getFormParamList();

                    //検索条件データの復元処理
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['age_list']['value'], "age_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['scene_list']['value'], "scene_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['trouble_list']['value'], "trouble_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['haircolor_list']['value'], "haircolor_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['hairlength_list']['value'], "hairlength_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    $arrRet = null;
                    $arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['skincolor_list']['value'], "skincolor_list");
                    if ($arrRet != null) {
                        $this->arrForm = array_merge($this->arrForm, $arrRet);
                    }
                    // 商品ステータスの分割読込
                    if (isset($this->arrForm['age_list']['value'])) {
                        $this->arrForm['age_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['age_list']['value']);
                    }
                    if (isset($this->arrForm['scene_list']['value'])) {
                        $this->arrForm['scene_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['scene_list']['value']);
                    }
                    if (isset($this->arrForm['trouble_list']['value'])) {
                        $this->arrForm['trouble_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['trouble_list']['value']);
                    }
                    if (isset($this->arrForm['haircolor_list']['value'])) {
                        $this->arrForm['haircolor_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['haircolor_list']['value']);
                    }
                    if (isset($this->arrForm['hairlength_list']['value'])) {
                        $this->arrForm['hairlength_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['hairlength_list']['value']);
                    }
                    if (isset($this->arrForm['skincolor_list']['value'])) {
                        $this->arrForm['skincolor_list'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['skincolor_list']['value']);
                    }

//					$this->arrForm['recommend_no']=$max_prizeno;
					
//					$this->objFormParam->setParam(array('recommend_no'=>$max_prizeno));
//					$this->arrForm = $this->objFormParam->getFormParamList();

					$this->tpl_onload = "window.alert('登録が完了しました。');";
				} else {
					$this->arrForm = $this->objFormParam->getFormParamList();
				}
				break;

            //初回画面表示時
			default:
				$recommend_no = $this->objFormParam->getValue('recommend_no');
				if(empty($recommend_no)){
					$objQuery = SC_Query_Ex::getSingletonInstance();
					$sql = "SELECT max(recommend_no)+1 FROM dtb_trouble_recommended_dress WHERE del_flg <> 1 ";
					$max_prizeno = $objQuery->getOne($sql);
					if(empty($max_prizeno)){
						$max_prizeno = 1;
					}
					$this->arrForm['recommend_no']=$max_prizeno;
					$this->objFormParam->setParam(array('recommend_no'=>$max_prizeno));
				}
				$this->arrForm = $this->objFormParam->getFormParamList();

				break;
		}

		//---- 全データ取得
		$sql = "SELECT * FROM dtb_trouble_recommended_dress WHERE del_flg = '0' ORDER BY create_date DESC";


		$objQuery = SC_Query_Ex::getSingletonInstance();
		$this->list_data = $objQuery->getAll($sql);
    }

	function lfGetProductDetail($product_id, $no, $type = "")
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
        var_dump($no);
		if (!empty($no) && SC_Utils_Ex::sfIsInt($no)) {
			if (empty($type)){
				$col = "product_id as coordinate" . $no . "_productid, name as coordinate" . $no . "_product_name, main_list_image as coordinate" . $no . "_product_image ";
			}else{
				$col = "product_id as recommend_product_id" . $no . ", name as recommend_product_name" . $no . ", main_list_image as recommend_product_image" . $no;
			}
		} else {
			$col = "product_id, name as product_name, product_code, main_list_image , main_image ";
		}

		$where = "product_id = ?";
		$arrRet = $objQuery->select($col, "vw_products_nonclass", $where, array($product_id));

		if (!empty($arrRet)) {
			return $arrRet[0];
		}
		return array();
	}

	function lfRegistRecommend($arr)
	{
		$res = array();
		if (!empty($arr['trouble_recommended_dress_id'])) {
			$res['trouble_recommended_dress_id'] = $arr['trouble_recommended_dress_id'];
		} else {

		}

        //$date = date("Y-m-d H:i:s");
        $date = date("Y-m-d");
		$res['show_flg'] = $arr['show_flg'];
        if ($res['show_flg'] == 1) {
            $res['register_date'] = date('Y年n月j日', strtotime($date));
        }
		$res['title'] = $arr['title'];
		$res['video_url'] = $arr['video_url'];

		$no = intval($arr['recommend_no']);
		if($no <= 0 ){
			$no = $this->lfGetMaxRecommendNumber();
		}
		$res['recommend_no'] = $no;
		
		$res['legend1'] = $arr['legend1'];
		$res['legend2'] = $arr['legend2'];
		
		$res['meta_tag_keyword1'] = $arr['meta_tag_keyword1'];
		$res['meta_tag_keyword2'] = $arr['meta_tag_keyword2'];
		$res['meta_tag_keyword3'] = $arr['meta_tag_keyword3'];
		
		$res['recommend_product_id1'] = $arr['recommend_product_id1'];
		$res['recommend_product_id2'] = $arr['recommend_product_id2'];
		$res['recommend_product_id3'] = $arr['recommend_product_id3'];
		$res['recommend_product_id4'] = $arr['recommend_product_id4'];
		$res['recommend_product_id5'] = $arr['recommend_product_id5'];
		$res['recommend_product_id6'] = $arr['recommend_product_id6'];
		$res['recommend_product_image1'] = $arr['recommend_product_image1'];
		$res['recommend_product_image2'] = $arr['recommend_product_image2'];
		$res['recommend_product_image3'] = $arr['recommend_product_image3'];
		$res['recommend_product_image4'] = $arr['recommend_product_image4'];
		$res['recommend_product_image5'] = $arr['recommend_product_image5'];
		$res['recommend_product_image6'] = $arr['recommend_product_image6'];

		$res['age_list'] = $arr['age_list'];
		$res['scene_list'] = $arr['scene_list'];
		$res['trouble_list'] = $arr['trouble_list'];
		$res['haircolor_list'] = $arr['haircolor_list'];
		$res['hairlength_list'] = $arr['hairlength_list'];
		$res['skincolor_list'] = $arr['skincolor_list'];

		$objQuery = SC_Query_Ex::getSingletonInstance();
		if (!empty($arr['trouble_recommended_dress_id'])) {
			//$res['news_id'] = $this->lfSetNewsInfo($res);
			$objQuery->update('dtb_trouble_recommended_dress', $res, "trouble_recommended_dress_id = ?", array($res['trouble_recommended_dress_id']));
		} else {
			$res['trouble_recommended_dress_id'] = $objQuery->nextVal('dtb_trouble_recommended_dress_trouble_recommended_dress_id');
			//$res['news_id'] = $this->lfSetNewsInfo($res);
			$objQuery->insert('dtb_trouble_recommended_dress', $res);
		}
		return $res['trouble_recommended_dress_id'];
	}

	function lfGetMaxRecommendNumber(){
		$objQuery = SC_Query_Ex::getSingletonInstance();

		$sql = "SELECT max(recommend_no)+1 FROM dtb_trouble_recommended_dress WHERE del_flg<>1";

		$arrRet = $objQuery->getOne($sql);

		return $arrRet;
	}

	function lfGetRecommendDetail($trouble_recommended_dress_id)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$sql = "select * 
                from dtb_trouble_recommended_dress
                Where trouble_recommended_dress_id = ? and del_flg<>1";

		$arrRet = $objQuery->getAll($sql, array($trouble_recommended_dress_id));
		if (!empty($arrRet)) {
			return $arrRet[0];
		}
		return array();
	}

	/* 入力内容のチェック */
	function lfCheckError()
	{
		// 入力データを渡す。
		$arrRet = $this->objFormParam->getHashArray();
		$objErr = new SC_CheckError($arrRet);
		$objErr->arrErr = $this->objFormParam->checkError();
		
		return $objErr->arrErr;
	}

	/* パラメータ情報の初期化 */
	function lfInitParam()
	{
		$this->objFormParam->addParam("賞ID", "trouble_recommended_dress_id", INT_LEN, "n", array('NUM_CHECK', "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("公開／非公開 ", "show_flg", INT_LEN, "n", array('NUM_CHECK', "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("回", "recommend_no", INT_LEN, "n", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("タイトル", "title", MTEXT_LEN, "KV", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("動画URL", "video_url", MTEXT_LEN, "KV", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("商品ID", "tmp_product_id", INT_LEN, "n", array( "MAX_LENGTH_CHECK", "NUM_CHECK") );
		$this->objFormParam->addParam("商品コード", "product_code");
		$this->objFormParam->addParam("商品名", "product_name");
		$this->objFormParam->addParam("商品画像", "main_image");
		$this->objFormParam->addParam("商品一覧画像", "main_list_image");
		$this->objFormParam->addParam("規格1", "classcategory_id1");
		$this->objFormParam->addParam("規格2", "classcategory_id2");		
		$this->objFormParam->addParam("説明文1", "legend1", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("説明文2", "legend2", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK"));
		
		$this->objFormParam->addParam("メタタグ用キーワード1", "meta_tag_keyword1", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("メタタグ用キーワード2", "meta_tag_keyword2", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("メタタグ用キーワード3", "meta_tag_keyword3", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK"));
		
		$this->objFormParam->addParam("おすすめ商品1", "recommend_product_id1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$this->objFormParam->addParam("おすすめ画像1", "recommend_product_image1");
		$this->objFormParam->addParam("おすすめ商品2", "recommend_product_id2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$this->objFormParam->addParam("おすすめ画像2", "recommend_product_image2");
		$this->objFormParam->addParam("おすすめ商品3", "recommend_product_id3", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$this->objFormParam->addParam("おすすめ画像3", "recommend_product_image3");
		$this->objFormParam->addParam("おすすめ商品4", "recommend_product_id4", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$this->objFormParam->addParam("おすすめ画像4", "recommend_product_image4");
		$this->objFormParam->addParam("おすすめ商品5", "recommend_product_id5", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$this->objFormParam->addParam("おすすめ画像5", "recommend_product_image5");
		$this->objFormParam->addParam("おすすめ商品6", "recommend_product_id6", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$this->objFormParam->addParam("おすすめ画像6", "recommend_product_image6");

		$this->objFormParam->addParam("age", "age_list");
		$this->objFormParam->addParam("scene", "scene_list");
		$this->objFormParam->addParam("trouble", "trouble_list");
		$this->objFormParam->addParam("haircolor", "haircolor_list");
		$this->objFormParam->addParam("hairlength", "hairlength_list");
		$this->objFormParam->addParam("skincolor", "skincolor_list");

	}
}
