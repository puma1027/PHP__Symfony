<!--{* パンくず *}-->
<div class="pankuzu_sp_footer sp_show">
<!--{if $smarty.server.PHP_SELF == '/index.php'}-->
    <!--{* トップページはパンくずを出さない *}-->

<!--{elseif strpos($smarty.server.PHP_SELF,'products/detail.php') !== false}-->
    <!--{section name=i start=0 loop=3}-->
    <!--{* roop:0=年代、1=サイズ, 2=通常 *}-->
    <!--{if ($smarty.section.i.index == 0 && $pankuzu_age != NULL && $smarty.get.category_id != $smarty.const.CATEGORY_CEREMONYSUIT)
        || ( $smarty.section.i.index == 1 && ($arrProduct.item_size != NULL || count($kind_of_size) > 0) && $pankuzu_size_show == 1)}-->
    <div class="pankuzu h1_sp_scl">
      <ul class="sp_h1_wid">
          <li><a href="/"><!--{$arrSiteInfo.shop_name|h}--> TOP</a></li>
            <li>
                <!--{if $smarty.get.category_id == 1}-->
                <a href="<!--{$url_one_piece_list}-->">ワンピース一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_NECKLACE}-->
                <a href="<!--{$url_necklace_list}-->">ネックレス一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_STOLE}-->
                <a href="<!--{$url_outer_list}-->">羽織一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_BAG}-->
                <a href="<!--{$url_bag_list}-->">バッグ一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_CEREMONYSUIT}-->
                <a href="<!--{$url_ceremony_one_piece_list}-->">セレモニースーツ一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_KIDS}-->
                <a href="<!--{$url_kids_list}-->">キッズフォーマル一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_KIDS_DRESS}-->
                <a href="<!--{$url_kids_list}-->">キッズドレス一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_EARRINGS}-->
                <a href="<!--{$url_kids_list}-->">イヤリング一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_OTHERS}-->
                <!-- コメントアウト ishibashi $smarty.get.category_id == $smarty.const.CATEGORY_SNO -->
                <a href="<!--{$url_other_item_list}-->">その他小物一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_DRESS_ALL || $smarty.get.category_id == $smarty.const.CATEGORY_DRESS}-->
                <a href="<!--{$url_dress_list}-->">ドレス一覧</a>
                <!--{else}-->
                <a href="<!--{$url_dress_list}-->">商品一覧</a>
                <!--{/if}-->
            </li>
            <!--{if $smarty.section.i.index == 0}-->
                <li>
                    <a href="/products/list.php?age%5B%5D=<!--{$pankuzu_age_url}-->&category_id=dress&mode=category_search&rental_date=&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&len_knee_sel=&age%5B%5D=<!--{$pankuzu_age_url}-->"><!--{$pankuzu_age}--></a>
                </li>
            <!--{elseif $smarty.section.i.index == 1}-->
                <li>
                    <!--{if $kidsSize != NULL}-->
                        <a href="/products/list.php?category_id=371"><!--{$kidsSize}--></a>
                    <!--{else}-->
                        <a href="/products/list.php?category_id=dress&mode=category_search&rental_date=&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&size%5B%5D=<!--{$pankuzu_size_url}-->&len_knee_sel="><!--{$arrProduct.item_size}--></a>
                    <!--{/if}-->
                </li>
            <!--{/if}-->
            <li><span><!--{$arrProductCode.0}-->｜<!--{$tpl_h1|escape}--></span></li>
      </ul>
    </div>
    <!--{/if}-->
    <!--{/section}-->
<!--{*トップと商品詳細以外*}-->
<!--{else}-->
<!--{/if}-->
</div>

<div class="mt30 mb40 ta_c"><a href="https://onepiece-rental.net/" class="ui-link">レンタルドレスのワンピの魔法トップへ</a></div>