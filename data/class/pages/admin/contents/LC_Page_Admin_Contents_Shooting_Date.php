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
class LC_Page_Admin_Contents_Shooting_Date extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'contents/shooting_date.tpl';
        $this->tpl_subno = 'trouble';
		$this->tpl_subno_dresser = "shooting";
        $this->tpl_mainno = 'contents';
        $this->tpl_maintitle = 'コンテンツ管理';
        $this->tpl_subtitle = '悩み別おすすめドレス＞撮影日管理';

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

		// 補助文字処理
		if (!isset($_REQUEST['shooting_date_schedule']) || (isset($_REQUEST['shooting_date_schedule']) && $_REQUEST['shooting_date_schedule']=='例：ご興味のある方はお問い合わせください')) {
			$_REQUEST['shooting_date_schedule'] = "";
			$_GET['shooting_date_schedule'] = "";
			$_POST['shooting_date_schedule'] = "";
		}
		if (!isset($_REQUEST['shooting_place_text']) || (isset($_REQUEST['shooting_place_text']) && $_REQUEST['shooting_place_text']=='例：埼玉県所沢市')) {
			$_REQUEST['shooting_place_text'] = "";
			$_GET['shooting_place_text'] = "";
			$_POST['shooting_place_text'] = "";
		}
		if (!isset($_REQUEST['video_url']) || (isset($_REQUEST['video_url']) && $_REQUEST['video_url']=='例：http://www.youtube.com/embed/ML1NYw_Cmis')) {
			$_REQUEST['video_url'] = "";
			$_GET['video_url'] = "";
			$_POST['video_url'] = "";
		}
		if (!isset($_REQUEST['shooting_date_text']) || (isset($_REQUEST['shooting_date_text']) && $_REQUEST['shooting_date_text']=='例：2014年4月22日')) {
			$_REQUEST['shooting_date_text'] = "";
			$_GET['shooting_date_text'] = "";
			$_POST['shooting_date_text'] = "";
		}
		
		// POST情報で上書き
		$this->objFormParam->setParam($_POST);

		if (!isset($_POST['mode'])) $_POST['mode'] = "";

		switch ($_POST['mode']) {
            //バックナンバーの[編集]ボタン押下時
			case 'pre_edit':
				if (SC_Utils_Ex::sfIsInt($_POST['shooting_date_id'])) {
					// パラメータ管理クラス
					$this->objFormParam = new SC_FormParam();
					// パラメータ情報の初期化
					$this->lfInitParam();
					
					$res = $this->lfGetShootingDateDetail($_POST['shooting_date_id']);

					$this->objFormParam->setParam($res);
					$this->arrForm = $this->objFormParam->getFormParamList();
				}

				break;

            //バックナンバーの[削除]ボタン押下時
			case 'delete':
				if (SC_Utils_Ex::sfIsInt($_POST['shooting_date_id'])) {
					$objQuery = SC_Query_Ex::getSingletonInstance();
					$where = "shooting_date_id = ?";
					$sqlval['del_flg'] = '1';
					$objQuery->update("dtb_shooting_date", $sqlval, $where, array($_POST['shooting_date_id']));
				}
				break;

            //[この内容で登録する]ボタン押下時
			case 'edit':
				// 入力値の変換
				$this->objFormParam->convParam();
				$this->arrErr = $this->lfCheckError();
				if($_POST['shooting_date_no'] > 15){
					$objErr = new SC_CheckError();
					$objErr->doFunc(array("おすすめ", "recommend_word"), array("EXIST_CHECK"));
					$this->arrErr = array_merge((array)$this->arrErr, (array)$objErr->arrErr);
				}
				
				// エラーなしの場合
				if (count($this->arrErr) == 0) {
					$arrRet = $this->objFormParam->getHashArray();
					$shooting_date_id = $this->lfRegistShootingDate($arrRet);

					// パラメータ管理クラス
					$this->objFormParam = new SC_FormParam();
					// パラメータ情報の初期化
					$this->lfInitParam();

					$sql = "SELECT max(shooting_date_no)+1 FROM dtb_shooting_date WHERE del_flg <> 1 ";

					$objQuery = SC_Query_Ex::getSingletonInstance();
					$max_prizeno = $objQuery->getOne($sql);

					if(empty($max_prizeno)){
						$max_prizeno = 1;
					}


					$res = $this->lfGetShootingDateDetail($shooting_date_id);

					$this->objFormParam->setParam($res);
					$this->arrForm = $this->objFormParam->getFormParamList();
					$this->arrForm['shooting_date_no']=$max_prizeno;

					$this->tpl_onload = "window.alert('登録が完了しました。');";
				} else {
					$this->arrForm = $this->objFormParam->getFormParamList();
				}
				break;

            //初回画面表示時
			default:
				$shooting_date_no = $this->objFormParam->getValue('shooting_date_no');
				if(empty($shooting_date_no)){
					$sql = "SELECT max(shooting_date_no)+1 FROM dtb_shooting_date WHERE del_flg <> 1 ";

					$objQuery = SC_Query_Ex::getSingletonInstance();
					$max_prizeno = $objQuery->getOne($sql);
					if(empty($max_prizeno)){
						$max_prizeno = 1;
					}
					$this->arrForm['shooting_date_no']=$max_prizeno;
					$this->objFormParam->setParam(array('shooting_date_no'=>$max_prizeno));
				}
				$this->arrForm = $this->objFormParam->getFormParamList();

				break;
		}

		//---- 全データ取得
		$sql = "SELECT * FROM dtb_shooting_date WHERE del_flg = '0' ORDER BY create_date DESC"; // prize_date => create_date RCHJ Change 2013.04.30
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$this->list_data = $objQuery->getAll($sql);


    }

	function lfGetProductDetail($product_id, $no, $type = "")
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();

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

	function lfRegistShootingDate($arr)
	{
		$res = array();
		if (!empty($arr['shooting_date_id'])) {
			$res['shooting_date_id'] = $arr['shooting_date_id'];
		} else {

		}

		$res['shooting_date_schedule'] = $arr['shooting_date_schedule'];
		$res['shooting_place_text'] = $arr['shooting_place_text'];
		$res['video_url'] = $arr['video_url'];
		$res['shooting_date_text'] = $arr['shooting_date_text'];

		$no = intval($arr['shooting_date_no']);
		if($no <= 0 ){
			$no = $this->lfGetMaxShootingDateNumber();
		}
		$res['shooting_date_no'] = $no;
		
		$objQuery = SC_Query_Ex::getSingletonInstance();
		if (!empty($arr['shooting_date_id'])) {
			$objQuery->update('dtb_shooting_date', $res, "shooting_date_id = ?", array($res['shooting_date_id']));
		} else {
			$res['shooting_date_id'] = $objQuery->nextval('dtb_shooting_date_shooting_date_id');
			$objQuery->insert('dtb_shooting_date', $res);
		}
		return $res['shooting_date_id'];
	}

	function lfGetMaxShootingDateNumber(){
		$objQuery = SC_Query_Ex::getSingletonInstance();

		$sql = "SELECT max(shooting_date_no)+1 FROM dtb_shooting_date WHERE del_flg<>1";

		$arrRet = $objQuery->getOne($sql);

		return $arrRet;
	}

	function lfGetShootingDateDetail($shooting_date_id)
	{
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$sql = "select *
                from dtb_shooting_date
                Where shooting_date_id = ? and del_flg<>1";

		$arrRet = $objQuery->getAll($sql, array($shooting_date_id));
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
		$this->objFormParam->addParam("賞ID", "shooting_date_id", INT_LEN, "n", array('NUM_CHECK', "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("回", "shooting_date_no", INT_LEN, "n", array("NUM_CHECK", "MAX_LENGTH_CHECK"));

		$this->objFormParam->addParam("次回日程", "shooting_date_schedule", MTEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("撮影場所", "shooting_place_text", MTEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("メイキングビデオ", "video_url", MTEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
		$this->objFormParam->addParam("テキスト", "shooting_date_text", MTEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
	}

}
