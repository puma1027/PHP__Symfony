<!DOCTYPE HTML>
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
 *************************   SPHONE   *******************************
 *}-->
<html lang="ja" class="<!--{$tpl_page_class_name|h}-->">
    <head>
		<!-- ＜サイトジェネラルタグ＞ウェブサイト内の全ページの<HEAD>タグ開始直後に設置 -->
		<script async src="https://s.yimg.jp/images/listing/tool/cv/ytag.js"></script>
		<script>
		window.yjDataLayer = window.yjDataLayer || [];
		function ytag() { yjDataLayer.push(arguments); }
		ytag({"type":"ycl_cookie", "config":{"ycl_use_non_cookie_storage":true}});
		</script>
        <meta charset="UTF-8">
<!-- スマホ -->
	<!--{assign var=top_url value="`$smarty.const.URL_DIR`index.php"}-->
	<!--{assign var=preview_url value="`$smarty.const.URL_DIR`preview/index.php"}-->
	<!--{assign var=items value="`$smarty.const.URL_DIR`products/detail.php"}-->
	<!--{assign var=guide value="`$smarty.const.URL_DIR`user_data/guide.php"}-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
        <meta name="format-detection" content="telephone=no">
        <!--{* 共通CSS *}-->
		<link rel="stylesheet" media="only screen" href="/user_data/packages/sphone/css/base.css">
		<!--{if $smarty.server.REQUEST_URI == $guide}-->
			<link rel="stylesheet" media="only screen" href="/user_data/packages/sphone/css/guide.css">
		<!--{elseif $smarty.server.REQUEST_URI == $top_url}-->
		<!--{else}-->
			<link rel="stylesheet" media="only screen" href="<!--{$TPL_URLPATH}-->css/jquery.mobile-1.0.1.min.css" />
			<link rel="stylesheet" media="only screen" href="/user_data/packages/sphone/css/stylelink.css">
		<!--{/if}-->
		<link rel="stylesheet" media="only screen" href="/user_data/packages/sphone/css/common.css">
		<link rel="stylesheet" media="only screen" href="/user_data/packages/sphone/css/new_style.min.css">
		<link rel="stylesheet" media="only screen" href="/user_data/packages/sphone/css/pc.css">
	<!--{assign var="wedding1" value="`$smarty.const.URL_DIR`user_data/kekkonsiki_fukusou.php"}--><!--//::N00130-->
    <!--{if $smarty.server.PHP_SELF != $wedding1}--><!--//::N00130-->
        <script src="<!--{$smarty.const.ROOT_URLPATH}-->js/navi.js"></script>
        <script src="<!--{$smarty.const.ROOT_URLPATH}-->js/win_op.js"></script>
        <script src="<!--{$smarty.const.ROOT_URLPATH}-->js/site.js"></script>
    <!--{/if}--><!--//::N00130-->
        <!--{assign var="faq" value="`$smarty.const.URL_DIR`user_data/faq.php"}-->
		<!--{assign var="product_select" value="`$smarty.const.URL_DIR`mypage/product_select.php"}-->
		<!--{if $smarty.server.PHP_SELF == $faq}-->
			<script src="<!--{$TPL_URLPATH}-->js/jquery-1.10.1.min.js"></script>
        	<script src="<!--{$TPL_URLPATH}-->js/jquery-migrate-1.2.1.min.js"></script>
        <!--{elseif $smarty.server.REQUEST_URI == "/user_data/color_check.php"}-->
        	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js" integrity="sha512-nhY06wKras39lb9lRO76J4397CH1XpRSLfLJSftTeo3+q2vP7PaebILH9TqH+GRpnOhfAGjuYMVmVTOZJ+682w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<!--{else}-->
        	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.6.4/jquery.min.js" integrity="sha512-SW0bB7zYONzOFdTogLM8mF+lpvSaPH55g+RyyV8+dRZkiW5n/c1gNgGk5i2xfzDLTmPHvSCqsaiEoZJDiToTWg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        	<script src="<!--{$TPL_URLPATH}-->js/jquery.biggerlink.js"></script>
	        <script>//<![CDATA[
	            $(function(){
	                $('.header_navi li, .recommendblock, .list_area, .newslist li, .bubbleBox, .arrowBox, .category_body, .navBox li,#mypagecolumn .cartitemBox').biggerlink();
	            });
	        //]]></script>
        <!--{/if}-->
        <script src="<!--{$TPL_URLPATH}-->js/barbutton.js"></script>
        <script src="<!--{$TPL_URLPATH}-->js/category.js"></script>
        <script src="<!--{$TPL_URLPATH}-->js/news.js"></script>
        <script src="<!--{$TPL_URLPATH}-->js/app.min.js"></script><!-- もっと見る商品一覧が動かないのでずらす -->

        <!--{* jQuery Mobile *}-->
        <script src="<!--{$TPL_URLPATH}-->js/config.js"></script>
        <!--{if $smarty.server.PHP_SELF == $faq || $smarty.server.PHP_SELF == $product_select}-->
				<link rel="stylesheet" media="only screen" href="<!--{$TPL_URLPATH}-->js/jquery.mobile/jquery.mobile-1.3.1.min.css" />
        		<!--<script src="<!--{$TPL_URLPATH}-->js/jquery.mobile/jquery.mobile-1.3.1.min.js"></script>--><!-- 検索ボタンがhiddenになってしまうので一旦コメントアウトishibashi -->
		<!--{/if}-->
		<!--{* 〜/index.phpは noindex *}-->
		<!--{if strpos($smarty.server.REQUEST_URI,'index.php') !== false}-->
			<meta name="robots" content="noindex">
		<!--{/if}-->
		<!--{* 20180425 add *}-->
		<!--{if $smarty.server.PHP_SELF == $top_url}-->
			<PageMap>
				<DataObject type="thumbnail">
					<Attribute name="src" value="https://onepiece-rental.net/upload/save_image/02131356_5c63a386ea6fd.jpg"/>
					<Attribute name="width" value="100"/>
					<Attribute name="height" value="130"/>
				</DataObject>
			</PageMap>
			<link rel="canonical" href="https://onepiece-rental.net/">
		<!--{else}-->
			<link rel="canonical" href="https://onepiece-rental.net<!--{$smarty.server.REQUEST_URI}-->" />
		<!--{/if}-->
        <!--{* スマートフォンカスタマイズ用JS *}-->
        <script src="<!--{$TPL_URLPATH}-->js/jquery.autoResizeTextAreaQ-0.1.js"></script>
        <script src="<!--{$TPL_URLPATH}-->js/jquery.flickslide.js"></script>
        <script src="<!--{$TPL_URLPATH}-->js/favorite.js"></script>
        <!--<script src="<!--{$TPL_URLPATH}-->js/app.min.js"></script>-->
<!--{*        
        <link rel="shortcut icon" href="https://www.onepiece-rental.net/user_data/packages/wanpi/img/favicon.ico" />
        <link rel="icon" type="image/vnd.microsoft.icon" href="https://www.onepiece-rental.net/user_data/packages/wanpi/img/favicon.ico" />
*}-->
        <!--{* iPhone用アイコン画像 *}-->
        <link rel="apple-touch-icon" href="<!--{$smarty.const.HTTPS_URL}-->img/common/apple-touch-icon.png" />
		<!--google analytics-->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-NWV75Z8');</script>
		<!-- End Google Tag Manager Q20170510-->
		<!--google analytics end-->
	<!--{assign var="fukusou" value="`$smarty.const.URL_DIR`user_data/fukusou.php"}-->
	<!--{assign var="wedding2" value="`$smarty.const.URL_DIR`user_data/kekkonsiki_nijikai.php"}-->
	<!--{assign var="nijikai3" value="`$smarty.const.URL_DIR`user_data/kekkonsiki_nijikai3.php"}-->
	<!--{assign var="maternity1" value="`$smarty.const.URL_DIR`user_data/maternity.php"}-->
	<!--{assign var="maternity2" value="`$smarty.const.URL_DIR`user_data/maternity2.php"}-->

	<!--{* ドレス一覧 *}-->
	<!--{assign var="url_dress_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&kind2=44"}-->
	<!--{* セットドレス一覧 *}-->
	<!--{assign var="url_set_dress_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&kind3=232"}-->
	<!--{* ワンピース一覧 *}-->
	<!--{assign var="url_one_piece_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=1&kind1=1"}-->
	<!--{* セレモニースーツ一覧 *}-->
	<!--{assign var="url_ceremony_one_piece_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=367"}-->
	<!--{* キッズフォーマル一覧 *}-->
	<!--{assign var="url_kids_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=371"}-->
	<!--{* キッズドレス一覧 *}-->
	<!--{assign var="url_kidsdress_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=375"}-->
	<!--{* パンツドレス一覧 *}-->
	<!--{assign var="url_ceremony_pants_list" value="`$smarty.const.HTTPS_URL`products/list.php?&kind_all=all&name=19-"}-->

	<!--{* ブラックフォーマル覧 *}-->
	<!--{assign var="url_blackf_list" value="`$smarty.const.HTTPS_URL`products/list.php?&category_id=0&kind_all=all&name=black_f"}-->

	<!--{* マタニティ一覧 *}-->
	<!--{assign var="url_maternity_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&size%5B%5D=8"}-->

	<!--{* コート一覧 *}-->
	<!--{assign var="url_ceremony_coat_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=368"}-->

	<!--{* 羽織一覧 *}-->
	<!--{assign var="url_outer_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=64"}-->

	<!--{* ストール一覧 *}-->
	<!--{assign var="url_stall_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=64&type%5B%5D=000_78"}-->

	<!--{* ボレロ一覧 *}-->
	<!--{assign var="url_bolero_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=64&type%5B%5D=001_79"}-->

	<!--{* バッグ一覧 *}-->
	<!--{assign var="url_bag_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=231"}-->

	<!--{* ネックレス一覧 *}-->
	<!--{assign var="url_necklace_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=63"}-->

	<!--{* その他小物一覧 *}-->
	<!--{assign var="url_other_item_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=65"}-->

	<!--{* イヤリング一覧 *}-->
	<!--{assign var="url_earrings_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=65&type%5B%5D=006_370"}-->

	<!--{* ヘアアクセ一覧 *}-->
	<!--{assign var="url_hairacce_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=65&type%5B%5D=005_352"}-->

	<!--{* ブレスレット一覧 *}-->
	<!--{assign var="url_bracelet_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=65&type%5B%5D=004_179"}-->

	<!--{* ベルト一覧 *}-->
	<!--{assign var="url_belt_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=65&type%5B%5D=003_144"}-->

	<!--{* コサージュ一覧 *}-->
	<!--{assign var="url_corsage_list" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=65&type%5B%5D=005_143"}-->

	<!--{* アドバイステレフォン *}-->
	<!--{assign var="url_advicetel_form" value="`$smarty.const.HTTPS_URL`contact/"}-->

	<!--{* ドレス ご利用シーン別一覧 *}-->
		<!--{* 結婚式・お呼ばれ *}-->
		<!--{assign var="url_dress_list_oyobare" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event1"}-->

		<!--{* ご親族様 *}-->
		<!--{assign var="url_dress_list_relatives" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event2"}-->

		<!--{* 結婚式二次会 パーティー *}-->
		<!--{assign var="url_dress_list_nijikai" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event4"}-->

		<!--{* マタニティ向け *}-->
		<!--{assign var="url_dress_list_matanity" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&mode=category_search&rental_date=&size%5B%5D=8&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl="}-->

		<!--{* 花嫁様向け二次会 *}-->
		<!--{assign var="url_dress_list_hanayome_nijikai" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event3"}-->

		<!--{* 謝恩会向けドレス一覧 *}-->
		<!--{assign var="url_dress_list_shaonkai" value="`$smarty.const.HTTPS_URL`products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event5"}-->


<!--{* title,description,keywords *}-->
	<!--{* トップページ *}-->
	<!--{if $smarty.server.PHP_SELF == $top_url}-->
	    <title><!--{$arrSiteInfo.shop_name|h}--><!--{if $tpl_subtitle|strlen >= 1}-->｜<!--{$tpl_subtitle|h}--><!--{elseif $tpl_title|strlen >= 1}-->｜<!--{$tpl_title|h}--><!--{/if}--></title>
		<meta name="description" content="【ドレス一律5,980円】【NHK・日テレで紹介】 【レビュー5万件超】【実績10万着超】初めての方にも安心のレンタルドレス。どこよりも詳しい商品情報を掲載しています。" />
		<meta name="keywords" content="レンタルドレス,ドレス,レンタル,ドレスレンタル,結婚式,二次会,婚活,パーティー,ワンピース,<!--{$tpl_region_name}-->" />
		<meta name="thumbnail" content="https://onepiece-rental.net/user_data/packages/sphone/img/wanpi_thumbnail.jpg" />
	<!--{* 商品詳細ページ *}-->	
	<!--{elseif $smarty.server.PHP_SELF == $items }-->
<script src="https://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js" integrity="sha256-vcH8PFBYdhGpTcT3FBwFjvLkhqBdl396kLMbEya2xEA=" crossorigin="anonymous"></script>
<link rel="stylesheet" media="only screen" href="https://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
		<!--{assign var=codecnt value=$arrProductCode|@count}-->
		<!--{assign var=codemax value="`$codecnt-1`"}-->
			<!--{if $codecnt > 1}-->
				<title><!--{$tpl_h1|escape}-->｜<!--{$arrProductCode.0}-->0<!--{$arrProductCode[$codemax]}-->｜<!--{$arrSiteInfo.shop_name|escape}--></title>
			<!--{else}-->
				<title><!--{$tpl_h1|escape}-->｜<!--{$arrProductCode.0}-->｜<!--{$arrSiteInfo.shop_name|escape}--></title>
			<!--{/if}-->
		<!--{if $arrProduct.haiki == 1 OR $arrProduct.status <> 1}-->
			<meta name="robots" content="noindex">
		<!--{else}-->
	        <!--{* 商品詳細urlに「&」が一つでもついている場合はnoindex *}-->
	        <!--{$noindex_tag}-->	
		<!--{/if}-->
			<meta name="description" content="<!--{$arrProduct.recommended_staff_comment|escape|regex_replace:"/[\r\t\n]/":""}--><!--{$wanpi_comment}-->" />
			<meta name="keywords" content="<!--{if $arrProduct.comment3}--><!--{$arrProduct.comment3|escape|regex_replace:"/[\r\t\n]/":""|regex_replace:"/、/":","}--><!--{/if}-->" />
	<!--{elseif $smarty.server.PHP_SELF == "/products/list.php"}-->
<link rel="stylesheet" media="only screen" href="<!--{$TPL_URLPATH}-->css/jquery.mobile-1.0.1.min.css" />
<script src="https://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js" integrity="sha256-vcH8PFBYdhGpTcT3FBwFjvLkhqBdl396kLMbEya2xEA=" crossorigin="anonymous"></script>
		<meta name="thumbnail" content="https://onepiece-rental.net/upload/save_image/02131356_5c63a386ea6fd.jpg" />
	    <title><!--{if $h1_title|strlen >= 1}--><!--{$h1_title|h}--><!--{else}--><!--{$tpl_subtitle|h}--><!--{/if}-->｜<!--{$tpl_title|h}-->｜<!--{$arrSiteInfo.shop_name|h}-->
    	</title>
		<meta name="description" content="【発送日前日21時までキャンセル無料】【NHK・日テレで紹介】 初めての方にも安心のレンタルドレス［実績10万着超］、［商品レビュー5万件超］どこよりも詳しい商品情報を掲載！ドレス5,980円〜商品一覧<!--{$title_brand|h}-->" />
		<meta name="keywords" content="レンタルドレス,ドレス,レンタル,ドレスレンタル,結婚式,二次会,婚活,パーティー,ワンピース,<!--{$tpl_region_name}-->" />
	<!--{elseif $smarty.server.PHP_SELF == "/products/search.php"}-->
		<title>商品を探す｜レンタルドレスのワンピの魔法</title>
		<meta name="author" content="レンタルドレスのワンピの魔法" />
		<meta name="description" content="【発送日前日21時までキャンセル無料】【NHK・日テレで紹介】 初めての方にも安心のレンタルドレス［実績10万着超］、［商品レビュー5万件超］どこよりも詳しい商品情報を掲載！ドレス5,980円〜商品一覧<!--{$title_brand|h}-->" />
		<meta name="keywords" content="レンタルドレス,ドレス,レンタル,ドレスレンタル,結婚式,二次会,婚活,パーティー,ワンピース,<!--{$tpl_region_name}-->" />
	<!--{elseif $smarty.server.PHP_SELF == $fukusou}-->
		<title>◆結婚式の服装 ・女性編(ドレスの選び方)</title>
		<meta name="author" content="レンタルドレスのワンピの魔法" />
		<meta name="description" content="婚活の服装、女性は何を着て行けばいいのでしょう？女性が婚活に着て行く服を、イラストで分かりやすく解説。" />
		<meta name="keywords" content="婚活,服装,女性,30代,ファッション,婚活服,女,着て行く,服,ワンピース,モテ" />
	<!--{elseif $smarty.server.PHP_SELF == $wedding1}-->
		<title>結婚式・披露宴の服装｜～女性編～【ＮＧな服を写真で解説！】</title>
		<meta name="author" content="レンタルドレスのワンピの魔法" />
		<meta name="description" content="結婚式での女性の服装は？結婚式へのお呼ばれでＯＫな服装・ＮＧな服装を、写真で分かりやすく解説。ドレスのコーディネート例をご紹介。" />
		<meta name="keywords" content="結婚式,服装,ゲストドレス,マナー,女性,コーディネイト" />
	<!--{elseif $smarty.server.PHP_SELF == $wedding2}-->
		<title>結婚式二次会の服装｜服装マナー【女性編】 会場別の二次会服装を解説</title>
		<meta name="author" content="レンタルドレスのワンピの魔法" />
		<meta name="description" content="結婚式二次会にお呼ばれしたときの服装（コーディネート）のポイントとNGマナーを二次会となる会場別に解説しています。結婚式二次会のドレス・ワンピースのレンタルならば「ワンピの魔法」にお任せください" />
		<meta name="keywords" content="結婚式,二次会,服装,女性,マナー,ゲストドレス" />
	<!--{elseif $smarty.server.PHP_SELF == $nijikai3}-->
		<title>結婚式二次会に出席したワンピの魔法ユーザーの「着用ドレス」と、成功例・失敗例など「感想」をご紹介｜二次会の服装マナー【女性編】</title>
		<meta name="author" content="レンタルドレスのワンピの魔法" />
		<meta name="description" content="実際に結婚式二次会に出席したワンピの魔法ユーザーの「着用ドレス」と、成功・時には失敗した「感想」をご紹介します。どんな解説よりも信頼できる参列者の生の声を聞いて、安心して二次会に参加しましょう！" />
		<meta name="keywords" content="結婚式,二次会,服装,服,女性,2次会,ワンピース,レンタル,お呼ばれ,スタイル,ドレス,ゲスト,30代" />
	<!--{elseif $smarty.server.PHP_SELF == $maternity1}-->
		<title>マタニティの結婚式向けドレス紹介！そのままレンタルOK！</title>
		<meta name="author" content="レンタルドレスのワンピの魔法" />
		<meta name="description" content="ブランドのマタニティフォーマルドレスが豊富なワンピの魔法。可愛いマタニティドレス・ワンピースが3,980円からレンタルできます。" />
		<meta name="keywords" content="マタニティ,フォーマル,ワンピース,レンタル,お呼ばれ,結婚式" />
	<!--{elseif $smarty.server.PHP_SELF == $maternity2}-->
		<title>マタニティフォーマルドレス　【マタニティドレスの購入とレンタル、どちらがお得？】</title>
		<meta name="author" content="レンタルドレスのワンピの魔法" />
		<meta name="description" content="着る機会の少ないマタニティフォーマルドレスは、買うよりもレンタルがお得？それぞれのメリット・デメリットと、賢い利用法をご紹介。" />
		<meta name="keywords" content="マタニティフォーマル,マタニティー,マタニティ,フォーマル,ドレス,レンタル,お呼ばれ,結婚式,ワンピース" />
    <!--悩み別おすすめドレス企画 削除-->
	<!--{elseif $smarty.server.REQUEST_URI == "/user_data/concept.php"}-->
		<link rel="stylesheet" media="only screen" href="/user_data/packages/sphone/css/relieve.css">
		<title>簡単・わかりやすいレンタルシステム｜ワンピの魔法へようこそ！</title>
	<!--{* YSS注文完了タグ　注文完了ページの<HEAD>タグ開始直後のサイトジェネラルタグの下に設置 *}-->
	<!--{elseif strpos($smarty.server.PHP_SELF,'shopping/complete.php') !== false}-->
	<script async>
	ytag({
	  "type": "yss_conversion",
	  "config": {
	    "yahoo_conversion_id": "1001173777",
	    "yahoo_conversion_label": "AiM6CIH9vu0BEI7FutwB",
	    "yahoo_conversion_value": "0"
	  }
	});
	</script>

	<script async>
	ytag({
	  "type":"yjad_conversion",
	  "config":{
	    "yahoo_ydn_conv_io": "jlp_ClA_iXR-jtHQP49xPw..",
	    "yahoo_ydn_conv_label": "8ZLYQ23E1CDT5SJOETA772707",
	    "yahoo_ydn_conv_transaction_id": "",
	    "yahoo_ydn_conv_value": "0"
	  }
	});
	</script>

		<!--{* User Insight PCCV Code Start : *}-->
		<script type="text/javascript">
		var _uiconv = _uiconv || [];
		var _uiconv_value = {};
		_uiconv_value['id'] = 'wanpi_cv'; /* conversion name */
		_uiconv_value['price'] = ''; /* price */
		_uiconv_value['item_code'] = ''; /* item name */
		_uiconv_value['free_1'] = '';
		_uiconv_value['free_2'] = '';
		_uiconv_value['free_3'] = '';
		_uiconv_value['free_4'] = '';
		_uiconv_value['free_5'] = '';
		_uiconv_value['free_6'] = '';
		_uiconv_value['free_7'] = '';
		_uiconv_value['free_8'] = '';
		_uiconv_value['free_9'] = '';
		_uiconv_value['free_10'] = '';
		_uiconv.push(_uiconv_value);
		</script>
		<!--{* User Insight PCCV Code End : *}-->


	<!--{* YSS会員登録完了ページの<HEAD>タグ開始直後のサイトジェネラルタグの下に設置 *}-->
	<!--{elseif strpos($smarty.server.PHP_SELF,'entry/complete.php') !== false}-->
	<script async>
	ytag({
	  "type": "yss_conversion",
	  "config": {
	    "yahoo_conversion_id": "1001173777",
	    "yahoo_conversion_label": "x7m2COH0z-0BEI7FutwB",
	    "yahoo_conversion_value": "0"
	  }
	});
	</script>

	<script async>
	ytag({
	  "type":"yjad_conversion",
	  "config":{
	    "yahoo_ydn_conv_io": "jlp_ClA_iXR-jtHQP49xPw..",
	    "yahoo_ydn_conv_label": "AX5FH6NEBEX9LUPQZSY772708",
	    "yahoo_ydn_conv_transaction_id": "",
	    "yahoo_ydn_conv_value": "0"
	  }
	});
	</script>
	<!--{else}-->
	    <title><!--{$text_tdk}--><!--{$tpl_title|h}-->｜<!--{$arrSiteInfo.shop_name|h}--></title>
		<meta name="description" content="<!--{$arrSiteInfo.shop_name|h}-->｜<!--{$text_tdk|h}--><!--{if $tpl_subtitle|strlen >= 1}-->｜<!--{$tpl_subtitle|h}--><!--{elseif $tpl_title|strlen >= 1}-->｜<!--{$tpl_title|h}--><!--{/if}-->" />
		<meta name="keywords" content="レンタルドレス,ドレス,レンタル,ドレスレンタル,結婚式,二次会,婚活,パーティー,ワンピース,<!--{$tpl_region_name}-->" />
	<!--{/if}-->


<!--{* 管理画面で登録されたkeywords,descriptionは反映しない
    <!--{if $arrPageLayout.author|strlen >= 1}-->
        <meta name="author" content="<!--{$arrPageLayout.author|h}-->" />
    <!--{/if}-->
    <!--{if $arrPageLayout.description|strlen != 0}-->
        <meta name="description" content="<!--{$arrPageLayout.description|h}-->" />
    <!--{/if}-->
    <!--{if $arrPageLayout.keyword|strlen >= 1}-->
        <meta name="keywords" content="<!--{$arrPageLayout.keyword|h}-->" />
    <!--{/if}-->
*}-->

	<!--{* 新規会員登録 *}-->
	<!--{if $smarty.server.PHP_SELF == "/entry/index.php"}-->
		<!--{* 全角を半角にする *}-->
		<script type="text/javascript">
			$(function(){
			    $(".halfcharacter").blur(function(){
			        charChange($(this));
			    });


			    charChange = function(e){
			        var val = e.val();
			        var str = val.replace(/[Ａ-Ｚａ-ｚ０-９]/g,function(s){return String.fromCharCode(s.charCodeAt(0)-0xFEE0)});

			        if(val.match(/[Ａ-Ｚａ-ｚ０-９]/g)){
			            $(e).val(str);
			        }
			    }
			});
		</script>

		<!--{* ページ離脱確認 *}-->
		<meta charset="UTF-8">
		  <meta name="viewport" content="width=device-width, initial-scale=1.0">
		  <meta http-equiv="X-UA-Compatible" content="ie=edge">
		  <script type="text/javascript">
			var onBeforeunloadHandler = function(e) {
			    e.returnValue = '入力中の内容は消えてしまいますが、よろしいですか？';
			};
			// イベントを登録
			window.addEventListener('beforeunload', onBeforeunloadHandler, false);

			function preSubmit(){
				// 新規登録のsubmit時はイベントを削除
			    window.removeEventListener('beforeunload', onBeforeunloadHandler, false);
			}

		  </script>
		  <title>入力中の内容は消えてしまいますが、よろしいですか？</title>
		  <!--{* ページ離脱確認 end *}-->

	<!--{/if}-->

        <script type="text/javascript">//<![CDATA[
            <!--{$tpl_javascript}-->
            $(function(){
                <!--{$tpl_onload}-->
            });
        //]]></script>

        <!--{*▼Head COLUMN*}-->
            <!--{if !empty($arrPageLayout.HeadNavi)}-->
                <!--{* ▼上ナビ *}-->
                    <!--{foreach key=HeadNaviKey item=HeadNaviItem from=$arrPageLayout.HeadNavi}-->
                        <!--{* ▼<!--{$HeadNaviItem.bloc_name}--> ここから*}-->
                            <!--{if $HeadNaviItem.php_path != ""}-->
                                <!--{include_php file=$HeadNaviItem.php_path items=$HeadNaviItem}-->
                            <!--{else}-->
                                <!--{include file=$HeadNaviItem.tpl_path items=$HeadNaviItem}-->
                            <!--{/if}-->
                        <!--{* ▲<!--{$HeadNaviItem.bloc_name}--> ここまで*}-->
                    <!--{/foreach}-->
                <!--{* ▲上ナビ *}-->
            <!--{/if}-->
        <!--{* ▲Head COLUMN*}-->

</head>
    <!-- ▼BODY部 スタート -->
    <!--{include file='./site_main.tpl'}-->
	<!-- ＜Y!リターゲティングタグ＞ウェブサイト内の全ページの終了タグ</body>の直前に設置 -->
	<script async src="https://s.yimg.jp/images/listing/tool/cv/ytag.js"></script>
	<script>
	window.yjDataLayer = window.yjDataLayer || [];
	function ytag() { yjDataLayer.push(arguments); }
	ytag({
	  "type":"yss_retargeting",
	  "config": {
	    "yahoo_ss_retargeting_id": "1001173777",
	    "yahoo_sstag_custom_params": {
	    }
	  }
	});
	</script>
	<!--{* User Insight PCDF Code Start : *}-->
	<script type="text/javascript">
	var _uic = _uic ||{}; var _uih = _uih ||{};_uih['id'] = 54912;
	_uih['lg_id'] = '';
	_uih['fb_id'] = '';
	_uih['tw_id'] = '';
	_uih['uigr_1'] = ''; _uih['uigr_2'] = ''; _uih['uigr_3'] = ''; _uih['uigr_4'] = ''; _uih['uigr_5'] = '';
	_uih['uigr_6'] = ''; _uih['uigr_7'] = ''; _uih['uigr_8'] = ''; _uih['uigr_9'] = ''; _uih['uigr_10'] = '';
	_uic['uls'] = 1;

	/* DO NOT ALTER BELOW THIS LINE */
	/* WITH FIRST PARTY COOKIE */
	(function() {
	var bi = document.createElement('script');bi.type = 'text/javascript'; bi.async = true;
	bi.src = '//cs.nakanohito.jp/b3/bi.js';
	var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(bi, s);
	})();
	</script>
	<!-- User Insight PCDF Code End :  -->

	<script async src="https://s.yimg.jp/images/listing/tool/cv/ytag.js"></script>
	<script>
	window.yjDataLayer = window.yjDataLayer || [];
	function ytag() { yjDataLayer.push(arguments); }
	ytag({
	  "type":"yjad_retargeting",
	  "config":{
	    "yahoo_retargeting_id": "2ZIJCX2KMP",
	    "yahoo_retargeting_label": "",
	    "yahoo_retargeting_page_type": "",
	    "yahoo_retargeting_items":[
	      {item_id: '', category_id: '', price: '', quantity: ''}
	    ]
	  }
	});
	</script>

	<!--{* seo - 構造化データ部分 *}-->
	<script type="application/ld+json">
	  {
	    "@context": "http://schema.org",
	    "@type": "BreadcrumbList",
	    "itemListElement":
	    [
	      {
	        "@type": "ListItem",
	        "position": 1,
	        "item":
	        {
	          "@id": "https://onepiece-rental.net",
			  "name": "<!--{$arrSiteInfo.shop_name|escape}-->"
	        }
	      },
	      {
	        "@type": "ListItem",
	        "position": 2,
	        "item":
	        {
	        <!--{if strpos($smarty.server.PHP_SELF,'products/detail.php') !== false}-->
	        	"@id": "https://onepiece-rental.net<!--{$smarty.server.PHP_SELF}-->?product_id=<!--{$arrProduct.product_id}-->",
	        	"name": "<!--{$tpl_h1|escape}-->｜<!--{$arrProductCode.0}-->｜<!--{$arrSiteInfo.shop_name|escape}-->"
	        <!--{else}-->
	        	"@id": "https://onepiece-rental.net<!--{$smarty.server.PHP_SELF}-->",
	        	"name": "<!--{$tpl_title|h}-->｜<!--{$arrSiteInfo.shop_name|escape}-->"
	        <!--{/if}-->
	        }
	      }
	    ]
	  }
	</script>
    <!-- ▲BODY部 エンド -->

    <!--{if $smarty.server.PHP_SELF == $items || $smarty.server.PHP_SELF == $top_url}-->
    <script type="text/javascript" async>
    (function(callback){
      var script = document.createElement("script");
      script.type = "text/javascript";
      script.src = "https://www.rentracks.jp/js/itp/rt.track.js?t=" + (new Date()).getTime();
      if ( script.readyState ) {
        script.onreadystatechange = function() {
          if ( script.readyState === "loaded" || script.readyState === "complete" ) {
            script.onreadystatechange = null;
            callback();
          }
        };
      } else {
        script.onload = function() {
          callback();
        };
      }
      document.getElementsByTagName("head")[0].appendChild(script);
    }(function(){}));
  </script>
  <!--{/if}-->
</html>
