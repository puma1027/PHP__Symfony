<!--{*
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
 * ####################################################
 * バージョン　変更日　		変更者　変更内容
 * 1.0.1	  2013/09/09	T.A		セットドレス追加
 * ####################################################
*}-->
<!--★★メインコンテンツ★★-->
<div id="products" class="contents-main">
    <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" enctype="multipart/form-data">
    <!--{foreach key=key item=item from=$arrForm}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
    <!--{/foreach}-->
                        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" /> 
        <table>
            <tr>
                <th>登場日</th>
                <td>
                <!--{$arrRELEASEDAY[$arrForm.releaseday_id]|escape}-->
                </td>
            </tr>
            <!--{if $tpl_nonclass == true}-->
            <tr>
                <th>商品コード</th>
                <td>
                <!--{$arrForm.product_code|escape}-->
                </td>
            </tr>
            <tr>
                <th>在庫数</th>
                <td>
                <!--{if $arrForm.stock_unlimited == 1}-->
                無制限
                <!--{else}-->
                <!--{$arrForm.stock|escape}-->
                個<!--{/if}-->
                </td>
            </tr>
            <tr>
                <th><!--{$smarty.const.NORMAL_PRICE_TITLE}--></th>
                <td>
                <!--{$arrForm.price01|escape}-->
                円</td>
            </tr>
            <tr>
                <th><!--{$smarty.const.SALE_PRICE_TITLE}--></th>
                <td>
                <!--{$arrForm.price02|escape}-->
                円</td>
            </tr>
            <!--{/if}-->
            <tr>
                <th>公開・非公開</th>
                <td>
                <!--{$arrDISP[$arrForm.status]}-->
                </td>
            </tr>
            <tr>
                <th>アイコン</th>
                <td>
                <!--{section name=cnt loop=$arrForm.icon_flag|count_characters}-->
                    <!--{if $arrForm.icon_flag[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><!--{$arrICON[$key]}-->&nbsp;&nbsp;<!--{/if}-->
                <!--{/section}-->
                </td>
            </tr>
            <tr>
                <th>ポイント付与率</th>
                <td>
                <!--{$arrForm.point_rate|escape}-->
                ％</td>
            </tr>
            <tr>
                <th>購入制限</th>
                <td>
                <!--{if $arrForm.sale_unlimited == 1}-->
                無制限
                <!--{else}-->
                <!--{$arrForm.sale_limit|escape}-->
                個<!--{/if}-->
                </td>
            </tr>
            <tr>
                <th>検索ワード</th>
                <td>
                <!--{$arrForm.comment3|escape}-->
                </td>
            </tr>
            <tr>
                <th>備考欄(SHOP専用)</th>
                <td>
                  <!--{$arrForm.note|escape}-->
                </td>
            </tr>
            <!-- 2012.04.28 Y.C. Add -->
            <tr>
                <th>バッグ仮番号</th>
                <td>
                    <span class="red12"><!--{$arrErr.bag_temp_id}--></span>
                    <!--{$arrBag[$arrForm.bag_temp_id]|escape}-->
                </td>
            </tr>
<!-- 2012.04.13 RCHJ Add -->
			<tr>
                <th>検品画像タイプ</th>
                <td>
					 ドレス：<!--{$arrImageDress[$arrForm.image_dress]|escape}-->&nbsp;
					 羽織物：<!--{$arrImageStole[$arrForm.image_stole]|escape}-->&nbsp;
					 ネックレス：<!--{$arrImageNecklace[$arrForm.image_necklace]|escape}-->&nbsp;
					 バッグ：<!--{$arrImageBag[$arrForm.image_bag]|escape}-->
                </td>
            </tr>
<!-- end --> 
            <tr>
                <th>ブランド</th>
                <td>
                <!--{$arrBRAND[$arrForm.brand_id]|escape}-->
           		&nbsp;&nbsp;羽織物ブランド:<!--{$arrBRAND[$arrForm.haori_brand_id]|escape}-->
           		&nbsp;&nbsp;ネックレスブランド:<!--{$arrBRAND[$arrForm.necklace_brand_id]|escape}-->
           		&nbsp;&nbsp;バッグブランド:<!--{$arrBRAND[$arrForm.bag_brand_id]|escape}-->
                </td>
            </tr>
            <tr>
                <th>商品名</th>
                <td>
                <!--{$arrForm.name|escape}-->
                </td>
            </tr>
            <tr>
                <th>商品カテゴリ</th>
                <td>
                <!--{section name=cnt loop=$arrCategory_id}-->
                    <!--{assign var=key value=$arrCategory_id[cnt]}-->
                    <!--{$arrCatList[$key]|strip|sfTrim}--><br>
                <!--{/section}-->
                </td>
            </tr>
            <tr>
                <th>素材</th>
                <td>
                <!--{$arrForm.item_materrial|escape|sfPutBR:$smarty.const.LINE_LIMIT_SIZE}-->
                </td>
            </tr>
            <tr>
                <th>生地の厚さ</th>
                <td>
                <!--{$arrTHICKNESSTYPE[$arrForm.thickness_type]|escape}-->
                </td>
            </tr>
            <tr>
                <th>裏地</th>
                <td>
                <!--{$arrLINERTYPE[$arrForm.liner_type]|escape}-->
                </td>
            </tr>
            <tr>
                <th>ファスナー</th>
                <td>
                <!--{$arrFASTENERTYPE[$arrForm.fastener_type]|escape}-->
                </td>
            </tr>
            <tr>
                <th>その他</th>
                <td>
                <!--{$arrForm.other_data|escape}-->
                </td>
            </tr>
            <tr>
                <th>付属品</th>
                <td>
                    ドレス:<!--{$arrForm.set_content|escape}-->
                    <br/>羽織物:<!--{$arrForm.set_content1|escape}-->
                    <br/>ネックレス:<!--{$arrForm.set_content2|escape}-->
                    <br/>バッグ :<!--{$arrForm.set_content3|escape}-->
                    <br/>ピンク袋 :<!--{$arrForm.set_content4|escape}--><!--//::N00062 Add 20130531-->
                </td>
            </tr>
            <tr>
                <th>商品ステータス</th>
                <td>
                <!--{section name=cnt loop=$arrForm.product_flag|count_characters}-->
                    <!--{if $arrForm.product_flag[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><img src="<!--{$TPL_DIR}--><!--{$arrSTATUS_IMAGE[$key]}-->"><!--{/if}-->
                <!--{/section}-->
                </td>
            </tr>
<!-- 2013.01.21 RCHJ Add & Change -->
            <tr>
                <th>注意事項</th>
                <td>
                    <!--{html_checkboxes name="important_points_ids" options=$arrImportanPoint separator="<br/>" selected=$arrForm.important_points_ids}-->
                </td>
            </tr>
<!-- End -->              
            <tr>
                <th>ドレスカラー</th><!--//::N00031 Change 20130402-->
                <td>
                <!--{$arrForm.main_list_comment|escape|nl2br}-->
                </td>
            </tr>
<!--
            <tr>
                <th>おすすめコメント</th>
                <td>
                <!--{$arrForm.main_comment|nl2br}-->
                </td>
            </tr>
            <tr>
                <th>コーデのワンポイント</th>
                <td>
                <!--{$arrForm.main_comment_point|nl2br}-->
                </td>
            </tr>
-->
            <tr>
                <th>シルエット</th>
                <td>
                    <!--{$separate}-->
                </td>
            </tr>
            <tr>
                <th>機能</th>
                <td>
                <!--{section name=cnt loop=$arrForm.funct_flag|count_characters}-->
                    <!--{if $arrForm.funct_flag[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><!--{$arrFOUCTION[$key]}-->&nbsp;&nbsp;<!--{/if}-->
                <!--{/section}-->
                </td>
            </tr>
		</table>
        <table>
            <p>＊( )は子供のサイズ</p>
            <tr>
                <th>バスト<br>( ー )</th>
                <th>アンダー<br>(身幅)</th><!--//::B00020 Change 20130326-->
                <th>ウエスト<br>(パンツウエストBoy)</th><!--//::B00020 Change 20130326-->
                <th>ヒップ<br>(パンツのヒップBoy)</th><!--//::B00020 Change 20130326-->
                <th>着丈</th><!--//::B00020 Change 20130326-->
                <th>肩幅</th><!--//::B00020 Change 20130326-->
                <th>ゆき丈<br>( ー )</th><!--//201807 add-->
                <th>アームホール</th><!--//::B00020 Change 20130326-->
                <th>二の腕周り<br>(パンツ丈boy)</th><!--{*add 201807*}-->
                <th>袖の長さ</th><!--//::B00020 Change 20130326-->
                <th>袖口<br>(もも周りboy)</th><!--//::B00020 Change 20130326-->
                <th>股下</th><!--//::B00020 Change 20190717-->
           <tr>
                <td><!--{if $arrForm.bust_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.bust|nl2br}--><!--{if $arrForm.bust_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                <td><!--{if $arrForm.under_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.under_text|nl2br}--><!--{if $arrForm.under_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                <td><!--{if $arrForm.waist_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.waist|nl2br}--><!--{if $arrForm.waist_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                <td><!--{if $arrForm.hip_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.hip|nl2br}--><!--{if $arrForm.hip_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                <td><!--{if $arrForm.garment_length_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.garment_length|nl2br}--><!--{if $arrForm.garment_length_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                <td><!--{if $arrForm.shoulders_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.shoulders|nl2br}--><!--{if $arrForm.shoulders_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                <td><!--{if $arrForm.bow_length_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.bow_length|nl2br}--><!--{if $arrForm.bow_length_flg == "1"}--></span><!--{/if}--></td><!--//add 201807-->
                <td><!--{if $arrForm.arm_hole_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.arm_hole|nl2br}--><!--{if $arrForm.arm_hole_flg == "1"}--></span><!--{/if}--></td><!--//add 201807-->
                <td><!--{if $arrForm.ninoude_mawari_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.ninoude_mawari|nl2br}--><!--{if $arrForm.ninoude_mawari_flg == "1"}--></span><!--{/if}--></td><!--{*add 201807*}-->
                <td><!--{if $arrForm.sleeve_length_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.sleeve_length|nl2br}--><!--{if $arrForm.sleeve_length_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                <td><!--{if $arrForm.cuff_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.cuff|nl2br}--><!--{if $arrForm.cuff_flg == "1"}--></span><!--{/if}--></td>
                <td><!--{if $arrForm.inseam_flg == "1"}--><span class="attention"><!--{/if}--><!--{$arrForm.inseam|nl2br}--><!--{if $arrForm.inseam_flg == "1"}--></span><!--{/if}--></td>
             </tr>
        </table>
<!--
        <!--{*▼ボレロ実寸▼*}-->
        <table>
            <tr>
                <th>サイズ</th>
                <th>ボレロ身丈</th>
                <th>ボレロ肩幅</th>
                <th>ボレロ身幅</th>
                <th>ボレロ袖の長さ</th>
                <th>ストール幅</th>
                <th>ストール長さ</th>
            </tr>
            <tr>
                <td><!--{if $arrForm.bolero_bodysize_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.bolero_bodysize|nl2br}--><!--{if $arrForm.bolero_bodysize_flg == "1"}--></span><!--{/if}--></td>
                <td><!--{if $arrForm.bolero_shoulders_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.bolero_shoulders|nl2br}--><!--{if $arrForm.bolero_shoulders_flg == "1"}--></span><!--{/if}--></td>
                <td><!--{if $arrForm.bolero_bust_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.bolero_bust|nl2br}--><!--{if $arrForm.bolero_bust_flg == "1"}--></span><!--{/if}--></td>
                <td><!--{if $arrForm.bolero_waist_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.bolero_waist|nl2br}--><!--{if $arrForm.bolero_waist_flg == "1"}--></span><!--{/if}--></td>
                <td><!--{if $arrForm.bolero_garment_length_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.bolero_garment_length|nl2br}--><!--{if $arrForm.bolero_garment_length_flg == "1"}--></span><!--{/if}--></td>
                <td><!--{if $arrForm.bolero_shoulders_length_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.bolero_shoulders_length|nl2br}--><!--{if $arrForm.bolero_shoulders_length_flg == "1"}--></span><!--{/if}--></td>
                <td><!--{if $arrForm.bolero_sleeve_length_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.bolero_sleeve_length|nl2br}--><!--{if $arrForm.bolero_sleeve_length_flg == "1"}--></span><!--{/if}--></td>
            </tr>
        </table>
        <!--{*▲ボレロ実寸サイズ▲*}-->
-->
            <table>
                <tr>
                    <th rowspan="4">着用コメント１</th>
                    <th colspan="2">モデル</th>
                    <th colspan="2">全体</th><!--//::B00020 Change 20130326-->
                </tr>
                <tr>
                    <th colspan="2">
                    <!--{$arrMODEL[$arrForm.wear_comment_model1]|escape}-->
                    </th>
                    <th colspan="2">
                    <!--{$arrWEARRANK[$arrForm.wear_comment_wearrank1]|escape}-->
                    </th>
                </tr>
                <tr>
                    <th colspan="4">コメント</th>
                </tr>
                <tr>
                    <th colspan="4">
                    <!--{$arrForm.wear_comment1|escape}-->
                    </th>
                </tr>
                    
                <tr>
                    <th rowspan="4">着用コメント２</th>
                    <th colspan="2">モデル</th>
                    <th colspan="2">全体</th><!--//::B00020 Change 20130326-->
                </tr>
                <tr>
                    <th colspan="2">
                    <!--{$arrMODEL[$arrForm.wear_comment_model2]|escape}-->
                    </th>
                    <th colspan="2">
                    <!--{$arrWEARRANK[$arrForm.wear_comment_wearrank2]|escape}-->
                    </th>
                </tr>
                <tr>
                    <th colspan="4">コメント</th>
                </tr>
                <tr>
                    <th colspan="4">
                    <!--{$arrForm.wear_comment2|escape}-->
                    </th>
                </tr>
        </table>

        <table>
<!-- 2013.01.21 RCHJ Add -->
            <tr>
                <th>モデル身長</th>
                <td>
                <!--{$arrForm.model_body_length|escape|sfPutBR:$smarty.const.LINE_LIMIT_SIZE}--> cm
                </td>
            </tr>
<!-- End -->
            <tr>
                <th>対応サイズ：大人</th>
                <td>
                  <!--{html_checkboxes name="figure_detail" options=$arrFIGUREDETAIL separator="&nbsp;" selected=$arrForm.figure_detail}-->
                </td>
            </tr>
            <tr>
                <th>対応サイズ：子供</th>
                <td>
                  <!--{html_checkboxes name="figure_detail_kids" options=$arrFigureDetailKids separator="&nbsp;" selected=$arrForm.figure_detail_kids}-->
                </td>
            </tr>
<!--//::N00072 Add 20130807-->
<!--{*            <tr>
                <th>対応バストカップ</th>
                <td>
                  <!--{html_checkboxes name="bustcup" options=$arrBUSTCUP separator="&nbsp;" selected=$arrForm.bustcup}-->
                </td>
            </tr>*}-->
            <tr>
                <th rowspan="5">サイズ補足</th>
                <td><p>※タグ表記は<!--{$arrForm.tag|escape}-->。</p></td>
            </tr>
            <tr>
                <td>
                  <p>※<!--{$arrBUST_UNDER_WAIST[$arrForm.size_supplement2]|escape}-->が「〜」となっているのは、ゴムが入っているから。</p>
                </td>
            </tr>
            <tr>
                <td><p>※<!--{$arrBUST_UNDER_WAIST[$arrForm.size_supplement3]|escape}-->が「〜」となっているのは、背中に調節ひもがついているから。</br>フィットしやすいのでおすすめ。</p></td>
            </tr>
            <tr>
                <td><p>※着丈は、裾の透けている部分(<!--{$arrForm.size_supplement4|escape}-->cm)を含む。</p></td>
            </tr>
            <tr>
                <td><p>※<!--{$arrForm.important_points|nl2br}--></p></td>
            </tr>

            <tr>
                <th>一覧-メイン画像</th>
                <td>
                <!--{assign var=key value="main_list_image"}-->
                <!--{if $arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" /><br />
                <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th>検品用-メイン画像</th>
                <td>
                <!--{assign var=key value="main_image"}-->
                <!--{if $arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" /><br />
                <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th>詳細-メイン拡大画像</th>
                <td>
                <!--{assign var=key value="main_large_image"}-->
                <!--{if $arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" /><br />
                <!--{/if}-->
                </td>
            </tr> 
            <!--{if $arrForm.has_ext_data == "1"}--> 

        </table>
        <table>

            <!--{/if}-->
			<!--{section name=cnt loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3}-->
			<!--{assign var="key" value="photo_gallery_image`$smarty.section.cnt.iteration`"}-->
			<!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration`"}-->
			<tr>
				<th>フォトギャラリー画像<!--{$smarty.section.cnt.iteration}--></th>
				<td>
				<!--{if $arrFile[$key].filepath != ""}-->
					<img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" /><br />
				<!--{/if}-->
				</td>
			</tr>
			<tr>
				<th>フォトギャラリーコメント<!--{$smarty.section.cnt.iteration}--></th>
				<td>
					<!--{$arrForm[$key2]|escape}-->
				</td>
			</tr>
			<!--{/section}-->
            <!--{* オペビルダー用 *}-->
            <!--{if "sfViewAdminOpe"|function_exists === TRUE}-->
            <!--{include file="`$smarty.const.MODULE_PATH`mdl_opebuilder/admin_ope_view.tpl"}-->
            <!--{/if}-->

<!-- RCHJ Add & Remark 2013.02.14 -->
			<tr>
                <th colspan="2" style="text-align:center;">サブ情報</th>
            </tr>
            <!--{assign var="start_index" value="`$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3`"}-->
            <!--{section name=cnt start=$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3 loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM}-->
			<!--{assign var="key" value="photo_gallery_image`$smarty.section.cnt.iteration+$start_index`"}-->
            <!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration+$start_index`"}-->
			<tr>
				<th>詳細-サブ画像<!--{$smarty.section.cnt.iteration}--></th>
				<td>
				<!--{if $arrFile[$key].filepath != ""}-->
					<img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" /><br />
				<!--{/if}-->
				</td>
			</tr>
			<tr>
				<th>詳細-サブコメント<!--{$smarty.section.cnt.iteration}--></th>
				<td>
					<!--{$arrForm[$key2]|escape}-->
				</td>
			</tr>
			<!--{/section}-->
<!-- End -->

            <!--{if $smarty.const.OPTION_RECOMMEND == 1}-->
<!-- 2013.01.22 RCHJ Add -->
            <!--▼コーディネートで使用している商品-->
            <tr>
                <th colspan="2" style="text-align:center;">コーディネートで使用している商品</th>
            </tr>
            <!--{section name=cnt loop=$smarty.const.COORDINATE_RECOMMEND_PRODUCT_MAX}-->
            <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
            <tr>
                <th>商品(<!--{$smarty.section.cnt.iteration}-->)<br>
                <!--{if $arrCoordinateRecommend[$recommend_no].main_list_image != ""}-->
                    <!--{assign var=image_path value="`$arrCoordinateRecommend[$recommend_no].main_list_image`"}-->
                <!--{else}-->
                    <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                <!--{/if}-->
                <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65" alt="<!--{$arrCoordinateRecommend[$recommend_no].name|escape}-->" />
                </th>
                <td>
                <!--{if $arrCoordinateRecommend[$recommend_no].name != ""}-->
                商品コード:<!--{$arrCoordinateRecommend[$recommend_no].product_code_min}--><br>
                商品名:<!--{$arrCoordinateRecommend[$recommend_no].name|escape}--><br>
                コメント:<br>
                <!--{$arrCoordinateRecommend[$recommend_no].comment|escape}-->
                <!--{/if}-->
                </td>
            </tr>
            <!--{/section}-->
            <!--▲コーディネートで使用している商品-->
            <!--▼サイズ・色違いの商品-->
            <tr>
                <th colspan="2" style="text-align:center;">サイズ・色違いの商品</th>
            </tr>
            <!--{section name=cnt loop=$smarty.const.SIZE_COLOR_RECOMMEND_PRODUCT_MAX}-->
            <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
            <tr>
                <th>商品(<!--{$smarty.section.cnt.iteration}-->)<br>
                <!--{if $arrSizeColorRecommend[$recommend_no].main_list_image != ""}-->
                    <!--{assign var=image_path value="`$arrSizeColorRecommend[$recommend_no].main_list_image`"}-->
                <!--{else}-->
                    <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                <!--{/if}-->
                <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65" alt="<!--{$arrSizeColorRecommend[$recommend_no].name|escape}-->" />
                </th>
                <td>
                <!--{if $arrSizeColorRecommend[$recommend_no].name != ""}-->
                商品コード:<!--{$arrSizeColorRecommend[$recommend_no].product_code_min}--><br>
                商品名:<!--{$arrSizeColorRecommend[$recommend_no].name|escape}--><br>
                コメント:<br>
                <!--{$arrSizeColorRecommend[$recommend_no].comment|escape}-->
                <!--{/if}-->
                </td>
            </tr>
            <!--{/section}-->
            <!--▲サイズ・色違いの商品-->
<!-- end -->
<!--//::N00072 Add 20130902-->
            <tr>
                <th rowspan="2">こんな方におすすめ</th>
                <td>
                <!--{$arrEMPLOYEE[$arrForm.recommended_staff_id]|escape}-->
                </td>
            </tr>
            <tr>
                <td>
                  <!--{$arrForm.recommended_staff_comment|escape}-->
                </td>
            </tr>
            <tr>
                <th rowspan="2">コーデのポイント</th>
                <td>
                <!--{$arrEMPLOYEE[$arrForm.coord_point_staff_id]|escape}-->
                </td>
            </tr>
            <tr>
                <td>
                  <!--{$arrForm.coord_point_staff_comment|escape}-->
                </td>
            </tr>
            <tr>
                <th rowspan="2">オトコ目線レビュー</th>
                <td>
                <!--{$arrEMPLOYEE[$arrForm.mens_review_staff_id]|escape}-->
                </td>
            </tr>
            <tr>
                <td>
                  <!--{$arrForm.mens_review_staff_comment|escape}-->
                </td>
            </tr>
            <tr>
                <th>年代にあったドレス</th>
                <td>
                  <!--{section name=cnt loop=$arrForm.mpsc_age|count_characters}-->
                  <!--{if $arrForm.mpsc_age[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><!--{$arrMPSC_AGE[$key]}-->&nbsp;&nbsp;<!--{/if}-->
                  <!--{/section}-->
                </td>
            </tr>
            <tr>
                <th>シーンにあったドレス</th>
                <td>
                  <!--{section name=cnt loop=$arrForm.mpsc_event|count_characters}-->
                  <!--{if $arrForm.mpsc_event[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><!--{$arrMPSC_EVENT[$key]}-->&nbsp;&nbsp;<!--{/if}-->
                  <!--{/section}-->
                </td>
            </tr>
            <tr>
                <th>サイズで失敗しない</th>
                <td>
                  <!--{section name=cnt loop=$arrForm.mpsc_size|count_characters}-->
                  <!--{if $arrForm.mpsc_size[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><!--{$arrMPSC_SIZE[$key]}-->&nbsp;&nbsp;<!--{/if}-->
                  <!--{/section}-->
                </td>
            </tr>
            <tr>
                <th>体型の悩みを解決</th>
                <td>
                  <!--{section name=cnt loop=$arrForm.mpsc_complex|count_characters}-->
                  <!--{if $arrForm.mpsc_complex[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><!--{$arrMPSC_COMPLEX[$key]}-->&nbsp;&nbsp;<!--{/if}-->
                  <!--{/section}-->
                </td>
            </tr>
            <tr>
                <th>お子様連れの悩みを解決</th>
                <td>
                  <!--{section name=cnt loop=$arrForm.mpsc_worry|count_characters}-->
                  <!--{if $arrForm.mpsc_worry[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><!--{$arrMPSC_WORRY[$key]}-->&nbsp;&nbsp;<!--{/if}-->
                  <!--{/section}-->
                </td>
            </tr>

            <!--{/if}-->
        </table>
        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.setModeAndSubmit('confirm_return','',''); return false;"><span class="btn-prev">前のページに戻る</span></a></li>
                <li><a class="btn-action" href="javascript:;" onclick="document.form1.submit(); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
</form>
</div>
