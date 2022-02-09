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
<!--★★メインコンテンツ★★-->
<script type="text/javascript">
<!--

// セレクトボックスのリストを移動
//（移動元セレクトボックスID, 移動先セレクトボックスID）
function fnMoveSelect(select, target) {
    $('#' + select).children().each(function() {
        if (this.selected) {
            $('#' + target).append(this);
            $(this).attr({selected: false});
        }
    });
}

function fnBoxCheckedTag(check){
    var count;
    var fm = document.form1;
    var max = fm["pdf_tag[]"].length;
    if(fm["pdf_tag[]"]){
	fm["pdf_tag[]"].checked = check;
    }
    for(count=0; count<max; count++){
        fm["pdf_tag[]"][count].checked = check;
    }
}


function fnBoxCheckedSeal(check){
    var count;
    var fm = document.form1;
    var max = fm["pdf_seal[]"].length;
    if(fm["pdf_seal[]"]){
	fm["pdf_seal[]"].checked = check;
    }
    for(count=0; count<max; count++){
        fm["pdf_seal[]"][count].checked = check;
    }
}

// target の子要素を選択状態にする
function selectAll(target) { 
    $('#' + target).children().attr({selected: true});
}

function fnSelectCheckSubmit(action){

    var fm = document.form1;

    var i;
    var tag_checkflag = 0;
    var tag_max = fm["pdf_tag[]"].length;
    var seal_checkflag = 0;
    var seal_max = fm["pdf_seal[]"].length;

    if(tag_max) {
        for (i=0;i<tag_max;i++){
            if(fm["pdf_tag[]"][i].checked == true){
                tag_checkflag = 1;
            }
        }
    } else {
        if(fm["pdf_tag[]"].checked == true) {
            tag_checkflag = 1;
        }
    }

    if(seal_max) {
        for (i=0;i<seal_max;i++){
            if(fm["pdf_seal[]"][i].checked == true){
                seal_checkflag = 1;
            }
        }
    } else {
        if(fm["pdf_seal[]"].checked == true) {
            seal_checkflag = 1;
        }
    }

    if(tag_checkflag == 0 && seal_checkflag == 0){
        alert('タグシールが選択されていません');
        return false;
    }

    if(tag_checkflag == 1 || seal_checkflag ==1){
        eccube.setModeAndSubmit('pdf','','');
        
    }
}

//-->
</script>
<div id="products" class="contents-main">                                                                           
<form name="search_form" id="search_form" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" /> 
<input type="hidden" name="mode" value="search">
<!--{foreach key=key item=item from=$arrHidden}-->
        <!--{if $key == 'search_mode'}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
            <!--{/if}-->
<!--{/foreach}-->                           

                            <!--検索条件設定テーブルここから-->
                            <table>
                                <tr>
                                    <th>商品コード</th>
                                    <td>
                                        <span class="attention"><!--{$arrErr.search_product_code}--></span>
                                        <input type="text" name="search_product_code" value="<!--{$arrForm.search_product_code|escape}-->" size="30" class="box30" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>登場日</th>
                                    <td><span class="attention"><!--{$arrErr.search_releaseday_id}--></span>
                                        <select name="search_releaseday_id" style="<!--{$arrErr.search_releaseday_id|sfGetErrorColor}-->">
                                            <option value="">選択してください</option>
                                            <!--{html_options options=$arrRELEASEDAY selected=$arrForm.search_releaseday_id}-->
                                        </select>
                                    </td>
                                </tr>                
                                <tr>
                                    <th>商品カテゴリ</th>
                                    <td>
                                                  
                                        <span class="attention"><!--{$arrErr.search_category_id}--></span>
                                            <table class="layout">
                                                <tr>
                                                    <td>
                                                    <select name="search_category_id[]" id="search_category_id" style="<!--{if $arrErr.search_category_id != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}--> height: 120px; min-width: 200px;" onchange="" size="10" multiple="multiple">
                                                            </select> 
                                                          
                                                    </td>
                                                    <td style="padding: 15px;">
                                                        <a class="btn-normal" href="javascript:;" name="on_select" onclick="fnMoveSelect('search_category_id_unselect','search_category_id'); return false;">&nbsp;&nbsp;&lt;-&nbsp;登録&nbsp;&nbsp;</a><br /><br />
                                                        <a class="btn-normal" href="javascript:;" name="un_select" onclick="fnMoveSelect('search_category_id','search_category_id_unselect'); return false;">&nbsp;&nbsp;削除&nbsp;-&gt;&nbsp;&nbsp;</a>
                                                    </td>
                                                    <td>
                                                       <select name="search_category_id_unselect[]" id="search_category_id_unselect" onchange="" size="10" style="height: 120px; min-width: 200px;" multiple="multiple">
                                                        <!--{html_options values=$arrCatVal output=$arrCatOut selected=$arrForm.search_category_id}-->
                                                    </select>
                                                    </td>
                                                </tr>
                                            </table>  
                                    </td> 
                                </tr>
                                <tr>
                                    <th>公開／非公開</th>
                                    <td>
                                        <!--{html_checkboxes name="search_status" options=$arrDISP selected=$arrForm.search_status}-->
                                    </td>
                                </tr>
                            </table>
                                        
           <div class="btn">
            <p class="page_rows">検索結果表示件数
            <!--{assign var=key value="search_page_max"}-->
            <!--{if $arrErr[$key]}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
            <!--{/if}-->
            <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <!--{html_options options=$arrPageMax selected=$arrForm.search_page_max.value}-->
            </select> 件</p>

            <div class="btn-area">
                <ul>
                    <li><a class="btn-action" href="javascript:;" onclick="selectAll('search_category_id'); document.search_form.submit(); return false;"><span class="btn-next">この条件で検索する</span></a></li>
                </ul>
            </div>

        </div>

</form>  
<!--★★メインコンテンツ★★-->


        <!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete')}-->

<!--★★検索結果一覧★★-->                                                  
    <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
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
                    <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('delete_all','',''); return false;">検索結果を全て削除</a>
                <!--{/if}-->
                <a class="btn-tool" href="javascript:;" onclick="fnSelectCheckSubmit(); return false;">PDF ダウンロード</a> 
                <!--単品-->
                            <label><input type="checkbox" name="pdf_kind1" value="1" <!--{if $arrForm.pdf_kind1==1}-->checked="checked"<!--{/if}-->>単品</label>
                            <!--羽織物-->
                            <label><input type="checkbox" name="pdf_kind2" value="2" <!--{if $arrForm.pdf_kind2==1}-->checked="checked"<!--{/if}-->>羽織物</label>
                            <!--ネックレス-->
                            <label><input type="checkbox" name="pdf_kind3" value="3" <!--{if $arrForm.pdf_kind3==1}-->checked="checked"<!--{/if}-->>ネックレス</label>
                            <!--バッグ-->
                            <label><input type="checkbox" name="pdf_kind4" value="4" <!--{if $arrForm.pdf_kind4==1}-->checked="checked"<!--{/if}-->>バッグ</label>  
                 
            </div>
            
            <!--{if count($arrProducts) > 0}-->
                  <!--{include file=$tpl_pager}-->    
            <table class="list" id="products-search-result">
                    <col width="5%" />
                    <col width="10%" />
                    <col width="8%" />
                    <col width="40%" />
                    <col width="18%" />
                    <col width="19%" />       
                <tr class="center">                                              
                                <th>商品ID</th>
                                <th>商品画像</th>
                                <th>商品コード</th>
                                <th>商品名</th>

                                <th>
                                    タグ<br/>
                                        <!--{*<a style="color: #FFFFFF" href="#" onclick="fnBoxCheckedTag(true);return false;">全て選択</a>／*}-->
                                        <!--{*<a style="color: #FFFFFF" href="#" onclick="fnBoxCheckedTag(false);return false;">全て解除</a>*}-->
                                        <span class="btn-normal"><a href="javascript:;" name="btn01" id="btn01" onclick="fnBoxCheckedTag(true);">全て選択</a></span>
                                        <span class="btn-normal"><a href="javascript:;" name="btn02" id="vtn02" onclick="fnBoxCheckedTag(false);">全て解除</a></span>
                                </th>

                                <th>
                                    シール<br/>
                                        <!--{*<a style="color: #FFFFFF" href="#" onclick="fnBoxCheckedSeal(true);return false;">全て選択</a>／*}-->
                                        <!--{*<a style="color: #FFFFFF" href="#" onclick="fnBoxCheckedSeal(false);return false;">全て解除</a>*}-->
                                        <span class="btn-normal"><a href="javascript:;" name="btn03" id="btn03" onclick="fnBoxCheckedSeal(true);">全て選択</a></span>
                                        <span class="btn-normal"><a href="javascript:;" name="btn04" id="btn04" onclick="fnBoxCheckedSeal(false);">全て解除</a></span>
                                </th>
                            </tr>

                                <!--{section name=cnt loop=$arrProducts}-->
                            <!--▼商品<!--{$smarty.section.cnt.iteration}-->-->
                            <!--{assign var=status value="`$arrProducts[cnt].status`"}-->
                            <tr bgcolor="<!--{$arrPRODUCTSTATUS_COLOR[$status]}-->">
                                <td class="center"><!--{$arrProducts[cnt].product_id}--></td>
                                <td class="center">
                                <!--{if $arrProducts[cnt].main_list_image != ""}-->
                                    <!--{assign var=image_path value="`$arrProducts[cnt].main_list_image`"}-->
                                    <!--{else}-->
                                    <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                                        <!--{/if}-->
                                      <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProducts[cnt].main_list_image|sfNoImageMainList|h}-->" style="max-width: 65px;max-height: 65;" alt="" />    
                                   
                                </td>
                                <td><!--{$arrProducts[cnt].product_code|escape|default:"-"}--></td>
                                <td><!--{$arrProducts[cnt].name|escape}--></td>
                                <td class="center"><input type="checkbox"  name="pdf_tag[]" value="<!--{$arrProducts[cnt].product_id}-->"></td>
                                <td class="center"><input type="checkbox"  name="pdf_seal[]" value="<!--{$arrProducts[cnt].product_id}-->"></td>

                            </tr>

                            <!--▲商品<!--{$smarty.section.cnt.iteration}-->-->
                                <!--{/section}-->
                        </table> 
                        <input type="hidden" name="item_cnt" value="<!--{$arrProducts|@count}-->">
                <!--{/if}-->                 
    </form> 

<!--★★検索結果一覧★★-->
        <!--{/if}-->
</div> 