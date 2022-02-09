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
/*
 * ####################################################
 * バージョン　変更日　		変更者　変更内容
 * 1.0.0	  2012/02/14	R.K		モデル設定で新規作成
 * ####################################################
 */
*}-->
<!--★★メインコンテンツ★★-->
<div id="products" class="contents-main">

<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data"> 
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="model_id" value="<!--{$tpl_model_id}-->">
<input type="hidden" name="image_key" value="">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<!--{foreach key=key item=item from=$arrHidden}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->
                       
                        <!--登録テーブルここから-->
                        <table>
                            <tr>
                                <th>モデル名<span class="attention"> *</span></td>
                                <td>
                                <span class="attention"><!--{$arrErr.name}--></span>
                                <input type="text" name="name" value="<!--{$arrForm.name|escape}-->" style="" size="30" class="box30"/>
                                </td>
                            </tr>
                            <tr>
                                <th>区分<span class="attention"> *</span></th>
                                <td>
                                <span class="attention"><!--{$arrErr.type}--></span>
                                <!--{html_radios name="type" options=$arrModelType selected=$arrForm.type separator="<br />"}-->
                                </td>
                            </tr>
                            <tr>
                                <th>画像</th>
                                <td>                                                     
                                <!--{assign var=key value="model_image"}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                <!--{if $arrFile[$key].filepath != ""}-->
                                    <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />　<a href="" onclick="eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
                                <!--{/if}-->
                                <input type="file" name="model_image" size="50" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                                <a class="btn-normal" href="javascript:;" name="btn" onclick="eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->')">アップロード</a>
                                </td>
                            </tr>
                            <tr>
                                <th>体型<span class="attention"> *</span></th>
                                <td>
                                <span class="attention"><!--{$arrErr.height}--></span>
                                <span class="attention"><!--{$arrErr.weight}--></span>
                                <span class="attention"><!--{$arrErr.size}--></span>
                                <span class="attention"><!--{$arrErr.body_type}--></span>
                                <span class="attention"><!--{$arrErr.bust}--></span>
                                <span class="attention"><!--{$arrErr.waist}--></span>
                                <span class="attention"><!--{$arrErr.hip}--></span>
                                <span class="attention"><!--{$arrErr.under}--></span>
                            <!-- ADD KGS_20140318 -->
                                <span class="red12">※体重と体系は入力不要です。</span><br>  
                                
                                    <table>
                                        <tr>
                                            <td>身長<input type="text" name="height" value="<!--{$arrForm.height|escape}-->"/>cm</td>
                                            <td>体重<input type="text" name="weight" value="<!--{$arrForm.weight|escape}-->"/>kg</td>
                                            <td>サイズ<input type="text" name="size" value="<!--{$arrForm.size|escape}-->" /></td>
                                        </tr>
                                        <tr>
                                            <td>体型<input type="text" name="body_type" value="<!--{$arrForm.body_type|escape}-->"/></td>    
                                            <td>バスト<input type="text" name="bust" value="<!--{$arrForm.bust|escape}-->" />cm</td>
                                            <td>ウェスト<input type="text" name="waist" value="<!--{$arrForm.waist|escape}-->"/>cm</td>
                                        </tr>
                                        <tr>
                                            <td>ヒップ<input type="text" name="hip" value="<!--{$arrForm.hip|escape}-->" />cm</td>
                                            <td>アンダー<input type="text" name="under" value="<!--{$arrForm.under|escape}-->" />cm</td>
                                            <td>下着のカップ<input type="text" name="under_cup" value="<!--{$arrForm.under_cup|escape}-->" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>SHOP備考欄</th>
                                <td>
                                <span class="attention"><!--{$arrErr.description}--></span>
                                <input type="text" name="description" value="<!--{$arrForm.description|escape}-->" style="" size="30" class="box76"/>
                                </td>
                            </tr>
                        </table>
                        <div class="btn-area">
                            <ul>
                                <li><a class="btn-action" href="javascript:;" onclick="document.form1.submit(); return false;"><span class="btn-next">この内容で登録する</span></a></li>
                            </ul>
                        </div>

                        <!--登録テーブルここまで-->
                        
                        </td>
                    </tr>
                </table>
</form>
    <table>
            <col width="10%" />
            <col width="15%" />
            <col width="10%" />
            <col width="45%" />
            <col width="10%" />
            <col width="5%" />
            <col width="5%" />
        <tr>
            <th>モデル名</th>
            <th>区分</th>
            <th>画像</th>
            <th>体型</th>
            <th>SHOP備考欄</th>
            <th colspan="2" class="center">操作</th>
        </tr>
        <!--{section name=cnt loop=$arrModel}-->
        <tr bgcolor="<!--{if $tpl_model_id != $arrModel[cnt].model_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->">
            <td class="center"><!--{* モデル名 *}-->
                <!--{$arrModel[cnt].name|escape}-->
            </td>
            <td class="center"><!--{* 区分 *}-->
                <!--{html_radios name="type_"|cat:$smarty.section.cnt.index+1 options=$arrModelType selected=$arrModel[cnt].type separator="<br />"}-->
            </td>
            <td class="center"><!--{* 画像 *}-->
            <!--{if $arrModel[cnt].model_image != ""}-->
                <!--{assign var=image_path value="`$arrModel[cnt].model_image`"}-->
            <!--{else}-->
                <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
            <!--{/if}-->
            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$image_path|sfNoImageMainList|h}-->" style="max-width: 120px;max-height: 120;" />
            </td>
            <td class="center"><!--{* 体型 *}-->
                <table>
                    <tr>
                        <td>身長<input type="text" name="height_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].height|escape}-->" readonly style="" />cm</td>
                        <td>体重<input type="text" name="weight_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].weight|escape}-->" readonly style="" />kg</td>
                        <td>サイズ<input type="text" name="size_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].size|escape}-->" readonly style=""/></td>
                    </tr>
                    <tr>
                        <td>体型<input type="text" name="body_type_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].body_type|escape}-->" readonly style="" /></td>
                        <td>バスト<input type="text" name="bust_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].bust|escape}-->" readonly style=""/>cm</td>
                        <td>ウェスト<input type="text" name="waist_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].waist|escape}-->" readonly style="" />cm</td>
                    </tr>
                    <tr>
                        <td>ヒップ<input type="text" name="hip_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].hip|escape}-->" readonly style="" />cm</td>
                        <td>アンダー<input type="text" name="under_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].under|escape}-->" readonly style=""/>cm</td>
                        <td>下着のカップ<input type="text" name="under_cup_<!--{$smarty.section.cnt.index+1}-->" value="<!--{$arrModel[cnt].under_cup|escape}-->" readonly style=""/></td>
                    </tr>
                </table>
            </td>
            <td class="center"><!--{* SHOP備考欄 *}-->
                <!--{$arrModel[cnt].description|escape}-->
            </td>
            <td class="center">
            <!--{if $tpl_model_id != $arrModel[cnt].model_id}-->
            <a href="?" onclick="eccube.setModeAndSubmit('pre_edit', 'model_id', <!--{$arrModel[cnt].model_id}-->); return false;">編集</a>
            
            <!--{else}-->
            編集中
            <!--{/if}-->
            </td>
            <td class="center">
            <!--{if $arrModelCatCount[$model_id] > 0}-->
            -
            <!--{else}-->
            <a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="eccube.setModeAndSubmit('delete', 'model_id', <!--{$arrModel[cnt].model_id}-->);document.form1.submit(); return false;">削除</a>
            <!--{/if}-->
            </td>
        </tr>
        <!--{/section}-->
    </table>
</form>

</div>