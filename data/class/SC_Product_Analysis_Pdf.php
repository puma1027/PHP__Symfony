<?php
/*----------------------------------------------------------------------
* [名称] SC_Product_Analysis_Pdf
* [概要] 商品分析 pdfファイルを表示する。
*----------------------------------------------------------------------
*/

require(DATA_REALDIR . 'pdf/japanese.php');
define('PDF_TEMPLATE_DIR', DATA_REALDIR . 'pdf/');
define('PDF_IMG_DIR', HTML_REALDIR . USER_DIR . USER_PACKAGE_DIR . TEMPLATE_NAME . '/img/pdf/');

class SC_Product_Analysis_Pdf
{

	var $tpl_title = '商品分析';

	//Constants  unit (mm)
	var $top = 11;
	var $left = 4.5;

	function SC_Product_Analysis_Pdf($download, $tpl_pdf = "empty.pdf")
	{

		// デフォルトの設定
		$this->tpl_pdf = PDF_TEMPLATE_DIR . $tpl_pdf; // テンプレートファイル
		$this->pdf_download = $download; // PDFのダウンロード形式（0:表示、1:ダウンロード）

		$this->tpl_dispmode = "real"; // 表示モード

		$this->pdf = new PDF_Japanese('l', 'mm', 'A4');

		// SJISフォント
		$this->pdf->AddSJISFont();

		//ページ総数取得
		$this->pdf->AliasNbPages();

		// マージン設定
		$this->pdf->SetMargins($this->left, $this->top, $this->left);

		// PDFを読み込んでページ数を取得
		$pageno = $this->pdf->setSourceFile($this->tpl_pdf);

	}

	function setData($arrCatSum, $arrSubCatSum, $arrSubCatProduct,$arrProduct, $catAge, $subCatAge, $productAge, $ageAll, $catAgeAll, $subCatAgeAll, $productAgeAll, $stat)
	{
		if (empty($stat)) {
			$stat = 0;
		}
		// ページ番号よりIDを取得
		$tplidx = $this->pdf->ImportPage(1);

		// ページを追加（新規）
		$this->pdf->AddPage();

		//表示倍率(100%)
		$this->pdf->SetDisplayMode($this->tpl_dispmode);

		// テンプレート内容の位置、幅を調整 ※useTemplateに引数を与えなければ100%表示がデフォルト
		$this->pdf->useTemplate($tplidx);

		$this->pdf->SetMargins($this->left, $this->top, $this->left);


		//OUTPUT
		$this->pdf->SetFont('SJIS', '', 14);
		$this->pdf->MultiCell(116, 10, '', 0, 'C', 0, 0, 0);
		$this->pdf->MultiCell(56, 7, $this->sjis_conv("商　　品　　分　　析"), 'B', 'C', 0, 0, 0);
		$this->pdf->MultiCell(116, 10, '', 0, 'C', 0, 1, 0);

		//$w = array(48, 48, 48, 48, 48, 48); //288  288/6  = 48
		$w = array(37, 20, 29, 29, 29, 16, 16, 16, 16, 16, 16, 16, 16, 16); //289

		$this->pdf->SetFont('SJIS', '', 8);
		$this->pdf->SetTextColor(0);

		foreach ($arrCatSum as $catSumKey => $catSumVal) {
			if ($stat == 0 || $stat == 1) {
				$this->pdf->SetFillColor(222, 231, 247);
				$this->pdf->MultiCell($w[0] + $w[1], 5, $this->sjis_conv($catSumVal['category_name']), 'LRT', 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[2], 5, $this->sjis_conv("受注件数"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[3], 5, $this->sjis_conv("回転率"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[4], 5, $this->sjis_conv("売上"), 1, 'L', 1, 0, 0);

				$this->pdf->MultiCell($w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10] + $w[11] + $w[12] + $w[13], 5, $this->sjis_conv('レビュー平均／年代比率'), 1, 'L', 1, 1, 0);

				//2 line
				$this->pdf->MultiCell($w[0] + $w[1], 5, '', 'LR', 'C', 1, 0, 0);
				$this->pdf->MultiCell($w[2], 5, $this->sjis_conv(number_format($catSumVal['order_cnt']) . '件'), 1, 'R', 0, 0, 0);
				$this->pdf->MultiCell($w[3], 5, $this->sjis_conv(number_format($catSumVal['order_cnt'] * 100 / $catSumVal['all_product_cnt'] / $catSumVal['week'], 2) . '％'), 1, 'R', 0, 0, 0);
				$this->pdf->MultiCell($w[4], 5, $this->sjis_conv(number_format($catSumVal['total']) . '円'), 1, 'R', 0, 0, 0);

				$this->pdf->MultiCell($w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10] + $w[11] + $w[12] + $w[13], 5, $this->sjis_conv(number_format($catSumVal['review_sum'] / $catSumVal['review_cnt'], 1) . '　／　' . $catSumVal['review_cnt'] . '件'), 1, 'C', 0, 1, 0);

				//3 line
				$this->pdf->MultiCell($w[0] + $w[1], 5, '', 'LR', 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[2], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[3], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[4], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);

				$this->pdf->SetFillColor(222, 150, 148);
				$this->pdf->MultiCell($w[13], 5, $this->sjis_conv('不明'), 1, 'L', 1, 0, 0);

				$this->pdf->SetFillColor(255, 239, 222);
				$this->pdf->MultiCell($w[5], 5, $this->sjis_conv('～10代'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(222, 239, 247);
				$this->pdf->MultiCell($w[6], 5, $this->sjis_conv('20代前半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(148, 207, 222);
				$this->pdf->MultiCell($w[7], 5, $this->sjis_conv('20代後半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(247, 223, 222);
				$this->pdf->MultiCell($w[8], 5, $this->sjis_conv('30代前半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(222, 150, 148);
				$this->pdf->MultiCell($w[9], 5, $this->sjis_conv('30代後半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(239, 247, 222);
				$this->pdf->MultiCell($w[10], 5, $this->sjis_conv('40代前半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(198, 215, 156);
				$this->pdf->MultiCell($w[11], 5, $this->sjis_conv('40代後半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(206, 199, 222);
				$this->pdf->MultiCell($w[12], 5, $this->sjis_conv('50代～'), 1, 'L', 1, 1, 0);

				//4 line
				$this->pdf->SetFillColor(222, 231, 247);
				$this->pdf->MultiCell($w[0] + $w[1], 5, '', 'LRB', 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[2], 5, $this->sjis_conv(number_format($catSumVal['order_cnt']) . "件"), 1, 'R', 0, 0, 0);
				$this->pdf->MultiCell($w[3], 5, $this->sjis_conv(number_format($catSumVal['order_cnt'] * 100 / $catSumVal['all_product_cnt'] / $catSumVal['week'], 2) . "％"), 1, 'R', 0, 0, 0);
				$this->pdf->MultiCell($w[4], 5, $this->sjis_conv(number_format($catSumVal['total']) . "円"), 1, 'R', 0, 0, 0);

				$this->pdf->SetFillColor(222, 150, 148);
				$this->pdf->MultiCell($w[13], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age0'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 2, 'R', 1, 0, 0);

				$this->pdf->SetFillColor(255, 239, 222);
				$this->pdf->MultiCell($w[5], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age1'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(222, 239, 247);
				$this->pdf->MultiCell($w[6], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age2'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(148, 207, 222);
				$this->pdf->MultiCell($w[7], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age3'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(247, 223, 222);
				$this->pdf->MultiCell($w[8], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age4'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(222, 150, 148);
				$this->pdf->MultiCell($w[9], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age5'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(239, 247, 222);
				$this->pdf->MultiCell($w[10], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age6'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(198, 215, 156);
				$this->pdf->MultiCell($w[11], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age7'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(206, 199, 222);
				$this->pdf->MultiCell($w[12], 5, $this->sjis_conv(number_format($catAge[$catSumVal['product_type']]['age8'] * 100 / $catAgeAll[$catSumVal['product_type']], 1) . '％'), 1, 'R', 1, 1, 0);

			}

			foreach ($arrSubCatSum[$catSumVal['product_type']] as $subCatSumKeys => $subCatSumVal) {
				$subCatSumKey = $subCatSumVal['category_id'];
				if ($stat == 0 || $stat == 1) {
					$this->pdf->SetFillColor(222, 231, 247);
					$this->pdf->MultiCell($w[0] + $w[1], 5, $this->sjis_conv($subCatSumVal['category_name']), 'LRT', 'L', 1, 0, 0);
					$this->pdf->MultiCell($w[2], 5, $this->sjis_conv("受注件数"), 1, 'L', 1, 0, 0);
					$this->pdf->MultiCell($w[3], 5, $this->sjis_conv("カテゴリ回転率"), 1, 'L', 1, 0, 0);
					$this->pdf->MultiCell($w[4], 5, $this->sjis_conv("売上"), 1, 'L', 1, 0, 0);

					$this->pdf->MultiCell($w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10] + $w[11] + $w[12] + $w[13], 5, $this->sjis_conv('レビュー平均／年代比率'), 1, 'L', 1, 1, 0);

					//2 line
					$this->pdf->MultiCell($w[0] + $w[1], 5, '', 'LR', 'C', 1, 0, 0);
					$this->pdf->MultiCell($w[2], 5, $this->sjis_conv(number_format($subCatSumVal['order_cnt']) . '件'), 1, 'R', 0, 0, 0);
					$this->pdf->MultiCell($w[3], 5, $this->sjis_conv(number_format($subCatSumVal['order_cnt'] * 100 / $subCatSumVal['all_product_cnt'] / $subCatSumVal['week'], 2) . '％'), 1, 'R', 0, 0, 0);
					$this->pdf->MultiCell($w[4], 5, $this->sjis_conv(number_format($subCatSumVal['total']) . '円'), 1, 'R', 0, 0, 0);

					$this->pdf->MultiCell($w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10] + $w[11] + $w[12] + $w[13], 5, $this->sjis_conv(number_format($subCatSumVal['review_sum'] / $subCatSumVal['review_cnt'], 1) . '　／　' . $subCatSumVal['review_cnt'] . '件'), 1, 'C', 0, 1, 0);

					//3 line
					$this->pdf->MultiCell($w[0] + $w[1], 5, '', 'LR', 'L', 1, 0, 0);
					$this->pdf->MultiCell($w[2], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);
					$this->pdf->MultiCell($w[3], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);
					$this->pdf->MultiCell($w[4], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);

					$this->pdf->SetFillColor(222, 150, 148);
					$this->pdf->MultiCell($w[13], 5, $this->sjis_conv('不明'), 1, 'L', 1, 0, 0);

					$this->pdf->SetFillColor(255, 239, 222);
					$this->pdf->MultiCell($w[5], 5, $this->sjis_conv('～10代'), 1, 'L', 1, 0, 0);
					$this->pdf->SetFillColor(222, 239, 247);
					$this->pdf->MultiCell($w[6], 5, $this->sjis_conv('20代前半'), 1, 'L', 1, 0, 0);
					$this->pdf->SetFillColor(148, 207, 222);
					$this->pdf->MultiCell($w[7], 5, $this->sjis_conv('20代後半'), 1, 'L', 1, 0, 0);
					$this->pdf->SetFillColor(247, 223, 222);
					$this->pdf->MultiCell($w[8], 5, $this->sjis_conv('30代前半'), 1, 'L', 1, 0, 0);
					$this->pdf->SetFillColor(222, 150, 148);
					$this->pdf->MultiCell($w[9], 5, $this->sjis_conv('30代後半'), 1, 'L', 1, 0, 0);
					$this->pdf->SetFillColor(239, 247, 222);
					$this->pdf->MultiCell($w[10], 5, $this->sjis_conv('40代前半'), 1, 'L', 1, 0, 0);
					$this->pdf->SetFillColor(198, 215, 156);
					$this->pdf->MultiCell($w[11], 5, $this->sjis_conv('40代後半'), 1, 'L', 1, 0, 0);
					$this->pdf->SetFillColor(206, 199, 222);
					$this->pdf->MultiCell($w[12], 5, $this->sjis_conv('50代～'), 1, 'L', 1, 1, 0);

					//4 line
					$this->pdf->SetFillColor(222, 231, 247);
					$this->pdf->MultiCell($w[0] + $w[1], 5, '', 'LRB', 'L', 1, 0, 0);
					$this->pdf->MultiCell($w[2], 5, $this->sjis_conv(number_format($subCatSumVal['order_cnt']) . "件"), 1, 'R', 0, 0, 0);
					$this->pdf->MultiCell($w[3], 5, $this->sjis_conv(number_format($subCatSumVal['order_cnt'] * 100 / $subCatSumVal['all_product_cnt'] / $subCatSumVal['week'], 2) . "％"), 1, 'R', 0, 0, 0);
					$this->pdf->MultiCell($w[4], 5, $this->sjis_conv(number_format($subCatSumVal['total']) . "円"), 1, 'R', 0, 0, 0);

					$this->pdf->SetFillColor(222, 150, 148);
					$this->pdf->MultiCell($w[5], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age0'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 0, 0);

					$this->pdf->SetFillColor(255, 239, 222);
					$this->pdf->MultiCell($w[5], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age1'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 0, 0);
					$this->pdf->SetFillColor(222, 239, 247);
					$this->pdf->MultiCell($w[6], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age2'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 0, 0);
					$this->pdf->SetFillColor(148, 207, 222);
					$this->pdf->MultiCell($w[7], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age3'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 0, 0);
					$this->pdf->SetFillColor(247, 223, 222);
					$this->pdf->MultiCell($w[8], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age4'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 0, 0);
					$this->pdf->SetFillColor(222, 150, 148);
					$this->pdf->MultiCell($w[9], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age5'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 0, 0);
					$this->pdf->SetFillColor(239, 247, 222);
					$this->pdf->MultiCell($w[10], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age6'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 0, 0);
					$this->pdf->SetFillColor(198, 215, 156);
					$this->pdf->MultiCell($w[11], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age7'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 0, 0);
					$this->pdf->SetFillColor(206, 199, 222);
					$this->pdf->MultiCell($w[12], 5, $this->sjis_conv(number_format($subCatAge[$catSumVal['product_type']][$subCatSumKey]['age8'] * 100 / $subCatAgeAll[$catSumVal['product_type']][$subCatSumKey], 1) . '％'), 1, 'R', 1, 1, 0);
				}

				foreach ($arrSubCatProduct[$catSumVal['product_type']][$subCatSumVal['category_id']] as $productSumKey => $productSumVal) {
					if ($stat == 0) {
						$this->pdf->SetFillColor(132, 134, 132);
						if ($this->pdf->y > 180) {
							$this->pdf->AddPage();
						}

						$this->pdf->MultiCell($w[0], 5, $this->sjis_conv('商品コード'), 1, 'L', 1, 0, 0);
						$this->pdf->MultiCell($w[1], 5, $this->sjis_conv('商品写真'), 'LRT', 'L', 1, 0, 0);
						$this->pdf->MultiCell($w[2], 5, $this->sjis_conv("受注件数"), 1, 'L', 1, 0, 0);
						$this->pdf->MultiCell($w[3], 5, $this->sjis_conv("1カ月の回転率"), 1, 'L', 1, 0, 0);
						$this->pdf->MultiCell($w[4], 5, $this->sjis_conv("売上"), 1, 'L', 1, 0, 0);

						$this->pdf->MultiCell($w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10] + $w[11] + $w[12] + $w[13], 5, $this->sjis_conv('レビュー平均／年代比率'), 1, 'L', 1, 1, 0);

						//2 line
						$this->pdf->MultiCell($w[0], 5, $this->sjis_conv($productSumVal['product_code']), 1, 'C', 0, 0, 0);

						$product_image_file = IMAGE_SAVE_REALDIR . $productSumVal['product_image'];
						if (getimagesize($product_image_file)) {
							$this->pdf->PutImage($product_image_file, $this->pdf->x + 0.5, $this->pdf->y + 0.5, 19, 19, 1);
						}

						$this->pdf->MultiCell($w[1], 5, '', 'LRT', 'C', 0, 0, 0);
						$this->pdf->MultiCell($w[2], 5, $this->sjis_conv(number_format($productSumVal['order_cnt']) . '件'), 1, 'R', 0, 0, 0);
						$this->pdf->MultiCell($w[3], 5, $this->sjis_conv(number_format($productSumVal['order_cnt'] * 25 / $productSumVal['period'], 2) . '％'), 1, 'R', 0, 0, 0);
						$this->pdf->MultiCell($w[4], 5, $this->sjis_conv(number_format($productSumVal['total']) . '円'), 1, 'R', 0, 0, 0);

						$this->pdf->MultiCell($w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10] + $w[11] + $w[12] + $w[13], 5, $this->sjis_conv(number_format($productSumVal['review_sum'] / $productSumVal['review_cnt'], 1) . '　／　' . $productSumVal['review_cnt'] . '件'), 1, 'C', 0, 1, 0);

						//3 line
						$this->pdf->MultiCell($w[0], 5, $this->sjis_conv("商品名"), 1, 'L', 1, 0, 0);
						$this->pdf->MultiCell($w[1], 5, '', 'LR', 'L', 0, 0, 0);
						$this->pdf->MultiCell($w[2], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);
						$this->pdf->MultiCell($w[3], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);
						$this->pdf->MultiCell($w[4], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);

						$this->pdf->SetFillColor(222, 150, 148);
						$this->pdf->MultiCell($w[13], 5, $this->sjis_conv('不明'), 1, 'L', 1, 0, 0);

						$this->pdf->SetFillColor(255, 239, 222);
						$this->pdf->MultiCell($w[5], 5, $this->sjis_conv('～10代'), 1, 'L', 1, 0, 0);
						$this->pdf->SetFillColor(222, 239, 247);
						$this->pdf->MultiCell($w[6], 5, $this->sjis_conv('20代前半'), 1, 'L', 1, 0, 0);
						$this->pdf->SetFillColor(148, 207, 222);
						$this->pdf->MultiCell($w[7], 5, $this->sjis_conv('20代後半'), 1, 'L', 1, 0, 0);
						$this->pdf->SetFillColor(247, 223, 222);
						$this->pdf->MultiCell($w[8], 5, $this->sjis_conv('30代前半'), 1, 'L', 1, 0, 0);
						$this->pdf->SetFillColor(222, 150, 148);
						$this->pdf->MultiCell($w[9], 5, $this->sjis_conv('30代後半'), 1, 'L', 1, 0, 0);
						$this->pdf->SetFillColor(239, 247, 222);
						$this->pdf->MultiCell($w[10], 5, $this->sjis_conv('40代前半'), 1, 'L', 1, 0, 0);
						$this->pdf->SetFillColor(198, 215, 156);
						$this->pdf->MultiCell($w[11], 5, $this->sjis_conv('40代後半'), 1, 'L', 1, 0, 0);
						$this->pdf->SetFillColor(206, 199, 222);
						$this->pdf->MultiCell($w[12], 5, $this->sjis_conv('50代～'), 1, 'L', 1, 1, 0);

						//4 line
						$h = 10;
						$this->pdf->SetFillColor(222, 231, 247);
						$this->pdf->SJISMultiLineCell($w[0], $h / 2, $this->sjis_conv($productSumVal['product_name']), 0, 'L', 0, 2, 2);
						$this->pdf->MultiCell($w[0], $h, '', 1, 'L', 0, 0, 0);

						$this->pdf->MultiCell($w[1], $h, '', 'LRB', 'L', 0, 0, 0);
						$this->pdf->MultiCell($w[2], $h, $this->sjis_conv(number_format($productSumVal['order_cnt']) . "件"), 1, 'R', 0, 0, 0);
						$this->pdf->MultiCell($w[3], $h, $this->sjis_conv(number_format($productSumVal['order_cnt'] * 25 / $productSumVal['period'], 2) . "％"), 1, 'R', 0, 0, 0);
						$this->pdf->MultiCell($w[4], $h, $this->sjis_conv(number_format($productSumVal['total']) . "円"), 1, 'R', 0, 0, 0);

						$this->pdf->SetFillColor(222, 150, 148);
						$this->pdf->MultiCell($w[5], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age0'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);


						$this->pdf->SetFillColor(255, 239, 222);
						$this->pdf->MultiCell($w[5], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age1'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
						$this->pdf->SetFillColor(222, 239, 247);
						$this->pdf->MultiCell($w[6], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age2'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
						$this->pdf->SetFillColor(148, 207, 222);
						$this->pdf->MultiCell($w[7], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age3'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
						$this->pdf->SetFillColor(247, 223, 222);
						$this->pdf->MultiCell($w[8], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age4'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
						$this->pdf->SetFillColor(222, 150, 148);
						$this->pdf->MultiCell($w[9], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age5'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
						$this->pdf->SetFillColor(239, 247, 222);
						$this->pdf->MultiCell($w[10], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age6'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
						$this->pdf->SetFillColor(198, 215, 156);
						$this->pdf->MultiCell($w[11], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age7'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
						$this->pdf->SetFillColor(206, 199, 222);
						$this->pdf->MultiCell($w[12], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age8'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 1, 0);

					}

				}
			}
		}
		
		//product only
		if ($stat == 2) {
			foreach ($arrProduct as $productSumKey => $productSumVal) {
				$this->pdf->SetFillColor(132, 134, 132);
				if ($this->pdf->y > 180) {
					$this->pdf->AddPage();
				}

				$this->pdf->MultiCell($w[0], 5, $this->sjis_conv('商品コード'), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[1], 5, $this->sjis_conv('商品写真'), 'LRT', 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[2], 5, $this->sjis_conv("受注件数"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[3], 5, $this->sjis_conv("1カ月の回転率"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[4], 5, $this->sjis_conv("売上"), 1, 'L', 1, 0, 0);

				$this->pdf->MultiCell($w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10] + $w[11] + $w[12] + $w[13], 5, $this->sjis_conv('レビュー平均／年代比率'), 1, 'L', 1, 1, 0);

				//2 line
				$this->pdf->MultiCell($w[0], 5, $this->sjis_conv($productSumVal['product_code']), 1, 'C', 0, 0, 0);

				$product_image_file = IMAGE_SAVE_REALDIR . $productSumVal['product_image'];
				if (getimagesize($product_image_file)) {
					$this->pdf->PutImage($product_image_file, $this->pdf->x + 0.5, $this->pdf->y + 0.5, 19, 19, 1);
				}

				$this->pdf->MultiCell($w[1], 5, '', 'LRT', 'C', 0, 0, 0);
				$this->pdf->MultiCell($w[2], 5, $this->sjis_conv(number_format($productSumVal['order_cnt']) . '件'), 1, 'R', 0, 0, 0);
				$this->pdf->MultiCell($w[3], 5, $this->sjis_conv(number_format($productSumVal['order_cnt'] * 25 / $productSumVal['period'], 2) . '％'), 1, 'R', 0, 0, 0);
				$this->pdf->MultiCell($w[4], 5, $this->sjis_conv(number_format($productSumVal['total']) . '円'), 1, 'R', 0, 0, 0);

				$this->pdf->MultiCell($w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10] + $w[11] + $w[12] + $w[13], 5, $this->sjis_conv(number_format($productSumVal['review_sum'] / $productSumVal['review_cnt'], 2) . '　／　' . $productSumVal['review_cnt'] . '件'), 1, 'C', 0, 1, 0);

				//3 line
				$this->pdf->MultiCell($w[0], 5, $this->sjis_conv("商品名"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[1], 5, '', 'LR', 'L', 0, 0, 0);
				$this->pdf->MultiCell($w[2], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[3], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);
				$this->pdf->MultiCell($w[4], 5, $this->sjis_conv("累計"), 1, 'L', 1, 0, 0);

				$this->pdf->SetFillColor(222, 150, 148);
				$this->pdf->MultiCell($w[13], 5, $this->sjis_conv('不明'), 1, 'L', 1, 0, 0);

				$this->pdf->SetFillColor(255, 239, 222);
				$this->pdf->MultiCell($w[5], 5, $this->sjis_conv('～10代'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(222, 239, 247);
				$this->pdf->MultiCell($w[6], 5, $this->sjis_conv('20代前半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(148, 207, 222);
				$this->pdf->MultiCell($w[7], 5, $this->sjis_conv('20代後半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(247, 223, 222);
				$this->pdf->MultiCell($w[8], 5, $this->sjis_conv('30代前半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(222, 150, 148);
				$this->pdf->MultiCell($w[9], 5, $this->sjis_conv('30代後半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(239, 247, 222);
				$this->pdf->MultiCell($w[10], 5, $this->sjis_conv('40代前半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(198, 215, 156);
				$this->pdf->MultiCell($w[11], 5, $this->sjis_conv('40代後半'), 1, 'L', 1, 0, 0);
				$this->pdf->SetFillColor(206, 199, 222);
				$this->pdf->MultiCell($w[12], 5, $this->sjis_conv('50代～'), 1, 'L', 1, 1, 0);

				//4 line
				$h = 10;
				$this->pdf->SetFillColor(222, 231, 247);
				$this->pdf->SJISMultiLineCell($w[0], $h / 2, $this->sjis_conv($productSumVal['product_name']), 0, 'L', 0, 2, 2);
				$this->pdf->MultiCell($w[0], $h, '', 1, 'L', 0, 0, 0);

				$this->pdf->MultiCell($w[1], $h, '', 'LRB', 'L', 0, 0, 0);
				$this->pdf->MultiCell($w[2], $h, $this->sjis_conv(number_format($productSumVal['order_cnt']) . "件"), 1, 'R', 0, 0, 0);
				$this->pdf->MultiCell($w[3], $h, $this->sjis_conv(number_format($productSumVal['order_cnt'] * 25 / $productSumVal['period'], 1) . "％"), 1, 'R', 0, 0, 0);
				$this->pdf->MultiCell($w[4], $h, $this->sjis_conv(number_format($productSumVal['total']) . "円"), 1, 'R', 0, 0, 0);

				$this->pdf->SetFillColor(222, 150, 148);
				$this->pdf->MultiCell($w[5], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age0'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);


				$this->pdf->SetFillColor(255, 239, 222);
				$this->pdf->MultiCell($w[5], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age1'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(222, 239, 247);
				$this->pdf->MultiCell($w[6], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age2'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(148, 207, 222);
				$this->pdf->MultiCell($w[7], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age3'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(247, 223, 222);
				$this->pdf->MultiCell($w[8], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age4'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(222, 150, 148);
				$this->pdf->MultiCell($w[9], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age5'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(239, 247, 222);
				$this->pdf->MultiCell($w[10], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age6'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(198, 215, 156);
				$this->pdf->MultiCell($w[11], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age7'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 0, 0);
				$this->pdf->SetFillColor(206, 199, 222);
				$this->pdf->MultiCell($w[12], $h, $this->sjis_conv(number_format($productAge[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']]['age8'] * 100 / $productAgeAll[$catSumVal['product_type']][$subCatSumKey][$productSumVal['product_id']], 1) . '％'), 1, 'R', 1, 1, 0);

			}
		}
	}

	function createPdf()
	{
		// PDFをブラウザに送信
		ob_clean();

		$this->pdf->Output($this->sjis_conv($this->tpl_title . '.pdf'), D);

		// 入力してPDFファイルを閉じる
		$this->pdf->Close();
	}

// PDF_Japanese::Text へのパーサー
	function lfText($x, $y, $text, $size, $style = '', $family = 'SJIS')
	{

		$text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

		$this->pdf->SetFont($family, $style, $size);

		$this->pdf->Text($x, $y, $text);
	}

// PDF_Japanese::Colored Text へのパーサー
	function lfRedText($x, $y, $text, $size, $style = '', $family = 'SJIS')
	{
		$text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

		$this->pdf->SetFont($family, $style, $size);
		$this->pdf->RedText($x, $y, $text);
	}

// 文字コードSJIS変換 -> japanese.phpで使用出来る文字コードはSJISのみ
	function sjis_conv($conv_str)
	{
		return (mb_convert_encoding($conv_str, "SJIS", CHAR_CODE));
	}
}

?>
