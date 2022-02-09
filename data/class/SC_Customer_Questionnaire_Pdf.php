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
 * [名称] SC_Customer_Questionnaire_Pdf
 * [概要] レンタル票pdfファイルを表示する。
 *----------------------------------------------------------------------
 */

require(DATA_REALDIR . 'module/fpdi/japanese.php');
define('PDF_TEMPLATE_DIR', DATA_REALDIR . 'pdf/questionnaire/');
define('PDF_IMG_DIR', HTML_REALDIR. USER_DIR. USER_PACKAGE_DIR. TEMPLATE_NAME. '/img/pdf/');

class SC_Customer_Questionnaire_Pdf {
    //::function SC_Customer_Questionnaire_Pdf($download, $title, $tpl_pdf = "template_nouhin01.pdf") {
    function __construct($download) {

        // デフォルトの設定
        $this->pdf_download = $download;      // PDFのダウンロード形式（0:表示、1:ダウンロード）

        $this->tpl_dispmode = "real";      // 表示モード

        //A4横
        $this->pdf = new PDF_Japanese('l', 'mm', 'A4');

        // SJISフォント
        $this->pdf->AddSJISFont();
    }


    function setData($arrData, $tpl_pdf = "template_nouhin01.pdf") {
        $this->arrData = $arrData;

        // テンプレートファイル
        $this->tpl_pdf = PDF_TEMPLATE_DIR . $tpl_pdf;
        // PDFを読み込んでページ数を取得
        $pageno = $this->pdf->setSourceFile($this->tpl_pdf);

        //表面
        // ページ番号よりIDを取得
        $tplidx = $this->pdf->ImportPage(1);
        // ページを追加（新規）
        $this->pdf->AddPage();
        //表示倍率(100%)
        $this->pdf->SetDisplayMode($this->tpl_dispmode);
        // テンプレート内容の位置、幅を調整 ※useTemplateに引数を与えなければ100%表示がデフォルト
        $this->pdf->useTemplate($tplidx);

        if ($pageno == 1) return;//::B00135 Add 20140722

        //::B00121 Add 20140626
        //裏面
        // ページ番号よりIDを取得
        $tplidx = $this->pdf->ImportPage(2);
        // ページを追加（新規）
        $this->pdf->AddPage();
        //表示倍率(100%)
        $this->pdf->SetDisplayMode($this->tpl_dispmode);
        // テンプレート内容の位置、幅を調整。useTemplateに引数を与えなければ100%表示がデフォルト
        $this->pdf->useTemplate($tplidx);
        //::B00121 end 20140626

    }

    function createPdf() {
        // PDFをブラウザに送信
        ob_clean();

        //ファイル名は5桁の顧客番号と注文番号なので、5桁未満の場合は0埋めする。
        $customer_val = str_pad($this->arrData['customer_id'], 5, "0", STR_PAD_LEFT);
        $order_val = str_pad($this->arrData['order_id'], 5, "0", STR_PAD_LEFT);
        //$filename = $customer_val."_".$order_val.".pdf";
        $filename = $order_val."_".$customer_val.".pdf";

        // PDFのダウンロード形式（0:表示、1:ダウンロード）
        if ($this->pdf_download == 0) {
            $this->pdf->Output($this->sjis_conv($filename), I);
        } else {
            $this->pdf->Output($this->sjis_conv($filename), D);
        }

        // 入力してPDFファイルを閉じる
        $this->pdf->Close();
    }

    // 文字コードSJIS変換 -> japanese.phpで使用出来る文字コードはSJISのみ
    function sjis_conv($conv_str) {
      return (mb_convert_encoding($conv_str, "SJIS", CHAR_CODE));
    }

}
?>
