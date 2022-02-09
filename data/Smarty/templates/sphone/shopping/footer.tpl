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

<!--▼ FOOTER-->
<footer class="global_footer">

    <nav class="guide_area">
    	<p>
    	<!--{assign var=server_qurey_string value="`$smarty.server.QUERY_STRING`"}-->
    	<!--{if $server_qurey_string == ""}-->
    	<a rel="external" href="?show_view=pc" class="btn_btm ui-link">PCページを見る</a>
    	<!--{else}-->
	    	<!--{if stripos($server_qurey_string,"show_view") === false}-->
	    		<!--{assign var=qurey_string value="`$server_qurey_string`&show_view=pc"}-->
	    	<!--{else}-->
	    		<!--{assign var=qurey_string value=$server_qurey_string|replace:'sphone':'pc'}-->
	    	<!--{/if}-->
    	<a rel="external" href="?<!--{$qurey_string}-->" class="btn_btm ui-link">PCページを見る</a>
    	<!--{/if}-->
    	</p>
        <p>
            <a rel="external" href="<!--{$smarty.const.HTTPS_URL}-->user_data/tel.php">アドバイステレフォン</a>
            <a rel="external" href="<!--{$smarty.const.HTTPS_URL}-->contact/<!--{$smarty.const.DIR_INDEX_PATH|h}-->">お問い合わせ</a><br />
            <a rel="external" href="<!--{$smarty.const.HTTPS_URL}-->user_data/guide.php">配送について</a>
            <a rel="external" href="<!--{$smarty.const.HTTPS_URL}-->user_data/guide6.php">返却について</a>
        </p>
    </nav>

    <p class="copyright"><small>Copyright &copy;
        2009-<!--{$smarty.now|date_format:"%Y"}--> Redundancy Co.,Ltd. All rights reserved.</small>
	</p>

</footer>
<!--//::N00082 Add 20130919-->
<script type="text/javascript" src="/user_data/packages/wanpi/js/jquery.lazyload.min.js"></script>
<script type="text/javascript">
$("img").lazyload({
    effect: 'fadeIn',
    effectspeed: 20
});
</script>
<!--//::N00082 end 20130919-->
<!--▲ FOOTER-->
