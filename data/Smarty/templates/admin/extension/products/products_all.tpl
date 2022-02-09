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
// URLの表示非表示切り替え
function lfnDispChange(){
	inner_id = 'switch';

	cnt = form1.item_cnt.value;
	
	if(document.getElementById('disp_url1').style.display == 'none'){
		for (i = 1; i <= cnt; i++) {
			disp_id = 'disp_url'+i;
			document.getElementById(disp_id).style.display="";
	
			disp_id = 'disp_cat'+i;
			document.getElementById(disp_id).style.display="none";
			
			document.getElementById(inner_id).innerHTML = '	URL <a href="#" onClick="lfnDispChange();"> <FONT Color="#FFFF99"> >> カテゴリ表示</FONT></a>';
		}
	}else{
		for (i = 1; i <= cnt; i++) {
			disp_id = 'disp_url'+i;
			document.getElementById(disp_id).style.display="none";
	
			disp_id = 'disp_cat'+i;
			document.getElementById(disp_id).style.display="";
			
			document.getElementById(inner_id).innerHTML = '	カテゴリ <a href="#" onClick="lfnDispChange();"> <FONT Color="#FFFF99"> >> URL表示</FONT></a>';
		}
	}

}

// == 2012.05.16 RCHJ Change - Parameter(chk_name Add) ==
// チェックボックスすべてチェック
function fnBoxChecked(chk_name, check){
    var count;
    var fm = document.form1;
    var max = fm[chk_name].length;
    if(max){
    	for(count=0; count<max; count++){
	        fm[chk_name][count].checked = check;
	    }
    }else{
    	fm[chk_name].checked = check;
    }
}

//== 2012.05.16 RCHJ Change ==
function fnUpdateSubmit(check,mode){
    /*var count;
    var check_count;
    check_count = 0;
    // formを取得
    var fm = document.form1;
    var max = fm["chk_normal_day[]"].length;
    if(max){
        for(count=0; count<max; count++){
	        if(fm["chk_normal_day[]"][count].checked) {
	            //var element=document.getElementById(fm["update_products_id[]"][count].value);
	            //var e = document.createElement("input");
	            //e.name="update_data_"+count;
	            //if (element.value.match(/[^0-9]/g)) {
	            //    alert("更新在庫数は半角数値で入力してください");
	            //    return false;
	            //}
	            //e.type="hidden";
	            //e.value=fm["update_products_id[]"][count].value + "_" + element.value;
	            //fm.appendChild(e);
	            check_count++;
	        }
	    }
    }else{
        var flag = fm["chk_normal_day[]"].checked;
        if(flag){
            //var element=document.getElementById(fm["update_products_id[]"].value);
	        //var e = document.createElement("input");
	        //e.name="update_data_"+0;
	        //if (element.value.match(/[^0-9]/g)) {
	        //    alert("更新在庫数は半角数値で入力してください");
	        //    return false;
	        //}
	        //e.type="hidden";
	        //e.value=fm["update_products_id[]"].value + "_" + element.value;
	        //fm.appendChild(e);
            check_count++;
        }
    }

    max = fm["chk_rest_day[]"].length;
    if(max){
        for(count=0; count<max; count++){
	        if(fm["chk_rest_day[]"][count].checked) {
	            check_count++;
	        }
	    }
    }else{
        var flag = fm["chk_rest_day[]"].checked;
        if(flag){
            check_count++;
        }
    }*/
    
    // 商品が選択されていない場合
    /*if(check_count == 0){
        alert("商品を選択してください");
        return false;
    }else{*/
        if(!window.confirm('更新処理を開始します')){
            return false;
        }
        document.form1['mode'].value = mode;
        document.form1.submit();
    //}
}

//================= 2012.05.16 RCHJ Add =============
	var send_date_index = 0;
	
	// delete send date box
	function deleteSendDate(){
	    if(send_date_index == 0){
			return;
	    }
	    /*if(send_date_index == 1){
	    	$('#rdo_reserved').attr("disabled", true);
	    	$('#rdo_unreserved').attr("disabled", true);
	    	
	    	$('#rdo_reserved').attr("checked", false);
	    	$('#rdo_unreserved').attr("checked", false);
	    }*/
	    
		$('#search_txt_send_date'+send_date_index).val("");
		$('#search_txt_send_date'+send_date_index).remove();
		send_date_index --;
		$('#search_txt_send_date'+send_date_index).val("");
	
		$('#search_send_date_index').val(send_date_index);
	}
	
	// add send date box
	function processSendDate(){
	    if(this.id != "search_txt_send_date"+send_date_index){
			return;
	    }

	    if(this.id == "search_txt_send_date0"){
	    	//$('#rdo_reserved').attr("disabled", false);
	    	//$('#rdo_unreserved').attr("disabled", false);
	    	
	    	$('#rdo_reserved').attr("checked", true);
	    }
	    
	    processSendDateCommon('add');
	}
	function processSendDateCommon(mode){
		send_date_index ++;
	
		var obj_value = '';
		if(mode!='add' && send_date_value && send_date_value[send_date_index]){
			obj_value = send_date_value[send_date_index];
		}
	    var obj_add = '<input type="text" name="search_txt_send_date'+send_date_index+'" id="search_txt_send_date'+send_date_index+'" value="'+obj_value+'" class="box9" readonly="readonly">';
	    $('#div_send_date').append(obj_add);
	    
	    /*var obj_add=document.createElement("INPUT");
	    obj_add.setAttribute("type","TEXT");
	    obj_add.setAttribute("name","search_txt_send_date" + send_date_index);
	    obj_add.setAttribute("value","");
	    obj_add.setAttribute("class","box9");
	    obj_add.setAttribute("readonly","readonly");
	    obj_add.setAttribute("id", "search_txt_send_date" + send_date_index)
		document.getElementById('div_send_date').appendChild(obj_add);;*/
	
		$('#search_txt_send_date'+send_date_index).datepicker({
			onSelect: processSendDate
		});
	
		$('#search_send_date_index').val(send_date_index);
	}
	
	$(function() {
		var index = $('#search_send_date_index').val();
		$('#search_txt_send_date0').datepicker({
			onSelect: processSendDate
		});
		for(var i=1; i<=index;i++){
			processSendDateCommon();
		}
	});
//==================== end ============
	
</script>
<!--★★メインコンテンツ★★-->

<div id="products" class="contents-main">  
<form name="search_form" id="search_form" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<input type="hidden" name="mode" value="search">
<!--{foreach key=key item=item from=$arrHidden}-->
<!--{if $key == 'campaign_id' || $key == 'search_mode'}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/if}-->
<!--{/foreach}-->
<input type="hidden" name="search_send_date_index" id="search_send_date_index" value="<!--{$arrHidden.search_send_date_index|default:0}-->"><!-- 2012.05.16 RCHJ Add -->
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />


						<!--検索条件設定テーブルここから-->
						<table>
							 <tr>
								<th>商品コード</th>
								<td><input type="text" name="search_product_code" value="<!--{$arrForm.search_product_code|escape}-->" size="30" class="box30" /><FONT Color="#FF0000"><br />※工事中<br />複数在庫対応になっていません。</FONT></td>
								<th>商品名</th>
								<td><input type="text" name="search_name" value="<!--{$arrForm.search_name|escape}-->" size="30" class="box30" /></td>
							</tr>
							<tr>
								<th>カテゴリ</th>
								<td>
									<select name="search_category_id" style="<!--{if $arrErr.search_category_id != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->;width: 240px;">
									<option value="">選択してください</option>
									<!--{html_options options=$arrCatList selected=$arrForm.search_category_id}-->
									</select>
								</td>
<!-- 2012.05.16 RCHJ Add -->
								<th>種別</th>
								<td>
									<!--{html_checkboxes name="search_status" options=$arrDISP selected=$arrForm.search_status}-->
								</td>
<!-- end -->
							</tr>
<!-- 2012.05.16 RCHJ Add -->
							<tr>
								<th rowspan="2">発送日<span class="attention">※</span></th>
	                            <td colspan="3">
	                                <div id="div_send_date" style="display: inline;"><span class="attention"><!--{$arrErr.search_txt_send_date0}--></span><input type="text" name="search_txt_send_date0" id="search_txt_send_date0" value="<!--{$arrForm.search_txt_send_date0}-->" class="box9" readonly="readonly" style="<!--{$arrErr.search_txt_send_date0|sfGetErrorColor}-->"></div>
                                    <a class="btn-normal" href="javascript:;" name="btn_del_date" onclick="deleteSendDate();">削除</a>
	                            </td>
                            </tr>
                            <tr>
                            	<td colspan="3">
	                                <input type="radio" name="search_rdo_senddate" id="rdo_reserved" value="1" <!--{if $arrForm.search_rdo_senddate == 1}-->checked="checked"<!--{/if}-->><label for="rdo_reserved">予約が入っている商品</label>&nbsp;
	                                <input type="radio" name="search_rdo_senddate" id="rdo_unreserved" value="2" <!--{if $arrForm.search_rdo_senddate == 2}-->checked="checked"<!--{/if}-->><label for="rdo_unreserved">予約が入っていない商品</label>
	                                <input type="radio" name="search_rdo_senddate" id="rdo_impossible" value="3" <!--{if $arrForm.search_rdo_senddate == 3}-->checked="checked"<!--{/if}-->><label for="rdo_impossible">予約不可に設定された商品</label>&nbsp;
	                            </td>
                            </tr>
                            <tr>
								<th>平日、休業日の設定</th>
	                            <td colspan="3">
	                                <input type="checkbox" name="search_normal_day" id="search_normal_day" value="1" <!--{if $arrForm.search_normal_day == 1}-->checked="checked"<!--{/if}-->><label for="search_normal_day">平日の予約可商品</label>&nbsp;
	                                <input type="checkbox" name="search_rest_day" id="search_rest_day" value="1" <!--{if $arrForm.search_rest_day == 1}-->checked="checked"<!--{/if}-->><label for="search_rest_day">休業日の予約不可商品</label>
	                            </td>
                            </tr>
<!-- End -->
						</table>

								<table>
									<tr>
										<th>検索結果表示件数
											<!--{assign var=key value="search_page_max"}-->
											<span class="attention"><!--{$arrErr[$key]}--></span>
											<select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
											<!--{html_options options=$arrPageMax selected=$arrForm.search_page_max}-->
											</select> 件
										</th>
                                    </tr>
								</table>
                                <div class="btn-area">
                                    <ul>
                                        <li><a class="btn-action" href="javascript:;" onclick="document.search_form.submit(); return false;"><span class="btn-next">この条件で検索する</span></a></li>
                                    </ul>
                                </div>
</form>
</div>
<!--★★メインコンテンツ★★-->

<!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete')}-->

<!--★★検索結果一覧★★-->

<div id="products" class="contents-main">  
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="wed_productid" value="">
<input type="hidden" name="wed_classcategory_id1" value="">
<input type="hidden" name="wed_classcategory_id2" value="">

<input type="hidden" name="mode" value="search">
<input type="hidden" name="product_id" value="">
<input type="hidden" name="category_id" value="">
<!--{foreach key=key item=item from=$arrHidden}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->	
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <h2>検索結果一覧　</h2>
            <div class="btn">
                <span class="attention"><!--検索結果数--><!--{$tpl_linemax}-->件</span>&nbsp;が該当しました。
                <!--検索結果-->
                <!--{if $smarty.const.ADMIN_MODE == '1'}-->
                <!--{/if}-->
            </div>
		<!--{if count($arrProducts) > 0}-->		
            <!--{include file=$tpl_pager}-->

					<!--検索結果表示テーブル-->
					<table class="list">
						<!-- ヘッダ -->
						<tr>
							<th>商品画像</th>
							<th>商品コード</th>
							<th>商品名</th>
							<th>平日の予約可<br />
                                <span class="btn-normal"><a href="javascript:;" name="btn01" onclick="fnBoxChecked('chk_normal_day[]', true);">全て選択</a></span>
                                <span class="btn-normal"><a href="javascript:;" name="btn01" onclick="fnBoxChecked('chk_normal_day[]', false);">全て解除</a></span>
                            </th>
							<th>休業日の予約不可<br />
                                <span class="btn-normal"><a href="javascript:;" name="btn01" onclick="fnBoxChecked('chk_rest_day[]', true);">全て選択</a></span>
                                <span class="btn-normal"><a href="javascript:;" name="btn01" onclick="fnBoxChecked('chk_rest_day[]', false);">全て解除</a></span>
                            </th>
							<th>予約管理ページ </th>
						</tr>
						<!-- ヘッダ -->
						<!--{section name=cnt loop=$arrProducts}-->
						<!--▼商品<!--{$smarty.section.cnt.iteration}-->-->
						<!--{assign var=status value="`$arrProducts[cnt].status`"}-->
						<tr bgcolor="<!--{$arrPRODUCTSTATUS_COLOR[$status]}-->">
							<td class="center">
							<input type="hidden" name="hdn_product[]" value="<!--{$arrProducts[cnt].product_id}-->_<!--{$arrProducts[cnt].classcategory_id1}-->_<!--{$arrProducts[cnt].classcategory_id2}-->">
							<!--{if $arrProducts[cnt].main_list_image != ""}-->
								<!--{assign var=image_path value="`$arrProducts[cnt].main_list_image`"}-->
							<!--{else}-->
								<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
							<!--{/if}-->
                            <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65">
                            </td>
							<td><!--{$arrProducts[cnt].product_code|escape|default:"-"}--></td>
							<td><!--{$arrProducts[cnt].name|escape}--></td>
							<td class="center">
								<input type="checkbox" name="chk_normal_day[]" value="<!--{$arrProducts[cnt].product_id}-->_<!--{$arrProducts[cnt].classcategory_id1}-->_<!--{$arrProducts[cnt].classcategory_id2}-->" <!--{if $arrProducts[cnt].order_enable_flg eq '1'}-->checked="checked"<!--{/if}-->>
							</td>
							<td class="center">
								<!--{if $arrProducts[cnt].order_disable_flg eq '1'}-->
								<input type="checkbox" name="chk_rest_day[]" value="<!--{$arrProducts[cnt].product_id}-->_<!--{$arrProducts[cnt].classcategory_id1}-->_<!--{$arrProducts[cnt].classcategory_id2}-->" checked="checked">
								<!--{else}-->
								<input type="checkbox" name="chk_rest_day[]" value="<!--{$arrProducts[cnt].product_id}-->_<!--{$arrProducts[cnt].classcategory_id1}-->_<!--{$arrProducts[cnt].classcategory_id2}-->" >
								<!--{/if}-->
							</td>
							<td class="center">
								<a href="<!--{$smarty.const.URL_DIR}-->" onclick="fnChangeAction('./products_reserved_mng.php'); fnModeSubmit('pre_edit', 'product_id', <!--{$arrProducts[cnt].product_id}-->); return false;"  >予約管理</a>
							</td>
<!-- End -->
						</tr>
						<!--▲商品<!--{$smarty.section.cnt.iteration}-->-->
						<!--{/section}-->
						<input type="hidden" name="item_cnt" value="<!--{$arrProducts|@count}-->">
					</table>
                    <div class="btn-area">
                        <ul>
                            <li><a class="btn-action" href="javascript:;" onclick="fnUpdateSubmit(true, 'update'); return false;"><span class="btn-next">この内容で登録する</span></a></li>
                        </ul>
                    </div>
		<!--{/if}-->
</form>
</div>
<!--★★検索結果一覧★★-->		
<!--{/if}-->
