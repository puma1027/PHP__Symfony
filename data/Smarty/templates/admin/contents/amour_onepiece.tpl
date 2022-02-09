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
<script type="text/javascript">
<!--
function addRedtext(tag) {

    var comment = document.getElementsByName(tag)[0];

    var text1 = "<font color='#CC0033'>";
    var text2 = "</font>";

    if (typeof(comment.selectionStart) != "undefined") {
		var begin = comment.value.substr(0, comment.selectionStart);
		var selection = comment.value.substr(comment.selectionStart, comment.selectionEnd - comment.selectionStart);
		var end = comment.value.substr(comment.selectionEnd);
		comment.value = begin + text1 + selection + text2 + end;
		comment.selectionEnd =begin.length + text1.length  + selection.length  + text2.length  ;
    }else{
		var start = getStart(comment);
		var end = getEnd(comment);
		if(start){
			var begin = comment.value.substr(0, start );
			var selection = comment.value.substr(start , end  -start);
			var end = comment.value.substr(end);
			comment.value = begin + text1 + selection + text2 + end;
			comment.selectionEnd =begin.length + text1.length  + selection.length  + text2.length  ;
		}else{
			//comment.value =comment.value+text1+text2;
		}
    }
    comment.focus();
}

function getStart(el) {

  if (el.selectionStart) {  
    return el.selectionStart;  
  } else if (document.selection) {  
    el.focus();  
 
    var r = document.selection.createRange();  
    if (r == null) {  
      return 0;  
    }  
 
    var re = el.createTextRange(),  
        rc = re.duplicate();  
    re.moveToBookmark(r.getBookmark());  
    rc.setEndPoint('EndToStart', re);  
 
    return rc.text.length;  
  }   
  return 0;  
} 
function getEnd(el) {

  if (el.selectionStart) {  
    return el.selectionStart;  
  } else if (document.selection) {  
    el.focus();  
 
    var r = document.selection.createRange();  
    if (r == null) {  
      return 0;  
    }  
 
    var re = el.createTextRange(),  
        rc = re.duplicate();  
    re.moveToBookmark(r.getBookmark());  
    rc.setEndPoint('EndToStart', re);  
 
    return rc.text.length+r.text.length;  
  }   
  return 0;  
} 

function removeRedtext(tag) {

    var comment = document.getElementsByName(tag)[0];

    var text1 = "<font color='#CC0033'>";
    var text2 = "</font>";

    if (typeof(comment.selectionStart) != "undefined") {
        var begin = comment.value.substr(0, comment.selectionStart);
        var selection = comment.value.substr(comment.selectionStart, comment.selectionEnd - comment.selectionStart);
        var end = comment.value.substr(comment.selectionEnd);
        var intIndexOfMatch1 = selection.indexOf( text1 );
        var intIndexOfMatch2 = selection.indexOf( text2 );
        if(intIndexOfMatch1 != -1 && intIndexOfMatch2 != -1){
            selection = selection.replace(text1, '');
            selection = selection.replace(text2, '');
            comment.value = begin + selection + end;
            comment.selectionEnd =begin.length+selection.length  ;
        }else{
            alert('文字列を正確に選択してください。');
        }
    }else{
		var start = getStart(comment);
		var end = getEnd(comment);
		if(start){
			var begin = comment.value.substr(0, start);
			var selection = comment.value.substr(start, end - start);
			var end = comment.value.substr(end);
			var intIndexOfMatch1 = selection.indexOf( text1 );
			var intIndexOfMatch2 = selection.indexOf( text2 );
			if(intIndexOfMatch1 != -1 && intIndexOfMatch2 != -1){
				selection = selection.replace(text1, '');
				selection = selection.replace(text2, '');
				comment.value = begin + selection + end;
				comment.selectionEnd =begin.length+selection.length  ;
			}else{
				alert('文字列を正確に選択してください。');
			}
		}else{
			var intIndexOfMatch1 = comment.value.indexOf( text1 );
			var intIndexOfMatch2 = comment.value.indexOf( text2 );
			if(intIndexOfMatch1 != -1 && intIndexOfMatch2 != -1){
				comment.value = comment.value.replace(text1, '');
				comment.value = comment.value.replace(text2, '');
			}
		}

        
    }
    comment.focus();
}
//-->
</script>
<div id="admin-contents" class="contents-main">
    <form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="" />
        <input type="hidden" name="image_key" value="" />
        <input type="hidden" name="amour_id" value="<!--{$arrForm.amour_id.value|default:$tpl_amour_id|h}-->" >
        <input type="hidden" name="tmp_product_id" id="tmp_product_id" value="<!--{$arrForm.tmp_product_id.value|escape}-->">
        <input type="hidden" name="no" id="no" value="">
        <input type="hidden" name="classcategory_id1" id="classcategory_id1" value="<!--{$classcategory_id1|escape}-->">
        <input type="hidden" name="classcategory_id2" id="classcategory_id2" value="<!--{$classcategory_id2|escape}-->">
        <!--{foreach key=key item=item from=$arrHidden}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
        <!--{/foreach}-->

        <!--{* ▼登録テーブルここから *}-->
        <table>
            <tr>
                <th class="center" colspan="2">レポート</th>
            </tr>
            <tr>
                <th>
                    第<!--{$arrForm.time_count.value}-->回登場日
                    <input type="hidden" name="time_count" value="<!--{$arrForm.time_count.value}-->" />
                </th>
                <td>
					<span class="attention"><!--{$arrErr.year}--><!--{$arrErr.month}--><!--{$arrErr.day}--></span>
                    <select name="year" <!--{if $arrErr.year}-->style="background-color:<!--{$smarty.const.ERR_COLOR|escape}-->"<!--{/if}-->>
                        <option value="" selected>----</option>
                        <!--{html_options options=$arrYear selected=$arrForm.year.value}-->
                    </select>年
                    <select name="month" <!--{if $arrErr.month}-->style="background-color:<!--{$smarty.const.ERR_COLOR|escape}-->"<!--{/if}-->>
                        <option value="" selected>--</option>
                        <!--{html_options options=$arrMonth selected=$arrForm.month.value}-->
                    </select>月
                    <select name="day" <!--{if $arrErr.day}-->style="background-color:<!--{$smarty.const.ERR_COLOR|escape}-->"<!--{/if}-->>
                        <option value="" selected>--</option>
                        <!--{html_options options=$arrDay selected=$arrForm.day.value}-->
                    </select>日
                </td>
            </tr>
            <tr>
                <th>公開／非公開<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.show_flg}--><span class="attention"><!--{$arrErr.show_flg}--></span><!--{/if}-->
                    <label><input type="radio" name="show_flg" value="1" <!--{if $arrForm.show_flg.value == 1}-->checked="checked"<!--{/if}--> />公開</label>
                    <label><input type="radio" name="show_flg" value="0" <!--{if $arrForm.show_flg.value == 0}-->checked="checked"<!--{/if}--> />非公開</label>
                </td>
            </tr>
            <tr>
                <th>タイトル<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.report_title}--><span class="attention"><!--{$arrErr.report_title}--></span><!--{/if}-->
                    <input type="text" name="report_title" value="<!--{$arrForm.report_title.value}-->" size="50" class="box50" <!--{if $arrErr.report_title}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
                </td>
            </tr>
            <tr>
                <th>写真1<span class="attention"> *</span></th>
                <td>
                    <!--{assign var=key value="report_image"}-->
                    <span class="red12"><!--{$arrErr[$key]}--></span>
                    <!--{if $arrFile[$key].filepath != ""}-->
                    <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.report_image.value|escape}-->" />　<a href="" onclick="fnModeSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
                    <!--{/if}-->
                    <input type="file" name="report_image" size="43" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <input type="button" name="btnReportImg1" onclick="fnModeSubmit('upload_image', 'image_key', '<!--{$key}-->')" value="アップロード">
                </td>
            </tr>
            <tr>
                <th>写真2</th>
                <td>
                    <!--{assign var=key value="report_image2"}-->
                    <span class="red12"><!--{$arrErr[$key]}--></span>
                    <!--{if $arrFile[$key].filepath != ""}-->
                    <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.report_image2.value|escape}-->" />　<a href="" onclick="fnModeSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
                    <!--{/if}-->
                    <input type="file" name="report_image2" size="43" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <input type="button" name="btnReportImg2" onclick="fnModeSubmit('upload_image', 'image_key', '<!--{$key}-->')" value="アップロード">
                </td>
            </tr>
            <tr>
                <th>商品コード<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.report_product_id}--><span class="attention"><!--{$arrErr.report_product_id}--></span><!--{/if}-->
                    <input type="hidden" name="report_product_id" id="report_product_id" value="<!--{$arrForm.report_product_id.value|escape}-->">
                    <input type="hidden" name="report_product_name" id="report_product_name" value="<!--{$arrForm.report_product_name.value|escape}-->">
                    <input type="hidden" name="report_product_code" id="report_product_code" value="<!--{$arrForm.report_product_code.value|escape}-->">
                    <input type="hidden" name="main_list_image" id="main_list_image" value="<!--{$arrForm.main_list_image.value|escape}-->">
                    <!--{if $arrForm.product_name.value}-->
                    <!--{$arrForm.product_name.value}-->・<!--{$arrForm.product_code.value}-->&nbsp;&nbsp;
                    <!--{/if}-->
                    <!--{if $arrForm.main_list_image.value}-->
                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.main_list_image.value}-->" alt="商品コード">
                    <!--{/if}-->
                    <input type="button" name="add_product" value="商品" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php', 'search', '500', '500'); " />
                </td>
            </tr>
            <tr>
                <th>本文</th>
                <td>
                    <!--{if $arrErr.report_content}--><span class="attention"><!--{$arrErr.report_content}--></span><!--{/if}-->
                    <textarea name="report_content" cols="60" rows="8" wrap="soft" class="area60" maxlength="<!--{$smarty.const.LTEXT_LEN}-->"
                            style="background-color:<!--{if $arrErr.report_content}--><!--{$smarty.const.ERR_COLOR|escape}--><!--{/if}-->"><!--{$arrForm.report_content.value|escape}--></textarea>
                    <br/><span class="attention"> （上限3000文字）</span>
                    <button id="red_text1" name="red_text1" title="赤字" onclick="addRedtext('report_content');return false;"><font color="#CC0033">赤字</font></button>
                    <button id="black_text1" name="black_text1" title="黒字に戻す" onclick="removeRedtext('report_content');return false;">黒字に戻す</button>
                </td>
            </tr>           
            <tr>
                <th class="center" colspan="2">オトコ目線レビュー</t>
            </tr>
            <tr>
                <th>作成社員</th>
                <td>
                    <select name="create_staff_id">
                    <!--{html_options options=$arrStaff selected=$arrForm.create_staff_id.value}-->
                    </select>
                </td>
            </tr>
            <tr>
                <th>レビュー<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.review_flg}--><span class="attention"><!--{$arrErr.review_flg}--></span><!--{/if}-->
                    <label><input type="radio" name="review_flg" value="1" <!--{if $arrForm.review_flg.value == 1}-->checked="checked"<!--{/if}--> />アリ</label>
                    <label><input type="radio" name="review_flg" value="0" <!--{if $arrForm.review_flg.value == 0}-->checked="checked"<!--{/if}--> />ナシ</label>
                </td>
            </tr>
            <tr>
                <th>一言感想<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.man_impression}--><span class="attention"><!--{$arrErr.man_impression}--></span><!--{/if}-->
                    <!--{assign var="man_impression" value="`$arrForm.man_impression.value`"}-->
		    例：ボーダーワンピは、カジュアルで好き。<br/>
                    <input type="text" name="man_impression" value="<!--{$man_impression}-->" class="box68"  <!--{if $arrErr.man_impression}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
                </td>
            </tr>
            <tr>
                <th>本文</th>
                <td>
                    <!--{if $arrErr.man_review_content}--><span class="attention"><!--{$arrErr.man_review_content}--></span><!--{/if}-->
                    <textarea name="man_review_content" cols="60" rows="8" wrap="soft" class="area60" maxlength="<!--{$smarty.const.LTEXT_LEN}-->"
                            style="background-color:<!--{if $arrErr.man_review_content}--><!--{$smarty.const.ERR_COLOR|escape}--><!--{/if}-->"><!--{$arrForm.man_review_content.value|escape}--></textarea>
                    <br/>
                    <span class="attention"> （上限3000文字）</span>
                    <button id="red_text2" name="red_text2" title="赤字" onclick="addRedtext('man_review_content');return false;"><font color="#CC0033">赤字</font></button>
                    <button id="black_text2" name="black_text2" title="黒字に戻す" onclick="removeRedtext('man_review_content');return false;">黒字に戻す</button>
                </td>
            </tr>
            <tr>
                <th>まとめ<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.summary}--><span class="attention"><!--{$arrErr.summary}--></span><!--{/if}-->
                    <!--{assign var="summary" value="`$arrForm.summary.value`"}-->
		    例：スポーツ観戦には、屋外に映える明るい色のワンピがおすすめ！<br />
                    <input type="text" name="summary" value="<!--{$summary}-->" class="box68" <!--{if $arrErr.summary}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
                </td>
            </tr>
            <tr>
                <th>おすすめ商品</th>
                <td>
                    <table class="no-border" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <!--{if $arrForm.recommend_product_image1.value}-->
                                <img alt="おすすめ商品1" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image1.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product1" value="商品1" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=1', 'search', '500', '500'); " />
                                <input type="button" name="del_product1" value="削除1" onclick="eccube.fnFormModeSubmit('form1', 'del_recommend_product', 'no', 1);" />
                                <input type="hidden" name="recommend_product_id1" value="<!--{$arrForm.recommend_product_id1.value|escape}-->"/>
                                <input type="hidden" name="recommend_product_image1" id="recommend_product_image1" value="<!--{$arrForm.recommend_product_image1.value|escape}-->">
                            </td>
                            <td>
                                <!--{if $arrForm.recommend_product_image2.value}-->
                                <img alt="おすすめ商品2" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image2.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product2" value="商品2" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=2', 'search', '500', '500'); " />
                                <input type="button" name="del_product2" value="削除2" onclick="eccube.fnFormModeSubmit('form1', 'del_recommend_product', 'no', 2);" />
                                <input type="hidden" name="recommend_product_id2" value="<!--{$arrForm.recommend_product_id2.value|escape}-->"/>
                                <input type="hidden" name="recommend_product_image2" id="recommend_product_image2" value="<!--{$arrForm.recommend_product_image2.value|escape}-->">
                            </td>
                            <td>
                                <!--{if $arrForm.recommend_product_image3.value}-->
                                <img alt="おすすめ商品3" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image3.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product3" value="商品3" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=3', 'search', '500', '500'); " />
                                <input type="button" name="del_product3" value="削除3" onclick="eccube.fnFormModeSubmit('form1', 'del_recommend_product', 'no', 3);" />
                                <input type="hidden" name="recommend_product_id3" value="<!--{$arrForm.recommend_product_id3.value|escape}-->"/>
                                <input type="hidden" name="recommend_product_image3" id="recommend_product_image3" value="<!--{$arrForm.recommend_product_image3.value|escape}-->">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--{* ▲登録テーブルここまで *}-->

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>

        <h2>バックナンバー一覧
            <a class="btn-normal" href="">新規登録</a>
        </h2>
        <table class="list">
            <col width="10%" />
            <col width="15%" />
            <col width="45%" />
            <col width="10%" />
            <col width="10%" />
            <tr>
                <th class="center">回</th>
                <th class="center">登場日</th>
                <th class="center">レポートタイトル</th>
                <th class="edit center">編集</th>
                <th class="delete center">削除</th>
            </tr>
            <!--{section name=data loop=$list_data}-->
            <tr style="background-color:<!--{if $list_data[data].amour_id == $tpl_amour_id}--><!--{$smarty.const.SELECT_RGB}--><!--{else}-->#ffffff<!--{/if}-->;">
                <td class="center"><!--{$list_data[data].time_count}--></td>
                <td class="center"><!--{$list_data[data].appear_date|date_format:"%Y/%m/%d"}--></td>
                <td class="center"><!--{$list_data[data].report_title}--></td>
                <td class="center">
                    <!--{if $list_data[data].amour_id!=$tpl_amour_id}-->
                    <a href="#" onclick="eccube.fnFormModeSubmit('form1','pre_edit','amour_id','<!--{$list_data[data].amour_id|escape}-->'); return false;">編集</a>
                    <!--{else}-->
                    編集中
                    <!--{/if}-->
                </td>
                <td class="center"><a href="#" onclick="eccube.fnFormModeSubmit('form1','delete','amour_id','<!--{$list_data[data].amour_id|escape}-->'); return false;">削除</a></td>
            </tr>
            <!--{sectionelse}-->
            <tr>
                <td colspan="6">現在登録された社員はありません。</td>
            </tr>
            <!--{/section}-->
        </table>
    </form>
    <!--{* ▲一覧表示エリアここまで *}-->

</div>
