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
 * 在庫管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id:LC_Page_Admin_Products_Inventory_Management_Bag.php
 */
class LC_Page_Admin_Products_Inventory_Management_Bag extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'extension/products/inventory_management_bag.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'inventory_management_bag';
        $this->tpl_pager = SMARTY_TEMPLATES_REALDIR . 'admin/pager.tpl';  
        $this->tpl_subtitle = 'バッグ在庫管理';
        $this->tpl_maintitle = '商品管理'; 
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
        $objDb = new SC_Helper_DB_Ex();     
        

        // 認証可否の判定
        //$objSess = new SC_Session();
//        $objSess->SetPageShowFlag(true);
//        SC_Utils_Ex::sfIsSuccess($objSess);

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        // POST値の引き継ぎ
        $this->arrForm = $_POST;

        // POSTデータを引き継ぐ
        $this->tpl_bag_id = $_POST['bag_id'];

        // 要求判定
        switch($_POST['mode']) {
        // 編集処理
        case 'edit':

            // エラーチェック
            $this->arrErr = $this->lfErrorCheck();

            if(count($this->arrErr) <= 0) {
                $sqlval['stock'] = $_POST['stock'];
                $where = "product_code = ?";
                $objQuery->update("dtb_product_class", $sqlval, $where, array($_POST['product_code']));

                $this->tpl_bag_id = null;
                // 再表示
//                $this->reload();
            } else {
                $where = "product_code = '".$_POST['bag_id']."'";
                $setOrder = "B.product_code";
                $arrRet = $this->lfGetBagDetail($where,$setOrder);

                // 入力項目にカテゴリ名を入力する。
                if(!empty($arrRet)){
                    $arrForm = $arrRet[0];
                    if($arrForm['cancel']==$this->arrCancel[0]){
                        $arrForm['chain_remove'] = 0;
                    }else{
                        $arrForm['chain_remove'] = 1;
                    }
                }
                $this->arrForm = $arrForm;

            }
            break;

        // 削除
        case 'delete':
            // セットドレスのセット商品情報(ストール)を削除し、指定のストールをセット商品からはずす。
            $sqlval['set_pcode_bag'] = "";
            $where = "product_code = ?";
            $objQuery->update("dtb_products_class", $sqlval, $where, array($_POST['del_set_product']));

            // 再表示(現在編集中の商品を再表示させたいので、'pre_edit'と同じ処理を行う)
            $where = "product_code = '".$_POST['bag_id']."'";
            $setOrder = "product_code";
            $arrRet = $this->lfGetBagDetail($where,$setOrder);
            // 入力項目にカテゴリ名を入力する。
            if(!empty($arrRet)){
                $arrForm = $arrRet[0];
                if($arrForm['cancel']==$this->arrCancel[0]){
                    $arrForm['chain_remove'] = 0;
                }else{
                    $arrForm['chain_remove'] = 1;
                }
            }
            $this->arrForm = $arrForm;
            break;

        // セット商品追加
        case 'add':
            // エラーチェック
            $this->arrErr = $this->lfErrorCheck();
            if(count($this->arrErr) <= 0) {
                // セットドレスのセット商品情報(ストール)を追加する。
                $sql = "UPDATE dtb_products_class SET set_pcode_bag = '".$_POST['product_code']."' WHERE product_code = '".$_POST['add_set_product']."';";
                $objQuery->query($sql);
            }

            // 再表示(現在編集中の商品を再表示させたいので、'pre_edit'と同じ処理を行う)
            $where = "product_code = '".$_POST['bag_id']."'";
            $setOrder = "product_code";
            $arrRet = $this->lfGetBagDetail($where,$setOrder);
            // 入力項目にカテゴリ名を入力する。
            if(!empty($arrRet)){
                $arrForm = $arrRet[0];
                if($arrForm['cancel']==$this->arrCancel[0]){
                    $arrForm['chain_remove'] = 0;
                }else{
                    $arrForm['chain_remove'] = 1;
                }
            }
            $this->arrForm = $arrForm;
            break;

        // 編集前処理
        case 'pre_edit':
            // 編集項目をDBより取得する。
            $where = "product_code = '".$_POST['bag_id']."'";
            $setOrder = "product_code";
            $arrRet = $this->lfGetBagDetail($where,$setOrder);

            // 入力項目にカテゴリ名を入力する。
            if(!empty($arrRet)){
                $arrForm = $arrRet[0];
                if($arrForm['cancel']==$this->arrCancel[0]){
                    $arrForm['chain_remove'] = 0;
                }else{
                    $arrForm['chain_remove'] = 1;
                }
            }
            $this->arrForm = $arrForm;
            break;

        default:
            break;
        }

        // アンカーを設定
        if (isset($_POST['image_key']) && !empty($_POST['image_key'])) {
            $anchor_hash = "location.hash='#" . $_POST['image_key'] . "'";
        } elseif (isset($_POST['anchor_key']) && !empty($_POST['anchor_key'])) {
            $anchor_hash = "location.hash='#" . $_POST['anchor_key'] . "'";
        } else {
            $anchor_hash = "";
        }

        $this->tpl_onload = $anchor_hash;

        // バッグの読込
        $where = "A.del_flg <> 1 AND B.product_code LIKE '%".PCODE_BAG."%'";
        $setOrder = "product_code";
        $this->arrBag = $this->lfGetBagDetail($where,$setOrder);

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }


    /* 入力エラーチェック */
    function lfErrorCheck() {
        $objErr = new SC_CheckError();

        $objErr->doFunc(array("在庫数", "stock", STEXT_LEN), array("EXIST_CHECK","NUM_CHECK","MAX_LENGTH_CHECK"));

        if(!empty($_POST['add_set_product'])){
            if ((strpos($_POST['add_set_product'],PCODE_SET_DRESS ) !== false)) {
                $objQuery = SC_Query_Ex::getSingletonInstance();
                $arrRet = $objQuery->select("count(product_code)", "dtb_products_class", "set_pcode_bag = '".$_POST['product_code']."' AND product_code = '".$_POST['add_set_product']."'");
                if ($arrRet[0]['count'] > 0 ) {
                    $objErr->arrErr['add_set_product'] = "※既に同じ商品コードが登録されています。<br>";
                }else{
                    $arrRet = $objQuery->select("count(product_code)", "dtb_products_class", " product_code = '".$_POST['add_set_product']."'");
                    if ($arrRet[0]['count'] == 0 ) {
                        $arrRetTemp = $objQuery->select("product_code", "dtb_products_class", " product_code LIKE '".substr($_POST['add_set_product'], 0, 7)."%'");
                        if (empty($arrRetTemp[0]['product_code'])) {
                            $objErr->arrErr['add_set_product'] = "※その商品コードは存在しません。末尾のアルファベットまで正確に入力してください。";
                        } else {
                            $objErr->arrErr['add_set_product'] = "※その商品コードは存在しません。末尾のアルファベットまで正確に入力してください。<br />もしかして、".$arrRetTemp[0]['product_code']."ですか？<br />";
                        }
                    }
                }
            }else{
                $objErr->arrErr['add_set_product'] = "※その商品コードはセット商品用ではありません。<br>";
            }
        }

        return $objErr->arrErr;
    }

    // 
    function lfGetBagDetail($where,$setOrder){

        $this_week = $this->get_week(date("Y"),date("m"),date("d"));

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->setOrder($setOrder);
        $arrRet = $objQuery->select("A.product_id,B.product_code,A.main_list_image,B.stock", "(dtb_products AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id)", $where);


        foreach ($arrRet as $key => $val) {
            //セットドレスの発送数を数える------------------------------------------------------------------------
            $where = "A.del_flg <> 1 AND A.status <> 2 AND set_pcode_bag LIKE '%".substr($arrRet[$key]['product_code'], 0, 7)."%' AND C.sending_date BETWEEN '".$this_week[0]."' AND '".$this_week[1]."'";
            $objQuery->setOrder("set_pcode_bag, B.product_code");
            $arrRetEx = $objQuery->select("C.reserved_type,B.product_code", "(dtb_products AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id)  LEFT JOIN dtb_products_reserved C ON A.product_id = C.product_id ", $where);

            $temp='';
            $count_dress=0;
            foreach ($arrRetEx as $keyEx=>$valEx) {
                if (strpos($arrRetEx[$keyEx]['product_code'],PCODE_BAG) === false) {
                    if ($arrRetEx[$keyEx]['reserved_type'] != RESERVE_PATTEN_SPECDAY) {
                        $temp[$count_dress] = $arrRetEx[$keyEx]['product_code'];
                        $count_dress++;
                    }
                }
            }
            $arrRet[$key]['shipping_product_set'] = $temp;
            $arrRet[$key]['shipping_this_week'] = $count_dress;
            $arrRet[$key]['shipping_this_week_set'] = $count_dress;


            //バッグ単品での発送数を数える------------------------------------------------------------------------
            $where = "A.del_flg <> 1 AND A.status <> 2 AND D.set_pid IS NULL AND B.product_code LIKE '%".substr($arrRet[$key]['product_code'], 0, 7)."%' AND C.sending_date BETWEEN '".$this_week[0]."' AND '".$this_week[1]."'";
            $arrRetSingleEx = $objQuery->select("C.reserved_type,B.product_code,D.set_pid", "((dtb_products AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id)
                                                 LEFT JOIN dtb_products_reserved C ON A.product_id = C.product_id) LEFT JOIN dtb_order_detail AS D ON D.order_id = C.order_id AND D.product_id = C.product_id ", $where);

            $temp='';
            $chancel_pcode_bag=array();
            $count_bag=0;
            $count_chancel_bag=0;
            foreach ($arrRetSingleEx as $keyEx=>$valEx) {
                if (strpos($arrRetSingleEx[$keyEx]['product_code'],PCODE_BAG) !== false) {
                    if ($arrRetSingleEx[$keyEx]['reserved_type'] != RESERVE_PATTEN_SPECDAY) {
                        $temp[$count_bag] = $arrRetSingleEx[$keyEx]['product_code'];
                        $count_bag++;
                    } else {
                        if (!in_array($arrRetSingleEx[$keyEx]['product_code'], $chancel_pcode_bag)) {
                            $chancel_pcode_bag[$count_chancel_bag] = $arrRetSingleEx[$keyEx]['product_code'];
                            $count_chancel_bag++;
                        }
                    }
                }
            }
            //$arrRet[$key]['shipping_product_single'] = $temp;
            //$arrRet[$key]['shipping_this_week_single'] = $count_bag;
            //$arrRet[$key]['shipping_this_week'] = $arrRet[$key]['shipping_this_week'] + $count_bag;

            $arrRet[$key]['shipping_chancel'] = $count_chancel_bag;
            //$arrRet[$key]['shipping_chancel_product_set'] = $chancel_pcode_bag;

            //使用しているセット商品を求める------------------------------------------------------------------------
            $where = "A.del_flg <> 1 AND A.status <> 2 AND set_pcode_bag LIKE '%".substr($arrRet[$key]['product_code'], 0, 7)."%'";
            $objQuery->setOrder("B.product_code");
            $arrRetEx = $objQuery->select("B.product_code", "(dtb_products AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id) ", $where);

            $temp=[];
            $count=0;
            foreach ($arrRetEx as $keyEx=>$valEx) {
                $count++;
                $temp[$keyEx] = $arrRetEx[$keyEx]['product_code'];
            }
            $arrRet[$key]['set_product'] = $temp;
            $arrRet[$key]['set_num'] = $count;
        }

        return $arrRet;
    }

    //今週(日曜日から土曜日まで)を取得する
    function get_week($yyyy, $mm, $dd)
    {
        $now_date = mktime(0,0,0,$mm,$dd,$yyyy);
        $w = (intval(date("w",$now_date)) + 7) % 7;
        $this_week[0] = date("Y-m-d",$now_date - 86400 * $w);
        $this_week[1] = date("Y-m-d",$now_date + 86400 * (6 - $w));
        return $this_week;
    }

}
?>
