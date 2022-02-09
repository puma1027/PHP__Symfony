<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<style type="text/css">
    table.comment{
        margin: 0 0 0px;
        width: 100%;
    }
    table.comment td {
        border: 0px;
        vertical-align: top;
        padding: 1px;
    }
    p.contents_fold {word-break: break-all;}
</style>
<script type="text/javascript">
<!--
    self.moveTo(20,20);self.focus();
    self.resizeTo(640,800);

    function lfnCheckConnect() {
        var connect_type = document.form1.connect_type;

        list1 = new Array('credit_url', 'mobile_credit_url', 'direct', 'payquick','convenience_url', 'mobile_convenience_url', 'securitycode', 'remise_tk_flg');
        list2 = new Array('gateway_credit_url', 'token_appid', 'token_password', 'token_sdk', 'gateway_convenience_url', 'credit_method[]', 'use_cardbrand[]');

        for (i = 0; i < connect_type.length; i++) {
            if (connect_type[i].checked && connect_type[i].value == '1') {
                fnChangeDisabled(list1, false);
                fnChangeDisabled(list2);
            } else if (connect_type[i].checked && connect_type[i].value == '2') {
                fnChangeDisabled(list1);
                fnChangeDisabled(list2, false);
            }
        }
        lfnCheckDirect();
    }

    function lfnCheckDirect() {
        var direct = document.form1.direct;

        var list = new Array('securitycode', 'use_cardbrand[]', 'cvs[]');

        if ( direct[0].checked && !direct[0].disabled ) {
            fnChangeDisabled(list, true);
        } else {
            fnChangeDisabled(list, false);
        }
        lfnCheckPaquick();
    }

    function lfnCheckPaquick() {
        var direct = document.form1.direct
        var payquick = document.form1.payquick;

        var list = new Array('credit_method[]');

        if ( direct[0].checked && !direct[0].disabled && payquick[1].checked && !payquick[1].disabled ) {
            fnChangeDisabled(list, true);
        } else {
            fnChangeDisabled(list, false);
        }
    }

    function fnChangeDisabled(list, disable) {
        len = list.length;

        if (disable == null) { disable = true; }

        for (i = 0; i < len; i++) {
            if (document.form1[list[i]]) {
                // ラジオボタン、チェックボックス等の配列に対応
                max = document.form1[list[i]].length
                if (max > 1) {
                    for (j = 0; j < max; j++) {
                        // 有効、無効の切り替え
                        document.form1[list[i]][j].disabled = disable;
                    }
                } else {
                    // 有効、無効の切り替え
                    document.form1[list[i]].disabled = disable;
                }
            }
        }
    }

    function win_open(URL) {
        var WIN;
        WIN = window.open(URL);
        WIN.focus();
    }
//-->
</script>

<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
    <input type="hidden" name="mode" value="edit" />
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

    <h2><!--コンテンツタイトル--><!--{$tpl_subtitle}--></h2>
    ルミーズ決済モジュールをダウンロード頂きありがとうございます。<br/>
    <table>
        <colgroup width="30%">
        <colgroup width="70%">
        <tr>
            <th colspan="2"><strong>■本モジュールについて</strong></th>
        </tr>
        <tr>
            <th>お知らせ</th>
            <td>
                ルミーズ決済モジュールをご利用頂く為には、ユーザ様ご自身で
                ルミーズ株式会社とご契約を行っていただく必要があります。
                お申し込みにつきましては、下記のページから、お申し込みを行ってください。<br/>
                <a href="#" onClick="win_open('http://www.remise.jp/')" > ルミーズ株式会社オフィシャルサイトはこちら→</a><br/>
            </td>
        </tr>
        <tr>
            <th>サポート</th>
            <td>
                「<span class="red">（オプション）</span>」の記載のある項目につきましては、<b>ご利用の際にオプションお申込みが必要</b>となります。<br/>
                <span class="red"><b>
                <u>ご利用に必要なオプションをお申込みいただかずに設定をされた場合、正常動作致しません。</u>
                </b></span><br /><br/>
                また、「カード情報入力」の「ローカル」設定及び「トークン式」ご利用の際には、<b>サイトにＳＳＬ通信が必須となります。</b><br /><br/>
                本モジュールの設定に関するお問い合わせにつきましては、<b>ルミーズテクニカルデスク(tech@remise.jp)</b>までご連絡ください。
                なお、お問い合わせの際には下記バージョン番号をお伝えください。
            </td>
        </tr>
        <tr>
            <th>マニュアル</th>
            <td>
                <a href="#" onClick="win_open('http://www.remise.jp/data/ec-cube/')" >設定マニュアルはこちら→</a>
            </td>
        </tr>
        <tr>
            <th>バージョン番号</th>
            <td>
                <!--{$smarty.const.MDL_REMISE_VERSION}-->
            </td>
        </tr>
        <tr>
            <th colspan="2"><strong>■基本設定</strong></th>
        </tr>
        <tr>
            <th>加盟店コード</th>
            <td>
                <!--{assign var=key value="code"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box10" maxlength="<!--{$smarty.const.INT_LEN}-->">
                <span class="attention">※必須入力</span><br /><br />「設定情報連絡票」に記載の「加盟店コード」を設定してください。
            </td>
        </tr>
        <tr>
            <th>ホスト番号</th>
            <td>
                <!--{assign var=key value="host_id"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box10" maxlength="<!--{$smarty.const.INT_LEN}-->">
                <span class="attention">※必須入力</span><br /><br />ルミーズ加盟店バックヤードシステムにて初期設定後、発番されるホスト番号を入力してください。
            </td>
        </tr>
        <tr>
            <th>接続形態</th>
            <td>
                <!--{assign var=key value="connect_type"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{html_radios name="$key" options=$arrConnect selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor onclick="lfnCheckConnect();"}-->
                <span class="attention">※必須入力</span>
                <br />
                <br />
                <u>通常は、<b>「リンク式」</b>を選択してください。</u>
                <br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>
                            <b>「トークン決済」をお申込みいただいている場合のみ</b>、「トークン式」を設定してください。
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th>結果通知URL/<br />収納情報通知URL</th>
            <td>
                <p><b><!--{$smarty.const.HTTPS_URL}--><!--{$smarty.const.RESULT_RECEIVE_PG}--></b></p><br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>
                            <span class="red"><b>
                            <u>リンク式及び拡張セットをご利用の場合</u>
                            </b></span>、ルミーズ加盟店バックヤードシステムにログインして頂き各種設定‐ホスト設定の<b>
                            <u>「結果通知URL」</u>
                            </b>に設定してください。
                        </td>
                    </tr>
                    <tr>
                        <td>※</td>
                        <td>
                            <span class="red"><b>
                            <u>マルチ決済をお使いの場合</u>
                            </b></span>、ルミーズ加盟店バックヤードシステムのマルチ決済設定-収納情報通知サービスの
                            <b>
                            <u>「収納情報通知URL」</u>
                            </b>にも設定してください。
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th>カード情報入力</th>
            <td>
                <!--{assign var=key value="direct"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{html_radios name="$key" options=$arrDirect selected=$arrForm[$key].value style=$arrErr[$key]|sfGetErrorColor onclick="lfnCheckDirect();"}--><br />
                <br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>
                            「ローカル」設定のご利用には<b>
                            <u>「ダイレクトモード」</u>
                            </b>オプションが必要になります。<br /><span class="red">
                            <b>
                            <u>オプションのお申込みなく「ローカル」設定をされても、正常動作致しません。</u>
                            </b>
                            </span>
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th>管理画面カスタマイズ</th>
            <td>
                <!--{assign var=key value="customize"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input class="radio_btn" type="checkbox" name="<!--{$key}-->" id="<!--{$key}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}-->/>カスタマイズする。(推奨)<br /><br />
                以下のファイルが上書きコピーされます。<br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>
                            カスタマイズを行うことで、<b>受注管理メニューの機能拡張</b>、<b>拡張セットの利用</b>、<b>サンクス画面でのマルチ決済支払い情報表示</b>が可能になります。<br />
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
                <p class="contents_fold">
                <!--{$install_files}--><br />
                <br />
                </p>
            </td>
        </tr>
        <!--{if substr($smarty.const.ECCUBE_VERSION,0,4) != '2.11'}-->
        <!--{if $ACEnable}-->
        <tr>
            <th>定期購買カスタマイズ</th>
            <td>
                <!--{assign var=key value="remise_ac_flg"}-->
                <input class="radio_btn" type="checkbox" name="<!--{$key}-->" id="<!--{$key}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}--> />カスタマイズする。<span class="red">（オプション）</span><br /><br />
                定期購買機能をご利用いただくのに必要なカスタマイズを行うため、下記のファイルの上書きコピーを行います。<br /><br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>
                            定期購買機能ご利用には、<b>「自動継続課金」オプションのご契約が必要</b>です。<br />
                            <span class="red"><b>
                            <u>なお、定期購買機能がご不要の場合には、このカスタマイズを行わないでください。<br />定期購買プラグイン無しにカスタマイズを行われた場合、正常動作致しません。</u>
                            </b></span>
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
                <p class="contents_fold">
                <!--{$ac_install_files}--><br />
                <br />
                </p>
            </td>
        </tr>
        <!--{/if}-->
        <!--{if $TKEnable}-->
        <tr>
            <th>2クリックカスタマイズ</th>
            <td>
                <!--{assign var=key value="remise_tk_flg"}-->
                <input class="radio_btn" type="checkbox" name="<!--{$key}-->" id="<!--{$key}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}--> />カスタマイズする。<br /><br />
                2クリック機能をご利用いただくのに必要なカスタマイズを行うため、下記のファイルの上書きコピーを行います。<br /><br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>
                            2クリック決済機能のご利用には、<b>接続形態を「リンク式」、カード情報保持機能を「利用する」と設定する必要があります。</b><br />
                            <span class="red"><b>
                            <u>※なお、接続形態が「トークン式」の場合、2クリック決済機能はお使いいただけません。</u>
                            </b></span>
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
                <p class="contents_fold">
                <!--{$tk_install_files}-->
                <br />
                <br />
                </p>
            </td>
        </tr>
        <!--{/if}-->
        <!--{/if}-->
        <tr>
            <th colspan="2"><strong>■カード決済設定</strong></th>
        </tr>
        <tr>
            <th>決済情報送信先URL</th>
            <td>
                <!--{assign var=key value="card_url"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                [リンク式]<br />
                <!--{assign var=key value="credit_url"}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box40" maxlength="<!--{$smarty.const.URL_LEN}-->"><br />
                [トークン式]<span class="red">（オプション）</span><br />
                <!--{assign var=key value="gateway_credit_url"}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box40" maxlength="<!--{$smarty.const.URL_LEN}-->"><br />
            </td>
        </tr>
        <!--{* add start 2017/06/29 *}-->
        <tr>
            <th>トークン決済</th>
            <td>
                <!--{assign var=key value="token"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                [APPID]<br />
                <!--{assign var=key value="token_appid"}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box10" maxlength="<!--{$smarty.const.INT_LEN}-->"><br />
                [PASSWORD]<br />
                <!--{assign var=key value="token_password"}-->
                <input type="password" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box10" maxlength="<!--{$smarty.const.INT_LEN}-->"><br />
                [RemiseTokenSDK]<br />
                <!--{assign var=key value="token_sdk"}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box40" maxlength="<!--{$smarty.const.URL_LEN}-->"><br />
                <br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>接続形態が「<b>トークン式</b>」の場合のみ設定が必要です。</td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <!--{* add end 2017/06/29 *}-->
        <tr>
            <th>処理区分</th>
            <td>
                <!--{assign var=key value="job"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{html_radios name="$key" options=$arrPaymentJob selected=$arrForm[$key].value style="$arrErr[$key]|sfGetErrorColor;"}-->
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>
                            AUTH：<b>
                            <u>一旦与信枠を確保しておき、後で実売上処理が必要な</u>
                            </b>設定です。物販にて運営されるショップ様向けです。
                        </td>
                    </tr>
                    <tr>
                        <td>※</td>
                        <td>
                            CAPTURE：<b>
                            <u>ご注文時に売上処理まで行う</u>
                            </b>設定となります。ダウンロード販売を行われるショップ様向けです。
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th>支払方法</th>
            <td>
                <!--{assign var=key value="credit_method"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{html_checkboxes_ex name="$key" options=$arrCredit selected=$arrForm[$key].value style="$arrErr[$key]|sfGetErrorColor;"}--><br /><br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>ダウンロード商品の場合には一括払いのみが表示されます。</td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th>カード情報保持機能<br />（ペイクイック）</th>
            <td>
                <!--{assign var=key value="payquick"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{html_radios name="$key" options=$arrUse selected=$arrForm[$key].value style="$arrErr[$key]|sfGetErrorColor; onclick=lfnCheckPaquick();"}-->
            </td>
        </tr>
        <tr>
            <th>セキュリティコード</th>
            <td>
                <!--{assign var=key value="securitycode"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{html_radios name="$key" options=$arrUse selected=$arrForm[$key].value style="$arrErr[$key]|sfGetErrorColor;"}-->
            </td>
        </tr>
        <tr>
            <th>3Dセキュア</th>
            <td>
                <!--{assign var=key value="3dsecure"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{html_radios name="$key" options=$arrUse selected=$arrForm[$key].value style="$arrErr[$key]|sfGetErrorColor;"}-->
            </td>
        </tr>
        <tr>
            <th>利用カードブランド</th>
            <td>
                <!--{assign var=key value="use_cardbrand[]"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{foreach from=$arrUseCardBrand key=keyname item=val}-->
                <input type="checkbox" name="<!--{$key}-->" value="<!--{$keyname}-->" id="<!--{$keyname}-->" <!--{$arrUseCardBrand[$keyname].chk}-->/><!--{$val.CNAME}--><br />
                <!--{/foreach}--><br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>契約内容に合わせて選択をしてください。なお、選択されたカードブランドはカード情報入力画面に表示されます。</td>
                    </tr>
                      <tr>
                        <td>※</td>
                        <td>
                          カード情報入力が「<b>ローカル</b>」か、接続形態が「<b>トークン式</b>」の場合のみ設定が必要です。
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <strong>■カード決済・拡張セット設定</strong><span class="red">（オプション）</span></br> ※ご利用の際には、拡張セット専用のホスト番号及び決済情報送信先URLの登録が必要です。
            </th>
        </tr>
        <tr>
            <th>
                ホスト番号<br /> [拡張セット]
            </th>
            <td>
                <!--{assign var=key value="extset_host_id"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box10" maxlength="<!--{$smarty.const.INT_LEN}-->">
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>
                            ルミーズ加盟店バックヤードシステムにて、<b>
                            <u>拡張セット専用のホストを作成いただき、そのホスト番号をご登録</u>
                            </b>ください。
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th>
                決済情報送信先URL<br /> [拡張セット]
            </th>
            <td>
                <!--{assign var=key value="extset_url"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box40" maxlength="<!--{$smarty.const.URL_LEN}-->"><br />
            </td>
        </tr>
        <tr>
            <th colspan="2"><strong>■マルチ決済設定</strong><span class="red">（オプション）</span></th>
        </tr>
        <tr>
            <th>
                ホスト番号<br /> [マルチ決済]
            </th>
            <td>
                <!--{assign var=key value="cvs_host_id"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box10" maxlength="<!--{$smarty.const.INT_LEN}-->">
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>マルチ決済専用のホストを設定する場合、こちらにホスト番号を入力してください。</td>
                    </tr>
                    <tr>
                        <td>※</td>
                        <td>入力が無い場合、「基本設定」の「ホスト番号」が適用されます。</td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th>決済情報送信先URL</th>
            <td>
                <!--{assign var=key value="cvs_url"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                [リンク式]<br />
                <!--{assign var=key value="convenience_url"}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box40" maxlength="<!--{$smarty.const.URL_LEN}-->"><br />
                [トークン式]<span class="red">（オプション）</span><br />
                <!--{assign var=key value="gateway_convenience_url"}-->
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box40" maxlength="<!--{$smarty.const.URL_LEN}-->"><br />
            </td>
        </tr>
        <tr>
            <th>支払期限</th>
            <td>
                <!--{assign var=key value="pay_date"}-->
                <select name="<!--{$key}-->" id="<!--{$key}-->">
                <!--{html_options options=$arrPaydate selected=$arrForm[$key].value}-->
                </select>&emsp;日後
            </td>
        </tr>
        <tr>
            <th>利用収納機関</th>
            <td>
                <!--{assign var=key value="cvs[]"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{foreach from=$arrConvenience key=keyname item=val}-->
                    <!--{if $val.NAME != ""}-->
                    <input type="checkbox" name="<!--{$key}-->" value="<!--{$keyname}-->" id="<!--{$keyname}-->" <!--{$arrConvenience[$keyname].chk}-->/><!--{$val.NAME}--><br />
                    <!--{/if}-->
                <!--{/foreach}--><br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>契約内容に合わせて選択をしてください。なお、選択された利用収納機関のみがマルチ決済の選択画面に表示されます。</td>
                    </tr>
                    <tr>
                        <td>※</td>
                        <td>
                            カード情報入力が「<b>ローカル</b>」か、接続形態が「<b>トークン式</b>」の場合のみ設定が必要です。
                        </td>
                      </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        <tr>
            <th>入金お知らせメール</th>
            <td>
                <!--{assign var=key value="receiptmail_flg"}-->
                <input class="radio_btn" type="checkbox" name="<!--{$key}-->" id="<!--{$key}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}--> />
                入金お知らせメールを送信する。<br /><br />
                <table class="comment">
                    <colgroup width="0%">
                    <colgroup width="100%">
                    <tr>
                        <td>※</td>
                        <td>ルミーズより収納情報通知があった際に入金に関するお知らせメールが顧客宛て(Bcc:管理者)に送信されます。</td>
                    </tr>
                    <tr>
                        <td>※</td>
                        <td>
                            <b>
                            <u>「収納情報通知URL」の設定が必要</u>
                            </b>です。基本設定の「結果通知URL/収納情報通知URL」欄をご覧ください。
                        </td>
                    </tr>
                    </colgroup>
                    </colgroup>
                </table>
            </td>
        </tr>
        </colgroup>
        </colgroup>
    </table>

    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
        </ul>
    </div>
</form>

<script type="text/javascript">
//<![CDATA[
    lfnCheckConnect();
//]]>
</script>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
