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
function lfnCheckSubmit( fm ){

    var err = '';

    if ( ! fm["product_id"].value ){
        err += '商品を選択して下さい。';
    }

    if ( fm["comment"] && !fm["comment"].value ){
        if ( err ) err += '';
        err += 'コメントを入力して下さい。';
    }

    if ( err ){
        alert(err);
        return false;
    } else {
        if(window.confirm('内容を登録しても宜しいですか')){
                fm.submit();
                return true;
        }
    }
	return false;
}

function ifConf(){

	if(window.confirm('内容を更新しても宜しいですか')){
			return true;
		}
		return false;
}

function lfnCheckSetItem( rank,type ){
	var flag = true;
    var checkRank = '<!--{$checkRank|h}-->';
	if ( checkRank ){
		if ( rank != checkRank ){
            if( ! window.confirm('さきほど選択した<!--{$checkRank|h}-->位の情報は破棄されます。宜しいでしょうか')){
				flag = false;
			}
		}
	}

	if ( flag ){
		eccube.openWindow('./recommend_search.php?rank=' + rank + '&type='+ type,'search','500','500');
	}
}

function lfnSortItem(mode,data){
    var flag = true;
    var checkRank = '<!--{$checkRank|h}-->';
    if ( checkRank ){
        if( ! window.confirm('さきほど選択した<!--{$checkRank|h}-->位の情報は破棄されます。宜しいでしょうか')){
            flag = false;
        }
    }

    if ( flag ){
        document.form1["mode"].value = mode;
        document.form1["best_id"].value = data;
        document.form1.submit();
    }
}
//-->
</script>

<p>
								<form name="form"" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
                                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
								<input type="hidden" name="mode" value="update">
								<input type="hidden" name="type" value="<!--{$type}-->">
								<input type="submit" name="subm" value="現在日から30日間のランキング反映" onclick="return ifConf();" />
								</form>
</p>

						<!--{section name=cnt loop=$tpl_disp_max}-->
<!--▼s2 20120918 #237-->
<!--ランキング-->
<!--{php}-->
$admin_best_title_p = '<p id="admin_best_title" class="admin_best_title">';
$admin_best_title_p_end = "</p>";

$admin_best_table = '<table id="table'. $_smarty_tpl->tpl_vars['__smarty_section_cnt']->value['iteration'] .'" class="recomment_table" width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">';
$admin_best_table_end = '</table>';

$admin_best_tr = '<tr class="fs12n">';
$admin_best_tr_end = '</tr>';

$admin_best_td = '<td bgcolor="#ffffff" width="25%" align="center" style="vertical-align: top;" id="td'. $_smarty_tpl->tpl_vars['__smarty_section_cnt']->value['iteration'] .'">';
$admin_best_td_end = '</td>';

switch($_smarty_tpl->tpl_vars['__smarty_section_cnt']->value['index'])
{
	case 0:
		echo $admin_best_title_p . "20代ドレス" . $admin_best_title_p_end;

		echo $admin_best_table;
		echo $admin_best_tr;

		echo $admin_best_td;
		echo "1位";

		break;
	case 1:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "2位";

		break;
	case 2:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "3位";
		break;
	case 3:

		echo $admin_best_td_end;
		echo $admin_best_tr_end;
		echo $admin_best_table_end;

		echo $admin_best_title_p . "20代ワンピース" . $admin_best_title_p_end;
		echo $admin_best_table;
		echo $admin_best_tr;

		echo $admin_best_td;
		echo "1位";

		break;
	case 4:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "2位";

		break;
	case 5:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "3位";
		break;
	case 6:

		echo $admin_best_td_end;
		echo $admin_best_tr_end;
		echo $admin_best_table_end;

		echo $admin_best_title_p . "30代ドレス" . $admin_best_title_p_end;
		echo $admin_best_table;
		echo $admin_best_tr;

		echo $admin_best_td;
		echo "1位";

		break;
	case 7:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "2位";

		break;
	case 8:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "3位";

		break;

	case 9:

		echo $admin_best_td_end;
		echo $admin_best_tr_end;
		echo $admin_best_table_end;

		echo $admin_best_title_p . "30代ワンピース" . $admin_best_title_p_end;
		echo $admin_best_table;
		echo $admin_best_tr;

		echo $admin_best_td;
		echo "1位";

		break;

	case 10:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "2位";

		break;
	case 11:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "3位";

		break;
	case 12:

		echo $admin_best_td_end;
		echo $admin_best_tr_end;
		echo $admin_best_table_end;

		echo $admin_best_title_p . "40代ドレス" . $admin_best_title_p_end;
		echo $admin_best_table;
		echo $admin_best_tr;

		echo $admin_best_td;
		echo "1位";

		break;
	case 13:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "2位";

		break;
	case 14:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "3位";

		break;

	case 15:

		echo $admin_best_td_end;
		echo $admin_best_tr_end;
		echo $admin_best_table_end;

		echo $admin_best_title_p . "40代ワンピース" . $admin_best_title_p_end;
		echo $admin_best_table;
		echo $admin_best_tr;

		echo $admin_best_td;
		echo "1位";

		break;

	case 16:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "2位";

		break;
	case 17:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "3位";

		break;


	case 18:

		echo $admin_best_td_end;
		echo $admin_best_tr_end;
		echo $admin_best_table_end;

		echo $admin_best_title_p . "50代ドレス" . $admin_best_title_p_end;
		echo $admin_best_table;
		echo $admin_best_tr;

		echo $admin_best_td;
		echo "1位";

		break;
	case 19:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "2位";

		break;
	case 20:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "3位";

		break;

	case 21:

		echo $admin_best_td_end;
		echo $admin_best_tr_end;
		echo $admin_best_table_end;

		echo $admin_best_title_p . "50代ワンピース" . $admin_best_title_p_end;
		echo $admin_best_table;
		echo $admin_best_tr;

		echo $admin_best_td;
		echo "1位";

		break;

	case 22:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "2位";

		break;
	case 23:
		echo $admin_best_td_end;
		echo $admin_best_td;
		echo "3位";

		break;

	default:
		// 何もしない
		break;
}
<!--{/php}-->
<!--▲s2 20120918 #237-->

<!--▼おすすめ<!--{$smarty.section.cnt.iteration}-->-->
<form name="form<!--{$smarty.section.cnt.iteration}-->" id="form<!--{$smarty.section.cnt.iteration}-->" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	<input type="hidden" name="mode" value="regist">
    <input type="hidden" name="best_id" value="<!--{$arrItems[$smarty.section.cnt.iteration].best_id|h}-->" />
	<input type="hidden" name="product_id" value="<!--{$arrItems[$smarty.section.cnt.iteration].product_id|h}-->" />
    <input type="hidden" name="category_id" value="<!--{$category_id|h}-->" />
    <input type="hidden" name="rank" value="<!--{$arrItems[$smarty.section.cnt.iteration].rank|h}-->" />
	<input type="hidden" name="type" value="<!--{$type|h}-->">

	<br/>
	<!--{if $arrItems[$smarty.section.cnt.iteration].main_list_image != ""}-->
		<!--{assign var=image_path value="`$smarty.const.IMAGE_SAVE_URLPATH``$arrItems[$smarty.section.cnt.iteration].main_list_image`"}-->
	<!--{else}-->
		<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
	<!--{/if}-->
	<!--{if $arrItems[$smarty.section.cnt.iteration].status == 2 || $arrItems[$smarty.section.cnt.iteration].del_flag == 1}-->
    	<!--{assign var=image_path value="`$smarty.const.READY_IMAGE_URL`"}-->
	<!--{/if}-->
	<img src="<!--{$image_path}-->" alt="" />
	<p><!--{$arrItems[$smarty.section.cnt.iteration].name|escape}--></p>

	<!--{if $arrItems[$smarty.section.cnt.iteration].product_id}-->
	<a href="#" onClick="return eccube.insertValueAndSubmit( document.form<!--{$smarty.section.cnt.iteration}-->, 'mode', 'delete', '削除します。宜しいですか' )">削除</a>
	<!--{/if}-->
	<br/><br/>

	<a href="#" onclick="lfnCheckSetItem('<!--{$smarty.section.cnt.iteration}-->','<!--{$type|h}-->'); return false;" target="_blank">
	<!--{if $arrItems[$smarty.section.cnt.iteration].product_id}-->商品変更<!--{else}-->商品選択<!--{/if}-->
	</a>
	<br/><br/>

	<!--{if $arrItems[$smarty.section.cnt.iteration].product_id}-->
	<input type="submit" name="subm" value="登録する" onclick="return lfnCheckSubmit(document.form<!--{$smarty.section.cnt.iteration}-->);"/>
	<!--{/if}-->
</form>
<!--{php}-->
$admin_best_table_end = '</table>';
$admin_best_tr_end = '</tr>';
$admin_best_td_end = '</td>';
switch($_smarty_tpl->tpl_vars['__smarty_section_cnt']->value['index'])
{
	case 23:
		echo $admin_best_td_end;
		echo $admin_best_tr_end;
		echo $admin_best_table_end;
		break;
	default:
		// 何もしない
		break;
}
<!--{/php}-->
						<!--{/section}-->
