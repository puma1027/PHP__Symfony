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
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * おすすめ管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Ranking extends LC_Page {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objQuery = new SC_Query();
        $objSess = new SC_Session();

        $type = $_REQUEST['type'];

        switch($type){
        	case 'review':
        		$this->tpl_subtitle = 'レビューの多い順管理';
        		$this->tpl_subno_recommend = "sp_review";
        		$this->tpl_mainpage = 'ranking/recomend_sp_review.tpl';
        		$this->category_id = 10;
        		$this->tpl_disp_max = 24;
        		break;
        	case 'value':
        		$this->tpl_subtitle = '評価の高い順管理';
        		$this->tpl_subno_recommend = "sp_value";
        		$this->tpl_mainpage = 'ranking/recomend_sp_value.tpl';
        		$this->category_id = 11;
        		$this->tpl_disp_max = 24;
        		break;
        	case 'reco':
        		$this->tpl_subtitle = 'スタッフオススメ管理';
        		$this->tpl_subno_recommend = "sp_reco";
        		$this->tpl_mainpage = 'ranking/recomend_sp_reco.tpl';
        		$this->category_id = 12;
        		//::$this->tpl_disp_max = ADMIN_RECOMMEND_PRODUCT_COUNT;//::N00137 Del 20140331
        		$this->tpl_disp_max = 24;

        		$sql = "SELECT * FROM dtb_staff_regist where del_flg = 0 order by staff_id asc";
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
								 array(  "column" => "del_flg", "convert" => "n"),
								 array(	 "column" => "status", "convert" => "n"),
                                 );

        // 認証可否の判定
        //SC_Utils_Ex::sfIsSuccess($objSess);



        // 既に登録されている内容を取得する
        $sql = "SELECT B.name, B.main_list_image, B.status, B.del_flg as del_flag, A.* FROM dtb_best_products as A INNER JOIN dtb_products as B USING (product_id)
		 WHERE A.del_flg = 0 and A.category_id = ".$this->category_id." ORDER BY rank";
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

        //----　ページ表示
		$this->sendResponse();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    

}
?>
