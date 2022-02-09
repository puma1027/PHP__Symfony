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
define('PDF_IMG_DIR', HTML_REALDIR. USER_DIR. USER_PACKAGE_DIR. TEMPLATE_NAME. '/img/pdf/');

class SC_Order_PikingList_Pdf {
	
	var $tpl_title = 'ピッキングリスト';
	var $left_magin= 8;
	var $top_magin= 16;
    var $right_magin= 8;
	var $title_magin= 16;
	var $r_date;

    function SC_Order_PikingList_Pdf($download, $tpl_pdf = "empty.pdf") {

        // デフォルトの設定
        $this->tpl_pdf = PDF_TEMPLATE_DIR . $tpl_pdf;  // テンプレートファイル
        $this->pdf_download = $download;      // PDFのダウンロード形式（0:表示、1:ダウンロード）

        $this->tpl_dispmode = "real";      // 表示モード

        $this->pdf  = new PDF_Japanese('l','mm','A4');

        // SJISフォント
        $this->pdf->AddSJISFont();

        //ページ総数取得
        $this->pdf->AliasNbPages();

        // マージン設定
        $this->pdf->SetMargins($this->left_magin, $this->top_magin);

        // PDFを読み込んでページ数を取得
        $pageno = $this->pdf->setSourceFile($this->tpl_pdf);
    }


    function setData($arrData,$sums ,$title = 'ピッキングリスト') {
        $this->tpl_title = $title;
        $this->arrData = $arrData;
        $this->arrSum = $sums;
        // ページ番号よりIDを取得
        $tplidx = $this->pdf->ImportPage(1);

        // ページを追加（新規）
        $this->pdf->AddPage();

        //表示倍率(100%)
        $this->pdf->SetDisplayMode($this->tpl_dispmode);

        // テンプレート内容の位置、幅を調整 ※useTemplateに引数を与えなければ100%表示がデフォルト
        $this->pdf->useTemplate($tplidx);

        $this->setListData();
        
    }
    
    function setListData() {
        //文書タイトル
        $this->pdf->SetFont('SJIS', 'B', 12);
        $this->pdf->Cell(0, 8, $this->sjis_conv($this->tpl_title), 1, 2, 'C', 0, '');  //文書タイトル
        $this->pdf->Cell(0, 4, '', 0, 1, 'L', 0, '');

        //注文番号
        // 	ドレス・ワンピース（3754件） 	ストール・ボレロ（1560件） 	ネックレス・小物（1964件） 	バッグ（124件）
        //商品コード 	商品名 	商品コード 	商品名 	商品コード 	商品名 	商品コード 	商品名
        $w = array(16,16,48,16,48,16,48,16,48);//280-18
        $w = array(17,18,48,18,48,18,48,18,48);//280

        $this->pdf->SetFont('SJIS', 'B', 8);
        $this->pdf->SetFillColor(216,216,216);
        $this->pdf->SetTextColor(0);
        $this->pdf->SJISMultiLineCell( $w[0],10,$this->sjis_conv("注文番号"),1,'C',1 ,0,0);
        $this->pdf->x+=$w[0];
//        $this->pdf->SJISMultiLineCell( $w[0],10,$this->sjis_conv("顧客名"),1,'C',1 ,0,0);
//        $this->pdf->x+=$w[0];
        $this->pdf->MultiCell( $w[1]+$w[2],5,$this->sjis_conv("ドレス・ワンピース（".$this->arrSum['dress']."件）"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[3]+$w[4],5,$this->sjis_conv("ストール・ボレロ（".$this->arrSum['stole']."件）"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[5]+$w[6],5,$this->sjis_conv("ネックレス・小物（".$this->arrSum['necklace']."件）"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[7]+$w[8],5,$this->sjis_conv("バッグ（".$this->arrSum['bag']."件）"),1,'C',1 ,1,0);
        $this->pdf->x+=$w[0];
//        $this->pdf->x+=$w[0];
        $this->pdf->MultiCell( $w[1],5,$this->sjis_conv("商品コード"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[2],5,$this->sjis_conv("商品名"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[3],5,$this->sjis_conv("商品コード"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[4],5,$this->sjis_conv("商品名"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[5],5,$this->sjis_conv("商品コード"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[6],5,$this->sjis_conv("商品名"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[7],5,$this->sjis_conv("商品コード"),1,'C',1 ,0,0);
        $this->pdf->MultiCell( $w[8],5,$this->sjis_conv("商品名"),1,'C',1 ,1,0);

        $this->pdf->SetFillColor(200,200,200);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('SJIS', '', 10);
        $flag = true;
        $fill= 1;
        foreach($this->arrData as $row){

            if(empty($row['order_id'] )){
                $this->pdf->x+=$w[0];
//                $this->pdf->x+=$w[0];
            }else{
                $flag = !$flag;
                if($flag){
                    $this->pdf->SetFillColor(200,200,200);
                    $this->pdf->MultiCell( $w[0],5*$row['product_count'],$this->sjis_conv($row['order_id']),1,'C',$fill ,0);
//                    $this->pdf->MultiCell( $w[0],5*$row['product_count'],$this->sjis_conv($row['order_name']),1,'C',$fill ,0);
                }else{
                    $this->pdf->SetFillColor(255,255,255);
                    $this->pdf->MultiCell( $w[0],5*$row['product_count'],$this->sjis_conv($row['order_id']),1,'C',$fill ,0);
//                    $this->pdf->MultiCell( $w[0],5*$row['product_count'],$this->sjis_conv($row['order_name']),1,'C',$fill ,0);
                }
            }

            if($flag){//fill
                $this->pdf->SetFillColor(200,200,200);
            }else{
                $this->pdf->SetFillColor(255,255,255);
            }
            //Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')

            if($row['infive1']==2){
                $this->pdf->SetTextColor(0,192,0);
            }else if($row['infive1']==1){
                $this->pdf->SetTextColor(255,0,0);
            }else if($row['infive1']==0){
                $this->pdf->SetTextColor(0,0,255);
            }else{
                $this->pdf->SetTextColor(0,0,0);
            }
            $this->pdf->Cell($w[1],5,$this->sjis_conv($row['product_code1']),1,0,'L',$fill);
            $this->pdf->Cell( $w[2],5,$this->sjis_conv($row['product_name1']),1,0,'L',$fill);
            if($row['infive2']==2){
                $this->pdf->SetTextColor(0,192,0);
            }else if($row['infive2']==1){
                $this->pdf->SetTextColor(255,0,0);
            }else if($row['infive2']==0){
                $this->pdf->SetTextColor(0,0,255);
            }else{
                $this->pdf->SetTextColor(0,0,0);
            }
            $this->pdf->Cell( $w[3],5,$this->sjis_conv($row['product_code2']),1,0,'L',$fill);
            $this->pdf->Cell( $w[4],5,$this->sjis_conv($row['product_name2']),1,0,'L',$fill);
            if($row['infive3']==2){
                $this->pdf->SetTextColor(0,192,0);
            }else if($row['infive3']==1){
                $this->pdf->SetTextColor(255,0,0);
            }else if($row['infive3']==0){
                $this->pdf->SetTextColor(0,0,255);
            }else{
                $this->pdf->SetTextColor(0,0,0);
            }
            $this->pdf->Cell( $w[5],5,$this->sjis_conv($row['product_code3']),1,0,'L',$fill);
            $this->pdf->Cell( $w[6],5,$this->sjis_conv($row['product_name3']),1,0,'L',$fill);
            if($row['infive4']==2){
                $this->pdf->SetTextColor(0,192,0);
            }else if($row['infive4']==1){
                $this->pdf->SetTextColor(255,0,0);
            }else if($row['infive4']==0){
                $this->pdf->SetTextColor(0,0,255);
            }else{
                $this->pdf->SetTextColor(0,0,0);
            }
            $this->pdf->Cell( $w[7],5,$this->sjis_conv($row['product_code4']),1,0,'L',$fill);
            $this->pdf->Cell( $w[8],5,$this->sjis_conv($row['product_name4']),1,0,'L',$fill);

            $this->pdf->SetTextColor(0);

//            $this->pdf->MultiCell( $w[1],5,$this->sjis_conv($row['product_code1']),1,'L',$fill ,0,0);
//            $this->pdf->MultiCell( $w[2],5,$this->sjis_conv($row['product_name1']),1,'L',$fill ,0,0);
//            $this->pdf->MultiCell( $w[3],5,$this->sjis_conv($row['product_code2']),1,'L',$fill ,0,0);
//            $this->pdf->MultiCell( $w[4],5,$this->sjis_conv($row['product_name2']),1,'L',$fill ,0,0);
//            $this->pdf->MultiCell( $w[5],5,$this->sjis_conv($row['product_code3']),1,'L',$fill ,0,0);
//            $this->pdf->MultiCell( $w[6],5,$this->sjis_conv($row['product_name3']),1,'L',$fill ,0,0);
//            $this->pdf->MultiCell( $w[7],5,$this->sjis_conv($row['product_code4']),1,'L',$fill ,0,0);
//            $this->pdf->MultiCell( $w[8],5,$this->sjis_conv($row['product_name4']),1,'L',$fill ,0,0);

            $this->pdf->Ln();
        }

    }

    function createPdf() {
        // PDFをブラウザに送信
		ob_clean();

        $this->pdf->Output($this->sjis_conv($this->tpl_title.'.pdf'), D);

        // 入力してPDFファイルを閉じる
        $this->pdf->Close();
    }

    // PDF_Japanese::Text へのパーサー
    function lfText($x, $y, $text, $size, $style = '' ) {
        $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);
		
        $this->pdf->SetFont('SJIS', $style, $size);
        
        $this->pdf->Text($x, $y, $text);
    }

    // PDF_Japanese::Colored Text へのパーサー
    function lfRedText($x, $y, $text, $size, $style = '' ) {
    	$text = mb_convert_encoding($text, "SJIS", CHAR_CODE);
    
    	$this->pdf->SetFont('SJIS', $style, $size);
    	$this->pdf->RedText($x, $y, $text);
    }



    // 文字コードSJIS変換 -> japanese.phpで使用出来る文字コードはSJISのみ
    function sjis_conv($conv_str) {
        return (mb_convert_encoding($conv_str, "SJIS", CHAR_CODE));
    }


}
?>
