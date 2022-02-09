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
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';


/**
 * ユーザーレビュー登録 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_ReviewRegister extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'products/review_register.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'review_register';
        $this->tpl_subtitle = 'レビュー管理>ユーザーレビュー登録';
        $this->tpl_maintitle = '商品管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPageMax = $masterData->getMasterData("mtb_page_max");
        $this->arrRECOMMEND = $masterData->getMasterData("mtb_recommend");
        $this->arrSex = $masterData->getMasterData("mtb_sex");
        $this->arrUSESCENE = $masterData->getMasterData("mtb_use_scene"); // RCHJ 2013.03.01 Add
        $this->arrVENUE = $masterData->getMasterData("mtb_review_venue"); // 2019.10.31 Add

        $this->arrAge =array("10代","20代前半","20代後半","30代前半","30代後半","40代前半","40代後半","50代以上");//::N00069 Change 20130716
        //$this->arrType = array("華奢な体型","標準体型","ややぽっちゃり体型","ぽっちゃり体型","妊娠中");//::N00038 Del 20130418

        $this->arrSize = array("かなり大きく","やや大きく","ぴったりで","やや小さく","かなり小さく");//::N00002 Add 20130418
        $this->arrHeight = array("ひざ上","ひざ丈","ひざ下");//::N00002 Add 20130418

        //::$this->arrSize2 = array("SSサイズ","Sサイズ","Mサイズ","Lサイズ","LLサイズ","3Lサイズ","妊娠中");//::N00038 Add 20130418
        $this->arrSize2 = array("SSサイズ","Sサイズ","Mサイズ","Lサイズ","LLサイズ","3Lサイズ","4Lサイズ","妊娠中");//::N00140 Add 20140410
        $this->arrCap1 = array("A","B","C","D","E","F","G","H","I");//::N00038 Add 20130418
        $this->arrCap2 = array("65","70","75","80","85","90");//::N00038 Add 20130418
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
     public function process() {
        $this->action();
        $this->sendResponse();
    }

    public function action()
    {


        $objQuery = SC_Query_Ex::getSingletonInstance();

        $this->arrForm = $_POST;

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        if($this->arrForm['status'] == "") {
            $this->arrForm['status'] = '1';
        }

        if($this->arrForm['recommend_level'] == "") {
            $this->arrForm['recommend_level'] = '5';
        }

        //::N00038 Del 20130418
        //if($this->arrForm['review_type'] == "") {
        //    $this->arrForm['review_type'] = '1';
        //}
        //::N00038 end 20130418

        // ================ 2013.03.01 RCHJ Add ===============
        if($this->arrForm['use_scene1'] == "") {
        	$this->arrForm['use_scene1'] = ''; // 1
        }
        if($this->arrForm['use_scene2'] == "") {
        	$this->arrForm['use_scene2'] = ''; // 1
        }
        if($this->arrForm['use_scene3'] == "") {
        	$this->arrForm['use_scene3'] = ''; //
        }
        // ================ end ===============

        //::N00002 Add 20130418
        if($this->arrForm['comment1'] == "") {
        	$this->arrForm['comment1'] = '2';
        }
        if($this->arrForm['comment2'] == "") {
        	$this->arrForm['comment2'] = '1';
        }
        //::N00002 Add 20130418

        if ( $_POST['product_ids']!="" && $_POST['order_id']){
            $sql = "select A.name , B.product_code from dtb_products as A inner join dtb_products_class as B on A.product_id =B.product_id  where A.product_id in (".$_POST['product_ids'].")  ";
            $this->arrSelectedProducts = $objQuery->getAll($sql,array());
            $this->selectedProductIds = $_POST['product_ids'];
        }

        if ($_REQUEST['mode'] == "add_product"){
            $this->order_id = $_REQUEST['order_id'];
            $sql = "select dtb_order_detail.* ,dtb_products.product_type from dtb_order_detail inner join dtb_products on dtb_products.product_id =dtb_order_detail.product_id  where order_id = ? ";
            $this->arrProducts = $objQuery->getAll($sql,array($this->order_id));

            foreach($this->arrProducts as $item){
                //::if($item['product_type'] == DRESS3_PRODUCT_TYPE || $item['product_type'] == DRESS4_PRODUCT_TYPE  ){
                if($item['product_type'] == DRESS3_PRODUCT_TYPE || $item['product_type'] == DRESS4_PRODUCT_TYPE || $item['product_type'] == SET_DRESS_PRODUCT_TYPE  ){//::N00083 Change 20131201
                    $this->select_id = $item['product_id'];
                    break;
                }elseif(empty($this->select_id ) && ($item['product_type'] == ONEPIECE_PRODUCT_TYPE || $item['product_type'] == DRESS_PRODUCT_TYPE  ) ){
                    $this->select_id = $item['product_id'];
                }
            }

            $this->tpl_mainpage = 'products/review_select.tpl';
            $this->tpl_select= 1;
            return;
        }

        if ($_POST['mode'] == 'register' ){

            //エラーチェック
            $this->arrErr = $this->lfCheckError();

            if (!$this->arrErr){
                $arrval['product_id'] = $_POST['product_ids'];
                $arrval['reviewer_name'] = $_POST['order_id'];
                $arrval['reviewer_url'] = '';
                $arrval['sex'] = '2';//１：男性　２：女性
                $arrval['customer_id'] = '';
                $arrval['recommend_level'] = $_POST['recommend_level'];

// ================= 2013.03.01 RCHJ Add ===========
                $arrval['use_scene1'] = $_POST['use_scene1'];
                $arrval['use_scene2'] = $_POST['use_scene2'];
                $arrval['use_scene3'] = $_POST['use_scene3'];
// ================= End ============

                //::$arrval['title'] = $this->arrAge[$_POST['review_age']]."　・身長".$_POST['review_height']."㎝(".$this->arrType[$_POST['review_type']].") ".$_POST['review_etc'];
                //::N00051 Add 20130516
                //::N00038 Change 20130419
                if($_POST['review_cap1'] === "" || $_POST['review_cap2'] === "") {
                    if (empty($_POST['review_etc'])) {
                        $arrval['title'] = $this->arrAge[$_POST['review_age']]."　・身長".$_POST['review_height']."cm【".$this->arrSize2[$_POST['review_size2']]."】 ";
                    } else {
                        $arrval['title'] = $this->arrAge[$_POST['review_age']]."　・身長".$_POST['review_height']."cm【".$this->arrSize2[$_POST['review_size2']]."(".$_POST['review_etc'].")】 ";
                    }
                } else {
                    if (empty($_POST['review_etc'])) {
                        $arrval['title'] = $this->arrAge[$_POST['review_age']]."　・身長".$_POST['review_height']."cm【".$this->arrSize2[$_POST['review_size2']]."】 "."(バスト：".$this->arrCap1[$_POST['review_cap1']].$this->arrCap2[$_POST['review_cap2']].")";
                    } else {
                        $arrval['title'] = $this->arrAge[$_POST['review_age']]."　・身長".$_POST['review_height']."cm【".$this->arrSize2[$_POST['review_size2']]."(".$_POST['review_etc'].")】 "."(バスト：".$this->arrCap1[$_POST['review_cap1']].$this->arrCap2[$_POST['review_cap2']].")";
                    }
                }
                //::N00038 end 20130419
                //::N00051 end 20130516
                $arrval['comment'] = "サイズは".$this->arrSize[$_POST['comment1']]."、丈は".$this->arrHeight[$_POST['comment2']]."でした。\n".$_POST['comment'];
                $arrval['status'] = $_POST['status'];
                $arrval['creator_id'] = $_SESSION['member_id'];
                $arrval['create_date'] = $this->lfGetReturnDate( $_POST['order_id']);
                $arrval['update_date'] = $arrval['create_date'];//'Now()';
                $arrval['del_flg'] = '0';
                $arrval['order_id'] = $_POST['order_id'];
                $arrval['product_list'] = $_POST['product_ids'];

                //insert
                if($objQuery->insert("dtb_review",$arrval)){
                    $this->arrForm =null;
                    $this->arrSelectedProducts = array();
                    $this->selectedProductIds = "";
                    $this->tpl_onload = "window.alert('登録が完了しました。');";

                    //init form
                    //$this->arrForm['review_type'] = '1';//::N00038 Del 20130418
                    $this->arrForm['recommend_level'] = '5';
                    $this->arrForm['status'] = '1';

                    // 利用シーン  2013.03.01 RCHJ Add
                    $this->arrForm['use_scene1'] = '';
                    $this->arrForm['use_scene2'] = '';
                    $this->arrForm['use_scene3'] = '';
                }else{
                    //error
                    $this->tpl_onload = "window.alert('エラーが発生しました。入力内容をご確認下さい。');";
                }

            }
        }

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    // 入力エラーチェック
    function lfCheckError() {
        $objErr = new SC_CheckError();

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        switch ($_POST['mode']){
        case 'register':
            $objErr->doFunc(array("注文番号", "order_id", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK","MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("商品名", "product_ids", LLTEXT_LEN), array("EXIST_CHECK"));

            $objErr->doFunc(array("レビュー表示", "status", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("おすすめレベル", "recommend_level"), array("SELECT_CHECK"));

// =========== 2013.03.01 RCHJ Add ==============
            $objErr->doFunc(array("利用シーン1", "use_scene1"), array("NUM_CHECK"));
            $objErr->doFunc(array("利用シーン2", "use_scene2"), array("NUM_CHECK"));
            $objErr->doFunc(array("利用シーン3", "use_scene3"), array("NUM_CHECK"));
// =============== End ==============

            $objErr->doFunc(array("年代", "review_age", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("身長", "review_height", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK" , "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            //$objErr->doFunc(array("体型", "review_type", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));//::N00038 Del 20130418
            $objErr->doFunc(array("タイトル", "review_etc", STEXT_LEN), array( "SPTAB_CHECK", "MAX_LENGTH_CHECK"));

            $objErr->doFunc(array("サイズ", "review_size2", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));//::N00038 Add 20130418
            //::$objErr->doFunc(array("カップ数", "review_cap1", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));//::N00038 Add 20130418
            //::$objErr->doFunc(array("アンダーバスト", "review_cap2", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));//::N00038 Add 20130418

            $objErr->doFunc(array("サイズ", "comment1", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("丈", "comment2", STEXT_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("コメント", "comment", LTEXT_LEN), array( "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            break;
        }
        return $objErr->arrErr;
    }

    function lfGetReturnDate($order_id  ){
        //
        $year = date("Y",strtotime('now'));
        $month = date("m",strtotime('now'));
        $day = date("d",strtotime('now'));

        $format ="Y-m-d  h:i:s";

        // DBから受注情報を読み込む
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = "order_id = ?";
        $arrRet = $objQuery->select("*", "dtb_order", $where, array($order_id));
        if(empty($arrRet)){
            return "Now()";
        }

		$sending_date = $arrRet[0]['sending_date'];
		$return_date = '';
		if($sending_date!=null && $sending_date!=""){
			$sending_date = strtotime($sending_date);
			$return_date = date($format,strtotime("+4 day" ,$sending_date));
		}

        return $return_date;
    }
}
?>
