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
 * おすすめ商品を管理するヘルパークラス.
 *
 * @package Helper
 * @author pineray
 * @version $Id:$
 */
class SC_Helper_BestProducts
{
    /**
     * おすすめ商品の情報を取得.
     *
     * @param  integer $best_id     おすすめ商品ID
     * @param  boolean $has_deleted 削除されたおすすめ商品も含む場合 true; 初期値 false
     * @return array
     */
    public function getBestProducts($best_id, $has_deleted = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $where = 'best_id = ?';
        if (!$has_deleted) {
            $where .= ' AND del_flg = 0';
        }
        $arrRet = $objQuery->select($col, 'dtb_best_products', $where, array($best_id));

        return $arrRet[0];
    }

    /**
     * おすすめ商品の情報をランクから取得.
     *
     * @param  integer $rank        ランク
     * @param  boolean $has_deleted 削除されたおすすめ商品も含む場合 true; 初期値 false
     * @return array
     */
    public function getByRank($rank, $has_deleted = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $where = 'rank = ?';
        if (!$has_deleted) {
            $where .= ' AND del_flg = 0';
        }
        $arrRet = $objQuery->select($col, 'dtb_best_products', $where, array($rank));

        return $arrRet[0];
    }

    /**
     * おすすめ商品一覧の取得.
     *
     * @param  integer $dispNumber  表示件数
     * @param  integer $pageNumber  ページ番号
     * @param  boolean $has_deleted 削除されたおすすめ商品も含む場合 true; 初期値 false
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
        $table = 'dtb_best_products';
        $objQuery->setOrder('rank');
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
     * おすすめ商品の登録.
     *
     * @param  array    $sqlval
     * @return multiple 登録成功:おすすめ商品ID, 失敗:FALSE
     */
    public function saveBestProducts($sqlval, $updateCreater = false) // Add MGN_20140313 add $updateCreater parameter
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $best_id = $sqlval['best_id'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        // 新規登録
        if ($best_id == '') {
            // INSERTの実行
            if (!$sqlval['rank']) {
                $sqlval['rank'] = $objQuery->max('rank', 'dtb_best_products') + 1;
            }
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $sqlval['best_id'] = $objQuery->nextVal('dtb_best_products_best_id');
            $ret = $objQuery->insert('dtb_best_products', $sqlval);
        // 既存編集
        } else {
			if(!$updateCreater) unset($sqlval['creator_id']);
            unset($sqlval['create_date']);
            $where = 'best_id = ?';
            $ret = $objQuery->update('dtb_best_products', $sqlval, $where, array($best_id));
        }

        return ($ret) ? $sqlval['best_id'] : FALSE;
    }

    /**
     * おすすめ商品の削除.
     *
     * @param  integer $best_id おすすめ商品ID
     * @return void
     */
    public function deleteBestProducts($best_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $table = 'dtb_best_products';
        $arrVal = array('del_flg' => 1);
        $where = 'best_id = ?';
        $arrWhereVal = array($best_id);
        $objQuery->update($table, $arrVal, $where, $arrWhereVal);
    }

    /**
     * おすすめ商品の削除.
     *
     * @param  integer $best_id おすすめ商品ID
     * @return void
     */
    public function deleteBestProducts2($best_id)
    {
        $objDb = new SC_Helper_DB_Ex();
        // ランク付きレコードの削除
        $objDb->sfDeleteRankRecord2('dtb_best_products', 'best_id', $best_id,'', TRUE);
    }

    /**
     * 商品IDの配列からおすすめ商品を削除.
     *
     * @param  array $productIDs 商品ID
     * @return void
     */
    public function deleteByProductIDs($productIDs)
    {
        $objDb = new SC_Helper_DB_Ex();
        $arrList = $this->getList();
        foreach ($arrList as $recommend) {
            if (in_array($recommend['product_id'], $productIDs)) {
                $this->deleteBestProducts($recommend['best_id']);
            }
        }
    }

    /**
     * おすすめ商品の表示順をひとつ上げる.
     *
     * @param  integer $best_id おすすめ商品ID
     * @return void
     */
    public function rankUp($best_id)
    {
        $arrBestProducts = $this->getBestProducts($best_id);
        $rank = $arrBestProducts['rank'];

        if ($rank > 1) {
            // 表示順が一つ上のIDを取得する
            $arrAboveBestProducts = $this->getByRank($rank - 1);
            $above_best_id = $arrAboveBestProducts['best_id'];

            if ($above_best_id) {
                // 一つ上のものを一つ下に下げる
                $this->changeRank($above_best_id, $rank);
            } else {
                // 無ければ何もしない。(歯抜けの場合)
            }

            // 一つ上に上げる
            $this->changeRank($best_id, $rank - 1);
        }
    }

    /**
     * おすすめ商品の表示順をひとつ下げる.
     *
     * @param  integer $best_id おすすめ商品ID
     * @return void
     */
    public function rankDown($best_id)
    {
        $arrBestProducts = $this->getBestProducts($best_id);
        $rank = $arrBestProducts['rank'];

        if ($rank < RECOMMEND_NUM) {
            // 表示順が一つ下のIDを取得する
            $arrBelowBestProducts = $this->getByRank($rank + 1);
            $below_best_id = $arrBelowBestProducts['best_id'];

            if ($below_best_id) {
                // 一つ下のものを一つ上に上げる
                $this->changeRank($below_best_id, $rank);
            } else {
                // 無ければ何もしない。(歯抜けの場合)
            }

            // 一つ下に下げる
            $this->changeRank($best_id, $rank + 1);
        }
    }

    /**
     * 対象IDのrankを指定値に変更する
     *
     * @param integer $best_id 対象ID
     * @param integer $rank 変更したいrank値
     * @return void
     */
    public function changeRank($best_id, $rank)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $table = 'dtb_best_products';
        $sqlval = array('rank' => $rank);
        $where = 'best_id = ?';
        $arrWhereVal = array($best_id);
        $objQuery->update($table, $sqlval, $where, $arrWhereVal);
    }

    /**
     * Add MGN_20140312.
     *
     * @return array
     */
    public function getStaffAllResigsts($dispNumber = 0, $pageNumber = 0, $has_deleted = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $where = '';
        if (!$has_deleted) {
            $where .= 'del_flg = 0';
        }
        $table = 'dtb_staff_regist';
        $objQuery->setOrder('staff_id');
        $objQuery->setOption('asc');
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
     * Add MGN_20140312.
     *
     * @param  integer $cate_id  表示件数
     * @param  integer $dispNumber  表示件数
     * @param  integer $pageNumber  ページ番号
     * @param  boolean $has_deleted 削除されたおすすめ商品も含む場合 true; 初期値 false
     * @return array
     */
    public function getListFromCategory($cate_id = 0, $dispNumber = 0, $pageNumber = 0, $has_deleted = false)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $where = 'category_id = '.$cate_id;
        if (!$has_deleted) {
            $where .= 'and del_flg = 0';
        }
        $table = 'dtb_best_products';
        $objQuery->setOrder('rank');
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
     * Add MGN_20140312.
     *
     * @param  integer $date30 
     * @param  integer $year20 
     * @param  integer $year30 
     * @return array
     */
    public function getReviewDressFor20($date30, $year20, $year30)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
		"and o.del_flg = 0 and c.del_flg = 0 ".
		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year30."' and '".$year20."' ".
		"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
		"group by od.product_id order by avg desc limit 3";
        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

	    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 
     * @param  integer $year30 
     * @param  integer $year40 
     * @return array
     */
    public function getReviewOnepieceFor30($date30, $year30, $year40)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
		"and o.del_flg = 0 and c.del_flg = 0 ".
		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year40."' and '".$year30."' ".
		"and ct.category_id = 1 ".
		"group by od.product_id order by avg desc limit 3";
        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year20 from year
     * @param  integer $year30 to yeaer
     * @return array
     */
    public function getReviewOnepieceFor20($date30, $year20, $year30)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
		"and o.del_flg = 0 and c.del_flg = 0 ".
		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year30."' and '".$year20."' ".
		"and ct.category_id = 1 ".
		"group by od.product_id order by avg desc limit 3";

        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }
	
    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year30 from year
     * @param  integer $year40 to year
     * @return array
     */
    public function getReviewDressFor30($date30, $year30, $year40)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
		"and o.del_flg = 0 and c.del_flg = 0 ".
		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year40."' and '".$year30."' ".
		"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
		"group by od.product_id order by avg desc limit 3";

        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year30 from year
     * @param  integer $year40 to year
     * @return array
     */
    public function getReviewOnepieceFor40($date30, $year40, $year50)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
		"and o.del_flg = 0 and c.del_flg = 0 ".
		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year50."' and '".$year40."' ".
		"and ct.category_id = 1 ".
		"group by od.product_id order by avg desc limit 3";

        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year30 from year
     * @param  integer $year40 to year
     * @return array
     */
    public function getReviewDressFor40($date30, $year40, $year50)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
		"and o.del_flg = 0 and c.del_flg = 0 ".
		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year50."' and '".$year40."' ".
		"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
		"group by od.product_id order by avg desc limit 3";

        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year30 from year
     * @param  integer $year40 to year
     * @return array
     */
    public function getReviewOnepieceFor50($date30, $year50, $year60)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
		"and o.del_flg = 0 and c.del_flg = 0 ".
		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year60."' and '".$year50."' ".
		"and ct.category_id = 1 ".
		"group by od.product_id order by avg desc limit 3";

        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year30 from year
     * @param  integer $year40 to year
     * @return array
     */
    public function getReviewDressFor50($date30, $year50, $year60)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_count) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
		"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
		"and o.del_flg = 0 and c.del_flg = 0 ".
		"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year60."' and '".$year50."' ".
		"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
		"group by od.product_id order by avg desc limit 3";

        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }
	
    /**
     * おすすめ商品の削除.
	 * Add MGN_20140312
     *
     * @param  integer $category_id おすすめ商品ID
     * @return void
     */
    public function deleteBestProductsByCategory($category_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$objQuery->delete('dtb_best_products', "category_id = ?", array($category_id));
    }

    /**
     * おすすめ商品の削除.
	 * Add MGN_20140312
     *
     * @param  integer $category_id おすすめ商品ID
     * @return void
     */
    public function insertCustomRecommendProduct($datas)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$datas['best_id'] = $objQuery->nextVal('dtb_best_products_best_id');
 		$ret = $objQuery->insert('dtb_best_products', $datas);
        return ($ret) ? TRUE : FALSE;
    }
	
    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year20 from year
     * @param  integer $year30 to year
     * @return array
     */
    public function getValueDressFor20($date30, $year20, $year30)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
				"and o.del_flg = 0 and c.del_flg = 0 ".
				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year30."' and '".$year20."' ".
				"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
				"group by od.product_id order by avg desc limit 3";

        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year20 from year
     * @param  integer $year30 to year
     * @return array
     */
    public function getValueOnepieceFor20($date30, $year20, $year30)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
				"and o.del_flg = 0 and c.del_flg = 0 ".
				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year30."' and '".$year20."' ".
				"and ct.category_id = 1 ".
				"group by od.product_id order by avg desc limit 3";
        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }
	
    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year30 from year
     * @param  integer $year40 to year
     * @return array
     */
    public function getValueDressFor30($date30, $year30, $year40)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
				"and o.del_flg = 0 and c.del_flg = 0 ".
				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year40."' and '".$year30."' ".
				"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
				"group by od.product_id order by avg desc limit 3";
        
		$arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year30 from year
     * @param  integer $year40 to year
     * @return array
     */
    public function getValueOnepieceFor30($date30, $year30, $year40)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
				"and o.del_flg = 0 and c.del_flg = 0 ".
				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year40."' and '".$year30."' ".
				"and ct.category_id = 1 ".
				"group by od.product_id order by avg desc limit 3";
        $arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }
	
    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year40 from year
     * @param  integer $year50 to year
     * @return array
     */
    public function getValueDressFor40($date30, $year40, $year50)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
				"and o.del_flg = 0 and c.del_flg = 0 ".
				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year50."' and '".$year40."' ".
				"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
				"group by od.product_id order by avg desc limit 3";

		$arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year40 from year
     * @param  integer $year50 to year
     * @return array
     */
    public function getValueOnepieceFor40($date30, $year40, $year50)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
				"and o.del_flg = 0 and c.del_flg = 0 ".
				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year50."' and '".$year40."' ".
				"and ct.category_id = 1 ".
				"group by od.product_id order by avg desc limit 3";

		$arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year40 from year
     * @param  integer $year50 to year
     * @return array
     */
    public function getValueDressFor50($date30, $year50, $year60)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
				"and o.del_flg = 0 and c.del_flg = 0 ".
				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year60."' and '".$year50."' ".
				"and (ct.category_id = 44 or ct.category_id = 90 or ct.category_id = 148 or ct.category_id = 232) ".
				"group by od.product_id order by avg desc limit 3";

		$arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

    /**
     * Add MGN_20140312.
     *
     * @param  integer $date30 date
     * @param  integer $year40 from year
     * @param  integer $year50 to year
     * @return array
     */
    public function getValueOnepieceFor50($date30, $year50, $year60)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql ="select od.product_id,sum(p.womens_review_avg) as avg from dtb_order o,dtb_order_detail od,dtb_customer c,dtb_products p,dtb_product_categories ct ".
				"where o.order_id = od.order_id and o.customer_id = c.customer_id and od.product_id = p.product_id and  p.product_id = ct.product_id ".
				"and o.del_flg = 0 and c.del_flg = 0 ".
				"and o.sending_date > '".$date30."' and c.birth BETWEEN '".$year60."' and '".$year50."' ".
				"and ct.category_id = 1 ".
				"group by od.product_id order by avg desc limit 3";

		$arrRet = $objQuery->getAll($sql);

        return $arrRet;
    }

}
