<!--★★メインコンテンツ★★-->
	<!-- Add KH 2014/3/13 -->
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->ChlFApkIyT8eBiMz/css/datePicker/flora.datepicker.css" type="text/css" media="all" />
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/datePicker/ui.datepicker.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/datePicker/ui.datepicker-ja.js"></script>
	<!---End--->

<script type="text/javascript">
    <!--
    function fnOpenPdfSettingPage(action){
        document.form1['mode'].value = 'pdf';
        document.form1.submit();
    }
// ================= 2012.05.16 RCHJ Add =============
    var send_date_index = 0;

    // delete send date box
    function deleteSendDate(){
        if(send_date_index == 0){
            return;
        }
        $('#search_txt_send_date'+send_date_index).val("");
        $('#search_txt_send_date'+send_date_index).remove();
        send_date_index --;
        $('#search_txt_send_date'+send_date_index).val("");

        $('#search_send_date_index').val(send_date_index);
    }

    // add send date box
    function processSendDate(){
        if(this.id != "search_txt_send_date"+send_date_index){
            return;
        }

        processSendDateCommon("add");
    }
    function processSendDateCommon(mode){
        send_date_index ++;

        var obj_value = '';
        if(mode!='add' && send_date_value && send_date_value[send_date_index]){
            obj_value = send_date_value[send_date_index];
        }

        var obj_add = '<input type="text" name="search_txt_send_date'+send_date_index+'" id="search_txt_send_date'+send_date_index+'" value="'+obj_value+'" class="box9" readonly="readonly">';
        $('#div_send_date').append(obj_add);

        $('#search_txt_send_date'+send_date_index).datepicker({
            onSelect: processSendDate
        });

        $('#search_send_date_index').val(send_date_index);
    }

    $(function() {
        var index = $('#search_send_date_index').val();
        $('#search_txt_send_date0').datepicker({
            onSelect: processSendDate
        });
        for(var i=1; i<=index;i++){
            processSendDateCommon();
        }
    });
    // ==================== end ============
    //-->
</script>
<div id="contents" class="clearfix">
 <div id="mail" class="contents-main">
<form name="search_form" id="search_form" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="search">
    <input type="hidden" name="search_send_date_index" id="search_send_date_index" value="<!--{$arrHidden.search_send_date_index|default:0}-->"><!-- 2012.05.16 RCHJ Add -->
	<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<!--検索条件設定テーブルここから-->
<table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td bgcolor="#f2f1ec" width="110">注文番号</td>
        <td bgcolor="#ffffff" width="194">
        <!--{assign var=key1 value="search_order_id1"}-->
        <!--{assign var=key2 value="search_order_id2"}-->
            <span class="red12"><!--{$arrErr[$key1]}--></span>
            <span class="red12"><!--{$arrErr[$key2]}--></span>
            <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|escape}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"    size="6" class="box6" />
            〜
            <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|escape}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"    size="6" class="box6" />
        </td>
        <td bgcolor="#f2f1ec" width="110">対応状況</td>
        <td bgcolor="#ffffff" width="195">
        <!--{assign var=key value="search_order_status"}-->
            <span class="red12"><!--{$arrErr[$key]}--></span>
            <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <option value="">選択してください</option>
            <!--{html_options options=$arrORDERSTATUS selected=$arrForm[$key].value}-->
            </select>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f2f1ec" width="110">期間</td>
        <td bgcolor="#ffffff" width="499" colspan="3">
            <span class="red"><!--{$arrErr.search_sorderyear}--></span>
            <span class="red"><!--{$arrErr.search_eorderyear}--></span>
            <select name="search_sorderyear"    style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
                <option value="">----</option>
            <!--{html_options options=$arrRegistYear selected=$arrForm.search_sorderyear.value}-->
            </select>年
            <select name="search_sordermonth" style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
                <option value="">--</option>
            <!--{html_options options=$arrMonth selected=$arrForm.search_sordermonth.value}-->
            </select>月
            <select name="search_sorderday" style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
                <option value="">--</option>
            <!--{html_options options=$arrDay selected=$arrForm.search_sorderday.value}-->
            </select>日〜
            <select name="search_eorderyear" style="<!--{$arrErr.search_eorderyear|sfGetErrorColor}-->">
                <option value="">----</option>
            <!--{html_options options=$arrRegistYear selected=$arrForm.search_eorderyear.value}-->
            </select>年
            <select name="search_eordermonth" style="<!--{$arrErr.search_eorderyear|sfGetErrorColor}-->">
                <option value="">--</option>
            <!--{html_options options=$arrMonth selected=$arrForm.search_eordermonth.value}-->
            </select>月
            <select name="search_eorderday" style="<!--{$arrErr.search_eorderyear|sfGetErrorColor}-->">
                <option value="">--</option>
            <!--{html_options options=$arrDay selected=$arrForm.search_eorderday.value}-->
            </select>日
        </td>
    </tr>
    <!-- 2012.05.16 RCHJ Add -->
    <tr>
        <td bgcolor="#f2f1ec" width="110">発送日</td>
        <td bgcolor="#ffffff" width="499" colspan="3">
            <div id="div_send_date" style="display: inline;"><input type="text" name="search_txt_send_date0" id="search_txt_send_date0" value="<!--{$arrForm.search_txt_send_date0.value}-->" class="box9" readonly="readonly"></div>
	    <a class="btn-normal" name="btn_del_date" onclick="deleteSendDate()" href="javascript:;"> 削除 </a>
        </td>
    </tr>
    <!-- End -->
    <tr>
        <td bgcolor="#f2f1ec" width="110">集計項目</td>
        <td bgcolor="#ffffff" width="499" colspan="3">
        <!--{assign var=key1 value="search_order_total1"}-->
        <!--{assign var=key2 value="search_order_total2"}-->
        <!--{assign var=key3 value="search_order_total3"}-->
        <!--{assign var=key4 value="search_order_total4"}-->
            <span class="red12"><!--{$arrErr[$key1]}--></span>
            <span class="red12"><!--{$arrErr[$key2]}--></span>
            <span class="red12"><!--{$arrErr[$key3]}--></span>
            <span class="red12"><!--{$arrErr[$key4]}--></span>
            <input type="checkbox" value="1" name="<!--{$key1}-->"<!--{if $arrForm[$key1].value =='1'}--> checked="checked" <!--{/if}-->/>注文回数
            <input type="checkbox" value="1" name="<!--{$key2}-->"<!--{if $arrForm[$key2].value =='1'}--> checked="checked" <!--{/if}-->/>年代
            <input type="checkbox" value="1" name="<!--{$key3}-->"<!--{if $arrForm[$key3].value =='1'}--> checked="checked" <!--{/if}-->/>商品カテゴリ
            <input type="checkbox" value="1" name="<!--{$key4}-->"<!--{if $arrForm[$key4].value =='1'}--> checked="checked" <!--{/if}-->/>都道府県
    </tr>
</table>
<div class="btn-area">
  <ul>
     <li><a onclick="eccube.fnFormModeSubmit('search_form', 'search', '', ''); return false;" class="btn-action" href="javascript:;"><span class="btn-next">この条件で検索する</span></a></li>
  </ul>
</div>

<!--検索条件設定テーブルここまで-->
</td>
</tr>
</table>
</td>
</tr>
<!--メインエリア-->
</table>
</td>
</tr>
</form>

<!--★★メインコンテンツ★★-->

<!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete') }-->

<!--★★検索結果一覧★★-->
<table width="878" border="0" cellspacing="0" cellpadding="0" summary=" ">
    <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
        <input type="hidden" name="mode" value="search">
        <input type="hidden" name="order_id" value="">
	<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->"/>
        <!--{foreach key=key item=item from=$arrHidden}-->
            <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
        <!--{/foreach}-->
        <tr bgcolor="cbcbcb">
            <td align="right" colspan="2" style="text-align:right">
                <img src="<!--{$TPL_DIR}-->img/common/_.gif" width="8" height="22" alt="">
                <a href="#" onmouseover="chgImg('<!--{$TPL_DIR}-->img/contents/btn_pdf_on.jpg','btn_pdf');" onmouseout="chgImg('<!--{$TPL_DIR}-->img/contents/btn_pdf.jpg','btn_pdf');"  onclick="fnOpenPdfSettingPage('pdf.php'); return false;" ><img src="<!--{$TPL_DIR}-->img/contents/btn_pdf.jpg" width="99" height="22" alt="PDF DOWNLOAD" border="0" name="btn_pdf" id="btn_pdf"></a>
                <img src="<!--{$TPL_DIR}-->img/common/_.gif" width="8" height="22" alt="">
            </td>
        </tr>
</table>

                        <!--検索結果表示テーブル-->
                        <table width="840" border="0" cellspacing="1" cellpadding="5" summary=" " class="list">
                            <tr>
                                <th>&nbsp;</th>
                                <th>総受注件数</th>
                                <th>&nbsp;</th>
                                <th>総受注合計金額</th>
                                <th>&nbsp;</th>
                                <th>平均単価</th>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="right">&nbsp;</td>
                                <td class="right"><!--{$arrResults.total_cnt|number_format}-->&nbsp;件</td>
                                <td class="right">&nbsp;</td>
                                <td class="right"><!--{$arrResults.total_money|number_format}-->&nbsp;円</td>
                                <td class="right">&nbsp;</td>
                                <td class="right"><!--{$arrResults.total_money/$arrResults.total_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                <!--{if count($arrOrderResults) > 0}-->
                            <!--{*注文回数*}-->
                            <tr bgcolor="#FFEBDE">
                                <td class="center">注文回数</td>
                                <td class="center">受注件数</td>
                                <td class="center">受注全体に占める割合</td>
                                <td class="center">受注合計金額</td>
                                <td class="center">受注全体に占める割合</td>
                                <td class="center">平均単価</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#FFEBDE">新規</td>
                                <td class="right"><!--{$arrOrderResults1.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrOrderResults1.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults1.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrOrderResults1.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults1.sub_money/$arrOrderResults1.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#FFEBDE">リピート</td>
                                <td class="right"><!--{$arrOrderResultsCus.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrOrderResultsCus.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResultsCus.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrOrderResultsCus.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResultsCus.sub_money/$arrOrderResultsCus.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#FFF7EF">　▼2回目</td>
                                <td class="right"><!--{$arrOrderResults2.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrOrderResults2.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults2.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrOrderResults2.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults2.sub_money/$arrOrderResults2.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr  bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#FFF7EF">　▼3回目</td>
                                <td class="right"><!--{$arrOrderResults3.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrOrderResults3.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults3.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrOrderResults3.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults3.sub_money/$arrOrderResults3.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#FFF7EF">　▼4回目</td>
                                <td class="right"><!--{$arrOrderResults4.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrOrderResults4.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults4.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrOrderResults4.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults4.sub_money/$arrOrderResults4.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#FFF7EF">　▼5回目以降</td>
                                <td class="right"><!--{$arrOrderResults5.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrOrderResults5.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults5.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrOrderResults5.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrOrderResults5.sub_money/$arrOrderResults5.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                <!--{/if}-->
                <!--{if count($arrAgeResults) > 0}-->
                            <!--{*年代*}-->
                            <tr bgcolor="#B5DFEF">
                                <td class="center">年代</td>
                                <td class="center">受注件数</td>
                                <td class="center">受注全体に占める割合</td>
                                <td class="center">受注合計金額</td>
                                <td class="center">受注全体に占める割合</td>
                                <td class="center">平均単価</td>
                            </tr>
                            <tr  bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">不明</td>
                                <td class="right"><!--{$arrAgeResults0.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults0.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults0.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults0.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults0.sub_money/$arrAgeResults0.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>                
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">10代</td>
                                <td class="right"><!--{$arrAgeResults1.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults1.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults1.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults1.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults1.sub_money/$arrAgeResults1.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">20代前半</td>
                                <td class="right"><!--{$arrAgeResults2.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults2.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults2.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults2.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults2.sub_money/$arrAgeResults2.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">20代後半</td>
                                <td class="right"><!--{$arrAgeResults2a.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults2a.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults2a.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults2a.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults2a.sub_money/$arrAgeResults2a.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr  bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">30代前半</td>
                                <td class="right"><!--{$arrAgeResults3.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults3.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults3.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults3.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults3.sub_money/$arrAgeResults3.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">30代後半</td>
                                <td class="right"><!--{$arrAgeResults3a.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults3a.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults3a.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults3a.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults3a.sub_money/$arrAgeResults3a.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">40代前半</td>
                                <td class="right"><!--{$arrAgeResults4.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults4.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults4.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults4.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults4.sub_money/$arrAgeResults4.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">40代後半</td>
                                <td class="right"><!--{$arrAgeResults4a.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults4a.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults4a.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults4a.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults4a.sub_money/$arrAgeResults4a.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#B5DFEF">50代～</td>
                                <td class="right"><!--{$arrAgeResults5.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrAgeResults5.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults5.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrAgeResults5.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrAgeResults5.sub_money/$arrAgeResults5.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                <!--{/if}-->
                <!--{if count($arrCatResults) > 0}-->
                            <!--{*商品カテゴリ*}-->
                            <tr bgcolor="#D6E3BD">
                                <td class="center">商品カテゴリ</td>
                                <td class="center">受注件数</td>
                                <td class="center">受注全体に占める割合</td>
                                <td class="center">各商品数に占める割合</td>
                                <td class="center">受注合計金額</td>
                                <td class="center">受注全体に占める割合</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#D6E3BD">ワンピース</td>
                                <td class="right"><!--{$arrCatResults1.p_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrCatResults1.p_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults1.p_cnt*100/$allProductCnt[1]|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults1.money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrCatResults1.money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <!--{assign var="cnt" value="`$arrCatResults1.o_cnt`"}-->
                                <!--{*<td class="right"><!--{$arrCatResults1.money/$cnt|number_format}-->&nbsp;円</td>*}-->
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#D6E3BD">ドレス</td>
                                <td class="right"><!--{$arrCatResults2.p_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrCatResults2.p_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults2.p_cnt*100/$allProductCnt[2]|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults2.money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrCatResults2.money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <!--{assign var="cnt" value="`$arrCatResults2.o_cnt`"}-->
                                <!--{*<td class="right"><!--{$arrCatResults2.money/$cnt|number_format}-->&nbsp;円</td>*}-->
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#D6E3BD">ドレス3点セット</td>
                                <td class="right"><!--{$arrCatResults3.p_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrCatResults3.p_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults3.p_cnt*100/$allProductCnt[3]|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults3.money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrCatResults3.money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <!--{assign var="cnt" value="`$arrCatResults3.o_cnt`"}-->
                                <!--{*<td class="right"><!--{$arrCatResults3.money/$cnt|number_format}-->&nbsp;円</td>*}-->
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#D6E3BD">ドレス4点セット</td>
                                <td class="right"><!--{$arrCatResults4.p_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrCatResults4.p_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults4.p_cnt*100/$allProductCnt[4]|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults4.money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrCatResults4.money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <!--{assign var="cnt" value="`$arrCatResults4.o_cnt`"}-->
                                <!--{*<td class="right"><!--{$arrCatResults4.money/$cnt|number_format}-->&nbsp;円</td>*}-->
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#D6E3BD">羽織物</td>
                                <td class="right"><!--{$arrCatResults5.p_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrCatResults5.p_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults5.p_cnt*100/$allProductCnt[5]|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults5.money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrCatResults5.money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <!--{assign var="cnt" value="`$arrCatResults5.o_cnt`"}-->
                                <!--{*<td class="right"><!--{$arrCatResults5.money/$cnt|number_format}-->&nbsp;円</td>*}-->
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#D6E3BD">ネックレス</td>
                                <td class="right"><!--{$arrCatResults6.p_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrCatResults6.p_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults6.p_cnt*100/$allProductCnt[6]|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults6.money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrCatResults6.money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <!--{assign var="cnt" value="`$arrCatResults6.o_cnt`"}-->
                                <!--{*<td class="right"><!--{$arrCatResults6.money/$cnt|number_format}-->&nbsp;円</td>*}-->
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#D6E3BD">その他小物</td>
                                <td class="right"><!--{$arrCatResults7.p_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrCatResults7.p_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults7.p_cnt*100/$allProductCnt[7]|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrCatResults7.money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrCatResults7.money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <!--{assign var="cnt" value="`$arrCatResults7.o_cnt`"}-->
                                <!--{*<td class="right"><!--{$arrCatResults7.money/$cnt|number_format}-->&nbsp;円</td>*}-->
                            </tr>
                <!--{/if}-->
                <!--{if count($arrPrefResults) > 0}-->
                            <!--{*都道府県*}-->
                            <tr bgcolor="#E7BAB5">
                                <td class="center">都道府県</td>
                                <td class="center">受注件数</td>
                                <td class="center">受注全体に占める割合</td>
                                <td class="center">受注合計金額</td>
                                <td class="center">受注全体に占める割合</td>
                                <td class="center">平均単価</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#E7BAB5">北海道</td>
                                <td class="right"><!--{$arrPrefResults1.sub_cnt|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrPrefResults1.sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefResults1.sub_money|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrPrefResults1.sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefResults1.sub_money/$arrPrefResults1.sub_cnt|number_format}-->&nbsp;円</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#E7BAB5">東北地方</td>
                                <td class="right"><!--{$arrPrefSumResults[1][0]|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrPrefSumResults[1][0]*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][0]|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrPrefSumResults[0][0]*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][0]/$arrPrefSumResults[1][0]|number_format}-->&nbsp;円</td>
                            </tr>
                    <!--{section name=bar loop=8 start=2 step=1}-->
                            <tr  bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#F7DBDE">　▼<!--{$arrPrefResult[$smarty.section.bar.index].name|escape}-->
                                    <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;件</td>
                                    <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                    <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money|number_format}-->&nbsp;円</td>
                                    <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                    <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money/$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;円</td>
                                </td>
                            </tr>
                    <!--{/section}-->
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#E7BAB5">関東地方</td>
                                <!--{assign var=key value=1}-->
                                <td class="right"><!--{$arrPrefSumResults[1][$key]|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrPrefSumResults[1][$key]*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]/$arrPrefSumResults[1][$key]|number_format}-->&nbsp;円</td>
                            </tr>
                    <!--{section name=bar loop=15 start=8 step=1}-->
                    <tr bgcolor="#FFFFFF">
                        <td class="left" bgcolor="#F7DBDE">　▼<!--{$arrPrefResult[$smarty.section.bar.index].name|escape}-->
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;件</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money|number_format}-->&nbsp;円</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money/$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;円</td>
                        </td>
                    </tr>
                    <!--{/section}-->
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#E7BAB5">中部地方</td>
                                <!--{assign var=key value=2}-->
                                <td class="right"><!--{$arrPrefSumResults[1][$key]|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrPrefSumResults[1][$key]*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]/$arrPrefSumResults[1][$key]|number_format}-->&nbsp;円</td>
                            </tr>
                    <!--{section name=bar loop=24 start=15 step=1}-->
                    <tr bgcolor="#FFFFFF">
                        <td class="left" bgcolor="#F7DBDE">　▼<!--{$arrPrefResult[$smarty.section.bar.index].name|escape}-->
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;件</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money|number_format}-->&nbsp;円</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money/$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;円</td>
                        </td>
                    </tr>
                    <!--{/section}-->
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#E7BAB5">近畿地方</td>
                                <!--{assign var=key value=3}-->
                                <td class="right"><!--{$arrPrefSumResults[1][$key]|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrPrefSumResults[1][$key]*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]/$arrPrefSumResults[1][$key]|number_format}-->&nbsp;円</td>
                            </tr>
                    <!--{section name=bar loop=31 start=24 step=1}-->
                    <tr bgcolor="#FFFFFF">
                        <td class="left" bgcolor="#F7DBDE">　▼<!--{$arrPrefResult[$smarty.section.bar.index].name|escape}-->
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;件</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money|number_format}-->&nbsp;円</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money/$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;円</td>
                        </td>
                    </tr>
                    <!--{/section}-->
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#E7BAB5">中国地方</td>
                                <!--{assign var=key value=4}-->
                                <td class="right"><!--{$arrPrefSumResults[1][$key]|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrPrefSumResults[1][$key]*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]/$arrPrefSumResults[1][$key]|number_format}-->&nbsp;円</td>
                            </tr>
                    <!--{section name=bar loop=36 start=31 step=1}-->
                    <tr bgcolor="#FFFFFF">
                        <td class="left" bgcolor="#F7DBDE">　▼<!--{$arrPrefResult[$smarty.section.bar.index].name|escape}-->
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;件</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money|number_format}-->&nbsp;円</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money/$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;円</td>
                        </td>
                    </tr>
                    <!--{/section}-->
                            <tr bgcolor="#FFFFFF">
                                <td class="left" bgcolor="#E7BAB5">四国地方</td>
                                <!--{assign var=key value=5}-->
                                <td class="right"><!--{$arrPrefSumResults[1][$key]|number_format}-->&nbsp;件</td>
                                <td class="right"><!--{$arrPrefSumResults[1][$key]*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]|number_format}-->&nbsp;円</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                                <td class="right"><!--{$arrPrefSumResults[0][$key]/$arrPrefSumResults[1][$key]|number_format}-->&nbsp;円</td>
                            </tr>
                    <!--{section name=bar loop=40 start=36 step=1}-->
                    <tr bgcolor="#FFFFFF">
                        <td class="left" bgcolor="#F7DBDE">　▼<!--{$arrPrefResult[$smarty.section.bar.index].name|escape}-->
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;件</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money|number_format}-->&nbsp;円</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money/$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;円</td>
                        </td>
                    </tr>
                    <!--{/section}-->
                        <tr bgcolor="#FFFFFF">
                            <td class="left" bgcolor="#E7BAB5">九州地方</td>
                            <!--{assign var=key value=6}-->
                            <td class="right"><!--{$arrPrefSumResults[1][$key]|number_format}-->&nbsp;件</td>
                            <td class="right"><!--{$arrPrefSumResults[1][$key]*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                            <td class="right"><!--{$arrPrefSumResults[0][$key]|number_format}-->&nbsp;円</td>
                            <td class="right"><!--{$arrPrefSumResults[0][$key]*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                            <td class="right"><!--{$arrPrefSumResults[0][$key]/$arrPrefSumResults[1][$key]|number_format}-->&nbsp;円</td>
                        </tr>
                    <!--{section name=bar loop=48 start=40 step=1}-->
                    <tr bgcolor="#FFFFFF">
                        <td class="left" bgcolor="#F7DBDE">　▼<!--{$arrPrefResult[$smarty.section.bar.index].name|escape}-->
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;件</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_cnt*100/$arrResults.total_cnt|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money|number_format}-->&nbsp;円</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money*100/$arrResults.total_money|number_format:2}-->&nbsp;％</td>
                        <td class="right"><!--{$arrPrefResult[$smarty.section.bar.index].sub_money/$arrPrefResult[$smarty.section.bar.index].sub_cnt|number_format}-->&nbsp;円</td>
                        </td>
                    </tr>
                    <!--{/section}-->
                <!--{/if}-->
                        </table>
                        <!--検索結果表示テーブル-->
</div></div>
</table>

</form>
<!--★★検索結果一覧★★-->

<!--{/if}-->
