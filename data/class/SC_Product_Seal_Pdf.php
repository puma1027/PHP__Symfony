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
 * [名称] SC_Order_Pdf
 * [概要] レンタル票 pdfファイルを表示する。
 *----------------------------------------------------------------------
 */

require(DATA_REALDIR . 'pdf/japanese.php');
define('PDF_TEMPLATE_DIR', DATA_REALDIR . 'pdf/');
define('PDF_TEMPLATE_REALDIR', PDF_TEMPLATE_DIR);
define('PDF_IMG_DIR', HTML_REALDIR . USER_DIR . USER_PACKAGE_DIR . TEMPLATE_NAME . '/img/pdf/');

class SC_Product_Seal_Pdf
{

	var $tpl_title = 'タグ・シール';

	var $sheet_count = 1;

	//Constants  unit (mm)
	var $tag_h = 60;
	var $tag_w = 65;
	var $tag_img_h = 44.5;
	var $tag_as_h = 11;
	var $tag_top = 11;
	var $tag_left = 5;
	var $tag_hspace = 2.5;
	var $tag_vspace = 0;

	var $seal_sheet_h = 297;
	var $seal_sheet_w = 210;
//	var $seal_h = 38.1; //21.2
//	var $seal_w = 63.5; //38.1
//	var $seal_hspace = 2.55; //2.5
//	var $seal_vspace = 0;
//	var $seal_top = 15.15; //11
//	var $seal_left = 7.2; //4.5

	var $seal_h = 35.7; //21.2
	var $seal_w = 61.1; //38.1
	var $seal_hspace = 4.95; //2.5
	var $seal_vspace = 2.4;
	var $seal_top = 16.35; //11
	var $seal_left = 8.4; //4.5

	var $arrTag;
	var $arrSeal;

	function SC_Product_Seal_Pdf($download, $tpl_pdf = "empty.pdf")
	{

		// デフォルトの設定
		$this->tpl_pdf = PDF_TEMPLATE_REALDIR . $tpl_pdf; // テンプレートファイル
		$this->pdf_download = $download; // PDFのダウンロード形式（0:表示、1:ダウンロード）

		$this->tpl_dispmode = "real"; // 表示モード

		$this->pdf = new PDF_Japanese('P', 'mm', 'A4');

		// SJISフォント
		$this->pdf->AddSJISFont();

		//ページ総数取得
		$this->pdf->AliasNbPages();

		// マージン設定
		$this->pdf->SetMargins($this->tag_left, $this->tag_top, $this->tag_left);
//		$this->pdf->SetMargins($this->seal_left, $this->seal_top, $this->seal_left);

		// PDFを読み込んでページ数を取得
		$pageno = $this->pdf->setSourceFile($this->tpl_pdf);

	}

	function setSheetCount($sheet = 1)
	{
		$this->sheet_count = $sheet;
	}

	function setTag($p_id)
	{

		$this->arrTag = $this->lfGetProductInfo($p_id);
		$this->pdf->SetFont('SJIS', 'B', 8);

		// ページ番号よりIDを取得
		$tpl_idx = $this->pdf->ImportPage(1);

		// ページを追加（新規）
		$this->pdf->AddPage();

		//表示倍率(100%)
		$this->pdf->SetDisplayMode($this->tpl_dispmode);

		// テンプレート内容の位置、幅を調整 ※useTemplateに引数を与えなければ100%表示がデフォルト
		$this->pdf->useTemplate($tpl_idx);


		$this->pdf->SetMargins($this->tag_left, $this->tag_top, $this->tag_left);
		

		//TAG OUTPUT

		//Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
		$col = 0;
		for ($i = 0; $i < $this->sheet_count; $i++) {
			foreach ($this->arrTag as $tag) {

				$curX = $this->pdf->x;
				$curY = $this->pdf->y;

				if ($curY > 60*4) {
					$this->pdf->AddPage();
					$curX = $this->pdf->x;
					$curY = $this->pdf->y;
				}

				$this->pdf->SJISMultiLineCell($this->tag_w, $this->tag_h, '', 1, 'C', 0, 2, 2, 8);

				//$this->pdf->SJISMultiLineCell( $this->tag_w,15,$this->sjis_conv($tag['product_code']),1,'C',0,0,0,38 );
				$this->pdf->SJISMultiLineCell($this->tag_w, 15, "", 1, 'C', 0, 0, 0, 38);

				$code1 = substr($tag['product_code'], 0, strpos($tag['product_code'], "-") + 1);
				$code2 = substr($tag['product_code'], strpos($tag['product_code'], "-") + 1, 4);
				$code3 = substr($tag['product_code'], strpos($tag['product_code'], "-") + 5);

				$fs = 38;

				if (strlen($code2) < 4 || strlen($code1) < 3 || strlen($tag['product_code']) != 8) {
					if (strlen($tag['product_code']) > 8) {
						$fs = 36 * 8 / strlen($tag['product_code']);
					}
					$this->pdf->SetFont('Arial', '', $fs);
					//$this->pdf->y +=12;
					$this->pdf->SJISMultiLineCell($this->tag_w, 15, $this->sjis_conv($tag['product_code']), 0, 'C', 0, 0, 0, $fs);
				} else {
					$this->lfText($curX + 2, $curY + 12, $this->sjis_conv($code1), $fs, '', 'Arial');
					$this->lfRedText($curX + 7 + 8 * (strlen($code1) - 1), $curY + 12, $this->sjis_conv($code2), $fs, '', 'Arial');
					$this->lfText($curX + 5 + 8 * (strlen($code1 . $code2) - 1), $curY + 12, $this->sjis_conv($code3), $fs, '', 'Arial');
				}

				//::$product_image_file = IMAGE_SAVE_REALDIR . $tag['main_image'];//::B00021 Del 20130514
				$product_image_file = IMAGE_SAVE_REALDIR . $tag['main_list_image'];//::B00021 Add 20130514
				if (getimagesize($product_image_file)) {
					$this->pdf->PutImage($product_image_file, $this->pdf->x + 0.5, $this->pdf->y + 15.5, $this->tag_w / 2 - 1, $this->tag_img_h - 1, 1);
				}

				$name = $tag['name'];
				//$pre_name = $this->getBrand($name);
				//$name = substr($tag['name'] , strpos($tag['name'],$pre_name)+strlen($pre_name)-1);

				if (empty($name)) {
					$name = $tag['name'];
				}

				if ($this->checkJapan($name)) {
					$this->pdf->SetFont('SJIS', 'B', 15);
				} else {
					$this->pdf->SetFont('SJIS', 'B', 15);
				}

				$fs = 10;

				$len = mb_strlen($name);
				if($len>30){
					$fs = 8;
				}else if($len>20){
					$fs = 9.5;
				}
/*
				$n = strlen($name);
				$mn = mb_strlen($name);
				$s = ($mn - $n) * 3 / 5 + $n;
				if ($s > 9) {
					$fs = 17 * 9 / $s;
				}
*/
				$this->pdf->x += $this->tag_w / 2;
				$this->pdf->y += 16;
				$break_cnt = $this->pdf->SJISMultiLineCell($this->tag_w / 2, 5, $this->sjis_conv( $name), '', 'L', 0, 2, 2, $fs);
				$this->pdf->y -=1;

				if($break_cnt<1){
					$this->pdf->SJISMultiLineCell($this->tag_w / 2, 7, '', 1, 'C', 0, 0, 0, $fs);
					$this->pdf->y += 7;
				}else{
					$this->pdf->SJISMultiLineCell($this->tag_w / 2, 12, '', 1, 'C', 0, 0, 0, $fs);
					$this->pdf->y += 12;
				}

				
				//$this->pdf->SJISMultiLineCell($this->tag_w / 2, 5, $this->sjis_conv($name), 'LBR', 'L', 0, 0, 0, $fs-2);
				//$this->pdf->y += 5;
                //::N00062 Change 20130531
                if ((empty($tag['set_content'])  || $tag['set_content']  == 'なし')
                    && (empty($tag['set_content4']) || $tag['set_content4'] == 'なし')) {
                    //付属品とピンク袋両方なし
                    $tag_out = '';
                } elseif(empty($tag['set_content'])  || $tag['set_content']  == 'なし') {
                    //付属品なし、ピンク袋あり
                    $tag_out = $tag['set_content4'];
                } elseif(empty($tag['set_content4']) || $tag['set_content4'] == 'なし') {
                    //付属品あり、ピンク袋なし
                    $tag_out = $tag['set_content'];
                } else {
                    //付属品あり、ピンク袋あり
                    $tag_out = $tag['set_content']."/".$tag['set_content4'];
                }
                //::N00062 end 20130531
				$this->pdf->SetFont('SJIS', 'B');
				//::$this->pdf->SJISMultiLineCell($this->tag_w / 2, 5, $this->sjis_conv($tag['set_content']), 'LTR', 'L', 0, 5, 5, 8);//::N00062 Del 20130531
				$this->pdf->SJISMultiLineCell($this->tag_w / 2, 5, $this->sjis_conv($tag_out), 'LTR', 'L', 0, 5, 5, 8);//::N00062 Add 20130531
//                $this->pdf->y +=9;
//                $this->pdf->SJISMultiLineCell( $this->tag_w/2,9,$this->sjis_conv($tag['set_content1']),'LR','L',0,0,0,8 );
//                $this->pdf->y +=9;
//                $this->pdf->SJISMultiLineCell( $this->tag_w/2,9,$this->sjis_conv($tag['set_content2']),'LR','L',0,0,0,8 );
//                $this->pdf->y +=9;
//                $this->pdf->SJISMultiLineCell( $this->tag_w/2,9,$this->sjis_conv($tag['set_content3']),'LR','L',0,0,0,8 );

				if($break_cnt<1){
					$this->pdf->SJISMultiLineCell($this->tag_w / 2, 38, '', 'LTR', 'L', 0, 0, 0, 8);
				}else{
					$this->pdf->SJISMultiLineCell($this->tag_w / 2, 33, '', 'LTR', 'L', 0, 0, 0, 8);
				}

				$this->pdf->x = $curX;
				$this->pdf->y = $curY;

				//$this->pdf->Cell( $this->tag_w,$this->tag_h,"",0,'C' );
				$this->pdf->Cell($this->tag_w +$this->tag_hspace, $this->tag_h+$this->tag_hspace , "", 0, 0, 'C', 0);

				//208 215 229

				$col++;
				if ($col > 2) {
					$col = 0;
					$this->pdf->Ln();
				}
			}
		}
	}

	function setSeal($p_id, $type1 = 1, $type2 = 1, $type3 = 1, $type4 = 1)
	{
		$border = 1;

		if (empty($type1) && empty($type2) && empty($type3) && empty($type4)) {
			$type1 = 1;
		}
		$arrOut = array();
		if (!empty($type1)) {
			$arrOut[] = 1;
		}
		if (!empty($type2)) {
			$arrOut[] = 2;
		}
		if (!empty($type3)) {
			$arrOut[] = 3;
		}
		if (!empty($type4)) {
			$arrOut[] = 4;
		}

		$this->arrSeal = $this->lfGetProductInfo($p_id);

		//Seal Output
		$this->pdf->SetFont('SJIS', '', 8);


		// ページ番号よりIDを取得
		$tpl_index = $this->pdf->ImportPage(1);

		$this->pdf->SetMargins($this->seal_left, $this->seal_top, $this->seal_left);

		// ページを追加（新規）
		$this->pdf->AddPage();

		//表示倍率(100%)
		$this->pdf->SetDisplayMode($this->tpl_dispmode);

		// テンプレート内容の位置、幅を調整 ※useTemplateに引数を与えなければ100%表示がデフォルト
		$this->pdf->useTemplate($tpl_index);

		$this->pdf->SetMargins($this->seal_left, $this->seal_top, $this->seal_left);
		$this->pdf->SetAutoPageBreak(true);
		
		$this->pdf->SetLineWidth(0.2);
		//$this->pdf->SJISMultiLineCell($this->seal_sheet_w-$this->seal_left*2,$this->seal_sheet_h-$this->seal_top*2,'',1, 'C', 0,2 , 2 ,8);
		$col = 0;
		
		$img_w = ($this->seal_h - 1)*0.75;
		
		for ($i = 0; $i < $this->sheet_count; $i++) {
			foreach ($this->arrSeal as $seal) {
				foreach ($arrOut as $type) {
					$curX = $this->pdf->x;
					$curY = $this->pdf->y;

					if ($curY > $this->seal_sheet_h - $this->seal_h) {
						$this->pdf->AddPage();
						$curX = $this->pdf->x;
						$curY = $this->pdf->y;
					}
					
					if ($border == 1) {
						$this->pdf->SJISMultiLineCell($this->seal_w, $this->seal_h, '', 1, 'C', 0, 2, 2, 8);
					}
					
					//単品
					$product_image_file = IMAGE_SAVE_REALDIR . $seal['main_list_image'];
					if ($type == 2) { //羽織物    バッグ
						$product_image_file = IMAGE_SAVE_REALDIR . $seal['photo_gallery_image5'];
					} elseif ($type == 3) { //ネックレス
						//::$product_image_file = IMAGE_SAVE_REALDIR . $seal['photo_gallery_image9'];//::B00021 Add 20130514
                        //::B00021 Add 20130514
                        if ($seal['display_position_seal_tag_id'] == 1) {
                            $product_image_file = IMAGE_SAVE_REALDIR . $seal['photo_gallery_image9'];
                        } else {
                            $product_image_file = IMAGE_SAVE_REALDIR . $seal['photo_gallery_image10'];
                        }
                        //::B00021 end 20130514
					} elseif ($type == 4) { //バッグ
						//::$product_image_file = IMAGE_SAVE_REALDIR . $seal['sub_image1'];//::B00021 Add 20130514
						$product_image_file = IMAGE_SAVE_REALDIR . $seal['photo_gallery_image12'];//::B00021 Add 20130514
					}

					if (getimagesize($product_image_file)) {
						$this->pdf->PutImage($product_image_file, $this->pdf->x + 0.5, $this->pdf->y + 0.5, $img_w, $this->seal_h - 1, 1);
					}
					if ($border == 1) {
						$this->pdf->SJISMultiLineCell($img_w+1, $this->seal_h, '', 1, 'C', 0, 2, 2, 8);
					}

					$this->pdf->y += 4;
					$this->pdf->SetFont('SJIS', 'B', 20);

					$up_code = substr($seal['product_code'], 0, strpos($seal['product_code'], "-"));
					$down_code = substr($seal['product_code'], strpos($seal['product_code'], "-"));

					$dx = $img_w+1.5;//33-31.5
					$dy = 8;
					$fs = 19;
					$n = strlen($seal['product_code']);
					if ($n < 8) {
						$dx = $dx + 2.5 * (8 - $n);
						$n = 8;
					} else if ($n > 8) {
						$fs = $fs * 8 / $n;
					}

					$pos = 9;
					//::if ($up_code == '01' || $up_code == '02') {
					if ($up_code == PCODE_SET_DRESS) {//::N00083 Change 20131201
						//$this->pdf->SetTextColor(154, 205, 50);//黄緑色
						//$this->pdf->SetTextColor(185, 196, 47);//黄緑色
						$this->pdf->SetTextColor(100, 150, 8); //黄緑色
						$this->lfText($curX + $dx, $curY + $dy, $this->sjis_conv($up_code), $fs, 'B');
						$this->pdf->SetTextColor(0, 0, 0);
						$this->lfText($curX + $dx + $pos, $curY + $dy, $this->sjis_conv($down_code), $fs, 'B');
					//::} else if ($up_code == '91' || $up_code == '92') {
					//::	//$this->pdf->SetTextColor(0, 0, 255);//青色
					//::	$this->pdf->SetTextColor(5, 5, 240); //青色
					//::	$this->lfText($curX + $dx, $curY + $dy, $this->sjis_conv($up_code), $fs, 'B');
					//::	$this->pdf->SetTextColor(0, 0, 0);
					//::	$this->lfText($curX + $dx + $pos, $curY + $dy, $this->sjis_conv($down_code), $fs, 'B');
					//::} else if ($up_code == '31' || $up_code == '32') {
					} else if ($up_code == PCODE_NECKLACE_SMALL || $up_code == PCODE_NECKLACE_LARGE ) {//::N00083 Change 20131201
						$this->pdf->SetTextColor(255, 127, 0); //オレンジ色
						$this->lfText($curX + $dx, $curY + $dy, $this->sjis_conv($up_code), $fs, 'B');
						$this->pdf->SetTextColor(0, 0, 0);
						$this->lfText($curX + $dx + $pos, $curY + $dy, $this->sjis_conv($down_code), $fs, 'B');
					} else if (strlen($up_code) == 2) {
						$this->pdf->SetTextColor(0, 0, 0);
						$this->lfText($curX + $dx, $curY + $dy, $this->sjis_conv($seal['product_code']), $fs, 'B');
					} else {
						$this->pdf->y += 1;
						$this->pdf->SJISMultiLineCell($this->seal_w, 8, $this->sjis_conv($seal['product_code']), 0, 'C', 0, 0, 0, $fs);
					}

					$name = $seal['name'];
					
					$this->pdf->SetFont('SJIS', '', 10.5);
					$this->pdf->x += $img_w+1;
					$this->pdf->y += 7;
					$this->pdf->SJISMultiLineCell($this->seal_w - $img_w-1, 4, $this->sjis_conv($name), 0, 'C', 0, 2, 2, 10.5);
					
					$this->pdf->y += 13; //201807 change
					
					//単品
                    //::N00062 Change 20130531
                    if ((empty($seal['set_content'])  || $seal['set_content']  == 'なし')
                     && (empty($seal['set_content4']) || $seal['set_content4'] == 'なし')) {
                        //付属品とピンク袋両方なし
                        $content = 'なし';
                    } elseif(empty($seal['set_content'])  || $seal['set_content']  == 'なし') {
                        //付属品なし、ピンク袋あり
                        $content = $seal['set_content4'];
                    } elseif(empty($seal['set_content4']) || $seal['set_content4'] == 'なし') {
                        //付属品あり、ピンク袋なし
                        $content = $seal['set_content'];
                    } else {
                        //付属品あり、ピンク袋あり
                        $content = $seal['set_content']."/".$seal['set_content4'];
                    }
                    //::N00062 end 20130531
					if ($type == 2) { //羽織物    バッグ
						$content = $seal['set_content1'];
					} elseif ($type == 3) { //ネックレス
						$content = $seal['set_content2'];
					} elseif ($type == 4) { //バッグ
						$content = $seal['set_content3'];
					}

					$this->pdf->SJISMultiLineCell($this->seal_w - $img_w-1, 5, $this->sjis_conv($content), 0, 'L', 0, 6, 6, 8);

					$this->pdf->y = $curY + $this->seal_h - 10;
					
					$this->pdf->SetFillColor(255, 255, 0);
					$this->pdf->SetFont('SJIS', '', 5);
					
					$this->pdf->x = $curX;
					$this->pdf->y = $curY;
					
					//$this->pdf->SJISMultiLineCell( $this->seal_w ,$this->seal_h,'',1,'C',0,0,0,22 );
					$this->pdf->Cell($this->seal_w + $this->seal_hspace, $this->seal_h + $this->seal_vspace, "", 0, 0, 'C', 0);
					
					$col++;
					if ($col > 2) {
						$col = 0;
						$this->pdf->Ln();
					}
				}
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
		return (mb_convert_encoding($conv_str, "SJIS-win", CHAR_CODE));
	}

	// 詳細データの取得
	function lfGetProductInfo($p_id)
	{
		$comma_pid = implode(",", $p_id);
		$objQuery = SC_Query_Ex::getSingletonInstance();
         
        //::B00021 Change 20130514 N00062 Change 20130531 取得カラム追加
		$sql = "SELECT T1.product_id,T2.product_code,T1.name,T1.main_image,T3.set_content,T3.set_content1,T3.set_content2,T3.set_content3,T3.set_content4
				,T1.main_list_image,T1.photo_gallery_image5,T1.photo_gallery_image10,T1.sub_image1,T1.display_position_seal_tag_id,T1.photo_gallery_image9,T1.photo_gallery_image12
              FROM dtb_products AS T1 LEFT JOIN
              (SELECT
                      product_code ,product_id
                 FROM dtb_products_class
                WHERE classcategory_id1 = 0
                  AND classcategory_id2 = 0) AS T2
                ON T1.product_id = T2.product_id
                LEFT JOIN dtb_products_ext AS T3 ON T3.product_id =T1.product_id ";
		$sql .= " where T1.product_id in (" . $comma_pid . ")  ORDER BY T1.update_date DESC, T1.product_id DESC ";
		$arrRet = $objQuery->getAll($sql);
		return $arrRet;
	}

	function lfGetEnglishName($name)
	{

		$p1 = strrpos($name, " ");
		$p2 = strrpos($name, "　");

		$e_name = "";
		if ($p1 < $p2) {
			$p1 = $p2;
		}
		$e_name = substr($name, 0, $p1);
		if (!empty($e_name)) {

			$e_name1 = mb_substr($name, $p1 + 1);

			preg_match("/^[\w]+/", $e_name1, $match);

			if (count($match) > 0) {
				$e_name = $e_name . ' ' . $match[0];
			}

			return $e_name;
		}

		preg_match("/^[\w-]+/", $name, $match);

		if (count($match) > 0) {
			return $match[0];
		}
		return "";
	}

	function getBrand($name)
	{
		preg_match("/[\w\s-']+.[\w-]*/", $name, $ma);
		return $ma[0];
	}

	function checkJapan($content)
	{

		if (preg_match('/[\x80-\xfe]/', $content)) {
			return true;
		} else {
			return false;
		}
//        $c1 = preg_match('!['
//            .'\x{2E80}-\x{2EFF}'//
//            .'\x{31C0}-\x{31EF}\x{3200}-\x{32FF}'
//            .'\x{3400}-\x{4DBF}\x{4E00}-\x{9FBF}\x{F900}-\x{FAFF}'
//            .'\x{20000}-\x{2A6DF}\x{2F800}-\x{2FA1F}'//
//            .']+!u', $content);
//
//
//        //
//        $c2 = preg_match('!['
//            .'\x{3040}-\x{309F}'//h
//            .'\x{30A0}-\x{30FF}'//k
//            .'\x{31F0}-\x{31FF}'//
//            .']+!u', $content);
//        if($c1 || $c2){
//            return true;
//        }
//        return false;
	}

}

?>
