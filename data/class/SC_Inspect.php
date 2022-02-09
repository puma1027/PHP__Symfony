
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
 * 検品表ヘルパークラス
 *
 * @author RCHJ
 * @version $Id$
 */
class SC_Inspect {

    // {{{ constructor

    /**
     * コンストラクタ
     */
    function SC_Inspect() {
 		
    }
    
	/** estimate this product's automatic grade */
    function sfAutoEstimateGrade($product_id, $product_grade = ""){
    	// グレード値
        $arrGrade = array(
        	"00001" => GRADE_VERY_GOOD,
        	"00010" => GRADE_GOOD,
        	"00100" => GRADE_NORMAL,
        	"01000" => GRADE_BAD,
        	"10000" => GRADE_VERY_BAD,
        );
        
    	$objQuery = new SC_Query();
    	
    	if(empty($product_grade)){
    		$product_flag = $objQuery->getOne("select product_flag from dtb_products where del_flg = ? and product_id = ?", array(OFF, $product_id));
    		
    		$product_grade = $arrGrade[$product_flag];
    	}
    	
    	// get grade changed date
        $col = "create_date, reason_id";
        $table = "dtb_products_gradehistory";
        $where = "del_flg = ? and product_id = ? and grade = ?";
        $objQuery->setOrder("create_date desc");
        $ary_grade = $objQuery->select($col, $table, $where, array(OFF, $product_id, $product_grade));

        $sql = "select count(dtb_order.order_id) as order_count 
        		from dtb_order inner join dtb_order_detail on dtb_order.order_id = dtb_order_detail.order_id
        		where dtb_order.del_flg = ? and dtb_order.commit_date > ? and dtb_order_detail.product_id = ?";
        $order_count = $objQuery->getOne($sql, array(OFF, $ary_grade[0]["create_date"], $product_id));
        
        $life_limit = false;
        if($product_grade == GRADE_VERY_BAD){
        	if($order_count > 0){
        		$life_limit = true;
        	}

        	if($ary_grade[0]["reason_id"] == REASON_5){
        		$life_limit = true;
        	}
        }
        
        $remain_count = 0;
        if($product_grade == GRADE_VERY_GOOD){
			$remain_count = NEW_GRADE_DROP_ORDER_COUNT - $order_count;
        }else{
        	$remain_count = GRADE_DROP_ORDER_COUNT - $order_count;
        }
        if($remain_count < 0){$remain_count = 0;}
        
        return array("life_limit"=>$life_limit, "remain_order_count"=>$remain_count, "product_grade"=>$product_grade);
    }
    
    /*
     * $reason_id
     * 0	手動
     * 1	25回注文
     * 2	検品表データあり
     * 3	「目立つ」の評価が1つ以上
     * 4	「全体」の場所で「やや目立つ」の評価が1つ
     * 5	寿命が近づいている
     * 6	10回注文(新品同様)
     * 
     * $rank_drop_count: +(up)/-(down) change grade value
     * 
     */
    function sfRegistGradeHistory($product_id, $current_product_grade, $rank_drop_count, $reason_id){
    	$ary_reason = array(
    		REASON_0=>"手動",
    		REASON_1=>"25回注文",
    		REASON_2=>"検品表データあり",
    		REASON_3=>"「目立つ」の評価が1つ以上",
    		REASON_4=>"「全体」の場所で「やや目立つ」の評価が1つ",
    		REASON_5=>"寿命が近づいている",
    		REASON_6=>"10回注文",
    	);

        //::N00126 Add 20140312
        //REASON_0とREASON_2以外の自動で下がる要因を排除する。
        if (($reason_id == REASON_1) ||
            ($reason_id == REASON_3) ||
            ($reason_id == REASON_4) ||
            ($reason_id == REASON_5) ||
            ($reason_id == REASON_6)) {
            return;
        }
        //::N00126 end 20140312

    	//::$objQuery = new SC_Query();
    	$objQuery = SC_Query_Ex::getSingletonInstance();//::B00101 Change 20140527
    	
    	$objQuery->begin();
    	
    	// update product_flag of product table
    	$update_grade = GRADE_VERY_BAD;
    	if($current_product_grade != GRADE_VERY_BAD){// case grade is not 1
    		$update_grade = $current_product_grade + $rank_drop_count;
    		if($update_grade < GRADE_VERY_BAD ){$update_grade = GRADE_VERY_BAD;}
    		if($update_grade > GRADE_VERY_GOOD ){$update_grade = GRADE_VERY_GOOD;}
    		
    		$product_flag = "";
    		for($i=GRADE_VERY_BAD;$i<=GRADE_VERY_GOOD;$i++){
    			if($update_grade == $i){
    				$product_flag .= "1";
    			}else{
    				$product_flag .= "0";
    			}
    		}
    		$sqlval = array();
    		$sqlval["product_flag"] = $product_flag;
    		$sqlval["update_date"] = "Now()";
    		$where = "product_id = ? and del_flg = ?";
    		$objQuery->update("dtb_products", $sqlval, $where, array($product_id, OFF));
    	}else{
    		if($reason_id == REASON_0){
    			$update_grade = $current_product_grade + $rank_drop_count;
    		}else{
    			$reason_id = REASON_5;
    		}
    	}
    	
    	// insert grade history
    	$history_id = $objQuery->nextVal("dtb_products_gradehistory_history_id");
    	
    	$sqlval = array();
    	$sqlval["history_id"] = $history_id;
    	$sqlval["product_id"] = $product_id;
    	$sqlval["grade"] = $update_grade;
    	$sqlval["reason_id"] = empty($reason_id)?OFF:$reason_id;
    	$sqlval["remark"] = $ary_reason[$reason_id];
    	$sqlval["creator_id"] = $_SESSION['member_id'];
    	$sqlval["create_date"] = "Now()";
    	$sqlval["update_date"] = "Now()";
    	$sqlval["del_flg"] = OFF;
    	$objQuery->insert("dtb_products_gradehistory", $sqlval);
    	
    	$objQuery->commit();
    }
    
	/** 
     * get showing front and back images path 
     * 
     * @param $product_id
     * @param $product_kind 検品画像タイプ（ドレス、 羽織物、 ネックレス、 バッグ、  その他小物）
     * 
     * @return array
     */
    function sfGetImagePaths($product_id, $product_kind){
    	$objQuery = new SC_Query();
    	
    	$sql = "select dtb_products_inspectimage.image_front".$product_kind.", dtb_products_inspectimage.image_back".$product_kind.", dtb_inspect_image.image_front, dtb_inspect_image.image_back
from dtb_products_inspectimage inner join dtb_inspect_image on dtb_products_inspectimage.image_id".$product_kind." = dtb_inspect_image.image_id
where dtb_products_inspectimage.product_id = ? and dtb_products_inspectimage.del_flg = ? and dtb_inspect_image.del_flg = ?";
    	
    	$result = $objQuery->getAll($sql, array($product_id, OFF, OFF));
    	if(empty($result)){
    		return array("image_front"=>INSPECT_IMAGE_DIR."img_blank.gif", "image_back"=>INSPECT_IMAGE_DIR."img_blank.gif");
    	}
    	
    	$img_front_path = $result[0]["image_front".$product_kind];
    	$img_back_path = $result[0]["image_back".$product_kind];
    	if(empty($img_front_path) || !file_exists(HTML_REALDIR.$img_front_path)){
    		$img_front_path = $result[0]["image_front"];
    	}
    	if(empty($img_back_path)|| !file_exists(HTML_REALDIR.$img_back_path)){
    		$img_back_path = $result[0]["image_back"];
    	}
    	
    	return array("image_front"=>$img_front_path, "image_back"=>$img_back_path);
    }
    
    /** get inspect history data
     * 
     * $inspect_type : 1:ドレス系　２：羽織物　３：ネックレス　４：バッグ　５：その他小物
     */
    function sfGetInspectorHistory($product_id, $product_code, $inspect_type, $direction_flag = 1){
    	$objQuery = new SC_Query();
        $col = "*";
        //::N00087 Change 20131029
        //::$table = "dtb_products_inspecthistory";
        //::$where = "del_flg = ? and direction_flg = ? and product_id = ? and inspect_type = ? ";
        $table = "dtb_products_inspecthistory AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id";
        //::$where = "A.del_flg = ? and A.direction_flg = ? and A.product_id = ? and A.inspect_type = ? AND B.status=1";
        $where = "A.del_flg = ? and A.direction_flg = ? and A.product_id = ? and A.inspect_type = ?";//::N00149 Change 20140428
        $ary_where = array(OFF, $direction_flag, $product_id, $inspect_type);
        if(!empty($product_code)){
        	//::$where .= " and product_code = ? ";
        	$where .= " and A.product_code = ? ";
        	$ary_where[] = $product_code;
        }
        //::$objQuery->setOrder("inspect_date, history_id");
        $objQuery->setOrder("A.inspect_date, A.history_id desc"); // 20201215 add ishibashi
        //::N00087 end 20131029
        
        return $objQuery->select($col, $table, $where, $ary_where);
    }
    
	/** get inspect setting data */
    function sfGetInspectSettingData($table, $id_field, $value_field, $bln_place=false){
    	$objQuery = new SC_Query();
        $col = $id_field.", ".$value_field;
        if($bln_place){$col .= ", place_flg";}
        $where = "del_flg = ?";
        $objQuery->setOrder("rank");
        
        $ary_data = $objQuery->select($col, $table, $where, array(OFF));
        
        $ary_result = array();
        $ary_result[""] = "";
        foreach ($ary_data as $row) {
        	$ary_result[$row[$id_field]] = $row[$value_field];
	        if($bln_place && !empty($row["place_flg"])){
	        	$this->tpl_place_all_id = $row[$id_field];
	        }
        }
        
        return $ary_result;
    }
}
