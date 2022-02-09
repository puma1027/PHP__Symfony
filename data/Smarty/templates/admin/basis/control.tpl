
                                         
    <table class="list">  
        <col width="65%" />
        <col width="20%" />
        <col width="15%" />
       
        <tr>
            <th class="center">管理タイトル/管理内容</th>
            <th class="center">選択項目</th>
            <th class="center">編集</th>  
        </tr>
        <!--{section name=cnt loop=$arrControlList}-->
        <tr>
          <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
            <td class="center">
                <b><!--{$arrControlList[cnt].control_title|escape}-->
                </b>
                <br/>
                <!--{$arrControlList[cnt].control_text|escape}-->
            </td>
            <td class="center">
                <select name="control_flg" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <!--{html_options options=$arrControlList[cnt].control_area selected=$arrControlList[cnt].control_flg.value}-->
                </select>
            </td>
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="edit" />  
            <input type="hidden" name="control_id" value="<!--{$arrControlList[cnt].control_id}-->" />
            <td class="center">                                   
               <a  href="javascript:;" onclick="document.form1.submit();">編集</a> 
            </td>
          </form>
        </tr>
        <!--{/section}-->
    </table>  
