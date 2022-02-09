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
	$(function() {
		$('#txt_date').datepicker();
		$('#txt_date_b').datepicker();
		<!--{foreach from=$arrHistoryFront key=key item=row}-->
		$('#txt_date<!--{$row.history_id}-->').datepicker();
		<!--{/foreach}-->
		<!--{foreach from=$arrHistoryBack key=key item=row}-->
		$('#txt_date<!--{$row.history_id}-->').datepicker();
		<!--{/foreach}-->
	});

	function jsFunction(message1,message2)
	{
		document.form1.drawdata.value =  message1;
		document.form1.drawdata_b.value =  message2;
	}

	function editableObject(id){
		var frm = document.forms["form1"];
		var btn_update_name = "btn_update"+id;
		var btn_edit_name = "btn_edit"+id;

		var opt_evalute = "chk_evaluate"+id;
		for (var i=0; i < frm[opt_evalute].length; i++) {
	        frm[opt_evalute][i].disabled = false;
	    }
	    document.getElementById("txt_date"+id).disabled = false;
	    document.getElementById("chk_inspector"+id).disabled = false;
	    document.getElementById("chk_inspect_place"+id).disabled = false;
	    document.getElementById("chk_diameter"+id).disabled = false;
	    document.getElementById("txt_size"+id).disabled = false;
	    document.getElementById("chk_inspect_status"+id).disabled = false;
	    document.getElementById("txt_remarks"+id).disabled = false;
		/*$("#txt_date"+id).attr("disabled" , false);
		$("#chk_inspector"+id).attr("disabled" , false);
		$("#chk_inspect_place"+id).attr("disabled" , false);
		$("#chk_diameter"+id).attr("disabled" , false);
		$("#txt_size"+id).attr("disabled" , false);
		$("#chk_inspect_status"+id).attr("disabled" , false);
		$("#txt_remarks"+id).attr("disabled" , false);*/
        document.getElementById(btn_update_name).style.display = "block";
//		frm[btnupdate_name].style.display = "block";
        document.getElementById(btn_edit_name).style.display = "none";
//		frm[btn_edit_name].style.display = "none";
		
		return true;
	}

	function fnCheckInput(){
		var direction = "front";
		var bln_front_empty = true;
		var bln_back_empty = true;
        
        // canvasの情報を取得
        const front_canvas = $('#front_canvas')[0].toDataURL('image/jpg');;
        const back_canvas  = $('#back_canvas')[0].toDataURL('image/jpg');;
        $('#front_canvas_data').val(front_canvas);
        $('#back_canvas_data').val(back_canvas);

        var draw_history = document.form1.draw_history.value;
        var draw_history_old = document.form1.draw_history_old.value;
		
		if(!fnIsEmpty(direction)){
			if(!fnCheckInputValue(direction)){
				return;
			}
			//if(document.form1.drawdata.value == ""){
            if( draw_history === "" || draw_history === draw_history_old )
            {
				if(!window.confirm("検品画像（正面）の変更がありません。\n登録を続行しますか？")){
					return;	
				}
			}
			bln_front_empty = false;
		}
		
		direction = "back";
		if(!fnIsEmpty(direction)){
			if(!fnCheckInputValue(direction)){
				return;
			}
			//if(document.form1.drawdata_b.value == ""){
            if( draw_history.value === "" || draw_history === draw_history_old )
            {
				if(!window.confirm("検品画像（背面）の変更がありません。\n登録を続行しますか？")){
					return;
				}
			}
			bln_back_empty = false;
		}
        
        if(bln_front_empty && bln_back_empty){
			alert("登録データがありません。");
    
			return;
		}
        
		fnModeSubmit('regist', 'select_id', '');
	}

	function fnCheckInputValue(direction){
		var frm = document.forms["form1"];
		var after = (direction == "front")?"":"_b";
		var pre_msg = (direction == "front")?"（正面）":"（背面）";
		var msg = pre_msg + "データを入力してください。";
		
		var value = frm["txt_date"+after].value;
		if(value == "" || value == null || value == "undefiend"){
			alert("年月日" + msg);
			frm["txt_date"+after].focus();

			return false;
		}
		
		value = frm["chk_inspector"+after].value;
		if(value == "" || value == null || value == "undefiend"){
			alert("検品者" + msg);
			frm["chk_inspector"+after].focus();

			return false;
		}

		value = frm["chk_inspect_place"+after].value;
		if(value == "" || value == null || value == "undefiend"){
			alert("場所" + msg);
			frm["chk_inspect_place"+after].focus();

			return false;
		}

		/*value = frm["txt_size"+after].value;
		if(value == "" || value == null || value == "undefiend"){
			alert("大きさ" + msg);
			frm["txt_size"+after].focus();

			return false;
		}
		if(isNaN(value)){
			alert("大きさは数字で入力してください。");
			frm["txt_size"+after].select();
			frm["txt_size"+after].focus();

			return false;
		}*/

		value = frm["chk_inspect_status"+after].value;
		if(value == "" || value == null || value == "undefiend"){
			alert("状態" + msg);
			frm["chk_inspect_status"+after].focus();

			return false;
		}

		var bln_chk = false;
		for (var i=0; i < frm["chk_evaluate"+after].length; i++) {
			value = frm["chk_evaluate"+after][i].checked;
			if(value){
				bln_chk = true;

				break;
			}
	    }
		if(!bln_chk){
			alert("評価" + msg);

			return false;
		}
		
		return true;
	}

	function fnIsEmpty(direction){
		var frm = document.forms["form1"];
		var after = (direction == "front")?"":"_b";

		var value = frm["chk_inspector"+after].value;
		if(!(value == "" || value == null || value == "undefiend")){
    		return false;
    	}

		value = frm["chk_inspect_place"+after].value;
		if(!(value == "" || value == null || value == "undefiend")){
    		return false;
    	}

    	value = frm["txt_size"+after].value;
    	if(!(value == "" || value == null || value == "undefiend")){
    		return false;
    	}

    	value = frm["chk_inspect_status"+after].value;
    	if(!(value == "" || value == null || value == "undefiend")){
    		return false;
    	}

    	value = frm["chk_evaluate"+after].value;
    	if(!(value == "" || value == null || value == "undefiend")){
    		return false;
    	}
    	
    	return true;
	}
    </script>

<!--★★メインコンテンツ★★-->

<div id="products" class="contents-main">
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />  
<input type="hidden" name="mode" value="search">
						<!--検索条件設定テーブルここから-->
						<table>
							<tr>
								<th>商品コード</th>
								<td>
								<span class="attention"><!--{$arrErr.search_product_code}--></span>
								<input type="text" name="search_product_code" value="<!--{$arrForm.search_product_code.value|escape}-->" size="30" class="box30" />
								</td>
							</tr>
							<tr>
								<th>種類</th>
                                <td>
                                <!--{html_radios name='search_product_kind' options=$arrProductKind selected=$arrForm.search_product_kind.value separator='&nbsp;'}-->
                                <input type="hidden" name="hdn_product_kind" value="<!--{$arrForm.search_product_kind.value}-->">
                                </td>
							</tr>
						</table>
                        <div class="btn-area">
                            <ul>
                                <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('search', '', ''); return false;"><span class="btn-next">この条件で検索する</span></a></li>
                            </ul>
                        </div>
<!--★★メインコンテンツ★★-->

<!--{if count($arrErr) == 0 and $smarty.post.mode != ''}-->
<input type="hidden" name="select_id" id="select_id" value="">
<input type="hidden" name="place_all_id" id="place_all_id" value="<!--{$tpl_place_all_id}-->">
<input type="hidden" name="product_id" id="product_id" value="<!--{$arrProducts.product_id}-->">
<input type="hidden" name="product_code" id="product_code" value="<!--{$arrProducts.product_code}-->">
<input type="hidden" name="product_flag" id="product_flag" value="<!--{$arrProducts.product_flag}-->">

<input type="hidden" name="front_image_path" id="front_image_path" value="<!--{$arrImagePaths.image_front}-->">
<input type="hidden" name="back_image_path" id="back_image_path" value="<!--{$arrImagePaths.image_back}-->">

<input type="hidden" name="drawdata" value = ""/>
<input type="hidden" name="drawdata_b" value = ""/>
<input type="hidden" name="front_canvas_data" id="front_canvas_data" value = ""/>
<input type="hidden" name="back_canvas_data"  id="back_canvas_data" value = ""/> 
<input type="hidden" name="draw_history" id="draw_history" value=""/> 
<input type="hidden" name="draw_history_old" id="draw_history_old" value="<!--{$arrHistoryFront[0].draw_data|escape}-->"/> 

        <h2>検索結果一覧　</h2>
            <div class="btn">
                <span class="attention"><!--{if count($arrProducts) == 0}-->0<!--{else}-->1<!--{/if}-->件</span>&nbsp;が該当しました。
            </div>  
       <!--{if count($arrProducts) > 0}-->

   
			<!--検索結果表示テーブル-->
            <p>新規状態入力</p>
			<table>
                    <col width="5%" />
                    <col width="10%" />
                    <col width="8%" />
                    <col width="15%" />
                    <col width="4%" />
                    <col width="4%" />
                    <col width="19%" />  
                    <col width="10%" />
                    <col width="15%" />
                    <col width="10%" />
                    
						<!--{if count($arrErrRegist) > 0}-->
						<tr><td colspan="10">
							<!--{foreach key=key item=item from=$arrErrRegist}-->
							<span class="attention"><!--{$item}--></span>
                        	<!--{/foreach}-->
						</td></tr>
						<!--{/if}-->
						<tr class="center">
							<th></th>
							<th>年月日</th>
							<th>検品者</th>
							<th>場所</th>
							<th colspan="2">大きさ(cm)</th>
							<th>状態</th>
							<th>備考</th>
							<th>評価</th>
							<th>登録</th>
						</tr>
						<tr>
							<th>正面</th>
							<td>
								<input type="text" name="txt_date" id="txt_date" value="<!--{$arrForm.txt_date.value}-->" class="box9" readonly="readonly">
							</td>
							<td>
								<select name="chk_inspector" style="<!--{$arrErrRegist.chk_inspector|sfGetErrorColor}-->">
									<!--{html_options options=$arrInspector selected=$arrForm.chk_inspector.value}-->
								</select>
							</td>
							<td>
								<select name="chk_inspect_place" style="<!--{$arrErrRegist.chk_inspect_place|sfGetErrorColor}-->">
									<!--{html_options options=$arrInspectPlace selected=$arrForm.chk_inspect_place.value}-->
								</select>
							</td>
							<td>
								<input type="checkbox" name="chk_diameter" id="chk_diameter" value="1" <!--{if $arrForm.chk_diameter.value == 1}-->checked="checked"<!--{/if}--> ><br><label for="chk_diameter">直径</label>
							</td>
							<td>
								<input type="text" name="txt_size" id="txt_size" value="<!--{$arrForm.txt_size.value}-->" class="box3" style="ime-mode: disabled; <!--{$arrErrRegist.txt_size|sfGetErrorColor}-->">
							</td>
							<td>
								<select name="chk_inspect_status" style="<!--{$arrErrRegist.chk_inspect_status|sfGetErrorColor}-->">
									<!--{html_options options=$arrInspectStatus selected=$arrForm.chk_inspect_status.value}-->
								</select>
							</td>
							<td>
								<input type="text" name="txt_remarks" id="txt_remarks" value="<!--{$arrForm.txt_remarks.value}-->" class="box10">
							</td>
							<td>
								<!--{html_radios name='chk_evaluate' options=$arrEvaluate selected=$arrForm.chk_evaluate.value separator='&nbsp;'}-->
							</td>
							<td rowspan="2">
                                <span><a name="btn_regist" id="btn_regist" href="javascript:;" onclick="fnCheckInput();">登録</a></span>
							</td>
						</tr>
						<tr>
							<th>背面</th>
							<td>
								<input type="text" name="txt_date_b" id="txt_date_b" value="<!--{$arrForm.txt_date_b.value}-->" class="box9" readonly="readonly">
							</td>
							<td>
								<select name="chk_inspector_b" style="<!--{$arrErrRegist.chk_inspector_b|sfGetErrorColor}-->">
									<!--{html_options options=$arrInspector selected=$arrForm.chk_inspector_b.value}-->
								</select>
							</td>
							<td>
								<select name="chk_inspect_place_b" style="<!--{$arrErrRegist.chk_inspect_place_b|sfGetErrorColor}-->">
									<!--{html_options options=$arrInspectPlace selected=$arrForm.chk_inspect_place_b.value}-->
								</select>
							</td>
							<td>
								<input type="checkbox" name="chk_diameter_b" id="chk_diameter_b" value="1" <!--{if $arrForm.chk_diameter_b.value == 1}-->checked="checked"<!--{/if}--> ><br><label for="chk_diameter_b">直径</label>
							</td>
							<td>
								<input type="text" name="txt_size_b" id="txt_size_b" value="<!--{$arrForm.txt_size_b.value}-->" class="box3" style="ime-mode: disabled; <!--{$arrErrRegist.txt_size_b|sfGetErrorColor}-->">
							</td>
							<td>
								<select name="chk_inspect_status_b" style="<!--{$arrErrRegist.chk_inspect_status_b|sfGetErrorColor}-->">
									<!--{html_options options=$arrInspectStatus selected=$arrForm.chk_inspect_status_b.value}-->
								</select>
							</td>
							<td>
								<input type="text" name="txt_remarks_b" id="txt_remarks_b" value="<!--{$arrForm.txt_remarks_b.value}-->" class="box10">
							</td>
							<td>
								<!--{html_radios name='chk_evaluate_b' options=$arrEvaluate selected=$arrForm.chk_evaluate_b.value separator='&nbsp;'}-->
							</td>
						</tr>
					</table>
<!--{*
					<table>
                    <col width="26%" />
                    <col width="31%" />
                    <col width="12%" />
                    <col width="31%" />
						<tr class="center">
							<th><!--{$arrProductKind[$arrForm.search_product_kind.value]}--></th>
							<th><!--{$arrProductKind[$arrForm.search_product_kind.value]}-->正面図</th>
							<th>ツール</th>
							<th><!--{$arrProductKind[$arrForm.search_product_kind.value]}-->背面図</th>
						</tr>
						<tr>
							<td>
								<b><span style="font-size: medium;"><!--{$arrProducts.product_code}--></span></b><br>
								<!--{if $arrForm.search_product_kind.value == $smarty.const.BAG_INSPECT_IMAGE_TYPE}-->
								<img width="<!--{$smarty.const.INSPECT_IMAGE_THUMB_WIDTH}-->px" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProducts.sub_image1}-->" alt="<!--{$arrProducts.name}-->"><br>
                                
								<!--{else}-->
								<img width="<!--{$smarty.const.INSPECT_IMAGE_THUMB_WIDTH}-->px" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProducts.main_image}-->" alt="<!--{$arrProducts.name}-->"><br>
								<!--{/if}-->
								<span style="font-size: medium;"><!--{$arrProducts.name}--></span>
							</td>
							<td colspan="3">
                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="640px" height="300px" id="main">
					            <param name="movie" value="<!--{$smarty.const.URL_DIR}-->ChlFApkIyT8eBiMz/products/main.swf" />
					            <param name="quality" value="high" />
					            <param name="allowScriptAccess" value="always" />
					            <param name="flashvars" value="front_image=<!--{$smarty.const.URL_DIR}--><!--{$arrImagePaths.image_front}-->&back_image=<!--{$smarty.const.URL_DIR}--><!--{$arrImagePaths.image_back}-->" />
					            <embed src="<!--{$smarty.const.URL_DIR}-->ChlFApkIyT8eBiMz/products/main.swf?front_image=<!--{$smarty.const.URL_DIR}--><!--{$arrImagePaths.image_front}-->&back_image=<!--{$smarty.const.URL_DIR}--><!--{$arrImagePaths.image_back}-->"
					            	quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="100%" height="300px" allowScriptAccess="always" showLiveConnect="true" name="main"></embed>
					            </object>

                            </td>
						</tr>
					</table>
*}-->
<!-- canvas test -->
<table>
<col width="26%" />
<col width="31%" />
<col width="8%" />
<col width="31%" />
	<tr class="center">
		<th><!--{$arrProductKind[$arrForm.search_product_kind.value]}--></th>
		<th class="right"><!--{$arrProductKind[$arrForm.search_product_kind.value]}-->正面図</th>
		<th class="center">ツール</th>
		<th><!--{$arrProductKind[$arrForm.search_product_kind.value]}-->背面図</th>
	</tr>
	<tr>
		<td>
			<b><span style="font-size: medium;"><!--{$arrProducts.product_code}--></span></b><br>
			<!--{if $arrForm.search_product_kind.value == $smarty.const.BAG_INSPECT_IMAGE_TYPE}-->
			<img width="<!--{$smarty.const.INSPECT_IMAGE_THUMB_WIDTH}-->px" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProducts.sub_image1}-->" alt="<!--{$arrProducts.name}-->"><br>
            
			<!--{else}-->
			<img width="<!--{$smarty.const.INSPECT_IMAGE_THUMB_WIDTH}-->px" src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProducts.main_image}-->" alt="<!--{$arrProducts.name}-->"><br>
			<!--{/if}-->
			<span style="font-size: medium;"><!--{$arrProducts.name}--></span>
		</td>
		<td>
            <!--
            <label>front image</label><img id="front_image" src="<!--{$smarty.const.URL_DIR}--><!--{$arrImagePaths.image_front}-->">
            <label>back image</label><img id="back_image" src="<!--{$smarty.const.URL_DIR}--><!--{$arrImagePaths.image_back}-->">
            -->
            <input id="front_image" type="hidden" value="<!--{$smarty.const.URL_DIR}--><!--{$arrImagePaths.image_front}-->">
            <input id="back_image" type="hidden" value="<!--{$smarty.const.URL_DIR}--><!--{$arrImagePaths.image_back}-->">

            <canvas id="front_canvas" width="300" height="300"></canvas>
        </td>
        <td>
            <ul class="canvas_tool center">
                <li><input type="radio" name="color" id="color_red" checked="checked">赤</li>
                <li><input type="radio" name="color" id="color_blue">青</li>

                <li><input type="radio" name="draw_type" id="straight" checked="checked">直線</li>
                <li><input type="radio" name="draw_type" id="circle">円</li>
                
                <li><button id="clear" type="button">クリア</button></li>
                <li><button id="one_back" type="button">一つ戻る</button></li>
            </ul>
        </td>
        <td>
            <canvas id="back_canvas" width="300" height="300"></canvas>
        </td>
	</tr>
</table>
                    
				<p>過去状態</p>
					<table>
                    <col width="5%" />
                    <col width="10%" />
                    <col width="8%" />
                    <col width="15%" />
                    <col width="4%" />
                    <col width="4%" />
                    <col width="19%" />  
                    <col width="10%" />
                    <col width="9%" />
                    <col width="8%" />
                    <col width="8%" />
						<tr class="center">
							<th></th>
							<th>年月日</th>
							<th>検品者</th>
							<th>場所</th>
							<th colspan="2">大きさ(cm)</th>
							<th>状態</th>
							<th>備考</th>
							<th>評価</th>
							<th>変更</th>
							<th>削除</th>
						</tr>
						<!--{foreach key=key item=row from=$arrHistoryFront}-->
						<!--{assign var=id value=$row.history_id}-->
						<tr>
							<!--{if $key == 0}-->
							<td rowspan="<!--{$tpl_front_history_count}-->">正面</td>
							<!--{/if}-->
							<td>
								<input type="text" name="txt_date<!--{$id}-->" id="txt_date<!--{$id}-->" value="<!--{$row.inspect_date}-->" class="box9" disabled="disabled" style="background-color:white" readonly="readonly">
							</td>
							<td>
								<select name="chk_inspector<!--{$id}-->" id="chk_inspector<!--{$id}-->" disabled="disabled" style="background-color:white">
									<!--{html_options options=$arrInspector selected=$row.inspector_id}-->
								</select>
							</td>
							<td>
								<select name="chk_inspect_place<!--{$id}-->" id="chk_inspect_place<!--{$id}-->" disabled="disabled" style="background-color:white">
									<!--{html_options options=$arrInspectPlace selected=$row.place_id}-->
								</select>
							</td>
							<td>
								<input type="checkbox" name="chk_diameter<!--{$id}-->" id="chk_diameter<!--{$id}-->" value="1" <!--{if $row.diameter_flg == 1}-->checked="checked"<!--{/if}--> disabled="disabled" style="background-color:white"><br><label for="chk_diameter<!--{$id}-->">直径</label>
							</td>
							<td>
								<input type="text" name="txt_size<!--{$id}-->" id="txt_size<!--{$id}-->" value="<!--{$row.defect_size}-->" class="box3" disabled="disabled" style="ime-mode: disabled; background-color:white">
							</td>
							<td>
								<select name="chk_inspect_status<!--{$id}-->" id="chk_inspect_status<!--{$id}-->" disabled="disabled" style="background-color:white">
									<!--{html_options options=$arrInspectStatus selected=$row.status_id}-->
								</select>
							</td>
							<td>
								<input type="text" name="txt_remarks<!--{$id}-->" id="txt_remarks<!--{$id}-->" value="<!--{$row.remarks}-->" class="box10" disabled="disabled" style="background-color:white">
							</td>
							<td>
								<!--{assign var=name value="chk_evaluate`$id`"}-->
								<!--{html_radios name=$name id=$name options=$arrEvaluate selected=$row.evaluat_id separator='&nbsp;' disabled="disabled" style="background-color:white"}-->
							</td>
							<td>
								
								
                                <span ><a id = "btn_update<!--{$id}-->" name="btn_update<!--{$id}-->" href="javascript:;" onclick="fnModeSubmit('update', 'select_id', '<!--{$id}-->');" style="display:none">変更</a></span>
                                <span><a id = "btn_edit<!--{$id}-->" name = "btn_edit<!--{$id}-->" href="javascript:;" onclick="editableObject('<!--{$id}-->');">編集</a></span>
							</td>
							<td>
                                <span class="icon_delete"><a name="btn_delete<!--{$id}-->" href="javascript:;" onclick="fnModeSubmit('delete', 'select_id', '<!--{$id}-->');">削除</a></span>
							</td>
						</tr>
						<!--{/foreach}-->
						
						<!--{foreach key=key item=row from=$arrHistoryBack}-->
						<!--{assign var=id value=$row.history_id}-->
						<tr>
							<!--{if $key == 0}-->
							<th rowspan="<!--{$tpl_back_history_count}-->">背面</th>
							<!--{/if}-->
							<td>
								<input type="text" name="txt_date<!--{$id}-->" id="txt_date<!--{$id}-->" value="<!--{$row.inspect_date}-->" class="box9" disabled="disabled" style="background-color:white" readonly="readonly">
							</td>
							<td>
								<select name="chk_inspector<!--{$id}-->" id="chk_inspector<!--{$id}-->" disabled="disabled" style="background-color:white">
									<!--{html_options options=$arrInspector selected=$row.inspector_id}-->
								</select>
							</td>
							<td>
								<select name="chk_inspect_place<!--{$id}-->" id="chk_inspect_place<!--{$id}-->" disabled="disabled" style="background-color:white">
									<!--{html_options options=$arrInspectPlace selected=$row.place_id}-->
								</select>
							</td>
							<td>
								<input type="checkbox" name="chk_diameter<!--{$id}-->" id="chk_diameter<!--{$id}-->" value="1" <!--{if $row.diameter_flg == 1}-->checked="checked"<!--{/if}--> disabled="disabled" style="background-color:white"><br><label for="chk_diameter<!--{$id}-->">直径</label>
							</td>
							<td>
								<input type="text" name="txt_size<!--{$id}-->" id="txt_size<!--{$id}-->" value="<!--{$row.defect_size}-->" class="box3" disabled="disabled" style="ime-mode: disabled; background-color:white">
							</td>
							<td>
								<select name="chk_inspect_status<!--{$id}-->" id="chk_inspect_status<!--{$id}-->" disabled="disabled" style="background-color:white">
									<!--{html_options options=$arrInspectStatus selected=$row.status_id}-->
								</select>
							</td>
							<td>
								<input type="text" name="txt_remarks<!--{$id}-->" id="txt_remarks<!--{$id}-->" value="<!--{$row.remarks}-->" class="box10" disabled="disabled" style="background-color:white">
							</td>
							<td>
                                <!--20200611 ishibahi valueに""を付与-->
								<!--{assign var=name value="chk_evaluate`$id`"}-->
								<!--{html_radios name=$name id=$name options=$arrEvaluate selected=$row.evaluat_id separator='&nbsp;' disabled="disabled" style="background-color:white"}-->
							</td>
							<td>
								<span ><a id = "btn_update<!--{$id}-->" name="btn_update<!--{$id}-->" href="javascript:;" onclick="fnModeSubmit('update', 'select_id', '<!--{$id}-->');" style="display:none">変更</a></span>
                                <span><a id = "btn_edit<!--{$id}-->" name = "btn_edit<!--{$id}-->" href="javascript:;" onclick="editableObject('<!--{$id}-->');">編集</a></span>
							</td>
							<td>
								<span class="icon_delete"><a name="btn_delete<!--{$id}-->" href="javascript:;" onclick="fnModeSubmit('delete', 'select_id', '<!--{$id}-->');">削除</a></span>
							</td>
						</tr>
						<!--{/foreach}-->
					</table>  
                       <div class="btn-area">
                        <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('', 'select_id', '');"><span class="btn-prev">戻る</span></a></li>
                        </div>
                        
					<table>
                    <col width="26%" />
                    <col width="29%" />
                    <col width="45%" />
						<tr>
							<th>グレード</th>
							<th>グレード履歴</th>
							<th>グレード自動判定</th>
						</tr>
						<tr>
							<td>
								<!--<img alt="<!--{$arrAutoResult.product_grade}-->" src="<!--{$TPL_DIR}-->img/grade_icon<!--{$arrAutoResult.product_grade|string_format:"%02d"}-->.gif">-->
                                <img alt="<!--{$arrAutoResult.product_grade}-->" src="/user_data/packages/wanpi/img/grade_icon<!--{$arrAutoResult.product_grade|string_format:"%02d"}-->.gif"><!-- ishibashi -->
							</td>
							<td>
								<!--{foreach key=key item=row from=$arrGradeHistory}-->
								<div style="margin: 4px;"><span>・<!--{$row.create_date|date_format:"%Y年%m月%d日"}--></span>&nbsp;<!--{$arrGradeName[$row.grade]}-->（<span class="attention"><!--{$row.remark}--></span>）</div>
								<!--{/foreach}-->
							</td>
							<td>
                                <!--//::N00126 change 20140312-->
								<div style="margin: 4px;"><span class="attention">※機能停止中⇒</span><span>・あと<!--{$arrAutoResult.remain_order_count}-->回使用したら、1つランクダウンします。</span></div>
                                <!--{if $arrGrade[$arrProducts.product_flag] == $smarty.const.GRADE_VERY_GOOD}-->
                                <div style="margin: 4px;"><span class="attention">※機能停止中⇒</span><span>・検品の評価が1つ以上ついたら、1つランクダウンします。</span></div>
								<!--{/if}-->
					            <div style="margin: 4px;"><span class="attention">※機能停止中⇒</span><span>・「目立つ」の評価が1つ以上ついたら、2つランクダウンします。</span></div>
                    			<div style="margin: 4px;"><span class="attention">※機能停止中⇒</span><span>・「全体」の場所で「やや目立つ」の評価が1つついたら、1つランクダウンします。</span></div>
								<!--{if $arrAutoResult.life_limit}-->
								<div style="margin: 4px;"><span class="attention">※機能停止中⇒</span><span class="attention">・寿命が近づいています。</span></div>
								<!--{/if}-->
                                <!--//::N00126 end 20140312-->
							</td>
						</tr>
					</table>             
		<!--{/if}-->   
<!--★★検索結果一覧★★-->
<!--{/if}-->
</form>

</div>
<script>
    $(function(){
        // 描写用
        let oldX, oldY, moveX, moveY, pX, pY = null; // 始点、途中、終点の初期化
        let canvas_mouse_event = false;
        let stored_lines = [];
        let color        = '#f00';
        let draw_type    = 'straight';

        // 正面用
        let front_canvas        = document.getElementById('front_canvas');
        let front_ctx           = front_canvas.getContext( '2d' );
        let front_canvas_width  = 300; 
        let front_canvas_height = 300; 
        let front_image         = new Image();
        
        // 背面用
        let back_canvas        = document.getElementById('back_canvas');
        let back_ctx           = back_canvas.getContext( '2d' );
        let back_canvas_width  = 300; 
        let back_canvas_height = 300; 
        let back_image         = new Image();

        // 初期化
        front_image.src         = $('#front_image').val();
        front_image.onload = function(){
            front_ctx.drawImage( front_image,0,0,front_canvas_width,front_image.height * front_canvas_width / front_image.width);
        }
        back_image.src          = $('#back_image').val();
        back_image.onload = function(){
            back_ctx.drawImage( back_image,0,0,back_canvas_width,back_image.height * back_canvas_width / back_image.width);
        }

        // マウスダウン時
        front_canvas.onmousedown = function(e){
            oldX = e.offsetX;
            oldY = e.offsetY;
            canvas_mouse_event = true;
        };
        back_canvas.onmousedown = function(e){
            oldX = e.offsetX;
            oldY = e.offsetY;
            canvas_mouse_event = true;
        };
        
        front_canvas.onmousemove = function(e){
            canvas_onmousemove(e,front_ctx, 'front');
        };
        back_canvas.onmousemove = function(e){
            canvas_onmousemove(e,back_ctx, 'back');
        };
        // 描写履歴を取得
        window.onload = (event) => {
            let draw_history = $('#draw_history').val();
            if ( draw_history !== '' )
            {
                stored_lines = JSON.parse( draw_history );
                canvas_mouse_event = true;
                canvas_onmousemove(event,front_ctx,'front');
                canvas_onmousemove(event,back_ctx,'back');
                canvas_mouse_event = false;
            }
        }

        function canvas_onmousemove( e, obj, direction )
        {
            if ( canvas_mouse_event === true )
            {
                redraw(obj,direction);
                moveX = e.offsetX;
                moveY = e.offsetY;
                obj.strokeStyle = color;
                obj.lineWidth   = 2;
                obj.lineJoin    = "round";
                obj.lineCap     = "round";
                obj.beginPath();
                if ( draw_type === 'straight' )
                {
                    obj.moveTo(oldX,oldY);
                    obj.lineTo(moveX,moveY);
                }
                else
                {
                    let tmp = Math.pow( ( moveX - oldX ), 2 ) + Math.pow( ( moveY - oldY ), 2 );
                    //front_ctx.arc( ( ( moveX + oldX ) / 2 ), ( ( oldY + moveY ) / 2 ), Math.sqrt( tmp ), 0, Math.PI*2, false );
                    draw_circle(oldX,oldY,moveX,moveY,tmp,obj);
                }
                obj.stroke();
            }
        }
        function redraw(obj,direction)
        {
            //front_ctx.clearRect(0,0,front_canvas.width,front_canvas.height);
            if ( direction === 'front' )
            {
                obj.drawImage( front_image,0,0,front_canvas_width,front_image.height * front_canvas_width / front_image.width);
            }
            else if ( direction === 'back' )
            {
                obj.drawImage( back_image,0,0,back_canvas_width,back_image.height * back_canvas_width / back_image.width);
            }
            if(stored_lines.length === 0)
            {
                return;
            }
            for( let i = 0; i < stored_lines.length; i++ )
            {
                if ( stored_lines[i].direction === 'front' )
                {
                    front_ctx.beginPath();
                    if ( stored_lines[i].draw_type === 'straight' )
                    {
                        front_ctx.moveTo(stored_lines[i].x1, stored_lines[i].y1);
                        front_ctx.lineTo(stored_lines[i].x2, stored_lines[i].y2);
                    }
                    else
                    {
                        let tmp = Math.pow( ( stored_lines[i].x2 - stored_lines[i].x1 ), 2 ) + Math.pow( ( stored_lines[i].y2 - stored_lines[i].y1 ), 2 );
                        draw_circle(stored_lines[i].x1,stored_lines[i].y1,stored_lines[i].x2,stored_lines[i].y2,tmp,front_ctx);
                    }
                    front_ctx.strokeStyle = stored_lines[i].color;
                    front_ctx.stroke();
                }
                else if ( stored_lines[i].direction === 'back' )
                {
                    back_ctx.beginPath();
                    if ( stored_lines[i].draw_type === 'straight' )
                    {
                        back_ctx.moveTo(stored_lines[i].x1, stored_lines[i].y1);
                        back_ctx.lineTo(stored_lines[i].x2, stored_lines[i].y2);
                    }
                    else
                    {
                        let tmp = Math.pow( ( stored_lines[i].x2 - stored_lines[i].x1 ), 2 ) + Math.pow( ( stored_lines[i].y2 - stored_lines[i].y1 ), 2 );
                        draw_circle(stored_lines[i].x1,stored_lines[i].y1,stored_lines[i].x2,stored_lines[i].y2,tmp,back_ctx);
                    }
                    back_ctx.strokeStyle = stored_lines[i].color;
                    back_ctx.stroke();
                }
            }
        }
        function draw_circle(x1,y1,x2,y2, tmp, obj)
        {
            let cos = ( x2 - x1 ) / ( y2 - y1 );
            obj.ellipse(((x1+x2)/2), ((y1+y2)/2),Math.abs((x2-x1)/2),Math.abs((y2-y1)/2),0,0,Math.PI*2,false);
        }
        front_canvas.onmouseup = function(e){
            canvas_onmouseup(e,'front');
        };
        back_canvas.onmouseup = function(e){
            canvas_onmouseup(e,'back');
        };
        function canvas_onmouseup(e,direction)
        {
            canvas_mouse_event = false;
            pX = e.offsetX;
            pY = e.offsetY;
            stored_lines.push({
                x1:oldX,
                y1:oldY,
                x2:pX,
                y2:pY,
                color:color,
                draw_type:draw_type,
                direction:direction
            })
            $('#draw_history').val(JSON.stringify(stored_lines));
        }
        
        front_canvas.onmouseout = function(){
            canvas_mouse_event = false;
        };
        back_canvas.onmouseout = function(){
            canvas_mouse_event = false;
        };
        // クリア
        $(document).on('click','#clear',function(){
            front_ctx.beginPath();
            front_ctx.drawImage( front_image,0,0,front_canvas_width,front_image.height * front_canvas_width / front_image.width);
            back_ctx.beginPath();
            back_ctx.drawImage( back_image,0,0,back_canvas_width,back_image.height * back_canvas_width / back_image.width);
            stored_lines.length = 0;
            $('#draw_history').val('');
        });
        // 一つ戻る
        $(document).on('click','#one_back',function(){
            front_ctx.beginPath();
            front_ctx.drawImage( front_image,0,0,front_canvas_width,front_image.height * front_canvas_width / front_image.width);
            back_ctx.beginPath();
            back_ctx.drawImage( back_image,0,0,back_canvas_width,back_image.height * back_canvas_width / back_image.width);
            if(stored_lines.length === 0)
            {
                return;
            }
            stored_lines.pop();
            $('#draw_history').val(JSON.stringify(stored_lines));
            for( let i = 0; i < stored_lines.length; i++ )
            {
                if ( stored_lines[i].direction === 'front' )
                {
                    front_ctx.beginPath();
                    if ( stored_lines[i].draw_type === 'straight' )
                    {
                        front_ctx.moveTo(stored_lines[i].x1, stored_lines[i].y1);
                        front_ctx.lineTo(stored_lines[i].x2, stored_lines[i].y2);
                    }
                    else
                    {
                        let tmp = Math.pow( ( stored_lines[i].x2 - stored_lines[i].x1 ), 2 ) + Math.pow( ( stored_lines[i].y2 - stored_lines[i].y1 ), 2 );
                        draw_circle(stored_lines[i].x1,stored_lines[i].y1,stored_lines[i].x2,stored_lines[i].y2,tmp,front_ctx);
                    }
                    front_ctx.strokeStyle = stored_lines[i].color;
                    front_ctx.stroke();
                }
                else if ( stored_lines[i].direction === 'back' )
                {
                    back_ctx.beginPath();
                    if ( stored_lines[i].draw_type === 'straight' )
                    {
                        back_ctx.moveTo(stored_lines[i].x1, stored_lines[i].y1);
                        back_ctx.lineTo(stored_lines[i].x2, stored_lines[i].y2);
                    }
                    else
                    {
                        let tmp = Math.pow( ( stored_lines[i].x2 - stored_lines[i].x1 ), 2 ) + Math.pow( ( stored_lines[i].y2 - stored_lines[i].y1 ), 2 );
                        draw_circle(stored_lines[i].x1,stored_lines[i].y1,stored_lines[i].x2,stored_lines[i].y2,tmp,back_ctx);
                    }
                    back_ctx.strokeStyle = stored_lines[i].color;
                    back_ctx.stroke();
                }
            }
        });
        // 色の選択
        $(document).on('click','#color_red',function(){
            color = '#f00';
        });
        $(document).on('click','#color_blue',function(){
            color = '#00f';
        });
        // タイプの選択
        $(document).on('click','#straight',function(){
            draw_type = 'straight';
        });
        $(document).on('click','#circle',function(){
            draw_type = 'circle';
        });

    });

</script>
