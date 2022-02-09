<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<script type="text/javascript">
<!--
    function fnSelectCheckSubmit(action) {

        var fm = document.form1;

        if (!fm["pdf_order_id[]"]) {
            return false;
        }

        var checkflag = false;
        var max = fm["pdf_order_id[]"].length;

        if (max) {
            for (var i = 0; i < max; i++) {
                if (fm["pdf_order_id[]"][i].checked == true) {
                    checkflag = true;
                }
            }
        } else {
            if (fm["pdf_order_id[]"].checked == true) {
                checkflag = true;
            }
        }

        if (!checkflag) {
            alert('チェックボックスが選択されていません');
            return false;
        }

        fnOpenPdfSettingPage(action);
    }

    function fnOpenPdfSettingPage(action) {
        var fm = document.form1;
        win02("about:blank", "pdf_input", "620", "650");

        // 退避
        tmpTarget = fm.target;
        tmpMode = fm.mode.value;
        tmpAction = fm.action;

        fm.target = "pdf_input";
        fm.mode.value = 'pdf';
        fm.action = action;
        fm.submit();

        // 復元
        fm.target = tmpTarget;
        fm.mode.value = tmpMode;
        fm.action = tmpAction;
    }

    function fnSelectMailCheckSubmit(action) {

        var fm = document.form1;

        if (!fm["mail_order_id[]"]) {
            return false;
        }

        var checkflag = false;
        var max = fm["mail_order_id[]"].length;

        if (max) {
            for (var i = 0; i < max; i++) {
                if (fm["mail_order_id[]"][i].checked == true) {
                    checkflag = true;
                }
            }
        } else {
            if (fm["mail_order_id[]"].checked == true) {
                checkflag = true;
            }
        }

        if (!checkflag) {
            alert('チェックボックスが選択されていません');
            return false;
        }

        fm.mode.value="mail_select";
        fm.action=action;
        fm.submit();
    }
//-->
</script>

<div id="order" class="contents-main">
<form name="search_form" id="search_form" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="search" />

    <h2>検索条件設定</h2>

    <!--{* 検索条件設定テーブルここから *}-->
    <table>
        <tr>
            <th>注文番号</th>
            <td>
                <!--{assign var=key1 value="search_order_id1"}-->
                <!--{assign var=key2 value="search_order_id2"}-->
                <span class="attention"><!--{$arrErr[$key1]}--></span>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" />
                ～
                <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" />
            </td>
            <th>定期購買の状態</th>
            <td>
                <!--{assign var=key value="search_ac_status"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrACStatus selected=$arrForm[$key].value}-->
                </select>
            </td>
        </tr>
        <tr>
            <th>会員番号</th>
            <td>
                <!--{assign var=key value="search_customer_id"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="6" class="box30" />
            </td>
            <th>メンバーID（ルミーズ発番のID）</th>
            <td>
                <!--{assign var=key value="search_member_id"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="6" class="box30" />
            </td>
        </tr>
        <tr>
            <th>お名前</th>
            <td>
                <!--{assign var=key value="search_order_name"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
            </td>
            <th>お名前(フリガナ)</th>
            <td>
                <!--{assign var=key value="search_order_kana"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
            </td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>
                <!--{assign var=key value="search_order_email"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
            </td>
            <th>TEL</th>
            <td>
                <!--{assign var=key value="search_order_tel"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
            </td>
        </tr>
        <tr>
            <th>購入商品</th>
            <td>
                <!--{assign var=key value="search_product_name"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="6" class="box30" />
            </td>
            <th>購入商品コード</th>
            <td>
                <!--{assign var=key value="search_product_code"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="6" class="box30" />
            </td>
        </tr>
        <tr>
            <th>受注日</th>
            <td colspan="3">
                <!--{if $arrErr.search_sorderyear}--><span class="attention"><!--{$arrErr.search_sorderyear}--></span><!--{/if}-->
                <!--{if $arrErr.search_eorderyear}--><span class="attention"><!--{$arrErr.search_eorderyear}--></span><!--{/if}-->
                <select name="search_sorderyear" style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
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
                </select>日～
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
        <tr>
            <th>次回課金日</th>
            <td colspan="3">
                <!--{if $arrErr.search_snextyear}--><span class="attention"><!--{$arrErr.search_snextyear}--></span><!--{/if}-->
                <!--{if $arrErr.search_enextyear}--><span class="attention"><!--{$arrErr.search_enextyear}--></span><!--{/if}-->
                <select name="search_snextyear" style="<!--{$arrErr.search_snextyear|sfGetErrorColor}-->">
                    <option value="">----</option>
                    <!--{html_options options=$arrRegistYear selected=$arrForm.search_snextyear.value}-->
                </select>年
                <select name="search_snextmonth" style="<!--{$arrErr.search_snextyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrMonth selected=$arrForm.search_snextmonth.value}-->
                </select>月
                <select name="search_snextday" style="<!--{$arrErr.search_snextyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrDay selected=$arrForm.search_snextday.value}-->
                </select>日～
                <select name="search_enextyear" style="<!--{$arrErr.search_enextyear|sfGetErrorColor}-->">
                    <option value="">----</option>
                    <!--{html_options options=$arrRegistYear selected=$arrForm.search_enextyear.value}-->
                </select>年
                <select name="search_enextmonth" style="<!--{$arrErr.search_enextyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrMonth selected=$arrForm.search_enextmonth.value}-->
                </select>月
                <select name="search_enextday" style="<!--{$arrErr.search_enextyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrDay selected=$arrForm.search_enextday.value}-->
                </select>日
            </td>
        </tr>
    </table>

    <div class="btn">
        <p class="page_rows">検索結果表示件数
        <!--{assign var=key value="search_page_max"}-->
        <span class="attention"><!--{$arrErr[$key]}--></span>
        <select name="<!--{$arrForm[$key].keyname}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
            <!--{html_options options=$arrPageMax selected=$arrForm[$key].value}-->
        </select> 件</p>
        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('search_form', 'search', '', ''); return false;"><span class="btn-next">この条件で検索する</span></a></li>
            </ul>
        </div>
    </div>
    <!--検索条件設定テーブルここまで-->
</form>

<!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete' or $smarty.post.mode == 'econ_subs_neworder' or $smarty.post.mode == 'econ_subs_commit' or $smarty.post.mode == 'econ_subs_arai')}-->
<!--★★検索結果一覧★★-->
<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="search" />
    <input type="hidden" name="order_id" value="" />

    <!--{foreach key=key item=item from=$arrHidden}-->
        <!--{if is_array($item)}-->
            <!--{foreach item=c_item from=$item}-->
                <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
            <!--{/foreach}-->
        <!--{else}-->
            <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
        <!--{/if}-->
    <!--{/foreach}-->

    <h2>検索結果一覧</h2>
    <div class="btn">
        <span class="attention"><!--検索結果数--><!--{$tpl_linemax}-->件</span>&nbsp;が該当しました。

        <a class="btn-normal" href="javascript:;" onclick="fnModeSubmit('csv','',''); return false;"><span>CSVダウンロード</span></a>
        <a class="btn-normal" href="javascript:;" onclick="fnSelectCheckSubmit('pdf_ac.php'); return false;"><span>PDF一括出力</span></a>
        <a class="btn-normal" href="javascript:;" onclick="fnSelectMailCheckSubmit('mail_ac.php'); return false;"><span>メール一括通知</span></a>
    </div>

    <!--{if count($arrResults) > 0}-->
        <!--{include file=$tpl_pager}-->

        <!--{* 検索結果表示テーブル *}-->
        <table class="list">
            <!--{* remise決済モジュール用 *}-->
            <colgroup width="11%">
            <colgroup width="7%">
            <colgroup width="10%">
            <colgroup width="10%">
            <colgroup width="8%">
            <colgroup width="11%">
            <colgroup width="8%">
            <colgroup width="7%">
            <colgroup width="9%">
            <colgroup width="9%">
            <colgroup width="5%">
            <colgroup width="5%">
            <tr>
                <th>登録日</th>
                <th>会員番号</th>
                <th>顧客名</th>
                <th>商品名</th>
                <th>定期購買<br />金額(円)</th>
                <th>定期購買<br />次回課金日</th>
                <th>定期購買<br />課金間隔</th>
                <th>定期購買<br />状態</th>
                <th>
                    <label for="pdf_check">帳票</label>
                    <input type="checkbox" name="pdf_check" id="pdf_check" onclick="fnAllCheck(this, 'input[name=\'pdf_order_id[]\']')" />
                </th>
                <th>メール <input type="checkbox" name="mail_check" id="mail_check" onclick="fnAllCheck(this, 'input[name=\'mail_order_id[]\']')" /></th>
                <th>編集</th>
                <th>削除</th>
            </tr>

            <!--{section name=cnt loop=$arrResults}-->
            <!--{assign var=status value="`$arrResults[cnt].status`"}-->
            <tr style="background:<!--{$arrORDERSTATUS_COLOR[$status]}-->;">
                <td class="center"><!--{$arrResults[cnt].create_date|sfDispDBDate}--></td>
                <td class="center"><!--{$arrResults[cnt].customer_id}--></td>
                <td class="center"><!--{$arrResults[cnt].order_name01|h}--> <!--{$arrResults[cnt].order_name02|h}--></td>
                <td class="center"><!--{$arrResults[cnt].name}--></td>
                <td class="right"><!--{$arrResults[cnt].plg_remiseautocharge_total|number_format}--></td>
                <td class="right"><!--{$arrResults[cnt].plg_remiseautocharge_next_date}--></td>
                <td class="right"><!--{$arrResults[cnt].plg_remiseautocharge_interval}-->ヶ月毎</td>
                <td class="center"><!--{$arrResults[cnt].status}--></td>
                <td class="center">
                    <input type="checkbox" name="pdf_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="pdf_order_id_<!--{$arrResults[cnt].order_id}-->"/><label for="pdf_order_id_<!--{$arrResults[cnt].order_id}-->">一括出力</label><br>
                    <a href="./" onClick="win02('pdf_ac.php?order_id=<!--{$arrResults[cnt].order_id}-->','pdf_input','620','650'); return false;"><span class="icon_class">個別出力</span></a>
                </td>
                <td class="center">
                    <!--{if $arrResults[cnt].order_email|strlen >= 1}-->
                        <input type="checkbox" name="mail_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="mail_order_id_<!--{$arrResults[cnt].order_id}-->"/><label for="mail_order_id_<!--{$arrResults[cnt].order_id}-->">一括通知</label><br>
                        <a href="?" onclick="fnChangeAction('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/mail_ac.php'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrResults[cnt].order_id}-->'); return false;"><span class="icon_mail">個別通知</span></a>
                    <!--{/if}-->
                </td>
                <td class="center"><a href="?" onclick="fnChangeAction('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/remise_ac_order_edit.php'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrResults[cnt].order_id}-->'); return false;"><span class="icon_edit">編集</span></a></td>

                <td class="center">
                    <!--{if $smarty.const.ECCUBE_VERSION < '2.13.0'}-->
                        <a href="?" onclick="fnModeSubmit('delete_order', 'order_id', <!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_delete">削除</span></a>
                    <!--{else}-->
                        <a href="?" onclick="eccube.setModeAndSubmit('delete', 'order_id',<!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_delete">削除</span></a>
                    <!--{/if}-->
                </td>
            </tr>
            <!--{/section}-->
        </table>
        <!--{* 検索結果表示テーブル *}-->
    <!--{/if}-->
</form>
<!--{/if}-->
</div>
