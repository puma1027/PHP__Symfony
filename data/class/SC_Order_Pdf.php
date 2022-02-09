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
    define('PDF_IMG_DIR', HTML_REALDIR . USER_DIR . USER_PACKAGE_DIR . TEMPLATE_NAME . '/img/pdf/');
    class SC_Order_Pdf
    {

        var $tpl_title = 'レンタル票';
        var $left_magin = 10.5;
        var $top_magin = 15.7;
        var $right_magin = 10.5;
        var $title_magin = 9;
        var $enquete = 146;
        var $r_date;

        var $msg_region_height = 36;

        var $deliv_addr02_max_len = 21;

        function SC_Order_Pdf($download, $tpl_pdf = "template_nouhin01.pdf")
        {


            // デフォルトの設定
            $this->tpl_pdf = PDF_TEMPLATE_DIR . $tpl_pdf; // テンプレートファイル
            $this->pdf_download = $download; // PDFのダウンロード形式（0:表示、1:ダウンロード）
            $this->tpl_dispmode = "real"; // 表示モード
            $masterData = new SC_DB_MasterData_Ex();
            $this->arrPref = $masterData->getMasterData("mtb_pref", array("id", "name", "rank"));
            //$this->width_cell = array(80.5,9,19,20);
            $this->width_cell = array(70, 13, 9, 3);

            $this->label_cell[] = $this->sjis_conv("商品名 / 商品コード");
            $this->label_cell[] = $this->sjis_conv("商品写真");
            $this->label_cell[] = $this->sjis_conv("数量");
            // DEL 201806
            //$this->label_cell[] = $this->sjis_conv("金額(税込)");

            $this->arrMessage = array(
                'このたびはワンピの魔法のレンタルをご利用いただき、ありがとうございます。',
                '下記の内容にてお届けいたします。',
                'ご確認いただきますよう、お願いいたします。'
            );

            $this->pdf = new PDF_Japanese('l', 'mm', 'A4');

            // SJISフォント
            $this->pdf->AddSJISFont();

            //ページ総数取得
            $this->pdf->AliasNbPages();

            // マージン設定
            $this->pdf->SetMargins($this->left_magin, $this->top_magin, $this->right_magin);
            // PDFを読み込んでページ数を取得
            $pageno = $this->pdf->setSourceFile($this->tpl_pdf);
        }


        function setData($arrData, $title)
        {
            $this->tpl_title = $title;
            $this->arrData = $arrData;

            // ページ番号よりIDを取得
            $tplidx = $this->pdf->ImportPage(1);

            // ページを追加（新規）
            $this->pdf->AddPage();

            //表示倍率(100%)
            $this->pdf->SetDisplayMode($this->tpl_dispmode);

            // テンプレート内容の位置、幅を調整 ※useTemplateに引数を与えなければ100%表示がデフォルト
            $this->pdf->useTemplate($tplidx);

            $this->setShopData();
            $this->setOrderData(true);
            $this->setEtcData(true);

        }

        function setShopData()
        {
            // ショップ情報
            $shopleft = $this->enquete + 85;
            $shoptop = 28 + $this->title_magin;

            $objDb = new SC_Helper_DB_Ex();
            $arrInfo = $objDb->sfGetBasisData();

            $this->lfText($shopleft, $shoptop, $arrInfo['shop_name'], 8, 'B'); //ショップ名
            $this->lfText($shopleft, $shoptop + 3, $arrInfo['law_url'], 8); //URL
            $this->lfText($shopleft, $shoptop + 8, $arrInfo['law_company'], 8); //会社名
            $text = "〒 " . $arrInfo['zip01'] . " - " . $arrInfo['zip02'];
            $this->lfText($shopleft, $shoptop + 11, $text, 8); //郵便番号
            $text = $this->arrPref[$arrInfo['law_pref']] . $arrInfo['addr01'];
            $this->lfText($shopleft, $shoptop + 14, $text, 8); //都道府県+住所1
            $this->lfText($shopleft, $shoptop + 17, $arrInfo['addr02'], 8); //住所2
            //20171213　change law_addr01　→　addr01, law_addr02　→　addr02, law_zip01-> zip01, law_zip02-> zip02

            if (strlen($arrInfo['law_email']) > 0) {
                $text = "Email: " . $arrInfo['law_email'];
                $this->lfText($shopleft, $shoptop + 20, $text, 8); //Email
            }

        }

        function setManageData()
        {
            $m_left = $this->enquete + 24;
            $m_width = 26;

            $this->pdf->SetFont('SJIS', '', 8);
            $this->pdf->Cell($m_left, 0, '', 0, 0, 'C', 0, '');
            $this->pdf->Cell($m_width, 5, $this->sjis_conv('アンケート '), 'LTR', 0, 'L', 0, '');
            $this->pdf->Cell($m_width, 5, $this->sjis_conv('状態'), 'LTR', 0, 'L', 0, '');
            $this->pdf->Cell($m_width, 5, $this->sjis_conv('返却メール'), 'LTR', 0, 'L', 0, '');
            $this->pdf->Cell(0, 5, $this->sjis_conv('決済 '), 'LTR', 1, 'L', 0, '');
            $this->pdf->Cell($m_left, 0, '', 0, 0, 'C', 0, '');
            $this->pdf->Cell($m_width, 5, $this->sjis_conv('入力・未入力'), 'LBR', 0, 'C', 0, '');
            $this->pdf->Cell($m_width, 5, $this->sjis_conv('通常・金額変更'), 'LBR', 0, 'C', 0, '');
            $this->pdf->Cell($m_width, 5, $this->sjis_conv('送信済み'), 'LBR', 0, 'C', 0, '');
            $this->pdf->Cell(0, 5, $this->sjis_conv('／'), 'LBR', 1, 'C', 0, '');

        }

        function setOrderData()
        {

            $order_top = 24 + $this->title_magin;

            // ショップ情報
            $objInfo = new SC_SiteInfo();
            $arrInfo = $objInfo->data;

            // DBから受注情報を読み込む
            $this->lfGetOrderData($this->arrData['order_id']);

            //::B00005 Add 20130228 --------------------------------------------------
            // 購入者住所2の長さを検査し、長すぎる場合は2行目へ出力する。
            if(mb_strlen($this->arrDisp['deliv_addr02'], "UTF-8") > $this->deliv_addr02_max_len) {
                // お届け先情報
                // 購入者情報
                $this->lfText($this->enquete + $this->left_magin, $order_top - 6, '[ お届け先 ]', 8, 'B'); //[ お届け先 ]
                $text = "〒 " . $this->arrDisp['deliv_zip01'] . " - " . $this->arrDisp['deliv_zip02'];
                $this->lfText($this->enquete + $this->left_magin, $order_top -2 , $text, 9); //購入者郵便番号
                $text = $this->arrPref[$this->arrDisp['deliv_pref']] . $this->arrDisp['deliv_addr01'];
                $this->lfText($this->enquete + $this->left_magin + 5, $order_top + 2, $text, 9); //購入者都道府県+住所1

                // 購入者住所2を分割する
                $arrDelivAddr02 = $this->mb_str_split($this->arrDisp['deliv_addr02'], $this->deliv_addr02_max_len);
                // 1行目には分割した購入者住所2を表示
                $this->lfText($this->enquete + $this->left_magin + 5, $order_top + 6, $arrDelivAddr02[0], 9);
                // 2行目には残りすべてを表示するため残りを取得し表示する
                $this->lfText($this->enquete + $this->left_magin + 5, $order_top + 10, mb_substr($this->arrDisp['deliv_addr02'], $this->deliv_addr02_max_len, mb_strlen($this->arrDisp['deliv_addr02'], 'UTF-8'), 'UTF-8'), 9); //購入者住所2(レングス指定なし)

                $text = $this->arrDisp['deliv_name01'] . "　" . $this->arrDisp['deliv_name02'] . "　様";
                $this->lfText($this->enquete + $this->left_magin + 5, $order_top +15, $text, 9); //購入者氏名

                //電話番号
                $this->lfText($this->enquete + $this->left_magin + 5, $order_top +19, '' . $this->arrDisp['deliv_tel01'] . '-' . $this->arrDisp['deliv_tel02'] . '-' . $this->arrDisp['deliv_tel03'], 8); //メッセージ1

            } else {
            // お届け先情報
            // 購入者情報
            $this->lfText($this->enquete + $this->left_magin, $order_top - 4, '[ お届け先 ]', 8, 'B'); //[ お届け先 ]
            $text = "〒 " . $this->arrDisp['deliv_zip01'] . " - " . $this->arrDisp['deliv_zip02'];
            $this->lfText($this->enquete + $this->left_magin, $order_top, $text, 9); //購入者郵便番号
            $text = $this->arrPref[$this->arrDisp['deliv_pref']] . $this->arrDisp['deliv_addr01'];
            $this->lfText($this->enquete + $this->left_magin + 5, $order_top + 4, $text, 9); //購入者都道府県+住所1
            $this->lfText($this->enquete + $this->left_magin + 5, $order_top + 8, $this->arrDisp['deliv_addr02'], 9); //購入者住所2
            $text = $this->arrDisp['deliv_name01'] . "　" . $this->arrDisp['deliv_name02'] . "　様";
            $this->lfText($this->enquete + $this->left_magin + 5, $order_top + 13, $text, 9); //購入者氏名

            //電話番号
            $this->lfText($this->enquete + $this->left_magin + 5, $order_top + 17, '' . $this->arrDisp['deliv_tel01'] . '-' . $this->arrDisp['deliv_tel02'] . '-' . $this->arrDisp['deliv_tel03'], 8); //メッセージ1
            }
            //::B00005 End 20130228 --------------------------------------------------

            //ご利用明細
            $order_top += 56;

            $this->lfText($this->enquete + $this->left_magin, $order_top - 4, '[ ご注文日 ]', 8, 'B'); //ご注文日
            $this->lfText($this->enquete + $this->left_magin + 15, $order_top - 4, ' ' . SC_Utils_Ex::sfDispDBDate($this->arrDisp['create_date']), 6); //ご注文日

            // お届け時間
            $deliv_time = $this->arrDisp['deliv_time'];
            // 未入力なら指定なし
            if (empty($deliv_time)) {
              $deliv_time = "　指定なし";
            }
            $this->lfText($this->enquete + $this->left_magin + 35, $order_top - 4, '[お届け予定日]', 8, 'B'); // お届け予定日
            $this->lfText($this->enquete + $this->left_magin + 55, $order_top - 4, ' ' . $this->arrDisp['deliv_date'] . $deliv_time, 8); // お届け予定日

            $change_date = '';
            $change_date1 = '';
            $change_date2 = '';
            for ($i = 0; $i < count($this->arrDisp['change_flg']); $i++) {
                if ($this->arrDisp['change_flg'][$i] == 2) {
                    $change_date1 = $this->arrDisp['change_date'][$i];
                } elseif ($this->arrDisp['change_flg'][$i] == 1) {
                    $change_date2 = $this->arrDisp['change_date'][$i];
                }
            }

            if (!empty($change_date1) || !empty($change_date2)) {
                if (strtotime($change_date1) < strtotime($change_date2)) {
                    if ($change_date1 != '') {
                        $change_date = SC_Utils_Ex::sfDispDBDate($change_date1) . ' 商品の追加  ';
                    }
                    if ($change_date2 != '') {
                        $change_date .= SC_Utils_Ex::sfDispDBDate($change_date2) . ' 商品の変更  ';
                    }
                } else {
                    if ($change_date2 != '') {
                        $change_date = SC_Utils_Ex::sfDispDBDate($change_date2) . ' 商品の変更  ';
                    }
                    if ($change_date1 != '') {
                        $change_date .= SC_Utils_Ex::sfDispDBDate($change_date1) . ' 商品の追加  ';
                    }
                }
            }
            if (!empty($change_date)) {
                $this->lfText($this->enquete + $this->left_magin + 5, $order_top + 1, $change_date, 8); //ご注文商品の追加,変更
            }
            //add 20170719　帳票作成時が水or木、のみ顧客IDの横に☆を表示
            //「文字列1」が「文字列2」 よりも小さければ負の整数を、「文字列1」が 「文字列2」よりも大きければ正の整数を、 等しければ0を返します
            $w = array('日', '月', '火', '水', '木', '金', '土');
            if(strcmp( $w[date("w")], "水" ) == 0){
                $week = " ☆";
            }elseif(strcmp( $w[date("w")], "木" ) == 0){
                $week = " ☆";
            }
            //$week = $this->sjis_conv( "★" . $w[date("w")] . "曜日★" );
            //$this->pdf->Cell(0, 5, $week, 0, 1, 'R');

            //::N00054 Add 20130522
            $this->lfText($this->enquete+111, $this->title_magin, '[ 顧客ID ]', 8, 'B'); //顧客ID
            $this->lfBlueText($this->enquete+124, $this->title_magin, $this->arrDisp['customer_id'] . $week, 11); //顧客ID //::N00061 Change 20130524
            //::N00054 end 20130522
            $this->lfText($this->enquete + $this->left_magin + 93, $order_top - 4, '[ 注文番号 ]', 8, 'B'); //注文番号
            $this->lfRedText($this->enquete + $this->left_magin + 110, $order_top - 4, $this->arrDisp['order_id'], 18); //注文番号//::N00061 Change 20130524
            if ($this->arrDisp['include_orderid'] != null && $this->arrDisp['include_orderid'] != "") {
                //※注文番号20 を同梱
                $this->lfText($this->enquete + $this->left_magin + 93, $order_top+41, '※注文番号' . $this->arrDisp['include_orderid'] . 'を同梱', 8); //同梱注文番号
            }

            //文書タイトル（納品書・請求書）
            $this->pdf->SetFont('SJIS', 'B', 12);
            $this->pdf->Cell($this->enquete, 0, '', 0, 0, 'C', 0, '');
            $this->pdf->Cell(0, 7.2, $this->sjis_conv($this->tpl_title), 1, 2, 'C', 0, ''); //文書タイトル（納品書・請求書）
            $this->lfRedText($this->enquete-20, $this->title_magin + 199, "※返却時、必ず箱に同封して下さい。", 8); //::N00064 Add 20130625

            $return_top = 46 + $this->title_magin;
            //返却期限
            //※コンビニのポストへの投函は遅延となりますのでご遠慮ください。
            //※期限を過ぎると延滞金が発生します。
            $arrReturnDate = $this->lfGetReturnDate( $this->arrDisp['sending_date']);

            //☆「種別」＝「ポス」の場合
            $kind = 1;
            for ($i = 0; $i < count($this->arrDisp['product_type']); $i++) {
                if (($this->arrDisp['product_type'][$i] == DRESS4_PRODUCT_TYPE) ||
                    //($this->arrDisp['product_type'][$i] == SET_DRESS_PRODUCT_TYPE)) {//::N00083 Add 20131201
                    (strpos($this->arrDisp['product_code'][$i],PCODE_BAG) !== false)) {//::N00083 Add 20131201
                    $kind = 0;
                    break;
                }
            }
            if ($kind == 1) { //「種別」＝「ポス」の場合
                $this->lfRedText($this->enquete + $this->left_magin, $return_top, '返却期限', 8); ////返却期限 メッセージ
            } else { //「種別」＝「郵パ」の場合
                $this->lfRedText($this->enquete + $this->left_magin, $return_top, '返却期限', 8); ////返却期限 メッセージ
            }

            $this->lfRedText($this->enquete + $this->left_magin, $return_top + 5, $arrReturnDate[0] . '(' . $arrReturnDate[2] . ')' . ' ' . RETURN_TIME . 'まで', 11); //返却期限

            $this->lfText($this->enquete + $this->left_magin + 5, $return_top + 9, '※当店への到着日ではなく、お客様が返却手続きをする期限です。過ぎると延滞金が発生します。', 8);

            $this->pdf->Cell(0, 44, '', 0, 1, 'L', 0, '');


            $sum_top = $return_top + 16;

            $this->lfText($this->enquete + $this->left_magin, $sum_top, '総合計金額', 8, 'B'); //総合計金額
            $this->pdf->SetFont('SJIS', '', 9);
            $this->pdf->Cell($this->enquete, 0, '', 0, 0, 'C', 0, '');
            $this->pdf->Cell(67, 5, $this->sjis_conv(str_repeat(' ', 60) . number_format($this->arrDisp['payment_total']) . " 円"), 'B', 2, 'R', 0, '');
            $this->pdf->Cell(0, 2, '', 0, 2, '', 0, '');
            $this->pdf->SetFont('SJIS', 'B', 8);
            $this->pdf->SetFillColor(238, 238, 238);
            $this->pdf->Cell(0, 5, $this->sjis_conv('ご利用明細'), 1, 1, '', 1, '');
            $this->pdf->Cell(0, 8, '', 0, 2, '', 0, '');

            $this->pdf->SetFont('SJIS', '', 8);

            $monetary_unit = $this->sjis_conv("円");
            $point_unit = $this->sjis_conv("pt");
            $quantity_unit = $this->sjis_conv("点");


            if (!empty($change_date)) {
                $this->pdf->Cell(0, 5, '', 0, 1, 'L', 0, '');
                $this->msg_region_height = 32;
            }

            $arrOrderFive = array();

            //::N00083 Add 20131201
            $set_price = 0;
            for ($i = 0; $i < count($this->arrDisp['quantity']); $i++) {
                // 税込金額（単価）
                if ($this->arrDisp['set_pid'][$i]) {
                    //$set_price += $this->arrDisp['price'][$i];
                    $set_price += SC_Utils_Ex::sfPreTax($this->arrDisp['price'][$i], $arrInfo['tax'], $arrInfo['tax_rule']);
                }
                if ($this->arrDisp['product_id'][$i] == $this->arrDisp['set_pid'][$i]) {
                    $set_dress_pcode = $this->arrDisp['product_code'][$i];
                }
            }

            //::N00083 end 20131201

            // 購入商品情報
            for ($i = 0; $i < count($this->arrDisp['quantity']); $i++) {

                // 購入数量
                $data[0] = $this->arrDisp['quantity'][$i];

                // 税込金額（単価）
                //::N00083 Add 20131201
                //::$data[1] = SC_Utils_Ex::sfPreTax($this->arrDisp['price'][$i], $arrInfo['tax'], $arrInfo['tax_rule']);
                if ($this->arrDisp['product_id'][$i] == $this->arrDisp['set_pid'][$i]) {
                    // $data[1] = SC_Utils_Ex::sfPreTax($set_price, $arrInfo['tax'], $arrInfo['tax_rule']);
                    // $data[1] = $set_price;
                    $data[1] = SC_Utils_Ex::sfPreTax($this->arrDisp['price'][$i], $arrInfo['tax'], $arrInfo['tax_rule']);
                } else {
                    $data[1] = SC_Utils_Ex::sfPreTax($this->arrDisp['price'][$i], $arrInfo['tax'], $arrInfo['tax_rule']);
                }
                //::N00083 end 20131201

                // 小計（商品毎）
                $data[2] = $data[0] * $data[1];

                //::N00083 Add 20131201
                $objQuery = new SC_Query();
                $where = "product_id = ?";
                //$arrStock = $objQuery->select("A.sending_date,C.stock FROM (dtb_order AS A LEFT JOIN dtb_order_detail AS B ON A.order_id=B.order_id) LEFT JOIN dtb_products_class AS C ON C.product_id = B.product_id", $where, array($this->arrDisp['product_id'][$i]));
                $arrStock = $objQuery->select("stock", "dtb_products_class", $where, array($this->arrDisp['product_id'][$i]));
                $where = "A.sending_date = ? AND product_code = ?";
                $arrSameSendDay = $objQuery->select("A.sending_date,B.product_code", "dtb_order AS A LEFT JOIN dtb_order_detail AS B ON A.order_id = B.order_id", $where, array($this->arrDisp['sending_date_stock_cnt'][$i],$this->arrDisp['product_code'][$i]));

                //SC_Utils::sfPrintR($arrSameSendDay);
                $arr_pcode_cnt = array();
                foreach ($arrSameSendDay as $key=>$row) {
                    $arr_pcode_cnt[$row['sending_date']][$row['product_code']]++;
                }
                ksort($arr_pcode_cnt);
                //var_dump('arr_pcode_cnt');
                //SC_Utils::sfPrintR($arr_pcode_cnt);
                //::N00083 end 20131201

                $varOrder = array(
                  'diff1' => $this->arrDisp['diff1'][$i],
                  'diff2' => $this->arrDisp['diff2'][$i],
                  'payment_id1' => $this->arrDisp['payment_id1'][$i],
                  'payment_id2' => $this->arrDisp['payment_id2'][$i],
                  'payment_id' => $this->arrDisp['payment_id3'][$i]
                );
                $infive = -1;
                if($this->arrDisp['status']!=6 && $this->arrDisp['status']!=8){
                    //在庫数と同じ数の注文が入っている場合は、色をつける
                    if ($arrStock[0]['stock'] <= $arr_pcode_cnt[$this->arrDisp['sending_date_stock_cnt'][$i]][$this->arrDisp['product_code'][$i]]) {//::N00083 Add 20131201
                      $infive = $this->getOrderStatusColor($varOrder);
                    }
                }
                $arrOrderFive[$i] = $infive;

                //::B00061 Change 20140512
                //::$arrOrder[$i][0] = $this->sjis_conv($this->arrDisp['product_name'][$i] . "/\n"); //／
                //::$arrOrder[$i][0] .= $this->sjis_conv($this->arrDisp['product_code'][$i]);
                $arrOrder[$i][0] = $this->sjis_conv($this->arrDisp['product_code'][$i] . " / "); //／
                $arrOrder[$i][0] .= $this->sjis_conv($this->arrDisp['product_name'][$i]);
                //::B00061 end 20140512
                if ($this->arrDisp['classcategory_name1'][$i]) {
                    $arrOrder[$i][0] .= $this->sjis_conv(" [ " . $this->arrDisp['classcategory_name1'][$i]);
                    if ($this->arrDisp['classcategory_name2'][$i] == "") {
                        $arrOrder[$i][0] .= " ]";
                    } else {
                        $arrOrder[$i][0] .= $this->sjis_conv(" * " . $this->arrDisp['classcategory_name2'][$i] . " ]");
                    }
                }
                //::N00083 Add 20131201
                if (!empty($this->arrDisp['set_pid'][$i])) {
                    $arrOrder[$i][0] .= $this->sjis_conv("  (※".$set_dress_pcode."のセット商品)");
                }
                //::N00083 end 20131201
                $arrOrder[$i][1] = $this->arrDisp['main_list_image'][$i];
                $arrOrder[$i][2] = number_format($data[0]);
                //::N00083 Add 20131201
                //::$arrOrder[$i][3] = number_format($data[2]) . $monetary_unit;

                // Dell 201806
                //$arrOrder[$i][3] = number_format($data[2]).$monetary_unit;
            }

            //購入商品
            $curY = $this->pdf->y;
            //::N00083 Change 20131201 //::B00061 Change 20140415
            //同梱商品数に応じてフォントサイズを変える
            if (count($this->arrDisp['quantity']) < 3) {
                $this->pdf->FancyTable($this->enquete, $this->label_cell, $arrOrder, $this->width_cell, 8.75,$arrOrderFive, 8,$arrSetPid);
            } else if ((3 <= count($this->arrDisp['quantity'])) && (count($this->arrDisp['quantity']) <= 6)) {
                $this->pdf->FancyTable($this->enquete, $this->label_cell, $arrOrder, $this->width_cell, 6,$arrOrderFive, 7,$arrSetPid);
            } else {
                $this->pdf->FancyTable($this->enquete, $this->label_cell, $arrOrder, $this->width_cell, 4.8,$arrOrderFive, 6,$arrSetPid);
            }
            //::N00083 end 20131201 //::B00061 end 20140415

            $lastY = $this->pdf->y;
            //new
            $cellws = array(18.5, 18.5);

            $this->pdf->Cell(0, 3, '', 0, 1, 'L', 0, '');

            $sum_data[0] = array($this->sjis_conv("商品点数"), number_format(count($this->arrDisp['quantity'])) . $quantity_unit);
            $sum_data[1] = array($this->sjis_conv("商品合計"), number_format($this->arrDisp['subtotal']) . $monetary_unit);
            $sum_data[2] = array($this->sjis_conv("送料"), number_format($this->arrDisp['deliv_fee']) . $monetary_unit);
            $sum_data[3] = array($this->sjis_conv("手数料"), number_format($this->arrDisp['charge']) . $monetary_unit);
            $sum_data[4] = array($this->sjis_conv("あんしん保証"), number_format($this->arrDisp['relief_value']) . $monetary_unit);
            $sum_data[5] = array($this->sjis_conv("値引き"), "- " . number_format(($this->arrDisp['use_point'] * POINT_VALUE) + $this->arrDisp['discount']) . $monetary_unit);
            $sum_data[6] = array($this->sjis_conv("請求金額"), number_format($this->arrDisp['payment_total']) . $monetary_unit);

            if ($this->arrData['disp_point'] && $this->arrDisp['customer_id']) {
                $sum_data[7] = array($this->sjis_conv("利用ポイント"), number_format($this->arrDisp['use_point']) . $point_unit);
                $sum_data[8] = array($this->sjis_conv("加算ポイント"), number_format($this->arrDisp['add_point']) . $point_unit);
                $sum_data[9] = array($this->sjis_conv("所有ポイント"), number_format($this->arrDisp['point']) . $point_unit);
            } else {
                $sum_data[7] = array($this->sjis_conv("利用ポイント"), "0" . $point_unit);
                $sum_data[8] = array($this->sjis_conv("加算ポイント"), "0" . $point_unit);
                $sum_data[9] = array($this->sjis_conv("所有ポイント"), "0" . $point_unit);
            }

            $this->pdf->y = $curY;
            $this->pdf->VTable($this->enquete + 93, array(), $sum_data, $cellws, 5, 5);
            $this->pdf->y = $lastY;

        }

        function setEtcData()
        {
            $msg_region_height = $this->msg_region_height;
            if (count($this->arrDisp['quantity']) < 3) {
                $this->pdf->Cell(0, 18 * (2 - count($this->arrDisp['quantity'])), '', 0, 1, 'C', 0, '');
            }

            $this->pdf->Cell(0, 2, '', 0, 1, 'C', 0, '');
            $this->pdf->Cell($this->enquete, 0, '', 0, 0, 'C', 0, '');

            $this->pdf->SetFont('SJIS', '', 9);
            $this->pdf->MultiCell(0, 5, $this->sjis_conv("＜その他ご連絡事項＞"), '', 'L', '', 1); //備考
            //$this->pdf->Cell( $this->enquete+4 , 0, '', 0, 0, 'C', 0, '');
            //$this->pdf->MultiCell(0, 5, $this->sjis_conv("「不在時は宅配BOXへ」"), '', 'L', '', 1);  //備考
            $this->pdf->SetFont('SJIS', '', 8);

            //その他ご連絡事項
            $msg_height = $msg_region_height;
            if (count($this->arrDisp['quantity']) > 3) {
                $msg_height = $msg_region_height - 12 * (count($this->arrDisp['quantity']) - 3);
            }
            $this->pdf->Cell(4, 0, '', 0, 0, 'L', 0, '');

            //$this->arrDisp['message'] = str_repeat('あ', 3000);
            $this->pdf->Cell($this->enquete, 0, '', 0, 0, 'C', 0, '');
            $lnmax = ($msg_height) / 4 -1;

            $MAX_CNT = 34;//
            $arr = explode("\n",$this->arrDisp['message']);

            $ln_count = count($arr );
            $i = 0;
            $new_arr = array();
            $after = array();
            foreach($arr as $item ){

                $i++;
                if(mb_strlen($item)>$MAX_CNT){
                    $str1 = mb_strcut($item,0,$MAX_CNT);
                    $str2 = mb_strcut($item,$MAX_CNT);

                    $new_arr[] = $str1;
                    $new_arr[] = $str2;
                    $ln_count++;
                    $i++;
                }else{
                    $new_arr[] = $item;
                }
            }
            $arr = $new_arr;

            if($ln_count>$lnmax ){
                if(count($arr)>$lnmax ){
                    $cnt = $ln_count;
                    for($i= count($arr)-2;$i>0;$i--){
                        if($cnt<$lnmax-2){
                            break;
                        }
                        $new = $arr[$i].$arr[$i+1];
                        $arr[$i] = $new;
                        unset($arr[$i+1]);
                        if(mb_strlen($new)<=$MAX_CNT*($ln_count-$cnt+1)){
                            $cnt--;
                        }
                    }
                    $this->arrDisp['message'] = implode("\n",$arr);
                }else{
                    $this->arrDisp['message'] = preg_replace( "/\n/u","",$this->arrDisp['message'] );
                }
            }
            $break_count = $this->pdf->MultiCell(0, 4, $this->sjis_conv($this->arrDisp['message']), 0, 'L', '', 0,$lnmax); //その他ご連絡事項

            //弊社記入欄
            $left_pos = $this->enquete;
            $lnheight = $msg_height;
            if ($lnheight < 0) {
                $lnheight = 0;
            }
            $this->pdf->Cell(0, $lnheight, '', 0, 1, 'L', 0, '');
            //弊社記入欄
            $this->pdf->Cell($left_pos, 5, '', 0, 0, 'L', 0, '');
            $strCellData = "≪弊社記入欄≫  お誕生日 ".$this->lfGetBirthDay();
            $this->pdf->MultiCell(0, 5, $this->sjis_conv($strCellData), 0, 'L', 0, 1);

            //種別   カード   注文回数  前回No.  誕生日   到着日   アンケート   返完   決済
            $this->pdf->Cell($left_pos, 5, '', 0, 0, 'L', 0, '');
            $this->pdf->MultiCell(14, 5, $this->sjis_conv('箱'), 1, 'C', 1, 0);
            $this->pdf->MultiCell(16, 5, $this->sjis_conv('配送方法'), 1, 'C', 1, 0);
            $this->pdf->MultiCell(16, 5, $this->sjis_conv('カード'), 1, 'C', 1, 0);
            $this->pdf->MultiCell(16, 5, $this->sjis_conv('注文回数'), 1, 'C', 1, 0);
$this->pdf->MultiCell(16, 5, $this->sjis_conv('発送番号'), 1, 'C', 1, 0);
            $this->pdf->MultiCell(12, 5, $this->sjis_conv('到着日'), 1, 'C', 1, 0);
            $this->pdf->MultiCell(16, 5, $this->sjis_conv('アンケート'), 1, 'C', 1, 0);
            $this->pdf->MultiCell(12, 5, $this->sjis_conv('返完'), 1, 'C', 1, 0);
            $this->pdf->MultiCell(12, 5, $this->sjis_conv('決済'), 1, 'C', 1, 1);

            $weight = SC_Helper_Delivery_Ex::sfCalcWeight($this->arrDisp['product_code'], $this->arrDisp['product_name']);
            $box = '小サイズ';

            // 箱サイズのフォントを指定(デフォルトはサイズ12、ボールドあり)
            $box_font_setting = array(
              "box_font_size" => 12,
              "box_bold_style" => "B",
            );
            if ($weight >= 8 && $weight < 20) {
              $box = '中';
            } else if ($weight >= 20) {
              $box = '大';
            } else {
              // 小サイズの場合はフォントサイズを8に設定しボールド解除
              $box_font_setting["box_font_size"] = 8;
              $box_font_setting["box_bold_style"] = "";
            }

            $this->pdf->Cell($left_pos, 12, '', 0, 0, 'L', 0, '');
            $this->pdf->SetFont('SJIS', $box_font_setting["box_bold_style"], $box_font_setting["box_font_size"]);
            $this->pdf->Cell(14, 12, $this->sjis_conv($box), 1, 0, 'C');
            // フォントサイズを8から変更している場合
            if ($box_font_setting["box_font_size"] !== 8) {
              $this->pdf->SetFont('SJIS', '', 8);
            }

            $kind = SC_Helper_Payment_Ex::sfDeliveryTypeForPayment($this->arrDisp['payment_id']);
            $this->pdf->SJISMultiLineCell(16, 6, $this->sjis_conv($kind), 0, 'L', 0, 2, 2);

            //新規 なし 誕 特
            $text = "特";

            //::B00094 Add 20140515
            $objQuery = new SC_Query();
            $where = "customer_id = ?  AND status <>6 AND del_flg=0 ";
            $objQuery->setorder("order_id ASC");
            $arrRets = $objQuery->select("*", "dtb_order", $where, array($this->arrDisp['customer_id']));
            $curReturnDate =  $this->lfGetReturnDate( $this->arrDisp['sending_date']);
            foreach ($arrRets as $item) {
                $arrReturnDate =  $this->lfGetReturnDate( $item['sending_date']);
                if ($curReturnDate[0] == $arrReturnDate[0]) {
                    $arrRet[] = $item;
                }
            }
            //::B00094 end 20140515

            if ($this->arrDisp['order_birth'] != null && $this->arrDisp['order_birth'] != "") {
                $bir = strtotime($this->arrDisp['order_birth']);

                $fbir = mktime(0, 0, 0, date('m', $bir), date('d', $bir), date('Y', $this->r_date));
                $fres = mktime(0, 0, 0, date('m', $this->r_date), date('d', $this->r_date), date('Y', $this->r_date));

                if ($this->arrDisp['order_count'] < 2) {
                    $text = "新規";
                }

                /*//::B00094 Del 20140515
                $objQuery = new SC_Query();
                $where = "customer_id = ?  AND status <>6 AND del_flg=0 ";
                $objQuery->setorder("order_id ASC");
                $arrRets = $objQuery->select("*", "dtb_order", $where, array($this->arrDisp['customer_id']));

                $curReturnDate =  $this->lfGetReturnDate( $this->arrDisp['sending_date']);

                foreach ($arrRets as $item) {
                    $arrReturnDate =  $this->lfGetReturnDate( $item['sending_date']);
					if ($curReturnDate[0] == $arrReturnDate[0]) {
                        $arrRet[] = $item;
                    }
                }
                */

                if (count($arrRet) > 1) {
                    if ($arrRet[0]['order_id'] == $this->arrDisp['order_id']) {
                        if (($fres / 86400 - $fbir / 86400) < 5 && ($fres / 86400 - $fbir / 86400) > 0) {
                            //::$text = "誕";
                            $text = "BD";//::N00122 Change 20140311
                        } else if ($this->arrDisp['order_count'] < 2) {
                            $text = "新規";
                        }
                    } else {
                        $text = "なし";
                    }
                } else {
                    if (($fres / 86400 - $fbir / 86400) < 5 && ($fres / 86400 - $fbir / 86400) > 0) {
                        //::$text = "誕";
                        $text = "BD";//::N00122 Change 20140311
                    } else if ($this->arrDisp['order_count'] < 2) {
                        $text = "新規";
                    }
                }
            } else {
                if ($this->arrDisp['order_count'] < 2) {
                    //::B00094 Change 20140515
                    //::$text = "新規";
                    if ($arrRet[0]['order_id'] == $this->arrDisp['order_id']) {
                    $text = "新規";
                } else {
                        $text = "なし";
                    }
                    //::B00094 Add 20140515

                } else {
                    /*//::B00094 Del 20140515
                    $objQuery = new SC_Query();
                    $where = "customer_id = ? AND status <>6 AND del_flg=0 ";
                    $objQuery->setorder("order_id ASC");
                    $arrRets = $objQuery->select("*", "dtb_order", $where, array($this->arrDisp['customer_id']));

                    $curReturnDate = $this->lfGetReturnDate($this->arrDisp['sending_date'] );

                    foreach ($arrRets as $item) {
                        $arrReturnDate = $this->lfGetReturnDate( $item['sending_date']);
                        if ($curReturnDate[0] == $arrReturnDate[0]) {
                            $arrRet[] = $item;
                        }
                    }
                    */

                    if (count($arrRet) > 1) {
                        if ($arrRet[0]['order_id'] != $this->arrDisp['order_id']) {
                            $text = "なし";
                        }
                    }
                }
            }

            $this->pdf->Cell(16, 12, '', 1, 0, 'C');
            $this->pdf->Cell(16, 12, $this->sjis_conv($text), 1, 0, 'C');

            $text = "";
            if ($this->arrDisp['order_count'] > 1) {
                $text = $this->arrDisp['order_count'] . ' 回目';
            } else {
                $text = '新規';
            }
            $this->pdf->Cell(16, 12, $this->sjis_conv($text), 1, 0, 'C');

//$_SESSION['today_symbol']
//$_SESSION['arrOrderSortNum']
$shippingNum = '';
$todaySweek = date('w');
        foreach ($_SESSION['arrOrderSortNum'] as $key => $value) {
            //exp..D-1, D-2
            if($value == $this->arrDisp['order_id']){
                $shippingNum = $_SESSION['today_symbol'] . '-' . ($key + 1);
                break;
            }
        }

//発送番号
$this->pdf->Cell(16, 12, $this->sjis_conv($shippingNum), 1, 0, 'C');

            // if (($this->arrDisp['order_birth'] == null || $this->arrDisp['order_birth'] == "")
            //  //::N00185 Add 20140625
            //  || ($this->arrDisp['order_birth'] == "1901-01-01 00:00:00")
            //  //::N00185 end 20140625
            //     ){
            //     $this->pdf->Cell(16, 12, $this->sjis_conv(''), 1, 0, 'C');
            // } else {
            //     $this->pdf->y += 2;
            //     $this->pdf->SJISMultiLineCell(16, 4, $this->sjis_conv(date("Y年\nm月d日", strtotime($this->arrDisp['order_birth']))), 0, 'C', 0, 2, 2);
            //     $this->pdf->y -= 2;
            //     $this->pdf->Cell(16, 12, $this->sjis_conv(''), 1, 0, 'C');
            // }

            $this->pdf->Cell(12, 12, $this->sjis_conv('月・火'), 1, 0, 'C');
            $this->pdf->SJISMultiLineCell(16, 6, $this->sjis_conv("   ○・×\n入・未"), 0, 'L', 0, 2, 2);
            $this->pdf->Cell(16, 12, $this->sjis_conv(''), 1, 0, 'C');
            $this->pdf->Cell(12, 12, $this->sjis_conv(''), 1, 0, 'C');
            $this->pdf->MultiCell(12, 12, $this->sjis_conv('／'), 1, 'C', 0, 1);

            //作成日
            $this->pdf->Cell(0, 1, '', 0, 1, 'L', 0, '');
            //$this->pdf->Cell( $this->enquete+98 , 0, '', 0, 0, 'C', 0, '');
            $text = $this->sjis_conv("作成日: " . $this->arrData['year'] . "年" . $this->arrData['month'] . "月" . $this->arrData['day'] . "日");
            $this->pdf->Cell(0, 5, $text, 0, 1, 'R');

        }

        function createPdf()
        {
            // PDFをブラウザに送信
            ob_clean();
            if ($this->pdf->PageNo() == 1) {
                $filename = "nouhinsyo-No" . $this->arrData['order_id'] . ".pdf";
            } else {
                $filename = "nouhinsyo.pdf";
            }
            $this->pdf->Output($this->sjis_conv($filename), D);

            // 入力してPDFファイルを閉じる
            $this->pdf->Close();
        }

        // PDF_Japanese::Text へのパーサー
        function lfText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);

            $this->pdf->Text($x, $y, $text);
        }

        // PDF_Japanese::Colored Text へのパーサー
        function lfRedText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);
            $this->pdf->RedText($x, $y, $text);
        }

        //::N00054 Add 20130522
        // PDF_Japanese::Colored Text へのパーサー
        function lfBlueText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);
            $this->pdf->BlueText($x, $y, $text);
        }
        //::N00054 end 20130522

        // 受注データの取得
        function lfGetOrderData($order_id)
        {
            if (SC_Utils_Ex::sfIsInt($order_id)) {
                // DBから受注情報を読み込む
                $objQuery = new SC_Query();
                $where = "order_id = ?";
                $arrRet = $objQuery->select("*", "dtb_order", $where, array($order_id));
                list($point, $total_point) = SC_Helper_DB_Ex::sfGetCustomerPoint($order_id, $arrRet[0]['use_point'], $arrRet[0]['add_point']);

                $arrRet[0]['total_point'] = $total_point;
                $arrRet[0]['point'] = $point;

//:: N00009 Change 20130315 -------------------------------------------------------------
                //注文回数
                //::$where = "customer_id = " . $arrRet[0]['customer_id'] . " AND sending_date < '" . $arrRet[0]['sending_date'] . "' AND status <>6 AND del_flg=0 ";
                $where = "customer_id = " . $arrRet[0]['customer_id'] . " AND sending_date < '" . $arrRet[0]['sending_date'] . "' AND status not in (".ORDER_STATUS_UNDO.",".ORDER_STATUS_CANCEL.") AND del_flg=0 ";//::B00055 Change 20140515
                $order_count = $objQuery->count("dtb_order", $where) + 1;

                //前回の注文番号
                //::$where = "customer_id = " . $arrRet[0]['customer_id'] . " AND sending_date < '" . $arrRet[0]['sending_date'] . "' AND status <>6 AND del_flg=0 ORDER BY sending_date DESC  LIMIT 1";
                $where = "customer_id = " . $arrRet[0]['customer_id'] . " AND sending_date < '" . $arrRet[0]['sending_date'] . "' AND status not in (".ORDER_STATUS_UNDO.",".ORDER_STATUS_CANCEL.") AND del_flg=0 ORDER BY sending_date DESC  LIMIT 1";//::B00055 Change 20140515
                $old_order_id = $objQuery->select("order_id , customer_id , sending_date , status ", "dtb_order", $where);
//:: N00009 end 20130315 -------------------------------------------------------------


                $arrRet[0]['order_count'] = $order_count;
                $arrRet[0]['old_order_id'] = $old_order_id[0]['order_id'];

                $this->arrDisp = $arrRet[0];

                // 受注詳細データの取得
                $arrRet = $this->lfGetOrderDetail($order_id);

                // 商品コード順に並べ替える
                $tmpArray = array();
                foreach ($arrRet as $key => $row) {
                  $tmpArray[$key] = $row['product_code'];
                }
                array_multisort($tmpArray, SORT_ASC, $arrRet);
                unset($tmpArray);

                $arrRet = SC_Utils_Ex::sfSwapArray($arrRet);
                $this->arrDisp = array_merge($this->arrDisp, $arrRet);

                // その他支払い情報を表示
                if ($this->arrDisp["memo02"] != "") $this->arrDisp["payment_info"] = unserialize($this->arrDisp["memo02"]);
                if ($this->arrDisp["memo01"] == PAYMENT_CREDIT_ID) {
                    $this->arrDisp["payment_type"] = "クレジット決済";
                } elseif ($this->arrDisp["memo01"] == PAYMENT_CONVENIENCE_ID) {
                    $this->arrDisp["payment_type"] = "コンビニ決済";
                } else {
                    $this->arrDisp["payment_type"] = "お支払い";
                }

            }
        }

        // 受注詳細データの取得
        function lfGetOrderDetail($order_id)
        {
            $objQuery = new SC_Query();

			$sql = "SELECT T1.product_id,
						max(T1.product_code) as product_code,max(T1.product_name) as product_name, max(T1.classcategory_name1) as classcategory_name1,
						max(T1.classcategory_name2) as classcategory_name2, max(T1.price) as price, max(T1.quantity) as quantity ,
						max(T1.point_rate) as point_rate,max(T1.change_flg) as change_flg,max(T1.change_date) as change_date ,
						max(dtb_products.main_image) as main_image,max(dtb_products.product_type) as product_type,max(dtb_products.main_list_image) as main_list_image,
						min(CASE when dtb_order.sending_date < B.sending_date THEN B.sending_date ELSE null END ) as max_date,
						max(CASE when dtb_order.sending_date > B.sending_date THEN B.sending_date ELSE null END ) as min_date,
						min(CASE when dtb_order.sending_date < B.sending_date THEN B.sending_date-dtb_order.sending_date ELSE 99999 END ) as diff1,
						min(CASE when dtb_order.sending_date > B.sending_date THEN dtb_order.sending_date-B.sending_date ELSE 99999 END ) as diff2,
            max(T1.set_pid) as set_pid, max(T1.set_ptype) as set_ptype,max(dtb_order.sending_date) as sending_date_stock_cnt,dtb_order.payment_id As payment_id3,
            min(CASE when dtb_order.sending_date < B.sending_date THEN B.payment_id ELSE null END ) as payment_id1,
						max(CASE when dtb_order.sending_date > B.sending_date THEN B.payment_id ELSE null END ) as payment_id2
					FROM dtb_order
					INNER JOIN dtb_order_detail as T1 on  (dtb_order.order_id = ? and dtb_order.order_id = T1.order_id)
					INNER JOIN
					(
						SELECT dtb_order_detail.product_id,dtb_order.order_id, dtb_order.sending_date, " . "dtb_order.sending_date || '_' || dtb_order.payment_id as payment_id" . "
						 FROM dtb_order inner join dtb_order_detail on (dtb_order_detail.order_id = dtb_order.order_id)
						 WHERE dtb_order.del_flg <>1 and dtb_order.status not in (6,8)
					 ) B on (T1.product_id = B.product_id)
					LEFT JOIN dtb_products on T1.product_id= dtb_products.product_id
					GROUP BY T1.product_id,dtb_order.payment_id
					ORDER BY set_pid, set_ptype";

            $res = $objQuery->getall($sql, array($order_id));
            return $res;
        }

        // 文字コードSJIS変換 -> japanese.phpで使用出来る文字コードはSJISのみ
        function sjis_conv($conv_str)
        {
            return (mb_convert_encoding($conv_str, "SJIS", CHAR_CODE));
        }

		function lfGetReturnDate($sending_date, $format = "Y年m月d日"){

			$return_date = "";
			$return_datestamp = "";
			$return_wday = "";
			//
			if (!empty($sending_date) ) {
				$sending_date = strtotime($sending_date);
				$return_date = date($format, strtotime("+4 day", $sending_date));
				$return_datestamp = strtotime("+4 day", $sending_date);
				$return_wday = date("N", strtotime("+4 day", $sending_date));
			}
			$wday_array = array('1' => '月',
				'2' => '火',
				'3' => '水',
				'4' => '木',
				'5' => '金',
				'6' => '土',
				'7' => '日');

			$this->r_date = $return_datestamp;

			return array($return_date, $return_datestamp, $wday_array[$return_wday]);
		}
        function lfGetReturnDateOld($year, $month, $day, $deliv_date, $create_date, $format = "Y年m月d日")
        {
            $return_wday = date("N", strtotime($year . '-' . $month . '-' . $day));
            $return_wday = 8 - $return_wday;

            $return_date = date($format, strtotime('+' . $return_wday . ' day'));
            $return_datestamp = strtotime('+' . $return_wday . ' day');
            $return_wday = date("N", strtotime('+' . $return_wday . ' day'));
            if ($deliv_date != null && $deliv_date != "") {

                $pattern = '/(1?[0-9])月([0-3]?[0-9])日(.*)/iu';///(1?[0-9])月([0-3]?[0-9])日\((.*)\)/iu
                preg_match($pattern, $deliv_date, $matches, PREG_OFFSET_CAPTURE);

                if (count($matches) > 3) {

                    $tmp_date = strtotime($year . '-' . $matches[1][0] . '-' . $matches[2][0]);

                    if ($tmp_date > strtotime($create_date)) {
                        $return_date = date($format, strtotime("+3 day", $tmp_date));
                        $return_datestamp = strtotime("+3 day", $tmp_date);
                        $return_wday = date("N", strtotime("+3 day", $tmp_date));
                    } else {
                        $tmp_date = strtotime(($year + 1) . '-' . $matches[1][0] . '-' . $matches[2][0]);
                        $return_date = date($format, strtotime("+3 day", $tmp_date));
                        $return_datestamp = strtotime("+3 day", $tmp_date);
                        $return_wday = date("N", strtotime("+3 day", $tmp_date));
                    }
                }
            } else {
                $return_date = date($format, strtotime($create_date));
            }
            $wday_array = array('1' => '月',
                '2' => '火',
                '3' => '水',
                '4' => '木',
                '5' => '金',
                '6' => '土',
                '7' => '日');

            $this->r_date = $return_datestamp;

            return array($return_date, $return_datestamp, $wday_array[$return_wday]);
        }

        function lfGetBirthDay() {
          if (($this->arrDisp['order_birth'] == null || $this->arrDisp['order_birth'] == "")
           //::N00185 Add 20140625
           || ($this->arrDisp['order_birth'] == "1901-01-01 00:00:00")
           //::N00185 end 20140625
           )
          {
              return '';
          } else {
            return date("Y年m月d日", strtotime($this->arrDisp['order_birth']));
          }
          return '';;
        }

        /**
        * 前回、次回の貸出日から商品名に付ける色を返す
        *
        * @param 受注情報の連想配列
        * @return int green=2 red=1 blue=0
        */
        function getOrderStatusColor($order)
        {
          $blue = false;
          $red = false;

          // 次回貸出し日が７日未満
          if ($order['diff1'] > 0 && $order['diff1'] < 7) {
            $red = true;
          }

          // 前回貸出し日が７日未満
          if ($order['diff2'] > 0 && $order['diff2'] < 7) {
            $blue = true;
          }

          // 両方成立
          if ($red && $blue) {
            // green
            return 2;
          }

          // 超速便の場合は7日の場合も色を付ける
          // データ構造が貸出日_支払方法IDなので分割する
          $arrPayment_id1 = explode("_", $order['payment_id1']);
          $payment_id1 = $arrPayment_id1[1];

          $arrPayment_id2 = explode("_", $order['payment_id2']);
          $payment_id2 = $arrPayment_id2[1];

          // 今回が超速便かつ中6日
          if (($order['diff1'] > 0 && $order['diff1'] == 7)
          && ($order['payment_id3'] == '12' || $order['payment_id3'] == '11'))
          {
            $red = true;
          }

          // 前回の貸出日が超速便
          if (($order['diff2'] > 0 && $order['diff2'] == 7)
          && ($payment_id2 == '12' || $payment_id2 == '11'))
          {
            $blue = true;
          }

          if ($red && $blue) {
            return 2;
          } else if ($red) {
            return 1;
          } else if ($blue) {
            return 0;
          } else {
            return -1;
          }
        }

        function mb_str_split($str, $split_len = 1, $encoding = 'UTF-8') {

          mb_internal_encoding($encoding);
          mb_regex_encoding($encoding);

          if ($split_len <= 0) {
            $split_len = 1;
          }

          $strlen = mb_strlen($str, $encoding);
          $ret = array();
          for ($i = 0; $i < $strlen; $i += $split_len) {
            $ret[ ] = mb_substr($str, $i, $split_len);
          }
          return $ret;
        }
    }
?>
