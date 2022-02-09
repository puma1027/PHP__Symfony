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

<ul class="level1">
    <!--{if $smarty.session.authority <= $smarty.const.ADMIN_ALLOW_LIMIT}-->

        <li<!--{if $tpl_subno == 'index'}--> class="on"<!--{/if}--> id="navi-products-index"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/<!--{$smarty.const.DIR_INDEX_PATH}-->"><span>商品マスター</span></a></li>

        <!-- {{add BHM_201403013 -->
        <li class="on_level2" id="navi-products-product"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product.php"><span>商品登録</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'product'}-->on<!--{/if}-->" id="navi-products-product"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product.php"><span>商品登録(元)</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_onepiece'}-->on<!--{/if}-->" id="navi-products-product-onepiece"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_onepiece.php"><span>ワンピース</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_dress'}-->on<!--{/if}-->" id="navi-products-product-dress">
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_dress.php"><span>ドレス</span></a></li>
                <li  class="<!--{if $tpl_subno == 'product_set_dress'}-->on<!--{/if}-->" id="navi-products-product-set-dress"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_set_dress.php"><span>セット商品</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_stole'}-->on<!--{/if}-->" id="navi-products-product-stole"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_stole.php"><span>ストール・ボレロ</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_necklace'}-->on<!--{/if}-->" id="navi-products-product-necklace"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_necklace.php"><span>ネックレス</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_others'}-->on<!--{/if}-->" id="navi-products-product-other"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_others.php"><span>その他小物</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'releaseday'}-->on<!--{/if}-->" id="navi-products-product-releaseday"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/releaseday.php"><span>登場日設定</span></a></li>                            
                <li  class="<!--{if $tpl_subno == 'product_bag'}-->on<!--{/if}-->" id="navi-products-product-bag"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_bag.php"><span>バッグ設定</span></a></li>                            
            </ul>
        </li>
        <!-- }}add BHM_20140313 -->

        <li<!--{if $tpl_subno == 'upload_csv'}--> class="on"<!--{/if}--> id="navi-products-uploadcsv"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/upload_csv.php"><span>商品登録CSV</span></a></li>
        <!--{if $smarty.const.OPTION_CLASS_REGIST == 1}-->
            <li<!--{if $tpl_subno == 'class'}--> class="on"<!--{/if}--> id="navi-products-class"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/class.php"><span>規格管理</span></a></li>
        <!--{/if}-->
        <li<!--{if $tpl_subno == 'category'}--> class="on"<!--{/if}--> id="navi-products-category"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/category.php"><span>カテゴリ登録</span></a></li>
        <li<!--{if $tpl_subno == 'upload_csv_category'}--> class="on"<!--{/if}--> id="navi-products-upload-csv-category"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/upload_csv_category.php"><span>カテゴリ登録CSV</span></a></li>
        <li<!--{if $tpl_subno == 'maker'}--> class="on"<!--{/if}--> id="navi-products-maker"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/maker.php"><span>メーカー登録</span></a></li>
        <li<!--{if $tpl_subno == 'product_rank'}--> class="on"<!--{/if}--> id="navi-products-rank"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_rank.php"><span>商品並び替え</span></a></li>


        <!-- {{add BHM_201403013 -->
        <li class="on_level2" id="navi-products-review"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/review.php"><span>レビュー管理</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'review'}-->on<!--{/if}-->" id="navi-products-review"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/review.php"><span>レビュー管理</span></a>
                </li>
                <li  class="<!--{if $tpl_subno == 'review_register'}-->on<!--{/if}-->" id="navi-products-review-register"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/review_register.php"><span>ユーザーレビュー登録</span></a>
                </li> 
            </ul>
        </li>

        <li<!--{if $tpl_subno == 'trackback'}--> class="on"<!--{/if}--> id="navi-products-trackback"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/trackback.php"><span>トラックバック管理</span></a></li>

        <li<!--{if $tpl_subno == 'products_all'}--> class="on"<!--{/if}--> id="navi-products-all"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/products_all.php"><span>予約状況管理</span></a></li>

        <li class="on_level2" id="navi-products-inventory-management-stole"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/inventory_management_stole.php"><span>在庫管理</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'inventory_management_stole'}-->on<!--{/if}-->" id="navi-products-inventory-management-stole"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/inventory_management_stole.php"><span>ストール・ボレロ在庫管理</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'inventory_management_necklace'}-->on<!--{/if}-->" id="navi-products-inventory-management-necklace"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/inventory_management_necklace.php"><span>ネックレス在庫管理</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'inventory_management_bag'}-->on<!--{/if}-->" id="navi-products-inventory-management-bag"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/inventory_management_bag.php"><span>バッグ在庫管理</span></a></li>            
            </ul>
        </li>
        <li<!--{if $tpl_subno == 'products_all_edit'}--> class="on"<!--{/if}--> id="navi-products-all-edit"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/products_all_edit.php"><span>商品一括管理</span></a></li>
        <li<!--{if $tpl_subno == 'brand'}--> class="on"<!--{/if}--> id="navi-products-brand"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/brand.php"><span>ブランド設定</span></a></li>
        <li<!--{if $tpl_subno == 'model'}--> class="on"<!--{/if}--> id="navi-products-model"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/model.php"><span>モデル設定</span></a></li>

        <li class="on_level2" id="navi-products-inspect-search"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_inspect_search.php"><span>検品表</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'product_inspect_search'}-->on<!--{/if}-->" id="navi-products-inspect-search"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_inspect_search.php"><span>検品表検索</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_inspect_images'}-->on<!--{/if}-->" id="navi-products-inspect-images"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_inspect_images.php"><span>検品画像設定</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_inspect_setting'}-->on<!--{/if}-->" id="navi-products-inspect-setting"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_inspect_setting.php"><span>検品表設定</span></a></li>            
            </ul>
        </li>
        <li<!--{if $tpl_subno == 'product_seal'}--> class="on"<!--{/if}--> id="navi-products-seal"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_seal.php"><span>タグ・シール作成</span></a></li>
        <!-- }}add BHM_20140313 -->

    <!--{elseif $smarty.session.authority <= $smarty.const.ITOKAWA_ALLOW_LIMIT}-->
        <li<!--{if $tpl_subno == 'index'}--> class="on"<!--{/if}--> id="navi-products-index"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/<!--{$smarty.const.DIR_INDEX_PATH}-->"><span>商品マスター</span></a></li>

        <!-- {{add BHM_201403013 -->
        <li class="on_level2" id="navi-products-product"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product.php"><span>商品登録</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'product'}-->on<!--{/if}-->" id="navi-products-product"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/product.php"><span>商品登録(元)</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_onepiece'}-->on<!--{/if}-->" id="navi-products-product-onepiece"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/product_onepiece.php"><span>ワンピース</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_dress'}-->on<!--{/if}-->" id="navi-products-product-dress">
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/product_dress.php"><span>ドレス</span></a></li>
                <li  class="<!--{if $tpl_subno == 'product_set_dress'}-->on<!--{/if}-->" id="navi-products-product-set-dress"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/product_set_dress.php"><span>セット商品</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_stole'}-->on<!--{/if}-->" id="navi-products-product-stole"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/product_stole.php"><span>ストール・ボレロ</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_necklace'}-->on<!--{/if}-->" id="navi-products-product-necklace"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/product_necklace.php"><span>ネックレス</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'product_others'}-->on<!--{/if}-->" id="navi-products-product-other"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/product_others.php"><span>その他小物</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'releaseday'}-->on<!--{/if}-->" id="navi-products-product-releaseday"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/releaseday.php"><span>登場日設定</span></a></li>                            
                <li  class="<!--{if $tpl_subno == 'product_bag'}-->on<!--{/if}-->" id="navi-products-product-bag"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/product_bag.php"><span>バッグ設定</span></a></li>                            
            </ul>
        </li>

        <li<!--{if $tpl_subno == 'products_all'}--> class="on"<!--{/if}--> id="navi-products-all"><a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/products_all.php"><span>予約状況管理</span></a></li>
        <!-- }}add bhm_20140313 -->
        <li class="on_level2" id="navi-products-review"><a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/review.php"><span>レビュー管理</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'review'}-->on<!--{/if}-->" id="navi-products-review"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/review.php"><span>レビュー管理</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'review_register'}-->on<!--{/if}-->" id="navi-products-review-register"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/review_register.php"><span>ユーザーレビュー登録</span></a></li>            
            </ul>
        </li>

        <li class="on_level2" id="navi-products-inventory-management-stole"><a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/inventory_management_stole.php"><span>在庫管理</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'inventory_management_stole'}-->on<!--{/if}-->" id="navi-products-inventory-management-stole"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/inventory_management_stole.php"><span>ストール・ボレロ在庫管理</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'inventory_management_necklace'}-->on<!--{/if}-->" id="navi-products-inventory-management-necklace"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/inventory_management_necklace.php"><span>ネックレス在庫管理</span></a></li>            
                <li  class="<!--{if $tpl_subno == 'inventory_management_bag'}-->on<!--{/if}-->" id="navi-products-inventory-management-bag"> 
                    <a href="<!--{$smarty.const.root_urlpath}--><!--{$smarty.const.admin_dir}-->products/inventory_management_bag.php"><span>バッグ在庫管理</span></a></li>            
            </ul>
        </li>

        <li class="on_level2" id="navi-products-inspect-search"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_inspect_search.php"><span>検品表</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'product_inspect_search'}-->on<!--{/if}-->" id="navi-products-inspect-search"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_inspect_search.php"><span>検品表検索</span></a></li>
            </ul>
        </li>
        <li<!--{if $tpl_subno == 'product_seal'}--> class="on"<!--{/if}--> id="navi-products-seal"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_seal.php"><span>タグ・シール作成</span></a></li>

    <!--{else}-->
        <li class="on_level2" id="navi-products-inspect-search"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_inspect_search.php"><span>検品表</span></a>
           <ul class="level2">
                <li  class="<!--{if $tpl_subno == 'product_inspect_search'}-->on<!--{/if}-->" id="navi-products-inspect-search"> 
                    <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_inspect_search.php"><span>検品表検索</span></a></li>
            </ul>
        </li>
        <li<!--{if $tpl_subno == 'product_seal'}--> class="on"<!--{/if}--> id="navi-products-seal"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->products/product_seal.php"><span>タグ・シール作成</span></a></li>

    <!--{/if}-->
</ul>
