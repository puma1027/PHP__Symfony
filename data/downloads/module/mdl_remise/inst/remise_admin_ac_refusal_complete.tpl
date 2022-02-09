<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<div id="mypagecolumn">
    <h2 class="title"><!--{$tpl_title|h}--></h2>
    <div id="mycontents_area">
        <h3>定期購買会員　退会完了</h3>
        <div id="complete_area">
            <div class="message">定期購買の退会手続きが完了いたしました。<br /></div>

            <div class="btn_area">
                <ul>
                    <li>
                        <a href="javascript:;" onclick="fnOrderDetailReload(); return false;"><img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_close.jpg" alt="閉じる" /></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<!--
    function fnOrderDetailReload() {
         var obj1 = window.opener.document.getElementsByName("ac_change_status");
         obj1[1].checked = true;
         window.opener.$("#form1").submit();
         window.close();
    }
//-->
</script>
