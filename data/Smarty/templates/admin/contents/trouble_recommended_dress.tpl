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
            alert('????????????????????????????????????????????????');
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
				alert('????????????????????????????????????????????????');
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

//function func_regist(url) {
//
//	res = confirm('???????????????<!--{if $edit_mode eq "on"}-->??????<!--{else}-->??????<!--{/if}-->??????????????????????????????');
//	if(res == true) {
//		document.form1.mode.value = 'regist';
//		document.form1.submit();
//		return false;
//	}
//	return false;
//}

function func_del(trouble_recommended_dress_id) {
	res = confirm('????????????????????????????????????????????????????????????????????????');
	if(res == true) {
		document.form1.mode.value = "delete";
		document.form1.trouble_recommended_dress_id.value = trouble_recommended_dress_id;
		document.form1.submit();
	}
	return false;
}

function func_edit(trouble_recommended_dress_id) {
    document.form1.mode.value = "edit";
    document.form1.trouble_recommended_dress_id.value = trouble_recommended_dress_id;
    document.form1.submit();
}

function func_del_recommend_product(no) {
    document.form1.mode.value = "del_recommend_product";
    document.form1.no.value = no;
    document.form1.submit();
}

//function func_del_coodinate_product(no) {
//    document.form1.mode.value = "del_coodinate_product";
//    document.form1.no.value = no;
//    document.form1.submit();
//}
//-->
</script>
<div id="admin-contents" class="contents-main">
    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="" />
		<input type="hidden" name="trouble_recommended_dress_id" value="<!--{$arrForm.trouble_recommended_dress_id.value}-->" >
		<input type="hidden" name="tmp_product_id" id="tmp_product_id" value="<!--{$arrForm.tmp_product_id.value|h}-->">
        <input type="hidden" name="no" id="no" value="">
        <input type="hidden" name="type" id="type" value="">
        <input type="hidden" name="classcategory_id1" id="classcategory_id1" value="<!--{$classcategory_id1|h}-->">
        <input type="hidden" name="classcategory_id2" id="classcategory_id2" value="<!--{$classcategory_id2|h}-->">
        <input type="hidden" name="recommend_no" value="<!--{$arrForm.recommend_no.value}-->" />
		
		<!--{* ????????????????????????????????? *}-->
        <table>
            <tr>
                <th>??????????????????<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.show_flg}--><span class="attention"><!--{$arrErr.show_flg}--></span><!--{/if}-->
                    <label><input type="radio" name="show_flg" value="1" <!--{if $arrForm.show_flg.value == 1}-->checked="checked"<!--{/if}--> />??????</label>
                    <label><input type="radio" name="show_flg" value="0" <!--{if $arrForm.show_flg.value == 0}-->checked="checked"<!--{/if}--> />?????????</label>
                </td>
            </tr>
            <tr>
                <th>??????????????????38???????????????<span class="attention"> *</span></th>
                <td>
                	<table class="no-border" width="100%" cellpadding="0" cellspacing="0" border="0">
                    	<tr>
                        	<td>
                                <!--{if $arrErr.meta_tag_keyword1}--><span class="attention"><!--{$arrErr.meta_tag_keyword1}--></span><!--{/if}-->
                                <!--{assign var="meta_tag_keyword1" value="`$arrForm.meta_tag_keyword1.value`"}-->
                                ??????????????????<br />
                                <input type="text" name="meta_tag_keyword1" value="<!--{$meta_tag_keyword1}-->" class="box20" <!--{if $arrErr.meta_tag_keyword1}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
                            </td>
                            <td>
                                <!--{if $arrErr.meta_tag_keyword2}--><span class="attention"><!--{$arrErr.meta_tag_keyword2}--></span><!--{/if}-->
                                <!--{assign var="meta_tag_keyword2" value="`$arrForm.meta_tag_keyword2.value`"}-->
                                ??????????????????<br />
                                <input type="text" name="meta_tag_keyword2" value="<!--{$meta_tag_keyword2}-->" class="box20" <!--{if $arrErr.meta_tag_keyword2}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
                            </td>
                            <td>
                                <!--{if $arrErr.meta_tag_keyword3}--><span class="attention"><!--{$arrErr.meta_tag_keyword3}--></span><!--{/if}-->
                                <!--{assign var="meta_tag_keyword3" value="`$arrForm.meta_tag_keyword3.value`"}-->
                                ??????????????????<br />
                                <input type="text" name="meta_tag_keyword3" value="<!--{$meta_tag_keyword3}-->" class="box20" <!--{if $arrErr.meta_tag_keyword3}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
                            </td>
                        </tr>
                    </table>
				</td>
            </tr>
            <tr>
                <th>???????????????20???????????????<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.title}--><span class="attention"><!--{$arrErr.title}--></span><!--{/if}-->
                    <input type="text" name="title" value="<!--{$arrForm.title.value}-->" size="50" class="box50" maxlength="20" <!--{if $arrErr.title}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
				</td>
            </tr>
            <tr>
                <th>??????URL<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.video_url}--><span class="attention"><!--{$arrErr.video_url}--></span><!--{/if}-->
                    <input type="text" name="video_url" value="<!--{$arrForm.video_url.value}-->" size="50" class="box50" <!--{if $arrErr.video_url}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
				</td>
            </tr>
            <tr>
                <th>????????????</th>
                <td>
                    <!--{if $arrErr.legend1}--><span class="attention"><!--{$arrErr.legend1}--></span><!--{/if}-->
                    <textarea name="legend1" cols="60" rows="5" wrap="soft" class="area61" maxlength="<!--{$smarty.const.LTEXT_LEN}-->"
                              style="background-color:<!--{if $arrErr.legend1}--><!--{$smarty.const.ERR_COLOR|h}--><!--{/if}-->"><!--{$arrForm.legend1.value|h}--></textarea>
                    <br/><span class="attention"> *</span>
                    <button id="red_text2" name="red_text2" title="??????" onclick="addRedtext('legend1');return false;"><font color="#CC0033">??????</font></button>
                    <button id="black_text2" name="black_text2" title="???????????????" onclick="removeRedtext('legend1');return false;">???????????????</button>

				</td>
            </tr>
            <tr>
                <th>?????????????????????????????????</th>
                <td>
                    <table class="no-border" width="100%" border="0" cellspacing="0" cellpadding="0" summary=" ">
                        <tr class="fs12n" style="vertical-align: bottom">
                            <td width="33%">
                                <!--{if $arrForm.recommend_product_image1.value}-->
                                <img alt="?????????????????????????????????1" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image1.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product4" value="??????1" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=1&type=recommend', 'search', '500', '500'); " />
                                <input type="button" name="del_product4" value="??????1" onclick="func_del_recommend_product(1);" />
                                <input type="hidden" name="recommend_product_id1" value="<!--{$arrForm.recommend_product_id1.value|h}-->"/>
                                <input type="hidden" name="recommend_product_image1" id="recommend_product_image1" value="<!--{$arrForm.recommend_product_image1.value|h}-->">
                            </td>
                            <td width="33%">
                                <!--{if $arrForm.recommend_product_image2.value}-->
                                <img alt="?????????????????????????????????2" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image2.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product5" value="??????2" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=2&type=recommend', 'search', '500', '500'); " />
                                <input type="button" name="del_product5" value="??????2" onclick="func_del_recommend_product(2);" />
                                <input type="hidden" name="recommend_product_id2" value="<!--{$arrForm.recommend_product_id2.value|h}-->"/>
                                <input type="hidden" name="recommend_product_image2" id="recommend_product_image2" value="<!--{$arrForm.recommend_product_image2.value|h}-->">
                            </td>
                            <td>
                                <!--{if $arrForm.recommend_product_image3.value}-->
                                <img alt="?????????????????????????????????3" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image3.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product6" value="??????3" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=3&type=recommend', 'search', '500', '500'); " />
                                <input type="button" name="del_product6" value="??????3" onclick="func_del_recommend_product(3);" />
                                <input type="hidden" name="recommend_product_id3" value="<!--{$arrForm.recommend_product_id3.value|h}-->"/>
                                <input type="hidden" name="recommend_product_image3" id="recommend_product_image3" value="<!--{$arrForm.recommend_product_image3.value|h}-->">
                            </td>
                        </tr>
                    </table>
    </td>
            </tr>
            <tr>
                <th>??????????????????????????????????????????</th>
                <td>
                    <table class="no-border" width="100%" border="0" cellspacing="0" cellpadding="0" summary=" ">
                        <tr class="fs12n" style="vertical-align: bottom">
                            <td width="33%">
                                <!--{if $arrForm.recommend_product_image4.value}-->
                                <img alt="??????????????????????????????????????????1" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image4.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product4" value="??????1" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=4&type=recommend', 'search', '500', '500'); " />
                                <input type="button" name="del_product4" value="??????1" onclick="func_del_recommend_product(4);" />
                                <input type="hidden" name="recommend_product_id4" value="<!--{$arrForm.recommend_product_id4.value|h}-->"/>
                                <input type="hidden" name="recommend_product_image4" id="recommend_product_image4" value="<!--{$arrForm.recommend_product_image4.value|h}-->">
                            </td>
                            <td width="33%">
                                <!--{if $arrForm.recommend_product_image5.value}-->
                                <img alt="??????????????????????????????????????????2" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image5.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product5" value="??????2" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=5&type=recommend', 'search', '500', '500'); " />
                                <input type="button" name="del_product5" value="??????2" onclick="func_del_recommend_product(5);" />
                                <input type="hidden" name="recommend_product_id5" value="<!--{$arrForm.recommend_product_id5.value|h}-->"/>
                                <input type="hidden" name="recommend_product_image5" id="recommend_product_image5" value="<!--{$arrForm.recommend_product_image5.value|h}-->">
                            </td>
                            <td>
                                <!--{if $arrForm.recommend_product_image6.value}-->
                                <img alt="??????????????????????????????????????????3" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image6.value}-->">
                                <!--{/if}-->
                                <br/>
                                <input type="button" name="add_product6" value="??????3" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=6&type=recommend', 'search', '500', '500'); " />
                                <input type="button" name="del_product6" value="??????3" onclick="func_del_recommend_product(6);" />
                                <input type="hidden" name="recommend_product_id6" value="<!--{$arrForm.recommend_product_id6.value|h}-->"/>
                                <input type="hidden" name="recommend_product_image6" id="recommend_product_image6" value="<!--{$arrForm.recommend_product_image6.value|h}-->">
                            </td>
                        </tr>
                    </table>

				</td>
            </tr>
            <tr>
                <th>????????????</th>
                <td>
                    <!--{if $arrErr.legend2}--><span class="attention"><!--{$arrErr.legend2}--></span><!--{/if}-->
                    <textarea name="legend2" cols="60" rows="5" wrap="soft" class="area61" maxlength="<!--{$smarty.const.LTEXT_LEN}-->"
                              style="background-color:<!--{if $arrErr.legend2}--><!--{$smarty.const.ERR_COLOR|h}--><!--{/if}-->"><!--{$arrForm.legend2.value|h}--></textarea>
                    <br/><span class="attention"> ?????????3000?????????</span>
                    <button id="red_text2" name="red_text2" title="??????" onclick="addRedtext('legend2');return false;"><font color="#CC0033">??????</font></button>
                    <button id="black_text2" name="black_text2" title="???????????????" onclick="removeRedtext('legend2');return false;">???????????????</button>

				</td>
            </tr>
			<tr>
                <th colspan="2">????????????</th>
            </tr>
            <tr>
                <th>???????????????????????????</th>
                <td>
                    <!--{html_checkboxes name="age_list" options=$arrAge selected=$arrForm.age_list}-->
				</td>
            </tr>
			
            <tr>
                <th>???????????????</th>
                <td>
                    <!--{html_checkboxes name="scene_list" options=$arrScene selected=$arrForm.scene_list}-->
				</td>
            </tr>
            <tr>            <tr>
                <th>???????????????</th>
                <td>
                    <!--{html_checkboxes name="trouble_list" options=$arrTrouble selected=$arrForm.trouble_list}-->
				</td>
            </tr>
            <tr>
                <th>?????????</th>
                <td>
                    <!--{html_checkboxes name="haircolor_list" options=$arrHairColor selected=$arrForm.haircolor_list}-->
				</td>
            </tr>

                <th>????????????</th>
                <td>
                    <!--{html_checkboxes name="hairlength_list" options=$arrHairLength selected=$arrForm.hairlength_list}-->
				</td>
            </tr>
            <tr>
                <th>?????????</th>
                <td>
                    <!--{html_checkboxes name="skincolor_list" options=$arrSkinColor selected=$arrForm.skincolor_list}-->
				</td>
            </tr>
        </table>
        <!--{* ????????????????????????????????? *}-->

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">???????????????????????????</span></a></li>
            </ul>
        </div>
    </form>

    <h2>??????????????????????????????????????????????????????
        <a class="btn-normal" href="">????????????</a>
    </h2>

    <!--{if $arrErr.moveposition}-->
    <p><span class="attention"><!--{$arrErr.moveposition}--></span></p>
    <!--{/if}-->
    <!--{* ???????????????????????????????????? *}-->
    <form name="move" id="move" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="moveRankSet" />
        <input type="hidden" name="trouble_recommended_dress_id" value="" />
        <input type="hidden" name="moveposition" value="" />
        <input type="hidden" name="rank" value="" />
        <table class="list">
            <col width="5%" />
            <col width="45%" />
            <col width="5%" />
            <col width="5%" />
            <tr>
                <th>???</th>
                <!--<th>????????????</th>-->
                <th>????????????</th>
                <th class="edit">??????</th>
                <th class="delete">??????</th>
            </tr>
            <!--{section name=data loop=$list_data}-->
            <tr style="background:<!--{if $list_data[data].trouble_recommended_dress_id!=$arrForm.trouble_recommended_dress_id.value}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->;" class="center">
                <td><!--{$list_data[data].recommend_no}--></td>
                <!--<td><!--{$list_data[data].prize_date_text}--></td>-->
                <td><!--{$list_data[data].title}--></td>
                <td>
                    <!--{if $list_data[data].trouble_recommended_dress_id!=$arrForm.trouble_recommended_dress_id.value}-->
                    <a href="#" onclick="eccube.fnFormModeSubmit('move','pre_edit','trouble_recommended_dress_id','<!--{$list_data[data].trouble_recommended_dress_id|h}-->'); return false;">??????</a>
                    <!--{else}-->
                    ?????????
                    <!--{/if}-->
                </td>
                <td><a href="#" onclick="eccube.fnFormModeSubmit('move','delete','trouble_recommended_dress_id','<!--{$list_data[data].trouble_recommended_dress_id|h}-->'); return false;">??????</a></td>
            </tr>
            <!--{sectionelse}-->
            <tr class="center">
                <td colspan="6">????????????????????????????????????</td>
            </tr>
            <!--{/section}-->
        </table>
    </form>
    <!--{* ???????????????????????????????????? *}-->

</div>
