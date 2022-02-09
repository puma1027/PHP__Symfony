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
*}-->

<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="complete" />
<!--{foreach key=key item=item from=$arrSearchHidden}-->
    <!--{if is_array($item)}-->
        <!--{foreach item=c_item from=$item}-->
            <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
        <!--{/foreach}-->
    <!--{else}-->
        <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
    <!--{/if}-->
<!--{/foreach}-->
<!--{foreach key=key item=item from=$arrForm}-->
    <!--{if $key == 'product_status'}-->
        <!--{foreach item=statusVal from=$item}-->
            <input type="hidden" name="<!--{$key}-->[]" value="<!--{$statusVal|h}-->" />
        <!--{/foreach}-->
    <!--{elseif $key == 'arrCategoryId'}-->
        <!--{* nop *}-->
    <!--{elseif $key == 'arrFile'}-->
        <!--{* nop *}-->
    <!--{else}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->" />
    <!--{/if}-->
<!--{/foreach}-->
<div id="products" class="contents-main">

    <table>
        <tr>
            <th>商品名</th>
            <td>
                <!--{$arrForm.name|h}-->
            </td>
        </tr>
        <tr>
            <th>商品カテゴリ</th>
            <td>
                <!--{section name=cnt loop=$arrForm.arrCategoryId}-->
                    <!--{assign var=key value=$arrForm.arrCategoryId[cnt]}-->
                    <!--{$arrCatList[$key]|sfTrim}--><br />
                <!--{/section}-->
            </td>
        </tr>
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
            <th>商品ステータス</th>     
            <td>
            <!--{*
                <!--{foreach from=$arrForm.product_flag item=flag}-->  
                    <!--{if $flag != ""}-->
                        <img src="<!--{$TPL_URLPATH_PC}--><!--{$arrSTATUS_IMAGE[$flag]}-->">
                    <!--{/if}-->
                <!--{/foreach}-->
            *}-->
            <!--{section name=cnt loop=$arrForm.product_flag|count_characters}-->
                <!--{if $arrForm.product_flag[cnt] == "1"}--><!--{assign var=key value="`$smarty.section.cnt.iteration`"}--><img src="<!--{$TPL_URLPATH_PC}--><!--{$arrSTATUS_IMAGE[$key]}-->"><!--{/if}-->
            <!--{/section}-->

<!--{*
todo 確認 nakagawa 20200410
            <!--{foreach from=$arrForm.product_status item=status}-->
                <!--{if $status != ""}-->
                    <img src="<!--{$TPL_URLPATH_PC}--><!--{$arrSTATUS_IMAGE[$status]}-->">
                <!--{/if}-->
            <!--{/foreach}-->
*}-->
            </td>
        </tr>

        <!--{if $arrForm.has_product_class != true}-->
            <tr>
                <th>商品種別</th>
                <td>
                    <!--{$arrProductType[$arrForm.product_type_id]}-->
                </td>
            </tr>
            <!--{if $arrForm.product_type_id == PRODUCT_TYPE_DOWNLOAD}-->
            <tr>
                <th>ダウンロード商品ファイル名</th>
                <td>
                    <!--{$arrForm.down_filename|h}-->
                </td>
            </tr>
            <tr>
                <th>ダウンロード商品用<br />ファイル</th>
                <td>
                    <!--{if $arrForm.down_realfilename != ""}-->
                        <!--{$arrForm.down_realfilename|h}-->
                    <!--{/if}-->
                </td>
            </tr>
            <!--{/if}-->
            <tr>
                <th>商品コード</th>
                <td>
                    <!--{$arrForm.product_code|h}-->
                </td>
            </tr>
            <tr>
                <th><!--{$smarty.const.NORMAL_PRICE_TITLE}--></th>
                <td>
                    <!--{if strlen($arrForm.price01) >= 1}--><!--{$arrForm.price01|h}--> 円<!--{/if}-->
                </td>
            </tr>
            <tr>
                <th><!--{$smarty.const.SALE_PRICE_TITLE}--></th>
                <td>
                    <!--{if strlen($arrForm.price02) >= 1}--><!--{$arrForm.price02|h}--> 円<!--{/if}-->
                </td>
            </tr>
            <tr>
                <th>在庫数</th>
                <td>
                    <!--{if $arrForm.stock_unlimited == 1}-->
                        無制限
                    <!--{else}-->
                        <!--{$arrForm.stock|h}-->
                    <!--{/if}-->
                </td>
            </tr>
        <!--{/if}-->
        <!--{if $smarty.const.OPTION_PRODUCT_TAX_RULE ==1}-->
            <tr>
                <th>消費税率</th>
                <td>
                    <!--{if strlen($arrForm.tax_rate) >= 1}--><!--{$arrForm.tax_rate|h}--> %<!--{/if}-->
                </td>
            </tr>
        <!--{/if}-->

        <tr>
            <th>商品送料</th>
            <td>
                <!--{if strlen($arrForm.deliv_fee) >= 1}--><!--{$arrForm.deliv_fee|h}--> 円<!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>ポイント付与率</th>
            <td>
                <!--{if strlen($arrForm.point_rate) >= 1}--><!--{$arrForm.point_rate|h}--> ％<!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>発送日目安</th>
            <td>
                <!--{$arrDELIVERYDATE[$arrForm.deliv_date_id]|h}-->
            </td>
        </tr>
        <tr>
            <th>販売制限数</th>
            <td>
                <!--{$arrForm.sale_limit|default:'無制限'|h}-->
            </td>
        </tr>
        <tr>
            <th>メーカー</th>
            <td>
                <!--{$arrMaker[$arrForm.maker_id]|h}-->
            </td>
        </tr>
        <tr>
            <th>メーカーURL</th>
            <td style="word-break: break-all;">
                <!--{$arrForm.comment1|h}-->
            </td>
        </tr>
        <tr>
            <th>検索ワード</th>
            <td>
                <!--{$arrForm.comment3|h}-->
            </td>
        </tr>
        <tr>
            <th>備考欄(SHOP専用)</th>
            <td>
                <!--{$arrForm.note|h|nl2br}-->
            </td>
        </tr>
        <tr>
            <th>>ドレスカラー</th> 	<!-- REMARK KGS_20140307 -->
            <td>
                <!--{$arrForm.main_list_comment|h|nl2br}-->
            </td>
        </tr>
        <tr>
            <th>詳細-メインコメント</th>
            <td>
                <!--{$arrForm.main_comment|nl2br_html}-->
            </td>
        </tr>
        <tr>
            <th>一覧-メイン画像</th>
            <td>
                <!--{assign var=key value="main_list_image"}-->
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                    <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" /><br />
                <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>検品用-メイン画像</th> 	<!-- REMARK KGS_20140307 -->
            <td>
                <!--{assign var=key value="main_image"}-->
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                    <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" /><br />
                <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>詳細-メイン拡大画像</th>
            <td>
                <!--{assign var=key value="main_large_image"}-->
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                    <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" /><br />
                <!--{/if}-->
            </td>
        </tr>
        	<!-- {{ADD KGS_20140307 -->
			       <tr>
                                    <th>拡張データを使用する</th>
                                    <td><!--{if $arrForm.has_ext_data == "1"}-->あり<!--{else}-->なし<!--{/if}--></td>
                                </tr>
                                <!--{if $arrForm.has_ext_data == "1"}--> 
                                <tr>
                                    <th>体型詳細</th>
                                    <td bgcolor="#ffffff" width="557"><!--{html_checkboxes name="figure_detail" options=$arrFIGUREDETAIL separator="&nbsp;" selected=$arrForm.figure_detail}--></td>
                                </tr>
                            </table>
                            <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
                                <tr>
                                    <td width="94">バスト</td>
                                    <td width="94">アンダー</td><!--//::B00020 Change 20130326-->
                                    <td width="94">ウエスト</td><!--//::B00020 Change 20130326-->
                                    <td width="94">ヒップ</td><!--//::B00020 Change 20130326-->
                                    <td width="94">着丈</td><!--//::B00020 Change 20130326-->
                                    <td width="94">肩幅</td><!--//::B00020 Change 20130326-->
                                    <td width="94">肩まわり</td><!--//::B00020 Change 20130326-->
                                    <td width="94">袖の長さ</td><!--//::B00020 Change 20130326-->
                                    <td width="94">袖口</td><!--//::B00020 Change 20130326-->
                               <tr>
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.bust_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.bust|nl2br}--><!--{if $arrForm.bust_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.under_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.under_text|nl2br}--><!--{if $arrForm.under_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.waist_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.waist|nl2br}--><!--{if $arrForm.waist_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.hip_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.hip|nl2br}--><!--{if $arrForm.hip_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.garment_length_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.garment_length|nl2br}--><!--{if $arrForm.garment_length_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.shoulders_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.shoulders|nl2br}--><!--{if $arrForm.shoulders_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.shoulders_length_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.shoulders_length|nl2br}--><!--{if $arrForm.shoulders_length_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.sleeve_length_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.sleeve_length|nl2br}--><!--{if $arrForm.sleeve_length_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                    <td width="94" bgcolor="#ffffff"><!--{if $arrForm.cuff_flg == "1"}--><span class="red"><!--{/if}--><!--{$arrForm.cuff|nl2br}--><!--{if $arrForm.cuff_flg == "1"}--></span><!--{/if}--></td><!--//::B00020 Change 20130326-->
                                 </tr>
                            </table>
                            <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
<!-- 2013.01.21 RCHJ Add & Change -->
                                <tr>
                                    <th>注意事項</th>
                                    <td>
                                    	<!--{html_checkboxes name="important_points_ids" options=$arrImportanPoint separator="<br/>" selected=$arrForm.important_points_ids}-->
                                    	<!--{$arrForm.important_points|nl2br}-->
                                    </td>
                                </tr>
<!-- End -->
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">素材</td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
                                    <!--{$arrForm.item_materrial|escape|sfPutBR:$smarty.const.LINE_LIMIT_SIZE}-->
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">生地の厚さ</td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
                                    <!--{$arrTHICKNESSTYPE[$arrForm.thickness_type]|escape}-->
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">裏地</td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
                                    <!--{$arrLINERTYPE[$arrForm.liner_type]|escape}-->
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">ファスナー</td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
                                    <!--{$arrFASTENERTYPE[$arrForm.fastener_type]|escape}-->
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">その他</td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
                                    <!--{$arrForm.other_data|escape}-->
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">セット内容</td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
                                    <!--{$arrForm.set_content|escape}-->
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">状態</td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
                                    <!--{$arrForm.content_status|nl2br}-->
                                    </td>
                                </tr>
                                <!--{/if}-->
								<!--{section name=cnt loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3}-->
								<!--{assign var="key" value="photo_gallery_image`$smarty.section.cnt.iteration`"}-->
								<!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration`"}-->
								<tr>
									<td bgcolor="#f2f1ec" width="160" class="fs12n">フォトギャラリー画像<!--{$smarty.section.cnt.iteration}--></td>
									<td bgcolor="#ffffff" width="557">
									<!--{if $arrFile[$key].filepath != ""}-->
										<img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" /><br />
									<!--{/if}-->
									</td>
								</tr>
								<tr>
									<td bgcolor="#f2f1ec" width="160" class="fs12n">フォトギャラリーコメント<!--{$smarty.section.cnt.iteration}--></td>
									<td bgcolor="#ffffff" width="557" class="fs12n">
										<!--{$arrForm[$key2]|escape}-->
									</td>
								</tr>
								<!--{/section}-->
				<!-- ADD KGS_20140307}} -->
<!-- {{ADD KGS_20140307 -->
								<tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n" colspan="2" style="text-align:center;">サブ情報</td>
                                </tr>
                                <!--{assign var="start_index" value="`$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3`"}-->
                                <!--{section name=cnt start=$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3 loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM}-->
								<!--{assign var="key" value="photo_gallery_image`$smarty.section.cnt.iteration+$start_index`"}-->
                                <!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration+$start_index`"}-->
								<tr>
									<td bgcolor="#f2f1ec" width="160" class="fs12n">詳細-サブ画像<!--{$smarty.section.cnt.iteration}--></td>
									<td bgcolor="#ffffff" width="557">
									<!--{if $arrFile[$key].filepath != ""}-->
										<img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" /><br />
									<!--{/if}-->
									</td>
								</tr>
								<tr>
									<td bgcolor="#f2f1ec" width="160" class="fs12n">詳細-サブコメント<!--{$smarty.section.cnt.iteration}--></td>
									<td bgcolor="#ffffff" width="557" class="fs12n">
										<!--{$arrForm[$key2]|escape}-->
									</td>
								</tr>
								<!--{/section}-->
<!-- ADD KGS_20140307}} -->
        <!--{if $smarty.const.OPTION_RECOMMEND == 1}-->
<!-- {{ADD KGS_20140307 -->
                                <!--▼コーディネートで使用している商品-->
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n" colspan="2" style="text-align:center;">コーディネートで使用している商品</td>
                                </tr>
                                <!--{section name=cnt loop=$smarty.const.COORDINATE_RECOMMEND_PRODUCT_MAX}-->
                                <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">商品(<!--{$smarty.section.cnt.iteration}-->)<br>
                                    <!--{if $arrCoordinateRecommend[$recommend_no].main_list_image != ""}-->
                                        <!--{assign var=image_path value="`$arrCoordinateRecommend[$recommend_no].main_list_image`"}-->
                                    <!--{else}-->
                                        <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                                    <!--{/if}-->
                                    <img src="<!--{$smarty.const.SITE_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65" alt="<!--{$arrCoordinateRecommend[$recommend_no].name|escape}-->">
                                    </td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
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
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n" colspan="2" style="text-align:center;">サイズ・色違いの商品</td>
                                </tr>
                                <!--{section name=cnt loop=$smarty.const.SIZE_COLOR_RECOMMEND_PRODUCT_MAX}-->
                                <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
                                <tr>
                                    <td bgcolor="#f2f1ec" width="160" class="fs12n">商品(<!--{$smarty.section.cnt.iteration}-->)<br>
                                    <!--{if $arrSizeColorRecommend[$recommend_no].main_list_image != ""}-->
                                        <!--{assign var=image_path value="`$arrSizeColorRecommend[$recommend_no].main_list_image`"}-->
                                    <!--{else}-->
                                        <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                                    <!--{/if}-->
                                    <img src="<!--{$smarty.const.SITE_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65" alt="<!--{$arrSizeColorRecommend[$recommend_no].name|escape}-->">
                                    </td>
                                    <td bgcolor="#ffffff" width="557" class="fs12n">
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
<!-- ADD KGS_20140307}} -->
        <!--{/if}-->
    </table>

    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="eccube.setModeAndSubmit('confirm_return','',''); return false;"><span class="btn-prev">前のページに戻る</span></a></li>
            <li><a class="btn-action" href="javascript:;" onclick="document.form1.submit(); return false;"><span class="btn-next">この内容で登録する</span></a></li>
        </ul>
    </div>
</div>
</form>
