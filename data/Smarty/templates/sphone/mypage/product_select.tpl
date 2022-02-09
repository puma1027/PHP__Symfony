
<script>//<![CDATA[
	$(function(){
    	$('.list_area').biggerlink();
	});
//]]></script>
<script src="<!--{$TPL_URLPATH}-->js/config.js"></script>
<script type="text/javascript">
<!--
function func_submit( product_id, class_name1, class_name2 ){
    var err_text = '';
    var fm = document.productSelect;
    var fm1 = document;
    var class1 = "classcategory_id" + product_id + "_1";
    var class2 = "classcategory_id" + product_id + "_2";

    var class1_id = document.getElementById(class1).value;
    var class2_id = document.getElementById(class2).value;

    <!--{if $tpl_no != ''}-->
        /*var opner_product_id = 'search_edit_product_id';
        var opner_classcategory_id1 = 'search_edit_classcategory_id1';
        var opner_classcategory_id2 = 'search_edit_classcategory_id2';*/
		var opner_product_id = 'sepi';
        var opner_classcategory_id1 = 'seci1';
        var opner_classcategory_id2 = 'seci2';
        var tpl_no = '<!--{$tpl_no}-->';
        //document.getElementById("search_no").value = tpl_no;

        //document.getElementById("search_new_product").value = product_id;
    <!--{else}-->
        /*var opner_product_id = 'search_add_product_id';
        var opner_classcategory_id1 = 'search_add_classcategory_id1';
        var opner_classcategory_id2 = 'search_add_classcategory_id2';*/
		var opner_product_id = 'sapi';
        var opner_classcategory_id1 = 'saci1';
        var opner_classcategory_id2 = 'saci2';
    <!--{/if}-->

    if (document.getElementById(class1).type == 'select-one' && class1_id == '') {
        err_text = class_name1 + "を選択してください。\n";
    }
    if (document.getElementById(class2).type == 'select-one' && class2_id == '') {
        err_text = err_text + class_name2 + "を選択してください。\n";
    }
    if (err_text != '') {
        alert(err_text);
        return false;
    }

    /*fm1.getElementById(opner_product_id).value = product_id;
    if (class1_id != '') {
        fm1.getElementById(opner_classcategory_id1).value = class1_id;
    }
    if (class2_id != '') {
        fm1.getElementById(opner_classcategory_id2).value = class2_id;
    }*/
    
    //fm.search_mode_cart.value = '1';
    fm.mode.value = 'select_product_detail';
	var url_param = "&"+opner_product_id+"="+product_id
				+"&"+opner_classcategory_id1+"="+class1_id
				+"&"+opner_classcategory_id2+"="+class2_id
				+"&smc=1";
	<!--{if $tpl_no != ''}-->
	url_param += "&snp="+product_id	+"&sn="+tpl_no;
	<!--{/if}-->
    fm.action = '<!--{$smarty.const.URL_DIR}-->mypage/history.php?order_id=<!--{$tpl_order_id}-->' + url_param;
	
    fm.submit();

    return true;
}
//-->
</script>

<script type="text/javascript">//<![CDATA[
// セレクトボックスに項目を割り当てる。
function lnSetSelect(name1, name2, id, val) {
    sele1 = document.productSelect[name1];
    sele2 = document.productSelect[name2];
    lists = eval('lists' + id);
    vals = eval('vals' + id);

    if(sele1 && sele2) {
        index = sele1.selectedIndex;

        // セレクトボックスのクリア
        count = sele2.options.length;
        for(i = count; i >= 0; i--) {
            sele2.options[i] = null;
        }

        // セレクトボックスに値を割り当てる
        len = lists[index].length;
        for(i = 0; i < len; i++) {
            sele2.options[i] = new Option(lists[index][i], vals[index][i]);
            if(val != "" && vals[index][i] == val) {
                sele2.options[i].selected = true;
            }
        }
    }
}
//]]>
</script>

<script type="text/javascript">//<![CDATA[
    <!--{$tpl_javascript}-->
//]]>
</script>
<style type="text/css">
.l-footer{position:absolute;}
#productSelectResult table th {width: 25%;}
.btn_p_select{width: 205px; padding-top: 10px; margin: 20px auto;}
.buttonBack a{font-size: 14px;}
</style>
<div id="wrapper">

<!--▼CONTENTS-->
<section id="productselectcolumn">
    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle"><!--{if $smarty.request.kind == "add"}-->商品の追加<!--{else}-->商品の変更<!--{/if}--></h2>
    </header>
<div>
    <p class="cancelTxt">単品ドレスからセットドレス、またその逆のカテゴリを超えた変更はできません。<br>
    ご希望の場合は、お問い合わせ（テキストリンク）かお電話（電話リンク）よりご依頼下さいませ。</p>
</div>
	<div class="sectionInner">
<!--▼検索フォーム-->
        <form name="productSelect" id="productSelect" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
        	
            <!--<input type="hidden" name="search_new_product" id="search_new_product" value="">
    		<input type="hidden" id="search_add_product_id" name="search_add_product_id" value="">
            <input type="hidden" id="search_add_classcategory_id1" name="search_add_classcategory_id1" value="">
            <input type="hidden" id="search_add_classcategory_id2" name="search_add_classcategory_id2" value="">
            <input type="hidden" id="search_edit_product_id" name="search_edit_product_id" value="">
            <input type="hidden" id="search_edit_classcategory_id1" name="search_edit_classcategory_id1" value="">
            <input type="hidden" id="search_search_edit_classcategory_id2" name="search_edit_classcategory_id2" value="">
            <input type="hidden" id="search_no" name="search_no" value="">
        	<input type="hidden" id="search_mode_cart" name="search_mode_cart" value="">-->
            <input type="hidden" name="mode_sphone" value="mobile">
            
	<!--{foreach from=$smarty.request key=key item=item}-->
		<!--{if $key ne "mode" && $key ne $smarty.const.TRANSACTION_ID_NAME}-->
        <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
        <!--{/if}-->
	<!--{/foreach}-->
    <input name="mode" type="hidden" value="search" />
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                <table width="100%">
                    <thead>
                    <tr>
                        <th>カテゴリ</th>
                        <td>
                            <select name="search_category_id" class="boxLong top data-role-none">
                            <!--{html_options options=$arrCatList selected=$arrForm.search_category_id}-->
                            </select>
                        </td>
                    </tr>
                        <tr>
                            <th>商品コード</th>
                            <td>
                                <input type="text" name="search_product_code" value="<!--{$arrForm.search_product_code}-->"  class="boxLong top data-role-none halfcharacter" />
                            </td>
                        </tr>
                        <tr>
                            <th>ご利用日</th>
                            <td>
					           <p><!--{$arrForm.rental_show_day}--></p>
                            </td>
                        </tr>
                    </thead>
                </table>
                
                <div class="has-borderbottom"><a href="javascript:void(document.productSelect.submit());" class="changeBtn btn btn_p_select">検索を開始</a></div>
                <div class="buttonBack"><a href="./history.php?order_id=<!--{$arrForm.order_id}-->">前のページヘ戻る</a></div>
		
</form>
	
    <!--▼検索結果表示-->
    <!--{if $tpl_linemax}-->
    <p class="intro clear"><span class="attention"><span id="productscount"><!--{$tpl_linemax}--></span></span>件が該当しました。</p>

	  	<form name="productSelectResult" id="productSelectResult">
    	<table width="100%" class="productSelect">
            <tr>
                <th class="rentalImgArea">商品画像</th>
                <th style="text-align:center; width:20%">商品番号</th>
                <th style="text-align:center; width:40%">商品名</th>
                <th style="text-align:center;">決定</th>
            </tr>
	<!--{section name=cnt loop=$arrProducts}-->
        <!--{assign var=id value=$arrProducts[cnt].product_id}-->

            <tr class="list_area_search">
            	<td align="center" style="text-align:center">
        	<!--{if $arrProducts[cnt].main_list_image != ""}-->
                <!--{assign var=image_path value="`$arrProducts[cnt].main_list_image`"}-->
            <!--{else}-->
                <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
            <!--{/if}-->
                    <p align="center" class="listphoto_search"><img align="middle" src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path}-->&width=80&height=80" alt="<!--{$arrRecommend[$recommend_no].name|escape}-->" /></p>
                </td>
                <td>
				<p class="product_code"><!--→商品番号--><!--{$arrProducts[cnt].product_code|escape}--></p>
                </td>
                <td class="listrightblock_search">
            	<h3 class="product_name"><!--→商品名--><!--{$arrProducts[cnt].name|escape}--></h3>
            		
            	<!--{assign var=class1 value="classcategory_id`$id`_1"}-->
                <!--{assign var=class2 value="classcategory_id`$id`_2"}-->
                <!--{if $tpl_classcat_find1[$id]}-->
                <dt><!--{$tpl_class_name1[$id]|escape}-->：</dt>
                <dd>
                    <select name="<!--{$class1}-->" id="<!--{$class1}-->" style="<!--{$arrErr[$class1]|sfGetErrorColor}-->" onchange="lnSetSelect('<!--{$class1}-->', '<!--{$class2}-->', '<!--{$id}-->','');">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrClassCat1[$id] selected=$arrForm[$class1]}-->
                    </select>
                    <!--{if $arrErr[$class1] != ""}-->
                    <br /><span class="attention">※ <!--{$tpl_class_name1[$id]}-->を入力して下さい。</span>
                    <!--{/if}-->
                </dd>
                <!--{else}-->
                <input type="hidden" name="<!--{$class1}-->" id="<!--{$class1}-->" value="">
                <!--{/if}-->
                <!--{if $tpl_classcat_find2[$id]}-->
                    <dt><!--{$tpl_class_name2[$id]|escape}-->：</dt>
                    <dd>
                        <select name="<!--{$class2}-->" id="<!--{$class2}-->" style="<!--{$arrErr[$class2]|sfGetErrorColor}-->">
                        <option value="">選択してください</option>
                        </select>
                        <!--{if $arrErr[$class2] != ""}-->
                        <br /><span class="attention">※ <!--{$tpl_class_name2[$id]}-->を入力して下さい。</span>
                        <!--{/if}-->
                    </dd>
                <!--{else}-->
                    <input type="hidden" name="<!--{$class2}-->" id="<!--{$class2}-->" value="">
                <!--{/if}-->
	                
                </td>
                <td style="text-align:center;">
                	<p><!--→決定--><a href="javascript:func_submit('<!--{$arrProducts[cnt].product_id}-->', '<!--{$tpl_class_name1[$id]}-->', '<!--{$tpl_class_name2[$id]}-->');" rel="external"  class="product_select">決定</a></p>
                </td>
            </tr>
            
        <!--▲商品<!--{$smarty.section.cnt.iteration}-->-->
        <!--{sectionelse}-->
        <!--{/section}-->
         </table>
         </form>
    
        
        <!--{if count($arrProducts) < $tpl_linemax}-->
        <div class="btn_area">
            <p><a rel="external" href="javascript: void(0);" class="btn_more" id="btn_more_product" onClick="getProducts(<!--{$disp_number|h}-->); return false;">もっとみる(＋<!--{$disp_number|h}-->件)</a></p>
        </div>
    	<!--{/if}-->
    <!--{else}-->
    <!--{/if}-->
    <!--▲検索結果表示-->
	</div>
</section>
<!--▲CONTENTS-->
</div>

<script>
    var pageNo = 2;
    var url = "<!--{$smarty.const.P_DETAIL_URLPATH}-->";
    var imagePath = "<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/";
    var statusImagePath = "<!--{$TPL_URLPATH}-->";

    function getProducts(limit) {
        $.mobile.showPageLoadingMsg();
        var i = limit;
        //送信データを準備
        var postData = {};
        $('#productSelect').find(':input').each(function(){
            postData[$(this).attr('name')] = $(this).val();
        });
        postData["call_type"] = "json";
        postData["search_pageno"] = pageNo;

        $.ajax({
            type: "POST",
            data: postData,
            url: "<!--{$smarty.server.REQUEST_URI|escape}-->",
            cache: false,
            dataType: "json",
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(textStatus);
                $.mobile.hidePageLoadingMsg();
            },
            success: function(result){
                var productStatus = result.productStatus;
                for (var product_id in result) {
                    if (isNaN(product_id)) continue;
                    var product = result[product_id];
                    var productHtml = "";
                    var maxCnt = $(".list_area_search").length - 1;
                    var productEl = $(".list_area_search").get(maxCnt);
                    productEl = $(productEl).clone(true).insertAfter(productEl);
                    maxCnt++;

                    productEl.find("input[type='hidden']").remove();
                    //商品写真をセット
                    $($(".list_area_search .listphoto_search img").get(maxCnt)).attr({
                        src: "<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=" + product.main_list_image + '&width=80&height=80',
                        alt: product.name
                    });

                    //商品名, コードをセット
                    $($(".list_area_search h3.product_name").get(maxCnt)).text(product.name);
                    $($(".list_area_search p.product_code").get(maxCnt)).text(product.product_code);

                    $($(".list_area_search .listrightblock_search").get(maxCnt)).append("<input type='hidden' id='classcategory_id"+product.product_id+"_1' name='classcategory_id"+product.product_id+"_1' value='' />");
                    $($(".list_area_search .listrightblock_search").get(maxCnt)).append("<input type='hidden' id='classcategory_id"+product.product_id+"_2' name='classcategory_id"+product.product_id+"_2' value='' />");
                    
                    //決定
                    $($(".list_area_search a.product_select").get(maxCnt)).attr("href", "javascript:func_submit('"+product.product_id+"', '', '');");
                }
                pageNo++;

                //すべての商品を表示したか判定
                if (parseInt($("#productscount").text()) <= $(".list_area_search").length) {
                    $("#btn_more_product").hide();
                }
                $.mobile.hidePageLoadingMsg();
            }
        });
    }


    /* 全角を半角にする */
            $(function(){
                $(".halfcharacter").blur(function(){
                    charChange($(this));
                });

                charChange = function(e){
                    var val = e.val();
                    var str = val.replace(/[Ａ-Ｚａ-ｚ０-９]/g,function(s){return String.fromCharCode(s.charCodeAt(0)-65248)}).replace(/[‐－―ー]/g, '-');
                    $(e).val(str);
/*
                    if(val.match(/[Ａ-Ｚａ-ｚ０-９]/g)){
                        $(e).val(str);
                    }*/
                }
            });
</script>
