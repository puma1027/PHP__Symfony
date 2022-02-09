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
class LC_Page_Admin_Contents_Dresser extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'contents/dresser.tpl';
        $this->tpl_subno = 'dresser';
		$this->tpl_subno_dresser = "list";
        $this->tpl_mainno = 'contents';
        $this->tpl_maintitle = 'コンテンツ管理';
        $this->tpl_subtitle = 'ベストドレッサー＞ベストドレッサー賞管理';
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
        $objPrize = new SC_Helper_Prize_Ex();

        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
		$this->arrStaff = $objPrize->lfGetStaff(); // 2013.04.30 RCHJ Add    
		$this->arrImage = $objPrize->lfGetImage();


		// ============ 2013.04.30 RCHJ Add ============
		// 補助文字処理
		if (!isset($_REQUEST['prize_date_text']) || (isset($_REQUEST['prize_date_text']) && $_REQUEST['prize_date_text']=='例：2013年4月22日・23日ご利用分')) {
			$_REQUEST['prize_date_text'] = "";
			$_GET['prize_date_text'] = "";
			$_POST['prize_date_text'] = "";
		}
		if (!isset($_REQUEST['customer_name']) || (isset($_REQUEST['customer_name']) && $_REQUEST['customer_name']=='例：Ｙ・Ｋ')) {
			$_REQUEST['customer_name'] = "";
			$_GET['customer_name'] = "";
			$_POST['customer_name'] = "";
		}
		
		if (!isset($_REQUEST['customer_info1']) || (isset($_REQUEST['customer_info1']) && $_REQUEST['customer_info1']=='例：愛知県')) {
			$_REQUEST['customer_info1'] = "";
			$_GET['customer_info1'] = "";
			$_POST['customer_info1'] = "";
		}
		if (!isset($_REQUEST['customer_info2']) || (isset($_REQUEST['customer_info2']) && $_REQUEST['customer_info2']=='例：30代前半')) {
			$_REQUEST['customer_info2'] = "";
			$_GET['customer_info2'] = "";
			$_POST['customer_info2'] = "";
		}
		if (!isset($_REQUEST['customer_info3']) || (isset($_REQUEST['customer_info3']) && $_REQUEST['customer_info3']=='例：身長160cm')) {
			$_REQUEST['customer_info3'] = "";
			$_GET['customer_info3'] = "";
			$_POST['customer_info3'] = "";
		}
		if (!isset($_REQUEST['customer_info4']) || (isset($_REQUEST['customer_info4']) && $_REQUEST['customer_info4']=='例：S体型')) {
			$_REQUEST['customer_info4'] = "";
			$_GET['customer_info4'] = "";
			$_POST['customer_info4'] = "";
		}
		
		if (!isset($_REQUEST['coordinate1_text']) || (isset($_REQUEST['coordinate1_text']) && $_REQUEST['coordinate1_text']=='例：白コサージュ／黒ボレロ')) {
			$_REQUEST['coordinate1_text'] = "";
			$_GET['coordinate1_text'] = "";
			$_POST['coordinate1_text'] = "";
		}
		if (!isset($_REQUEST['coordinate2_text']) || (isset($_REQUEST['coordinate2_text']) && $_REQUEST['coordinate2_text']=='例：パールネックレス')) {
			$_REQUEST['coordinate2_text'] = "";
			$_GET['coordinate2_text'] = "";
			$_POST['coordinate2_text'] = "";
		}
		if (!isset($_REQUEST['coordinate3_text']) || (isset($_REQUEST['coordinate3_text']) && $_REQUEST['coordinate3_text']=='例：ピンクのバッグ（参考）')) {
			$_REQUEST['coordinate3_text'] = "";
			$_GET['coordinate3_text'] = "";
			$_POST['coordinate3_text'] = "";
		}
		if (!isset($_REQUEST['coordinate4_text']) || (isset($_REQUEST['coordinate4_text']) && $_REQUEST['coordinate4_text']=='例：黒ベルト')) {
			$_REQUEST['coordinate4_text'] = "";
			$_GET['coordinate4_text'] = "";
			$_POST['coordinate4_text'] = "";
		}
		if (!isset($_REQUEST['coordinate5_text']) || (isset($_REQUEST['coordinate5_text']) && $_REQUEST['coordinate5_text']=='例：ゴールドパンプス（参考）')) {
			$_REQUEST['coordinate5_text'] = "";
			$_GET['coordinate5_text'] = "";
			$_POST['coordinate5_text'] = "";
		}
		if (!isset($_REQUEST['coordinate6_text']) || (isset($_REQUEST['coordinate6_text']) && $_REQUEST['coordinate6_text']=='例：白パニエ')) {
			$_REQUEST['coordinate6_text'] = "";
			$_GET['coordinate6_text'] = "";
			$_POST['coordinate6_text'] = "";
		}

		if (!isset($_REQUEST['recommend_word']) || (isset($_REQUEST['recommend_word']) && $_REQUEST['recommend_word']=='例：30代の方にぴったりの上品青ドレス')) {
			$_REQUEST['recommend_word'] = "";
			$_GET['recommend_word'] = "";
			$_POST['recommend_word'] = "";
		}
		// ================== End ==============
		
        $objFormParam->setParam($_POST);
//        $objFormParam->convParam();

        $prize_id = $objFormParam->getValue('prize_id');

        //---- 新規登録/編集登録
        switch ($this->getMode()) {
			case 'del_coodinate_product':
				$no = $_POST['no'];
				if(!empty($no)){
					$arrVal = array();
					$arrVal['coordinate'.$no.'_product_image'] = "";
					$arrVal['coordinate'.$no.'_productid'] = "";
					$arrVal['coordinate'.$no.'_product_name'] = "";
					
					$objFormParam->setParam($arrVal);
				}
				
				$this->arrForm = $objFormParam->getFormParamList();
				
				break;
			case 'del_recommend_product':
				$no = $_POST['no'];
				if(!empty($no)){
					$arrVal = array();
					$arrVal['recommend_product_id'.$no] = "";
					$arrVal['recommend_product_image'.$no] = "";
					$objFormParam->setParam($arrVal);
				}
				
				$this->arrForm = $objFormParam->getFormParamList();
				
				break;
			case 'select_product_detail':
				// POST情報で上書き
				if (!empty($_POST['tmp_product_id'])) {
					$arrVal = $objPrize->getProductDetail($_POST['tmp_product_id'], $_POST['no'], $_POST['type']);
					$objFormParam->setParam($arrVal);
				}
				$this->arrForm = $objFormParam->getFormParamList();
				break;
            case 'edit':
				$objFormParam->convParam();
                $this->arrErr = $this->lfCheckError($objFormParam);
				if($_POST['prize_no'] > 15){
					$objErr = new SC_CheckError_Ex();
					$objErr->doFunc(array("おすすめ", "recommend_word"), array("EXIST_CHECK"));
					$this->arrErr = array_merge((array)$this->arrErr, (array)$objErr->arrErr);
				}

                if (!SC_Utils_Ex::isBlank($this->arrErr['prize_id'])) {
                    trigger_error('', E_USER_ERROR);

                    return;
                }

                if (count($this->arrErr) <= 0) {
                    // POST値の引き継ぎ
                    $arrParam = $objFormParam->getHashArray();
                    // 登録実行
                    $res_prize_id = $this->doRegist($prize_id, $arrParam, $objPrize);
					// パラメータ管理クラス
					$objFormParam = new SC_FormParam();
					// パラメータ情報の初期化
					$this->lfInitParam($objFormParam);
					$max_prizeno = $objPrize->getMaxPrizeNo();
					$this->arrForm['prize_no']=$max_prizeno;
					$objFormParam->setParam(array('prize_no'=>$max_prizeno));
					$this->arrForm = $objFormParam->getFormParamList();
                    if ($res_prize_id !== FALSE) {
                        // 完了メッセージ
                        $prize_id = $res_prize_id;
                        $this->tpl_onload = "alert('登録が完了しました。');";

						// POSTデータを引き継ぐ
						$res = $objPrize->getPrize($prize_id);
						$objFormParam->setParam($res);
                    }
				}
				$this->arrForm = $objFormParam->getFormParamList();

                $this->tpl_prize_id = $prize_id;
                break;

            case 'pre_edit':
                $res = $objPrize->getPrize($prize_id);
                $objFormParam->setParam($res);
				$this->arrForm = $objFormParam->getFormParamList();
                // POSTデータを引き継ぐ
                $this->tpl_prize_id = $prize_id;
                break;

            case 'delete':
            //----　データ削除
                $objPrize->deletePrize($prize_id);
                //自分にリダイレクト（再読込による誤動作防止）
//                SC_Response_Ex::reload();
                break;

            //----　表示順位移動
            case 'up':
                $objPrize->rankUp($prize_id);

                // リロード
                SC_Response_Ex::reload();
                break;

            case 'down':
                $objPrize->rankDown($prize_id);

                // リロード
                SC_Response_Ex::reload();
                break;

            case 'moveRankSet':
            //----　指定表示順位移動
                $input_pos = $this->getPostRank($prize_id);
                if (SC_Utils_Ex::sfIsInt($input_pos)) {
                    $objPrize->moveRank($prize_id, $input_pos);
                }
                SC_Response_Ex::reload();
                break;


            default:
				$prize_no = $objFormParam->getValue('prize_no');
				if(empty($prize_no)){
					$max_prizeno = $objPrize->getMaxPrizeNo();
					$this->arrForm['prize_no']=$max_prizeno;
					$objFormParam->setParam(array('prize_no'=>$max_prizeno));
				}
				$this->arrForm = $objFormParam->getFormParamList();

				break;
        }
/*
		//---- 全データ取得
		$sql = "SELECT * FROM dtb_dresser_prize WHERE del_flg = '0' ORDER BY create_date DESC"; // prize_date => create_date RCHJ Change 2013.04.30
		$this->list_data = $conn->getAll($sql);
*/

        $this->arrPrize = $objPrize->getList();
        $this->line_max = count($this->arrPrize);
//        $this->arrForm = $objFormParam->getFormParamList();
    }

    /**
     * 入力されたパラメーターのエラーチェックを行う。
     * @param  Object $objFormParam
     * @return Array  エラー内容
     */
    public function lfCheckError(&$objFormParam)
    {
        $objErr = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();
		// 入力データを渡す。

		$id1= $objFormParam->getValue('coordinate1_imageid');
		if( !empty($id1) ){
			$objErr->doFunc(array("コーディネート１のテキスト", "coordinate1_text"), array("EXIST_CHECK"));
		}
		$id2 = $objFormParam->getValue('coordinate2_imageid');
		if( !empty($id2) ){
			$objErr->doFunc(array("コーディネート2のテキスト", "coordinate2_text"), array("EXIST_CHECK"));
		}
		$id3 = $objFormParam->getValue('coordinate3_imageid');
		if( !empty($id3) ){
			$objErr->doFunc(array("コーディネート3のテキスト", "coordinate3_text"), array("EXIST_CHECK"));
		}
		$id4 = $objFormParam->getValue('coordinate4_imageid');
		if( !empty($id4) ){
			$objErr->doFunc(array("コーディネート4のテキスト", "coordinate4_text"), array("EXIST_CHECK"));
		}
		$id5 = $objFormParam->getValue('coordinate5_imageid');
		if( !empty($id5) ){
			$objErr->doFunc(array("コーディネート5のテキスト", "coordinate5_text"), array("EXIST_CHECK"));
		}
		$id6 = $objFormParam->getValue('coordinate6_imageid');
		if( !empty($id6) ){
			$objErr->doFunc(array("コーディネート6のテキスト", "coordinate6_text"), array("EXIST_CHECK"));
		}
		
        return $objErr->arrErr;
    }

    /**
     * パラメーターの初期化を行う
     * @param Object $objFormParam
     */
    public function lfInitParam(&$objFormParam)
    {
		$objFormParam->addParam("賞ID", "prize_id", INT_LEN, "n", array('NUM_CHECK', "MAX_LENGTH_CHECK"));

		$objFormParam->addParam("作成社員", "create_staff_id", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK") );
		$objFormParam->addParam("公開／非公開 ", "show_flg", INT_LEN, "n", array('NUM_CHECK', "MAX_LENGTH_CHECK"));
		$objFormParam->addParam("回", "prize_no", INT_LEN, "n", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
		$objFormParam->addParam("ご利用分", "prize_date_text", MTEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
		//$objFormParam->addParam("登場日(年)", "year", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), date("Y"));
		//$objFormParam->addParam("登場日(月)", "month", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), date("n"));
		//$objFormParam->addParam("登場日(日)", "day", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), date("j"));

		$objFormParam->addParam("タイトル", "title", MTEXT_LEN, "KV", array("EXIST_CHECK", "MAX_LENGTH_CHECK")); // RCHJ Change 2013.05.10 KVa->KV
		$objFormParam->addParam("お客様イニシャル", "customer_name", MTEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
		$objFormParam->addParam("お客様情報", "customer_info1", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("お客様情報", "customer_info2", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("お客様情報", "customer_info3", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
		$objFormParam->addParam("お客様情報", "customer_info4", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
		
		$objFormParam->addParam("商品ID", "tmp_product_id", INT_LEN, "n", array( "MAX_LENGTH_CHECK", "NUM_CHECK") );
		$objFormParam->addParam("ドレス商品コード", "product_id", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK") );
		$objFormParam->addParam("商品コード", "product_code");
		$objFormParam->addParam("商品名", "product_name");
		$objFormParam->addParam("商品画像", "main_image");
		$objFormParam->addParam("商品一覧画像", "main_list_image");
		$objFormParam->addParam("規格1", "classcategory_id1");
		$objFormParam->addParam("規格2", "classcategory_id2");		

		$objFormParam->addParam("コーディネート１の商品ID", "coordinate1_productid", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("コーディネート１の商品名", "coordinate1_product_name", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"), '');
		$objFormParam->addParam("コーディネート１の商品画像", "coordinate1_product_image");
		$objFormParam->addParam("コーディネート１のテキスト", "coordinate1_text");
		$objFormParam->addParam("コーディネート１の商品画像", "coordinate1_imageid");

		$objFormParam->addParam("コーディネート２の商品ID", "coordinate2_productid", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("コーディネート２の商品名", "coordinate2_product_name", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"), '');
		$objFormParam->addParam("コーディネート２の商品画像", "coordinate2_product_image");
		$objFormParam->addParam("コーディネート２のテキスト", "coordinate2_text");
		$objFormParam->addParam("コーディネート２の商品画像", "coordinate2_imageid");

		$objFormParam->addParam("コーディネート３の商品ID", "coordinate3_productid", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("コーディネート３の商品名", "coordinate3_product_name", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"), '');
		$objFormParam->addParam("コーディネート３の商品画像", "coordinate3_product_image");
		$objFormParam->addParam("コーディネート３のテキスト", "coordinate3_text");
		$objFormParam->addParam("コーディネート３の商品画像", "coordinate3_imageid");

		$objFormParam->addParam("コーディネート４の商品ID", "coordinate4_productid", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("コーディネート４の商品名", "coordinate4_product_name", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"), '');
		$objFormParam->addParam("コーディネート４の商品画像", "coordinate4_product_image");
		$objFormParam->addParam("コーディネート４のテキスト", "coordinate4_text");
		$objFormParam->addParam("コーディネート４の商品画像", "coordinate4_imageid");
		
		$objFormParam->addParam("コーディネート５の商品ID", "coordinate5_productid", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("コーディネート５の商品名", "coordinate5_product_name", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"), '');
		$objFormParam->addParam("コーディネート５の商品画像", "coordinate5_product_image");
		$objFormParam->addParam("コーディネート５のテキスト", "coordinate5_text");
		$objFormParam->addParam("コーディネート５の商品画像", "coordinate5_imageid");

		$objFormParam->addParam("コーディネート６の商品ID", "coordinate6_productid", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("コーディネート６の商品名", "coordinate6_product_name", MTEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"), '');
		$objFormParam->addParam("コーディネート６の商品画像", "coordinate6_product_image");
		$objFormParam->addParam("コーディネート６のテキスト", "coordinate6_text");
		$objFormParam->addParam("コーディネート６の商品画像", "coordinate6_imageid");

		$objFormParam->addParam("色について", "content_color", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK")); // RCHJ Change 2013.05.10 KVa->KV
		$objFormParam->addParam("ココに注意！", "content_attention", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK")); // RCHJ Change 2013.05.10 KVa->KV
		$objFormParam->addParam("今回のポイント", "content_add_point", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK")); // RCHJ Change 2013.05.10 KVa->KV
		
		$objFormParam->addParam("お客様からのコメント", "comment_customer", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK")); // RCHJ Change 2013.05.10 KVa->KV
		
		$objFormParam->addParam("おすすめ", "recommend_word", LTEXT_LEN, "KV", array("MAX_LENGTH_CHECK")); // RCHJ Change 2013.05.10 KVa->KV , "EXIST_CHECK" remove
		
		$objFormParam->addParam("おすすめ商品1", "recommend_product_id1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("おすすめ画像1", "recommend_product_image1");
		$objFormParam->addParam("おすすめ商品2", "recommend_product_id2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("おすすめ画像2", "recommend_product_image2");
		$objFormParam->addParam("おすすめ商品3", "recommend_product_id3", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"), '0');
		$objFormParam->addParam("おすすめ画像3", "recommend_product_image3");
    }

    /**
     * 登録処理を実行.
     *
     * @param  integer  $prize_id
     * @param  array    $sqlval
     * @param  object   $objPrize
     * @return multiple
     */
    public function doRegist($prize_id, $sqlval, SC_Helper_Prize_Ex $objPrize)
    {
        $sqlval['prize_id'] = $prize_id;
        return $objPrize->savePrize($sqlval);
    }

    /**
     * データの登録日を返す。
     * @param  Array  $arrPost POSTのグローバル変数
     * @return string 登録日を示す文字列
     */
    public function getRegistDate($arrPost)
    {
        $registDate = $arrPost['year'] .'/'. $arrPost['month'] .'/'. $arrPost['day'];

        return $registDate;
    }

    /**
     * チェックボックスの値が空の時は無効な値として1を格納する
     * @param  int $link_method
     * @return int
     */
    public function checkLinkMethod($link_method)
    {
        if (strlen($link_method) == 0) {
            $link_method = 1;
        }

        return $link_method;
    }

    /**
     * ニュースの日付の値をフロントでの表示形式に合わせるために分割
     * @param String $news_date
     */
    public function splitPrizeDate($news_date)
    {
        return explode('-', $news_date);
    }

    /**
     * POSTされたランクの値を取得する
     * @param Object  $objFormParam
     * @param Integer $prize_id
     */
    public function getPostRank($prize_id)
    {
        if (strlen($prize_id) > 0 && is_numeric($prize_id) == true) {
            $key = 'pos-' . $prize_id;
            $input_pos = $_POST[$key];

            return $input_pos;
        }
    }
	
	public function lfGetProductDetail($product_id, $no, $type = "")
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
}
