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

/**
 * ニュースを管理するヘルパークラス.
 *
 * @package Helper
 * @author pineray
 * @version $Id$
 */
class SC_Helper_Prize
{
    /**
     * ニュースの情報を取得.
     *
     * @param  integer $prize_id     ニュースID
     * @param  boolean $has_deleted 削除されたニュースも含む場合 true; 初期値 false
     * @return array
     */
    public static function getPrize($prize_id, $has_deleted = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

		$sql = "select T.*,
                date_part('year',  T.prize_date   ) as year,
                date_part('month',  T.prize_date   ) as month ,
                date_part('day',  T.prize_date   ) as day ,
                T1.name as coordinate1_product_name,T1.main_list_image as coordinate1_product_image,
                T2.name as coordinate2_product_name,T2.main_list_image as coordinate2_product_image,
                T3.name as coordinate3_product_name ,T3.main_list_image as coordinate3_product_image,
                T4.name as coordinate4_product_name ,T4.main_list_image as coordinate4_product_image,
                T5.name as coordinate5_product_name ,T5.main_list_image as coordinate5_product_image,
                T6.name as coordinate6_product_name ,T6.main_list_image as coordinate6_product_image,
                TT1.image_path,TT2.image_path,TT3.image_path,TT4.image_path,TT5.image_path,TT6.image_path,
                TR1.name as recommend_product_name1,TR1.main_list_image as recommend_product_image1,
                TR2.name as recommend_product_name2,TR2.main_list_image as recommend_product_image2,
                TR3.name as recommend_product_name3 ,TR3.main_list_image as recommend_product_image3
            from (
                Select D.*, P.name, C.product_code, P.main_list_image,P.main_image
                From dtb_dresser_prize As D
                Inner Join dtb_products As P ON D.product_id=P.product_id
                Inner Join dtb_products_class As C ON D.product_id=C.product_id
                Where D.prize_id = ? and P.del_flg<>1
		    ) as T
            left join dtb_products as T1 on T1.product_id = T.coordinate1_productid
            left join dtb_products as T2 on T2.product_id = T.coordinate2_productid
            left join dtb_products as T3 on T3.product_id = T.coordinate3_productid
            left join dtb_products as T4 on T4.product_id = T.coordinate4_productid
            left join dtb_products as T5 on T5.product_id = T.coordinate5_productid
            left join dtb_products as T6 on T6.product_id = T.coordinate6_productid
            left join dtb_dresser_image as TT1 on TT1.image_id = T.coordinate1_imageid
            left join dtb_dresser_image as TT2 on TT2.image_id = T.coordinate2_imageid
            left join dtb_dresser_image as TT3 on TT3.image_id = T.coordinate3_imageid
            left join dtb_dresser_image as TT4 on TT4.image_id = T.coordinate4_imageid
            left join dtb_dresser_image as TT5 on TT5.image_id = T.coordinate5_imageid
            left join dtb_dresser_image as TT6 on TT6.image_id = T.coordinate6_imageid 
            left join dtb_products as TR1 on TR1.product_id = T.recommend_product_id1
            left join dtb_products as TR2 on TR2.product_id = T.recommend_product_id2
            left join dtb_products as TR3 on TR3.product_id = T.recommend_product_id3";

        $arrRet = $objQuery->getall($sql, array($prize_id));
		
		if (!empty($arrRet)) {
			return $arrRet[0];
		}
		return array();
    }

    /**
     * ニュース一覧の取得.
     *
     * @param  integer $dispNumber  表示件数
     * @param  integer $pageNumber  ページ番号
     * @param  boolean $has_deleted 削除されたニュースも含む場合 true; 初期値 false
     * @return array
     */
    public function getList($dispNumber = 0, $pageNumber = 0, $has_deleted = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $where = '';
        if (!$has_deleted) {
            $where .= 'del_flg = 0';
        }
        $table = 'dtb_dresser_prize';
        $objQuery->setOrder('create_date DESC');
        if ($dispNumber > 0) {
            if ($pageNumber > 0) {
                $objQuery->setLimitOffset($dispNumber, (($pageNumber - 1) * $dispNumber));
            } else {
                $objQuery->setLimit($dispNumber);
            }
        }
        $arrRet = $objQuery->select($col, $table, $where);

        return $arrRet;
    }

    /**
     * ニュース一覧の取得.
     *
     * @return int
     */
    public function getMaxPrizeNo()
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = 'max(prize_no)+1 as max_prize';
        $where = 'del_flg <> 1';
        $arrRet = $objQuery->select($col, 'dtb_dresser_prize', $where, array());
		if(count($arrRet) > 0)
	        return $arrRet[0]['max_prize'];
		else
			1;	
    }

    /**
     * lfGetStaff.
     *
     * @return array
     */
    public function lfGetStaff()
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = 'staff_id, staff_name';
        $where = 'del_flg <> 1';
        $table = 'dtb_staff_regist';
        $db_results = $objQuery->select($col, $table, $where);

		// 結果を key => value 形式に格納
		$arrRet = array();
		foreach ($db_results as $row) {
			$arrRet[$row['staff_id']] = $row['staff_name'];
		}

		return $arrRet;
    }

    /**
     * lfGetImage.
     *
     * @return array
     */
    public function lfGetImage()
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = 'image_id,image_name';
        $where = 'del_flg <> 1';
        $table = 'dtb_dresser_image';
        $objQuery->setOrder('image_name');
        $results = $objQuery->select($col, $table, $where);

		// 結果を key => value 形式に格納
		$imgData = array();
		foreach ($results as $result) {

			$imgData[$result['image_id']] = $result['image_name'];
		}

		return $imgData;
    }

    /**
     * ニュースの登録.
     *
     * @param  array    $sqlval
     * @return multiple 登録成功:ニュースID, 失敗:FALSE
     */
    public function savePrize($sqlval)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

		$res = array();

        $prize_id = $sqlval['prize_id'];
        $res['update_date'] = 'CURRENT_TIMESTAMP';
		$res['prize_date'] = date("Y-m-d");
		$res['prize_date_text'] = $sqlval['prize_date_text'];
		$res['show_flg'] = $sqlval['show_flg'];
		$res['create_staff_id'] = $sqlval['create_staff_id'];
		$res['title'] = $sqlval['title'];

		$no = intval($sqlval['prize_no']);
		if($no <= 0 ){
			$no = $this->getMaxPrizeNo();
		}
		$res['prize_no'] = $no;


		$res['prize_label'] = '★第' . $no. '回'.$res['title'].'★　（' . $sqlval['customer_info2']. $sqlval['customer_info3']. $sqlval['customer_info4'] . '）';

		$res['customer_name'] = $sqlval['customer_name'];
		$res['customer_info1'] = $sqlval['customer_info1'];
		$res['customer_info2'] = $sqlval['customer_info2'];
		$res['customer_info3'] = $sqlval['customer_info3'];
		$res['customer_info4'] = $sqlval['customer_info4'];
	
		$res['product_id'] = $sqlval['product_id'];
		$res['product_name'] = $sqlval['product_name'];
	
		if ($sqlval['coordinate1_product_name'] != '' && !empty($sqlval['coordinate1_productid'])) {
			$res['coordinate1_productid'] = $sqlval['coordinate1_productid'];
			$res['coordinate1_imageid'] = 0;
		} else {
			$res['coordinate1_productid'] = 0;
			$res['coordinate1_imageid'] = $sqlval['coordinate1_imageid'];
		}

		$res['coordinate1_text'] = $sqlval['coordinate1_text'];


		if ($sqlval['coordinate2_product_name'] != '' && !empty($sqlval['coordinate2_productid'])) {
			$res['coordinate2_productid'] = $sqlval['coordinate2_productid'];
			$res['coordinate2_imageid'] = 0;
		} else {
			$res['coordinate2_productid'] = 0;
			$res['coordinate2_imageid'] = $sqlval['coordinate2_imageid'];
		}
		$res['coordinate2_text'] = $sqlval['coordinate2_text'];

		if ($sqlval['coordinate3_product_name'] != '' && !empty($sqlval['coordinate3_productid'])) {
			$res['coordinate3_productid'] = $sqlval['coordinate3_productid'];
			$res['coordinate3_imageid'] = 0;
		} else {
			$res['coordinate3_productid'] = 0;
			$res['coordinate3_imageid'] = $sqlval['coordinate3_imageid'];
		}
		$res['coordinate3_text'] = $sqlval['coordinate3_text'];


		if ($sqlval['coordinate4_product_name'] != '' && !empty($sqlval['coordinate4_productid'])) {
			$res['coordinate4_productid'] = $sqlval['coordinate4_productid'];
			$res['coordinate4_imageid'] = 0;
		} else {
			$res['coordinate4_productid'] = 0;
			$res['coordinate4_imageid'] = $sqlval['coordinate4_imageid'];
		}
		$res['coordinate4_text'] = $sqlval['coordinate4_text'];

		if ($sqlval['coordinate5_product_name'] != '' && !empty($sqlval['coordinate5_productid'])) {
			$res['coordinate5_productid'] = $sqlval['coordinate5_productid'];
			$res['coordinate5_imageid'] = 0;
		} else {
			$res['coordinate5_productid'] = 0;
			$res['coordinate5_imageid'] = $sqlval['coordinate5_imageid'];
		}
		$res['coordinate5_text'] = $sqlval['coordinate5_text'];


		if ($sqlval['coordinate6_product_name'] != '' && !empty($sqlval['coordinate6_productid'])) {
			$res['coordinate6_productid'] = $sqlval['coordinate6_productid'];
			$res['coordinate6_imageid'] = 0;
		} else {
			$res['coordinate6_productid'] = 0;
			$res['coordinate6_imageid'] = $sqlval['coordinate6_imageid'];
		}
		$res['coordinate6_text'] = $sqlval['coordinate6_text'];
	
		$res['content_color'] = $sqlval['content_color'];
		$res['content_attention'] = $sqlval['content_attention'];
		$res['content_add_point'] = $sqlval['content_add_point'];
	
		//$res['comment_manager'] = $sqlval['comment_manager'];
		$res['comment_customer'] = $sqlval['comment_customer'];
	
		$res['recommend_word'] = $sqlval['recommend_word'];
	
		$res['recommend_product_id1'] = $sqlval['recommend_product_id1'];
		$res['recommend_product_id2'] = $sqlval['recommend_product_id2'];
		$res['recommend_product_id3'] = $sqlval['recommend_product_id3'];

        // 新規登録
        if ($prize_id == '') {
            // INSERTの実行
            $res['create_date'] = 'CURRENT_TIMESTAMP';
            $prize_id = $res['prize_id'] = $objQuery->nextVal('dtb_dresser_prize_prize_id');
            $ret = $objQuery->insert('dtb_dresser_prize', $res);
        // 既存編集
        } else {
            $where = 'prize_id = ?';
            $ret = $objQuery->update('dtb_dresser_prize', $res, $where, array($prize_id));
        }

        return ($ret) ? $prize_id : FALSE;



    }

    /**
     * ニュースの削除.
     *
     * @param  integer $prize_id ニュースID
     * @return void
     */
    public function getProductDetail($product_id, $no, $type = "")
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

        $arrRet = $objQuery->select($col, 'vw_products_nonclass', $where, array($product_id));
		if (!empty($arrRet)) {
			return $arrRet[0];
		}
		return array();
    }

    /**
     * ニュースの削除.
     *
     * @param  integer $prize_id ニュースID
     * @return void
     */
    public function deletePrize($prize_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

		$where = 'prize_id = ?';
		$sqlval['del_flg'] = '1';
		$objQuery->update('dtb_dresser_prize', $sqlval, $where, array($prize_id));
    }

    /**
     * ニュースの表示順をひとつ上げる.
     *
     * @param  integer $prize_id ニュースID
     * @return void
     */
    public function rankUp($prize_id)
    {
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRankUp('dtb_dresser_prize', 'prize_id', $prize_id);
    }

    /**
     * ニュースの表示順をひとつ下げる.
     *
     * @param  integer $prize_id ニュースID
     * @return void
     */
    public function rankDown($prize_id)
    {
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRankDown('dtb_dresser_prize', 'prize_id', $prize_id);
    }

    /**
     * ニュースの表示順を指定する.
     *
     * @param  integer $prize_id ニュースID
     * @param  integer $rank    移動先の表示順
     * @return void
     */
    public function moveRank($prize_id, $rank)
    {
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfMoveRank('dtb_dresser_prize', 'prize_id', $prize_id, $rank);
    }

    /**
     * ニュース記事数を計算.
     *
     * @param  boolean $has_deleted 削除されたニュースも含む場合 true; 初期値 false
     * @return integer ニュース記事数
     */
    public function getCount($has_deleted = false)
    {
        $objDb = new SC_Helper_DB_Ex();
        if (!$has_deleted) {
            $where = 'del_flg = 0';
        } else {
            $where = '';
        }

        return $objDb->countRecords('dtb_dresser_prize', $where);
    }
}
