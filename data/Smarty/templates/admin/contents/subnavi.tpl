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

<!--{if $smarty.session.authority <= $smarty.const.ADMIN_ALLOW_LIMIT}-->
<ul class="level1">
<li<!--{if $tpl_subno == 'index'}--> class="on"<!--{/if}--> id="navi-contents-index"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/<!--{$smarty.const.DIR_INDEX_PATH}-->"><span>新着情報管理</span></a></li>
<!-- {{ Modify MGN_20140310 -->
<li class="on_level2 <!--{if $tpl_subno == 'recommend'}--> on<!--{/if}--> " id="navi-contents-recommend"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend.php?tpl_subno_recommend=pc"><span>おすすめ商品管理</span></a>
    <ul id="navi-csv-recommend" class="level2">
    <li<!--{if $tpl_subno_recommend == 'pc'}--> class="on"<!--{/if}--> id="navi-recommend-product"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend.php?tpl_subno_recommend=pc"><span>PCランキング</span></a></li>
    <li<!--{if $tpl_subno_recommend == 'sp_review'}--> class="on"<!--{/if}--> id="navi-recommend-customer"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend_sp.php?type=review"><span>レビューの多い順</span></a></li>
    <li<!--{if $tpl_subno_recommend == 'sp_value'}--> class="on"<!--{/if}--> id="navi-recommend-order"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend_sp.php?type=value"><span>評価の高い順</span></a></li>
    <li<!--{if $tpl_subno_recommend == 'sp_reco'}--> class="on"<!--{/if}--> id="navi-recommend-category"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend_sp.php?type=reco"><span>スタッフオススメ順</span></a></li>
    </ul>
</li>
<!-- }} Modify MGN_20140310 -->
<li<!--{if $tpl_subno == 'file'}--> class="on"<!--{/if}--> id="navi-contents-file"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/file_manager.php"><span>ファイル管理</span></a></li>
<li class="on_level2<!--{if $tpl_subno == 'csv'}--> on<!--{/if}-->" id="navi-contents-csv">
    <div><span>CSV出力項目設定</span></div>
    <ul id="navi-csv-sub" class="level2">
    <li<!--{if $tpl_subno_csv == 'product'}--> class="on"<!--{/if}--> id="navi-csv-product"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/csv.php?tpl_subno_csv=product"><span>商品管理</span></a></li>
    <li<!--{if $tpl_subno_csv == 'customer'}--> class="on"<!--{/if}--> id="navi-csv-customer"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/csv.php?tpl_subno_csv=customer"><span>会員管理</span></a></li>
    <li<!--{if $tpl_subno_csv == 'order'}--> class="on"<!--{/if}--> id="navi-csv-order"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/csv.php?tpl_subno_csv=order"><span>受注管理</span></a></li>
    <li<!--{if $tpl_subno_csv == 'category'}--> class="on"<!--{/if}--> id="navi-csv-category"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/csv.php?tpl_subno_csv=category"><span>カテゴリ</span></a></li>
    <li<!--{if $tpl_subno_csv == 'review'}--> class="on"<!--{/if}--> id="navi-csv-review"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/csv.php?tpl_subno_csv=review"><span>レビュー</span></a></li>
    <li<!--{if $tpl_subno_csv == 'csv_sql'}--> class="on"<!--{/if}--> id="navi-csv-sql"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/csv_sql.php"><span>高度な設定</span></a></li>
    </ul>
</li>
<!-- {{ Add MGN_20140314 -->
<li class="on_level2 <!--{if $tpl_subno == 'dresser'}--> on<!--{/if}--> " id="navi-contents-recommend"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/dresser.php"><span>ベストドレッサー</span></a>
    <ul id="navi-csv-dresser" class="level2">
    <li<!--{if $tpl_subno_dresser == 'info'}--> class="on"<!--{/if}--> id="navi-dresser-info"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/dresser.php"><span>ベストドレッサー賞</span></a></li>
    <li<!--{if $tpl_subno_dresser == 'image'}--> class="on"<!--{/if}--> id="navi-dresser-image"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/dresser_image.php"><span>ベストドレッサー賞・画像</span></a></li>
    </ul>
</li>
<li<!--{if $tpl_subno == 'amour_onepiece'}--> class="on"<!--{/if}--> id="navi-contents-file"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/amour_onepiece.php"><span>婚活ワンピ</span></a></li>
<li class="on_level2 <!--{if $tpl_subno == 'trouble'}--> on<!--{/if}--> " id="navi-contents-recommend"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/trouble_recommended_dress.php"><span>悩み別おすすめドレス</span></a>
    <ul id="navi-csv-dresser" class="level2">
    <li<!--{if $tpl_subno_dresser == 'trouble'}--> class="on"<!--{/if}--> id="navi-dresser-info"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/trouble_recommended_dress.php"><span>悩み別おすすめドレス</span></a></li>
    <li<!--{if $tpl_subno_dresser == 'shooting'}--> class="on"<!--{/if}--> id="navi-dresser-image"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/shooting_date.php"><span>撮影日</span></a></li>
    </ul>
</li>
<li<!--{if $tpl_subno == 'staff_register'}--> class="on"<!--{/if}--> id="navi-contents-file"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/staff_register.php"><span>社員登録</span></a></li>
<li<!--{if $tpl_subno == 'useful_memo'}--> class="on"<!--{/if}--> id="navi-contents-file"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/useful_memo.php"><span>お役立ちメモ</span></a></li>
<!-- }} Add MGN_20140314 -->
<li<!--{if $tpl_subno == 'r_scan'}--> class="on"<!--{/if}--> id="navi-contents-file"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/r_scan.php"><span>レンタル票電子化</span></a></li>
</ul>

<!--{else}-->
<!--{if $smarty.session.authority <= $smarty.const.ITOKAWA_ALLOW_LIMIT}-->

<ul class="level1">
<li<!--{if $tpl_subno == 'file'}--> class="on"<!--{/if}--> id="navi-contents-file"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/file_manager.php"><span>ファイル管理</span></a></li>
<li<!--{if $tpl_subno == 'index'}--> class="on"<!--{/if}--> id="navi-contents-index"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/<!--{$smarty.const.DIR_INDEX_PATH}-->"><span>新着情報管理</span></a></li>
<li class="on_level2 <!--{if $tpl_subno == 'recommend'}--> on<!--{/if}--> " id="navi-contents-recommend"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend.php?tpl_subno_recommend=pc"><span>おすすめ商品管理</span></a>
    <ul id="navi-csv-recommend" class="level2">
    <li<!--{if $tpl_subno_recommend == 'pc'}--> class="on"<!--{/if}--> id="navi-recommend-product"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend.php?tpl_subno_recommend=pc"><span>PCランキング</span></a></li>
    <li<!--{if $tpl_subno_recommend == 'sp_review'}--> class="on"<!--{/if}--> id="navi-recommend-customer"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend_sp.php?type=review"><span>レビューの多い順</span></a></li>
    <li<!--{if $tpl_subno_recommend == 'sp_value'}--> class="on"<!--{/if}--> id="navi-recommend-order"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend_sp.php?type=value"><span>評価の高い順</span></a></li>
    <li<!--{if $tpl_subno_recommend == 'sp_reco'}--> class="on"<!--{/if}--> id="navi-recommend-category"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/recommend_sp.php?type=reco"><span>スタッフオススメ順</span></a></li>
    </ul>
</li>
<li<!--{if $tpl_subno == 'staff_register'}--> class="on"<!--{/if}--> id="navi-contents-file"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/staff_register.php"><span>社員登録</span></a></li>
</ul>
<!--{else}-->
<ul class="level1">
  <li<!--{if $tpl_subno == 'r_scan'}--> class="on"<!--{/if}--> id="navi-contents-file"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->contents/r_scan.php"><span>レンタル票電子化</span></a></li>
</ul>
<!--{/if}-->

<!--{/if}-->
