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

    /*----------------------------------------------------------------------
    * [名称] SC_Order_Analysis_Pdf
    * [概要] 受注分析 pdfファイルを表示する。
    *----------------------------------------------------------------------
    */

    require(DATA_REALDIR . 'pdf/japanese.php');
    define('PDF_TEMPLATE_DIR', DATA_REALDIR . 'pdf/');
    define('PDF_IMG_DIR', HTML_REALDIR . USER_DIR . USER_PACKAGE_DIR . TEMPLATE_NAME . '/img/pdf/');

    class SC_Order_Analysis_Pdf
    {

        var $tpl_title = '受注分析';

        //Constants  unit (mm)
        var $top = 11;
        var $left = 4.5;

        function SC_Order_Analysis_Pdf($download, $tpl_pdf = "empty.pdf")
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

        function setData($arrResults, $arrOrderResults, $arrAgeResults, $arrCatResults, $arrPrefResult)
        {

            $this->pdf->SetFont('SJIS', 'B', 8);

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
            $this->pdf->MultiCell(56, 7, $this->sjis_conv("受　　注　　分　　析"), 'B', 'C', 0, 0, 0);
            $this->pdf->MultiCell(116, 10, '', 0, 'C', 0, 1, 0);

            $w = array(48, 48, 48, 48, 48, 48); //288  288/6  = 48

            $this->pdf->SetFont('SJIS', '', 8);
            $this->pdf->SetFillColor(222, 231, 247);
            $this->pdf->SetTextColor(0);

            $this->pdf->MultiCell($w[0], 5, '', 1, 'C', 1, 0, 0);
            $this->pdf->MultiCell($w[1], 5, $this->sjis_conv("総受注件数"), 1, 'C', 1, 0, 0);
            $this->pdf->MultiCell($w[2], 5, '', 1, 'C', 1, 0, 0);
            $this->pdf->MultiCell($w[3], 5, $this->sjis_conv("総受注合計金額"), 1, 'C', 1, 0, 0);
            $this->pdf->MultiCell($w[4], 5, '', 1, 'C', 1, 0, 0);
            $this->pdf->MultiCell($w[5], 5, $this->sjis_conv("平均単価"), 1, 'C', 1, 1, 0);

            $this->pdf->MultiCell($w[0], 5, '', 1, 'C', 0, 0, 0);
            $this->pdf->MultiCell($w[1], 5, number_format($arrResults['total_cnt']) . $this->sjis_conv(' 件'), 1, 'R', 0, 0, 0);
            $this->pdf->MultiCell($w[2], 5, '', 1, 'C', 0, 0, 0);
            $this->pdf->MultiCell($w[3], 5, number_format($arrResults['total_money']) . $this->sjis_conv(' 円'), 1, 'R', 0, 0, 0);
            $this->pdf->MultiCell($w[4], 5, '', 1, 'C', 0, 0, 0);
            $this->pdf->MultiCell($w[5], 5, number_format($arrResults['total_money'] / $arrResults['total_cnt']) . $this->sjis_conv(' 円'), 1, 'R', 0, 1, 0);
            //注文回数
            $label = array('注文回数', '受注件数', '受注全体に占める割合', '受注合計金額', '受注全体に占める割合', '平均単価');
            $this->outHeader($w, $label, 255, 235, 222);

            $label = array('新規', 'リピート', '　▼2回目', '　▼3回目', '　▼4回目', '　▼5回目以降');
            for ($i = 0; $i < 6; $i++) {
                $this->pdf->MultiCell($w[0], 5, $this->sjis_conv($label[$i]), 1, 'L', 1, 0, 0);
                $this->pdf->MultiCell($w[1], 5, number_format($arrOrderResults[$i]['sub_cnt']) . $this->sjis_conv(' 件'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[2], 5, number_format($arrOrderResults[$i]['sub_cnt'] * 100 / $arrResults['total_cnt'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[3], 5, number_format($arrOrderResults[$i]['sub_money']) . $this->sjis_conv(' 円'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[4], 5, number_format($arrOrderResults[$i]['sub_money'] * 100 / $arrResults['total_money'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[5], 5, number_format($arrOrderResults[$i]['sub_money'] / $arrOrderResults[$i]['sub_cnt']) . $this->sjis_conv(' 円'), 1, 'R', 0, 1, 0);
            }
            //年代
            $label = array('年代', '受注件数', '受注全体に占める割合', '受注合計金額', '受注全体に占める割合', '平均単価');
            $this->outHeader($w, $label, 181, 223, 239);
            $label = array('不明','10代', '20代前半', '20代後半', '30代前半', '30代後半', '40代前半', '40代後半', '50代～');

            for ($i = 0; $i < 9; $i++) {
                $this->pdf->MultiCell($w[0], 5, $this->sjis_conv($label[$i]), 1, 'L', 1, 0, 0);
                $this->pdf->MultiCell($w[1], 5, number_format($arrAgeResults[$i]['sub_cnt']) . $this->sjis_conv(' 件'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[2], 5, number_format($arrAgeResults[$i]['sub_cnt'] * 100 / $arrResults['total_cnt'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[3], 5, number_format($arrAgeResults[$i]['sub_money']) . $this->sjis_conv(' 円'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[4], 5, number_format($arrAgeResults[$i]['sub_money'] * 100 / $arrResults['total_money'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[5], 5, number_format($arrAgeResults[$i]['sub_money'] / $arrAgeResults[$i]['sub_cnt']) . $this->sjis_conv(' 円'), 1, 'R', 0, 1, 0);
            }
            //商品カテゴリ   	 	 	 	 	 	平均単価
            $label = array('商品カテゴリ', '受注件数', '受注全体に占める割合', '各商品数に占める割合', '受注合計金額', '受注全体に占める割合', '平均単価');
            $this->outHeader($w, $label, 214, 227, 189, true);
            $label = array('','ワンピース', 'ドレス', 'ドレス3点セット', 'ドレス4点セット', '羽織物', 'ネックレス', 'その他小物');                    
            for ($i = 1; $i < 8; $i++) {
                $this->pdf->MultiCell($w[0], 5, $this->sjis_conv($label[$i]), 1, 'L', 1, 0, 0);
                $this->pdf->MultiCell($w[1], 5, number_format($arrCatResults[$i]['p_cnt']) . $this->sjis_conv(' 件'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[2] / 2, 5, number_format($arrCatResults[$i]['p_cnt'] * 100 / $arrResults['total_cnt'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[2] / 2, 5, number_format($arrCatResults[$i]['p_cnt'] * 100 / $arrCatResults['allProductCnt'][$i], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[3], 5, number_format($arrCatResults[$i]['money']) . $this->sjis_conv(' 円'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[4], 5, number_format($arrCatResults[$i]['money'] * 100 / $arrResults['total_money'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[5], 5, number_format($arrCatResults[$i]['money'] / ( $arrCatResults[$i]['o_cnt'])) . $this->sjis_conv(' 円'), 1, 'R', 0, 1, 0);
            }
			//ADD CHS 20140421
			//都道府県
			$label = array('都道府県', '受注件数', '受注全体に占める割合', '受注合計金額', '受注全体に占める割合', '平均単価');
            $this->outHeader($w, $label, 231, 186, 181);

			$this->pdf->MultiCell($w[0], 5, $this->sjis_conv($arrPrefResult[1]['name']), 1, 'L', 1, 0, 0);
			$this->pdf->MultiCell($w[1], 5, number_format($arrPrefResult[1]['sub_cnt']) . $this->sjis_conv(' 件'), 1, 'R', 0, 0, 0);
			$this->pdf->MultiCell($w[2], 5, number_format($arrPrefResult[1]['sub_cnt'] * 100 / $arrResults['total_cnt'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
			$this->pdf->MultiCell($w[3], 5, number_format($arrPrefResult[1]['sub_money']) . $this->sjis_conv(' 円'), 1, 'R', 0, 0, 0);
			$this->pdf->MultiCell($w[4], 5, number_format($arrPrefResult[1]['sub_money'] * 100 / $arrResults['total_money'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
			$this->pdf->MultiCell($w[5], 5, number_format($arrPrefResult[1]['sub_money'] / ( $arrPrefResult[1]['sub_cnt'])) . $this->sjis_conv(' 円'), 1, 'R', 0, 1, 0);

            $label = array('', '東北地方', '関東地方', '中部地方', '近畿地方', '中国地方', '四国地方', '九州地方');
			$points = array(2, 8, 15, 24, 31, 36, 40, 48); $j = 0;

			for($i = 2; $i < count($arrPrefResult); $i++) {
				if($i >= $points[$j]) {
					$this->pdf->MultiCell($w[0], 5, $this->sjis_conv($label[$j+1]), 1, 'L', 1, 0, 0);
					$this->pdf->MultiCell($w[1], 5, number_format($arrPrefResult['arrPrefSumResults'][1][$j]) . $this->sjis_conv(' 件'), 1, 'R', 0, 0, 0);
					$this->pdf->MultiCell($w[2], 5, number_format($arrPrefResult['arrPrefSumResults'][1][$j] * 100 / $arrResults['total_cnt'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
					$this->pdf->MultiCell($w[3], 5, number_format($arrPrefResult['arrPrefSumResults'][0][$j]) . $this->sjis_conv(' 円'), 1, 'R', 0, 0, 0);
					$this->pdf->MultiCell($w[4], 5, number_format($arrPrefResult['arrPrefSumResults'][0][$j] * 100 / $arrResults['total_money'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
					$this->pdf->MultiCell($w[5], 5, number_format($arrPrefResult['arrPrefSumResults'][0][$j] / ( $arrPrefResult['arrPrefSumResults'][1][$j])) . $this->sjis_conv(' 円'), 1, 'R', 0, 1, 0);
					$j++;
				}
				$this->pdf->MultiCell($w[0], 5, $this->sjis_conv('　▼') . $this->sjis_conv($arrPrefResult[$i]['name']), 1, 'L', 1, 0, 0);
                $this->pdf->MultiCell($w[1], 5, number_format($arrPrefResult[$i]['sub_cnt']) . $this->sjis_conv(' 件'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[2], 5, number_format($arrPrefResult[$i]['sub_cnt'] * 100 / $arrResults['total_cnt'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[3], 5, number_format($arrPrefResult[$i]['sub_money']) . $this->sjis_conv(' 円'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[4], 5, number_format($arrPrefResult[$i]['sub_money'] * 100 / $arrResults['total_money'], 2) . $this->sjis_conv(' ％'), 1, 'R', 0, 0, 0);
                $this->pdf->MultiCell($w[5], 5, number_format($arrPrefResult[$i]['sub_money'] / ( $arrPrefResult[$i]['sub_cnt'])) . $this->sjis_conv(' 円'), 1, 'R', 0, 1, 0);
			}
        }

        function outHeader($w, $arr, $r, $g, $b, $isCat = false)
        {
            $this->pdf->SetFillColor($r, $g, $b);
            $i = 0;
            if ($isCat) {
                $h = 5;
                $line = 2;
                $curX = $this->pdf->x;
                $curY = $this->pdf->y;
                $this->pdf->SJISMultiLineCell($w[0], 2*$h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->x += $w[0];
                $this->pdf->SJISMultiLineCell($w[1], 2*$h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->x += $w[1];
                $this->pdf->SJISMultiLineCell($w[2] / 2, 5, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->x += $w[2]/2;
                $this->pdf->SJISMultiLineCell($w[2] / 2, 5, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->x += $w[2]/2;
                $this->pdf->SJISMultiLineCell($w[3], 2*$h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->x += $w[3];
                $this->pdf->SJISMultiLineCell($w[4], 2*$h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->x += $w[4];
                $this->pdf->SJISMultiLineCell($w[5], 2*$h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 1, $line);
                $this->pdf->x = $curX;
                $this->pdf->y = $curY;
                $this->pdf->Cell( 288 , 10,"",0,0,'C',0 );
                $this->pdf->Ln();
            } else {
                $h = 5;
                $line = 0;
                $this->pdf->MultiCell($w[0], $h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->MultiCell($w[1], $h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->MultiCell($w[2], $h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->MultiCell($w[3], $h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->MultiCell($w[4], $h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 0, $line);
                $this->pdf->MultiCell($w[5], $h, $this->sjis_conv($arr[$i++]), 1, 'C', 1, 1, $line);
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
