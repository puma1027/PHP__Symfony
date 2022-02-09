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



<!--★★メインコンテンツ★★-->

<script type="text/javascript">
<!--

    function fnEtcEnable(check){
        var fm = document.form1;
        if(check==true){
            fm["clamp_etc"].disabled=false;
            fm["clamp_etc"].focus();
        }else{
            fm["clamp_etc"].disabled=true;
        }
    }

//-->
</script>
<div id="products" class="contents-main">  
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" enctype="multipart/form-data">
<!--{foreach key=key item=item from=$arrHidden}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />  
<input type="hidden" name="image_key" value="">

<input type="hidden" name="mode" value="edit">
<input type="hidden" name="bag_id" value="<!--{$tpl_bag_id}-->">
                      

                        <table>
                            <tr>
                                <th>バッグ仮番号<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.bag_no}--></span>
                                    <input type="text" name="bag_no" id="bag_no" value="<!--{$arrForm.bag_no|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.bag_no != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="20" class="box20" />
                                    <span class="attention">※そのうち削除しますが、今は必要なので入力してください。</span><!--//::N00083 Add 20131201-->
                                </td>
                            </tr>
                            <!--//::N00083 Add 20131201-->
                            <tr>
                                <th>バッグ商品コード<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.product_code}--></span>
                                    <input type="text" name="product_code" id="product_code" value="<!--{$arrForm.product_code|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.product_code != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="20" class="box20" />
                                </td>
                            </tr>
                            <tr>
                                <th>商品名<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.name}--></span>
                                    <input type="text" name="name" id="name" value="<!--{$arrForm.name|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.name != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="20" class="box20" />
                                </td>
                            </tr>
                            <tr>
                                <th>在庫数<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.stock}--></span>
                                    <input type="text" name="stock" id="stock" value="<!--{$arrForm.stock|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.stock != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="20" class="box20" />
                                </td>
                            </tr>
                            <!--//::N00083 end 20131201-->
                            <tr>
                                <th>検品画像タイプ<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"> <!--{$arrErr.inspect_image}--> </span>
                                    <select id="inspect_image" name="inspect_image" style="<!--{$arrErr.inspect_image|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrInspectImages selected=$arrForm.inspect_image}-->
                                    </select>
                                </td>
                            </tr>
                            <!--//::N00083 Add 20131201-->
                            <tr>
                              <th>商品ステータス</th>
                              <td>
                                <!--<!--{html_checkboxes name="product_flag" options=$arrSTATUS selected=$arrForm.product_flag|default:$smarty.const.GRADE_VERY_GOOD onChange="fnCheckProductFlag(this);"}-->-->
                                <!--{html_radios name="product_flag" options=$arrSTATUS selected=$arrForm.product_flag|default:$smarty.const.GRADE_VERY_GOOD}-->
                              </td>
                            </tr>
                            <!--//::N00083 end 20131201-->
                            <tr>
                                <th>ブランド<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.brand}--></span>

                                    <select id="brand" name="brand" style="<!--{$arrErr.brand|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrBRAND selected=$arrForm.brand}-->
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>バッグ外観<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.outside_width}--></span>
                                    <span class="attention"><!--{$arrErr.outside_height}--></span>
                                    <span class="attention"><!--{$arrErr.outside_thickness}--></span>
                                    <span class="attention"><!--{$arrErr.chain_length}--></span>
                                    <span class="attention"><!--{$arrErr.chain_added}--></span>
                                    <span class="attention"><!--{$arrErr.chain_remove}--></span>
                                    <span class="attention"><!--{$arrErr.clamp}--></span>
                                    幅
                                    <input type="text" name="outside_width" id="outside_width" value="<!--{$arrForm.outside_width|escape}-->"  style="<!--{if $arrErr.outside_width != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="3" class="box3" /><!-- ime-mode: disabled; -->
                                    ㎝×縦
                                    <input type="text" name="outside_height" id="outside_height" value="<!--{$arrForm.outside_height|escape}-->" style="<!--{if $arrErr.outside_height != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="3" class="box3" /><!-- ime-mode: disabled; -->
                                    ㎝×厚さ
                                    <input type="text" name="outside_thickness" id="outside_thickness" value="<!--{$arrForm.outside_thickness|escape}-->" style="<!--{if $arrErr.outside_thickness != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="3" class="box3" /><!-- ime-mode: disabled; -->
                                    ㎝。<br/>
                                    チェーン
                                    <input type="text" name="chain_length" id="chain_length" value="<!--{$arrForm.chain_length|escape}-->" style="<!--{if $arrErr.chain_length != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="3" class="box3" /><!-- ime-mode: disabled; -->
                                    本
                                    <input type="text" name="chain_added" id="chain_added" value="<!--{$arrForm.chain_added|escape}-->" style="<!--{if $arrErr.chain_added != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="3" class="box3" />
                                    ㎝付き。取外し
                                    <select id="chain_remove" name="chain_remove" style="<!--{$arrErr.chain_remove|sfGetErrorColor}-->">
                                        <!--{html_options options=$arrCancel selected=$arrForm.chain_remove}-->
                                    </select>
                                    。<br/>
                                    留め具は
                                    <input type="radio" name="clamp" value="0" <!--{if $arrForm.clamp=="0"}-->checked<!--{/if}--> onclick ="fnEtcEnable(false);">がま口
                                    <input type="radio" name="clamp" value="1" <!--{if $arrForm.clamp=="1"}-->checked<!--{/if}--> onclick ="fnEtcEnable(false);">マグネット
                                    <input type="radio" name="clamp" value="2" <!--{if $arrForm.clamp=="2"}-->checked<!--{/if}--> onclick ="fnEtcEnable(false);">ファスナー
                                    <input type="radio" name="clamp" value="3" <!--{if $arrForm.clamp=="3"}-->checked<!--{/if}--> onclick ="fnEtcEnable(this.checked);">その他（
                                    <input type="text" name="clamp_etc" id="clamp_etc" <!--{if $arrForm.clamp!="3"}-->disabled="disabled"<!--{/if}-->  value="<!--{$arrForm.clamp_etc|escape}-->" style="<!--{if $arrErr.clamp_etc != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="9" class="box9" />
                                    ）
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <th>バッグ内側<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.inside_width}--></span>
                                    <span class="attention"><!--{$arrErr.inside_height}--></span>
                                    内ポケット（幅
                                    <input type="text" name="inside_width" id="inside_width" value="<!--{$arrForm.inside_width|escape}-->" style="<!--{if $arrErr.inside_width != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="3" class="box3" /><!-- ime-mode: disabled; -->
                                    ㎝×縦
                                    <input type="text" name="inside_height" id="inside_height" value="<!--{$arrForm.inside_height|escape}-->" style="<!--{if $arrErr.inside_height != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="3" class="box3" /><!-- ime-mode: disabled; -->
                                    ㎝）付き。<br/>
                                    <input type="checkbox" name="tel_flg" value="1" <!--{if $arrForm.tel_flg=="1"}-->checked<!--{/if}-->>携帯電話
                                    <input type="checkbox" name="camera_flg" value="1" <!--{if $arrForm.camera_flg=="1"}-->checked<!--{/if}-->>デジカメ
                                    <input type="checkbox" name="money_flg" value="1" <!--{if $arrForm.money_flg=="1"}-->checked<!--{/if}-->>ご祝儀袋
                                    <input type="checkbox" name="handkerchief_flg" value="1" <!--{if $arrForm.handkerchief_flg=="1"}-->checked<!--{/if}-->>ハンカチ

                                </td>
                            </tr>
                            <tr>
                                <th>ディティール</th>
                                <td>
                                    <span class="attention"><!--{$arrErr.detail}--></span>
                                    <textarea name="detail" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.detail|sfGetErrorColor}-->"><!--{$arrForm.detail|escape}--></textarea><br />
                                </td>
                            </tr>
                            <tr>
                                <th>注意事項</th>
                                <td>
                                    <span class="attention"><!--{$arrErr.attention}--></span>
                                    <textarea name="attention" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.attention|sfGetErrorColor}-->"><!--{$arrForm.attention|escape}--></textarea><br />
                                </td>
                            </tr>
                            <tr>

                                <th>画像</th>
                                <td>
                                    <!--{assign var=key1 value="image1"}-->
                                    <a name="<!--{$key1}-->"></a>
                                    （1）<br/>
                                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                                        <!--{if $arrFile[$key1].filepath != ""}-->
                                    <img src="<!--{$arrFile[$key1].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />
                                    　<a href="" onclick="eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key1}-->'); return false;">[画像の取り消し]</a><br>
                                        <!--{/if}-->
                                    <input type="file" name="image1" size="44" class="box50" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" />
                                    <input type="button" name="img_btn1" onclick="eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key1}-->')" value="アップロード">
                                    <br/>

                                    <!--{assign var=key2 value="image2"}-->
                                    <a name="<!--{$key2}-->"></a>
                                    （2）<br/>
                                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                                        <!--{if $arrFile[$key2].filepath != ""}-->
                                    <img src="<!--{$arrFile[$key2].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />
                                    　<a href="" onclick="eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key2}-->'); return false;">[画像の取り消し]</a><br>
                                        <!--{/if}-->
                                    <input type="file" name="image2" size="44" class="box50" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" />
                                    <input type="button" name="img_btn2" onclick="eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key2}-->')" value="アップロード">
                                    <br/>

                                    <!--{assign var=key3 value="image3"}-->
                                    <a name="<!--{$key3}-->"></a>
                                    （3）<br/>
                                    <span class="attention"><!--{$arrErr[$key3]}--></span>
                                        <!--{if $arrFile[$key3].filepath != ""}-->
                                    <img src="<!--{$arrFile[$key3].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />
                                    　<a href="" onclick="eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key3}-->'); return false;">[画像の取り消し]</a><br>
                                        <!--{/if}-->
                                    <input type="file" name="image3" size="44" class="box50" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" />
                                    <input type="button" name="img_btn3" onclick="eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key3}-->')" value="アップロード">
                                    <br/>

                                </td>
                            </tr>
                        </table>
                        
                        
                                <div class="btn-area"> 
                                    <a class="btn-action" href="javascript:;" onclick="document.form1.submit(); return false;"><span class="btn-next">この内容で登録する</span></a>
                                </div>

                 
                        <table class="list">
			<col width='45%' />
			<col width='25%' />
			<col width='15%' />
			<col width='15%' />
                            <tr>
                                <th>仮番号・ブランド</th>
                                <th>画像</th>
                                <th>編集</th>
                                <th>削除</th>

                            </tr>
                            <!--{section name=cnt loop=$arrBag}-->
                            <tr bgcolor="<!--{if $tpl_bag_id != $arrBag[cnt].bag_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->">
                                <!--{assign var=bag_id value=$arrBag[cnt].bag_id}-->
                                <td class="center">
                                    <label><!--{$arrBag[cnt].product_code|escape}--></label><br/>
                                    <label>バッグ仮番号:<!--{$arrBag[cnt].bag_no|escape}--></label><br/>
                                    <label><!--{assign var=brand value=$arrBag[cnt].brand}--></label>
                                    <label><!--{$arrBRAND[$brand]}--></label>
                                </td>

                                <td class="center">
                                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrBag[cnt].image1|sfNoImageMainList|h}-->" style="max-width: 65px;max-height: 65;" alt="バッグ画像" />
                                </td>

                                <td class="center">
                                    <!--{if $tpl_bag_id != $arrBag[cnt].bag_id}-->
                                        <a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="eccube.setModeAndSubmit('pre_edit', 'bag_id', '<!--{$arrBag[cnt].bag_id}-->'); return false;">編集</a>
                                    <!--{else}-->
                                        編集中
                                    <!--{/if}-->
                                </td>
                                <td class="center">
                                    <a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="eccube.setModeAndSubmit('delete', 'bag_id', '<!--{$arrBag[cnt].bag_id}-->'); return false;">削除</a>
                                </td>
                            </tr>
                            <!--{/section}-->
                        </table>
</form>

</div>
<!--★★メインコンテンツ★★-->        
