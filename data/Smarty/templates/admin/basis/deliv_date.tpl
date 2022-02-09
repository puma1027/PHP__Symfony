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
// 更新値を生成して送信
// 各項目をアンダースコアで区切る
function fnUpdateSubmit(check,mode){
    var count;
    var check_count;
    check_count = 0;
    // formを取得
    var fm = document.form1;
    var max = fm["deliv_date_id[]"].length;
    if(max){
        for(count=0; count<max; count++){
            if(fm["deliv_date_id[]"][count].checked) {
                // 要素名生成
                var _use_day = "use_day_" + count;
                var _deliv_day_of_the_week = "deliv_day_of_the_week_" + count;
                var _rank = "rank_" + count;

                // 入力値の取得
                var use_day = fm[_use_day].value;
                var deliv_day_of_the_week = fm[_deliv_day_of_the_week].value;
                var rank = fm[_rank].value;
                var status = "0";

                if (fm["status[]"][count].checked){
                    status = fm["status[]"][count].value;
                }
                // 空文字(ご利用日)チェック
                if (!use_day) {
                    alert("利用日が未入力です");
                    return false;
                }

                if (!deliv_day_of_the_week){
                    alert("お届け日が未入力です");
                    return false;
                }

                // お届け日チェック(日~土までの値か)
                if (deliv_day_of_the_week.match(/[^日月火水木金土]/)){
                    alert("お届け日は日~土の間で入力してください");
                    return false;
                }

                // 数値(表示順)チェック
                if (rank.match(/[^0-9]/g)) {
                    alert("表示順は半角数値で入力してください");
                    return false;
                }

                // 数値(status)チェック ないと思うけど一応
                if (status.match(/[^0-9]/g)) {
                    alert("チェックボックスの値が不正です");
                    return false;
                }

                // 更新データ送信用要素の作成
                var e = document.createElement("input");
                // 要素名
                e.name="update_data_"+count;
                // hiddenで送る
                e.type="hidden";
                // 値の生成
                e.value =count + "_" + use_day + "_" + deliv_day_of_the_week + "_" + rank + "_" + status;
                fm.appendChild(e);
                check_count++;
            }
        }
    }

    // 商品が選択されていない場合
    if(check_count == 0){
        alert("変更する項目を選択してください");
        return false;
    }else{
        if(!window.confirm('更新処理を開始します')){
            return false;
        }
        document.form1.submit();
    }
}
</script>


<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="update" />
    <div id="basis" class="contents-main">
    <table >
        <col width="10%"/>
            <col  />
            <col width="15%" />
            <col width="15%" />
            <col width="10%" />
            <!--メインエリア-->
            <tr align="center">                                                                  
                <th>変更</th>
                <th>ご利用日</th>
                <th>お届け日</th>
                <th>表示順</th>
                <th>有効</th>
            </tr>                                                  
            <!--{section name=cnt loop=$arrDelivDate}-->
            <tr>
            <!--{assign var=id value=$arrDelivDate[cnt].id}-->
                <td><input type="checkbox" name="deliv_date_id[]" value="<!--{$id}-->" /></td>
                <td><input type="text" name="use_day_<!--{$id}-->" value="<!--{$arrDelivDate[cnt].use_day|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.name != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="30" class="box30" /><span class="red"> （上限<!--{$smarty.const.STEXT_LEN}-->文字）</span>
                <td><input type="text" name="deliv_day_of_the_week_<!--{$id}-->" value="<!--{$arrDelivDate[cnt].deliv_day_of_the_week|escape}-->" maxlength="1" style="<!--{if $arrErr.name != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="10" class="box10" /></td>
                <td><input type="text" name="rank_<!--{$id}-->" value="<!--{$arrDelivDate[cnt].rank|escape}-->" maxlength="1" style="<!--{if $arrErr.name != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="10" class="box10" /></td>
                <td ><input type="checkbox" name="status[]" value="1"<!--{if $arrDelivDate[cnt].status == "1"}-->checked /><!--{/if}--><br></td>
            </tr>                            
            <!--{/section}-->
     

        </table>

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="fnUpdateSubmit(true, 'update');return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </div>
</form>
