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
    * [名称] SC_Order_DressAccessorySeal_Pdf
    * [概要] ドレスとワンピースの付属品シール用 pdfファイルを表示する。N00062
    *----------------------------------------------------------------------
    */

    class SC_Order_DressAccessorySeal_Pdf
    {

        var $left_magin = 4;
        var $top_magin = 6;
        var $right_magin = 0;

        function SC_Order_DressAccessorySeal_Pdf()
        {

            // デフォルトの設定
            $this->pdf_download = $download; // PDFのダウンロード形式（0:表示、1:ダウンロード）

            $this->tpl_dispmode = "real"; // 表示モード
            $masterData = new SC_DB_MasterData_Ex();

            $this->pdf = new PDF_Japanese('P', 'mm', 'A4');

            // SJISフォント
            $this->pdf->AddSJISFont();

            //ページ総数取得
            $this->pdf->AliasNbPages();
            
            $this->arrInfive = array();
        }

        function setData($arrData, $i,$x,$y, $arrRet, $delev_pref)
        {
            $this->arrData = $arrData;
            $shopleft = $this->left_magin+($y);
            $shoptop = $this->top_magin+($x*22);
            $order_font = 10;
            $code_font = 10;
            $title_font = 8;
            $detail_font = 6;

            if ($i == 0) {
                // ページを追加（新規）
                $this->pdf->AddPage();
                if (($this->arrData['infive1'] == 2) || ($this->arrData['infive1'] == 0)) {
                    $category = '青字 and 緑字';
                } else {
                    $category = '通常 and 赤字';
                }
                $this->lfText(100, 3, $category, $detail_font, 'B'); //分類
            }

            //表示倍率(100%)
            $this->pdf->SetDisplayMode($this->tpl_dispmode);

            if (($this->arrData['infive1'] == 1) || ($this->arrData['infive1'] == 2)) {
                $this->pdf->SetFillColor(255, 255, 128);
                $this->pdf->SetXY($shopleft-2, $shoptop);
                $this->pdf->Cell(42, 21, '', 0, 0, '', 1, '');
            }

            if ($this->arrData['infive1'] == 2) {
                //green
                $this->lfGreenText($shopleft+22, $shoptop+4, $this->arrData['order_id'], $order_font, 'B'); //注文番号
            } else if ($this->arrData['infive1'] == 1) {
                //red
                $this->lfRedText($shopleft+22, $shoptop+4, $this->arrData['order_id'], $order_font, 'B'); //注文番号
            } else if ($this->arrData['infive1'] == 0) {
                //blue
                $this->lfBlueText($shopleft+22, $shoptop+4, $this->arrData['order_id'], $order_font, 'B'); //注文番号
            } else {
                //black
                $this->lfText($shopleft+22, $shoptop+4, $this->arrData['order_id'], $order_font, 'B'); //注文番号
            }

        if ($this->arrInfive[$this->arrData['order_id']] === 0) {

        } else if ($this->arrInfive[$this->arrData['order_id']] == 2) {
        if ($this->arrData['infive1'] == 0) {
            $this->arrInfive[$this->arrData['order_id']] = 0;
        }
        } else if ($this->arrInfive[$this->arrData['order_id']] == 1) {
        if ($this->arrData['infive1'] == 0 || $this->arrData['infive1'] == 2) {
            $this->arrInfive[$this->arrData['order_id']] = $this->arrData['infive1'];
        }
        } else {
        $this->arrInfive[$this->arrData['order_id']] = $this->arrData['infive1'];
        }

            $product_code = $arrRet[0]['product_code'];
            if (!SC_Helper_Delivery_Ex::sfIsNomalArea($delev_pref[0])){
              $product_code = "★".$product_code;
            }
            if ($delev_pref[1] == '500'){
              $product_code = "♪".$product_code;
            }

            $this->lfText($shopleft+14, $shoptop+20, $product_code, $code_font, 'B'); //商品コード


            //セパレート(kids, dress, setdress, onepiece)
            $cnt=0;
            $objQuery = new SC_Query();
            $silhouette_sql = 'SELECT silhouette_flag from dtb_products where product_id = ?';
            $separate_tops = '';
            $separate_under = '';
                $res = $objQuery->getall($silhouette_sql, $arrRet[0]['product_id']);

                    if(strpos($res[0]['silhouette_flag'],'1') !== false){
                        $separate_tops = 'kidsブラウス';
                        $separate_under = 'スカート';
                        $cnt=4;
                    }elseif(strpos($res[0]['silhouette_flag'],'2') !== false){
                        $separate_tops = 'kidsシャツ';
                        $separate_under = 'パンツ';
                        $cnt=4;
                    }elseif(strpos($res[0]['silhouette_flag'],'3') !== false){
                        $separate_tops = 'トップス';
                        $separate_under = 'スカート';
                        $cnt=4;
                    }elseif(strpos($res[0]['silhouette_flag'],'4') !== false){
                        $separate_tops = 'トップス';
                        $separate_under = 'パンツ';
                        $cnt=4;
                    }

            //付属品
            $set=1;
            $t = strtok($arrRet[0]['set_content'], "\/");   // 最初だけ文字列を渡す。/文字で割る
            while ($t !== false) {
                if ((strpos($t, "背中") !== false) ||
                    (strpos($t, "肩") !== false) ||
                    (strpos($t, "調節") !== false) ||
                    (strpos($t, "袖") !== false) ||
                    (strpos($t, "ひも") !== false) ||
                    (strpos($t, "胸元フリル") !== false) ||
                    (strpos($t, "すそ") !== false) ||
                    (strpos($t, "ショルダーリボン") !== false) ||
                    (strpos($t, "胸元の布") !== false) ||
                    (strpos($t, "ホルターネックリボン") !== false) ||
                    (strpos($t, "胸元レース") !== false) ||
                    (strpos($t, "付けえり") !== false) ||
                    (strpos($t, "ペプラム") !== false) ||
                    (strpos($t, "付けケープ") !== false) ||
                    (strpos($t, "ボタン付リボン") !== false) ||
                    (strpos($t, "ウエストリボン×2") !== false)
                    ){
                    $this->lfRedText($shopleft, $shoptop+10+$cnt, "・".$t, $detail_font, 'B');
                } elseif (strpos($t, "なし") !== false) {
                    //set_conentが『なし』の場合は表示しない。付属品がないので。
                    $set--;
                    $cnt = $cnt-3;
                } else {
                    $this->lfText($shopleft, $shoptop+10+$cnt, "・".$t, $detail_font, 'B');
                }
                if ((strpos($t,"２") !== false) || (strpos($t,"2") !== false)) {
                    $set = $set+2;
                } elseif ((strpos($t,"３") !== false) || (strpos($t,"3") !== false)) {
                    $set = $set+3;
                } elseif ((strpos($t,"４") !== false) || (strpos($t,"4") !== false)) {
                    $set = $set+4;
                } else {
                    $set++;
                }
                $cnt = $cnt+3;
                $t = strtok("\/");
            }
            //付属品(ピンク袋)
            $t = strtok($arrRet[0]['set_content4'], "\/");  // 最初だけ文字列を渡す。/文字で割る
            while ($t !== false) {
                if (strpos($t, "なし") !== false) {
                    //set_conent4が『なし』の場合は表示しない。付属品がないので。
                    $set--;
                } else {
                    $this->lfBlueText($shopleft, $shoptop+10+$cnt, "・付属品（".$t."）", $detail_font, 'B');
                }
                if ((strpos($t,"２") !== false) || (strpos($t,"2") !== false)) {
                    $set = $set+2;
                } elseif ((strpos($t,"３") !== false) || (strpos($t,"3") !== false)) {
                    $set = $set+3;
                } elseif ((strpos($t,"４") !== false) || (strpos($t,"4") !== false)) {
                    $set = $set+4;
                } else {
                    $set++;
                }
                $cnt = $cnt+3;
                $t = strtok("\/");
            }

            if (strpos($arrRet[0]['product_code'],"A") ) {
                $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                    $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                } else {
                    $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                }

            } elseif ((strpos($arrRet[0]['product_code'],"B") !== false) ) {
                if ($set == 1) {
                    $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                    }
                } else {
                    $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                    $this->lfGreenText($shopleft+3, $shoptop+5, $set."点", $title_font, 'B');
                    $this->lfText($shopleft+8, $shoptop+5, 'セット', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピース', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレス', $detail_font, 'B');
                    }
                }

            } elseif ((strpos($arrRet[0]['product_code'],"C") !== false) ) {
                if ($set == 1) {
                    $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                    }
                } else {
                    $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                    $this->lfBlueText($shopleft+3, $shoptop+5, $set."点", $title_font, 'B');
                    $this->lfText($shopleft+8, $shoptop+5, 'セット', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピース', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレス', $detail_font, 'B');
                    }
                }

            } elseif ((strpos($arrRet[0]['product_code'],"D") !== false) ) {
                if ($set == 1) {
                    $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                    }
                } else {
                    $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                    $this->lfRedText($shopleft+3, $shoptop+5, $set."点", $title_font, 'B');
                    $this->lfText($shopleft+8, $shoptop+5, 'セット', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピース', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレス', $detail_font, 'B');
                    }
                }

            } elseif ((strpos($arrRet[0]['product_code'],"E") !== false) ) {
                if ($set == 1) {
                    $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                    }
                } else {
                    $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                    $this->lfPurpleText($shopleft+3, $shoptop+5, $set."点", $title_font, 'B');
                    $this->lfText($shopleft+8, $shoptop+5, 'セット', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピース', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレス', $detail_font, 'B');
                    }
                }

            } elseif ((strpos($arrRet[0]['product_code'],"F") !== false) ) {
                if ($set == 1) {
                    $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                    }
                } else {
                    $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                    $this->lfOrangeText($shopleft+3, $shoptop+5, $set."点", $title_font, 'B');
                    $this->lfText($shopleft+8, $shoptop+5, 'セット', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピース', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレス', $detail_font, 'B');
                    }
                }

            } elseif ((strpos($arrRet[0]['product_code'],"G") !== false) ) {
                $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                $set = $set+1;//トップスとスカートで一つドレスでも、2点セットなので。
                $this->lfDarkYellowText($shopleft+3, $shoptop+5, $set."点", $title_font, 'B');
                $this->lfText($shopleft+8, $shoptop+5, 'セット', $title_font, 'B');
                if ((strpos($arrRet[0]['product_code'],"12-0077") !== false) ||
                    (strpos($arrRet[0]['product_code'],"11-0154") !== false) ||
                    (strpos($arrRet[0]['product_code'],"11-0168") !== false)) {
                    $this->lfText($shopleft, $shoptop+8, '・トップス', $detail_font, 'B');
                    $this->lfText($shopleft, $shoptop+12, '・キャミソールドレス', $detail_font, 'B');
                } else {
                    $this->lfText($shopleft, $shoptop+8, '・トップス', $detail_font, 'B');
                    $this->lfText($shopleft, $shoptop+12, '・スカート', $detail_font, 'B');
                }

            } elseif ((strpos($arrRet[0]['product_code'],"H") !== false) ) {
                if ($set == 1) {
                    $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                    }
                } else {
                    $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                    $this->lfRedText($shopleft+3, $shoptop+5, $set."点", $title_font, 'B');
                    $this->lfText($shopleft+8, $shoptop+5, 'セット', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピース', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレス', $detail_font, 'B');
                    }
                }

            } elseif ((strpos($arrRet[0]['product_code'],"J") !== false) ) {
                if ($set == 1) {
                    $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                    }
                } else {
                    $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                    $this->lfPinkText($shopleft+3, $shoptop+5, $set."点", $title_font, 'B');
                    $this->lfText($shopleft+8, $shoptop+5, 'セット', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        $this->lfText($shopleft, $shoptop+8, '・ワンピース', $detail_font, 'B');
                    } else {
                        $this->lfText($shopleft, $shoptop+8, '・ドレス', $detail_font, 'B');
                    }
                }

            } else {
                //商品コードの末尾にアルファベットがない商品, set以外
                if ($set == 1) {
                    $this->lfText($shopleft, $shoptop+5, '付属品なし', $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false)
                     || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                            if($separate_tops == ''){
                                $this->lfText($shopleft, $shoptop+8, '・ワンピースのみ', $detail_font, 'B');
                            }else{
                                $this->lfText($shopleft, $shoptop+8, '・'.$separate_tops, $detail_font, 'B');
                                $this->lfText($shopleft, $shoptop+11, '・'.$separate_under, $detail_font, 'B');
                            }
                    //kids
                    }elseif(strpos($arrRet[0]['product_code'], '02-') !== false){

                        $this->lfText($shopleft, $shoptop+8, '・'.$separate_tops, $detail_font, 'B');
                        $this->lfText($shopleft, $shoptop+11, '・'.$separate_under, $detail_font, 'B');
                    } else {
                            if($separate_tops == ''){
                                $this->lfText($shopleft, $shoptop+8, '・ドレスのみ', $detail_font, 'B');
                            }else{
                                $this->lfText($shopleft, $shoptop+8, '・'.$separate_tops, $detail_font, 'B');
                                $this->lfText($shopleft, $shoptop+11, '・'.$separate_under, $detail_font, 'B');
                            }
                    }
                 } else {
                    $this->lfText($shopleft, $shoptop+5, '', $title_font, 'B');
                    //$this->lfText($shopleft+3, $shoptop+5, $set."点セット", $title_font, 'B');
                    if ( (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_ALL) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_SUMMER) !== false) || (strpos($arrRet[0]['product_code'],PCODE_ONEPIECE_WINTER) !== false) ) {
                        
                            if($separate_tops == ''){
                                $this->lfText($shopleft, $shoptop+8, '・ワンピース', $detail_font, 'B');
                            }else{
                                $set++;
                                $this->lfText($shopleft, $shoptop+8, '・'.$separate_tops, $detail_font, 'B');
                                $this->lfText($shopleft, $shoptop+11, '・'.$separate_under, $detail_font, 'B');
                            }
                    //kids
                    }elseif(strpos($arrRet[0]['product_code'], '02-') !== false){
                        $set++;
                        $this->lfText($shopleft, $shoptop+8, '・'.$separate_tops, $detail_font, 'B');
                        $this->lfText($shopleft, $shoptop+11, '・'.$separate_under, $detail_font, 'B');
                    } else {
                            if($separate_tops == ''){
                                $this->lfText($shopleft, $shoptop+8, '・ドレス', $detail_font, 'B');
                            }else{
                                $set++;
                                $this->lfText($shopleft, $shoptop+8, '・'.$separate_tops, $detail_font, 'B');
                                $this->lfText($shopleft, $shoptop+11, '・'.$separate_under, $detail_font, 'B');
                            }
                    }
                    $this->lfText($shopleft+3, $shoptop+5, $set."点セット", $title_font, 'B');
                }
            }
        }

        // 2015.09.22 ピッキングリストにボレロを表示する対応 start
        function setBreloAndStoleData($arrData, $i,$x,$y, $delev_pref)
        {
            $this->arrData = $arrData;
            $shopleft = $this->left_magin+($y);
            $shoptop = $this->top_magin+($x*22);
            $order_font = 10;
            $code_header_font = 8;
            $code_font = 10;
            $detail_font = 6;
            if ($i == 0) {
                // ページを追加（新規）
                $this->pdf->AddPage();
                $this->lfText(100, 3, "ストール、ボレロ", $detail_font, 'B'); //分類
            }

            //表示倍率(100%)
            $this->pdf->SetDisplayMode($this->tpl_dispmode);

            //注文番号
            if ($this->arrInfive[$this->arrData['order_id']] == 2) {
                //green
                $this->lfGreenText($shopleft+2, $shoptop+6,"注文番号 : ".$this->arrData['order_id'], $order_font, 'B'); //注文番号
            } else if ($this->arrInfive[$this->arrData['order_id']] == 1) {
                //red
                $this->lfRedText($shopleft+2, $shoptop+6,"注文番号 : ".$this->arrData['order_id'], $order_font, 'B'); //注文番号
            } else if ($this->arrInfive[$this->arrData['order_id']] === 0) { // 配列がnullの場合は0と等価なので===で型チェックも行う
                //blue
                $this->lfBlueText($shopleft+2, $shoptop+6,"注文番号 : ".$this->arrData['order_id'], $order_font, 'B'); //注文番号
            } else {
                //black
                $this->lfText($shopleft+2, $shoptop+6,"注文番号 : ".$this->arrData['order_id'], $order_font, 'B'); //注文番号
            }

            $product_code = $this->arrData['product_code'];
            if (!SC_Helper_Delivery_Ex::sfIsNomalArea($delev_pref[0])){
                $product_code = "★".$product_code;
                $shopleft -= 2;
            }
            if ($delev_pref[1] == '500'){
              $product_code = "♪".$product_code;
            }

           $this->lfText($shopleft+14, $shoptop+16, $product_code, $code_font,'B'); //商品コード

           //付属品対応
            $objQuery = new SC_Query();
            $stole_huzoku_sql = 'SELECT set_content from dtb_products_ext where product_id = ?';
            $res = $objQuery->getall($stole_huzoku_sql, $arrData['product_id']);
            $attached = explode("/", $res[0]['set_content']);
            $attached_cut = count($attached);
            $top_add = 10;

            if(strpos($this->arrData['product_code'], 'Y') !== false || strpos($this->arrData['product_code'], 'K') !== false || 
                strpos($this->arrData['product_code'], 'k') !== false){
                if($attached_cut > 1){
                    for($i=0; $i<$attached_cut; $i++){
                        $this->lfText($shopleft+2, $shoptop+$top_add, '・'. $attached[$i], $detail_font,'B'); //付属品
                        $top_add += 3;
                    }
                }else{
                    $this->lfText($shopleft+2, $shoptop+10, '・'. $res[0]['set_content'], $detail_font,'B'); //付属品
                }
            }
        }

        // 2015.09.22 ピッキングリストにボレロを表示する対応 end

        function createPdf()
        {
            // PDFをブラウザに送信
            ob_clean();
            if ($this->pdf->PageNo() == 1) {
                $filename = "fuzokuhinn-No" . $this->arrData['order_id'] . ".pdf";
            } else {
                $filename = "fuzokuhinn.pdf";
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
        // PDF_Japanese::Colored Text へのパーサー
        function lfBlueText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);
            $this->pdf->BlueText($x, $y, $text);
        }
        // PDF_Japanese::Colored Text へのパーサー
        function lfGreenText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);
            $this->pdf->GreenText($x, $y, $text);
        }
        // PDF_Japanese::Colored Text へのパーサー
        function lfPurpleText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);
            $this->pdf->PurpleText($x, $y, $text);
        }
        // PDF_Japanese::Colored Text へのパーサー
        function lfOrangeText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);
            $this->pdf->OrangeText($x, $y, $text);
        }
        // PDF_Japanese::Colored Text へのパーサー
        function lfDarkYellowText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);
            $this->pdf->DarkYellowText($x, $y, $text);
        }
        // PDF_Japanese::Colored Text へのパーサー
        function lfPinkText($x, $y, $text, $size, $style = '')
        {
            $text = mb_convert_encoding($text, "SJIS", CHAR_CODE);

            $this->pdf->SetFont('SJIS', $style, $size);
            $this->pdf->PinkText($x, $y, $text);
        }

        // 文字コードSJIS変換 -> japanese.phpで使用出来る文字コードはSJISのみ
        function sjis_conv($conv_str)
        {
            return (mb_convert_encoding($conv_str, "SJIS", CHAR_CODE));
        }

    }

?>