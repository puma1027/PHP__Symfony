<?php
function smarty_insert_pan($param, &$smarty) {
		require_once("../require.php");
		$pan = array();
		$count = 0;
		// パンくず情報となるデータを取得
		$objQuery = new SC_Query();
		switch($param) {
				case (SC_Utils::sfIsInt($param['product_id'])):		// product_idが入ってきた場合
						$sql = "
							SELECT
						dtb_category.category_id,
						category_name,
						dtb_category.parent_category_id
				FROM
						dtb_category
				WHERE
						category_id = (SELECT MIN(category_id)
														FROM
																dtb_product_categories
														WHERE
																product_id = ?
											)
						";
						$res = $objQuery->getAll($sql, array($param['product_id']), MDB2_FETCHMODE_ASSOC);
						
						if (is_array($res)) {
								$pan[$count]['category_id'] = $res[0]['category_id'];
								$pan[$count]['category_name'] = $res[0]['category_name'];
								if (ereg("[0-9]+$", $res[0]['parent_category_id']) and ($res[0]['parent_category_id'] > 0)) {		// 上位にカテゴリーがある場合
										do {
												$sql = "SELECT category_id, category_name, parent_category_id FROM dtb_category WHERE category_id =?";
												$res = $objQuery->conn->getAll($sql, array($res[0]['parent_category_id']), MDB2_FETCHMODE_ASSOC);
												if (is_array($res)) {
														$count++;
														$pan[$count]['category_id'] = $res[0]['category_id'];
														$pan[$count]['category_name'] = $res[0]['category_name'];
												}
										} while ($res[0]['parent_category_id'] != "0");
								}
						}
						break;

				case (SC_Utils::sfIsInt($param['category_id'])):  // カテゴリーIDが入ってきた場合
						$res[0]['parent_category_id'] = $param['category_id'];
						do {
								$sql = "
					SELECT
							category_id,
							category_name,
							parent_category_id
					FROM
							dtb_category
					WHERE
							category_id =?
								";
								$res = $objQuery->getAll($sql, array($res[0]['parent_category_id']), DB_FETCHMODE_ASSOC);
								if (is_array($res)) {
										$count++;
										$pan[$count]['category_id'] = $res[0]['category_id'];
										$pan[$count]['category_name'] = $res[0]['category_name'];
								}
						} while ($res[0]['parent_category_id'] != "0");
						break;
		}

		// 取得した配列を逆ソート（上位カテゴリー順）に並び替える
		if (count($pan) > 0) {
				krsort($pan);
		}

		
		// 取得したデータをSmartyに渡す
		$smarty->caching=0;

		$smarty->assign("pan",$pan);
		$pan = $smarty->fetch("pan.tpl");  // Smartyテンプレートで生成されたデータを一旦配列に入れる
		print($pan);
}
?>