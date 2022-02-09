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
 * おすすめ商品管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Contents_Recommend_Sp extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainno = 'contents';
        $this->tpl_subno = 'recommend';
        $this->tpl_maintitle = 'コンテンツ管理';
        //最大登録数の表示
        $this->tpl_disp_max = ADMIN_RECOMMEND_PRODUCT_COUNT; // Add MGN_20140310
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
        $type = $_REQUEST['type'];

        switch($type){
        	case 'review':
        		$this->tpl_subtitle = 'おすすめ商品管理＞レビューの多い順管理';
        		$this->tpl_subno_recommend = "sp_review";
        		$this->tpl_mainpage = 'contents/recomend_sp_review.tpl';
        		$this->category_id = 10;
        		$this->tpl_disp_max = 24;
        		break;
        	case 'value':
        		$this->tpl_subtitle = 'おすすめ商品管理＞評価の高い順管理';
        		$this->tpl_subno_recommend = "sp_value";
        		$this->tpl_mainpage = 'contents/recomend_sp_value.tpl';
        		$this->category_id = 11;
        		$this->tpl_disp_max = 24;
        		break;
        	case 'reco':
        		$this->tpl_subtitle = 'おすすめ商品管理＞スタッフオススメ管理';
        		$this->tpl_subno_recommend = "sp_reco";
        		$this->tpl_mainpage = 'contents/recomend_sp_reco.tpl';
        		$this->category_id = 12;
        		//::$this->tpl_disp_max = ADMIN_RECOMMEND_PRODUCT_COUNT;//::N00137 Del 20140331
        		$this->tpl_disp_max = 24;

        		$sql = "SELECT * FROM dtb_staff_regist where del_flg = 0 order by staff_id asc";
				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$result = $objQuery->getAll($sql, array() );

        		$arrdata = array();

        		for ($cnt = 0; $cnt < count($result); $cnt++) {

        			$key = $result[$cnt]["staff_id"];
        			$value = $result[$cnt]["staff_name"];

        			$arrdata[$key] = $value;
        		}

        		$this->arrStaffTEMPLATE = $arrdata;

        		break;
        }

        $this->type = $type;

        $arrRegistColumn = array(
                                 array(  "column" => "product_id", "convert" => "n" ),
                                 array(  "column" => "category_id", "convert" => "n" ),
                                 array(  "column" => "rank", "convert" => "n" ),
                                 array(  "column" => "title", "convert" => "aKV" ),
                                 array(  "column" => "comment", "convert" => "aKV" ),
                                 );

        if (!isset($_POST['mode'])) $_POST['mode'] = "";


        // 登録時
        if ( $_POST['mode'] == 'regist' ){


            // 入力文字の強制変換
            $this->arrForm = $_POST;
            $this->arrForm = $this->lfConvertParam($this->arrForm, $arrRegistColumn);
            // エラーチェック
            $this->arrErr[$this->arrForm['rank']] = $this->lfErrorCheck();
            if ( ! $this->arrErr[$this->arrForm['rank']]) {
                // 古いのを消す
                $sql = "DELETE FROM dtb_best_products WHERE category_id = ? AND rank = ?";
				$objQuery = SC_Query_Ex::getSingletonInstance();
                $objQuery->query($sql, array($this->arrForm['category_id'] ,$this->arrForm['rank']));

                // ＤＢ登録

                if(isset($_POST['staff_id'])){
                	$this->arrForm['creator_id'] = $_POST['staff_id'];
                }
                else{
                	$this->arrForm['creator_id'] = $_SESSION['member_id'];
                }

                $this->arrForm['update_date'] = "NOW()";
                $this->arrForm['create_date'] = "NOW()";

				$objQuery = SC_Query_Ex::getSingletonInstance();
                $objQuery->insert("dtb_best_products", $this->arrForm );
                //		$conn->autoExecute("dtb_best_products", $this->arrForm );

                //::N00137 Add 20140331
                if ( $_POST['reco'] == 'regist' ){

                    //コメント登録はID1しか使わない
                    $sql = "DELETE FROM dtb_best_products_recommend_comment WHERE best_products_recommend_comment_id = ?";
                    $objQuery->query($sql, array(1));//::ID1しか使わない

                    $this->arrFormReco['best_products_recommend_comment_id'] = 1;//::ID1しか使わない
                    $this->arrFormReco['staff_id'] = $_POST['staff_id'];
                    $this->arrFormReco['staff_name'] = $this->arrStaffTEMPLATE[$_POST['staff_id']];
                    $ret = $objQuery->getrow("staff_image", "dtb_staff_regist", 'del_flg = 0 and staff_id = ?', array($_POST['staff_id']));
                    $this->arrFormReco['staff_image'] = $ret['staff_image'];
                    $this->arrFormReco['best_products_recommend_comment_text'] = $_POST['comment'];
                    $objQuery->insert("dtb_best_products_recommend_comment", $this->arrFormReco );

                }
                //::N00137 Add 20140331
            }

        } elseif ( $_POST['mode'] == 'delete' ){
            // 削除時

            $sql = "DELETE FROM dtb_best_products WHERE category_id = ? AND rank = ?";
			$objQuery = SC_Query_Ex::getSingletonInstance();
            $objQuery->query($sql, array($_POST['category_id'] ,$_POST['rank']));

        }
        elseif ( $_POST['mode'] == 'update' ){

        	$date30 = date("Y-m-d H:i:s",strtotime("-30 day"));
        	$year20 = date("Y-m-d H:i:s",strtotime("-20 year"));
        	$year30 = date("Y-m-d H:i:s",strtotime("-30 year"));
        	$year40 = date("Y-m-d H:i:s",strtotime("-40 year"));
        	$year50 = date("Y-m-d H:i:s",strtotime("-50 year"));
        	$year60 = date("Y-m-d H:i:s",strtotime("-60 year"));

        	if($type == 'review'){

        		//２０代ワンピース
        		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        		"and o.del_flg = 0 and c.del_flg = 0 ".
        		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year30."' and '".$year20."' ".
        		"and ct.category_id = 1 ".
        		"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData20w = $objQuery->getAll($sql);

        		//２０代ドレス
        		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        		"and o.del_flg = 0 and c.del_flg = 0 ".
        		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year30."' and '".$year20."' ".
        		"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
        		"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData20d = $objQuery->getAll($sql);


        		//３０代ワンピース
        		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        		"and o.del_flg = 0 and c.del_flg = 0 ".
        		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year40."' and '".$year30."' ".
        		"and ct.category_id = 1 ".
        		"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData30w = $objQuery->getAll($sql);

        		//３０代ドレス
        		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        		"and o.del_flg = 0 and c.del_flg = 0 ".
        		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year40."' and '".$year30."' ".
        		"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
        		"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData30d = $objQuery->getAll($sql);


        		//４０代ワンピース
        		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        		"and o.del_flg = 0 and c.del_flg = 0 ".
        		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year50."' and '".$year40."' ".
        		"and ct.category_id = 1 ".
        		"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData40w = $objQuery->getAll($sql);

        		//４０代ドレス
        		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        		"and o.del_flg = 0 and c.del_flg = 0 ".
        		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year50."' and '".$year40."' ".
        		"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
        		"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData40d = $objQuery->getAll($sql);

        		//５０代ワンピース
        		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        		"and o.del_flg = 0 and c.del_flg = 0 ".
        		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year60."' and '".$year50."' ".
        		"and ct.category_id = 1 ".
        		"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData50w = $objQuery->getAll($sql);

        		//５０代ドレス
        		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        		"and o.del_flg = 0 and c.del_flg = 0 ".
        		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year60."' and '".$year50."' ".
        		"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
        		"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData50d = $objQuery->getAll($sql);



        		$sql = "DELETE FROM dtb_best_products WHERE category_id = ? ";
				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$objQuery->query($sql, array($this->category_id));

        		for ($cnt = 0; $cnt < 3; $cnt++) {

					$objQuery = SC_Query_Ex::getSingletonInstance();

        			if(isset($arrData20d[$cnt]["product_id"]) && $arrData20d[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = $cnt+1;
        				$data['product_id'] = $arrData20d[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData20w[$cnt]["product_id"]) && $arrData20w[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+4);
        				$data['product_id'] = $arrData20w[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData30d[$cnt]["product_id"]) && $arrData30d[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+7);
        				$data['product_id'] = $arrData30d[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData30w[$cnt]["product_id"]) && $arrData30w[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+10);
        				$data['product_id'] = $arrData30w[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}



        			if(isset($arrData40d[$cnt]["product_id"]) && $arrData40d[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+13);
        				$data['product_id'] = $arrData40d[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData40w[$cnt]["product_id"]) && $arrData40w[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+16);
        				$data['product_id'] = $arrData40w[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData50d[$cnt]["product_id"]) && $arrData50d[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+19);
        				$data['product_id'] = $arrData50d[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData50w[$cnt]["product_id"]) && $arrData50w[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+22);
        				$data['product_id'] = $arrData50w[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}




        		}
        	}
        	else if($type == 'value'){

        		//２０代ワンピース
        		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        				"and o.del_flg = 0 and c.del_flg = 0 ".
        				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year30."' and '".$year20."' ".
        				"and ct.category_id = 1 ".
        				"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData20w = $objQuery->getAll($sql);

        		//２０代ドレス
        		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        				"and o.del_flg = 0 and c.del_flg = 0 ".
        				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year30."' and '".$year20."' ".
        				"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
        				"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData20d = $objQuery->getAll($sql);


        		//３０代ワンピース
        		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        				"and o.del_flg = 0 and c.del_flg = 0 ".
        				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year40."' and '".$year30."' ".
        				"and ct.category_id = 1 ".
        				"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData30w = $objQuery->getAll($sql);

        		//３０代ドレス
        		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        				"and o.del_flg = 0 and c.del_flg = 0 ".
        				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year40."' and '".$year30."' ".
        				"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
        				"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData30d = $objQuery->getAll($sql);


        		//４０代ワンピース
        		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        				"and o.del_flg = 0 and c.del_flg = 0 ".
        				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year50."' and '".$year40."' ".
        				"and ct.category_id = 1 ".
        				"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData40w = $objQuery->getAll($sql);

        		//４０代ドレス
        		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        				"and o.del_flg = 0 and c.del_flg = 0 ".
        				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year50."' and '".$year40."' ".
        				"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
        				"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData40d = $objQuery->getAll($sql);

        		//５０代ワンピース
        		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        				"and o.del_flg = 0 and c.del_flg = 0 ".
        				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year60."' and '".$year50."' ".
        				"and ct.category_id = 1 ".
        				"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData50w = $objQuery->getAll($sql);

        		//５０代ドレス
        		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
        				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
        				"and o.del_flg = 0 and c.del_flg = 0 ".
        				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year60."' and '".$year50."' ".
        				"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
        				"group by od.product_id order by avg desc limit 3";

				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$arrData50d = $objQuery->getAll($sql);



        		$sql = "DELETE FROM dtb_best_products WHERE category_id = ? ";
				$objQuery = SC_Query_Ex::getSingletonInstance();
        		$objQuery->query($sql, array($this->category_id));

        		for ($cnt = 0; $cnt < 3; $cnt++) {

					$objQuery = SC_Query_Ex::getSingletonInstance();

        			if(isset($arrData20d[$cnt]["product_id"]) && $arrData20d[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = $cnt+1;
        				$data['product_id'] = $arrData20d[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData20w[$cnt]["product_id"]) && $arrData20w[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+4);
        				$data['product_id'] = $arrData20w[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData30d[$cnt]["product_id"]) && $arrData30d[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+7);
        				$data['product_id'] = $arrData30d[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData30w[$cnt]["product_id"]) && $arrData30w[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+10);
        				$data['product_id'] = $arrData30w[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}


        			if(isset($arrData40d[$cnt]["product_id"]) && $arrData40d[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+13);
        				$data['product_id'] = $arrData40d[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData40w[$cnt]["product_id"]) && $arrData40w[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+16);
        				$data['product_id'] = $arrData40w[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData50d[$cnt]["product_id"]) && $arrData50d[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+19);
        				$data['product_id'] = $arrData50d[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        			if(isset($arrData50w[$cnt]["product_id"]) && $arrData50w[$cnt]["product_id"] > 0){

        				$data = array();

        				$data['category_id'] = $this->category_id;
        				$data['rank'] = ($cnt+22);
        				$data['product_id'] = $arrData50w[$cnt]["product_id"];
        				$data['creator_id'] = $_SESSION['member_id'];
        				$data['update_date'] = "NOW()";
        				$data['create_date'] = "NOW()";

        				$objQuery->insert("dtb_best_products", $data);
        			}

        		}

        	}
        }

        // 既に登録されている内容を取得する
        $sql = "SELECT B.name, B.main_list_image, A.* FROM dtb_best_products as A INNER JOIN dtb_products as B USING (product_id)
		 WHERE A.del_flg = 0 and A.category_id = ".$this->category_id." ORDER BY rank";
		$objQuery = SC_Query_Ex::getSingletonInstance();
        $arrItems = $objQuery->getAll($sql);
        foreach( $arrItems as $data ){
            $this->arrItems[$data['rank']] = $data;
        }

        //::N00137 Add 20140331
        // 既に登録されている内容を取得する
        $sql = "SELECT * FROM dtb_best_products_recommend_comment WHERE best_products_recommend_comment_id = 1";
        $arrComment = $objQuery->getAll($sql);
        foreach( $arrComment as $data ){
            $this->arrComment[0] = $data;
        }
        //::N00137 Del 20140331


        // 商品変更時は、選択された商品に一時的に置き換える
        if ( $_POST['mode'] == 'set_item'){
            $sql = "SELECT product_id, name, main_list_image FROM dtb_products WHERE product_id = ? AND del_flg = 0";
			$objQuery = SC_Query_Ex::getSingletonInstance();
            $result = $objQuery->getAll($sql, array($_POST['product_id']));
            if ( $result ){
                $data = $result[0];
                foreach( $data as $key=>$val){
                    $this->arrItems[$_POST['rank']][$key] = $val;
                }
                $this->arrItems[$_POST['rank']]['rank'] = $_POST['rank'];
            }
            $this->checkRank = $_POST['rank'];
        }

        //各ページ共通
        $this->cnt_question = 6;
        $this->arrActive = isset($arrActive) ? $arrActive : "";;
        $this->arrQuestion = isset($arrQuestion) ? $arrQuestion : "";

        // カテゴリ取得
        $objDb = new SC_Helper_DB_Ex();
        $this->arrCatList = $objDb->sfGetCategoryList("level = 1");

    }

    //----　取得文字列の変換
    function lfConvertParam($array, $arrRegistColumn) {

        // カラム名とコンバート情報
        foreach ($arrRegistColumn as $data) {
            $arrConvList[ $data["column"] ] = $data["convert"];
        }
        // 文字変換
        $new_array = array();
        foreach ($arrConvList as $key => $val) {
            $new_array[$key] = isset($array[$key]) ? $array[$key] : "";
            if( strlen($val) > 0) {
                $new_array[$key] = mb_convert_kana($new_array[$key] ,$val);
            }
        }
        return $new_array;

    }

    /* 入力エラーチェック */
    function lfErrorCheck() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objErr = new SC_CheckError();

        $objErr->doFunc(array("見出しコメント", "title", STEXT_LEN), array("MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("オススメコメント", "comment", LTEXT_LEN), array("MAX_LENGTH_CHECK")); //"EXIST_CHECK", RCHJ Change 2013.05.09

        return $objErr->arrErr;
    }
}
