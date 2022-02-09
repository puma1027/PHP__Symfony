<style>
.flex.search_input > .ui-radio, .flex.search_input > .ui-checkbox {
    padding: 0 20px 0 0;
}
span.ui-btn-text {
    padding-left: 10px;
}
</style>
<a id="ex_search" name="ex_search"></a>
<!--{if $smarty.get.category_id == $smarty.const.CATEGORY_DRESS_ALL || $smarty.get.category_id == $smarty.const.CATEGORY_DRESS || $smarty.get.category_id == $smarty.const.CATEGORY_DRESS3 || $smarty.get.category_id == $smarty.const.CATEGORY_DRESS4 || $smarty.get.category_id == $smarty.const.CATEGORY_SET_DRESS}--> <!--//::N00083 Add 20131201-->
<!--【★社員コンシェルジェのドレス検索・ここから】-->
<!--【検索枠・ここから】-->
<div class="pc_show">
<form method="get" name="form_ex_dress" id="form_ex_dress"  action="<!--{$smarty.const.URL_DIR}-->products/list.php">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <!--{if $smarty.get.category_id == 0}-->
    <input type="hidden" name="category_id" value="<!--{$smarty.const.CATEGORY_DRESS_ALL}-->">
    <!--{else}-->
    <input type="hidden" name="category_id" value="<!--{$smarty.get.category_id|default:$smarty.const.CATEGORY_DRESS_ALL}-->">
    <!--{/if}-->
    <input type="hidden" name="mode" value="ex_search">
    <input type="hidden" id="rental_date" name="rental_date" value="<!--{$smarty.get.rental_date}-->">

    <div id="pw_content20130909" class="clearfix20130902">
        <div id="pw_list20130909" class="clearfix20130902">
            <div class="block0120130909 clearfix20130909">
                <div class="box0120130909">
                    <div class="leaf0120130909"><img src="<!--{$TPL_DIR}-->img/201303/list/pw_list35.png" width="839" height="31" alt="検索条件" /></div>
                    <div class="leaf0220130909">
                        <div id="dress20130909">
                            <table width="840" border="0" cellspacing="0" cellpadding="0">
                                <!--▼セーラ社長▼-->
                                <tr class="tr0320130909">
                                    <td class="right20130909" rowspan="2"><img src="<!--{$TPL_DIR}-->img/201303/list/syain01.jpg" width="191" height="118" alt="セーラ社長" /></td>
                                    <td class="left20130909">年代に合ったドレス</td>
                                    <td class="right20130909" colspan="3">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <div class="flex search_input">
                                                <input id="cb_age_10" name="age[]" type="checkbox" value="cb_age_10" <!--{if "cb_age_10"|in_array:$smarty.get.age}-->checked<!--{/if}-->/><label for="cb_age_10">10代</label>
                                                <input id="cb_age_20fh" name="age[]" type="checkbox" value="cb_age_20fh" <!--{if "cb_age_20fh"|in_array:$smarty.get.age}-->checked<!--{/if}-->/><label for="cb_age_20fh">20代前半</label>
                                                <input id="cb_age_20sh" name="age[]" type="checkbox" value="cb_age_20sh" <!--{if "cb_age_20sh"|in_array:$smarty.get.age}-->checked<!--{/if}-->/><label for="cb_age_20sh">20代後半</label>
                                                <input id="cb_age_30fh" name="age[]" type="checkbox" value="cb_age_30fh" <!--{if "cb_age_30fh"|in_array:$smarty.get.age}-->checked<!--{/if}-->/><label for="cb_age_30fh">30代前半</label>
                                            </div>
                                        </tr>
                                        <tr>
                                            <div class="flex search_input">
                                                <input id="cb_age_30sh" name="age[]" type="checkbox" value="cb_age_30sh" <!--{if "cb_age_30sh"|in_array:$smarty.get.age}-->checked<!--{/if}-->/><label for="cb_age_30sh">30代後半</label>
                                                <input id="cb_age_40fh" name="age[]" type="checkbox" value="cb_age_40fh" <!--{if "cb_age_40fh"|in_array:$smarty.get.age}-->checked<!--{/if}-->/><label for="cb_age_40fh">40代前半</label>
                                                <input id="cb_age_40sh" name="age[]" type="checkbox" value="cb_age_40sh" <!--{if "cb_age_40sh"|in_array:$smarty.get.age}-->checked<!--{/if}-->/><label for="cb_age_40sh">40代後半</label>
                                                <input id="cb_age_50over" name="age[]" type="checkbox" value="cb_age_50over" <!--{if "cb_age_50over"|in_array:$smarty.get.age}-->checked<!--{/if}-->/><label for="cb_age_50over">50代～</label>
                                            </div>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="tr0320130909">
                                    <td class="left20130909">シーンに合ったドレス</td>
                                    <td class="right20130909" colspan="3">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <div class="flex search_input">
                                                    <input id="cb_event1" name="event[]" type="checkbox" value="cb_event1" <!--{if "cb_event1"|in_array:$smarty.get.event}-->checked<!--{/if}-->/><label for="cb_event1">結婚式お呼ばれ</label>
                                                    <input id="cb_event6" name="event[]" type="checkbox" value="cb_event6" <!--{if "cb_event6"|in_array:$smarty.get.event}-->checked<!--{/if}-->/><label for="cb_event6">結婚式二次会</label>
                                                    <input id="cb_event2" name="event[]" type="checkbox" value="cb_event2" <!--{if "cb_event2"|in_array:$smarty.get.event}-->checked<!--{/if}-->/><label for="cb_event2">結婚式ご親族</label>
                                                </div>
                                            </tr>
                                            <div class="flex search_input"> 
                                                <input id="cb_event5" name="event[]" type="checkbox" value="cb_event5" <!--{if "cb_event5"|in_array:$smarty.get.event}-->checked<!--{/if}-->/><label for="cb_event5">謝恩会</label>
                                                <input id="cb_event4" name="event[]" type="checkbox" value="cb_event4" <!--{if "cb_event4"|in_array:$smarty.get.event}-->checked<!--{/if}-->/><label for="cb_event4">パーティー</label>
                                                <input id="cb_event3" name="event[]" type="checkbox" value="cb_event3" <!--{if "cb_event3"|in_array:$smarty.get.event}-->checked<!--{/if}-->/><label for="cb_event3">結婚式花嫁2次会</label>
                                            </div>
                                        </table>
                                    </td>
                                </tr>
                                <!--▲セーラ社長▲-->
                                <!--▼たっくん▼-->
                                <tr class="tr0320130909">
                                    <td class="right20130909" rowspan="2"><img src="<!--{$TPL_DIR}-->img/201303/list/syain02.jpg" width="191" height="98" alt="たっくん" /></td>
                                    <td class="left20130909">品質でがっかりしない</td>
                                    <td class="right20130909" colspan="3">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <div class="flex search_input">
                                                <input id="cb_quality1" name="quality[]" type="checkbox" value="cb_quality1" <!--{if "cb_quality1"|in_array:$smarty.get.quality}-->checked<!--{/if}-->/><label for="cb_quality1">新品同様の品</label>
                                            </div>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="tr0320130909">
                                    <td class="left20130909">サイズで失敗しない</td>
                                    <td class="right20130909" colspan="3">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <div class="flex search_input">
                                                    <input id="cb_size1" name="size_failure[]" type="checkbox" value="cb_size1" <!--{if "cb_size1"|in_array:$smarty.get.size_failure}-->checked<!--{/if}-->/><label for="cb_size1">背中のひもでサイズを調節でき、体にぴったりフィットするドレス</label>
                                                </div>
                                            </tr>
                                            <tr>
                                                <div class="flex search_input">
                                                    <input id="cb_size2" name="size_failure[]" type="checkbox" value="cb_size2" <!--{if "cb_size2"|in_array:$smarty.get.size_failure}-->checked<!--{/if}-->/><label for="cb_size2">着心地が楽な、締めつけ感のないゆったりドレス</label>
                                                </div>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!--▲たっくん▲-->
                                <!--▼類子▼-->
                                <tr class="tr0320130909">
                                    <td class="right20130909" rowspan="2"><img src="<!--{$TPL_DIR}-->img/201303/list/syain03.jpg" width="191" height="102" alt="のりくん" /></td>
                                    <td class="left20130909">体型の悩みを解決</td>
                                    <td class="right20130909" colspan="3">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <div class="flex search_input">
                                                    <input id="cb_complex1" name="complex[]" type="checkbox" value="cb_complex1" <!--{if "cb_complex1"|in_array:$smarty.get.complex}-->checked<!--{/if}-->/><label for="cb_complex1">ぽっこりお腹カバー</label>
                        I                           <input id="cb_complex2" name="complex[]" type="checkbox" value="cb_complex2" <!--{if "cb_complex2"|in_array:$smarty.get.complex}-->checked<!--{/if}-->/><label for="cb_complex2">ぽっちゃり二の腕カバー(袖つきドレス)</label>
                                                </div>
                                            </tr>
                                            <tr>
                                                <div class="flex search_input">
                                                    <input id="cb_complex3" name="complex[]" type="checkbox" value="cb_complex3" <!--{if "cb_complex3"|in_array:$smarty.get.complex}-->checked<!--{/if}-->/><label for="cb_complex3">大きめバストすっきり</label>
                                                    <input id="cb_complex4" name="complex[]" type="checkbox" value="cb_complex4" <!--{if "cb_complex4"|in_array:$smarty.get.complex}-->checked<!--{/if}-->/><label for="cb_complex4">ひかえめバストふっくら</label>
                                                </div>
                                            </tr>
                                            <!-- 201706 del
                                            <tr>
                                                <td class="color01">
                                                    <input id="cb_complex5" name="complex[]" type="checkbox" value="cb_complex5" <!--{if "cb_complex5"|in_array:$smarty.get.complex}-->checked<!--{/if}-->/>大きめヒップカバー
                                                </td>
                                            </tr>
                                            -->
                                        </table>
                                    </td>
                                </tr>
                                <tr class="tr0320130909">
                                    <td class="left20130909">お子様連れの悩みを解決</td>
                                    <td class="right20130909" colspan="3">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <div class="flex search_input">
                                                    <input id="cb_worry1" name="worry[]" type="checkbox" value="cb_worry1" <!--{if "cb_worry1"|in_array:$smarty.get.worry}-->checked<!--{/if}-->/><label for="cb_worry1">生地が丈夫で、抱っこしやすい袖つきドレス</label>
                                                </div>
                                            </tr>
                                            <tr>
                                                <div class="flex search_input">
                                                    <input id="cb_worry2" name="worry[]" type="checkbox" value="cb_worry2" <!--{if "cb_worry2"|in_array:$smarty.get.worry}-->checked<!--{/if}-->/><label for="cb_worry2">授乳しやすいドレス</label>
                                                </div>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!--▲類子▲-->
                            </table>
                        </div>
                        <!-- //#dress -->
                    </div>
                </div>
                <!--【検索枠・ここまで】-->
                <div class="box0320130909">
                    <!--<a href="#" id="btn" name="btn" onclick='javascript:$("#div_more_search").append($("#dialog-form"));setWLenKneeParam();setClrParam();setExSearchParam();document.form_dress.submit();return false;'>-->
                    <a href="#" id="btn" name="btn" onclick='javascript:$("#div_more_search").append($("#dialog-form"));setWLenKneeParam();setPcClrParam();setPcExSearchParam();document.form_dress.submit();return false;'>
                    <img src="<!--{$TPL_DIR}-->img/201303/list/pw_list99_off.png" width="392" height="48" alt="検索" /></a>
                </div>
            </div>
            <!-- //.block01 -->
        </div>
        <!-- //#pw_list -->
    </div>
    <!-- //#pw_content -->
</form>
</div>
<!--{/if}-->
