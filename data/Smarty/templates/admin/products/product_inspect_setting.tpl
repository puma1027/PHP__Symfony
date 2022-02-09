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
    function editableObject(type, id){
        var txt_name = "";
        var btn_update_name = "";
        var btn_edit_name = "";
        var frm = document.forms["form1"];
        
        if(type == 'inspect_man'){
            txt_name = "#txt_inspect_man_name"+id;
            btn_update_name = "btn_inspect_man_update"+id;
            btn_edit_name = "btn_inspect_man_edit"+id;
        }else if(type == 'inspect_place'){
            txt_name = "#txt_inspect_place_name"+id;
            btn_update_name = "btn_inspect_place_update"+id;
            btn_edit_name = "btn_inspect_place_edit"+id;

            var chk_all = "#chk_all"+id;
            $(chk_all).attr("disabled" , false);
        }else{//inspect_status
            txt_name = "#txt_inspect_status_name"+id;
            btn_update_name = "btn_inspect_status_update"+id;
            btn_edit_name = "btn_inspect_status_edit"+id;
        }  
        document.getElementById(btn_update_name).style.display = "block"; 
//        frm[btn_update_name].style.display = "block"; 
        document.getElementById(btn_edit_name).style.display = "none";
//        frm[btn_edit_name].style.display = "none";
        $(txt_name).attr("readonly" , false);
        
        return true;
    }
</script>

<div id="products" class="contents-main">  
    <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="">
    <input type="hidden" name="select_id" id="select_id" value="">
    <input type="hidden" name="select_type" id="select_type" value="">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

                                                                        
        <h2>検品者設定</h2>
            
        <table class="list">
            <col width="10%" />
            <col width="50%"/>
            <col width="20%" />
            <col width="20%" />
            <tr>
                <th>検品者名</th>
                <th colspan="3">
                    <span class="attention"><!--{$arrErr.txt_inspect_man_name}--></span>
                    <input type="text" name="txt_inspect_man_name" id="txt_inspect_man_name" class="box25" value="<!--{$arrForm.txt_inspect_man_name.value}-->">
                    <span class="btn-normal"><a href="javascript:;" name="btn_inspect_man_regist" id="btn_inspect_man_regist" onclick="fnSetFormVal('form1','select_type','man');fnModeSubmit('regist', 'select_id', '');">登録</a></span>
                </th>
            </tr>
            <tr>
                <th>No</th>
                <th>検品者名</th>
                <th>変更</th>
                <th>削除</th>
            </tr>
             <!--{foreach from=$inspect_man_data key=key item=row}-->
            <!--{assign var=id value=$row.inspector_id}-->
            <tr>
                <td class="center"><!--{$key+1}--></td>
                <td class="center">
                    <!--{assign var=key value="txt_inspect_man_name`$id`"}-->
                    <input type="text" name="<!--{$key}-->" id="<!--{$key}-->" class="box25" value="<!--{$row.inspector_name}-->" readonly="readonly">
                </td>
                <td class="center">
                    <a href="javascript:;" name="btn_inspect_man_update<!--{$id}-->" id="btn_inspect_man_update<!--{$id}-->" onclick="fnSetFormVal('form1','select_type','man'); fnModeSubmit('update', 'select_id', '<!--{$id}-->');" style="display: none;">変更</a>
                    <a href="javascript:;" name="btn_inspect_man_edit<!--{$id}-->" id="btn_inspect_man_edit<!--{$id}-->" onclick="editableObject('inspect_man', '<!--{$id}-->');">編集</a>
                </td>
                <td class="center"> 
                    <a href="javascript:;" name="btn_inspect_man_delete<!--{$id}-->" id="btn_inspect_man_delete<!--{$id}-->" onclick="fnSetFormVal('form1','select_type','man');fnModeSubmit('delete', 'select_id', '<!--{$id}-->');">削除</a>
                </td>
            </tr>
            <!--{/foreach}-->
        </table>
        <h2>場所設定</h2>
        <table class="list">
            <col width="10%" />
            <col  width="50%"/>
            <col width="20%" />
            <col width="20%" />
            <tr>
                <th>場所名</th>
                <th colspan="3">
                    <span class="attention"><!--{$arrErr.txt_inspect_place_name}--></span>
                    <input type="text" name="txt_inspect_place_name" id="txt_inspect_place_name" class="box25" value="<!--{$arrForm.txt_inspect_place_name.value}-->">
                    <input type="checkbox" name="chk_all" id="chk_all" value="1" <!--{if $arrForm.chk_all.value==1}-->checked="checked"<!--{/if}--> ><label for="chk_all">全体場所</label>
                    <span class="btn-normal"><a href="javascript:;" name="btn_inspect_place_regist" id="btn_inspect_place_regist" onclick="fnSetFormVal('form1','select_type','place');fnModeSubmit('regist', 'select_id', '');">登録</a></span>
                </th>
            </tr>
            <tr>
                <th>No</th>
                <th>場所名</th>
                <th>変更</th>
                <th>削除</th>
            </tr>
             <!--{foreach from=$inspect_place_data key=key item=row}-->
            <!--{assign var=id value=$row.place_id}-->
            <tr>
                <td class="center"><!--{$key+1}--></td>
                <td class="center">
                    <!--{assign var=key value="txt_inspect_place_name`$id`"}-->
                    <input type="text" name="<!--{$key}-->" id="<!--{$key}-->" class="box25" value="<!--{$row.place_name}-->" readonly="readonly">
                    <!--{assign var=key value="chk_all`$id`"}-->
                    <input type="checkbox" name="<!--{$key}-->" id="<!--{$key}-->" value="1" <!--{if $row.place_flg == 1}-->checked="checked"<!--{/if}--> disabled="disabled"><label for="<!--{$key}-->">全体場所</label>
                </td>
                <td class="center">
                    <a href="javascript:;" name="btn_inspect_place_update<!--{$id}-->" id="btn_inspect_place_update<!--{$id}-->" onclick="fnSetFormVal('form1','select_type','place');fnModeSubmit('update', 'select_id', '<!--{$id}-->');" style="display: none;">変更</a>
                    <a href="javascript:;" name="btn_inspect_place_edit<!--{$id}-->" id="btn_inspect_place_edit<!--{$id}-->" onclick="editableObject('inspect_place', '<!--{$id}-->');">編集</a>
                </td>
                <td class="center">
                    <a href="javascript:;" name="btn_inspect_place_delete<!--{$id}-->" id="btn_inspect_place_delete<!--{$id}-->" onclick="fnSetFormVal('form1','select_type','place');fnModeSubmit('delete', 'select_id', '<!--{$id}-->');">削除</a>                              
                </td>
            </tr>
            <!--{/foreach}-->
        </table>
        <h2>状態設定</h2>
        <table class="list">
            <col width="10%" />
            <col  width="50%"/>
            <col width="20%" />
            <col width="20%" />
            <tr>
                <th>状態名</th>
                <th colspan="3">
                    <span class="attention"><!--{$arrErr.txt_inspect_status_name}--></span>
                    <input type="text" name="txt_inspect_status_name" id="txt_inspect_status_name" class="box25" value="<!--{$arrForm.txt_inspect_status_name.value}-->">
                    <span class="btn-normal"><a href="javascript:;" name="btn_inspect_status_regist" id="btn_inspect_status_regist" onclick="fnSetFormVal('form1','select_type','status');fnModeSubmit('regist', 'select_id', '');">登録</a></span>
                </th>
            </tr>
            <tr>
                <th>No</th>
                <th>状態名</th>
                <th>変更</th>
                <th>削除</th>
            </tr>
             <!--{foreach from=$inspect_status_data key=key item=row}-->
            <!--{assign var=id value=$row.status_id}-->
            <tr>
                <td class="center"><!--{$key+1}--></td>
                <td class="center">
                    <!--{assign var=key value="txt_inspect_status_name`$id`"}-->
                    <input type="text" name="<!--{$key}-->" id="<!--{$key}-->" class="box25" value="<!--{$row.status_name}-->" readonly="readonly">
                </td>                                                                                                                                    
                <td class="center">
                    <a href="javascript:;" name="btn_inspect_status_update<!--{$id}-->" id="btn_inspect_status_update<!--{$id}-->" onclick="fnSetFormVal('form1','select_type','status');fnModeSubmit('update', 'select_id', '<!--{$id}-->');" style="display: none;">変更</a>
                    <a href="javascript:;" name="btn_inspect_status_edit<!--{$id}-->" id="btn_inspect_status_edit<!--{$id}-->" onclick="editableObject('inspect_status', '<!--{$id}-->');">編集</a>
                </td>
                <td class="center">
                    <a href="javascript:;" name="btn_inspect_status_delete<!--{$id}-->" id="btn_inspect_status_delete<!--{$id}-->" onclick="fnSetFormVal('form1','select_type','status');fnModeSubmit('delete', 'select_id', '<!--{$id}-->');">削除</a>                              
                </td> 
            </tr>
            <!--{/foreach}-->
        </table>
    </form>
</div>
