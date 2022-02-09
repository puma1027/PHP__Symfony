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
				
				<!-- {{ Add BHM_20140307 combobox --> 
				<!--{assign var=key value="template_type"}-->
				<span class="attention"><!--{$arrErr[$key]}--></span> 
				<select name="<!--{$key}-->" onChange="eccube.setValueAndSubmit( 'form1', 'mode', 'type_set' );" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
				<option value="" selected="selected">選択してください</option>
				<!--{html_options options=$arrTemplateType selected=$arrForm[$key]}-->
				</select>
				
				          
				<!-- }} Add BHM_20140307   -->
				
                <!--{assign var=key value="template_id"}-->
                <!--{if $arrErr[$key]}-->
                <br><span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{/if}-->
                <select name="template_id" onChange="return checkFlagAndSubmit();" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <option value="" selected="selected">選択してください</option>
                <!--{html_options options=$arrMailTEMPLATEBatch selected=$arrForm[$key]}-->
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
                <textarea name="body" cols="75" rows="30" class="area75" onChange="setFlag();" style="height:500px;<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key]|h}--></textarea><br />
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
			
			
			
			
			<!-- {{Add BHM_20140307  input1-input5 -->
            
            <!--{if $arrForm.template_type == 1}-->
            <tr>
            <th>ご要望データ</th>                                                
                <td>
			    <!--{assign var=key value="input3"}-->
				<span class="attention"><!--{$arrErr[$key]}--></span>
				<textarea name="input3" cols="70" rows="12" class="area75" onChange="setFlag();" style="height:80px;<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{$arrForm[$key]|escape}--></textarea><br />
				<span class="red"> （上限<!--{$smarty.const.MTEXT_LEN}-->文字）</span>
			    <div>
                    <a class="btn-normal" href="javascript:;" onclick="eccube.countChars('form1','input3','cnt_input3'); return false;"><span>文字数カウント</span></a>
                    今までに入力したのは
                    <input type="text" name="cnt_input3" size="4" class="box4" readonly="readonly" style="text-align:right" />
                    文字です。
                </div>		                  
				</td>
			</tr>
            
            <tr>
            <th>差込1入力補助文</th>                                                
                <td>
                <!--{assign var=key value="input1"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <textarea name="input1" cols="70" rows="12" class="area75" onChange="setFlag();" style="height:80px;<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{$arrForm[$key]|escape}--></textarea><br />
                <span class="red"> （上限<!--{$smarty.const.SMTEXT_LEN}-->文字）</span>
                <div>
                    <a class="btn-normal" href="javascript:;" onclick="eccube.countChars('form1','input1','cnt_input1'); return false;"><span>文字数カウント</span></a>
                    今までに入力したのは
                    <input type="text" name="cnt_input1" size="4" class="box4" readonly="readonly" style="text-align:right" />
                    文字です。
                </div>                          
                </td>
            </tr>  
            
          <tr>
            <th>差込2入力補助文</th>                                                
                <td>
                <!--{assign var=key value="input2"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <textarea name="input2" cols="70" rows="12" class="area75" onChange="setFlag();" style="height:80px;<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{$arrForm[$key]|escape}--></textarea><br />
                <span class="red"> （上限<!--{$smarty.const.SMTEXT_LEN}-->文字）</span>
                <div>
                    <a class="btn-normal" href="javascript:;" onclick="eccube.countChars('form1','input2','cnt_input2'); return false;"><span>文字数カウント</span></a>
                    今までに入力したのは
                    <input type="text" name="cnt_input2" size="4" class="box4" readonly="readonly" style="text-align:right" />
                    文字です。
                </div>                          
                </td>
            </tr>                                                                      

          <tr>
            <th>差込3入力補助文</th>                                                
                <td>
                <!--{assign var=key value="input4"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <textarea name="input4" cols="70" rows="12" class="area75" onChange="setFlag();" style="height:80px;<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{$arrForm[$key]|escape}--></textarea><br />
                <span class="red"> （上限<!--{$smarty.const.SMTEXT_LEN}-->文字）</span>
                <div>
                    <a class="btn-normal" href="javascript:;" onclick="eccube.countChars('form1','input4','cnt_input4'); return false;"><span>文字数カウント</span></a>
                    今までに入力したのは
                    <input type="text" name="cnt_input4" size="4" class="box4" readonly="readonly" style="text-align:right" />
                    文字です。
                </div>                          
                </td>
            </tr>                                                                      

          <tr>
            <th>差込4入力補助文</th>                                                
                <td>
                <!--{assign var=key value="input5"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <textarea name="input5" cols="70" rows="12" class="area75" onChange="setFlag();" style="height:80px;<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{$arrForm[$key]|escape}--></textarea><br />
                <span class="red"> （上限<!--{$smarty.const.SMTEXT_LEN}-->文字）</span>
                <div>
                    <a class="btn-normal" href="javascript:;" onclick="eccube.countChars('form1','input5','cnt_input5'); return false;"><span>文字数カウント</span></a>
                    今までに入力したのは
                    <input type="text" name="cnt_input5" size="4" class="box4" readonly="readonly" style="text-align:right" />
                    文字です。
                </div>                          
                </td>
            </tr>             		
			
			<!--{/if}-->
			
			
			<!-- }}Add BHM_20140307  -->
			
			
            
        </table>

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'regist', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </div>
</form>
