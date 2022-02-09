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
/*
 * ####################################################
 * バージョン　変更日　        変更者　変更内容
 * 1.0.0      2012/02/14    R.K        商品一括管理で新規作成
 * ####################################################
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
            
            document.getElementById(inner_id).innerHTML = '    URL <a href="#" onClick="lfnDispChange();"> <FONT Color="#FFFF99"> >> カテゴリ表示</FONT></a>';
        }
    }else{
        for (i = 1; i <= cnt; i++) {
            disp_id = 'disp_url'+i;
            document.getElementById(disp_id).style.display="none";
    
            disp_id = 'disp_cat'+i;
            document.getElementById(disp_id).style.display="";
            
            document.getElementById(inner_id).innerHTML = '    カテゴリ <a href="#" onClick="lfnDispChange();"> <FONT Color="#FFFF99"> >> URL表示</FONT></a>';
        }
    }

}

// チェックボックスすべてチェック
function fnBoxChecked(field, check){
    var count;
    var fm = document.form1;
    var max = fm[field].length;
    if(max == undefined){
        fm[field].checked = check;
        return;
    }
    for(count=0; count<max; count++){
        fm[field][count].checked = check;
    }
}

//チェックボックスすべてチェック
function fnBoxChecked1(field, check_count, check){
    var count;
    var fm = document.form1;
    for(count=0; count<check_count; count++){
        var fieldcount=field+count+'[]';
        var max = fm[fieldcount].length;
        for(i=0;i<max;i++){
            fm[fieldcount][i].checked = check;
        }
    }
}

//チェックボックスすべてチェック
function fnBoxChecked2(field, check_count, check){
    var count;
    var fm = document.form1;
    for(count=0; count<check_count; count++){
        var fieldcount=field+count;
        fm[fieldcount].checked = check;
    }
}

//ラジオボックスすべてチェック
function fnRadioBoxChecked(field, radio_count, check){
    var count;
    var fm = document.form1;
    for(i=0; i<radio_count; i++){
        var fd = field+i;
        if(check == true){
            fm[fd][0].checked = true;
        }else{
            fm[fd][1].checked = true;
        }
    }
}

// 
function fnUpdateSubmit(check,mode){
    var count;
    var check_count;
    check_count = 0;
    // formを取得
    var fm = document.form1;
    var max = fm["update_products_id[]"].length;
    if(max){
        for(count=0; count<max; count++){
            if(fm["update_products_id[]"][count].checked) {
                var e = document.createElement("input");
                e.name="update_data_"+count;
                e.type="hidden";
                e.value=count + "_" + fm["update_products_id[]"][count].value;
                fm.appendChild(e);
                check_count++;
            }
        }
    }else{
        var flag = fm["update_products_id[]"].checked;
        if(flag){
            var e = document.createElement("input");
            e.name="update_data_"+0;
            e.type="hidden";
            e.value='0' + "_" + fm["update_products_id[]"].value;
            fm.appendChild(e);
            check_count++;
        }
    }

    // 商品が選択されていない場合
    if(check_count == 0){
        alert("商品を選択してください");
        return false;
    }else{
        if(!window.confirm('更新処理を開始します')){
            return false;
        }
        document.form1['mode'].value = mode;
        document.form1.submit();
    }
}

function fnWebUpdateSubmit(product_id, classcategory_id1, classcategory_id2, mode){
    if(!window.confirm('更新処理を開始しますか？')){
        return ;
    }
    
    document.form1['wed_productid'].value = product_id;
    document.form1['wed_classcategory_id1'].value = classcategory_id1;
    document.form1['wed_classcategory_id2'].value = classcategory_id2;
    document.form1['mode'].value = mode;
    
    document.form1.submit();
}
</script>
<!--★★メインコンテンツ★★-->

<div id="products" class="contents-main">  
<form name="search_form" id="search_form" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />  
<!--{foreach key=key item=item from=$arrHidden}-->
<!--{if $key == 'campaign_id' || $key == 'search_mode'}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/if}-->
<!--{/foreach}-->
                        <!--検索条件設定テーブルここから-->
                        <table>
                        <col width="30%"/>
                        <col width="40%"/>
                        <col width="30%"/>
                         
                            <tr>
                                <th>商品コード</th>
                                <td><input type="text" name="search_product_code" value="<!--{$arrForm.search_product_code|escape}-->" size="30" class="box30" /><br /><span class="red">※工事中!<br />バッグが出てきちゃいますが、絶対に公開にしないでください。</span></td>
                                <th>商品名</th>
                                <td><input type="text" name="search_name" value="<!--{$arrForm.search_name|escape}-->" size="30" class="box30" /></td>
                            </tr>
                            <tr>
                                <th>カテゴリ</th>
                                <td>
                                    <select name="search_category_id" style="<!--{if $arrErr.search_category_id != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->">
                                    <option value="">選択してください</option>
                                    <!--{html_options options=$arrCatList selected=$arrForm.search_category_id}-->
                                    </select>
                                </td>
                                <th>種別</th>
                                <td>
                                    <!--{html_checkboxes name="search_status" options=$arrDISP selected=$arrForm.search_status}-->
                                </td>
                            </tr>
                            <tr>
                                <th>アイコン項目</th>
                                <td colspan="3">
                                <!--{html_checkboxes name="search_icon_flag" options=$arrICON selected=$arrForm.search_icon_flag}-->
                                </td>
                            </tr>
                        </table>
                         <table>
                            <tr>
                                <td>検索結果表示件数
                                    <!--{assign var=key value="search_page_max"}-->
                                    <span class="red12"><!--{$arrErr[$key]}--></span>
                                    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                                    <!--{html_options options=$arrPageMax selected=$arrForm.search_page_max}-->
                                    </select> 件
                                </td>
                            </tr>
                         </table>
                         <div class="btn-area">
                            <ul>
                                <li><a class="btn-action" href="javascript:;" onclick="document.search_form.submit(); return false;"><span class="btn-next">この条件で検索する</span></a></li>
                            </ul>
                        </div>
                         <!--検索条件設定テーブルここまで-->
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
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />  
<input type="hidden" name="mode" value="search">
<input type="hidden" name="product_id" value="">
<input type="hidden" name="category_id" value="">
<!--{foreach key=key item=item from=$arrHidden}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->                                              
            <h2>検索結果一覧　</h2>
            <div class="btn">
                <span class="attention"><!--検索結果数--><!--{$tpl_linemax}-->件</span>&nbsp;が該当しました。
                <!--検索結果-->
                <!--{if $smarty.const.ADMIN_MODE == '1'}-->
                <!--{/if}-->
            </div>                
        <!--{if count($arrProducts) > 0}-->
        <!--{include file=$tpl_pager}-->        
                    <table class="list">
                        <!-- ヘッダ -->  
                        <tr>
                            <th>変更<br /><span class="btn-normal"><a href="javascript:;" name="btn01" id="btn01" onclick="fnBoxChecked('update_products_id[]',true);">全て選択</a></span><br /><span class="btn-normal"><a href="javascript:;" name="btn01" id="btn01" onclick="fnBoxChecked('update_products_id[]',false);">全て解除</a></span></th>
                            <th>商品画像</th>
                            <th>商品コード</th>
                            <th>商品名</th>
                            <th>公開／非公開 <br />
                            <span class="btn-normal">
                            <a href="javascript:;" name="btn01" id="btn01" onclick="fnRadioBoxChecked('update_status',<!--{$productsCnt}-->,true);">一括公開</a></span><br />
                            <span class="btn-normal"><a href="javascript:;" name="btn01" id="btn01" onclick="fnRadioBoxChecked('update_status',<!--{$productsCnt}-->,false);">一括非公開</a></span> </th>
                            <th>アイコン<br />
                            <span class="btn-normal"><a href="javascript:;" name="btn01" id="btn01" onclick="fnBoxChecked1('update_icon_flag',<!--{$productsCnt}-->,true);">全て選択</a>
                            </span><br /><span class="btn-normal"><a href="javascript:;" name="btn01" id="btn01" onclick="fnBoxChecked1('update_icon_flag',<!--{$productsCnt}-->,false);">全て解除</a></span> </th>
                            <th>休日の予約不可<br />
                            <span class="btn-normal"><a href="javascript:;" name="btn01" onclick="fnBoxChecked2('update_reserve_flag',<!--{$productsCnt}-->,true);">全て選択</a></span><br />
                            <span class="btn-normal"><a href="javascript:;" name="btn01" value="全て解除" onclick="fnBoxChecked2('update_reserve_flag',<!--{$productsCnt}-->, false);">全て解除</a></span> </th>
                        </tr>
                        <!-- ヘッダ -->
                        <!--{section name=cnt loop=$arrProducts}-->
                        <!--▼商品<!--{$smarty.section.cnt.iteration}-->-->
                        <!--{assign var=status value="`$arrProducts[cnt].status`"}-->
                        <tr bgcolor="<!--{$arrPRODUCTSTATUS_COLOR[$status]}-->">
                            <td class="center"><input type="checkbox" name="update_products_id[]" value="<!--{$arrProducts[cnt].product_id}-->_<!--{$arrProducts[cnt].classcategory_id1}-->_<!--{$arrProducts[cnt].classcategory_id2}-->" ></td>
                            <td class="center">
                            <!--{if $arrProducts[cnt].main_list_image != ""}-->
                                <!--{assign var=image_path value="`$arrProducts[cnt].main_list_image`"}-->
                            <!--{else}-->
                                <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                            <!--{/if}-->
                            
                            <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65">
                            </td>
                            <td class="center"><!--{$arrProducts[cnt].product_code|escape|default:"-"}--></td>
                            <td><!--{$arrProducts[cnt].name|escape}--></td>
                            <td>
                            <!--{html_radios name="update_status"|cat:$smarty.section.cnt.index options=$arrDISP selected=$arrProducts[cnt].status separator="<br />"}-->
                            </td>
                            <td>
                            <!--{html_checkboxes name="update_icon_flag"|cat:$smarty.section.cnt.index options=$arrICON selected=$arrProducts[cnt].icon_flag separator="<br />"}-->
                            </td>
                            <td class="center">
                            <input type="checkbox" name="update_reserve_flag<!--{$smarty.section.cnt.index}-->" value="1" <!--{if $arrProducts[cnt].reserve_flag == "1"}-->checked="checked"<!--{/if}-->>
                            </td>
                            
                        </tr>
                        <!--▲商品<!--{$smarty.section.cnt.iteration}-->-->
                        <!--{/section}-->
                        <input type="hidden" name="item_cnt" value="<!--{$arrProducts|@count}-->">
                    </table>
                    <!--検索結果表示テーブル-->

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


