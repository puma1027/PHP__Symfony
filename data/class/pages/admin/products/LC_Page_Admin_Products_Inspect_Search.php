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
    require_once CLASS_EX_REALDIR . 'SC_Inspect_Ex.php';

    /**
     * 検品表検索 のページクラス.
     *
     * @package Page
     * @author  EC-CUBE CO.,LTD.
     * @version $Id$
     */
    class LC_Page_Admin_Products_Inspect_Search extends LC_Page_Admin_Ex
    {

        // }}}
        // {{{ functions

        /**
         * Page を初期化する.
         *
         * @return void
         */
        function init()
        {
            parent::init();

            $this->tpl_mainpage = 'products/product_inspect_search.tpl';
            $this->tpl_mainno = 'products';                
            $this->tpl_subno = 'product_inspect_search';
//            $this->tpl_pager = SMARTY_TEMPLATES_REALDIR . 'admin/pager.tpl';        
            $this->tpl_subtitle = '検品表';  
            $this->tpl_maintitle = '商品管理';      

            // グレード値
            $this->arrGrade = array(
                "00001" => GRADE_VERY_GOOD,
                "00010" => GRADE_GOOD,
                "00100" => GRADE_NORMAL,
                "01000" => GRADE_BAD,
                "10000" => GRADE_VERY_BAD,
            );
            $this->arrGradeName = array(
                GRADE_VERY_BAD => "使用感あり",
                GRADE_BAD => "やや使用感あり",
                GRADE_NORMAL => "良い",
                GRADE_GOOD => "非常に良い",
                GRADE_VERY_GOOD => "新品同様",
            );

            // 1:ドレス／ワンピース系　２：羽織物　３：ネックレス　４：バッグ　５：その他小物
            $this->arrProductKind = array(
                DRESS_INSPECT_IMAGE_TYPE => "ドレス／ワンピース",
                STOLE_INSPECT_IMAGE_TYPE => "羽織物",
                /* del bhm_20140318
                NECKLACE_INSPECT_IMAGE_TYPE => "ネックレス",
                BAG_INSPECT_IMAGE_TYPE => "バッグ",
                OTHERS_INSPECT_IMAGE_TYPE => "その他小物",
                */
            );

            // 1:全く目立たない 2:あまり目立たない 3:やや目立つ 4:目立つ
            $this->arrEvaluate = array(
                EVALUATE_1 => "全く目立たない",
                EVALUATE_2 => "あまり目立たない",
                EVALUATE_3 => "やや目立つ",
                EVALUATE_4 => "目立つ",
            );

            $this->arrInspector = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_man", "inspector_id", "inspector_name");
            // 20200611 ihibashi trueを外すとSmartyエラー
            //$this->arrInspectPlace = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_place", "place_id", "place_name", true);
            $this->arrInspectPlace = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_place", "place_id", "place_name");
            $this->arrInspectStatus = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_status", "status_id", "status_name");
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

//            // 認証可否の判定
//            $objSess = new SC_Session();
//            $objSess->SetPageShowFlag(true);
//            SC_Utils_Ex::sfIsSuccess($objSess);

            // パラメータ管理クラス
            $objFormParam = new SC_FormParam();
            // パラメータ情報の初期化
            $this->lfInitParam($objFormParam);
            $this->lfSearchParam($objFormParam);

            // POST値の取得
            $objFormParam->setParam($_POST);
            // 入力値の変換
            $objFormParam->convParam();

            if (!isset($_POST['mode'])) $_POST['mode'] = "";
             
            switch ($_POST['mode']) {
                case "regist":
                    $this->arrErrRegist = array();
                    $arrErrRegist = array();
                    $arrErrRegist_b = array();

                    $bln_front_empty = true;
                    $bln_back_empty = true;
                    $bln_insert = false;

                    $front_image_path = "";
                    $back_image_path = "";
                    
                    $j = json_decode( $_POST["draw_history"] , true);
                    //　同時に描画したときに、分ける
                    foreach ( $j as $val )
                    {
                       if ( $val['direction'] === 'front' )
                       {
                            $draw_front[] = $val;
                       }
                       else
                       {
                            $draw_back[] = $val;
                       }
                    }

                    $direction = "front";
                    if (!$this->lfIsEmpty($direction)) {
                        $arrErrRegist = $this->lfCheckInputValue($direction);
                        $bln_front_empty = false;
                    }
                    
                    $direction = "back";
                    if (!$this->lfIsEmpty($direction)) {
                        $arrErrRegist_b = $this->lfCheckInputValue($direction);
                        $bln_back_empty = false; 
                    }
                    if (count($arrErrRegist) == 0 && count($arrErrRegist_b) == 0) {
                        if (!$bln_front_empty) {
                            $direction = "front";
                            $this->lfSetInspectorHistory($direction);
                            //$front_image_path = $this->lfCreateInspectImage($_POST["drawdata"], HTML_REALDIR . $_POST["front_image_path"], PRODUCT_INSPECT_IMAGE_FULL_DIR);
                            //$front_image_path = $this->lfCreateInspectImage2($_POST["draw_history"], $_POST["front_canvas_data"], HTML_REALDIR . $_POST["front_image_path"], PRODUCT_INSPECT_IMAGE_FULL_DIR);
                            $front_image_path = $this->lfCreateInspectImageRev($draw_front, HTML_REALDIR . $_POST["front_image_path"], PRODUCT_INSPECT_IMAGE_FULL_DIR);
                            $bln_insert = true;   
                        }

                        if (!$bln_back_empty) {
                            $direction = "back";
                            $this->lfSetInspectorHistory($direction);
                            //$back_image_path = $this->lfCreateInspectImage($_POST["drawdata_b"], HTML_REALDIR . $_POST["back_image_path"], PRODUCT_INSPECT_IMAGE_FULL_DIR, "b_");
                            //$back_image_path = $this->lfCreateInspectImage2($_POST["draw_history"], $_POST["back_canvas_data"], HTML_REALDIR . $_POST["back_image_path"], PRODUCT_INSPECT_IMAGE_FULL_DIR, "b_");
                            $back_image_path = $this->lfCreateInspectImageRev($draw_back, HTML_REALDIR . $_POST["back_image_path"], PRODUCT_INSPECT_IMAGE_FULL_DIR, "b_");
                            $bln_insert = true; 
                        }
                    }

                    $this->arrErrRegist = array_merge((array)$arrErrRegist, (array)$arrErrRegist_b);

                    // update product inspect image table
                    if ($bln_insert) {
                        $this->lfUpdateProductInspectImage($_POST["product_id"], $_POST["hdn_product_kind"], $front_image_path, $back_image_path);
                                       
                        // regist grade history
                        $product_grade = $this->arrGrade[$_POST['product_flag']];
                        $product_id = $_POST['product_id'];
                                      
                        if ($product_grade == GRADE_VERY_GOOD) {   
                            SC_Inspect_Ex::sfRegistGradeHistory($product_id, $product_grade, -1, REASON_2);
                        } else {  
                            $evaluate_id = $_POST["chk_evaluate"];
                            $evaluate_id_b = $_POST["chk_evaluate_b"];
                            $place_id = $_POST["chk_inspect_place"];
                            $place_id_b = $_POST["chk_inspect_place_b"];
                            if ($evaluate_id == EVALUATE_4 || $evaluate_id_b == EVALUATE_4) {
                                SC_Inspect_Ex::sfRegistGradeHistory($product_id, $product_grade, -2, REASON_3); 
                            } else if (($place_id == $this->tpl_place_all_id && $evaluate_id == EVALUATE_3) ||
                                ($place_id_b == $this->tpl_place_all_id && $evaluate_id_b == EVALUATE_3)     
                            ) {
                                SC_Inspect_Ex::sfRegistGradeHistory($product_id, $product_grade, -1, REASON_4); 
                            }
                        }
                    }
                                             
                    // get page view data
                    $this->lfGetShowingData();

                    if ($bln_insert) {
                        // initial post value
                        $this->lfInitialPostValue($objFormParam);
                    }

                    break;
                case 'update':
                    $direction = $_POST["select_id"];
                    if (!$this->lfIsEmpty($direction)) {
                        $this->arrErrRegist = $this->lfCheckInputValue($direction);
                        if (count($this->arrErrRegist) == 0) {
                            $this->lfSetInspectorHistory($direction, $direction);
                        }
                    }

                    $this->lfGetShowingData();

                    break;
                case 'delete':

                    $direction =$this->lfGetInspectDirection($_POST['select_id']);

                    $this->lfDeleteInspectorHistory($_POST['select_id']);

                    $this->lfRemakeProductInspectImage($direction, $_POST['product_id'], $_POST["search_product_kind"]);

                    $this->lfGetShowingData();

                    break;
                case 'search':
                    $this->arrErr = $objFormParam->checkerror();

                    if (count($this->arrErr) != 0) {
                        break;
                    }

                    $this->lfGetShowingData();
                case 'return':
                default:
                    // initial post value
                    $this->lfInitialPostValue($objFormParam);
            }

            // 入力値の変換
            $this->arrForm = $objFormParam->getFormParamList();
                                            
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

        /** initialize post value */
        protected function lfInitialPostValue(& $objFormParam)
        {
            $_POST["txt_date"] = date("Y-m-d");
            $_POST["txt_date_b"] = date("Y-m-d");
            $_POST["chk_inspector"] = "";
            $_POST["chk_inspector_b"] = "";
            $_POST["chk_inspect_place"] = "";
            $_POST["chk_inspect_place_b"] = "";
            $_POST["chk_diameter"] = "";
            $_POST["chk_diameter_b"] = "";
            $_POST["txt_size"] = "";
            $_POST["txt_size_b"] = "";
            $_POST["chk_inspect_status"] = "";
            $_POST["chk_inspect_status_b"] = "";
            $_POST["txt_remarks"] = "";
            $_POST["txt_remarks_b"] = "";
            $_POST["chk_evaluate"] = "";
            $_POST["chk_evaluate_b"] = "";

            // POST値の取得
            $objFormParam->setParam($_POST);
            // 入力値の変換
            $objFormParam->convParam();
        }

        protected function lfGetShowingData()
        {
            $ary_cond = array();
            $ary_cond["product_code"] = $_POST["search_product_code"];
            $ary_cond["product_select_kind"] = $_POST["search_product_kind"];
            $ary_search_product = $this->lfGetSearchProduct($ary_cond);
/* 201808 del
            if (count($ary_search_product) > 1) {
                $this->arrErr["search_product_code"] = "※ この商品コードは" . count($ary_search_product) . "つあります。完全な商品コードを入力して下さい。<br>";

                return false;
            }
*/
            // get search data
            $this->arrProducts = $ary_search_product[0];
            $this->arrImagePaths = SC_Inspect_Ex::sfGetImagePaths($this->arrProducts['product_id'], $_POST["search_product_kind"]);
            $this->arrHistoryFront = SC_Inspect_Ex::sfGetInspectorHistory($this->arrProducts['product_id'], "", $_POST["search_product_kind"]);//::N00079 Change 20130910 $this->arrProducts['product_code'] -> ""
            $this->tpl_front_history_count = count($this->arrHistoryFront);
            $this->arrHistoryBack = SC_Inspect_Ex::sfGetInspectorHistory($this->arrProducts['product_id'], "", $_POST["search_product_kind"], 2);//::N00079 Change 20130910 $this->arrProducts['product_code'] -> ""
            $this->tpl_back_history_count = count($this->arrHistoryBack);
            $this->arrGradeHistory = $this->lfGetGradeHistory($this->arrProducts['product_id']);
            $this->arrAutoResult = SC_Inspect_Ex::sfAutoEstimateGrade($this->arrProducts['product_id'], $this->arrGrade[$this->arrProducts['product_flag']]);

            return true;
        }

        /** update product's inspect image path */
        protected function lfUpdateProductInspectImage($product_id, $product_kind, $front_image_path, $back_image_path)
        {
            $objQuery = SC_Query_Ex::getSingletonInstance();

            $sqlval = array();
            if (!empty($front_image_path)) {
                $sqlval["image_front" . $product_kind] = $front_image_path;
            }
            if (!empty($back_image_path)) {
                $sqlval["image_back" . $product_kind] = $back_image_path;
            }
            $sqlval["inspect_flg"] = ON;
            $sqlval["update_date"] = "Now";
            $where = "product_id = ? and del_flg = ?";
            $objQuery->update("dtb_products_inspectimage", $sqlval, $where, array($product_id, OFF));
        }

        protected function lfRemakeProductInspectImage($direction, $product_id, $product_kind)
        {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            if ($direction == 1) {
                $draw_data1 = $objQuery->getCol("draw_data",  "dtb_products_inspecthistory"," product_id = ? and inspect_type = ? and direction_flg = ? and del_flg <> ? ", array($product_id, $product_kind, $direction, ON));
                //$draw_data1 = implode('|', $res1);
                //$canvas_data = $objQuery->getCol("canvas_data",  "dtb_products_inspecthistory"," product_id = ? and inspect_type = ? and direction_flg = ? and del_flg <> ? ", array($product_id, $product_kind, $direction, ON));
                //$canvas_data = end($canvas_data);
                $sql = "select dtb_products_inspectimage.image_front" . $product_kind . ", dtb_products_inspectimage.image_back" . $product_kind . ", dtb_inspect_image.image_front, dtb_inspect_image.image_back
                    from dtb_products_inspectimage inner join dtb_inspect_image on dtb_products_inspectimage.image_id" . $product_kind . " = dtb_inspect_image.image_id
                    where dtb_products_inspectimage.product_id = ? and dtb_products_inspectimage.del_flg = ? and dtb_inspect_image.del_flg = ?";

                $result = $objQuery->getAll($sql, array($product_id, OFF, OFF));
                $source_image1 = INSPECT_IMAGE_DIR . "img_blank.gif";
                if (!empty($result)) {
                    $source_image1 = $result[0]["image_front"];
                }
                $sqlval = array();
                $sqlval["inspect_flg"] = OFF;
                $sqlval["update_date"] = "Now()";
                $sqlval["image_front" . $product_kind] = '';
                if (!empty($draw_data1)) {
                    //$front_image_path = $this->lfCreateInspectImage($draw_data1, HTML_REALDIR . $source_image1, PRODUCT_INSPECT_IMAGE_FULL_DIR);
                    //$front_image_path = $this->lfCreateInspectImage2($draw_data1, $canvas_data, HTML_REALDIR . $source_image1, PRODUCT_INSPECT_IMAGE_FULL_DIR);
                    $front_image_path = $this->lfCreateInspectImageRevDel($draw_data1, HTML_REALDIR . $source_image1, PRODUCT_INSPECT_IMAGE_FULL_DIR, $direction);
                    $sqlval["inspect_flg"] = ON;
                    if (!empty($front_image_path)) {
                        $sqlval["image_front" . $product_kind] = $front_image_path;
                    }
                }

                $where = "product_id = ? and del_flg = ?";
                $org_image = $objQuery->getRow("image_front" . $product_kind, "dtb_products_inspectimage",  $where, array($product_id, OFF));

                if (!empty($org_image)) {
                    @unlink(HTML_REALDIR . $org_image[0]);
                }
                $objQuery->update("dtb_products_inspectimage", $sqlval, $where, array($product_id, OFF));
            } elseif ($direction == 2) {
                $draw_data2 = $objQuery->getCol( "draw_data", "dtb_products_inspecthistory"," product_id = ? and inspect_type = ? and direction_flg = ? and del_flg <> ? ", array($product_id, $product_kind, $direction, ON));

                //$draw_data2 = implode('|', $res2);
                //$canvas_data = $objQuery->getCol( "canvas_data", "dtb_products_inspecthistory"," product_id = ? and inspect_type = ? and direction_flg = ? and del_flg <> ? ", array($product_id, $product_kind, $direction, ON));
                //$canvas_data = end($canvas_data);

                $sql = "select dtb_products_inspectimage.image_front" . $product_kind . ", dtb_products_inspectimage.image_back" . $product_kind . ", dtb_inspect_image.image_front, dtb_inspect_image.image_back
                    from dtb_products_inspectimage inner join dtb_inspect_image on dtb_products_inspectimage.image_id" . $product_kind . " = dtb_inspect_image.image_id
                    where dtb_products_inspectimage.product_id = ? and dtb_products_inspectimage.del_flg = ? and dtb_inspect_image.del_flg = ?";

                $result = $objQuery->getAll($sql, array($product_id, OFF, OFF));
                $source_image2 = INSPECT_IMAGE_DIR . "img_blank.gif";
                if (!empty($result)) {
                    $source_image2 = $result[0]["image_back"];
                }

                $sqlval = array();
                $sqlval["inspect_flg"] = OFF;
                $sqlval["update_date"] = "Now()";
                $sqlval["image_back" . $product_kind] = '';
                if (!empty($draw_data2)) {
                    //$back_image_path = $this->lfCreateInspectImage($draw_data2, HTML_REALDIR . $source_image2, PRODUCT_INSPECT_IMAGE_FULL_DIR);
                    //$back_image_path = $this->lfCreateInspectImage2($draw_data2, $canvas_data, HTML_REALDIR . $source_image2, PRODUCT_INSPECT_IMAGE_FULL_DIR, "b_");
                    $back_image_path = $this->lfCreateInspectImageRevDel($draw_data2, HTML_REALDIR . $source_image2, PRODUCT_INSPECT_IMAGE_FULL_DIR, $direction);
                    $sqlval["inspect_flg"] = ON;
                    if (!empty($back_image_path)) {
                        $sqlval["image_back" . $product_kind] = $back_image_path;
                    }
                }

                $where = "product_id = ? and del_flg = ?";
                $org_image = $objQuery->getRow("image_back" . $product_kind, "dtb_products_inspectimage",  $where, array($product_id, OFF));

                if (!empty($org_image)) {
                    @unlink(HTML_REALDIR . $org_image[0]);
                }
                $objQuery->update("dtb_products_inspectimage", $sqlval, $where, array($product_id, OFF));
            }
        }

        /**
         * create new inspect image
         *
         * @param array  $draw_data
         * @param string $source_image
         * @param string $target_path
         */
        protected function lfCreateInspectImage($draw_data, $source_image, $target_path, $direction, $prefix = '' )
        {
            $data = [];
            foreach ($draw_data as $val )
            {
                $data[] = explode('|', $val);
            }
            // get file's extension
            $path_parts = pathinfo($source_image);
            $file_ext = strtolower($path_parts["extension"]);
            $im = null;
            switch ($file_ext) {
                case "gif": //gif形式
                    $im = imagecreatefromgif($source_image);
                    break;
                case "jpg":
                case "jpeg":
                    $im = imagecreatefromjpeg($source_image);
                    break;
                case "png":
                    $im = imagecreatefrompng($source_image);
                    break;
                default:
            }

            foreach ($data as $item) {
                $iteminfo = explode(':', $item);
                if (count($iteminfo) > 2) {
                    $draw_info = explode(';', $iteminfo[0]);
                    $color = 15538749;
                    if (count($draw_info) == 2) {
                        $color = $draw_info[0];
                        $draw_type = $draw_info[1];
                    } else {
                        $draw_type = $iteminfo[0];
                    }
                    //$draw_type =$iteminfo[0];
                    $from = explode(',', $iteminfo[1]);
                    $to = explode(',', $iteminfo[2]);
                    if ($draw_type == 0) { //line
                        for ($i = 2; $i < count($iteminfo); $i++) {
                            $from = explode(',', $iteminfo[$i - 1]);
                            $to = explode(',', $iteminfo[$i]);
                            $this->lfImagelinethick($im, $from[0], $from[1], $to[0], $to[1], $color);
                        }
                    } else if ($draw_type == 1) { //circle
                        imageellipse($im, $from[0] + $to[0] / 2, $from[1] + $to[1] / 2, abs($to[0]), abs($to[1]), $color);
                    }

                } else {
                    //echo("<br>parsing error!!!");
                }
            }
            
            
            $file_name = $prefix . time() . '.' . $file_ext;
            switch ($file_ext) {
                case "gif": //gif形式
                    @imagegif($im, $target_path . $file_name);
                    break;

                case "jpg":
                case "jpeg": 
                    @imagejpeg($im, $target_path . $file_name, 100); 
                    break;

                case "png":
                    @imagepng($im, $target_path . $file_name);
                    break;
                default:
            } 
            return PRODUCT_INSPECT_IMAGE_DIR . $file_name;
        }

//        // ishibashi flash対応版
//        protected function lfCreateInspectImage2($draw_data, $canvas_data, $source_image, $target_path, $prefix = '')
//        {
//
//            $canvas_data = preg_replace("/data:[^,]+,/i","",$canvas_data);    
//            $canvas_data = base64_decode($canvas_data);
//
//            $im = imagecreatefromstring($canvas_data);
//
//            $j = json_decode( $draw_data , true);
//
//            for ( $i = 0; $i < count($j); $i++ )
//            {
//                if ( $j[$i]['draw_type'] === 'straight' ) 
//                {
//                    $this->lfImagelinethick($im, $j[$i]['x1'], $j[$i]['y1'], $j[$i]['x2'], $j[$i]['y2'], $j[$i]['color']);
//                }
//                else if ( $j[$i]['draw_type'] === 'circle' )
//                {
//                    imageellipse($im, $j[$i]['x1'] + $j[$i]['x2'] / 2, $j[$i]['y1'] + $j[$i]['y2'] / 2, abs($j[$i]['x2']), abs($j[$i]['y2']), $j[$i]['color']);
//                }
//
//            }
//
//            imagesavealpha($im, TRUE); // 透明色の有効
//            
//            $file_name = $prefix . time() . '.' . 'jpg';
//
//            @imagepng($im ,$target_path . $file_name);
//
//            return PRODUCT_INSPECT_IMAGE_DIR . $file_name;
//        }
        
        // ishibashi flash対応改良版
        protected function lfCreateInspectImageRev($j, $source_image, $target_path, $prefix = '')
        {
            // $j = json_decode( $draw_data , true);
            // get file's extension
            $path_parts = pathinfo($source_image);
            $file_ext = strtolower($path_parts["extension"]);
            $im = null;
            switch ($file_ext) {
                case "gif": //gif形式
                    $im = imagecreatefromgif($source_image);
                    break;
                case "jpg":
                case "jpeg":
                    $im = imagecreatefromjpeg($source_image);
                    break;
                case "png":
                    $im = imagecreatefrompng($source_image);
                    break;
                default:
            }

            foreach ( $j as $v )
            {
                $v['color'] = ( $v['color'] === '#f00' )? imagecolorallocate($im, 255, 0 , 0) : imagecolorallocate($im, 0, 0 , 255);

                if ( $v['draw_type'] === 'straight' ) 
                {
                    $this->lfImagelinethick($im, $v['x1'], $v['y1'], $v['x2'], $v['y2'], $v['color']);
                }
                else if ( $v['draw_type'] === 'circle' )
                {
                    $v['x2'] = $v['x2'] - $v['x1'];
                    $v['y2'] = $v['y2'] - $v['y1'];
                    imageellipse($im, $v['x1'] + $v['x2'] / 2, $v['y1'] + $v['y2'] / 2 , abs($v['x2']), abs($v['y2']), $v['color']);
                    //imageellipse($im, $from[0] + $to[0] / 2, $from[1] + $to[1] / 2, abs($to[0]), abs($to[1]), $color);
                }
            } 
            
            $file_name = $prefix . time() . '.' . $file_ext;
            switch ($file_ext) {
                case "gif": //gif形式
                    @imagegif($im, $target_path . $file_name);
                    break;

                case "jpg":
                case "jpeg": 
                    @imagejpeg($im, $target_path . $file_name, 100); 
                    break;

                case "png":
                    @imagepng($im, $target_path . $file_name);
                    break;
                default:
            } 
            return PRODUCT_INSPECT_IMAGE_DIR . $file_name;
        }
        
        // flase delete add
        protected function lfCreateInspectImageRevDel($draw_data, $source_image, $target_path, $direction, $prefix = '')
        {
            $data = [];
            foreach ($draw_data as $val )
            {
                $data[] = explode('|', $val);
            }

            foreach ($data as $item)
            {
                foreach( $item as $val )
                {
                    $iteminfo[] = explode(';', $val);
                }
            }

            foreach ( $iteminfo as $key => $val )
            {
                $color = $val[0];
                // DBを見る限り下記の2種類のみ
                if( $color === '15538749') // red
                {
                   $color_convert = '#f00'; 
                } 
                else if ( $color === '39638' ) // blue
                {
                   $color_convert = '#00f'; 
                }

                $item       = explode(':', $val[1]);
                $draw_type  = $item[0]; // 1 円 0 直線 
                if( $draw_type === '0')
                {
                   $draw_type_convert = 'straight';
                }
                else
                {
                   $draw_type_convert = 'circle';
                }

                $from       = explode(',', $item[1]);
                $to         = explode(',', $item[2]);
                
                // 旧データは円の描画時点を始点(x,y)とし始点からの距離(x2,y2)が決まる
                // 新データはcanvas自体のx軸y軸
                if( $draw_type_convert === 'circle' )
                {
                    $to[0] = (int)$to[0] + (int)$from[0];
                    $to[1] = (int)$to[1] + (int)$from[1];
                }

                if ( $direction === '1' )
                {
                    $direction_convert = 'front';
                }

                else if ( $direction === '2' )
                {
                    $direction_convert = 'back';
                }
                $draw_data[] = '[{"x1":'. $from[0]. ',"y1":' . $from[1] . ',"x2":' . (int)$to[0] . ',"y2":' . (int)$to[1] . 
                    ',"color":"' . $color_convert . '","draw_type":"' . $draw_type_convert . '","direction":"' . $direction_convert . '"}]';
            }

            foreach ( $draw_data as $val)
            {
                $j[] = json_decode( $val , true);
            }
            // get file's extension
            $path_parts = pathinfo($source_image);
            $file_ext = strtolower($path_parts["extension"]);
            $im = null;
            switch ($file_ext) {
                case "gif": //gif形式
                    $im = imagecreatefromgif($source_image);
                    break;
                case "jpg":
                case "jpeg":
                    $im = imagecreatefromjpeg($source_image);
                    break;
                case "png":
                    $im = imagecreatefrompng($source_image);
                    break;
                default:
            }

            foreach ( $j as $v )
            {
                $v[0]['color'] = ( $v[0]['color'] === '#f00' )? imagecolorallocate($im, 255, 0 , 0) : imagecolorallocate($im, 0, 0 , 255);

                if ( $v[0]['draw_type'] === 'straight' ) 
                {
                    $this->lfImagelinethick($im, $v[0]['x1'], $v[0]['y1'], $v[0]['x2'], $v[0]['y2'], $v[0]['color']);
                }
                else if ( $v[0]['draw_type'] === 'circle' )
                {
                    $v[0]['x2'] = $v[0]['x2'] - $v[0]['x1'];
                    $v[0]['y2'] = $v[0]['y2'] - $v[0]['y1'];
                    imageellipse($im, $v[0]['x1'] + $v[0]['x2'] / 2, $v[0]['y1'] + $v[0]['y2'] / 2, abs($v[0]['x2']), abs($v[0]['y2']), $v[0]['color']);
                }
            } 
            
            $file_name = $prefix . time() . '.' . $file_ext;
            switch ($file_ext) {
                case "gif": //gif形式
                    @imagegif($im, $target_path . $file_name);
                    break;

                case "jpg":
                case "jpeg": 
                    @imagejpeg($im, $target_path . $file_name, 100); 
                    break;

                case "png":
                    @imagepng($im, $target_path . $file_name);
                    break;
                default:
            } 
            return PRODUCT_INSPECT_IMAGE_DIR . $file_name;
        }

        /** draw line on inspect image */
        protected function lfImagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
        {
            /* this way it works well only for orthogonal lines
            imagesetthickness($image, $thick);
            return imageline($image, $x1, $y1, $x2, $y2, $color);
            */
            if ($thick == 1) {
                return imageline($image, $x1, $y1, $x2, $y2, $color);
            }
            $t = $thick / 2 - 0.5;
            if ($x1 == $x2 || $y1 == $y2) {
                return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
            }
            $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
            $a = $t / sqrt(1 + pow($k, 2));
            $points = array(
                round($x1 - (1 + $k) * $a), round($y1 + (1 - $k) * $a),
                round($x1 - (1 - $k) * $a), round($y1 - (1 + $k) * $a),
                round($x2 + (1 + $k) * $a), round($y2 - (1 - $k) * $a),
                round($x2 + (1 - $k) * $a), round($y2 + (1 + $k) * $a),
            );
            imagefilledpolygon($image, $points, 4, $color);
            return imagepolygon($image, $points, 4, $color);
        }

        /** if all input fields is empty, return true */
        protected function lfIsEmpty($direction)
        {
            $after = $direction;
            if (!is_numeric($direction)) {
                $after = ($direction == "front") ? "" : "_b";
            }

            if (isset($_POST["chk_inspector" . $after]) && !empty($_POST["chk_inspector" . $after])) {
                return false;
            }
            if (isset($_POST["chk_inspect_place" . $after]) && !empty($_POST["chk_inspect_place" . $after])) {
                return false;
            }
            if (isset($_POST["txt_size" . $after]) && !empty($_POST["txt_size" . $after])) {
                return false;
            }
            if (isset($_POST["chk_inspect_status" . $after]) && !empty($_POST["chk_inspect_status" . $after])) {
                return false;
            }
            if (isset($_POST["chk_evaluate" . $after]) && !empty($_POST["chk_evaluate" . $after])) {
                return false;
            }

            return true;
        }

        /** check input value correction */
        protected function lfCheckInputValue($direction)
        {
            $after = $direction;
            if (!is_numeric($direction)) {
                $after = ($direction == "front") ? "" : "_b";
            }
            $after_name = ($direction == "front") ? "（正面）" : "（背面）";

            $objErr = new SC_CheckError();
            $objErr->doFunc(array("年月日" . $after_name, "txt_date" . $after), array("EXIST_CHECK"));
            $objErr->doFunc(array("検品者" . $after_name, "chk_inspector" . $after), array("SELECT_CHECK"));
            $objErr->doFunc(array("場所" . $after_name, "chk_inspect_place" . $after), array("SELECT_CHECK"));
            //$objErr->doFunc(array("大きさ" . $after_name, "txt_size" . $after), array("EXIST_CHECK", "NUM_POINT_CHECK"));
            $objErr->doFunc(array("状態" . $after_name, "chk_inspect_status" . $after), array("SELECT_CHECK"));
            $objErr->doFunc(array("評価" . $after_name, "chk_evaluate" . $after), array("SELECT_CHECK"));

            return $objErr->arrErr;
        }

        /** get grade history data*/
        protected function lfGetGradeHistory($product_id)
        {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $col = "*";
            $table = "dtb_products_gradehistory";
            $where = "del_flg = ? and product_id = ?";
            $objQuery->setOrder("create_date");

            return $objQuery->select($col, $table, $where, array(OFF, $product_id));
        }

        /** regist or update inspect history data */
        protected function lfSetInspectorHistory($direction, $history_id = "")
        {
            $after = $direction;
            if (!is_numeric($direction)) {
                $after = ($direction == "front") ? "" : "_b";
            }
            $bln_insert = false;
            $sqlval = array();

            $objQuery = SC_Query_Ex::getSingletonInstance();

            if (empty($history_id)) {
                $history_id = $objQuery->nextVal("dtb_products_inspecthistory_history_id");
                $bln_insert = true;
            }
            $sqlval["history_id"] = $history_id;
            $sqlval["product_id"] = $_POST["product_id"];
            $sqlval["product_code"] = $_POST["product_code"];
            $sqlval["inspect_date"] = $_POST["txt_date" . $after];
            $sqlval["inspect_type"] = $_POST["hdn_product_kind"];
            if ($bln_insert) {
                $sqlval["direction_flg"] = ($direction == "front") ? "1" : "2";
            }
            $sqlval["inspector_id"] = $_POST["chk_inspector" . $after];
            $sqlval["place_id"] = $_POST["chk_inspect_place" . $after];
            $sqlval["diameter_flg"] = (isset($_POST["chk_diameter" . $after])) ? "1" : "0";
            $sqlval["defect_size"] = $_POST["txt_size" . $after];
            $sqlval["status_id"] = $_POST["chk_inspect_status" . $after];
            $sqlval["remarks"] = $_POST["txt_remarks" . $after];
            $sqlval["evaluat_id"] = $_POST["chk_evaluate" . $after];
            //$sqlval["draw_data"] = $_POST["drawdata" . $after];
            //$sqlval["draw_data"] = $_POST["draw_history" . $after];
            $sqlval["draw_data"] = $_POST["draw_history"]; //20201215
            $sqlval['create_date'] = "Now()";
            $sqlval['update_date'] = "Now()";
            $sqlval['creator_id'] = $_SESSION['member_id'];
            $sqlval['del_flg'] = OFF;
            //$sqlval["canvas_data"] = ($direction === "front") ? $_POST["front_canvas_data"] : $_POST["back_canvas_data"];

            if ($bln_insert) {
                $objQuery->insert("dtb_products_inspecthistory", $sqlval);
            } else {
                $where = "history_id = ?";
                $objQuery->update("dtb_products_inspecthistory", $sqlval, $where, array($history_id));
            }

            return $history_id;
        }

        /** delete history data */
        protected function lfDeleteInspectorHistory($history_id)
        {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $table = "dtb_products_inspecthistory";
            $where = "history_id = ?";
            $sqlval = array("del_flg" => ON, "update_date" => "Now()");

            return $objQuery->update($table, $sqlval, $where, array($history_id));
        }

        /** delete history data */
        protected function lfGetInspectDirection($history_id)
        {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $table = "dtb_products_inspecthistory";
            $col="direction_flg";
            $where = "history_id = ?";
            $res = $objQuery->getCol($col,$table,$where,array($history_id));

            if($res){
                return $res[0];
            }
            return null;
        }

        /** get product data that matching the search condition */
        protected function lfGetSearchProduct($ary_cond)
        {
            $sql = "select dtb_products.product_id, dtb_products_class.product_code,
    				dtb_products.name, dtb_products.main_image, dtb_products.photo_gallery_image12 as sub_image1,
    				dtb_products.product_flag, dtb_products_inspectimage.*
				from (dtb_products inner join dtb_products_class on dtb_products.product_id = dtb_products_class.product_id)
					inner join dtb_products_inspectimage on dtb_products.product_id = dtb_products_inspectimage.product_id
				where dtb_products.del_flg = '" . OFF . "'";
            //::$sql .=" AND dtb_products.status = 1";//::N00087 Add 20131021//::N00149 Del 20140428
            $sql .= " and dtb_products_class.product_code like '%" . $ary_cond["product_code"] . "%'";
            $sql .= " and (dtb_products_inspectimage.image_id" . $ary_cond["product_select_kind"] . " <> 0 or dtb_products_inspectimage.image_id" . $ary_cond["product_select_kind"] . " is not null) ";

            $objQuery = SC_Query_Ex::getSingletonInstance();
            return $objQuery->getAll($sql);
        }

        /** パラメータ情報の初期化 */
        protected function lfInitParam(&$objFormParam)
        {
            $objFormParam->addParam("種類", "search_product_kind", "", "n", array(), 1);
            $objFormParam->addParam("商品コード", "search_product_code", "", "n", array("EXIST_CHECK"));
        }

        protected function lfSearchParam(&$objFormParam)
        {
            $objFormParam->addParam("年月日（正面）", "txt_date", "", "", array(), date("Y-m-d"));
            $objFormParam->addParam("年月日（背面）", "txt_date_b", "", "", array(), date("Y-m-d"));

            $objFormParam->addParam("検品者（正面）", "chk_inspector");
            $objFormParam->addParam("検品者（背面）", "chk_inspector_b");

            $objFormParam->addParam("場所（正面）", "chk_inspect_place");
            $objFormParam->addParam("場所（背面）", "chk_inspect_place_b");

            $objFormParam->addParam("直径（正面）", "chk_diameter");
            $objFormParam->addParam("直径（背面）", "chk_diameter_b");
            $objFormParam->addParam("大きさ（正面）", "txt_size");
            $objFormParam->addParam("大きさ（背面）", "txt_size_b");

            $objFormParam->addParam("状態（正面）", "chk_inspect_status");
            $objFormParam->addParam("状態（背面）", "chk_inspect_status_b");

            $objFormParam->addParam("備考（正面）", "txt_remarks");
            $objFormParam->addParam("備考（背面）", "txt_remarks_b");

            $objFormParam->addParam("評価（正面）", "chk_evaluate");
            $objFormParam->addParam("評価（背面）", "chk_evaluate_b");
        }
    }
?>

