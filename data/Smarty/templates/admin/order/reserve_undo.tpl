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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA    02111-1307, USA.
 */
*}-->
<!--★★メインコンテンツ★★-->
<script type="text/javascript">
<!--
function fnUpdateSubmit(check,mode){
    var count;
    var check_count = 0;

    // formを取得
    var fm = document.form1;
    var max = fm["chk_product[]"].length;
    if(max){
        for(count=0; count<max; count++){
	        if(fm["chk_product[]"][count].checked) {
	            check_count++;
	        }
	    }
    }else{
        var flag = fm["chk_product[]"].checked;
        if(flag){
            check_count++;
        }
    }
    
    // 商品が選択されていない場合
    if(check_count == 0){
        alert("商品を選択してください。");
        return false;
    }else{
        if(!window.confirm('更新処理を開始します。')){
            return false;
        }
        document.form1['mode'].value = mode;
        document.form1.submit();
    }
}

//-->
</script>
</script>
<div id="order" class="contents-main">
<form name="search_form" id="search_form" method="post" onsubmit="return onsubmitform();" action="<!--{$smarty.server.PHP_SELF|escape}-->" >
<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<input type="hidden" name="mode" value="search">
<!--KHS ADD 2014.3.16-->
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

                        <h2>検索条件設定</h2>
                        <!--検索条件設定テーブルここから-->
                        <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">注文番号<span class="red">※</span></td>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                <!--{assign var=key value="search_order_id"}-->
                                <span class="red12"><!--{$arrErr[$key]}--></span>
                                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"    size="6" class="box6" />
                            </td>
                            </tr>
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">商品コード</td>
                                <td bgcolor="#ffffff" width="499" colspan="3" padding="0">
	                                <!--{assign var=key value="search_product_code"}-->
                                    <span class="red12"><!--{$arrErr[$key]}--></span>
                                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"    size="6" class="box30" />
                                </td>
                            </tr>
                            
                        </table>
<!--<input type="image" name="subm" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/contents/btn_search_on.jpg',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/contents/btn_search.jpg',this)" src="<!--{$TPL_DIR}-->img/contents/btn_search.jpg" width="123" height="24" alt="この条件で検索する" border="0" >-->
 <!-- KHS ADD 2014.3.16                                       -->
        <div class="btn">
            <div class="btn-area">
                <ul>
                    <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('search_form', 'search', '', ''); return false;"><span class="btn-next">この条件で検索する</span></a></li>
                </ul>
            </div>
        </div>
<!--KHS END-->

</form>
<!--★★メインコンテンツ★★-->

<!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete') }-->

<!--★★検索結果一覧★★-->
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<!--KHS ADD 2014.3.16-->
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="update">
<!--{foreach key=key item=item from=$arrHidden}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->
<!--KHS ADD 2014.3.16-->
        <!--{if count($arrResults) > 0}-->
<!--KHS END-->

            <table width="840" border="0" cellspacing="0" cellpadding="0" summary=" ">
                <tr><td height="12"></td></tr>
                <tr>
             	  <td bgcolor="#cccccc">
                    <!--検索結果表示テーブル-->
                    <!--{if !empty($arrResults)}-->
                    <table width="100%" border="0" cellspacing="1" cellpadding="5" summary=" ">
                    	<tr>
                    		<th bgcolor="#636469" width="150" >注文番号</th>
                    		<td bgcolor="#ffffff" >
                    			<!--{$arrResults[0].order_id}-->
                    			<input type="hidden" name="order_id" value="<!--{$arrResults[0].order_id}-->">
                    			<input type="hidden" name="customer_id" value="<!--{$arrResults[0].customer_id}-->">
 								<input type="hidden" name="payment_total" value="<!--{$arrResults[0].payment_total}-->">
                        		<input type="hidden" name="use_point" value="<!--{$arrResults[0].use_point}-->">
                        		<input type="hidden" name="add_point" value="<!--{$arrResults[0].add_point}-->">
                        		<input type="hidden" name="sending_date" value="<!--{$arrResults[0].sending_date}-->">
                    		</td>
                    	</tr>
                    	<tr>
                    		<th bgcolor="#636469" width="150" >顧客名</th>
                    		<td bgcolor="#ffffff" ><!--{$arrResults[0].order_name01|escape}--><!--{$arrResults[0].order_name02|escape}--></td>
                    	</tr>
                    	<tr>
                    		<th bgcolor="#636469" width="150" >受注日</th>
                    		<td bgcolor="#ffffff" ><!--{$arrResults[0].create_date|sfDispDBDate}--></td>
                    	</tr>
                    	<tr>
                    		<th bgcolor="#636469" width="150" >発送日</th>
                    		<td bgcolor="#ffffff" ><!--{$arrResults[0].sending_date|escape}--></td>
                    	</tr>
                    </table>
                   	<!--{/if}-->
                    <br>
                    <table width="840" border="0" cellspacing="1" cellpadding="5" summary=" ">
                        <tr bgcolor="#636469" align="center" >
                       		<td width="60"><span class="white">選択</span></td>
                        	<td width="90"><span class="white">商品画像</span></td>
                            <td width="120"><span class="white">商品コード</span></td>
                            <td><span class="white">商品名</span></td>
                        </tr>
                        <!--{section name=cnt loop=$arrResults}-->
                        <!--{assign var=key value="`$smarty.section.cnt.index`"}-->
	                        <tr bgcolor="#ffffff" >
                          <!--{if ($arrResults[cnt].set_pid == $arrResults[cnt].product_id) || ($arrResults[cnt].set_pid=='')}--><!--{*//::N00083 Add 20131201*}-->
                            <td align="center" rowspan="<!--{$arrResults[cnt].set_ptype}-->">
	                        		<input type="checkbox" value="<!--{$arrResults[cnt].product_id}-->" name="chk_product[]">
	                        	</td>
                          <!--{else}--><!--{*//::N00083 Add 20131201*}-->
                            <!--{*セット商品のドレス以外は表示させない*}-->
                          <!--{/if}--><!--{*//::N00083 end 20131201*}-->
	                            <td align="center"><img src="<!--{$smarty.const.SITE_URL}-->resize_image.php?image=<!--{$arrResults[cnt].main_list_image|sfRmDupSlash}-->&width=65&height=65"></td>
	                            <td align="left"><!--{$arrResults[cnt].product_code|escape}--></td>
	                            <td align="left"><!--{$arrResults[cnt].product_name|escape}--></td>
	                        </tr>
                        <!--{/section}-->
                    </table>
                    <!--検索結果表示テーブル-->
                        <!--<input type="image" onclick="fnUpdateSubmit(true, 'update');return false;" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/contents/btn_regist_on.jpg',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/contents/btn_regist.jpg',this)" src="<!--{$TPL_DIR}-->img/contents/btn_regist.jpg" width="123" height="24" alt="この内容で登録する" border="0" name="subm" >-->
 <!-- KHS ADD 2014.3.16                                       -->
        <div class="btn">
            <div class="btn-area">
                <ul>
                    <li><a class="btn-action" href="javascript:;" onclick="fnUpdateSubmit(true, 'update'); return false;"><span class="btn-next">この内容で登録する</span></a></li>
                </ul>
            </div>
        </div>
<!--KHS END-->

        <!--{/if}-->
</form>
<!--★★検索結果一覧★★-->

<!--{/if}-->
</div>