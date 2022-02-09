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

function func_regist(url) {

	res = confirm('この内容で<!--{if $edit_mode eq "on"}-->編集<!--{else}-->登録<!--{/if}-->しても宜しいですか？');
	if(res == true) {
		document.form1.mode.value = 'regist';
		document.form1.submit();
		return false;
	}
	return false;
}

function func_edit(prize_id) {
    document.form1.mode.value = "edit";
    document.form1.prize_id.value = prize_id;
    document.form1.submit();
}

function func_del_recommend_product(no) {
    document.form1.mode.value = "del_recommend_product";
    document.form1.no.value = no;
    document.form1.submit();
}

function func_del_coodinate_product(no) {
    document.form1.mode.value = "del_coodinate_product";
    document.form1.no.value = no;
    document.form1.submit();
}
//-->
</script>

<div id="admin-contents" class="contents-main">
    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="" />
        <input type="hidden" name="prize_id" value="<!--{$arrForm.prize_id.value|default:$tpl_prize_id|h}-->" />

		<input type="hidden" name="tmp_product_id" id="tmp_product_id" value="<!--{$arrForm.tmp_product_id.value|h}-->">
		<input type="hidden" name="no" id="no" value="">
		<input type="hidden" name="type" id="type" value="">
		<input type="hidden" name="classcategory_id1" id="classcategory_id1" value="<!--{$classcategory_id1|h}-->">
		<input type="hidden" name="classcategory_id2" id="classcategory_id2" value="<!--{$classcategory_id2|h}-->">
		
		<!--{* ▼登録テーブルここから *}-->
        <table>
            <tr>
                <th>作成社員</th>
                <td>
					<select name="create_staff_id">
						<!--{html_options options=$arrStaff selected=$arrForm.create_staff_id.value}-->
					</select>
                </td>
            </tr>
            <tr>
                <th>第<!--{$arrForm.prize_no.value}-->回ご利用分<span class="attention"> *</span>
				<input type="hidden" name="prize_no" value="<!--{$arrForm.prize_no.value}-->" /></th>
                <td>
                    <!--{if $arrErr.prize_date_text}--><span class="attention"><!--{$arrErr.prize_date_text}--></span><!--{/if}-->
					<!--{assign var="prize_date_text" value="`$arrForm.prize_date_text.value`"}-->
					例：2013年4月22日・23日ご利用分<br>
					<input type="text" name="prize_date_text" value="<!--{$prize_date_text}-->" class="box68" <!--{if $arrErr.prize_date_text}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>

				</td>
            </tr>
            <tr>
                <th>公開／非公開<span class="attention"> *</span></th>
                <td>
					<label><input type="radio" name="show_flg" value="1" <!--{if $arrForm.show_flg.value == 1}-->checked="checked"<!--{/if}--> />公開</label>
					<label><input type="radio" name="show_flg" value="0" <!--{if $arrForm.show_flg.value == 0}-->checked="checked"<!--{/if}--> />非公開</label>

				</td>
            </tr>
            <tr>
                <th>タイトル（27文字以下）<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.title}--><span class="attention"><!--{$arrErr.title}--></span><!--{/if}-->
					<input type="text" name="title" value="<!--{$arrForm.title.value}-->" size="50" class="box50" maxlength="27"  <!--{if $arrErr.title}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
				</td>
            </tr>
            <tr>
                <th>お客様イニシャル<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.customer_name}--><span class="attention"><!--{$arrErr.customer_name}--></span><!--{/if}-->
					<!--{assign var="prize_date_text" value="`$arrForm.prize_date_text.value`"}-->
					例：Ｙ・Ｋ<br>
					<input type="text" name="customer_name" value="<!--{$arrForm.customer_name.value}-->" size="35" class="box35"  <!--{if $arrErr.customer_name}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>

				</td>
            </tr>
            <tr>
                <th>お客様情報</th>
                <td>
					<table class='no-border' width="100%" border='0' cellspacing='0' cellpadding='0' style='margin:0px'>
						<tr>
							<td>
								<!--{if $arrErr.customer_info1}--><span class="attention"><!--{$arrErr.customer_info1}--></span><!--{/if}-->
								<!--{assign var="customer_info" value="`$arrForm.customer_info1.value`"}-->
								例：愛知県<br>
								 <input type="text" name="customer_info1" value="<!--{$customer_info}-->" class="box15" <!--{if $arrErr.customer_info1}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
							</td>
							<td>
								<!--{if $arrErr.customer_info2}--><span class="attention"><!--{$arrErr.customer_info2}--></span><!--{/if}-->
								<!--{assign var="customer_info" value="`$arrForm.customer_info2.value`"}-->
								例：30代前半<br>
								 <input type="text" name="customer_info2" value="<!--{$customer_info}-->" class="box15" <!--{if $arrErr.customer_info2}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
							</td>
							<td>
								<!--{if $arrErr.customer_info3}--><span class="attention"><!--{$arrErr.customer_info3}--></span><!--{/if}-->
								<!--{assign var="customer_info" value="`$arrForm.customer_info3.value`"}-->
								例：身長160cm<br>
								 <input type="text" name="customer_info3" value="<!--{$customer_info}-->" class="box15" <!--{if $arrErr.customer_info3}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
							</td>
							<td>
								<!--{if $arrErr.customer_info4}--><span class="attention"><!--{$arrErr.customer_info4}--></span><!--{/if}-->
								<!--{assign var="customer_info" value="`$arrForm.customer_info4.value`"}-->
								例：S体型<br>
								 <input type="text" name="customer_info4" value="<!--{$customer_info}-->" class="box15" <!--{if $arrErr.customer_info4}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
							</td>
						</tr>
					</table>
				</td>
            </tr>
            <tr>
                <th>ドレス商品コード<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.product_id}--><span class="attention"><!--{$arrErr.product_id}--></span><!--{/if}-->
					<input type="hidden" name="product_id" id="product_id" value="<!--{$arrForm.product_id.value|h}-->">
					<input type="hidden" name="product_name" id="product_name" value="<!--{$arrForm.product_name.value|h}-->">
					<input type="hidden" name="product_code" id="product_code" value="<!--{$arrForm.product_code.value|h}-->">
					<input type="hidden" name="main_list_image" id="main_list_image" value="<!--{$arrForm.main_list_image.value|h}-->">
					<!--{if $arrForm.product_name.value}-->
						<!--{$arrForm.product_name.value}-->・<!--{$arrForm.product_code.value}-->&nbsp;&nbsp;
					<!--{/if}-->
					<!--{if $arrForm.main_list_image.value}-->
						<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.main_list_image.value}-->" alt="">
					<!--{/if}-->
						<input type="button" name="add_product" value="検索" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php', 'search', '500', '500'); " />

				</td>
            </tr>
            <tr>
                <th>コーディネートした小物<br>※商品以外は（参考）と入力</th>
                <td>
					<table class='no-border' width="100%" border="0" cellspacing="3px" cellpadding="3px" summary=" ">
					<!--{if $arrErr.coordinate1_text}-->
						<tr class="fs12n">
							<td colspan="6"><span class="attention"><!--{$arrErr.coordinate1_text}--></span></td>
						</tr>
					<!--{/if}-->
						<tr class="fs12n" style="vertical-align: bottom;">
							<td>1</td>
							<td >
								<!--{assign var="coordinate_text" value="`$arrForm.coordinate1_text.value`"}-->
								例：白コサージュ／黒ボレロ<br>
								<input type="text" name="coordinate1_text" value="<!--{$coordinate_text}-->" onfocus="cText(this, '<!--{$blur_text}-->')" style="width:90%"/>
							</td>
							<td width="120px">
								<!--{if $arrForm.coordinate1_product_image.value}-->
									<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate1_product_image.value}-->" alt="<!--{$arrForm.coordinate1_productid.value|h}-->">
									<input type="hidden" name="coordinate1_product_image" value="<!--{$arrForm.coordinate1_product_image.value}-->"/>
								<!--{/if}-->
								<input type="hidden" name="coordinate1_productid" value="<!--{$arrForm.coordinate1_productid.value|h}-->"/>
								<input type="hidden" name="coordinate1_product_name" value="<!--{$arrForm.coordinate1_product_name.value|h}-->" size="20" class="box20" />
							</td>
							<td>
								<input type="button" name="add_product1" value="商品1" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents/product_select.php?no=1', 'search', '500', '500'); " />
								<br/>
								<input type="button" name="del_coodinate_product1" value="削除1" onclick="func_del_coodinate_product(1);" />
							</td>
							<td>画像1</td>
							<td>
								<select name="coordinate1_imageid" style="width:100px;">
									<option value="" selected="selected"></option>
									<!--{html_options options=$arrImage selected=$arrForm.coordinate1_imageid.value}-->
								</select>
							</td>
						</tr>
					<!--{if $arrErr.coordinate2_text}-->
						<tr class="fs12n">
							<td colspan="6"><span class="attention"><!--{$arrErr.coordinate2_text}--></span></td>
						</tr>
					<!--{/if}-->
						<tr class="fs12n" style="vertical-align: bottom;">
							<td>2</td>
							<td>
								<!--{assign var="coordinate_text" value="`$arrForm.coordinate2_text.value`"}-->
								例：パールネックレス<br>
								<input type="text" name="coordinate2_text" value="<!--{$coordinate_text}-->" style="width:90%"/>
							</td>
							<td>
								<!--{if $arrForm.coordinate2_product_image.value}-->
									<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate2_product_image.value}-->" alt="<!--{$arrForm.coordinate2_productid.value|h}-->">
									<input type="hidden" name="coordinate2_product_image" value="<!--{$arrForm.coordinate2_product_image.value}-->"/>
								<!--{/if}-->
								<input type="hidden" name="coordinate2_productid" value="<!--{$arrForm.coordinate2_productid.value}-->"/>
								<input type="hidden" name="coordinate2_product_name" value="<!--{$arrForm.coordinate2_product_name.value}-->" size="20" class="box20" />
							</td>
							<td>
								<input type="button" name="add_product2" value="商品2" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents//product_select.php?no=2', 'search', '500', '500'); " />
								<br/>
								<input type="button" name="del_coodinate_product2" value="削除2" onclick="func_del_coodinate_product(2);" />
							</td>
							<td>画像2</td>
							<td>
								<select name="coordinate2_imageid" style="width:100px;">
									<option value="" selected="selected"></option>
								<!--{html_options options=$arrImage selected=$arrForm.coordinate2_imageid.value}-->
								</select>
							</td>
						</tr>
					<!--{if $arrErr.coordinate3_text}-->
						<tr class="fs12n">
							<td colspan="6"><span class="attention"><!--{$arrErr.coordinate3_text}--></span></td>
						</tr>
					<!--{/if}-->
						<tr class="fs12n" style="vertical-align: bottom;">
							<td>3</td>
							<td>
								<!--{assign var="coordinate_text" value="`$arrForm.coordinate3_text.value`"}-->
								例：ピンクのバッグ（参考）<br>
								<input type="text" name="coordinate3_text" value="<!--{$coordinate_text}-->"  style="width:90%"/>
							</td>
							<td>
								<!--{if $arrForm.coordinate3_product_image.value}-->
									<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate3_product_image.value}-->" alt="<!--{$arrForm.coordinate3_productid.value|h}-->">
									<input type="hidden" name="coordinate3_product_image" value="<!--{$arrForm.coordinate3_product_image.value}-->"/>
								<!--{/if}-->
								<input type="hidden" name="coordinate3_productid" value="<!--{$arrForm.coordinate3_productid.value}-->"/>
								<input type="hidden" name="coordinate3_product_name" value="<!--{$arrForm.coordinate3_product_name.value}-->" size="20" class="box20" />
							</td>
							<td>
								<input type="button" name="add_product3" value="商品3" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents//product_select.php?no=3', 'search', '500', '500'); " />
								<br/>
								<input type="button" name="del_coodinate_product3" value="削除3" onclick="func_del_coodinate_product(3);" />
							</td>
							<td>画像3</td>
							<td>
								<select name="coordinate3_imageid" style="width:100px;">
									<option value="" selected="selected"></option>
									<!--{html_options options=$arrImage selected=$arrForm.coordinate3_imageid.value}-->
								</select>
							</td>
						</tr>
					<!--{if $arrErr.coordinate4_text}-->
						<tr class="fs12n">
							<td colspan="6"><span class="attention"><!--{$arrErr.coordinate4_text}--></span></td>
						</tr>
					<!--{/if}-->
						<tr class="fs12n" style="vertical-align: bottom;">
							<td>4</td>
							<td>
								<!--{assign var="coordinate_text" value="`$arrForm.coordinate4_text.value`"}-->
								例：黒ベルト<br>
							<input type="text" name="coordinate4_text" value="<!--{$coordinate_text}-->" style="width:90%"/>
							</td>
							<td>
								<!--{if $arrForm.coordinate4_product_image.value}-->
									<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate4_product_image.value}-->" alt="<!--{$arrForm.coordinate4_productid.value|h}-->">
									<input type="hidden" name="coordinate4_product_image" value="<!--{$arrForm.coordinate4_product_image.value}-->"/>
								<!--{/if}-->
								<input type="hidden" name="coordinate4_productid" value="<!--{$arrForm.coordinate4_productid.value}-->"/>
								<input type="hidden" name="coordinate4_product_name" value="<!--{$arrForm.coordinate4_product_name.value}-->" size="20" class="box20" />
							</td>
							<td>
								<input type="button" name="add_product4" value="商品4" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents//product_select.php?no=4', 'search', '500', '500'); " />
								<br/>
								<input type="button" name="del_coodinate_product4" value="削除4" onclick="func_del_coodinate_product(4);" />
							</td>
							<td>画像4</td>
							<td>
								<select name="coordinate4_imageid" style="width:100px;">
									<option value="" selected="selected"></option>
									<!--{html_options options=$arrImage selected=$arrForm.coordinate4_imageid.value}-->
								</select>
							</td>
						</tr>
					<!--{if $arrErr.coordinate5_text}-->
						<tr class="fs12n">
							<td colspan="6"><span class="attention"><!--{$arrErr.coordinate5_text}--></span></td>
						</tr>
					<!--{/if}-->
						<tr class="fs12n" style="vertical-align: bottom;">
							<td>5</td>
							<td>
								<!--{assign var="coordinate_text" value="`$arrForm.coordinate5_text.value`"}-->
								例：ゴールドパンプス（参考）<br>
								<input type="text" name="coordinate5_text" value="<!--{$coordinate_text}-->" style="width:90%"/>
							</td>
							<td>
								<!--{if $arrForm.coordinate5_product_image.value}-->
									<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate5_product_image.value}-->" alt="<!--{$arrForm.coordinate5_productid.value|h}-->">
									<input type="hidden" name="coordinate5_product_image" value="<!--{$arrForm.coordinate5_product_image.value}-->"/>
								<!--{/if}-->
								<input type="hidden" name="coordinate5_productid" value="<!--{$arrForm.coordinate5_productid.value}-->"/>
								<input type="hidden" name="coordinate5_product_name" value="<!--{$arrForm.coordinate5_product_name.value}-->" size="20" class="box20" />
							</td>
							<td>
								<input type="button" name="add_product5" value="商品5" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents//product_select.php?no=5', 'search', '500', '500'); " />
								<br/>
								<input type="button" name="del_coodinate_product5" value="削除5" onclick="func_del_coodinate_product(5);" />
							</td>
							<td>画像5</td>
							<td>
								<select name="coordinate5_imageid" style="width:100px;">
									<option value="" selected="selected"></option>
									<!--{html_options options=$arrImage selected=$arrForm.coordinate5_imageid.value}-->
								</select>
							</td>
						</tr>
					<!--{if $arrErr.coordinate6_text}-->
						<tr class="fs12n">
							<td colspan="6"><span class="attention"><!--{$arrErr.coordinate6_text}--></span></td>
						</tr>
					<!--{/if}-->
						<tr class="fs12n" style="vertical-align: bottom;">
							<td>6</td>
							<td>
								例：白パニエ<br>
								<input type="text" name="coordinate6_text" value="<!--{$coordinate_text}-->" style="width:90%"/>
							</td>
							<td>
								<!--{if $arrForm.coordinate6_product_image.value}-->
									<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate6_product_image.value}-->" alt="<!--{$arrForm.coordinate6_productid.value|h}-->">
									<input type="hidden" name="coordinate6_product_image" value="<!--{$arrForm.coordinate6_product_image.value}-->"/>
								<!--{/if}-->
								<input type="hidden" name="coordinate6_productid" value="<!--{$arrForm.coordinate6_productid.value}-->"/>
								<input type="hidden" name="coordinate6_product_name" value="<!--{$arrForm.coordinate6_product_name.value}-->" size="20" class="box20" />
							</td>
							<td>
								<input type="button" name="add_product6" value="商品6" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents//product_select.php?no=6', 'search', '500', '500'); " />
								<br/>
								<input type="button" name="del_coodinate_product6" value="削除6" onclick="func_del_coodinate_product(6);" />
							</td>
							<td>画像6</td>
							<td>
								<select name="coordinate6_imageid" style="width:100px;">
									<option value="" selected="selected"></option>
									<!--{html_options options=$arrImage selected=$arrForm.coordinate6_imageid.value}-->
								</select>
							</td>
						</tr>                                                
					</table>

				</td>
            </tr>
			<tr>
                <th>本文</th>
                <td>
					色について<br/>
					<!--{if $arrErr.content_color}--><span class="attention"><!--{$arrErr.content_color}--></span><!--{/if}-->
					<textarea name="content_color" cols="60" rows="5" wrap="soft" class="area61" maxlength="<!--{$smarty.const.LTEXT_LEN}-->"
							  style="background-color:<!--{if $arrErr.content_color}--><!--{$smarty.const.ERR_COLOR|h}--><!--{/if}-->"><!--{$arrForm.content_color.value|h}--></textarea>
					<br/><span class="attention"> （上限3000文字）</span>
					<button id="red_text2" name="red_text2" title="赤字" onclick="addRedtext('content_color');return false;"><font color="#CC0033">赤字</font></button>
					<button id="black_text2" name="black_text2" title="黒字に戻す" onclick="removeRedtext('content_color');return false;">黒字に戻す</button>
					
					<br/><br/>ココに注意！<br/>
					<!--{if $arrErr.content_attention}--><span class="attention"><!--{$arrErr.content_attention}--></span><!--{/if}-->
					<textarea name="content_attention" cols="60" rows="5" wrap="soft" class="area61" maxlength="<!--{$smarty.const.LTEXT_LEN}-->"
							  style="background-color:<!--{if $arrErr.content_attention}--><!--{$smarty.const.ERR_COLOR|h}--><!--{/if}-->"><!--{$arrForm.content_attention.value|h}--></textarea>
					<br/><span class="attention"> （上限3000文字）</span>
					<button id="red_text3" name="red_text3" title="赤字" onclick="addRedtext('content_attention');return false;"><font color="#CC0033">赤字</font></button>
					<button id="black_text3" name="black_text3" title="黒字に戻す" onclick="removeRedtext('content_attention');return false;">黒字に戻す</button>
					
					<br/><br/>今回のポイント<br/>
					<!--{if $arrErr.content_add_point}--><span class="attention"><!--{$arrErr.content_add_point}--></span><!--{/if}-->
					<textarea name="content_add_point" cols="60" rows="5" wrap="soft" class="area61" maxlength="<!--{$smarty.const.LTEXT_LEN}-->"
							  style="background-color:<!--{if $arrErr.content_color}--><!--{$smarty.const.ERR_COLOR|h}--><!--{/if}-->"><!--{$arrForm.content_add_point.value|h}--></textarea>
					<br/><span class="attention"> （上限3000文字）</span>
					<button id="red_text4" name="red_text4" title="赤字" onclick="addRedtext('content_add_point');return false;"><font color="#CC0033">赤字</font></button>
					<button id="black_text4" name="black_text4" title="黒字に戻す" onclick="removeRedtext('content_add_point');return false;">黒字に戻す</button>
                </td>
            </tr>
            <tr>
                <th>お客様からのコメント</th>
                <td>
					<!--{if $arrErr.comment_customer}--><span class="attention"><!--{$arrErr.comment_customer}--></span><!--{/if}-->
						<textarea name="comment_customer" cols="60" rows="8" wrap="soft" class="area60" maxlength="<!--{$smarty.const.LTEXT_LEN}-->"
								  style="background-color:<!--{if $arrErr.comment_customer}--><!--{$smarty.const.ERR_COLOR|h}--><!--{/if}-->"><!--{$arrForm.comment_customer.value|h}--></textarea>
						<br/>
						<span class="red"> （上限3000文字）</span>
						<button id="red_text1" name="red_text1" title="赤字" onclick="addRedtext('comment_customer');return false;"><font color="#CC0033">赤字</font></button>
						<button id="black_text1" name="black_text1" title="黒字に戻す" onclick="removeRedtext('comment_customer');return false;">黒字に戻す</button>

				</td>
            </tr>


			
            <tr>
                <th>おすすめ（38文字以下）<!--{if $arrForm.prize_no.value > 15}--><span class="attention"> *</span><!--{/if}--></th>
                <td>
					<!--{if $arrErr.recommend_word}--><span class="attention"><!--{$arrErr.recommend_word}--></span><!--{/if}-->
					
					<!--{assign var="recommend_word" value="`$arrForm.recommend_word.value`"}-->
					例：30代の方にぴったりの上品青ドレス<br>
					<input type="text" name="recommend_word" value="<!--{$recommend_word}-->" class="box68" <!--{if $arrForm.prize_no.value > 15}-->maxlength="38"<!--{/if}--> <!--{if $arrErr.recommend_word}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
				</td>
            </tr>
            <tr>
                <th>おすすめ商品</th>
                <td>
					<table class='no-border' width="100%" border="0" cellspacing="0" cellpadding="0" summary=" ">
						<tr class="fs12n" style="vertical-align: bottom">
							<td width="33%">
								<!--{if $arrForm.recommend_product_image1.value}-->
								<img alt="おすすめ商品1" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image1.value}-->">
								<!--{/if}-->
								<br/>
								<input type="button" name="add_product1" value="商品1" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents//product_select.php?no=1&type=recommend', 'search', '500', '500'); " />
								<input type="button" name="del_product1" value="削除1" onclick="func_del_recommend_product(1);" />
								<input type="hidden" name="recommend_product_id1" value="<!--{$arrForm.recommend_product_id1.value|h}-->"/>
								<input type="hidden" name="recommend_product_image1" id="recommend_product_image1" value="<!--{$arrForm.recommend_product_image1.value|h}-->">
							</td>
							<td width="33%">
								<!--{if $arrForm.recommend_product_image2.value}-->
								<img alt="おすすめ商品2" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image2.value}-->">
								<!--{/if}-->
								<br/>
								<input type="button" name="add_product2" value="商品2" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents//product_select.php?no=2&type=recommend', 'search', '500', '500'); " />
								<input type="button" name="del_product1" value="削除2" onclick="func_del_recommend_product(2);" />
								<input type="hidden" name="recommend_product_id2" value="<!--{$arrForm.recommend_product_id2.value|h}-->"/>
								<input type="hidden" name="recommend_product_image2" id="recommend_product_image2" value="<!--{$arrForm.recommend_product_image2.value|h}-->">
							</td>
							<td>
								<!--{if $arrForm.recommend_product_image3.value}-->
								<img alt="おすすめ商品3" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image3.value}-->">
								<!--{/if}-->
								<br/>
								<input type="button" name="add_product3" value="商品3" onclick="win03('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/contents//product_select.php?no=3&type=recommend', 'search', '500', '500'); " />
								<input type="button" name="del_product3" value="削除3" onclick="func_del_recommend_product(3);" />
								<input type="hidden" name="recommend_product_id3" value="<!--{$arrForm.recommend_product_id3.value|h}-->"/>
								<input type="hidden" name="recommend_product_image3" id="recommend_product_image3" value="<!--{$arrForm.recommend_product_image3.value|h}-->">
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
    </form>

    <h2>登録済みベストドレッサー賞情報一覧
        <a class="btn-normal" href="">新規登録</a>
    </h2>

    <!--{if $arrErr.moveposition}-->
    <p><span class="attention"><!--{$arrErr.moveposition}--></span></p>
    <!--{/if}-->
    <!--{* ▼一覧表示エリアここから *}-->
    <form name="move" id="move" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="moveRankSet" />
        <input type="hidden" name="prize_id" value="" />
        <input type="hidden" name="moveposition" value="" />
        <input type="hidden" name="rank" value="" />
        <table class="list">
            <col width="5%" />
            <col width="15%" />
            <col width="45%" />
            <col width="5%" />
            <col width="5%" />
            <tr>
                <th>回</th>
                <th>ご利用分</th>
                <th>タイトル</th>
                <th class="edit">編集</th>
                <th class="delete">削除</th>
            </tr>
            <!--{section name=data loop=$arrPrize}-->
            <tr style="background:<!--{if $arrPrize[data].prize_id != $tpl_prize_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->;" class="center">
                <td><!--{$arrPrize[data].prize_no}--></td>
                <td><!--{$arrPrize[data].prize_date_text}--></td>
                <td><!--{$arrPrize[data].title}--></td>
                <td>
                    <!--{if $arrPrize[data].prize_id != $tpl_prize_id}-->
                    <a href="#" onclick="eccube.fnFormModeSubmit('move','pre_edit','prize_id','<!--{$arrPrize[data].prize_id|h}-->'); return false;">編集</a>
                    <!--{else}-->
                    編集中
                    <!--{/if}-->
                </td>
                <td><a href="#" onclick="eccube.fnFormModeSubmit('move','delete','prize_id','<!--{$arrPrize[data].prize_id|h}-->'); return false;">削除</a></td>
            </tr>
            <!--{sectionelse}-->
            <tr class="center">
                <td colspan="6">現在データはありません。</td>
            </tr>
            <!--{/section}-->
        </table>
    </form>
    <!--{* ▲一覧表示エリアここまで *}-->

</div>
