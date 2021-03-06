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

<script type="text/javascript">
<!--
var flag = 0;

function setFlag(){
    flag = 1;
}
function checkFlagAndSubmit(){
    if ( flag == 1 ){
        if( confirm("内容が変更されています。続行すれば変更内容は破棄されます。宜しいでしょうか？") ){
            eccube.setValueAndSubmit('form1', 'mode', 'id_set');
        } else {
            return false;
        }
    } else {
        eccube.setValueAndSubmit('form1', 'mode', 'id_set');
    }
}

//-->
</script>


<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="regist" />
    <div id="basis" class="contents-main">
        <table>
            <tr>
                <th>テンプレート<span class="attention"> *</span></th>
                <td>                      
                <!--{assign var=key value="template_id"}-->
                <!--{if $arrErr[$key]}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{/if}-->
                <select name="template_id" onChange="return checkFlagAndSubmit();" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <option value="" selected="selected">新規登録</option>
                <!--{html_options options=$arrMailSTEMPLATE selected=$arrForm[$key]}-->
                </select>
                </td>
            </tr>
            <tr>
                <th>メールタイトル<span class="attention"> *</span></th>
                <td>
                <!--{assign var=key value="subject"}-->
                <!--{if $arrErr[$key]}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{/if}-->
                <input type="text" name="subject" value="<!--{$arrForm[$key]|h}-->" onChange="setFlag();" size="30" class="box30" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                </td>
            </tr>
            <tr>
                <th>メール本体</th>
                <td>
                <!--{assign var=key value="body"}-->
                <!--{if $arrErr[$key]}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{/if}-->
                <textarea name="body" cols="75" rows="30" class="area75" onChange="setFlag();" style="height:500px;"<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key]|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)
                </span>
                <div>
                    <a class="btn-normal" href="javascript:;" onclick="eccube.countChars('form1','body','cnt_body'); return false;"><span>文字数カウント</span></a>
                    今までに入力したのは
                    <input type="text" name="cnt_body" size="4" class="box4" readonly="readonly" style="text-align:right" />
                    文字です。
                </div>
                </td>
            </tr>
            
        </table>

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'regist', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </div>
</form>
