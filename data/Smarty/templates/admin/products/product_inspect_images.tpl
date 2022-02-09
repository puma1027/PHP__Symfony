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

<!--<script type="text/javascript" src="<!--{$TPL_DIR}-->js/_swfupload/swfupload.js"></script>-->
<!--<script type="text/javascript" src="<!--{$TPL_DIR}-->js/_swfupload/fileprogress.js"></script>-->
<!--<script type="text/javascript" src="<!--{$TPL_DIR}-->js/_swfupload/handlers.js"></script>-->

<script type="text/javascript" src="/user_data/packages/wanpi/js/_swfupload/swfupload.js"></script>
<script type="text/javascript" src="/user_data/packages/wanpi/js/_swfupload/fileprogress.js"></script>
<script type="text/javascript" src="/user_data/packages/wanpi/js/_swfupload/handlers.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 

<script type="text/javascript">
<!--{if $delete_err}-->
window.alert("<!--{$delete_err}-->");
<!--{/if}-->
<!--{foreach from=$inspect_images_data key=key item=row}-->
var upload1_<!--{$row.image_id}-->;
var upload2_<!--{$row.image_id}-->;
<!--{/foreach}-->
var upload0_1;
var upload0_2;

//function makeUploadObject(image_id, index){
//    new_obj = new SWFUpload({
//        // Backend settings
//        upload_url: "<!--{$smarty.const.URL_DIR}-->ChlFApkIyT8eBiMz/products/upload.php",
//         
//        post_params : { "image_id" : image_id, "creator_id": <!--{$tpl_creator_id}--> },
//
//        // Flash file settings
//        file_size_limit : "10240",  // 10240kb
//        file_types : "*.jpg; *.png; *.bmp; *.* ", 
//        file_types_description : "Image Files",
//        file_upload_limit : 0,
//        file_queue_limit : 1,
//        
//        file_dialog_start_handler: fileDialogStart,
//        file_queued_handler : fileQueued,
//        file_queue_error_handler : fileQueueError,
//        file_dialog_complete_handler : fileDialogComplete,
//        
//        swfupload_preload_handler : preLoad,
//        swfupload_load_failed_handler : loadFailed,
//        upload_progress_handler : uploadProgress,
//        upload_error_handler : uploadError,
//        upload_success_handler : uploadSuccess,
//        upload_complete_handler : uploadComplete,
//
//        // Button Settings
//        // button_image_url : "<!--{$TPL_DIR}-->img/button/ButtonUpload_51x22.png", // ishibashi spに動いてるため、手動で修正
//        button_image_url : "/user_data/packages/wanpi/img/button/ButtonUpload_51x22.png",
//
//        button_placeholder_id : "upload_btn"+image_id+"_"+index,
//        button_width: 51,
//        button_height: 22,
//        
//        // Flash Settings
//        // flash_url : "<!--{$TPL_DIR}-->js/_swfupload/swfupload.swf", // 上記と同様 ishibashi
//        flash_url : "/user_data/packages/wanpi/js/_swfupload/swfupload.swf",
//
//        // flash9_url : "<!--{$TPL_DIR}-->js/_swfupload/swfupload_fp9.swf", // 上記と同様
//        flash9_url : "/user_data/packages/wanpi/js/_swfupload/swfupload_fp9.swf",
//
//        custom_settings : {
//            progress_target : "upload_div"+image_id+"_"+index,
//            upload_successful : false,
//            upload_file_text : "upload_txt"+image_id+"_"+index,
//            uploaded_file_a : "uploaded_a"+image_id+"_"+index,
//            uploaded_file_hid : "uploaded_hid"+image_id+"_"+index,
//            uploaded_file_full_hid : "uploaded_full_hid"+image_id+"_"+index,
//            uploaded_text : "検品画像"
//        },
//        
//        // Debug settings
//        debug: false
//    });
//     
//    return new_obj;
//}

    // flash部分の置き換え
    $(function(){
        var tmp = document.getElementById('upload_btn0_1');
        $('input[type=file]').each(function(i,e){
            $(e).on('change',function(){
                let file = this,
                files = file.files[0],
                fileRdr = new FileReader(),
                image_id = $(this).data('image_id'),
                idx = $(this).data('idx');

                if( !files.length )
                {
                    if(files.type.match('image.*') && files.type.match( /(gif|jpeg|jpg|png)/ ) )
                    {
                        fileRdr.onload = function(){

                            let formData = new FormData();
                            formData.append( 'Filedata', files );
                            formData.append( 'image_id', image_id );
                            formData.append( 'creator_id', <!--{$tpl_creator_id}--> );
                            $.ajax({
                                type: "POST",
                                datatype: 'html',
                                contentType: false,
                                processData: false,
                                url: "<!--{$smarty.const.URL_DIR}-->ChlFApkIyT8eBiMz/order/upload.php",
                                data: formData
                            }).done(function (data, textStatus, jqXHR){
                                let paths = data.split(';');
                                $('#uploaded_hid'+image_id+'_'+idx).val(paths[0]);
                                $('#uploaded_full_hid'+image_id+'_'+idx).val(paths[1]);
                                $('#uploaded_a'+image_id+'_'+idx).attr('href',paths[2]+paths[0]);
                                $('#uploaded_a'+image_id+'_'+idx).html('<img src="'+paths[2]+paths[0]+'" style="max-width:150px;max-hegith:150px;">');
                            });
                        }
                        fileRdr.readAsDataURL(files);
                    }
                    else
                    {
                        alert( '添付画像のファイル形式（拡張子）はjpeg(jpg)・png・bmp・tiff・gifのいずれかで受付けております。' );
                        this.value = '';
                    }
                }
            });
        });
    });


window.onload = function () {
    upload0_1 = makeUploadObject(0, 1);
    upload0_2 = makeUploadObject(0, 2);
    <!--{foreach from=$inspect_images_data key=key item=row}-->
    upload1_<!--{$row.image_id}--> = makeUploadObject(<!--{$row.image_id}-->, 1);
    upload2_<!--{$row.image_id}--> = makeUploadObject(<!--{$row.image_id}-->, 2);
    <!--{/foreach}-->
};

</script>
<!--★★メインコンテンツ★★-->
<div id="products" class="contents-main">  
    <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="">
    <input type="hidden" name="image_id" id="image_id" value="">
    <input type="hidden" name="upload_index" id="upload_index" value="">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />    
    <!--{foreach key=key item=item from=$arrSearchHidden}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
    <!--{/foreach}-->
    <!--{foreach key=key item=item from=$arrHidden}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
    <!--{/foreach}-->      
            
 <div class="btn-area"> 
                        <!--{html_radios name=opt_image_type options=$arrProductKind selected=$arrForm.opt_image_type.value|default:$smarty.const.DRESS_INSPECT_IMAGE_TYPE separator='&nbsp;' onClick='fnModeSubmit("", "", "");'}-->
 </div> 
            <table class="list">
            <col width="20%" />
            <col width="40%" />
            <col width="40%" />
                <tr>
                    <th>検品画像タイプ</th>
                    <th><!--{$tpl_image_type_name}-->正面</th>
                    <th><!--{$tpl_image_type_name}-->背面</th>
                </tr>
                <tr class="center">
                    <td class="center">
                        <!--{assign var=key value="txt_image_name0"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="text" name="txt_image_name0" id="txt_image_name0" class="box20" value="<!--{$arrForm[$key].value}-->">
                    </td>
                    <td class="center">
                        <!--{assign var=key value="uploaded_full_hid0_1"}-->
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <!--{assign var=key value="uploaded_hid0_1"}-->
                        <span class="red12"><!--{$arrErr[$key]}--></span>
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <div>
                            <!--{if $arrForm[$key].value eq ""}-->
                                <!--<a href="" id="uploaded_a0_1" target="_blank"></a>-->
                                <span id="uploaded_a0_1"></span>
                            <!--{else}-->
                                <a href="<!--{$smarty.const.SITE_URL}--><!--{$arrForm[$key].value}-->" id="uploaded_a0_1" target="_blank">検品画像</a>
                            <!--{/if}-->
                        </div>
                        <div style="display: table-cell;vertical-align: middle;">
                            <input type="hidden" name = "txt_image0_1" id = "txt_image0_1"  disabled="disabled" class="box20"  style="background-color: #FFFFFF;" />
                            <div name="upload_div0_1" id="upload_div0_1"></div>
                        </div>
                        <div style="display: table-cell;">
                            <input type="file" name="upload_btn0_1" id="upload_btn0_1" data-image_id="0" data-idx="1">
                        </div>
                        <div  style="display: table-cell;">
                            <!--<img onclick="fnModeSubmit('delete_upload', 'upload_index', '0_1')" name="delete_btn0_1" id="delete_btn0_1" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png',this)" src="<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >--><!-- ishibashi 上記と同様-->
                            <img onclick="fnModeSubmit('delete_upload', 'upload_index', '0_1')" name="delete_btn0_1" id="delete_btn0_1" onMouseover="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png',this)" src="/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >
                        </div>
                    </td>

                    <td class="center">
                        <!--{assign var=key value="uploaded_full_hid0_2"}-->
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <!--{assign var=key value="uploaded_hid0_2"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <div>
                        <!--{if $arrForm[$key].value eq ""}-->
                        <a href="" id="uploaded_a0_2" target="_blank"></a>
                        <!--{else}-->
                        <a href="<!--{$smarty.const.SITE_URL}--><!--{$arrForm[$key].value}-->" id="uploaded_a0_2" target="_blank">検品画像</a>
                        <!--{/if}-->
                        </div>
                        <div style="display: table-cell;vertical-align: middle;">
                            <input type="hidden" name = "txt_image0_2" id = "txt_image0_2"  disabled="disabled" class="box25" style="background-color: #FFFFFF;" />
                            <div name="upload_div0_2" id="upload_div0_2"></div>
                        </div>
                            <div  style="display: table-cell;">
                            <!--<span name="upload_btn0_2" id="upload_btn0_2"></span>-->
                            <input type="file" name="upload_btn0_2" id="upload_btn0_2" data-image_id="0" data-idx="2">
                        </div>
                            <div  style="display: table-cell;">
                            <!--<img onclick="fnModeSubmit('delete_upload', 'upload_index', '0_2')" name="delete_btn0_2" id="delete_btn0_2" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png',this)" src="<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >--><!-- ishibashi 上記と同様-->
                            <img onclick="fnModeSubmit('delete_upload', 'upload_index', '0_2')" name="delete_btn0_2" id="delete_btn0_2" onMouseover="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png',this)" src="/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="center">
                        <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('regist', '', '');"><span class="btn-next">登録</span></a></li>
                    </td>
                </tr>
            </table>
            <br>
            <table>
            <col width="20%">
                    <col width="40%">
                    <col width="40%">
                <tr> 
                    
                    <th>検品画像タイプ</th>
                    <th><!--{$tpl_image_type_name}-->正面</th>
                    <th><!--{$tpl_image_type_name}-->背面</th>
                </tr>
                <!--{foreach from=$inspect_images_data key=key item=row}-->
                <!--{assign var=id value=$row.image_id}-->
                <tr>
                    <td class="center">
                        <!--{assign var=key value="txt_image_name`$id`"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="text" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                    </td>
                    <td class="center">
                        <img alt="<!--{$row.image_name}-->" width="<!--{$smarty.const.INSPECT_IMAGE_THUMB_WIDTH}-->px" src="<!--{$smarty.const.URL_DIR}--><!--{$row.image_front}-->">
                        <!--{assign var=key value="hdn_db_image_`$id`_1"}-->
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <br><br>
                        
                        <!--{assign var=key value="uploaded_full_hid`$id`_1"}-->
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <!--{assign var=key value="uploaded_hid`$id`_1"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <div>
                        <!--{if $arrForm[$key].value eq ""}-->
                        <a href="" id="uploaded_a<!--{$id}-->_1" target="_blank"></a>
                        <!--{else}-->
                        <a href="<!--{$smarty.const.SITE_URL}--><!--{$arrForm[$key].value}-->" id="uploaded_a<!--{$id}-->_1" target="_blank">検品画像</a>
                        <!--{/if}-->
                        </div>
                        <div style="display: table-cell;vertical-align: middle;">
                            <!--<input type="text" name = "txt_image<!--{$id}-->_1" id = "txt_image<!--{$id}-->_1"  disabled="disabled" class="box20"  style="background-color: #FFFFFF;" />-->
                            <input type="hidden" name = "txt_image<!--{$id}-->_1" id = "txt_image<!--{$id}-->_1"  disabled="disabled" class="box20"  style="background-color: #FFFFFF;" />
                            <div name="upload_div<!--{$id}-->_1" id="upload_div<!--{$id}-->_1"></div>
                        </div>
                            <div style="display: table-cell;">
                            <!--<span name="upload_btn<!--{$id}-->_1" id="upload_btn<!--{$id}-->_1"></span>-->
                            <input type="file" name="upload_btn<!--{$id}-->_1" id="upload_btn<!--{$id}-->_1" data-image_id="<!--{$id}-->" data-idx="1">
                        </div>
                        <div  style="display: table-cell;">
                            <!--<img onclick="fnModeSubmit('delete_upload', 'upload_index', '<!--{$id}-->_1')" name="delete_btn<!--{$id}-->_1" id="delete_btn<!--{$id}-->_1" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png',this)" src="<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >-->
                            <img onclick="fnModeSubmit('delete_upload', 'upload_index', '<!--{$id}-->_1')" name="delete_btn<!--{$id}-->_1" id="delete_btn<!--{$id}-->_1" onMouseover="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png',this)" src="/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >
                        </div>
                    </td>

                    <td class="center">
                        <img alt="<!--{$row.image_name}-->" width="<!--{$smarty.const.INSPECT_IMAGE_THUMB_WIDTH}-->px" src="<!--{$smarty.const.URL_DIR}--><!--{$row.image_back}-->">
                        <!--{assign var=key value="hdn_db_image_`$id`_2"}-->
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <br><br>
                        
                        <!--{assign var=key value="uploaded_full_hid`$id`_2"}-->
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <!--{assign var=key value="uploaded_hid`$id`_2"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
                        <div>
                        <!--{if $arrForm[$key].value eq ""}-->
                        <a href="" id="uploaded_a<!--{$id}-->_2" target="_blank"></a>
                        <!--{else}-->
                        <a href="<!--{$smarty.const.SITE_URL}--><!--{$arrForm[$key].value}-->" id="uploaded_a<!--{$id}-->_2" target="_blank">検品画像</a>
                        <!--{/if}-->
                        </div>
                        <div style="display: table-cell;vertical-align: middle;">
                            <!--<input type="text" name = "txt_image<!--{$id}-->_2" id = "txt_image<!--{$id}-->_2"  disabled="disabled" class="box20"  style="background-color: #FFFFFF;" />-->
                            <input type="hidden" name = "txt_image<!--{$id}-->_2" id = "txt_image<!--{$id}-->_2"  disabled="disabled" class="box20"  style="background-color: #FFFFFF;" />
                            <div name="upload_div<!--{$id}-->_2" id="upload_div<!--{$id}-->_2"></div>
                        </div>
                        <div style="display: table-cell;">
                            <!--<span name="upload_btn<!--{$id}-->_2" id="upload_btn<!--{$id}-->_2"></span>-->
                            <input type="file" name="upload_btn<!--{$id}-->_2" id="upload_btn<!--{$id}-->_2" data-image_id="<!--{$id}-->" data-idx="2">
                        </div>
                        <div  style="display: table-cell;">
                            <!--<img onclick="fnModeSubmit('delete_upload', 'upload_index', '<!--{$id}-->_2')" name="delete_btn<!--{$id}-->_2" id="delete_btn<!--{$id}-->_2" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png',this)" src="<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >--><!-- ishibashi 上記と同様-->
                            <img onclick="fnModeSubmit('delete_upload', 'upload_index', '<!--{$id}-->_2')" name="delete_btn<!--{$id}-->_2" id="delete_btn<!--{$id}-->_2" onMouseover="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png',this)" src="/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >
                        </div>
                    </td>
                </tr>
                <tr class="center">
                    <th class="center" colspan="3">
                        <a class="btn-action" name="btn_edit_<!--{$id}-->" href="javascript:;" onclick="fnModeSubmit('confirm', 'image_id', '<!--{$id}-->');">変更</a>
                        <a class="btn-action" name="btn_edit_<!--{$id}-->" href="javascript:;" onclick="fnModeSubmit('delete', 'image_id', '<!--{$id}-->');">削除</a>
                    </th>
                </tr>
                <!--{/foreach}-->
            </table>
    </form>
</div>
<!--★★メインコンテンツ★★-->
