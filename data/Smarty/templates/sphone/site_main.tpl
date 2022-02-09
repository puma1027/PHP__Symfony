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

<body>
    <!-- Google Tag Manager (noscript) Q20170510-->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NWV75Z8"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) Q20170510-->

    <div data-role="page" data-keep-native=".data-role-none" data-theme="f" data-fullscreen="true">

        <!--{* ▼HEADER *}-->
        <!--{if $arrPageLayout.header_chk != 2}-->
            <!--{include file= $header_tpl}-->
        <!--{/if}-->
        <!--{* ▲HEADER *}-->


    <!--{* 202102 add araki *}-->
    <!--{assign var=top_url value="`$smarty.const.URL_DIR`index.php"}-->
    <!--{if $smarty.server.PHP_SELF == $top_url}-->
        <div class="homemaintext pc_show">
            <div><!--{* class="main_visual" *}-->
                <a href="/user_data/concept.php"><img src="<!--{$TPL_URLPATH}-->img/cover/cover_01.png" class="pc_main_img" alt="メインイメージ"></a>
            </div>
            <div class="pc_show bg_pink" style="padding:0; font-size:0;">
                <div style="width:960px; height:95px; margin:0 auto; padding:10px 0 0;">

                </div>
            </div>
        </div>
    <!--{/if}-->
    <!--{* 202102 add end *}-->

    <div id="container">
            <div class="l-content">

                <!--{* ▼HeaderHeaderTop COLUMN*}-->
                <!--{if !empty($arrPageLayout.HeaderTopNavi)}-->
                    <div class="header_utility">
                        <!--{* ▼上ナビ *}-->
                        <!--{foreach key=HeaderTopNaviKey item=HeaderTopNaviItem from=$arrPageLayout.HeaderTopNavi}-->
                            <!-- ▼<!--{$HeaderTopNaviItem.bloc_name}--> -->
                            <!--{if $HeaderTopNaviItem.php_path != ""}-->
                                <!--{include_php_ex file=$HeaderTopNaviItem.php_path items=$HeaderTopNaviItem}-->
                            <!--{else}-->
                                <!--{include file=$HeaderTopNaviItem.tpl_path items=$HeaderTopNaviItem}-->
                            <!--{/if}-->
                            <!-- ▲<!--{$HeaderTopNaviItem.bloc_name}--> -->
                        <!--{/foreach}-->
                        <!--{* ▲上ナビ *}-->
                    </div>
                <!--{/if}-->
                <!--{* ▲HeaderHeaderTop COLUMN*}-->

                <!--{*
                    ▼HEADER }-->
                <!--{if $arrPageLayout.header_chk != 2}-->
                    <!--{include file= $header_tpl}-->
                <!--{/if}-->
                <!--{ ▲HEADER
                *}-->


                <!--{* ▼TOP COLUMN*}-->
                <!--{if !empty($arrPageLayout.TopNavi)}-->
                    <div class="header_utility">
                        <!--{* ▼上ナビ *}-->
                        <!--{foreach key=TopNaviKey item=TopNaviItem from=$arrPageLayout.TopNavi}-->
                            <!-- ▼<!--{$TopNaviItem.bloc_name}--> -->
                            <!--{if $TopNaviItem.php_path != ""}-->
                                <!--{include_php_ex file=$TopNaviItem.php_path items=$TopNaviItem}-->
                            <!--{else}-->
                                <!--{include file=$TopNaviItem.tpl_path items=$TopNaviItem}-->
                            <!--{/if}-->
                            <!-- ▲<!--{$TopNaviItem.bloc_name}--> -->
                        <!--{/foreach}-->
                        <!--{* ▲上ナビ *}-->
                    </div>
                <!--{/if}-->
                <!--{* ▲TOP COLUMN*}-->

                <!--{* ▼CENTER COLUMN *}-->
                <div id="main-content">
                    <!--{* ▼メイン上部 *}-->
                        <!--{if !empty($arrPageLayout.MainHead)}-->
                            <!--{foreach key=MainHeadKey item=MainHeadItem from=$arrPageLayout.MainHead}-->
                                <!-- ▼<!--{$MainHeadItem.bloc_name}--> -->
                                <!--{if $MainHeadItem.php_path != ""}-->
                                    <!--{include_php_ex file=$MainHeadItem.php_path items=$MainHeadItem}-->
                                <!--{else}-->
                                    <!--{include file=$MainHeadItem.tpl_path items=$MainHeadItem}-->
                                <!--{/if}-->
                                <!-- ▲<!--{$MainHeadItem.bloc_name}--> -->
                            <!--{/foreach}-->
                        <!--{/if}-->
                    <!--{* ▲メイン上部 *}-->

                    <!-- ▼メイン -->
                    <!--{if 
                    $smarty.server.PHP_SELF == '/index.php' ||
                    $smarty.server.PHP_SELF == '/products/list.php' ||
                    $smarty.server.PHP_SELF == '/products/detail.php' ||
                    $smarty.server.PHP_SELF == '/cart/index.php' ||
                    $smarty.server.PHP_SELF == '/shopping/deliv.php' ||
                    $smarty.server.PHP_SELF == '/shopping/confirm.php' ||
                    $smarty.server.PHP_SELF == '/shopping/complete.php' ||
                    $smarty.server.PHP_SELF == '/shopping/payment.php'
                    }-->
                        <div>
                            <!--{include file=$tpl_mainpage}-->
                        </div>
                    <!--{else}-->
                        <div class="contents">
                            <div class="side_col">
                              <!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`sidebar_sub.tpl"}-->
                            </div>
                            <div class="main_col">
                                <!--{include file=$tpl_mainpage}-->
                            </div>
                        </div>
                    <!--{/if}-->
                    <!-- ▲メイン -->

                    <!--{* ▼メイン下部 *}-->
                    <!--{if $smarty.server.PHP_SELF == '/index.php'}-->
                        <div class="contents toppage">
                            <div class="side_col">
                              <!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`sidebar_top.tpl"}-->
                            </div>
                            <div class="main_col_top">
                                <!--{if !empty($arrPageLayout.MainFoot)}-->
                                    <!--{foreach key=MainFootKey item=MainFootItem from=$arrPageLayout.MainFoot}-->
                                        <!-- ▼<!--{$MainFootItem.bloc_name}--> -->
                                        <!--{if $MainFootItem.php_path != ""}-->
                                            <!--{include_php_ex file=$MainFootItem.php_path items=$MainFootItem}-->
                                        <!--{else}-->
                                            <!--{include file=$MainFootItem.tpl_path items=$MainFootItem}-->
                                        <!--{/if}-->
                                        <!-- ▲<!--{$MainFootItem.bloc_name}--> -->
                                    <!--{/foreach}-->
                                <!--{/if}-->
                            </div>
                        </div>
                    <!--{else}-->
                        <div>
                            <!--{if !empty($arrPageLayout.MainFoot)}-->
                                <!--{foreach key=MainFootKey item=MainFootItem from=$arrPageLayout.MainFoot}-->
                                    <!-- ▼<!--{$MainFootItem.bloc_name}--> -->
                                    <!--{if $MainFootItem.php_path != ""}-->
                                        <!--{include_php_ex file=$MainFootItem.php_path items=$MainFootItem}-->
                                    <!--{else}-->
                                        <!--{include file=$MainFootItem.tpl_path items=$MainFootItem}-->
                                    <!--{/if}-->
                                    <!-- ▲<!--{$MainFootItem.bloc_name}--> -->
                                <!--{/foreach}-->
                            <!--{/if}-->
                        </div>
                    <!--{/if}-->
                    <!--{* ▲メイン下部 *}-->
                <!--{* ▲CENTER COLUMN *}-->
                </div>

                <!--{* ▼BOTTOM COLUMN*}-->
                <!--{if !empty($arrPageLayout.BottomNavi)}-->
                    <div id="footer_utility" class="sp_show">
                        <!--{* ▼下ナビ *}-->
                        <!--{foreach key=BottomNaviKey item=BottomNaviItem from=$arrPageLayout.BottomNavi}-->
                            <!-- ▼<!--{$BottomNaviItem.bloc_name}--> -->
                            <!--{if $BottomNaviItem.php_path != ""}-->
                                <!--{include_php_ex file=$BottomNaviItem.php_path items=$BottomNaviItem}-->
                            <!--{else}-->
                                <!--{include file=$BottomNaviItem.tpl_path items=$BottomNaviItem}-->
                            <!--{/if}-->
                            <!-- ▲<!--{$BottomNaviItem.bloc_name}--> -->
                        <!--{/foreach}-->
                        <!--{* ▲下ナビ *}-->
                    </div>
                <!--{/if}-->
                <!--{* ▲BOTTOM COLUMN*}-->

            </div><!-- .l-content -->
        </div>

        <!--{* ▼FOOTER *}-->
        <!--{if $arrPageLayout.footer_chk != 2}-->
            <!--{include file= './footer.tpl'}-->
        <!--{/if}-->
        <!--{* ▲FOOTER *}-->

        <!--{* ▼FooterBottom COLUMN*}-->
        <!--{if !empty($arrPageLayout.FooterBottomNavi)}-->
                <!--{* ▼上ナビ *}-->
                <!--{foreach key=FooterBottomNaviKey item=FooterBottomNaviItem from=$arrPageLayout.FooterBottomNavi}-->
                    <!-- ▼<!--{$FooterBottomNaviItem.bloc_name}--> -->
                    <!--{if $FooterBottomNaviItem.php_path != ""}-->
                        <!--{include_php file=$FooterBottomNaviItem.php_path items=$FooterBottomNaviItem}-->
                    <!--{else}-->
                        <!--{include file=$FooterBottomNaviItem.tpl_path items=$FooterBottomNaviItem}-->
                    <!--{/if}-->
                    <!-- ▲<!--{$FooterBottomNaviItem.bloc_name}--> -->
                <!--{/foreach}-->
                <!--{* ▲上ナビ *}-->
        <!--{/if}-->
        <!--{* ▲FooterBottom COLUMN*}-->

    </div>

</body>
