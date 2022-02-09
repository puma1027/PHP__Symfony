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

require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * お客様の声検索 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Products_Review_Search extends LC_Page_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage =  'products/review_search.tpl';

        // ブランド一覧追加
        $this->arrBrand = $this->lfGetBrand();

        $this->arrItem = array(
                              '1'=>array('1'=>'2','2'=>'3'),
                              '2'=>array('1'=>'1','2'=>'1'),
                              '3'=>array('1'=>'5','2'=>'5'),
                              '4'=>array('1'=>'6','2'=>'6'),
                              '5'=>array('1'=>'7','2'=>'7')
                              );

        $this->arrAge = array(
                              '1'=>'10代',
                              '2'=>'20代前半',
                              '3'=>'20代後半',
                              '4'=>'30代前半',
                              '5'=>'30代後半',
                              '6'=>'40代前半',
                              '7'=>'40代後半',
                              '8'=>'50代以上',
                              '9'=>''
                              );

        $this->arrLen = array(
                              '1'=>array('1'=>'140','2'=>'141','3'=>'142','4'=>'143','5'=>'144'),
                              '2'=>array('1'=>'145','2'=>'146','3'=>'147','4'=>'148','5'=>'149'),
                              '3'=>array('1'=>'150','2'=>'151','3'=>'152','4'=>'153','5'=>'154'),
                              '4'=>array('1'=>'155','2'=>'156','3'=>'157','4'=>'158','5'=>'159'),
                              '5'=>array('1'=>'160','2'=>'161','3'=>'162','4'=>'163','5'=>'164'),
                              '6'=>array('1'=>'165','2'=>'166','3'=>'167','4'=>'168','5'=>'169'),
                              '7'=>array('1'=>'170','2'=>'171','3'=>'172','4'=>'173','5'=>'174'),
                              '8'=>array('1'=>'175','2'=>'176','3'=>'177','4'=>'178','5'=>'179'),
                              '9'=>array('1'=>'','2'=>'','3'=>'','4'=>'','5'=>''),
                              );

        $this->arrSize = array(
                              '1'=>'【SSサイズ',
                              '2'=>'【Sサイズ',
                              '3'=>'【Mサイズ',
                              '4'=>'【Lサイズ',
                              '5'=>'【LLサイズ',
                              '6'=>'【3Lサイズ',
                              '7'=>'【妊娠中',
                              '8'=>''
                              );

        $this->arrBust = array(
                              '1'=>'バスト：A',
                              '2'=>'バスト：B',
                              '3'=>'バスト：C',
                              '4'=>'バスト：D',
                              '5'=>'バスト：E',
                              '6'=>'バスト：F',
                              '7'=>'バスト：G',
                              '8'=>'バスト：H'
                              );

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
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $this->arrProductCount = $this->lfGetProductCount();

        ////var_dump($_REQUEST['age']);
        ////var_dump($this->arrAge);
        //var_dump($this->arrAge[$_REQUEST['age'][0]]);
        ////var_dump($_REQUEST['len'][0]);
        //var_dump($this->arrLen[$_REQUEST['len'][0]]);
        ////var_dump($this->arrSize[$_REQUEST['size'][0]]);
        //var_dump($this->arrItem[$_REQUEST['item'][0]][1]);
        //var_dump($this->arrItem[$_REQUEST['item'][0]][2]);

        if (empty($_REQUEST['scene'][0])) {
            $_REQUEST['scene'][0] = 1;
            $_GET['scene'][0] = 1;
        }
        if (empty($_REQUEST['item'][0])) {
            $_REQUEST['item'][0] = 1;
            $_GET['item'][0] = 1;
        }
        if (empty($_REQUEST['age'][0])) {
            $_REQUEST['age'][0] = 4;
            $_GET['age'][0] = 4;
        }
        if (empty($_REQUEST['len'][0])) {
            $_REQUEST['len'][0] = 3;
            $_GET['len'][0] = 3;
        }
        if (empty($_REQUEST['size'][0])) {
            $_REQUEST['size'][0] = 4;
            $_GET['size'][0] = 4;
        }


        // モバイル判定
        switch (SC_Display_Ex::detectDevice()) {
            case DEVICE_TYPE_MOBILE:
                break;
            default:
                //$sql = "SELECT * FROM dtb_review WHERE title LIKE '%149%' AND title LIKE '%30代%' AND title LIKE '%SS%'";
                //$sql = "SELECT * FROM dtb_review 
                $sql = "SELECT A.product_id,A.title,A.comment,A.use_scene1,A.use_scene2,A.use_scene3,B.product_type FROM dtb_review AS A INNER JOIN dtb_products AS B ON A.product_id = B.product_id
WHERE (B.product_type = ".$this->arrItem[$_REQUEST['item'][0]][1]." OR B.product_type = ".$this->arrItem[$_REQUEST['item'][0]][2].")
";

                //年代
                if (!empty($_REQUEST['age'][0])) {
                    $sql .= "
  AND title LIKE '%".$this->arrAge[$_REQUEST['age'][0]]."%'
";
                }
                //身長
                if (!empty($_REQUEST['len'][0])) {
                    $sql .= "
  AND (
      title LIKE '%".$this->arrLen[$_REQUEST['len'][0]]['1']."%'
   OR title LIKE '%".$this->arrLen[$_REQUEST['len'][0]]['2']."%'
   OR title LIKE '%".$this->arrLen[$_REQUEST['len'][0]]['3']."%'
   OR title LIKE '%".$this->arrLen[$_REQUEST['len'][0]]['4']."%'
   OR title LIKE '%".$this->arrLen[$_REQUEST['len'][0]]['5']."%'
      )
";
                }
                //サイズ
                if (!empty($_REQUEST['size'][0])) {
                    $sql .= "
  AND title LIKE '%".$this->arrSize[$_REQUEST['size'][0]]."%'
";
                }
//                //バスト
//                if (!empty($_REQUEST['bust'][0])) {
//                    $sql .= "
////  AND title LIKE '%".$this->arrBust[$_REQUEST['bust'][0]]."%'
//";
//                }
                //利用シーン
                if (!empty($_REQUEST['scene'][0])) {
                    $sql .= "
  AND (
      use_scene1 = ".$_REQUEST['scene'][0]."
   OR use_scene2 = ".$_REQUEST['scene'][0]."
   OR use_scene3 = ".$_REQUEST['scene'][0]."
      )
";
                }

                //var_dump($sql);
                $this->arrReviewRet = $objQuery->getAll($sql);
                $sql_review = $sql;
                if (empty($this->arrReviewRet)) {
                    //var_dump('からっぽ');
                    break;
                }

                $product_id="";
                foreach ($this->arrReviewRet AS $key=>$val) {
                    if (!empty($val['product_id'])) {
                        if (!empty($product_id)) {
                            $product_id .= ",";
                        }
                        $product_id .= $val['product_id'];
                    }
                }

                $sql = "SELECT A.brand_id,B.product_code,A.icon_flag,A.product_id,A.main_list_image,round(A.womens_review_avg,1) as womens_review_avg,A.name,A.womens_review_count,
A.photo_gallery_image1,A.photo_gallery_image2,A.photo_gallery_image3,A.photo_gallery_image4,A.photo_gallery_image5,A.photo_gallery_image6,A.photo_gallery_image7,A.photo_gallery_image8,A.photo_gallery_image9,A.photo_gallery_image10

 FROM dtb_products AS A INNER JOIN dtb_products_class AS B ON A.product_id=B.product_id
 WHERE A.product_id IN (".$product_id.") AND A.haiki<>1 AND A.status=1
ORDER BY A.womens_review_avg DESC
";
                $linemax_sql = $sql;
                $this->arrProducts = $objQuery->getAll($sql);

                foreach($this->arrProducts AS $key1=>$val1) {
                    $this->arrProducts[$key1]['product_id']=intval($val1['product_id']);
                    $this->arrProductImage[0]['image'] = $this->arrProducts[$key1]['main_list_image'];
                    $this->arrProductImage[1]['image'] = $this->arrProducts[$key1]['photo_gallery_image1'];
                    $this->arrProductImage[2]['image'] = $this->arrProducts[$key1]['photo_gallery_image2'];
                    $this->arrProductImage[3]['image'] = $this->arrProducts[$key1]['photo_gallery_image3'];
                    $this->arrProductImage[4]['image'] = $this->arrProducts[$key1]['photo_gallery_image4'];
                    $this->arrProductImage[5]['image'] = $this->arrProducts[$key1]['photo_gallery_image5'];
                    $this->arrProductImage[6]['image'] = $this->arrProducts[$key1]['photo_gallery_image6'];
                    $this->arrProductImage[7]['image'] = $this->arrProducts[$key1]['photo_gallery_image7'];
                    $this->arrProductImage[8]['image'] = $this->arrProducts[$key1]['photo_gallery_image8'];
                    $this->arrProductImage[9]['image'] = $this->arrProducts[$key1]['photo_gallery_image9'];
                    $this->arrProductImage[10]['image']= $this->arrProducts[$key1]['photo_gallery_image10'];

                    foreach($this->arrReviewRet AS $key2=>$val2) {
                        if ($val1['product_id'] === $val2['product_id']) {
                            $tmp = $sql_review;
                            $tmp.="AND A.product_id = ".$val1['product_id']."";
                            $this->arrReview[$val1['product_id']] = $objQuery->getAll($tmp);
                            //var_dump('--------------');
                            ////var_dump($val1['product_id']);
                            ////var_dump($this->arrProducts[$key1]['product_id']);
                            //var_dump('--------------');
                        }
                    }
                }

                $this->tpl_linemax = count($objQuery->getAll($linemax_sql));





                break;
        }

    }

    function lfGetBrand() {
        $objQuery = new SC_Query();
        $where = "del_flg <> 1";
        $objQuery->setorder("name ASC");
        $results = $objQuery->select("brand_id, name", "dtb_brand", $where);
        foreach ($results as $result) {
            $arrBrand[$result['brand_id']] = $result['name'];
        }
        return $arrBrand;
    }

    function lfGetProductCount(){
        $objQuery = new SC_Query();
        $result = array();
        $result['onepiece_count'] = $objQuery->count("dtb_products", "product_type = ? and status = ? and del_flg = 0", array(ONEPIECE_PRODUCT_TYPE, 1));
        $result['dress_count'] = $objQuery->count("dtb_products", "product_type in (?, ?, ?, ?) and status = ? and del_flg = 0", array(DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));
        $sql = "select sum(womens_review_count) from dtb_products where product_type in (?, ?, ?, ?, ?) and status = ? and del_flg = 0";
        $result['women_review_count'] = $objQuery->getone($sql, array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));

        return $result;
    }
}
