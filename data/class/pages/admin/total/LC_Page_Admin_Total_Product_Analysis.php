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
require_once(CLASS_PATH . "SC_Product_Analysis_Pdf.php");

/**
 * 売上集計 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Total_Product_Analysis extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

		$this->tpl_mainpage = 'total/product_analysis.tpl';

		$this->tpl_mainno = 'total';
		$this->tpl_subno = 'product_analysis';
		$this->tpl_subtitle = '売上集計＞商品分析';
		$masterData = new SC_DB_MasterData_Ex();
		$this->arrORDERSTATUS = $masterData->getMasterData("mtb_order_status");
		$this->arrORDERSTATUS_COLOR = $masterData->getMasterData("mtb_order_status_color");
		$this->arrSex = $masterData->getMasterData("mtb_sex");
		$this->arrPageMax = $masterData->getMasterData("mtb_page_max");
		// カテゴリの読込
//            $objDb = new SC_Helper_DB();
//            list($this->arrCatVal, $this->arrCatOut) = $objDb->sfGetLevelCatList(false,0 );
//            list($this->arrSmallCatVal, $this->arrSmallCatOut) = $objDb->sfGetLevelCatList(false,1,0);

		//::$this->arrCatVal = array('90', '148', '64', '63', '65', '44', '1');
		//::$this->arrCatOut = array('ドレス3点セット', 'ドレス4点セット', 'ストール・ボレロ', 'ネックレス', 'その他の小物', 'レンタルドレス', 'レンタルワンピース');
		$this->arrCatVal = array('90', '148', '64', '63', '65', '44', '1', '232');//::N00083 Change 20131201
		$this->arrCatOut = array('ドレス3点セット', 'ドレス4点セット', 'ストール・ボレロ', 'ネックレス', 'その他の小物', 'レンタルドレス', 'レンタルワンピース', 'セットドレス');//::N00083 Change 20131201

		//::$this->arrCatVal = array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, STOLE_PRODUCT_TYPE, NECKLACE_PRODUCT_TYPE, OTHERS_PRODUCT_TYPE);
		//::$this->arrCatOut = array('レンタルワンピース', 'レンタルドレス', 'ドレス3点セット', 'ドレス4点セット', 'ストール・ボレロ', 'ネックレス', 'その他の小物');
		$this->arrCatVal = array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, STOLE_PRODUCT_TYPE, NECKLACE_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, OTHERS_PRODUCT_TYPE);//::N00083 Change 20131201
		$this->arrCatOut = array('レンタルワンピース', 'レンタルドレス', 'ドレス3点セット', 'ドレス4点セット', 'ストール・ボレロ', 'ネックレス', 'セットドレス', 'その他の小物');//::N00083 Change 20131201

		$this->arrSmallCatVal = array('1', '2', '3', '4', '5', '6', '7', '8');
		$this->arrSmallCatOut = array('色', 'サイズ', '丈', 'シルエット', '機能', '袖', 'えりもと', '柄');

		$this->arrCategory = array(
			ONEPIECE_PRODUCT_TYPE => array( //レンタルワンピース
				$this->arrSmallCatVal[0] => array(10, 14, 19, 18, 11, 12, 13, 15), //色
				$this->arrSmallCatVal[1] => array(134, 133, 132, 131, 130), //サイズ
				$this->arrSmallCatVal[2] => array(39, 38, 147), //丈
				$this->arrSmallCatVal[3] => array(-1), //シルエット
				$this->arrSmallCatVal[4] => array(-1), //機能
				$this->arrSmallCatVal[5] => array(25, 26), //袖
				$this->arrSmallCatVal[6] => array(-1), //えりもと
				$this->arrSmallCatVal[7] => array(23, 24) //柄
			),
			DRESS_PRODUCT_TYPE => array( //レンタルドレス
				$this->arrSmallCatVal[0] => array(46, 72, 47, 48, 49, 52, 50, 77, 51, 71), //色
				$this->arrSmallCatVal[1] => array(66, 67, 68, 69, 70), //サイズ
				$this->arrSmallCatVal[2] => array(53, 54, 55, 56), //丈
				$this->arrSmallCatVal[3] => array(59, 60, 75), //シルエット
				$this->arrSmallCatVal[4] => array(74, 73, 76, 61, 62), //機能
				$this->arrSmallCatVal[5] => array(57, 58), //袖
				$this->arrSmallCatVal[6] => array(203, 202, 204, 205), //えりもと
				$this->arrSmallCatVal[7] => array(-1) //柄
			),
			DRESS3_PRODUCT_TYPE => array( //ドレス3点セット
				$this->arrSmallCatVal[0] => array(129, 121, 127, 126, 125, 124, 123, 122, 120, 128), //色
				$this->arrSmallCatVal[1] => array(105, 104, 103, 102, 101), //サイズ
				$this->arrSmallCatVal[2] => array(109, 108, 107, 106), //丈
				$this->arrSmallCatVal[3] => array(119, 118, 117), //シルエット
				$this->arrSmallCatVal[4] => array(115, 114, 113, 116, 112), //機能
				$this->arrSmallCatVal[5] => array(-1), //袖
				$this->arrSmallCatVal[6] => array(216, 215, 217, 218), //えりもと
				$this->arrSmallCatVal[7] => array(-1) //柄
			),
			DRESS4_PRODUCT_TYPE => array( //ドレス4点セット
				$this->arrSmallCatVal[0] => array(178, 171, 177, 176, 175, 174, 173, 172, 170), //色
				$this->arrSmallCatVal[1] => array(155, 154, 153, 152, 151), //サイズ
				$this->arrSmallCatVal[2] => array(159, 158, 157, 156), //丈
				$this->arrSmallCatVal[3] => array(169, 168, 167), //シルエット
				$this->arrSmallCatVal[4] => array(165, 164, 163, 166, 162), //機能
				$this->arrSmallCatVal[5] => array(-1), //袖
				$this->arrSmallCatVal[6] => array(184, 185, 186, 187), //えりもと
				$this->arrSmallCatVal[7] => array(-1) //柄
			),
			STOLE_PRODUCT_TYPE => array( //ストール・ボレロ
				$this->arrSmallCatVal[0] => array(84, 85, 86, 87, 88), //色
				$this->arrSmallCatVal[1] => array(80, 81, 82, 200), //サイズ
				$this->arrSmallCatVal[2] => array(-1), //丈
				$this->arrSmallCatVal[3] => array(-1), //シルエット
				$this->arrSmallCatVal[4] => array(-1), //機能
				$this->arrSmallCatVal[5] => array(-1), //袖
				$this->arrSmallCatVal[6] => array(-1), //えりもと
				$this->arrSmallCatVal[7] => array(-1) //柄
			),
			NECKLACE_PRODUCT_TYPE => array( //ネックレス
				$this->arrSmallCatVal[0] => array(140, 139, 138, 137, 136, 135), //色
				$this->arrSmallCatVal[1] => array(-1), //サイズ
				$this->arrSmallCatVal[2] => array(-1), //丈
				$this->arrSmallCatVal[3] => array(-1), //シルエット
				$this->arrSmallCatVal[4] => array(-1), //機能
				$this->arrSmallCatVal[5] => array(-1), //袖
				$this->arrSmallCatVal[6] => array(-1), //えりもと
				$this->arrSmallCatVal[7] => array(-1) //柄
			),
            //::N00083 Add 20131201
			SET_DRESS_PRODUCT_TYPE => array( //セットドレス
				$this->arrSmallCatVal[0] => array(252, 253, 254, 255, 256, 257, 258, 259, 260), //色
				$this->arrSmallCatVal[1] => array(233, 234, 235, 236, 237), //サイズ
				$this->arrSmallCatVal[2] => array(238, 239, 240, 241), //丈
				$this->arrSmallCatVal[3] => array(251, 250, 249), //シルエット
				$this->arrSmallCatVal[4] => array(248, 247, 246, 245, 244), //機能
				$this->arrSmallCatVal[5] => array(-1), //袖
				$this->arrSmallCatVal[6] => array(265, 266, 267, 268), //えりもと
				$this->arrSmallCatVal[7] => array(-1) //柄
			),
            //::N00083 end 20131201
			OTHERS_PRODUCT_TYPE => array( //その他の小物 //'188,179,145,144,143';TYPE
				$this->arrSmallCatVal[0] => array(), //色
				$this->arrSmallCatVal[1] => array(), //サイズ
				$this->arrSmallCatVal[2] => array(), //丈
				$this->arrSmallCatVal[3] => array(), //シルエット
				$this->arrSmallCatVal[4] => array(), //機能
				$this->arrSmallCatVal[5] => array(), //袖
				$this->arrSmallCatVal[6] => array(), //えりもと
				$this->arrSmallCatVal[7] => array() //柄
			)

		);

//            array_unshift($this->arrCatVal, '0');
//            array_unshift($this->arrCatOut, 'すべて');

		// お届け曜日取得用
		$this->arrWday = $masterData->getMasterData("mtb_wday");

		//表示 「カテゴリ・商品を表示」、「カテゴリのみ表示」、「商品のみ表示」
		$this->arrViewStat = array('カテゴリ・商品を表示', 'カテゴリのみ表示', '商品のみ表示');
		$this->arrOrderBy = array('回転率が高い順番', 'レビュー平均が高い順番', '受注件数が多い順番');
		//登場日
		$this->arrRELEASEDAY = $this->lfGetReleaseday();

		$this->arrDISP = $masterData->getMasterData("mtb_disp");

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
		$objDb = new SC_Helper_DB_Ex();
		$objSess = new SC_Session();
		$objDate = new SC_Date();

		// 登録・更新検索開始年
		$objDate->setStartYear(RELEASE_YEAR);
		$objDate->setEndYear(DATE("Y"));
		$this->arrStartYear = $objDate->getYear();
		$this->arrStartMonth = $objDate->getMonth();
		$this->arrStartDay = $objDate->getDay();
		// 登録・更新検索終了年
		$objDate->setStartYear(RELEASE_YEAR);
		$objDate->setEndYear(DATE("Y"));
		$this->arrEndYear = $objDate->getYear();
		$this->arrEndMonth = $objDate->getMonth();
		$this->arrEndDay = $objDate->getDay();

		// 認証可否の判定
		SC_Utils_Ex::sfIsSuccess($objSess);

		if (!isset($_POST['mode'])) $_POST['mode'] = "";
		if (!isset($arrRet)) $arrRet = array();

		$this->arrForm = $_POST;

//			for($i=0; $i<=$_REQUEST["search_send_date_index"]; $i++){
//				$this->arrForm['search_txt_send_date'.$i]=$_REQUEST['search_txt_send_date'.$i];
//			}

		// 検索ワードの引き継ぎ
		foreach ($_POST as $key => $val) {
            if (preg_match("/^search_/", $key)) { 
				switch ($key) {
					case 'search_category_id':
					case 'search_small_category_id':
					case 'search_status':
						$this->arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
						if (!is_array($val)) {
							$this->arrForm[$key] = preg_split("-", $val);
						}
						break;
					default:
						$this->arrHidden[$key] = $val;
						break;
				}
			}
		}

		switch ($_POST['mode']) {
			case 'pdf':
			case 'search':
				// エラーチェック
				$this->arrErr = $this->lfCheckError();
				// 入力なし
				if (count($this->arrErr) == 0) {
					$where = "";
					$addwhere = "";
					$searchCat = count($this->arrForm['search_category_id']) > 0 ? $this->arrForm['search_category_id'] : $this->arrCatVal;
					$searchSubCat = count($this->arrForm['search_small_category_id']) > 0 ? $this->arrForm['search_small_category_id'] : $this->arrSmallCatVal;

					$subCat = array();
					$subCatStr = array();
					
					foreach ($searchSubCat as $subCatID) {
						$temp = false;
						foreach ($searchCat as $catID) {
							if($catID=='7'){
								$temp=true;
							}else if(count($this->arrCategory[$catID][$subCatID]) > 0){
								$subCat[$subCatID][] = implode(',', $this->arrCategory[$catID][$subCatID]);
							}
						}
						$subCatStr[$subCatID] = implode(',', $subCat[$subCatID]);
						if( $temp){
							$subCatStr['-1'] = '188,179,145,144,143';
						}
					}
					//$subCatStr['-1'] = '-1';
					$cnt = 0;
					foreach ($subCatStr as $key => $itemStr) {						
						if ($itemStr != null && $itemStr !== '') {
							if ($cnt == 0) {
								$str = 'Select';
							} else {
								$str = 'UNION Select';
							}
							$cnt++;
							$addwhere .= $str . " distinct(product_id) , dtb_category.category_id, dtb_category.category_name," . $key . " as category_no
                                        From dtb_product_categories
                                        INNER JOIN dtb_category ON dtb_product_categories.category_id = dtb_category.category_id
                                        Where dtb_product_categories.category_id in (" . $itemStr . ") ";
						}
					}
					foreach ($this->arrForm as $key => $val) {
						if ($val == "") {
							continue;
						}
						$val = SC_Utils_Ex::sfManualEscape($val);

						switch ($key) {
							case 'search_releaseday_id': //登場日
								$where .= " AND P.releaseday_id = ? ";
								$arrval[] = $val;
								break;
							case 'search_startyear': // 登録更新日（FROM）
								$date = SC_Utils_Ex::sfGetTimestamp($_POST['search_startyear'], $_POST['search_startmonth'], $_POST['search_startday']);
								$where .= " AND P.update_date >= ?";
								$arrval[] = $date;
								break;
							case 'search_endyear': // 登録更新日（TO）
								$date = SC_Utils_Ex::sfGetTimestamp($_POST['search_endyear'], $_POST['search_endmonth'], $_POST['search_endday']);
								$where .= " AND P.update_date <= ?";
								$arrval[] = $date;
								break;
							case 'search_sorderyear':
								$date = SC_Utils_Ex::sfGetTimestamp($_POST['search_sorderyear'], $_POST['search_sordermonth'], $_POST['search_sorderday']);
								$where .= " AND O.create_date >= ?";
								$arrval[] = $date;
								break;
							case 'search_eorderyear':
								$date = SC_Utils_Ex::sfGetTimestamp($_POST['search_eorderyear'], $_POST['search_eordermonth'], $_POST['search_eorderday'], true);
								$where .= " AND O.create_date <= ?";
								$arrval[] = $date;
								break;
							case 'search_ssendyear':
								$date = SC_Utils_Ex::sfGetTimestamp($_POST['search_ssendyear'], $_POST['search_ssendmonth'], $_POST['search_ssendday']);
								$where .= " AND O.sending_date >= ?";
								$arrval[] = $date;
								break;
							case 'search_esendyear':
								$date = SC_Utils_Ex::sfGetTimestamp($_POST['search_esendyear'], $_POST['search_esendmonth'], $_POST['search_esendday'], true);
								$where .= " AND O.sending_date <= ?";
								$arrval[] = $date;
								break;
							case 'search_category_id': // カテゴリー
								$comma_category = implode(",", $val);
								if ($comma_category != "") {
									$where .= " AND P.product_type IN (" . $comma_category . ") ";
								}
								break;
							case 'search_status': // ステータス
								$tmp_where = "";
								foreach ($val as $element) {
									if ($element != "") {
										if ($tmp_where == "") {
											$tmp_where .= "AND (P.status = ? ";
										} else {
											$tmp_where .= "OR P.status = ? ";
										}
										$arrval[] = $element;
									}
								}
								if ($tmp_where != "") {
									$tmp_where .= ")";
									$where .= " $tmp_where";
								}
								break;
 //=============== 2012.05.16 RCHJ Add ================
								case 'search_txt_send_date0':
									$where .= " AND (O.sending_date = ?";
									$arrval[] = $val;
									for($i=1;$i<$_REQUEST["search_send_date_index"];$i++){
										$where .= " OR O.sending_date = ? ";
										$arrval[] = $_POST["search_txt_send_date".$i];
									}
									$where .= ") ";

									break;
 //======================== End ================
							default:
								if (!isset($arrval)) $arrval = array();
								break;
						}
					}

					$order_by1 = 'Order By product_type ASC, category_id ASC, order_cnt Desc';
					$order_by2 = 'Order By product_type ASC, category_id ASC, years_n ASC';
					if(isset($_POST['search_orderby'])){
						if($this->arrForm['search_view_stat'] ==2){
							if($_POST['search_orderby'] == 0){//turnover rate
								$order_by1 = 'Order By turnover Desc,product_type ASC';
							}else if($_POST['search_orderby'] == 1){//review count
								$order_by1 = 'Order By review_avg Desc,product_type ASC';
							}else if($_POST['search_orderby'] == 2){//order cout
								$order_by1 = 'Order By order_cnt Desc,product_type ASC';
							}
						}else{
							if($_POST['search_orderby'] == 0){//turnover rate
								$order_by1 = 'Order By product_type ASC, turnover Desc';
							}else if($_POST['search_orderby'] == 1){//review count
								$order_by1 = 'Order By product_type ASC, review_avg Desc';
							}else if($_POST['search_orderby'] == 2){//order cout
								$order_by1 = 'Order By product_type ASC, order_cnt Desc';
							}
						}
					}
					$objQuery = SC_Query_Ex::getSingletonInstance();

					$sql = "SELECT D.product_id, max(P.product_type) as product_type, category_no,
                                        C.category_id as category_id, max(C.category_name) as category_name,
                                        max(D.product_code) as product_code,
                                        max(P.name) as product_name, max(D.price) as price, max(P.main_list_image) as product_image,
                                        sum(D.price) as sum_price, sum(O.payment_total) as total, count(*) as order_cnt,
                                        max(P.mens_review_count) as mens_review_count, max(P.womens_review_count) as womens_review_count,
                                        max(P.mens_review_avg) as mens_review_avg, max(P.womens_review_avg ) as womens_review_avg,
                                        max(P.womens_review_count) as review_cnt,
                                        max(P.womens_review_avg)*max(P.womens_review_count) as review_sum,
                                        max(P.womens_review_avg) as review_avg,
                                        CASE WHEN  extract(month from age (max(P.create_date)))+12*(extract(year from age (max(P.create_date)))) =0  THEN '1'
                                        ELSE extract(month from age (max(P.create_date)))+12*(extract(year from age (max(P.create_date)))) END as period ,
                                         CASE WHEN  extract(month from age (max(P.create_date)))+12*(extract(year from age (max(P.create_date)))) =0  THEN '25'
                                        ELSE  count(*)  *25/(extract(month from age (max(P.create_date)))+12*(extract(year from age (max(P.create_date)))) ) END as turnover 
                                        ,  CASE WHEN  max(O.create_date) = min(O.create_date)  THEN '1'
                                        ELSE  date_part('day', (max(O.create_date) - min(O.create_date)) /7) 
                                        END as week 
                                    FROM dtb_order As O
                                    INNER JOIN dtb_order_detail  As D ON O.order_id = D.order_id
                                    INNER JOIN dtb_products As P ON D.product_id = P.product_id 
                                    INNER JOIN
                                    (
                                        " . $addwhere . "
                                    ) AS C ON P.product_id = C.product_id
                                    WHERE O.del_flg<>1 and O.status not in (6,8) " . $where . "
                                    Group By category_no,C.category_id,D.product_id ".$order_by1 ;

					$arrResults = $objQuery->getAll($sql, $arrval);

					$age_sql = "Select trunc(years)*10 + 5*round(years-trunc(years)) as years_n,
                                    category_no, max(category_id) as category_id,
                                    max(product_type) as product_type, product_id, count(*) as year_cnt
                                From
                                (
										SELECT D.product_id, P.product_type, C.category_no, C.category_id,
												CASE WHEN O.order_birth is not null THEN extract(year from age(O.create_date, order_birth))/10.0
													 ELSE '0'
												END As years 
										FROM dtb_order As O
										INNER JOIN dtb_order_detail  As D ON O.order_id = D.order_id
										INNER JOIN dtb_products As P ON D.product_id = P.product_id 
										INNER JOIN
										(
											" . $addwhere . "
										) AS C ON P.product_id = C.product_id
										LEFT JOIN dtb_review As R ON C.product_id = R.product_id
										WHERE O.del_flg<>1 and O.status not in (6,8) " . $where . "
									) As TT
                            Group By category_no, product_id, trunc(years)*10 + 5*round(years-trunc(years)) 
                            ".$order_by2 ;
					
//													 WHEN trim(R.title) SIMILAR TO '10%' THEN '1'
//													 WHEN trim(R.title) SIMILAR TO '20代前半%' THEN '2'
//													 WHEN trim(R.title) SIMILAR TO '20代後半%' THEN '2.5'
//													 WHEN trim(R.title) SIMILAR TO '30代前半%' THEN '3'
//													 WHEN trim(R.title) SIMILAR TO '30代後半%' THEN '3.5'
//													 WHEN trim(R.title) SIMILAR TO '40代前半%' THEN '4'
//													 WHEN trim(R.title) SIMILAR TO '40代後半%' THEN '4.5'
//													 WHEN trim(R.title) SIMILAR TO '50%' THEN '5'

					$arrAge = $objQuery->getAll($age_sql, $arrval);

					$this->lfCalcSum($arrResults, $arrAge, $this->arrForm['search_view_stat']);

				}
				break;
			default:
				break;
		}

		$objDate = new SC_Date();
		// 登録・更新日検索用
		$objDate->setStartYear(RELEASE_YEAR);
		$objDate->setEndYear(DATE("Y"));
		$this->arrRegistYear = $objDate->getYear();
		// 生年月日検索用
		$objDate->setStartYear(BIRTH_YEAR);
		$objDate->setEndYear(DATE("Y"));
		$this->arrBirthYear = $objDate->getYear();
		// 月日の設定
		$this->arrMonth = $objDate->getMonth();
		$this->arrDay = $objDate->getDay();

		if (empty($this->arrForm['search_view_stat'])) {
			$this->arrForm['search_view_stat'] = 0;
		}

		if (empty($this->arrForm['search_orderby'])) {
			$this->arrForm['search_orderby'] = 0;
		}
		
		$this->tpl_onload = "fnMoveSelect('search_category_id_unselect', 'search_category_id');fnMoveSelect('search_small_category_id_unselect', 'search_small_category_id');";

// =============== 2012.05.16 RCHJ Add ================
		$str_temp = "[";
		for ($i = 0; $i <= $_REQUEST["search_send_date_index"]; $i++) {
			$str_temp .= "'" . (isset($_REQUEST["search_txt_send_date" . $i]) ? $_REQUEST["search_txt_send_date" . $i] : '') . "',";
		}
		$str_temp = trim($str_temp, ",");
		$str_temp .= "];";
		$this->tpl_javascript .= "var send_date_value = " . $str_temp;
// =============== end ================

	}

	function lfCalcSum($arr, $arrAge, $stat)
	{
		$tmp_product =array();
		$productIndex = array();
		$product = array();
		$catRes = array();
		$subCatRes = array();
		$subCatProduct = array();
		$arrCatName = array(ONEPIECE_PRODUCT_TYPE => 'レンタルワンピース',
			DRESS_PRODUCT_TYPE => 'レンタルドレス',
			//::DRESS3_PRODUCT_TYPE => 'ドレス3点セット',
			//::DRESS4_PRODUCT_TYPE => 'ドレス4点セット',
			STOLE_PRODUCT_TYPE => 'ストール・ボレロ',
			NECKLACE_PRODUCT_TYPE => 'ネックレス',
			SET_DRESS_PRODUCT_TYPE=> 'セットドレス',//::N00083 Add 20131201
			OTHERS_PRODUCT_TYPE => 'その他の小物');

		$arrSubCatName = array('1' => '色', '2' => 'サイズ', '3' => '丈', '4' => 'シルエット', '5' => '機能', '6' => '袖', '7' => 'えりもと', '8' => '柄');

        $objQuery = SC_Query_Ex::getSingletonInstance();
		$all_query = 'SELECT dtb_category.category_id ,count(*) AS cnt
					FROM dtb_products
					INNER JOIN dtb_product_categories ON dtb_products.product_id = dtb_product_categories.product_id
					INNER JOIN dtb_category ON dtb_product_categories.category_id = dtb_category.category_id
					WHERE dtb_products.del_flg <>1 AND dtb_category.del_flg<>1 and dtb_products.status = 1
					GROUP BY dtb_category.category_id';
		$arrRes = $objQuery->getAll($all_query);
		$catProductCnt = array();
		foreach($arrRes as $item){
			$catProductCnt[$item['category_id']]  = $item['cnt'];
		}
		$all_query = 'SELECT dtb_products.product_type as type,count(*) AS cnt
			FROM dtb_products 
			WHERE dtb_products.del_flg <>1 and dtb_products.status = 1
			GROUP BY type';
		$arrRes = $objQuery->getAll($all_query);
		$allProductCnt = array();
		foreach($arrRes as $item){
			$allProductCnt[$item['type']]  += $item['cnt'];
		}

		$this->times = 0;
		foreach ($arr as $item) {
			if(empty($subCatRes[$item['product_type']][$item['category_id']]['week'])){
				$subCatRes[$item['product_type']][$item['category_id']]['week'] = $item['week'];
			}else if(!empty($subCatRes[$item['product_type']][$item['category_id']]['week']) && $subCatRes[$item['product_type']][$item['category_id']]['week']<$item['week']){
				$subCatRes[$item['product_type']][$item['category_id']]['week'] = $item['week'];
			}

			$subCatRes[$item['product_type']][$item['category_id']]['all_product_cnt'] = $catProductCnt[$item['category_id']];
			$subCatRes[$item['product_type']][$item['category_id']]['product_cnt'] ++;
			$subCatRes[$item['product_type']][$item['category_id']]['category_no'] = $item['category_no'];
			$subCatRes[$item['product_type']][$item['category_id']]['category_name'] = !empty($arrSubCatName[$item['category_no']]) ? $arrSubCatName[$item['category_no']]. '＞' . $item['category_name']:$item['category_name'];
			$subCatRes[$item['product_type']][$item['category_id']]['order_cnt'] += $item['order_cnt'];
			$subCatRes[$item['product_type']][$item['category_id']]['total'] += $item['total'];
			$subCatRes[$item['product_type']][$item['category_id']]['review_cnt'] += $item['review_cnt'];
			$subCatRes[$item['product_type']][$item['category_id']]['review_sum'] += $item['review_sum'];
			$subCatRes[$item['product_type']][$item['category_id']]['category_id'] = $item['category_id'];
			//$subCatRes[$item['product_type']][$item['category_id']]['turnover'] = $subCatRes[$item['product_type']][$item['category_id']]['order_cnt']*100/$subCatRes[$item['product_type']][$item['category_id']]['all_product_cnt']/$item['week'];

			if(empty($catRes[$item['product_type']]['week'])){
				$catRes[$item['product_type']]['week'] = $item['week'];
			}else if(!empty($catRes[$item['product_type']]['week']) && $catRes[$item['product_type']]['week']<$item['week']){
				$catRes[$item['product_type']]['week'] = $item['week'];
			}
			$catRes[$item['product_type']]['all_product_cnt'] = $allProductCnt[$item['product_type']];
			$catRes[$item['product_type']]['product_cnt'] ++;
			$catRes[$item['product_type']]['product_type'] = $item['product_type'];
			$catRes[$item['product_type']]['category_name'] = $arrCatName[$item['product_type']];

			$catProType[$item['product_id']] = $item['product_type'];
			$catOrderCnt[$item['product_id']] = $item['order_cnt'];
			$catTotal[$item['product_id']] = $item['total'];
			$catReviewCnt[$item['product_id']] = $item['review_cnt'];
			$catReviewSum[$item['product_id']] = $item['review_sum'];

			if($_POST['search_view_stat']==2){
				$tmp_product[$item['product_id']] = $item;
				$productIndex[] = $item['product_id'];
			}else{
				$tmp_product[$item['product_id']] = $item;
				$productIndex[] = $item['product_id'];
				$subCatProduct[$item['product_type']][$item['category_id']][] = $item;
			}
			$times[$item['product_id']] = $item['order_cnt'];
		}

		$productIndex = array_unique($productIndex);
		foreach($productIndex as $index){
			$product[] = $tmp_product[$index];
		}
		foreach($times as $key=> $val){
			$catRes[$catProType[$key]]['order_cnt'] += $catOrderCnt[$key];
			$catRes[$catProType[$key]]['total'] += $catTotal[$key];
			$catRes[$catProType[$key]]['review_cnt'] += $catReviewCnt[$key];
			$catRes[$catProType[$key]]['review_sum'] += $catReviewSum[$key];
		}

		if(isset($_POST['search_orderby'])){
			if($_POST['search_orderby'] ==0 ){
				$sortSubCatRes = array();
				foreach($subCatRes as $key => $item){
					$tmp = array();
					foreach($item as $subKey => $sub){
						$item[$subKey]['turnover'] = $sub['order_cnt']*100/$sub['all_product_cnt']/$sub['week'];
					}
					$tmp = $item;

					foreach ($tmp as $keys => $row) {
						$turnover[$keys]  = $row['turnover'];
					}

					array_multisort($turnover, SORT_DESC,  $tmp);

					$sortSubCatRes[$key] = $tmp;
				}
				$subCatRes = $sortSubCatRes;
			}else if($_POST['search_orderby'] ==1){
				$sortSubCatRes = array();
				foreach($subCatRes as $key => $item){
					$tmp = array();
					foreach($item as $subKey => $sub){
						$item[$subKey]['review_avg'] = $sub['review_sum']*100/$sub['review_cnt'];
					}
					$tmp = $item;

					foreach ($tmp as $keys => $row) {
						$review_avg[$keys]  = $row['review_avg'];
					}

					array_multisort($review_avg, SORT_DESC,  $tmp);

					$sortSubCatRes[$key] = $tmp;
				}
				$subCatRes = $sortSubCatRes;
			}else if($_POST['search_orderby'] ==2){
				$sortSubCatRes = array();
				foreach($subCatRes as $key => $item){
					$tmp = array();
					$tmp = $item;

					foreach ($tmp as $keys => $row) {
						$order_cnt[$keys]  = $row['order_cnt'];
					}

					array_multisort($order_cnt, SORT_DESC,  $tmp);

					$sortSubCatRes[$key] = $tmp;
				}
				$subCatRes = $sortSubCatRes;
			}
		}


		$this->arrCatSum = $catRes;
		$this->arrSubCatSum = $subCatRes;
		$this->arrSubCatProduct = $subCatProduct;
		$this->arrProduct = $product;

		//count
		$this->search_cnt = count($this->arrProduct);

		$subCatAge = array();
		$catAge = array();
		$productAge = array();
		$proAge = array();
		$catAgeAll = array();
		$subCatAgeAll = array();
		$productAgeAll = array();
		$proAgeAll = array();
		$ageAll = 0;

		foreach ($arrAge as $item) {
			if ($item['years_n'] == 0) {
				$catAge[$item['product_type']]['age0'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age0'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age0'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age0'] += $item['year_cnt'];
			} else if ($item['years_n'] ==10 || $item['years_n'] ==15 ) {
				$catAge[$item['product_type']]['age1'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age1'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age1'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age1'] += $item['year_cnt'];
			} else if ($item['years_n'] == 20) {
				$catAge[$item['product_type']]['age2'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age2'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age2'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age2'] += $item['year_cnt'];
			} else if ($item['years_n'] == 25) {
				$catAge[$item['product_type']]['age3'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age3'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age3'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age3'] += $item['year_cnt'];
			} else if ($item['years_n'] == 30) {
				$catAge[$item['product_type']]['age4'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age4'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age4'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age4'] += $item['year_cnt'];
			} else if ($item['years_n'] == 35) {
				$catAge[$item['product_type']]['age5'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age5'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age5'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age5'] += $item['year_cnt'];
			} else if ($item['years_n'] == 40) {
				$catAge[$item['product_type']]['age6'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age6'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age6'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age6'] += $item['year_cnt'];
			} else if ($item['years_n'] == 45) {
				$catAge[$item['product_type']]['age7'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age7'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age7'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age7'] += $item['year_cnt'];
			} else if ($item['years_n'] >= 50) {
				$catAge[$item['product_type']]['age8'] += $item['year_cnt'];
				$subCatAge[$item['product_type']][$item['category_id']]['age8'] += $item['year_cnt'];
				$productAge[$item['product_type']][$item['category_id']][$item['product_id']]['age8'] += $item['year_cnt'];
				$proAge[$item['product_id']]['age8'] += $item['year_cnt'];
			}
			$ageAll += $item['year_cnt'];
			$catAgeAll[$item['product_type']] += $item['year_cnt'];
			$subCatAgeAll[$item['product_type']][$item['category_id']] += $item['year_cnt'];
			$productAgeAll[$item['product_type']][$item['category_id']][$item['product_id']] += $item['year_cnt'];
			$proAgeAll[$item['product_id']] += $item['year_cnt'];
		}

		$this->catAge = $catAge;
		$this->subCatAge = $subCatAge;
		$this->productAge = $productAge;
		$this->proAge = $proAge;

		$this->ageAll = $ageAll;
		$this->catAgeAll = $catAgeAll;
		$this->subCatAgeAll = $subCatAgeAll;
		$this->productAgeAll = $productAgeAll;
		$this->proAgeAll = $proAgeAll;
		if ($_POST['mode'] == 'pdf') {

			$objFpdf = new SC_Product_Analysis_Pdf(1);
			$objFpdf->setData($this->arrCatSum, $this->arrSubCatSum, $this->arrSubCatProduct,$this->arrProduct, $this->catAge, $this->subCatAge, $this->productAge, $this->ageAll, $this->catAgeAll, $this->subCatAgeAll, $this->productAgeAll, $stat);
			$objFpdf->createPdf();
			exit;
		}
	}

	/* 入力内容のチェック */
	function lfCheckError()
	{
		$objErr = new SC_CheckError();

		// 特殊項目チェック
		$objErr->doFunc(array("開始日", "search_startyear", "search_startmonth", "search_startday"), array("CHECK_DATE"));
		$objErr->doFunc(array("終了日", "search_endyear", "search_endmonth", "search_endday"), array("CHECK_DATE"));
		$objErr->doFunc(array("開始日", "終了日", "search_startyear", "search_startmonth", "search_startday", "search_endyear", "search_endmonth", "search_endday"), array("CHECK_SET_TERM"));

		$objErr->doFunc(array("開始日", "search_sorderyear", "search_sordermonth", "search_sorderday"), array("CHECK_DATE"));
		$objErr->doFunc(array("終了日", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_DATE"));
		$objErr->doFunc(array("開始日", "終了日", "search_sorderyear", "search_sordermonth", "search_sorderday", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_SET_TERM"));

		return $objErr->arrErr;
	}

	function lfGetReleaseday()
	{
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$where = "del_flg <> 1";
		$objQuery->setOrder("rank DESC");
		$results = $objQuery->select("releaseday_id, title", "dtb_releaseday", $where);
		foreach ($results as $result) {
			$arrReleaseday[$result['releaseday_id']] = $result['title'];
		}
		return $arrReleaseday;
	}

	function lfGetSendEnableTimes($start = '2012-05-01', $end = '2012-05-31')
	{

		$start_stamp = strtotime($start);
		$end_stamp = strtotime($end);

		$term = ($end_stamp - $start_stamp) / 86400;
		$times = floor($term / 7);

		$start_weekday = date('w', $start_stamp);
		$end_weekday = date('w', $end_stamp);

		if ($start_weekday > $end_weekday) {
			if ($start_weekday <= 4) {
				$times++;
			}
			if ($end_weekday >= 3) {
				$times++;
			}
		} else if ($start_weekday < $end_weekday) {
			if ($start_weekday <= 3 && $end_weekday >= 4) {
				$times++;
			}
		} else {
			if ($start_weekday == 3 || $end_weekday == 4) {
				$times++;
			}
		}

		//holiday
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$sql = "SELECT month , day ,title FROM dtb_holiday WHERE del_flg<>1";

		$h = $objQuery->getAll($sql);

		$next_thur = strtotime("next thursday", $start_stamp - 86400);
		$last_wed = strtotime("previous wednesday", $end_stamp + 86400);

		$start_year = date('Y', $start_stamp);
		$end_year = date('Y', $end_stamp);
		foreach ($h as $item) {
			$holiday1 = mktime(0, 0, 0, $item['month'], $item['day'], $start_year);
			$holiday1 = $holiday1 - 2 * 86400;
			if ($holiday1 < $next_thur + 5 * 86400 && $holiday1 <= $end_stamp && $holiday1 >= $start_stamp) {
				$times++;
				break;
			}
			$holiday2 = mktime(0, 0, 0, $item['month'], $item['day'], $end_year);
			$holiday2 = $holiday2 - 2 * 86400;
			if ($holiday2 > $last_wed + 5 * 86400 && $holiday2 <= $end_stamp && $holiday2 >= $start_stamp) {
				$times++;
				break;
			}
		}
//		echo $times, "\n";

		return $times;
	}


 
}
