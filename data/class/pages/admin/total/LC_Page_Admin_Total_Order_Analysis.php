<?php
    // {{{ requires
	require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
    require_once(CLASS_PATH . "SC_Order_Analysis_Pdf.php"); // Change KH 2014/3/11
    /**
     * 受注分析 のページクラス.
     *
     * @package Page
     * @author  EC-CUBE CO.,LTD.
     * @version $Id$
     */
    class LC_Page_Admin_Total_Order_Analysis extends LC_Page_Admin_Ex
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
            $objFpdf = new SC_Order_Analysis_Pdf(1);
            //$objFpdf->setData($arrResults, $arrOrderResults, $arrAgeResults, $arrCatResults);
            //$objFpdf->createPdf();


            parent::init();
            $this->tpl_mainpage = 'total/order_analysis.tpl';
           // $this->tpl_subnavi = 'total/subnavi.tpl';
            $this->tpl_mainno = 'total';
            $this->tpl_subno = 'order_analysis';
            //$this->tpl_pager = TEMPLATE_REALDIR . 'admin/pager.tpl';
            $this->tpl_subtitle = '売上集計＞受注分析';

            $masterData = new SC_DB_MasterData_Ex();
            $this->arrORDERSTATUS = $masterData->getMasterData("mtb_order_status");
            $this->arrORDERSTATUS_COLOR = $masterData->getMasterData("mtb_order_status_color");
            $this->arrPageMax = $masterData->getMasterData("mtb_page_max");
			
            /* ペイジェント決済モジュール連携用 */
            if (function_exists("sfPaygentOrderPage")) {
                $this->arrDispKind = sfPaygentOrderPage();
            }
            // お届け曜日取得用
            $this->arrWday = $masterData->getMasterData("mtb_wday");
            //
            $this->arrTotal = array(1 => '注文回数', 2 => '年代', 3 => '商品カテゴリ', 4 => '都道府県');
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
			
            // パラメータ管理クラス
            $this->objFormParam = new SC_FormParam();
            // パラメータ情報の初期化
            $this->lfInitParam();
            $this->objFormParam->setParam($_POST);
			
            // 検索ワードの引き継ぎ
            foreach ($_POST as $key => $val) {
                if (preg_match("/^search_/", $key)) { 
                    switch ($key) {
                        case 'search_order_total':
                            $this->arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
                            break;
                        default:
                            $this->arrHidden[$key] = $val;
                            break;
                    }
                }
            }

            // 認証可否の判定
            SC_Utils_Ex::sfIsSuccess($objSess);

            if (!isset($_POST['mode'])) $_POST['mode'] = "";
            if (!isset($arrRet)) $arrRet = array();

			switch ($_POST['mode']) {
                case 'pdf':
                case 'search':
                    // 入力値の変換
                    $this->objFormParam->convParam();
                    $this->arrErr = $this->lfCheckError($arrRet);
                    $arrRet = $this->objFormParam->getHashArray();
                    // 入力なし
                    if (SC_Utils_Ex::isBlank($this->arrErr)) {
                        $where = "dtb_order.del_flg <> 1";
                        foreach ($arrRet as $key => $val) {
                            if (SC_Utils_Ex::isBlank($val)) {
                                continue;
                            }
							
                            $val = SC_Utils_Ex::sfManualEscape($val);
                            switch ($key) {
                                case 'search_order_id1':
                                    $where .= " AND dtb_order.order_id >= ?";
                                    $arrval[] = $val;
                                    break;
                                case 'search_order_id2':
                                    $where .= " AND dtb_order.order_id <= ?";
                                    $arrval[] = $val;
                                    break;
                                case 'search_sorderyear':
                                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_sorderyear'], $_POST['search_sordermonth'], $_POST['search_sorderday']);
                                    $where .= " AND dtb_order.create_date >= ?";
                                    $arrval[] = $date;
                                    break;
                                case 'search_eorderyear':
                                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_eorderyear'], $_POST['search_eordermonth'], $_POST['search_eorderday'], true);
                                    $where .= " AND dtb_order.create_date <= ?";
                                    $arrval[] = $date;
                                    break;
                                case 'search_ssendyear':
                                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_ssendyear'], $_POST['search_ssendmonth'], $_POST['search_ssendday']);
                                    $where .= " AND dtb_order.sending_date >= ?";
                                    $arrval[] = $date;
                                    break;
                                case 'search_esendyear':
                                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_esendyear'], $_POST['search_esendmonth'], $_POST['search_esendday'], true);
                                    $where .= " AND dtb_order.sending_date <= ?";
                                    $arrval[] = $date;
                                    break;
                                case 'search_order_status':
                                    $where .= " AND dtb_order.status = ?";
                                    $arrval[] = $val;
                                    break;
// =============== 2012.05.16 RCHJ Add ================
								case 'search_txt_send_date0':
									$where .= " AND (dtb_order.sending_date = ?";
									$arrval[] = $val;
									for($i=1;$i<$_REQUEST["search_send_date_index"];$i++){
										$where .= " OR dtb_order.sending_date = ? ";
										$arrval[] = $arrRet["search_txt_send_date".$i];
									}
									$where .= ") ";

									break;
// ======================== End ================
                                default:
                                    if (!isset($arrval)) $arrval = array();
                                    break;
                            }
                        }

                        switch ($_POST['mode']) {
                            case 'pdf':
                                $this->lfPdfOutput($where, $arrval);

                                break;
                            default:
                                $objQuery = SC_Query_Ex::getSingletonInstance(); // Change KH 2014/3/12

                                // 集計項目 = 注文回数
                                $order_sql = "Select A.cnt , sum(A.money) as sub_money, count(*) as sub_cnt
                                From
                                (
                                    Select customer_id, sum(payment_total) as money, count(*) as cnt
                                    from dtb_order
                                    where " . $where . " group by customer_id
                                ) As A
                                Group By A.cnt
                                Order By A.cnt";

                                $this->arrOrderResults = $objQuery->getAll($order_sql, $arrval); // Change KH 2014/3/12

                                if (!empty($this->arrOrderResults)) {
                                    $total_money = 0;
                                    $total_cnt = 0;
                                    $other_money = 0;
                                    $other_cnt = 0;
                                    $cus_money = 0;
                                    $cus_cnt = 0;
                                    foreach ($this->arrOrderResults as $item) {
                                        $total_money += $item['sub_money'];
                                        $total_cnt += $item['cnt']*$item['sub_cnt'];
                                        if ($item['cnt'] == 1) {
                                            $this->arrOrderResults1 = $item;
                                        }
                                        if ($item['cnt'] == 2) {
                                            $this->arrOrderResults2 = $item;
                                        }
                                        if ($item['cnt'] == 3) {
                                            $this->arrOrderResults3 = $item;
                                        }
                                        if ($item['cnt'] == 4) {
                                            $this->arrOrderResults4 = $item;
                                        }
                                        if ($item['cnt'] >= 5) {
                                            $other_money += $item['sub_money'];
                                            $other_cnt += $item['sub_cnt'];
                                        }
                                        if ($item['cnt'] > 1) {
                                            $cus_money += $item['sub_money'];
                                            $cus_cnt += $item['sub_cnt'];
                                        }
                                    }

                                    $this->arrOrderResultsCus['sub_money'] = $cus_money;
                                    $this->arrOrderResultsCus['sub_cnt'] = $cus_cnt;

                                    $this->arrOrderResults5['sub_money'] = $other_money;
                                    $this->arrOrderResults5['sub_cnt'] = $other_cnt;

                                    $this->arrResults['total_money'] = $total_money;
                                    $this->arrResults['total_cnt'] = $total_cnt;
                                }

                                if (empty($arrRet['search_order_total1'])) {
                                    unset($this->arrOrderResults);
                                }

                                // 集計項目 = 「年代」
                                if (!empty($arrRet['search_order_total2']) && $arrRet['search_order_total2'] == '1') {
                                    $age_sql = "Select sum(payment_total) as sub_money, count(*) as sub_cnt ,
                                            trunc(years)*10 + 5*round(years-trunc(years)) as years_n
                                        From
                                        (
                                            SELECT payment_total,
                                                   CASE WHEN order_birth is null THEN '0'
                                                    ELSE extract(year from age(create_date, order_birth))/10.0
                                                   END As years
                                            FROM dtb_order
                                            WHERE " . $where . "
                                        ) As A
                                        GROUP BY (trunc(years)*10 + 5*round(years-trunc(years)))
                                        Order By years_n";

                                    $this->arrAgeResults = $objQuery->getAll($age_sql, $arrval); // Change KH 2014/3/12
                                    if (!empty($this->arrAgeResults)) {
                                        $other_money = 0;
                                        $other_cnt = 0;
                                        foreach ($this->arrAgeResults as $item) {
											if ($item['years_n']==0) {
												$this->arrAgeResults0 = $item;
											}
                                            if ($item['years_n'] ==10 || $item['years_n'] ==15) {
                                                $this->arrAgeResults1 = $item;
                                            }
                                            if ($item['years_n'] == 20) {
                                                $this->arrAgeResults2 = $item;
                                            }
                                            if ($item['years_n'] == 25) {
                                                $this->arrAgeResults2a = $item;
                                            }
                                            if ($item['years_n'] == 30) {
                                                $this->arrAgeResults3 = $item;
                                            }
                                            if ($item['years_n'] == 35) {
                                                $this->arrAgeResults3a = $item;
                                            }
                                            if ($item['years_n'] == 40) {
                                                $this->arrAgeResults4 = $item;
                                            }
                                            if ($item['years_n'] == 45) {
                                                $this->arrAgeResults4a = $item;
                                            }
                                            if ($item['years_n'] >= 50) {
                                                $other_money += $item['sub_money'];
                                                $other_cnt += $item['sub_cnt'];
                                            }
                                        }
                                        $this->arrAgeResults5['sub_money'] = $other_money;
                                        $this->arrAgeResults5['sub_cnt'] = $other_cnt;
                                    }
                                }
                                // 集計項目 = 「商品カテゴリ」
                                if (!empty($arrRet['search_order_total3']) && $arrRet['search_order_total3'] == '1') {
                                    $category_sql = "SELECT A.product_type, A.p_cnt, C.o_cnt, C.money
                                            FROM
                                            (
                                                Select dtb_products.product_type, count(*) as  p_cnt
                                                FROM dtb_order
                                                    inner join dtb_order_detail on  dtb_order_detail.order_id = dtb_order.order_id
                                                    inner join dtb_products on dtb_order_detail.product_id = dtb_products.product_id
                                                WHERE " . $where . " GROUP BY dtb_products.product_type
                                            ) AS A
                                            INNER JOIN
                                            (
                                                Select  product_type, sum(payment_total) As money, count(*) as o_cnt 
                                                From
                                                (
                                                    Select distinct(dtb_order.order_id ), dtb_products.product_type, dtb_order.payment_total 
                                                    FROM dtb_order
                                                        inner join dtb_order_detail on  dtb_order_detail.order_id = dtb_order.order_id
                                                        inner join dtb_products on dtb_order_detail.product_id = dtb_products.product_id
                                                    WHERE " . $where . " ) AS B
                                                Group By product_type
                                            ) AS C
                                            ON A.product_type = C.product_type

                                            UNION
                                            Select '0',count(*) as p_cnt, count(*) as o_cnt, sum(payment_total) as money
                                            From dtb_order
                                            WHERE  " . $where . " Order By product_type";
                                    $arrval1 = array_merge($arrval, array_merge($arrval, $arrval));

									$all_query = 'SELECT dtb_products.product_type as type,count(*) AS cnt
										FROM dtb_products 
										WHERE dtb_products.del_flg <>1 and dtb_products.status = 1
										GROUP BY type';
									$arrRes = $objQuery->getAll($all_query); // Change KH 2014/3/12
									$this->allProductCnt = array();
									foreach($arrRes as $item){
										$this->allProductCnt[$item['type']]  += $item['cnt'];
									}
									
                                    $this->arrCatResults = $objQuery->getAll($category_sql, $arrval1); // Change KH 2014/3/12
                                    if (!empty($this->arrCatResults)) {
                                        foreach ($this->arrCatResults as $item) {
											//MODIFIED CHS 20140421
											$name = 'arrCatResults' . $item['product_type'];
                                            $this->$name = $item;
                                        }
                                    }
                                }
                                // 集計項目 = 「都道府県」
                                if (!empty($arrRet['search_order_total4']) && $arrRet['search_order_total4'] == '1') { 
									// Change KH sql query 2014/3/13
                                    $pref_sql = "Select id, name, sub_money, sub_cnt
                                    From mtb_pref
                                    Left join
                                    (
                                        Select CAST(order_pref as int4) as order_pref, sum(payment_total) as sub_money, count(*) as sub_cnt
                                        from dtb_order
                                        where " . $where . " group by order_pref
                                    ) As A
                                    On mtb_pref.id = A.order_pref
                                    Order By mtb_pref.rank";
									
                                    $this->arrPrefResults = $objQuery->getAll($pref_sql, $arrval); // Change KH 2014/3/12
													
                                    if (!empty($this->arrPrefResults)) {
                                        $money = array_fill(0, 7, 0);
                                        $cnt = array_fill(0, 7, 0);

                                        foreach ($this->arrPrefResults as $item) {
                                            $this->arrPrefResult[$item['id']] = $item; // Change pref_id to id  by KH 2014/3/13
                                            $name = 'arrPrefResults' . $item['id'];
                                            $this->$name = $item;
                                            if ($item['id'] >= 2 && $item['id'] < 8) {
                                                $money[0] += $item['sub_money'];
                                                $cnt[0] += $item['sub_cnt'];
                                            } else if ($item['id'] >= 8 && $item['id'] < 15) {
                                                $money[1] += $item['sub_money'];
                                                $cnt[1] += $item['sub_cnt'];
                                            } else if ($item['id'] >= 15 && $item['id'] < 24) {
                                                $money[2] += $item['sub_money'];
                                                $cnt[2] += $item['sub_cnt'];
                                            } else if ($item['id'] >= 24 && $item['id'] < 31) {
                                                $money[3] += $item['sub_money'];
                                                $cnt[3] += $item['sub_cnt'];
                                            } else if ($item['id'] >= 31 && $item['id'] < 36) {
                                                $money[4] += $item['sub_money'];
                                                $cnt[4] += $item['sub_cnt'];
                                            } else if ($item['id'] >= 36 && $item['id'] < 40) {
                                                $money[5] += $item['sub_money'];
                                                $cnt[5] += $item['sub_cnt'];
                                            } else if ($item['id'] >= 40) {
                                                $money[6] += $item['sub_money'];
                                                $cnt[6] += $item['sub_cnt'];
                                            }
                                        }
                                        $this->arrPrefSumResults = array($money, $cnt);
                                    }
                                }

                        }
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
            // 月日の設定
            $this->arrMonth = $objDate->getMonth();
            $this->arrDay = $objDate->getDay();

            // 入力値の取得
            $this->arrForm = $this->objFormParam->getFormParamList();

// =============== 2012.05.16 RCHJ Add ================
			$str_temp = "[";
			for($i=0; $i<=$_REQUEST["search_send_date_index"]; $i++){
				$str_temp .= "'".(isset($_REQUEST["search_txt_send_date".$i])?$_REQUEST["search_txt_send_date".$i]:'')."',";
			}
			$str_temp = trim($str_temp, ",");
			$str_temp .= "];";
			$this->tpl_javascript .= "var send_date_value = ".$str_temp;
// =============== end ================
			
        }

        /* パラメータ情報の初期化 */
        function lfInitParam()
        {
            $this->objFormParam->addParam("注文番号1", "search_order_id1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("注文番号2", "search_order_id2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("対応状況", "search_order_status", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sorderyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sordermonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("開始日", "search_sorderday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eorderyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eordermonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("終了日", "search_eorderday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));

//            $this->objFormParam->addParam("開始日", "search_ssendyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
//            $this->objFormParam->addParam("開始日", "search_ssendmonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
//            $this->objFormParam->addParam("開始日", "search_ssendday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
//            $this->objFormParam->addParam("終了日", "search_esendyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
//            $this->objFormParam->addParam("終了日", "search_esendmonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
//            $this->objFormParam->addParam("終了日", "search_esendday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));

            $this->objFormParam->addParam("注文回数", "search_order_total1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("年代", "search_order_total2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("商品カテゴリ", "search_order_total3", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("都道府県", "search_order_total4", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));

// =============== 2012.05.16 RCHJ Change & Add ================
			//$this->objFormParam->addParam("お届け曜日", "search_order_deliv_day", INT_LEN, "n", array("MAX_LENGTH_CHECK"));
			for($i=0; $i<=$_REQUEST["search_send_date_index"]; $i++){
				$this->objFormParam->addParam("発送日", "search_txt_send_date".$i);
			}
// ====================== end ====================
			
        }

        /* 入力内容のチェック */
        function lfCheckError()
        {
            // 入力データを渡す。
            $arrRet = $this->objFormParam->getHashArray();
            $objErr = new SC_CheckError($arrRet);
            $objErr->arrErr = $this->objFormParam->checkError();

            // 特殊項目チェック
            $objErr->doFunc(array("注文番号1", "注文番号2", "search_order_id1", "search_order_id2"), array("GREATER_CHECK"));
            $objErr->doFunc(array("開始日", "search_sorderyear", "search_sordermonth", "search_sorderday"), array("CHECK_DATE"));
            $objErr->doFunc(array("終了日", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_DATE"));
            $objErr->doFunc(array("開始日", "終了日", "search_sorderyear", "search_sordermonth", "search_sorderday", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_SET_TERM"));

            $objErr->doFunc(array("開始日", "search_ssendyear", "search_ssendmonth", "search_ssendday"), array("CHECK_DATE"));
            $objErr->doFunc(array("終了日", "search_esendyear", "search_esendmonth", "search_esendday"), array("CHECK_DATE"));
            $objErr->doFunc(array("開始日", "終了日", "search_ssendyear", "search_ssendmonth", "search_ssendday", "search_esendyear", "search_esendmonth", "search_esendday"), array("CHECK_SET_TERM"));

            return $objErr->arrErr;
        }

        function lfPdfOutput($where, $arrval)
        {
			 $objQuery = SC_Query_Ex::getSingletonInstance(); // Change KH 2014/3/12


            // 集計項目 = 注文回数
            $order_sql = "Select A.cnt , sum(A.money) as sub_money, count(*) as sub_cnt
                                From
                                (
                                    Select customer_id, sum(payment_total) as money, count(*) as cnt
                                    from dtb_order
                                    where " . $where . " group by customer_id
                                ) As A
                                Group By A.cnt
                                Order By A.cnt";

            $res = $objQuery->getAll($order_sql, $arrval); // Change KH 2014/3/12

            if (!empty($res)) {
                $total_money = 0;
                $total_cnt = 0;
                $other_money = 0;
                $other_cnt = 0;
                $cus_money = 0;
                $cus_cnt = 0;
                foreach ($res as $item) {
                    $total_money += $item['sub_money'];
                    $total_cnt += $item['cnt']*$item['sub_cnt'];
                    if ($item['cnt'] == 1) {
                        $arrOrderResults[0] = $item;
                    }
                    if ($item['cnt'] == 2) {
                        $arrOrderResults[2] = $item;
                    }
                    if ($item['cnt'] == 3) {
                        $arrOrderResults[3] = $item;
                    }
                    if ($item['cnt'] == 4) {
                        $arrOrderResults[4] = $item;
                    }
                    if ($item['cnt'] >= 5) {
                        $other_money += $item['sub_money'];
                        $other_cnt += $item['sub_cnt'];
                    }
                    if ($item['cnt'] > 1) {
                        $cus_money += $item['sub_money'];
                        $cus_cnt += $item['sub_cnt'];
                    }
                }

                $arrOrderResults[1]['sub_money'] = $cus_money;
                $arrOrderResults[1]['sub_cnt'] = $cus_cnt;

                $arrOrderResults[5]['sub_money'] = $other_money;
                $arrOrderResults[5]['sub_cnt'] = $other_cnt;

                $arrResults['total_money'] = $total_money;
                $arrResults['total_cnt'] = $total_cnt;
            }

            // 集計項目 = 「年代」
            $age_sql = "Select sum(payment_total) as sub_money, count(*) as sub_cnt ,
									trunc(years)*10 + 5*round(years-trunc(years)) as years_n
								From
								(
									SELECT payment_total,
										   CASE WHEN order_birth is null THEN '0'
											ELSE extract(year from age(create_date, order_birth))/10.0
										   END As years
									FROM dtb_order
									WHERE " . $where . "
								) As A
								GROUP BY (trunc(years)*10 + 5*round(years-trunc(years)))
								Order By years_n";

            $res = $objQuery->getAll($age_sql, $arrval); // Change KH 2014/3/12
		
            if (!empty($res)) {
                $other_money = 0;
                $other_cnt = 0;
                foreach ($res as $item) {
					if ($item['years_n']==0) {
						$arrAgeResults[0] = $item;
					}
					if ($item['years_n'] ==10 || $item['years_n'] ==15) {
						$arrAgeResults[1] = $item;
					}
                    if ($item['years_n'] == 20) {
                        $arrAgeResults[2] = $item;
                    }
                    if ($item['years_n'] == 25) {
                        $arrAgeResults[3] = $item;
                    }
                    if ($item['years_n'] == 30) {
                        $arrAgeResults[4] = $item;
                    }
                    if ($item['years_n'] == 35) {
                        $arrAgeResults[5] = $item;
                    }
                    if ($item['years_n'] == 40) {
                        $arrAgeResults[6] = $item;
                    }
                    if ($item['years_n'] == 45) {
                        $arrAgeResults[7] = $item;
                    }
                    if ($item['years_n'] >= 50) {
                        $other_money += $item['sub_money'];
                        $other_cnt += $item['sub_cnt'];
                    }
                }
                $arrAgeResults[8] = array('sub_money' => $other_money, 'sub_cnt' => $other_cnt);

            }
            // 集計項目 = 「商品カテゴリ」
            $category_sql = "SELECT A.product_type, A.p_cnt, C.o_cnt, C.money
									FROM
									(
										Select dtb_products.product_type, count(*) as  p_cnt
										FROM dtb_order
											inner join dtb_order_detail on  dtb_order_detail.order_id = dtb_order.order_id
											inner join dtb_products on dtb_order_detail.product_id = dtb_products.product_id
										WHERE " . $where . " GROUP BY dtb_products.product_type
									) AS A
									INNER JOIN
									(
										Select  product_type, sum(payment_total) As money, count(*) as o_cnt
										From
										(
											Select distinct(dtb_order.order_id ), dtb_products.product_type, dtb_order.payment_total
											FROM dtb_order
												inner join dtb_order_detail on  dtb_order_detail.order_id = dtb_order.order_id
												inner join dtb_products on dtb_order_detail.product_id = dtb_products.product_id
											WHERE " . $where . " ) AS B
										Group By product_type
									) AS C
									ON A.product_type = C.product_type

									UNION
									Select '0',count(*) as p_cnt, count(*) as o_cnt, sum(payment_total) as money
									From dtb_order
									WHERE  " . $where . " Order By product_type";

            $arrval1 = array_merge($arrval, array_merge($arrval, $arrval));

            $res = $objQuery->getAll($category_sql, $arrval1);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $arrCatResults[$item['product_type']] = $item;
                }
            }
			
			$all_query = 'SELECT dtb_products.product_type as type,count(*) AS cnt
				FROM dtb_products 
				WHERE dtb_products.del_flg <>1 and dtb_products.status = 1
				GROUP BY type';
			$arrRes = $objQuery->getAll($all_query); // Change KH 2014/3/12
			$allProductCnt = array();
			foreach($arrRes as $item){
				$allProductCnt[$item['type']]  += $item['cnt'];
			}
			$arrCatResults['allProductCnt'] = $allProductCnt;
			// 集計項目 = 「都道府県」
			$pref_sql = "Select id, name, sub_money, sub_cnt
							From mtb_pref
							Left join
							(
								Select CAST(order_pref as int4) as order_pref, sum(payment_total) as sub_money, count(*) as sub_cnt
								from dtb_order
								where " . $where . " group by order_pref
							) As A
							On mtb_pref.id = A.order_pref
							Order By mtb_pref.rank";
			
			$res = $objQuery->getAll($pref_sql, $arrval);
							
			if (!empty($res)) {
				$money = array_fill(0, 7, 0);
				$cnt = array_fill(0, 7, 0);

				foreach ($res as $item) {
					$arrPrefResult[$item['id']] = $item;
					if ($item['id'] >= 2 && $item['id'] < 8) {
						$money[0] += $item['sub_money'];
						$cnt[0] += $item['sub_cnt'];
					} else if ($item['id'] >= 8 && $item['id'] < 15) {
						$money[1] += $item['sub_money'];
						$cnt[1] += $item['sub_cnt'];
					} else if ($item['id'] >= 15 && $item['id'] < 24) {
						$money[2] += $item['sub_money'];
						$cnt[2] += $item['sub_cnt'];
					} else if ($item['id'] >= 24 && $item['id'] < 31) {
						$money[3] += $item['sub_money'];
						$cnt[3] += $item['sub_cnt'];
					} else if ($item['id'] >= 31 && $item['id'] < 36) {
						$money[4] += $item['sub_money'];
						$cnt[4] += $item['sub_cnt'];
					} else if ($item['id'] >= 36 && $item['id'] < 40) {
						$money[5] += $item['sub_money'];
						$cnt[5] += $item['sub_cnt'];
					} else if ($item['id'] >= 40) {
						$money[6] += $item['sub_money'];
						$cnt[6] += $item['sub_cnt'];
					}
				}
				$arrPrefResult['arrPrefSumResults'] = array($money, $cnt);
			}

            $objFpdf = new SC_Order_Analysis_Pdf(1);
            $objFpdf->setData($arrResults, $arrOrderResults, $arrAgeResults, $arrCatResults, $arrPrefResult);
            $objFpdf->createPdf();
        }
    }

?>
