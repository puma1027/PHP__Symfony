<!--{*
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
 *}-->

<!--{$CateName}-->
<!--{if $CateName == 'ドレス'}-->
    <!--{assign var="url_for_search" value=$url_dress_list}-->
<!--{elseif $CateName == 'セットドレス'}-->
    <!--{assign var="url_for_search" value=$url_set_dress_list}-->
<!--{elseif $CateName == 'ワンピース'}-->
    <!--{assign var="url_for_search" value=$url_one_piece_list}-->
<!--{elseif $CateName == 'セレモニースーツセット'}-->
    <!--{assign var="url_for_search" value=$url_ceremony_one_piece_list}-->
<!--{elseif $CateName == 'ストール・ボレロ'}-->
    <!--{assign var="url_for_search" value=$url_outer_list}-->
<!--{elseif $CateName == 'バッグ'}-->
    <!--{assign var="url_for_search" value=$url_bag_list}-->
<!--{elseif $CateName == 'ネックレス'}-->
    <!--{assign var="url_for_search" value=$url_necklace_list}-->
<!--{else}-->
    <!--{assign var="url_for_search" value=$url_other_item_list}-->
<!--{/if}-->

<!-- pop up for user review comment -->
<div class="overlay overlay--black js-overlayforcomment"></div>
<div class="uservoicedest">
    <div class="js-uservoicedest uservoicedest__inner"></div>
</div>
<footer class="uservoicedest__foot">
    <a href="#" class="closebtn js-popupvoiceclose">閉じる</a>
</footer>

<!--{*  予約ボタンをページ下へ固定するhtml。不採用になったが、一応残しておきます。css/jsは削除したので、復活する際はgitのcommitから復元すること commit id = 'b82a971cfbe296c7ffa5a8964b329c274b4ddb2e'
<div class="reservefixbtnarea js-reservefixbtnarea">
    <div class="btnarea">
        <a href="#" class="btn btn--fullmin btn--reserve js-scrolltoreservebtn">予約する</a>
    </div>
</div>
*}-->
<style>
    .current_date {
        background-color: #c77485 !important;
    }
    .ui-btn-corner-all{border-radius:0.5em;}
    .ui-select .ui-btn-icon-right .ui-btn-inner{line-height: 22px; font-size: 16px;}
    @media screen and (min-width: 640px) {
        #calendar_area,
        .reserve_calender,
        .table0420130315{
            width: 68%;
        }
        .widebtnarea .btn--large {
            font-size: 1.8rem;
            height: 50px;
            width: 100%;
        }
        .product__cmnbox_c {
            display: block;
            width: 75%;
            padding: 0 10px;
            margin: 0 auto;
        }
        .product__cmnhead_c {
            position: relative;
            width: 400px;
            background: #ded2b3;
            padding: 2px 0;
            margin-bottom: 10px;
        }
        .product__cmntitle_c {
            text-align: center;
            font-size: 16px;
            border-top: 1px dashed #f8f6f4;
            border-bottom: 1px dashed #f8f6f4;
            color: #65504e;
            padding: 12px 5px 12px 5px;
            width: calc(100% - 10px);
            box-sizing: border-box;
            display: inline-block;
        }
        .product__cmntitle_c h2{
            font-size: 1.6rem;
        }
    }
}
</style>
<script src="<!--{$smarty.const.ROOT_URLPATH}-->js/products.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/201303/detailtab.js"></script>
<script src="<!--{$TPL_URLPATH}-->js/jquery.facebox/facebox.js"></script>
<script defer src="<!--{$smarty.const.ROOT_URLPATH}-->js/detail.js"></script>
<script>//<![CDATA[
    //セレクトボックスに項目を割り当てる。
    // Add RCHJ 2013.06.15
    function lnSetSelect(form, name1, name2, val) {
      return;

        sele11 = document[form][name1];
        sele12 = document[form][name2];

        if(sele11 && sele12) {
            index = sele11.selectedIndex;

            // セレクトボックスのクリア
            count = sele12.options.length;
            for(i = count; i >= 0; i--) {
                  sele12.options[i] = null;
            }

            // セレクトボックスに値を割り当てる
            len = lists[index].length;
            for(i = 0; i < len; i++) {
                sele12.options[i] = new Option(lists[index][i], vals[index][i]);
                if(val != "" && vals[index][i] == val) {
                  sele12.options[i].selected = true;
                }
            }
        }
    }

    // 規格2に選択肢を割り当てる。
    function fnSetClassCategories(form, classcat_id2_selected) {
        var $form = $(form);
        var product_id = $form.find('input[name=product_id]').val();
        var $sele1 = $form.find('select[name=classcategory_id1]');
        var $sele2 = $form.find('select[name=classcategory_id2]');
        setClassCategories($form, product_id, $sele1, $sele2, classcat_id2_selected);
    }
    $(function(){
        $('#detailphotoblock ul li').flickSlide({target:'#detailphotoblock>ul', duration:5000, parentArea:'#detailphotoblock', height: 200});
        $('#whobought_area ul li').flickSlide({target:'#whobought_area>ul', duration:5000, parentArea:'#whobought_area', height: 80});
        $('#whobought_area_size ul li').flickSlide({target:'#whobought_area_size>ul', duration:5000, parentArea:'#whobought_area_size', height: 80});

        //お勧め商品のリンクを張り直し(フリックスライドによるエレメント生成後)
        $('#whobought_area li').biggerlink();
        $('#whobought_area_size li').biggerlink();

        //商品画像の拡大
        $('a.expansion').facebox({
            loadingImage : '<!--{$TPL_URLPATH}-->js/jquery.facebox/loading.gif',
            closeImage   : '<!--{$TPL_URLPATH}-->js/jquery.facebox/closelabel.png'
        });
    });
    //サブエリアの表示/非表示
    var speed = 500;
    var stateSub = 0;
    function fnSubToggle(areaEl, imgEl) {
        areaEl.slideToggle(speed);
        if (stateSub == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateSub = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateSub = 0;
        }
    }
    //この商品に対するお客様の声エリアの表示/非表示
    var stateReview = 0;
    function fnReviewToggle(areaEl, imgEl) {
        areaEl.slideToggle(speed);
        if (stateReview == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateReview = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateReview = 0;
        }
    }
    //お勧めエリアの表示/非表示
    var statewhobought = new Array(0,0);
    function fnWhoboughtToggle(areaEl, imgEl, index) {
        areaEl.slideToggle(speed);
        if (statewhobought[index] == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            statewhobought[index] = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            statewhobought[index] = 0;
        }
    }
//]]>
</script>

<div id="wrapper" class="product_detail">
<section id="product_detail" class="productdetail">

    <!--★画像★-->
    <div class="sectionInner">
        <section id="detailarea">

            <div class="flex product_main_area">
                <div class="left_blc">
                    <section class="slider">
                        <!--{if !$is_favorite}-->
                            <div class="favorite"> <a href="javascript:void(0); fnAddFavoriteSphone(<!--{$arrProduct.product_id|h}-->); "><img src="<!--{$TPL_URLPATH}-->/img/favorite_off.png" alt="お気に入り" width="70px"/></a> </div>
                        <!--{else}-->
                            <div class="favorite"> <a href="javascript:void(0); fnDelFavoriteSphone(<!--{$arrProduct.product_id|h}-->);"><img src="<!--{$TPL_URLPATH}-->/img/favorite_on.png" alt="お気に入り削除" width="70px"/></a> </div>
                        <!--{/if}-->

                        <div class="flexslider">
                            <ul class="slides">
                            <!--{assign var=detail_image_size value=140}-->
                            <!--★サブ画像★-->
                            <!--{section name=cnt loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM}-->
                            <!--{assign var=key value="photo_gallery_image`$smarty.section.cnt.index+1`"}-->
                            <!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration`"}-->
                            <!--{assign var=sub_image_factor value="`$arrFile[$key].width/$detail_image_size`"}-->
                            <!--{if $arrFile[$key].filepath != ""}-->
                                <li data-thumb="<!--{$arrFile[$key].filepath|h}-->">
                                    <img src="<!--{$arrFile[$key].filepath|h}-->" style="width:100%; height: auto; margin-top:1px;margin-bottom:10px;" alt="<!--{$arrProduct.name|h}-->"/>
                                </li>
                            <!--{/if}-->
                            <!--{/section}-->
                            </ul>
                        </div>
                          <!--{assign var=detail_image_size value=140}-->
                            <!--★サブ画像★-->

                          <!--{assign var=img_cnt value=0}-->
                            <!--{section name=cnt loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM}-->
                            <!--{assign var=key value="photo_gallery_image`$smarty.section.cnt.index+1`"}-->
                            <!--{if $arrFile[$key].filepath != ""}-->
                        <!--{assign var=img_cnt  value=$img_cnt+1}-->
                            <!--{/if}-->
                            <!--{/section}-->

                      <!--{if $img_cnt<8}-->
                        <!--{assign var=img_cnt  value='8'}-->
                            <!--{/if}-->

                            <!--{assign var=all_width value=100}-->
                            <!--{assign var=thumbnail_width value=$all_width/$img_cnt}-->

                    </section>

                    <link rel="stylesheet" href="<!--{$TPL_DIR}-->css/flexslider.min.css" type="text/css" media="all" />

                    <script>window.jQuery || document.write('<script src="<!--{$smarty.const.ROOT_URLPATH}-->js/libs/jquery-1.7.min.js">\x3C/script>')</script>

                    <!-- FlexSlider -->
                    <script defer src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.flexslider.js"></script>
                    <script type="text/javascript">

                        //$(function(){
                        //  SyntaxHighlighter.all();
                        //});
                        $(window).load(function(){
                            $(".favorite").css("top", ($(".flexslider").position().top) + "px");

                            setTimeout(
                                function(){
                                    $('.flexslider').flexslider({
                                        animation: "slide",
                                        controlNav: "thumbnails",
                                        start: function(slider){
                                            $('body').removeClass('loading');
                                        },
                                        animationLoop:false,
                                        slideshow : false
                                    });
                                    $(".flex-control-nav li").css({
                                        width : '<!--{$thumbnail_width}-->%'
                                    });
                                    $(".flex-direction-nav a").css({
                                        margin : '0'
                                    });
                                },300
                            );
                        });
                    </script>
                </div>
                <div class="right_blc">
                    <div class="pc_detail_area">
                        <p><!--{$arrBrand.name_furigana}--></p>
                        <h1 class="product__name"><!--{$arrProduct.name|h}--></h1>

                        <table class="product__outline__table">
                            <tbody>

                                <tr>
                                    <th>商品コード</th>
                                    <td>
                                        <!--{assign var=codecnt value=$arrProductCode|@count}-->
                                        <!--{assign var=codemax value="`$codecnt-1`"}-->
                                        <!--{if $codecnt > 1}-->
                                            <!--{$arrProductCode.0}-->0<!--{$arrProductCode[$codemax]}-->
                                        <!--{else}-->
                                            <!--{$arrProductCode.0}-->
                                        <!--{/if}--></td>
                                    <th>カラー</th>
                                    <td><!--{$arrProduct.dress_color}--></td>
                                </tr>
                                <tr>
                                    <th>サイズ</th>
                                    <td>
                                    <!--{if $arrProduct.product_type == NECKLACE_PRODUCT_TYPE}-->
                                        <!--{$arrProduct.length_overall}--><!--{if $arrProduct.length_overall != ""}-->cm<!--{/if}-->
                                    <!--{elseif $arrProduct.product_type == OTHERS_PRODUCT_TYPE}-->
                                        <!--{$arrProduct.length_overall}-->
                                    <!--{elseif $kidsSize != NULL}-->
                                        <!--{$kidsSize}-->
                                    <!--{else}-->
                                            <!--{$arrProduct.item_size}-->
                                    <!--{/if}-->
                                    </td>

                                <!--{if $arrProduct.model_body_length != "" && $arrProduct.model_body_length > 0}-->
                                <th>モデルの身長</th>
                                <td><!--{$arrProduct.model_body_length|nl2br}-->cm</td>
                                <!--{else}-->
                                <td></td><td></td>
                                <!--{/if}-->
                            </tbody>
                        </table>

                        <table class="product__outline__table">

                            <!--★ポイント★-->
                            <!--{* if $smarty.const.USE_POINT !== false *}-->
                                <!--{if $smarty.const.USE_POINT === false}-->
                            <tr>
                                <th>ポイント</th>
                                <td>：</td>
                                <td>
                                    <!--{if $arrProduct.price02_min == $arrProduct.price02_max}-->
                                        <!--{$arrProduct.price02_min|sfPrePoint:$arrProduct.point_rate:$smarty.const.POINT_RULE:$arrProduct.product_id|number_format}-->
                                    <!--{else}-->
                                        <!--{if $arrProduct.price02_min|sfPrePoint:$arrProduct.point_rate:$smarty.const.POINT_RULE:$arrProduct.product_id == $arrProduct.price02_max|sfPrePoint:$arrProduct.point_rate:$smarty.const.POINT_RULE:$arrProduct.product_id}-->
                                            <!--{$arrProduct.price02_min|sfPrePoint:$arrProduct.point_rate:$smarty.const.POINT_RULE:$arrProduct.product_id|number_format}-->
                                        <!--{else}-->
                                            <!--{$arrProduct.price02_min|sfPrePoint:$arrProduct.point_rate:$smarty.const.POINT_RULE:$arrProduct.product_id|number_format}-->～<!--{$arrProduct.price02_max|sfPrePoint:$arrProduct.point_rate:$smarty.const.POINT_RULE:$arrProduct.product_id|number_format}-->
                                        <!--{/if}-->
                                    <!--{/if}-->Pt
                                </td>
                            </tr>
                                <!--{/if}-->
                            <!--▼メーカーURL-->
                            <!--{if $arrProduct.comment1|strlen >= 1}-->
                            <tr>
                                <th>メーカーURL</th>
                                <td>：</td>
                                <td>
                                    <a rel="external" href="<!--{$arrProduct.comment1|h}-->" target="_blank">
                                                    <!--{$arrProduct.comment1|h}--></a>
                                </td>
                            </tr>
                            <!--{/if}-->
                            <!--▲メーカーURL-->
                        </table>
                        <!--{* 201806 if change *}-->
                        <!--{* セレ(367)、キッズ女子(373)、キッズ男子(372) *}-->
                        <!--{if $kidsSize != NULL || $arrProductCode[$codemax]|mb_strpos:'CM' !== FALSE}-->

                        <!--{* セットドレス *}-->
                        <!--{elseif $arrProduct.product_type == SET_DRESS_PRODUCT_TYPE || $arrProduct.product_type == "4"}-->
                            <div class="product__setdetail">
                                <div class="product__setdetail__title">このセットについてくる商品</div>

                                <table class="product__outline__table">
                                    <tr>
                                        <th>ネックレス</th>
                                        <td><a href="<!--{$smarty.const.ROOT_URLPATH}-->products/detail.php?product_id=<!--{$arrPIdNecklace}-->" style="text-decoration: underline;">「<!--{$arrPCodeNecklace[0]}-->」</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>羽織</th>
                                        <td>
                                            <!--{if $arrProductCode[$codemax]|mb_strpos:'01-0419' !== FALSE}-->
                                              「<!--{$arrPCodeStole[0]}-->」
                                            <!--{else}-->
                                              <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/detail.php?product_id=<!--{$arrPIdStole}-->" style="text-decoration: underline;">「<!--{$arrPCodeStole[0]}-->」</a>
                                            <!--{/if}-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>バッグ</th>
                                        <td><a href="<!--{$smarty.const.ROOT_URLPATH}-->products/detail.php?product_id=<!--{$arrPIdBag}-->" style="text-decoration: underline;">「<!--{$arrPCodeBag[0]}-->」</a>
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- // .product__setdetail -->
                        <!--{else}-->
                        <!--{* それ以外 *}-->
                        <!--{/if}-->
                        <!--★詳細メインコメント★-->

                        <form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->products/detail.php">
                        <input type="hidden" id="category_id" name="category_id" value="<!--{$category_id}-->" >
                        <div class="detailbottom">
                        <!--//::N00120 Add 20140303-->

                        <div class="js-scrolldestpotition"></div>

                        <!--{if $arrProduct.size_supplement == 'ON'}-->
                        <div class="product__caution">
                            ※サイズ選択欄はありません。この商品は、1着で複数のサイズに対応しています。
                        </div>
                        <!--{/if}-->
                        <!--//::N00120 del 20140303-->

                          <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                          <input type="hidden" name="mode" value="cart" />
                          <input type="hidden" name="product_id" value="<!--{$tpl_product_id}-->" />
                          <input type="hidden" name="product_class_id" value="<!--{$tpl_product_class_id}-->" id="product_class_id" />
                          <input type="hidden" name="favorite_product_id" value="" />
                        <!-- 2012.05.24 RCHJ Add -->
                          <input type="hidden" name="date1" id="date1" value="<!--{$smarty.request.date1}-->" />
                          <input type="hidden" name="date2" id="date2" value="<!--{$smarty.request.date2}-->" />
                          <input type="hidden" name="quantity" value="<!--{$arrForm.quantity.value|default:1}-->"/>
                          <input type="hidden" name="opt_send_date" value="<!--{$arrForm.opt_send_date.value|default:1}-->"/><!-- 2013.03.07 RCHJ Add -->
                        <!-- End -->

                          <!--//::N00135 Add 20140325-->
                          <!--{if $arrProduct.haiki == 1 }-->
                          <tr>
                            <td>
                              <p style="font-size:150%;color:red">※申し訳ありません。こちらの商品は廃棄になりました。</p>
                            </td>
                          </tr>
                          <!--{else}-->

                          <!--//::N00135 end 20140325-->
                                <!--{if $arrProductCode[$codemax]|mb_strpos:PCODE_SET_DRESS !== false && $smarty.get.category_id === CATEGORY_DRESS_ALL}--><!-- add ishibashi-->  
                                    <div class="productdetail__pricearea">
                                        <div class="">
                                            <div class="productPrice" style="width:95%;">
                                                <!--//::N00134 Add 20140325-->
                                                <!--箱小に入らないドレスは4点のみに-->
                                                <!--{if $arrProductCode[0] != '01-0267E' &&
                                                          $arrProductCode[0] != '01-0166E' &&
                                                          $arrProductCode[0] != '01-0263E' &&
                                                          $arrProductCode[0] != '01-0265E' &&
                                                          $arrProductCode[0] != '01-0266E' &&
                                                          $arrProductCode[0] != '01-0264A' &&
                                                          $arrProductCode[0] != '01-0262EY' &&
                                                          $arrProductCode[0] != '01-0167E' &&
                                                          $arrProductCode[0] != '01-0297F' &&
                                                          $arrProductCode[0] != '01-0189B'}-->
                                                <!--//::N00134 end 20140325-->

                                                    <!--{if $arrProduct.product_type == SET_DRESS_PRODUCT_TYPE}-->
                                                    <div class="product__itemlabel">
                                                        <!--{if $silhouetteFlag == 2}-->
                                                            シャツ+パンツ+羽織物
                                                        <!--{elseif $silhouetteFlag == 1 || $arrProductCode[$codemax]|mb_strpos:'CM' !== FALSE}-->
                                                            ワンピース+羽織物
                                                        <!--{else}-->
                                                            ドレス+ネックレス+羽織物
                                                        <!--{/if}-->
                                                    </div>
                                                    <!--{/if}-->
                                                    <!--{if $arrProduct.product_type == 2}-->
                                                        <div class="product__itemlabel">ドレス</div>
                                                    <!--{/if}-->
                                                <div class="priceArea">
                                                    <span>レンタル価格：３泊４日間</span><br>
                                                    <!--{if $arrProductCode[0]|mb_strpos:'21-' !== FALSE && $arrProductCode[$codemax]|mb_strpos:'CM' !== FALSE}-->
                                                        <!--{assign var=disp value='style="display:none;"'}-->
                                                    <!--{/if}-->
                                                    <div class="money" <!--{$disp}-->>
                                                        <span>
                                                        <!--//::N00083 Change 20131201-->
                                                        <!--{if $arrProductCode[0]|mb_strpos:'02-' !== FALSE}-->
                                                            <span>6,480円</span><span class="fs16">(税込)</span>
                                                        <!--{elseif $arrProduct.product_type != SET_DRESS_PRODUCT_TYPE}-->
                                                            <span>
                                                            <!--{if $arrProduct.price02_min == $arrProduct.price02_max}-->
                                                                <!--{$arrProduct.price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                                                            <!--{else}-->
                                                                <!--{$arrProduct.price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->　〜　<!--{$arrProduct.price02_max|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                                                            <!--{/if}-->
                                                            </span>円
                                                            <span class="fs16">(税込)</span>
                                                            <!--{else}-->
                                                              8,980円<span class="fs16">(税込)</span>
                                                            <!--{/if}-->
                                                        <!--//::N00083 end 20131201-->
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="buttonArea" <!--{$disp}-->>
                                                    <a href="javascript:goCalander(3);" class="btn btn--reserve">予約する</a>
                                                    <!--//::N00083 Add 20131201-->
                                                    <span style="font-size:15px; color:#333333;">
                                                        <p id="category_icon"></p>
                                                    </span>
                                                </div>
                                                <!--{/if}--><!--//::N00134 Add 20140325-->
                                            </div>

                                            <!--{* Change 201806 コーデセットバッグ付 *}-->
                                            <!--{* セレ、キッズ *}-->
                                            <!--{if $kidsSize != NULL}-->

                                                <!--{elseif $arrProduct.product_type == SET_DRESS_PRODUCT_TYPE && $arrProductCode[$codemax]|mb_strpos:'CM' === FALSE}-->
                                                <div class="productPrice" style="width:95%;">
                                                    <div class="product__itemlabel"><!--レンタルドレス4点セット-->ドレス+ネックレス+羽織物+バッグ</div>
                                                        <div class="priceArea">
                                                        <!--{if $arrProduct.price02_max > 0}-->
                                                            <span>レンタル価格：３泊４日間</span><br>
                                                            <div class="money">
                                                                11,980円<span class="fs16">(税込)</span>
                                                            </div>
                                                        <!--{/if}-->
                                                        </div>
                                                        <div class="buttonArea" >
                                                            <a href="javascript:goCalander(4);" class="btn btn--reserve">予約する</a>
                                                            <span style="font-size:15px; color:#333333;">
                                                                <p id="category_icon"></p>
                                                            </span>
                                                        </div>
                                                </div>
                                                <!--{else}-->
                                                <!--{* それ以外 *}-->
                                                <!--{/if}-->
                                            <!--{* end 201806 cere *}-->

                                            <div class="productPrice">
                                                <div><a rel="external" href='#a_review' target="_top">> お客様レビュー</a></div>
                                                <div style="margin: 5px 0 0 10px;">
                                                    <span class="yellow"><!--{assign var=idxReviewCnt value=-1}--><!--{section name=id loop=$arrProduct.womens_review_avg}--><span class="star">★</span><!--{assign var=idxReviewCnt value=$smarty.section.id.index}--><!--{/section}--><!--{section name=revCnt start=$idxReviewCnt+1 loop=5}--><span class="star_gray">★</span><!--{/section}--></span>
                                                    <span class="product__review__count">（<!--{$arrProduct.womens_review_count}-->）</span>
                                                </div>
                                            </div>

                                    </div><!-- // .productdetail__pricearea -->
                                <!--{else}-->
                                    <div class="productdetail__pricearea">
                                        <!--{if $arrProduct.product_type == 2}-->
                                            <div class="product__itemlabel">ドレス</div>
                                        <!--{elseif $arrProduct.product_type == SET_DRESS_PRODUCT_TYPE}-->
                                        <div class="product__itemlabel">
                                            <!--{if $silhouetteFlag == 2}-->
                                                シャツ+パンツ+羽織物
                                            <!--{elseif $silhouetteFlag == 1 || $arrProductCode[$codemax]|mb_strpos:'CM' !== FALSE}-->
                                                ワンピース+羽織物
                                            <!--{else}-->
                                                ドレス+ネックレス+羽織物
                                            <!--{/if}-->
                                        </div>
                                        <!--{elseif $arrProductCode[$codemax]|mb_strpos:'21-' !== FALSE || $arrProductCode[$codemax]|mb_strpos:'15-' !== FALSE}-->
                                            <div class="product__itemlabel">羽織物</div>
                                        <!--{elseif $arrProductCode[$codemax]|mb_strpos:'12-' !== FALSE || $arrProductCode[$codemax]|mb_strpos:'14-' !== FALSE}-->
                                            <div class="product__itemlabel">ワンピース</div>
                                        <!--{else}-->
                                            <div class="product__itemlabel">アクセサリー/小物</div>
                                        <!--{/if}-->

                                        <div class="product__detail_info">
                                            <div class="productPrice">
                                                <!--//::N00134 Add 20140325-->
                                                <!--ポスパケに入らないドレスは4点のみに-->
                                                <!--{if $arrProductCode[0] != '01-0267E' &&
                                                          $arrProductCode[0] != '01-0166E' &&
                                                          $arrProductCode[0] != '01-0263E' &&
                                                          $arrProductCode[0] != '01-0265E' &&
                                                          $arrProductCode[0] != '01-0266E' &&
                                                          $arrProductCode[0] != '01-0264A' &&
                                                          $arrProductCode[0] != '01-0262EY' &&
                                                          $arrProductCode[0] != '01-0167E' &&
                                                          $arrProductCode[0] != '01-0297F' &&
                                                          $arrProductCode[0] != '01-0189B'}-->
                                                <!--//::N00134 end 20140325-->
                                                <div class="priceArea">

                                                    <span>レンタル価格：３泊４日間</span><br>
                                                    <!--{if $arrProductCode[0]|mb_strpos:'21-' !== FALSE && $arrProductCode[$codemax]|mb_strpos:'CM' !== FALSE}-->
                                                        <!--{assign var=disp value='style="display:none;"'}-->
                                                    <!--{/if}-->
                                                    <div class="money" <!--{$disp}-->>
                                                        <span>
                                                        <!--//::N00083 Change 20131201-->
                                                        <!--{if $arrProductCode[0]|mb_strpos:'02-' !== FALSE}-->
                                                            <span>6,480円</span><span class="fs16">(税込)</span>
                                                        <!--{elseif $arrProduct.product_type != SET_DRESS_PRODUCT_TYPE}-->
                                                            <span>
                                                            <!--{if $arrProduct.price02_min == $arrProduct.price02_max}-->
                                                                <!--{$arrProduct.price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                                                            <!--{else}-->
                                                                <!--{$arrProduct.price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->　〜　<!--{$arrProduct.price02_max|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                                                            <!--{/if}-->
                                                            </span>円
                                                            <span class="fs16">(税込)</span>
                                                            <!--{else}-->
                                                              8,980円<span class="fs16">(税込)</span>
                                                            <!--{/if}-->
                                                        <!--//::N00083 end 20131201-->
                                                        </span>
                                                    </div>
                                                </div>
                                                <!--{/if}--><!--//::N00134 Add 20140325-->
                                            </div>

                                            <div class="productPrice">
                                                <div><a rel="external" href='#a_review' target="_top">> お客様レビュー</a></div>
                                                <div style="margin: 5px 0 0 10px;">
                                                    <span class="yellow"><!--{assign var=idxReviewCnt value=-1}--><!--{section name=id loop=$arrProduct.womens_review_avg}--><span class="star">★</span><!--{assign var=idxReviewCnt value=$smarty.section.id.index}--><!--{/section}--><!--{section name=revCnt start=$idxReviewCnt+1 loop=5}--><span class="star_gray">★</span><!--{/section}--></span>
                                                    <span class="product__review__count">（<!--{$arrProduct.womens_review_count}-->）</span>
                                                </div>
                                            </div>

                                    </div><!-- // .productdetail__pricearea -->
                                    
                                    <!--{* Change 201806 コーデセットバッグ付 *}-->
                                    <!--{* セレ、キッズ *}-->
                                    <!--{if $kidsSize != NULL}-->
                                        <!--{elseif $arrProduct.product_type == SET_DRESS_PRODUCT_TYPE && $arrProductCode[$codemax]|mb_strpos:'CM' === FALSE}-->
                                        <div class="priceArea">
                                            <div class="product__itemlabel"><!--レンタルドレス4点セット-->ドレス+ネックレス+羽織物+バッグ</div>
                                                <!--{if $arrProduct.price02_max > 0}-->
                                                <div class="priceArea">
                                                    <span>レンタル価格：３泊４日間</span><br>
                                                    <div class="money">
                                                        11,980円<span class="fs16">(税込)</span>
                                                    </div>
                                                </div>
                                                <!--{/if}-->
                                        </div>
                                        <!--{else}-->
                                        <!--{* それ以外 *}-->
                                        <!--{/if}-->
                                    <!--{* end 201806 cere *}-->

                                <!--{/if}-->
                            <!--{/if}--><!-- haiki -->
                        </div>

                        <div style="margin: 10px;">
                        <!--{foreach from=$arrProduct.group key=key item=value}-->
                            <p><a href="/products/detail.php?product_id=<!--{$value.product_id}-->&category_id=<!--{$smarty.get.category_id }-->"><!--{$value.product_code}--></a></p>
                        <!--{/foreach}-->
                        </div>
                </div>
            </div>
            <!--{if $arrProductCode[$codemax]|mb_strpos:PCODE_SET_DRESS !== false && $smarty.get.category_id === CATEGORY_DRESS_ALL}--><!-- add ishibashi-->  
            <!--{else}-->
            <!--<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />-->
            <!--<input type="hidden" name="mode" value="cart" />-->
            <!--<input type="hidden" name="product_id" value="<!--{$tpl_product_id}-->" />-->
            <!--<input type="hidden" name="product_class_id" value="<!--{$tpl_product_class_id}-->" id="product_class_id" />-->
            <!--<input type="hidden" name="favorite_product_id" value="" />-->
            <!-- 2012.05.24 RCHJ Add -->
            <input type="hidden" name="search_rendal_date" id="search_rendal_date" value="<!--{$search_rendal_date}-->" />
            <input type="hidden" name="select_date" id="select_date" value="" />
            <input type="hidden" name="set_type" id="set_type" value="set<!--{$smarty.request.set_type}-->" />

            <section class="pc_detail_area">
                <div title="予約をする">
                    <section id="calender" class="reserve_calender">
                        <header class="product__cmnhead_c pc_show">
                            <div class="product__cmntitle_c"><h2 class="">ご利用日をクリック</h2></div>
                        </header>
                        <header class="product__cmnhead sp_show">
                            <h2 class="product__cmntitle">ご利用日をクリック</h2>
                        </header>

                        <!--{if !empty($same_dress)}-->
                        <section class="product__cmnbox" style="margin:28px 0;">
                            <p class="ta_c fs16 lh22 pc_w_400">同じ商品が<span class="colorRed"><!--{($same_dress|@count)+1}-->点</span>あります。<br>各商品ごとに予約状況をご確認ください。</p>
                            <div class="slt_wit pc_w_400">
                                <select name="select" onChange="location.href=value;">
                                    <option value="#">
                                        <!--{assign var=codecnt value=$arrProductCode|@count}-->
                                        <!--{assign var=codemax value="`$codecnt-1`"}-->
                                        <!--{if $codecnt > 1}-->
                                            <!--{$arrProductCode.0}-->0<!--{$arrProductCode[$codemax]}-->
                                        <!--{else}-->
                                            <!--{$arrProductCode.0}-->
                                        <!--{/if}-->&nbsp;を選択中
                                    </option>
                                    <!--{section name=cnt loop=$same_dress}-->
                                    <option value="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$same_dress[cnt].product_id|u}-->&category_id=<!--{$same_dress[cnt].category_id|u}-->">
                                        <span class="sizeColor ta_c">商品コード：<!--{$same_dress[cnt].product_code}--></span>
                                    </option>
                                    <!--{/section}-->
                                </select>
                            </div>
                        </section>
                        <!--{/if}-->

                        <div class="sectionInner calenderInner mb10">
                            <ul>
                                <li class="available"><span></span>予約可能</li>
                                <li class="Settled"><span></span>予約済み</li>
                                <li class="impossible"><span></span>予約不可</li>
                            </ul>
                        </div>

                        <input type="hidden" name="category_id" value="<!--{$category_id}-->" >

                        <fieldset class="mb20 cal_width">
                        <table width="100%" >
                            <tr style="vertical-align:top;">
                                <td align="center">
                                <!--▼レンタル日程▼-->
                                    <div id="calendar_area" class="pc_cldr">
                                        <div class="leaf0220130315">
                                            <div id="tabs20130315">
                                                <ul class="tabcalendarmonth ml10 mr10">
                                                    <li id="tab0120130315"><a href="#tab-120130315"><!--{$tpl_current_month}-->月</a></li>
                                                    <li id="tab0220130315"><a href="#tab-220130315"><!--{$tpl_next_month}-->月</a></li>
                                                    <li id="tab0320130315"><a href="#tab-320130315"><!--{$tpl_next_next_month}-->月</a></li>
                                                </ul>
                                                <div id="tab-120130315" class="tab_box20130315">
                                                    <div id="datepicker_0" style="" ></div>
                                                    <span id="calendar_lbl_tab01"></span>
                                                    <!-- 日程未選択選択表示 -->
                                                    <div id="calendar_lbl_non" class="calendar_lbl_non">ご利用日を選択してください</div>
                                                </div>
                                                <div id="tab-220130315" class="tab_box20130315">
                                                    <div id="datepicker_1" style=""></div>
                                                    <span id="calendar_lbl_tab02"></span>
                                                </div>
                                                <div id="tab-320130315" class="tab_box20130315">
                                                    <div id="datepicker_2" style=""></div>
                                                    <span id="calendar_lbl_tab03"></span>
                                                </div>
                                            </div>
                                            <!-- //#tab -->
                                        </div>
                                    </div>
                                <!--▲レンタル日程▲-->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" class="pt20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table0420130315">
                                        <tr><td colspan="2"><div class="resStart"><p>※ご予約は<span class="colorRed">2か月前のその週の月曜夜9時</span>からになります。</p></div></td></tr>
                                        <tr class="pc_show arriaval_area">
                                            <td class="left20130315">お届け</td>
                                            <td class="right20130315"><span name="txt_arrival_date" id="txt_arrival_date" class="use_text">ご利用日をクリック</span></td>
                                        </tr>
                                        <tr class="pc_show arriaval_area">
                                            <td class="left20130315">ご利用</td>
                                            <td class="right20130315">
                                                <span name="txt_use_date" id="txt_use_date" class="use_text mt5" style="display:block;">ご利用日をクリック</span>
                                            </td>
                                        </tr>
                                        <tr class="pc_show arriaval_area">
                                            <td class="left20130315">ご返却</td>
                                            <td class="right20130315"><span name="txt_return_date" id="txt_return_date" class="use_text">ご利用日をクリック</span></td>
                                        </tr>
                                    </table>
                                    <div>
                                        <p class="fontS">※お届けの際、時間指定ができます。<br/>
                                        ※お客様が返却の「手続き」をする期限です。</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        </fieldset>
                </section>
                        <div class="widebtnarea pc_yoyaku">
                            <a class="btn btn--attention btn--large" href="javascript:goAddSchejule();"><span class="btn__label">この日程で予約する</span></a>
                        </div>
            </div>
        </section>
        </div>
        <!--{/if}--><!-- add ishibashi -->

        <!-- SP版 -->
        <!--{if !empty($diff_dress)}-->
        <div class="detail_area sp_show">
            <section class="product__cmnbox">
                <header class="product__cmnhead">
                    <h3 class="product__cmntitle">サイズ違い・色違いの商品</h3>
                </header>
                <!--{section name=cnt loop=$diff_dress}-->
                    <!--{if $smarty.section.cnt.iteration == 3 * $smarty.section.cnt.iteration +1}-->
                        <ul style="display:flex;">
                    <!--{/if}-->
                            <li class="product__itemlist__item" id="diff_size_color">
                                <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$diff_dress[cnt].product_id|u}-->&category_id=<!--{$diff_dress[cnt].category_id|u}-->">
                                    <img src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$diff_dress[cnt].main_list_image|u}-->" class="product__itemlist__fig">
                                    <span class="sizeColor"><!--{$diff_dress[cnt].comment}--></span>
                                </a>
                            </li>
                    <!--{if $smarty.section.cnt.iteration == 3 * $smarty.section.cnt.iteration +1}-->
                        </ul>
                    <!--{/if}-->
                <!--{/section}-->
            </section><!-- // .product__cmnbox-->
        </div>
        <!--{/if}-->

        <input type="hidden" id="hid_send_date" name="rentalDate[send_date]" value="" />
        <input type="hidden" id="hid_arrival_date" name="rentalDate[arrival_date]" value="" />
        <input type="hidden" id="hid_use_date" name="rentalDate[use_date]" value="" />
        <input type="hidden" id="hid_return_date" name="rentalDate[return_date]" value="" />
        <input type="hidden" id="hid_return_date_tip" name="rentalDate[hid_return_date_tip]" value="<!--{$smarty.const.RETURN_TIME}-->まで" />
        </form>
        </section>

<div class="js-getscrollpostion"></div>

    <div class="advicetel sp_show">
        <div class="advicetel__head">
            <div class="advicetel__head__text">ドレスのサイズ・コーディネート<hr class="underline">スタッフがしっかりサポートします！！</div>
            <a href="tel:0429467417">
                <div class="advicetel__head__main">
                    <span class="advicetel__head__label">アドバイス<br>テレフォン</span>
                    <span class="advicetel__head__tel">04-2946-7417</span>
                    <span class="advicetel__head__time">平日・祝日 10:00 - 17:00</span>
                    <div class="advice_product_char">
                        <img loading="lazy" src="/user_data/packages/sphone/img/advicetel_char_02.png" alt="">
                    </div>
                </div>
            </a>
        </div>
        <div class="advicetel__bottom">
            <ul class="btnarea">
                <li class="btnbox btnbox--2col">
                    <a href="tel:0429467417" class="btn btn--advicetel btn--fullmin"><span class="btn__label">電話で相談する</span></a>
                </li>
                <li class="btnbox btnbox--2col">
                    <a href="<!--{$url_advicetel_form}-->" class="btn btn--advicetel btn--advicetel--mail btn--fullmin"><span class="btn__label">メールで相談する</span></a>
                </li>
            </ul>
        </div><!-- // .advicetel__bottom -->
        <div class="faqlinkbox">
            <span class="faqlinkbox__linkarea">
                <a href="/user_data/faq.php" class="faqlinkbox__link" target="_blank"><i class="iconfaq"><img loading="lazy" src="/user_data/packages/sphone/img/icon_faq.svg" alt="FAQ"></i><span>よくあるご質問</span></a>
            </span>
        </div>
    </div><!-- // .advicetel-->

<section id="tabTitle">
  <input type="hidden" id="hid_recomment_flg" value="<!--{$recomment_flg}-->" />
  <input type="hidden" id="hid_model_flg" value="<!--{$model_flg}-->" />
  <input type="hidden" id="hid_size_flg" value="<!--{$size_flg}-->" />
  <input type="hidden" id="hid_actual_size_flg" value="<!--{$actual_size_flg}-->" />
  <div class="sectionInner" id="contentsDetail">

    <!--{if !empty($diff_dress)}-->
    <div class="detail_area">
        <section class="product__cmnbox pc_show">
            <header class="product__cmnhead">
                <h3 class="product__cmntitle">サイズ違い・色違いの商品</h3>
            </header>
            <div class="product__corditem">
                <ul class="product__itemlist__grp">
                    <!--{section name=cnt loop=$diff_dress}-->
                    <li class="product__itemlist__item" id="diff_size_color">
                        <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$diff_dress[cnt].product_id|u}-->&category_id=<!--{$diff_dress[cnt].category_id|u}-->">
                            <figure class="product__itemlist__fig"><img loading="lazy" class="imgfull" src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$diff_dress[cnt].main_list_image|sfNoImageMainList|h}-->" alt="<!--{$diff_dress[cnt].name|h}-->">
                            </figure>
                            <span class="sizeColor"><!--{$diff_dress[cnt].comment}--></span>
                            </a>
                        </li>
                    <!--{/section}-->
                </ul>
            </div>
        </section><!-- // .product__cmnbox-->
    </div>
    <!--{/if}-->

    <!-- tab1 -->
    <div id="recommendTab" style="display:block;" class="recommend_wrap">
        <div class="recommend_blc">
            <header class="product__cmnhead">
                <h3 class="product__cmntitle">こんな方におすすめ！</h3>
            </header>

              <div class="product__commentbox">
                <div class="product__commentbox__child product__commentbox__child--comment">
                  <div class="product__commentbox__item"><!--{$arrProduct.recommended_staff_comment}-->
                    <!--{$arrProduct.main_comment}--><!--{* ←ワンピースの場合main_comment *}--></div>
                </div>
                <div class="product__commentbox__child product__commentbox__child--chara">
                  <figure class="avatar"><img loading="lazy" src="<!--{$smarty.const.URL_DIR}-->upload/staff_image/<!--{$arrProduct.recommended_staff_image}--><!--{if $recomment_flg == '0'}-->07291523_5d3e90d87b6e2.png<!--{/if}-->" alt="<!--{$arrProduct.recommended_staff_name}-->"></figure>
                  <div class="avatar__name">スタッフ<br><!--{$arrProduct.recommended_staff_name}--><!--{if $recomment_flg == '0'}-->セイラちゃん<!--{/if}--></div>
                </div>
              </div>
        </div>

        <div class="point_blc">
             <header class="product__cmnhead">
                <h3 class="product__cmntitle">コーデのポイント</h3>
            </header>

              <div class="product__commentbox">
                <div class="product__commentbox__child product__commentbox__child--comment">
                  <div class="product__commentbox__item"><!--{$arrProduct.coord_point_staff_comment}-->
                    <!--{$arrProduct.main_comment_point}--><!--{* ←ワンピースの場合main_comment_point *}--></div>
                </div>
                <div class="product__commentbox__child product__commentbox__child--chara">
                  <figure class="avatar"><img loading="lazy" src="<!--{$smarty.const.URL_DIR}-->upload/staff_image/<!--{$arrProduct.coord_point_staff_image}--><!--{if $recomment_flg == '0'}-->07291523_5d3e90d87b6e2.png<!--{/if}-->" alt="<!--{$arrProduct.coord_point_staff_name}-->"></figure>
                  <div class="avatar__name">スタッフ<br><!--{$arrProduct.coord_point_staff_name}--><!--{if $recomment_flg == '0'}-->セイラちゃん<!--{/if}--></div>
                </div>
              </div>
          </div>
    </div>
    
    <div style="display:block;">
    <!-- tab2 --><!-- spのみ表示 -->
    <div class="product_status_blc">
        <div class="product_status sp_show">
        </div>
    </div>

    <div class="product_size_wrap flex" style="display:block;">
    <!--{if $size_flg != 0}-->
    <!-- tab3 -->

    <div class="product_size_blc">
        <header class="product__cmnhead">
            <h3 class="product__cmntitle">サイズ</h3>
        </header>
        <div id="sizeTab" style="display:block;">
            <div class="tabInner">
              <div id="sizeWrap">
                <div class="sizeInner">
                    <h3 class="product__cmnsubtitle">対象サイズ</h3>
                <!--{* kids *}-->
                <!--{if $kidsSize != NULL}-->
                    <ul class="size_ul">
                    <!--{foreach key=key item=row from=$kind_of_size}-->
                        <li class="kindOfSize"><!--{$row}--><br>
                            <!--{if $key == $getKidsNum}-->
                                <span class="kids_size_on" style="font-size:20px;">●</span>
                            <!--{else}-->
                                <span class="kids_size_on"></span>
                            <!--{/if}-->
                        </li>
                        <!--{if $key == 5 || $key == 11 }--></ul><!--{/if}-->
                    <!--{/foreach}-->
                <!--{else}-->
                    <ul>
                    <!--{* 大人 *}-->
                    <!--{foreach key=key item=row from=$kind_of_size}-->
                        <!--{if $key|in_array:$arrProduct.arr_figure_detail}-->
                        <li><span><!--{$row}--></span><img loading="lazy" src="<!--{$TPL_DIR}-->img/size0<!--{$key}-->_on.png" <!--{if $key == 8}--> style="width:32px;" <!--{/if}--> ></li>
                        <!--{else}-->
                        <li><span class="nofit"><!--{$row}--></span>
                            <img loading="lazy" src="<!--{$TPL_DIR}-->img/size0<!--{$key}-->_off.png" <!--{if $key == 8}--> style="width:32px;" <!--{/if}--> >
                        </li>
                        <!--{/if}-->
                    <!--{/foreach}-->
                    </ul>
                    <div class="pb10">
                        <a href="<!--{$smarty.const.HTTPS_URL}-->user_data/size_guide.php">
                            <img loading="lazy" src="<!--{$TPL_URLPATH}-->img/size_btn_sp.jpg" alt="サイズガイドはこちら">
                        </a>
                    </div>
                <!--{/if}-->


                    <div><!--{if $kidsSize != NULL}--><!--{else}-->※基本的にドレスは１サイズのみ。<br />※サイズ違いがある場合、ページ上部に表示。<!--{/if}--></div>
                </div>
            </div>
            <!--// 対応バストカップ del 20160202-->
            <!--// 対応バストカップ end 20160202-->

            <div id="tallWrap">
                <div class="sizeInner">
                <!--{if $kidsSize != NULL}-->
                    <!--{if $silhouetteFlag == 1 || $arrProductCode[0]|mb_strpos:'16-' !== FALSE }-->
                        <h3 class="product__cmnsubtitle">このワンピースの着丈めやす</h3>
                        <ul>
                            <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength110.strLength`"}-->
                            <li><div style="height:90%;"><span class="nofit">110cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall01_off.png" alt="110cm"
                             style="margin-top:20px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                            <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength115.strLength`"}-->
                            <li><div style="height:90%;"><span class="nofit">115cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall02_off.png" alt="115cm"
                             style="margin-top:15px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                            <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength120.strLength`"}-->
                            <li><div style="height:90%;"><span class="nofit">120cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall03_off.png" alt="120cm"
                             style="margin-top:10px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                            <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength125.strLength`"}-->
                            <li><div style="height:90%;"><span class="nofit">125cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall04_off.png" alt="125cm"
                             style="margin-top:10px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                            <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength130.strLength`"}-->
                            <li><div style="height:90%;"><span class="nofit">130cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall05_off.png" alt="130cm"
                            　style="margin-top:5px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                        </ul>
                    <!--{else}-->
                        <!--{*boysは出さない*}-->
                    <!--{/if}-->
                <!--{else}-->
                    <h3 class="product__cmnsubtitle">このドレスの着丈めやす</h3>
                    <ul>
                        <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength150.strLength`"}-->
                        <li><div style="height:90%;"><span class="nofit">150cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall01_off.png" alt="150cm"
                        style="margin-top:20px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                        <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength155.strLength`"}-->
                        <li><div style="height:90%;"><span class="nofit">155cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall02_off.png" alt="155cm"
                        style="margin-top:15px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                        <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength160.strLength`"}-->
                        <li><div style="height:90%;"><span class="nofit">160cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall03_off.png" alt="160cm"
                        style="margin-top:10px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                        <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength165.strLength`"}-->
                        <li><div style="height:90%;"><span class="nofit">165cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall04_off.png" alt="165cm"
                        style="margin-top:10px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                        <!--{assign var=arrGarmentLength value="`$arrProduct.arrGarmentLength170.strLength`"}-->
                        <li><div style="height:90%;"><span class="nofit">170cm</span><img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/tall05_off.png" alt="170cm"
                        　style="margin-top:5px;"></div><span class="take mt10"><!--{$arrGarmentLength}--></span></li>
                    </ul>
                <!--{/if}-->
                    <!--//::N00161 del 20140515-->
                    <p class="mt30 fs12 lh16 sp_show">※前後左右の着丈が異なる場合、最短部分と最長部分を「◯cm〜◯cm」として表記</p>
                    <p class="mt20 fs14 pc_show">※前後左右の着丈が異なる場合、最短部分と最長部分を「◯cm〜◯cm」として表記</p>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--{/if}-->

    <!--{if $actual_size_flg != 0}-->
    <!-- tab4 -->
    <div class="actual_size_blc">
        <header class="product__cmnhead">
            <h3 class="product__cmntitle">商品実寸サイズ</h3>
        </header>
        <div id="actualTab" style="display:block;"> 
            <div class="tabInner">
                <table class="product__exactsize__table">
                    <tbody>

                        <!--{if $arrProduct.product_type == STOLE_PRODUCT_TYPE}-->
                        <tr>
                            <th class="td_size_main">身丈</th>
                            <th class="td_size_main">身幅</th>
                            <th class="td_size_main">肩幅</th>
                            <th class="td_size_main">ゆき丈</th>
                            <th class="td_size_main">アーム<br>ホール</th>
                            <th class="td_size_main">二の腕周り</th>
                            <!--{* <th class="td_size_main">袖の長さ</th> *}-->
                        </tr>
                        <tr>
                            <td>
                            <span <!--{if $arrProduct.shoulders_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.shoulders|nl2br}--></span>
                            <!--{if $arrProduct.shoulders_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.waist_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.waist|nl2br}--></span>
                            <!--{if $arrProduct.waist_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.bust_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bust|nl2br}--></span>
                            <!--{if $arrProduct.bust_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.bow_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bow_length|nl2br}--></span>
                            <!--{if $arrProduct.bow_length_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.arm_hole_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.arm_hole|nl2br}--></span>
                            <!--{if $arrProduct.arm_hole_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.ninoude_mawari_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.ninoude_mawari|nl2br}--></span>
                            <!--{if $arrProduct.ninoude_mawari_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                        </tr>

                        <!--{elseif $arrProduct.product_type != NECKLACE_PRODUCT_TYPE && $arrProduct.product_type != OTHERS_PRODUCT_TYPE}-->
                        <tr>
                            <th>バスト</th>
                            <th>アンダー<br />
                            バスト</th>
                            <th>ウェスト</th>
                            <th>ヒップ</th>
                            <th>着丈</th>
                            <th>肩幅</th>
                        </tr>
                        <tr>
                            <td>
                            <span <!--{if $arrProduct.bust_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bust|nl2br}--></span>
                            <!--{if $arrProduct.bust_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.under_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.under_text|nl2br}--></span>
                            <!--{if $arrProduct.under_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.waist_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.waist|nl2br}--></span>
                            <!--{if $arrProduct.waist_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.hip_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.hip|nl2br}--></span>
                            <!--{if $arrProduct.hip_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.garment_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.garment_length|nl2br}--></span>
                            <!--{if $arrProduct.garment_length_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.shoulders_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.shoulders|nl2br}--></span>
                            <!--{if $arrProduct.shoulders_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                        </tr>
                        <!--{/if}-->

                        <!--{if $arrProduct.product_type == STOLE_PRODUCT_TYPE}-->
                        <tr>
                            <th>袖の<br>長さ</td>
                            <th>袖口</td>
                            <th>&nbsp;</td>
                            <th>&nbsp;</td>
                            <th>ストール幅</td>
                            <th>ストール長さ</td>
                        </tr>
                        <tr>
                            <td>
                            <span <!--{if $arrProduct.garment_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.garment_length|nl2br}--></span>
                            <!--{if $arrProduct.garment_length_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.cuff_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.cuff|nl2br}--></span>
                            <!--{if $arrProduct.cuff_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                            <span <!--{if $arrProduct.shoulders_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.shoulders_length|nl2br}--></span>
                            <!--{if $arrProduct.shoulders_length_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.sleeve_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.sleeve_length|nl2br}--></span>
                            <!--{if $arrProduct.sleeve_length_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                        </tr>

                        <!--{elseif $arrProduct.product_type != NECKLACE_PRODUCT_TYPE && $arrProduct.product_type != OTHERS_PRODUCT_TYPE}-->
                        <tr>
                            <th>ゆき丈</th>
                            <th>アーム<br>ホール</th>
                            <th>二の腕周り</th>
                            <th>袖の<br>長さ</th>
                            <th>袖口</th>
                            <th>股下</th>
                        </tr>
                        <tr>
                            <td>
                            <span <!--{if $arrProduct.bow_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bow_length|nl2br}--></span>
                            <!--{if $arrProduct.bow_length_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.arm_hole_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.arm_hole|nl2br}--></span>
                            <!--{if $arrProduct.arm_hole_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.cuff_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.ninoude_mawari|nl2br}--></span>
                            <!--{if $arrProduct.ninoude_mawari_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.sleeve_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.sleeve_length|nl2br}--></span>
                            <!--{if $arrProduct.sleeve_length_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.cuff_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.cuff|nl2br}--></span>
                            <!--{if $arrProduct.cuff_flg == '1'}-->
                            <span>（やや細身）</span>
                            <!--{/if}-->
                            </td>
                            <td>
                            <span <!--{if $arrProduct.inseam_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.inseam|nl2br}--></span>
                            </td>
                        </tr>
                        <!--{/if}-->
                    </tbody>
                </table>

                <div>
                    <p>【補足事項】<br>
                    ※ドレスを平置きにて計測。<br>
                    <span class="price">※ご自身のサイズより３cmほどゆとりがあると着られます。<br></span>
                    <!--{if $arrProduct.tag != ""}-->
                    ※タグ表記は<!--{$arrProduct.tag}-->。<br>
                    ただし、ブランドにより異なるので、サイズは上の「対象サイズ」を参照して下さい。
                    <!--{/if}-->
                    </p>
                    <!--{if $arrProduct.size_supplement2 != ""}-->
                    <p>
                    ※<!--{$arrProduct.size_supplement2}-->が「～」となっているのは、ゴムが入っているから。
                    </p>
                    <!--{/if}-->
                    <!--{if $arrProduct.size_supplement3 != ""}-->
                    <p>
                    ※<!--{$arrProduct.size_supplement3}-->が「～」となっているのは、背中に調節ひもがついているから。フィットしやすいのでおすすめ。
                    </p>
                    <!--{/if}-->
                    <!--{if $arrProduct.size_supplement4 != ""}-->
                    <p>
                    ※着丈は、裾の透けている部分(<!--{$arrProduct.size_supplement4}--> cm)を含む。
                    </p>
                    <!--{/if}-->
                    <!--{if $arrProduct.important_points != ""}-->
                    <p>
                    ※<!--{$arrProduct.important_points}-->
                    </p>
                    <!--{/if}-->
                </div>
            </div>
        </div>
        <!--{/if}-->

            <!--{* 201806 add cere *}-->
            <!--{if $arrProductCode[0]|mb_strpos:'CM' !== FALSE OR $arrProductCode[0]|mb_strpos:'01-0419' !== FALSE}-->
            <div class="set_size">
                <p class="cere_haori_size">セットの羽織物</p>
                <div id="actualTab" style="display:block;">
                    <div class="tabInner">
                        <table class="product__exactsize__table">
                            <tbody>
                                <tr>
                                    <th class="td_size_main">身丈</th>
                                    <th class="td_size_main">身幅</th>
                                    <th class="td_size_main">肩幅</th>
                                    <th class="td_size_main">ゆき丈</th>
                                    <th class="td_size_main">アーム<br>ホール</th>
                                    <th class="td_size_main">二の腕周り</th>
                                </tr>
                                <tr>
                                    <td>
                                    <span <!--{if $arrProduct.shoulders_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bolero_shoulders|nl2br}--></span>
                                    <!--{if $arrProduct.shoulders_flg == '1'}-->
                                    <span>（やや細身）</span>
                                    <!--{/if}-->
                                    </td>
                                    <td>
                                    <span <!--{if $arrProduct.waist_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bolero_waist|nl2br}--></span>
                                    <!--{if $arrProduct.waist_flg == '1'}-->
                                    <span>（やや細身）</span>
                                    <!--{/if}-->
                                    </td>
                                    <td>
                                    <span <!--{if $arrProduct.bust_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bolero_bust|nl2br}--></span>
                                    <!--{if $arrProduct.bust_flg == '1'}-->
                                    <span>（やや細身）</span>
                                    <!--{/if}-->
                                    </td>
                                    <td>
                                    <span <!--{if $arrProduct.bolero_bow_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bolero_bow_length|nl2br}--></span>
                                    <!--{if $arrProduct.bolero_bow_length_flg == '1'}-->
                                    <span>（やや細身）</span>
                                    <!--{/if}-->
                                    </td>
                                    <td>
                                    <span <!--{if $arrProduct.bolero_arm_hole_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bolero_arm_hole|nl2br}--></span>
                                    <!--{if $arrProduct.bolero_arm_hole_flg == '1'}-->
                                    <span>（やや細身）</span>
                                    <!--{/if}-->
                                    </td>
                                    <td>
                                    <span <!--{if $arrProduct.bolero_ninoude_mawari_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bolero_ninoude_mawari|nl2br}--></span>
                                    <!--{if $arrProduct.bolero_ninoude_mawari_flg == '1'}-->
                                    <span>（やや細身）</span>
                                    <!--{/if}-->
                                    </td>
                                </tr>
                                <tr>
                                    <th>袖の<br>長さ</td>
                                    <th>袖口</td>
                                    <th>&nbsp;</td>
                                    <th>&nbsp;</td>
                                    <th>&nbsp;</td>
                                    <th>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                    <span <!--{if $arrProduct.garment_length_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.bolero_garment_length|nl2br}--></span>
                                    <!--{if $arrProduct.garment_length_flg == '1'}-->
                                    <span>（やや細身）</span>
                                    <!--{/if}-->
                                    </td>
                                    <td>
                                    <span <!--{if $arrProduct.cuff_flg == '1'}-->class="attention"<!--{/if}-->><!--{$arrProduct.cuff|nl2br}--></span>
                                    <!--{if $arrProduct.cuff_flg == '1'}-->
                                    <span>（やや細身）</span>
                                    <!--{/if}-->
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--{/if}-->
        </div>
    <!--{* 201806 add end *}-->
       
    <!-- tab2 -->
    <div class="recommend_wrap product_size_wrap product_status_blc" style="width:100%;">
    <!-- tab6 -->
      <div class="product_detail_info">
        <header class="product__cmnhead">
            <h3 class="product__cmntitle">商品詳細情報</h3>
        </header>
        <div id="detailTab">
            <div class="tabInner">
                <table>
                    <!--//::N00072 Add 20131010 ドレスのみ新レイアウト-->
                    <!--{if $arrProduct.product_type == DRESS_PRODUCT_TYPE || $arrProduct.product_type == SET_DRESS_PRODUCT_TYPE || $arrProduct.product_type == "4"}-->
                    <tr>
                      <th class="th_main">カラー</th>
                      <td class="td_main"><!--{$arrProduct.dress_color|escape}--></td>
                    </tr>
                    <!--{/if}-->
                    <!--//::N00072 end 20131010 ドレスのみ新レイアウト-->
                    <tr>
                      <th class="th_main">素材</th>
                      <td class="td_main"><!--{$arrProduct.item_materrial|escape}--></td>
                    </tr>
                    <tr>
                      <th class="th_main">生地の厚さ</th>
                      <td class="td_main"><!--{$arrProduct.thickness_type|escape}--></td>
                    </tr>
                    <tr>
                      <th class="th_main">裏地</th>
                      <td class="td_main"><!--{$arrProduct.liner_type|escape}--></td>
                    </tr>
                    <tr>
                      <th class="th_main">ファスナー</th>
                      <td class="td_main"><!--{$arrProduct.fastener_type|escape}--></td>
                    </tr>
                    <tr>
                      <th class="th_main">注意事項</th>
                      <td class="td_main">
                        <table class="sub_table">
                            <!--{foreach from=$arrProduct.arr_important_points_ids key=k item=id}-->
                                <tr>
                                  <td style="padding:0 5px 0 0;">
                                    <!-- <input type="checkbox" name="q" style="margin:3px 5px 0 0;" disabled="1" checked="1"/> -->
                                    <img loading="lazy" src="<!--{$TPL_DIR}-->img/check_icon.png" alt="注意事項" />
                                  </td>
                                  <td>
                                      <!--{if $id == 20}--><!-- その他の場合 -->
                                          その他<br>
                                          <!--{$arrProduct.important_points|nl2br}-->
                                      <!--{else}-->
                                          <!--{$arrImportanPoint[$id]}-->
                                      <!--{/if}-->
                                  </td>
                                </tr>
                          <!--{/foreach}-->
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <th class="th_last">付属品</th>
                      <td class="td_last"><!--//::N00062 Add 20130531-->
                          <!--{if $arrProduct.set_content == "なし" && $arrProduct.set_content4 == "なし"}-->
                              なし
                          <!--{elseif $arrProduct.set_content == "なし"}-->
                              <!--{$arrProduct.set_content4|escape}-->
                              <!--{elseif $arrProduct.set_content4 == "なし"}-->
                              <!--{$arrProduct.set_content|escape}-->
                          <!--{else}-->
                              <!--{$arrProduct.set_content|escape}-->/<!--{$arrProduct.set_content4|escape}-->
                          <!--{/if}-->
                      </td>
                    </tr>
                </table>
            </div>
        </div>
      </div>
        <div class="product_status">
            <header class="product__cmnhead">
                <h3 class="product__cmntitle">商品の状態</h3>
            </header>
            <div id="statusTab" style="display:block;">
                <div class="tabInner">
                    <table class="status">
                        <thead>
                            <tr>
                                <th colspan="4">
                                    <!--{section name=flg loop=$arrProduct.product_flag|count_characters}-->
                                    <!--{if $arrProduct.product_flag[flg] == "1"}-->
                                    <!--{assign var=key value="`$smarty.section.flg.iteration`"}-->
                                    <img loading="lazy" src="<!--{$TPL_DIR}--><!--{$arrSTATUS_IMAGE[$key]}-->" alt="商品の状態" id="icon<!--{$key}-->" style="text-align: center;">
                                    <!--{/if}-->
                                    <!--{/section}-->
                                </th>
                            </tr>
                        </thead>
                        <!--{if $arrImagePathsDress.image_front == NULL && $arrImagePathsDress.image_back == NULL ||
                        strpos($arrImagePathsDress.image_front,'img_blank.gif') !== false ||
                        strpos($arrImagePathsDress.image_back,'img_blank.gif') !== false
                        }-->
                        </table>
                        <!--{else}-->
                        <tbody>
                            <tr>
                                <th colspan="2" class="shoumen">正面図</th>
                                <th colspan="2" class="haimen">背面図</th>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <!--{if $arrImagePathsDress.image_front != NULL}-->
                                    <img loading="lazy" width="<!--{$smarty.const.INSPECT_IMAGE_WIDTH}-->px" src="<!--{$smarty.const.URL_DIR}--><!--{$arrImagePathsDress.image_front}-->" alt="正面図">
                                    <!--{else}-->
                                    <img loading="lazy" width="<!--{$smarty.const.INSPECT_IMAGE_WIDTH}-->px" src="<!--{$smarty.const.URL_DIR}-->misc/blank.gif}-->" alt="正面図">
                                    <!--{/if}-->
                                </td>
                                    <td colspan="2">
                                    <!--{if $arrImagePathsDress.image_back != NULL}-->
                                    <img loading="lazy" width="<!--{$smarty.const.INSPECT_IMAGE_WIDTH}-->px" src="<!--{$smarty.const.URL_DIR}--><!--{$arrImagePathsDress.image_back}-->" alt="背面図">
                                    <!--{else}-->
                                    <img loading="lazy" width="<!--{$smarty.const.INSPECT_IMAGE_WIDTH}-->px" src="<!--{$smarty.const.URL_DIR}-->misc/blank.gif}-->" alt="背面図">
                                    <!--{/if}-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
<!--{if $maxCount_f > 0 || $maxCount_b > 0}-->
                    <table class="status">
                        <tbody>
                            <tr>
                                <th colspan="4" class="shoumen"><h4>正面</h4></th>
                            </tr>
                            <tr class="status_item">
                                <th>年月日</th>
                                <th colspan="2" class="condition">状態</th>
                                <th>評価</th>
                            </tr>
                            <!--{foreach key=key item=row from=$tmpHistory}-->
                            <tr><!--{if $row.injured_place ne ''}-->
                                <td><!--{$row.front_date}--></td>
                                <td colspan="2" class="condition">
                                <!--{$row.injured_place}-->に<!--{if $row.diameter_flg == 1}-->直径<!--{/if}--><!--{$row.scratch_size}--><!--{$row.front_status}--><!--{$row.txt_remarks}--></td>
                                <td><!--{$arrEvaluate[$row.front_test]}--></td>
                                <!--{/if}-->
                            </tr>
                            <!--{/foreach}-->
                            <tr>
                                <th colspan="4" class="haimen"><h4>背面</h4></th>
                            </tr>
                            <tr class="status_item">
                                <th>年月日</th>
                                <th colspan="2" class="condition">状態</th>
                                <th>評価</th>
                            </tr>
                            <!--{foreach key=key item=row from=$tmpHistory_b}-->
                            <tr>
                                <td><!--{$row.back_date}--></td>
                                <td colspan="2" class="condition">
                                <!--{$row.back_injured_place}-->に<!--{if $row.diameter_flg == 1}-->直径<!--{/if}--><!--{$row.back_scratch_size}--><!--{$row.back_status}--><!--{$row.txt_remarks}--></td>
                                <td><!--{$arrEvaluate[$row.back_test]}--></td>
                            </tr>
                            <!--{/foreach}-->
                        </tbody>
                    </table>
                        <!--{/if}-->
<!--{/if}-->
                    <!--{* 201806 add cere *}-->
                    <!--{if $arrProductCode[0]|mb_strpos:'CM' !== FALSE}-->
                    <p class="cere_haori_size">セットの羽織物</p>
                    <table class="status">
                        <thead>
                            <tr>
                                <th colspan="4">
                                    <!--{section name=flg loop=$arrProduct.product_flag|count_characters}-->
                                    <!--{if $arrProduct.product_flag[flg] == "1"}-->
                                    <!--{assign var=key value="`$smarty.section.flg.iteration`"}-->
                                    <img loading="lazy" src="<!--{$TPL_DIR}--><!--{$arrSTATUS_IMAGE[$key]}-->" alt="商品の状態" id="icon<!--{$key}-->" style="text-align: center;">
                                    <!--{/if}-->
                                    <!--{/section}-->
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th colspan="2" class="shoumen">正面図</th>
                            <th colspan="2" class="haimen">背面図</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <img loading="lazy" width="<!--{$smarty.const.INSPECT_IMAGE_WIDTH}-->px" src="<!--{$smarty.const.URL_DIR}--><!--{$arrImagePathsStole.image_front}-->" alt="正面図">
                            </td>
                            <td colspan="2">
                                <img loading="lazy" width="<!--{$smarty.const.INSPECT_IMAGE_WIDTH}-->px" src="<!--{$smarty.const.URL_DIR}--><!--{$arrImagePathsStole.image_back}-->" alt="背面図">
                            </td>
                        </tr>
                        <tr><td colspan="4"><h4>背面</h4></td></tr>
                        <tr>
                            <th>年月日</th>
                            <th>状態</th>
                            <th>年月日</th>
                            <th>状態</th>
                        </tr>
                        <!--{foreach key=key item=row from=$tmpStoleHistory}-->
                        <!--{assign var=id value=$row.history_id}-->
                        <tr>
                            <td><!--{$row.front_date_stole}--></td>
                            <td><!--{$row.front_status_stole}--></td>
                            <td><!--{$row.back_date_stole}--></td>
                            <td><!--{$row.back_status_stole}--></td>
                        </tr>
                        <!--{/foreach}-->
                        </tbody>
                    </table>
                    <!--{/if}-->
                    <!--{* 201806 add end *}-->
                    <!--{* 2020.02 add *}-->
                    <div id="sizeWrap" style="padding:15px 0;">
                        <P style="text-align:center;">\初めての方、ご不安な方におすすめです！/</P>
                        <a href="<!--{$smarty.const.HTTPS_URL}-->user_data/relief.php">
                        <img loading="lazy" src="<!--{$TPL_DIR}-->img/wanpi_500yen_SP.jpg" alt="「汚れ・傷」あんしん保証プラン">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="recommend_wrap product_size_wrap product_status_blc" style="width:100%;">    
    <!--{if $model_flg != 0}-->
    <!-- tab5 -->
    <div class="product_comment_blc">
        <header class="product__cmnhead">
        <h3 class="product__cmntitle">スタッフ着用コメント</h3>
        </header>
        <div id="staffTab" style="display:block;">
            <div class="tabInner">
                <div id="staffTabInner">
                    <!--{if $arrModel1.name != ""}-->
                    <div id="staffComLeft">
                        <p><span class="sizeCom">○<!--{$arrModel1.size}-->サイズ</span>のスタッフが着ると<span class="sizeCom"><!--{$arrWEARRANK[$arrProduct.wear_comment_wearrank1]}--></span></p>

                        <div class="product__staffcomment__size">
                            <table class="product__staffcomment__table">
                            <tr>
                            <th>身長</th>
                            <td><!--{$arrModel1.height}-->cm</td>
                            <th>バストカップ</th>
                            <td><!--{$arrModel1.under_cup}--></td>
                            <th>バスト</th>
                            <td><!--{$arrModel1.bust}-->cm</td>
                            </tr>
                            <tr>
                            <th>ウエスト</th>
                            <td><!--{$arrModel1.waist}-->cm</td>
                            <th>ヒップ</th>
                            <td><!--{$arrModel1.hip}-->cm</td>
                            <th>アンダー</th>
                            <td><!--{$arrModel1.under}-->cm</td>
                            </tr>
                            </table>
                        </div>

                        <div class="product__staffcomment__comment">
                            <div class="product__staffcomment__avatar">

                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size02_off.png" alt="スタッフ">
                                <!--{*
                                <!-- きちんと登録されてない人がいるので全キャラ共通にする -->

                                <!--{if $arrModel1.size|lower == 'ss'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size01_off.png" alt="SSサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel1.size|lower == 's'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size02_off.png" alt="Sサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel1.size|lower == 'm'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size03_off.png" alt="Mサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel1.size|lower == 'l'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size04_off.png" alt="Lサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel1.size|lower == 'll'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size05_off.png" alt="LLサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel1.size|lower == '3l'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size06_off.png" alt="3Lサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel1.size|lower == '4l'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size06_off.png" alt="4Lサイズスタッフ">
                                <!--{/if}-->
                                *}-->
                            </div>
                            <div class="product__staffcomment__cont"><!--{$arrProduct.wear_comment1}--></div>
                        </div>

                        <!--//::N00199 Add 20140717-->
                        <form name="form_staff1" id="form_staff1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
                            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                            <input type="hidden" name="staff1_id" value="<!--{$arrProduct.wear_comment_model1}-->" />
                            <!--//delete 20160216 このスタッフが着用した商品一覧 -->
                        </form>
                        <!--//::N00199 end 20140717-->
                    </div>
                    <!--{/if}-->

                    <!--{if $arrModel2.name != ""}-->
                    <div id="staffComRight">

                        <p><span class="sizeCom">○<!--{$arrModel2.size}-->サイズ</span>のスタッフが着ると<span class="sizeCom"><!--{$arrWEARRANK[$arrProduct.wear_comment_wearrank2]}--></span></p>

                        <div class="product__staffcomment__size">
                            <table class="product__staffcomment__table">
                            <tr>
                            <th>身長</th>
                            <td><!--{$arrModel2.height}-->cm</td>
                            <th>バストカップ</th>
                            <td><!--{$arrModel2.under_cup}--></td>
                            <th>バスト</th>
                            <td><!--{$arrModel2.bust}-->cm</td>
                            </tr>
                            <tr>
                            <th>ウエスト</th>
                            <td><!--{$arrModel2.waist}-->cm</td>
                            <th>ヒップ</th>
                            <td><!--{$arrModel2.hip}-->cm</td>
                            <th>アンダー</th>
                            <td><!--{$arrModel2.under}-->cm</td>
                            </tr>
                            </table>
                        </div>

                        <div class="product__staffcomment__comment">
                            <div class="product__staffcomment__avatar">

                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size02_off.png" alt="スタッフ">

                                <!--{*
                                <!-- きちんと登録されてない人がいるので全キャラ共通にする -->
                                <!--{if $arrModel2.size|lower == 'ss'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size01_off.png" alt="SSサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel2.size|lower == 's'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size02_off.png" alt="Sサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel2.size|lower == 'm'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size03_off.png" alt="Mサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel2.size|lower == 'l'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size04_off.png" alt="Lサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel2.size|lower == 'll'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size05_off.png" alt="LLサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel2.size|lower == '3l'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size06_off.png" alt="3Lサイズスタッフ">
                                <!--{/if}-->
                                <!--{if $arrModel2.size|lower == '4l'}-->
                                <img loading="lazy" src="<!--{$TPL_URLPATH}-->/img/size06_off.png" alt="4Lサイズスタッフ">
                                <!--{/if}-->
                                *}-->
                            </div>
                            <div class="product__staffcomment__cont"><!--{$arrProduct.wear_comment2}--></div>
                        </div>
                    </div>
                    <!--{/if}-->
                </div>
            </div>
        </div>
    <!--{/if}-->
    <!--{if $movie_flg != 'off'}-->
        <header class="product__cmnhead">
            <h2 class="product__cmntitle">スタッフ着用動画</h3>
        </header>
        <div class="yt">
            <div class="yt_video" youtube="https://www.youtube.com/embed/<!--{$arrProduct.funct_flag}-->?rel=0&mute=1">
                <img loading="lazy" src="<!--{$TPL_URLPATH}-->img/movie_image.png" alt="着用動画" class="movie_image">
            </div>
        </div>
        <script type="text/javascript">
            $('.yt_video').click(function(){
                video = '<iframe src="'+ $(this).attr('youtube') +'"frameborder="0" playsinline="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; fullscreen; picture-in-picture"></iframe>';
                $(this).replaceWith(video);
            });
        </script>
        <p class="movieText">※ 映像の色味と多少異なる場合がございます。<br>着用した時のイメージとしてご覧ください。</p>
    <!--{/if}-->
    </div>

    <div class="product_status_blc">
        <section id="a_review">
        <!--{if count($arrReview) > 0}-->
        <header class="product__cmnhead">
            <h3 class="product__cmntitle">お客様からの商品レビュー</h3>
        </header>

        <div class="sectionInner">
            <div id="totalReviewWrap" class="product__review__head">
                <div class="product__review__title">みなさまの平均レビュー</div>
                <div class="clearfix">
                    <div class="ratingblock">
                        <div class="rating__stars" style="width:<!--{$arrProduct.womens_review_avg*30}-->px;"></div>
                    </div><!-- // .ratingblock -->
                    <span class="rating__score">平均<!--{$arrProduct.womens_review_avg|round:1}--></span>
                </div>
                <div class="product__review__count">レビュー数：<!--{$arrProduct.womens_review_count}-->件</div>
            </div><!-- // #totalReviewWrap -->
            
              <div class="product__review__voice">
                <ul class="product__review__voice__grp js-uservoicecontent">
                    <!--{section name=cnt loop=$arrReview}-->
                        <li class="product__review__voice__item">
                        <div class="uservoice__head">
                            <div class="uservoice__head__score">
                                <div class="ratingblock">
                                    <ul class="unit-rating">
                                        <li style="width:<!--{$arrReview[cnt].recommend_level*15}-->px; height:15px;" class="current-rating"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="uservoice__size"><!--{$arrReview[cnt].title2|escape}--></div>
                            <div class="uservoice__age"><!--{$arrReview[cnt].title1|escape}--></div>
                            <div class="uservoice__date"><!--{$arrReview[cnt].create_date|sfDispDBDate:false}--></div>
                            <ul class="uservoice__scene">
                                <li class="uservoice__size1">シーン ></li>
                                <li class="uservoice__size2">
                                    <!--{if $arrReview[cnt].use_scene1 == "1"}-->結婚式<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "2"}-->二次会<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "3"}-->婚活<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "4"}-->卒業式<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "5"}-->入学式<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "6"}-->謝恩会<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "7"}-->パーティ<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "8"}-->同窓会<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "9"}-->デート<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "10"}-->両家挨拶<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "11"}-->お宮参り<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "12"}-->発表会<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "13"}-->仕事関係<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "14"}-->子供行事<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "15"}-->受験<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene1 == "16"}-->演奏会<!--{/if}-->
                                </li>
                                <!--{if $arrReview[cnt].use_scene3 != ""}-->
                                <li class="uservoice__size3">会場 ></li>
                                <li class="uservoice__size4">
                                    <!--{if $arrReview[cnt].use_scene3 == "1"}-->結婚式場<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene3 == "2"}-->ホテル<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene3 == "3"}-->ゲストハウス<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene3 == "4"}-->レストラン<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene3 == "5"}-->ダイニングバー<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene3 == "6"}-->神社<!--{/if}-->
                                    <!--{if $arrReview[cnt].use_scene3 == "7"}-->学校<!--{/if}-->
                                <!--{else}-->
                                <li style="padding:0;">
                                <!--{/if}-->
                                </li>
                            </ul>
                            <div class="uservoice__body">
                                <!--{$arrReview[cnt].comment|h|nl2br}-->
                            </div>
                            <!--{if $arrReview[cnt].recomment_status == 1}-->
                            <ul class="review_recomment">
                                <li colspan="2">
                                    <h4>ワンピの魔法からご返信</h4>
                                    <p><!--{$arrReview[cnt].recomment|escape|nl2br}--></p>
                                </li>
                            </ul>
                            <!--{/if}-->

                            <!--{if $arrReview[cnt].order_id}-->
                            <!--{assign var=review_id value=$arrReview[cnt].review_id}-->
                                <!--{if !empty($arrReviewProducts[$review_id])}-->
                                    <div class="uservoice__with">
                                        <div class="uservoice__with__label">一緒にレンタルされた商品</div>
                                        <ul class="uservoice_with_rent">
                                            <!--{section name=pro loop=$arrReviewProducts[$review_id]}-->
                                                <li class="product__itemlist__item">
                                                  <!--{if $arrReviewProducts[$review_id][pro].product_id}-->
                                                  <a class="product__itemlist__link" href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrReviewProducts[$review_id][pro].product_id|u}-->&category_id=<!--{$sub_cate_id}-->">
                                                    <figure class="product__itemlist__fig" style="width:18%;"><img loading="lazy" class="imgfull" src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$arrReviewProducts[$review_id][pro].main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrReviewProducts[$review_id][pro].product_name|escape}-->"></figure>
                                                    <div class="product_width_itemlist">
                                                      <div class="product__itemlist__title"><!--{$arrReviewProducts[$review_id][pro].product_name|escape}--></div>
                                                      <!--{*
                                                      <div class="product__itemlist__price">価格を出力する</div>
                                                      <div class="product__itemlist__code">商品コードを出力する</div>
                                                      *}-->
                                                    </div>
                                                  </a>
                                                  <!--{/if}-->
                                                </li>
                                            <!--{/section}-->
                                        </ul>
                                    </div><!-- // .uservoice__with -->
                                <!--{/if}-->
                            <!--{/if}-->
                      </li><!-- // .product__review__voice__item-->
                  <!--{/section}-->
    
                </ul><!-- // .product__review__voice__grp-->
                <div class="widebtnarea">
                    <div class="btnbox"><a class="btn btn--white btn--normal js-viewmorecommentbtn" href="#"><span class="btn__label">もっと見る</span></a></div>
                </div>
        </div><!-- // .product__review__voice-->
        <!--{/if}-->
    </div>
</div>
</section>

        <!--{if !empty($arrRecommendCoordinate)}-->
        <section>
            <div class="product_coodinate_blc">
                <header class="product__cmnhead">
                    <h3 class="product__cmntitle">モデルコーディネートで使用した商品</h3>
                </header>
                <div class="product__corditem">
                    <ul class="product_itemlist_grp" style="width:100%;">
                        <!--{section name=cnt loop=$arrRecommendCoordinate}-->
                        <li class="product_itemlist_item">
                            <!--{if $arrRecommendCoordinate[cnt].product_id}-->
                            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrRecommendCoordinate[cnt].product_id|u}-->"class="product__itemlist__link">
                                <figure class="product__itemlist__fig">
                                    <img loading="lazy" src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$arrRecommendCoordinate[cnt].main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrRecommendCoordinate[cnt].name|h}-->" class="imgfull">
                                </figure>
                                <!--{assign var=price02_min value=`$arrRecommendCoordinate[cnt].price02_min`}-->
                                <!--{assign var=price02_max value=`$arrRecommendCoordinate[cnt].price02_max`}-->
                                <div class="product__itemlist__detail">
                                    <div class="product__itemlist__price"><span class="fs10">商品コード:</span><br><!--{$arrRecommendCoordinate[cnt].product_code|h}-->
                                        <p class="mt10 mb0"><!--{if $price02_min == $price02_max}-->
                                        <!--{$price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                                        <!--{else}-->
                                        <!--{$price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price02_max|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                                        <!--{/if}-->円(税込)</p>
                                    </div>
                                </div>
                            </a>
                            <!--{/if}-->
                        </li>
                        <!--{/section}-->
                    </ul>
                </div><!-- // .product__corditem-->

            </div>
        </section><!-- // .product__cmnbox-->
        <!--{/if}-->

        <!--{if !empty($arrRelated)}--><!-- PC版  -->
        <section class="product__cmnbox">
            <header class="product__cmnhead">
                <h3 class="product__cmntitle">一緒にレンタルされた商品</h3>
            </header>
            <div class="product__corditem">
            <ul class="product_itemlist_grp" style="width:100%;">
            <!--{section name=cnt loop=$arrRelated}-->
            <li class="product_itemlist_item">
            <!--{if $arrRelated[cnt].product_id}-->
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrRelated[cnt].product_id|u}-->" class="product__itemlist__link">
            <figure class="product__itemlist__fig">
            <img loading="lazy" src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$arrRelated[cnt].main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrRelated[cnt].name|h}-->" class="imgfull">
            </figure>
            <!--{assign var=price02_min value=`$arrRelated[cnt].price02_min`}-->
            <!--{assign var=price02_max value=`$arrRelated[cnt].price02_max`}-->
            <div class="product__itemlist__detail">
            <div class="product__itemlist__price"><span class="fs10">商品コード:</span><br><!--{$arrRelated[cnt].product_code|h}-->

            <p class="mt10 mb0">
            <!--{if $price02_min == $price02_max}-->
            <!--{$price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
            <!--{else}-->
            <!--{$price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price02_max|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
            <!--{/if}-->
            円(税込)
            </p>
            </div>
            </div>
            </a>
            <!--{/if}-->
            </li>
            <!--{/section}-->

            </ul>
            </div>
        </section><!-- //.product__cmnbox-->
        <!--{/if}-->

        <!--{if !empty($sameColorProducts) && $arrProduct.product_type != NECKLACE_PRODUCT_TYPE && $arrProduct.product_type != OTHERS_PRODUCT_TYPE}-->
        <section class="product__cmnbox">
            <header class="product__cmnhead">
                <h3 class="product__cmntitle">似たカラーでオススメの商品</h3>
            </header>
            <div class="product__corditem">
            <ul class="product_itemlist_grp" style="width:100%;">
            <!--{section name=cnt loop=$sameColorProducts}-->
                <li class="product_itemlist_item">
                <!--{if $sameColorProducts[cnt].product_id}-->
                <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$sameColorProducts[cnt].product_id|u}-->" class="product__itemlist__link">
                    <figure class="product__itemlist__fig">
                    <img loading="lazy" src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$sameColorProducts[cnt].main_list_image|sfNoImageMainList|h}-->" alt="<!--{$sameColorProducts[cnt].name|h}-->" class="imgfull">
                    </figure>
                    <div class="product__itemlist__detail">
                        <div class="product__itemlist__price"><span class="fs10">商品コード:</span><br><!--{$sameColorProducts[cnt].product_code|h}-->
                            <p><!--{$sameColorProducts[cnt].color_price|number_format}-->円(税込)</p>
                        </div>
                    </div>
                </a>
                <!--{/if}-->
                </li>
            <!--{/section}-->
            </ul>
            </div>
        </section>
        <!--{/if}-->
<!--{* 最新のドレスから６着出すと予約が埋まっているため一旦非表示に。
        <!--{if !empty($sameSizeProducts) && $arrProduct.product_type != NECKLACE_PRODUCT_TYPE && $arrProduct.product_type != OTHERS_PRODUCT_TYPE}-->
        <section class="product__cmnbox">
            <header class="product__cmnhead">
                <h3 class="product__cmntitle">同じサイズでオススメの商品</h3>
            </header>
            <div class="product__corditem">
            <ul class="product_itemlist_grp" style="width:100%;">
            <!--{section name=cnt loop=$sameSizeProducts}-->
                <li class="product_itemlist_item">
                <!--{if $sameSizeProducts[cnt].product_id}-->
                <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$sameSizeProducts[cnt].product_id|u}-->" class="product__itemlist__link">
                    <figure class="product__itemlist__fig">
                    <img loading="lazy" src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$sameSizeProducts[cnt].main_list_image|sfNoImageMainList|h}-->&amp;width=160&amp;height=240" alt="<!--{$sameSizeProducts[cnt].name|h}-->" class="imgfull">
                    </figure>
                    <div class="product__itemlist__detail">
                        <div class="product__itemlist__price"><span class="fs10">商品コード:</span><br><!--{$sameSizeProducts[cnt].product_code|h}-->
                            <p><!--{$sameSizeProducts[cnt].price02|number_format}-->円(税込)</p>
                        </div>
                    </div>
                </a>
                <!--{/if}-->
                </li>
            <!--{/section}-->
            </ul>
            </div>
        </section>
        <!--{/if}-->
*}-->
            <div class="widebtnarea">
                <div class="btnbox"><a class="btn btn--white btn--normal" href="/products/smart_search.php"><span class="btn__label fs14">商品を絞り込んで探す</span></a></div>
            </div>

</div>

<script src="<!--{$smarty.const.ROOT_URLPATH}-->js/products.js"></script>
<script src="<!--{$smarty.const.ROOT_URLPATH}-->js/detail.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/201303/detailtab.js"></script>
<script src="<!--{$TPL_URLPATH}-->js/jquery.facebox/facebox.js"></script>

<!-- RCHJ 2013.06.17 Add Calendar -->
<!-- 2012.05.14 RCHJ Add -->
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/bgiframe.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.core.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.widget.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.datepicker_user.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.datepicker-ja_user.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.mouse.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.button.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.draggable.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.position.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.resizable.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/effects.core.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui-custom.js"></script>
<script>//<![CDATA[
	//セレクトボックスに項目を割り当てる。
	// Add RCHJ 2013.06.15
	function lnSetSelect(form, name1, name2, val) {
		return;

        sele11 = document[form][name1];
        sele12 = document[form][name2];

        if(sele11 && sele12) {
			index = sele11.selectedIndex;

			// セレクトボックスのクリア
			count = sele12.options.length;
			for(i = count; i >= 0; i--) {
            	sele12.options[i] = null;
			}

			// セレクトボックスに値を割り当てる
			len = lists[index].length;
			for(i = 0; i < len; i++) {
				sele12.options[i] = new Option(lists[index][i], vals[index][i]);
				if(val != "" && vals[index][i] == val) {
					sele12.options[i].selected = true;
				}
			}
        }
	}

    // 規格2に選択肢を割り当てる。
    function fnSetClassCategories(form, classcat_id2_selected) {
        var $form = $(form);
        var product_id = $form.find('input[name=product_id]').val();
        var $sele1 = $form.find('select[name=classcategory_id1]');
        var $sele2 = $form.find('select[name=classcategory_id2]');
        setClassCategories($form, product_id, $sele1, $sele2, classcat_id2_selected);
    }
    $(function(){
        $('#detailphotoblock ul li').flickSlide({target:'#detailphotoblock>ul', duration:5000, parentArea:'#detailphotoblock', height: 200});
        $('#whobought_area ul li').flickSlide({target:'#whobought_area>ul', duration:5000, parentArea:'#whobought_area', height: 80});
        $('#whobought_area_size ul li').flickSlide({target:'#whobought_area_size>ul', duration:5000, parentArea:'#whobought_area_size', height: 80});

        //お勧め商品のリンクを張り直し(フリックスライドによるエレメント生成後)
        $('#whobought_area li').biggerlink();
        $('#whobought_area_size li').biggerlink();

        //商品画像の拡大
        $('a.expansion').facebox({
            loadingImage : '<!--{$TPL_URLPATH}-->js/jquery.facebox/loading.gif',
            closeImage   : '<!--{$TPL_URLPATH}-->js/jquery.facebox/closelabel.png'
        });
    });
    //サブエリアの表示/非表示
    var speed = 500;
    var stateSub = 0;
    function fnSubToggle(areaEl, imgEl) {
        areaEl.slideToggle(speed);
        if (stateSub == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateSub = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateSub = 0;
        }
    }
    //この商品に対するお客様の声エリアの表示/非表示
    var stateReview = 0;
    function fnReviewToggle(areaEl, imgEl) {
        areaEl.slideToggle(speed);
        if (stateReview == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateReview = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateReview = 0;
        }
    }
    //お勧めエリアの表示/非表示
    var statewhobought = new Array(0,0);
    function fnWhoboughtToggle(areaEl, imgEl, index) {
        areaEl.slideToggle(speed);
        if (statewhobought[index] == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            statewhobought[index] = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            statewhobought[index] = 0;
        }
    }
//]]></script>

<!-- RCHJ 2013.06.17 Add Calendar -->
<!-- 2012.05.14 RCHJ Add -->
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/jquery.ui/jquery.ui.all.css" type="text/css" media="all" />
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/jquery.ui/jquery.ui.theme_custom.css" type="text/css"/>
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/calendar.css" type="text/css"/>
<style type="text/css">
/* title style */
.fc-header-title h2{
	background: none;
    font-size: 22px;
    line-height: 100%;
    margin: 0;
    padding: 0;
    width: 100%;
}
.ui-dialog .ui-dialog-titlebar {
	background-image: url("<!--{$TPL_DIR}-->img/products/new/yoyaku_title.png");
    padding: 0.24em 1em;
    position: relative;
    border:none!important;
    margin:1em;
}
.ui-dialog .ui-dialog-buttonpane {
    background-image: none;
    border-width: 0;
    margin: 0.5em 0 0;
    padding: 0.3em 1em 0.5em 0.4em;
    text-align: left;
}
.ui-widget-header .ui-icon {
    background-image: url("<!--{$TPL_DIR}-->img/products/new/yoyaku_title_close.png")!important;
    height: 18px;
    width: 18px;
    background-position: 0;
}
.ui-dialog .ui-dialog-titlebar-close {
    height: 18px;
    margin: -10px 0 0;
    padding: 0;
    position: absolute;
    right: 0.3em;
    top: 50%;
    width: 18px;
}
.ui-dialog .ui-dialog-titlebar-close span {
    display: block;
    margin: 0;
}
/* button style */
.ui_text_image0{
	background-image: url("<!--{$TPL_DIR}-->img/products/new/yoyaku_back_on.png");
	width:149px;
	height:40px;
	padding:0!important;
}
.ui_text_image1{
	background-image: url("<!--{$TPL_DIR}-->img/products/new/yoyaku_kakunin_button_on.png");
	width:274px;
	height:40px;
	padding:0!important;
}
/* calenda */
.ui-icon {
	width: 18px;
	height: 18px;
	background-image: url(<!--{$TPL_DIR}-->css/201303/images/icons-18-white.png)/*{iconsContent}*/;
}
.fontS{
	height: 30px;
	text-align:left;
	line-height: 1.5;
	padding-bottom: 10px;
	margin-left: 5px;
	font-size: 9px!important;
}
.colorRed{color: red;}
.resStart{text-align: left; margin-left: 5px;font-size: 12px;font-weight: bolder;}
</style>

<script type='text/javascript'>
	var my_datepicker_0;
	var my_datepicker_1;
	var my_datepicker_2;
	var parsed_limit_date; 

	function processLinkDate(show_date){
		var bln_return = false;
		var style = '';
		var cur_date = show_date.getFullYear()+"-"+String('0'+(show_date.getMonth()+1)).slice(-2)+"-"+String('0'+show_date.getDate()).slice(-2) ;
		// manual impossible day
		if(rental_manual_impossible_date[cur_date]){
			bln_return = false;
			style = 'ui-state-red';
		}

		// ======================rental2=================
		var date_temp2 = addDays(show_date, -3);
		date_temp2 = date_temp2.getFullYear()+"-"+String('0'+(date_temp2.getMonth()+1)).slice(-2)+"-"+String('0'+date_temp2.getDate()).slice(-2) ;
		// possible day
		if(rental_possible_date[date_temp2]){
			bln_return = true;
			style = '';
		}

		// =================ready rental day=================
		if(rental_impossible_date[cur_date]){
			bln_return = false;
			return [bln_return, 'ui-state-red'];
		}

        // add ishibashi
        if ($('#search_rendal_date').val() !== '')
        {
            if (cur_date === $('#search_rendal_date').val())
            {
                bln_return = false;
			    style = 'current_date';
            }
        }

		if(!bln_return){
			var diff_day = DateDiff.inDays(show_date, parsed_limit_date);
			if(diff_day <= 0){
				style = 'unreserve';
			}
		}
		return [bln_return, style];
	}

	function calcMoney(index){
		var date = $("#date"+index).val();

		$("#price").html(numberFormat($("#hdn_price").val()));
		if(rental_possible_date[date]['method'] == "<!--{$smarty.const.RESERVE_PATTEN_HOLIDAY}-->"){
			var price = parseFloat($("#hdn_price").val()) + parseFloat($("#hdn_price").val() * 0.1);
			$("#price").html(numberFormat(price)+"（※祝日料金10%増）");
		}
	}

	function selectDate(){
		var bln_exist_date = false;

		$("#txt_arrival_date").html("ご利用日をクリック");
		$("#txt_return_date").html("ご利用日をクリック");
		$("#hid_use_date").html("ご利用日をクリック");

		$("#hid_send_date").attr("value", "ご利用日をクリック");
		$("#hid_arrival_date").attr("value", "ご利用日をクリック");
		$("#hid_use_date").attr("value", "ご利用日をクリック");
		$("#hid_return_date").attr("value", "ご利用日をクリック");

		var date1_temp = $("#date1").val();
		var date2_temp = $("#date2").val();

		if(date1_temp != "" && !rental_possible_date[date1_temp]){
			if(date2_temp != ""){
				$("#date1").val(date2_temp);
				$("#date2").val("");
			}
		}

		if($("#date1").val() != "" || ($("#date1").val() == "" && $("#date2").val() != "")){

			var date1 = ($("#date1").val() != "")?$("#date1").val():$("#date2").val();
            
			if(rental_possible_date[date1]){
				$("#txt_arrival_date").html(rental_possible_date[date1]['arrival_show']);
				$("#txt_return_date").html(rental_possible_date[date1]['return_show'] + "<font color='#ff000000'><!--{$smarty.const.RETURN_TIME}--></font>");
				$("#txt_use_date").html(rental_possible_date[date1]['rental_show2']);
                $("#txt_use_date").removeClass("use_text");

				$("#hid_send_date").attr("value",rental_possible_date[date1]['send_show2']);
				$("#hid_arrival_date").attr("value",rental_possible_date[date1]['arrival_show']);
				$("#hid_use_date").attr("value",rental_possible_date[date1]['rental_show2']);
				$("#hid_return_date").attr("value",rental_possible_date[date1]['return_show']);

				$("#opt_send_date").val("1");

				bln_exist_date = true;

                $('.arriaval_area').addClass('sp_show');
			}
		}

		if(bln_exist_date == false){
			$("#date1").val("");
		}
		$("#date2").val("");

	}


	function processDate(show_date){
        
		var obj = document.getElementById("datepicker");

		var select_date = parseDate(show_date);

		var date_temp1 = addDays(select_date, -3);
		date_temp1 = date_temp1.getFullYear()+"-"+String('0'+(date_temp1.getMonth()+1)).slice(-2)+"-"+String('0'+date_temp1.getDate()).slice(-2) ;
		var date_temp2 = "";

		$("#date1").val("");
		if(rental_possible_date[date_temp1]){
			$("#date1").val(date_temp1);
		}

		$("#date2").val("");
		if(rental_possible_date[date_temp2]){
			($("#date1").val() == "")?$("#date1").val(date_temp2):$("#date2").val(date_temp2);
		}
        
        // add ishibashi 他の日程が選択された場合はデータを削除
        if (show_date !== $('#search_rendal_date').val())
        {
            $('#search_rendal_date').val('');
        }

        $('#select_date').val(show_date);
        // add ishibashi

		selectDate();

		return select_date;

	}



	$(document).ready(function() {
		var today = parseDate(server_date);//new Date();
		parsed_limit_date = parseDate(limit_date);
		var end_day = addDays(today, 7*parseInt("<!--{$smarty.const.RESERVE_WEEKS}-->"));  

		my_datepicker_0 = $( "#datepicker_0" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today,
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			hideIfNoPrevNext: false
		});
		my_datepicker_1 = $( "#datepicker_1" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today+'1m',
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			hideIfNoPrevNext: false
		});
		my_datepicker_2 = $( "#datepicker_2" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today+'2m',
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			hideIfNoPrevNext: false
		}); 

        // add ishibashi 日程検索の日にちがある場合はクリックされた初期状態にする
        if ($('#search_rendal_date').val() !== '')
        {
            var show_date = $('#search_rendal_date').val();
            processDate(show_date);

            var dt = new Date(show_date);
            var targetMonth = dt.getMonth() + 1;

            var datePicker0 = <!--{$tpl_current_month}-->;
            var datePicker1 = <!--{$tpl_next_month}-->;
            var datePicker2 = <!--{$tpl_next_next_month}-->;

            if (datePicker0 === targetMonth)
            {
                $('#tab0120130315').addClass('active');
            }
            else if(datePicker1 === targetMonth)
            {
                $('#tab0220130315').addClass('active');
                $('#tab0120130315').removeClass('active');

                $('#tab-120130315').css('display','none');
                $('#tab-220130315').css('display','block');
            }
            else if(datePicker2 === targetMonth)
            {
                $('#tab0320130315').addClass('active');
                $('#tab0120130315').removeClass('active');

                $('#tab-120130315').css('display','none');
                $('#tab-320130315').css('display','block');
            }
        }

		hiddenDatepickerNextPrev();
	});

	$(function() {
		var tips = $( ".validateTips" );
		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}
	});


	function closePopup(){
		//$( "#dialog-form" ).dialog( "close" );
	}

	function goAddSchejule(){

		var msg = "ご利用日を選択してください。";

		if($("#date1").val() == "" && $("#date2").val() == ""){
			alert( msg );
			//updateTips( msg );

			return;
		}

		//$( "#dialog-form" ).dialog( "close" );
		//$("#div_send_date").append($("#dialog-form"));
		document.form1.submit();
	}
	function hiddenDatepickerNextPrev()
	{
		$(".ui-datepicker-next").css("display", "none");
		$(".ui-datepicker-prev").css("display", "none");
		$(".ui-datepicker-title").css("display", "none");
		return false;
	}

    function goCalander(set_type){
        document.form1.action='<!--{$smarty.const.ROOT_URLPATH}-->products/detail.php?flg=rental&set_type=' + set_type;
        document.form1.submit();
    }
</script>
<!-- Calendar End -->
