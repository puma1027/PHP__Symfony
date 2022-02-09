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

require_once CLASS_REALDIR . 'pages/admin/contents/LC_Page_Admin_Contents_Recommend.php';

/**
 * おすすめ商品管理 のページクラス(拡張).
 *
 * LC_Page_Admin_Contents_Recommend をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Contents_Recommend_Ex extends LC_Page_Admin_Contents_Recommend
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    /*** 20200515 sg nakagawa 旧環境のソース**/
    public function action()
    {
        $arrRegistColumn = array(
                array(  "column" => "product_id", "convert" => "n" ),
                array(  "column" => "category_id", "convert" => "n" ),
                array(  "column" => "rank", "convert" => "n" ),
                array(  "column" => "title", "convert" => "aKV" ),
                array(  "column" => "comment", "convert" => "aKV" ),
                );


        //最大登録数の表示
        $this->tpl_disp_max = ADMIN_RECOMMEND_PRODUCT_COUNT;

        if (!isset($_POST['mode'])) $_POST['mode'] = "";
        if (!isset($_POST['category_id'])) $_POST['category_id'] = "";

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
                $this->arrForm['creator_id'] = $_SESSION['member_id'];
                $this->arrForm['update_date'] = "NOW()";
                $this->arrForm['create_date'] = "NOW()";
				$objQuery = SC_Query_Ex::getSingletonInstance();
                $objQuery->insert("dtb_best_products", $this->arrForm );
                //		$conn->autoExecute("dtb_best_products", $this->arrForm );
            }

        } elseif ( $_POST['mode'] == 'delete' ){
            // 削除時

            $sql = "DELETE FROM dtb_best_products WHERE category_id = ? AND rank = ?";
			$objQuery = SC_Query_Ex::getSingletonInstance();
            $objQuery->query($sql, array($_POST['category_id'] ,$_POST['rank']));

        }

        // カテゴリID取得 無いときはトップページ
		$this->category_id = intval($_POST['category_id']);

        // 既に登録されている内容を取得する
        $sql = "SELECT B.name, B.main_list_image, A.* FROM dtb_best_products as A INNER JOIN dtb_products as B USING (product_id)
		 WHERE A.del_flg = 0 AND A.category_id = ".$this->category_id." ORDER BY rank";
		$objQuery = SC_Query_Ex::getSingletonInstance();
        $arrItems = $objQuery->getAll($sql);
        foreach( $arrItems as $data ){
            $this->arrItems[$data['rank']] = $data;
        }

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

}
