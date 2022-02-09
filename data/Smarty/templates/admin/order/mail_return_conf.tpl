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
<!-- <script type="text/javascript" src="<!--{$TPL_DIR}-->js/_swfupload/swfupload.js"></script> -->
<!-- <script type="text/javascript" src="<!--{$TPL_DIR}-->js/_swfupload/fileprogress.js"></script> -->
<!-- <script type="text/javascript" src="<!--{$TPL_DIR}-->js/_swfupload/handlers.js"></script> -->

<!-- ishibashi -->
<script type="text/javascript" src="/user_data/packages/wanpi/js/_swfupload/swfupload.js"></script>
<script type="text/javascript" src="/user_data/packages/wanpi/js/_swfupload/fileprogress.js"></script>
<script type="text/javascript" src="/user_data/packages/wanpi/js/_swfupload/handlers.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
<!--

	<!--{foreach from=$arrSelectCustomer key=key item=row}-->
	var upload1_<!--{$row.order_id}-->;
	var upload2_<!--{$row.order_id}-->;
	var upload3_<!--{$row.order_id}-->;
	<!--{/foreach}-->
    
//    function makeUploadObject(order_id, index){
//		new_obj = new SWFUpload({
//			// Backend settings
//			upload_url: "<!--{$smarty.const.URL_DIR}-->ChlFApkIyT8eBiMz/order/upload.php",
//			
//			post_params : { "order_id" : order_id, "creator_id": <!--{$tpl_creator_id}--> },
//
//			// Flash file settings
//			file_size_limit : "10240",  // 10240kb
//			file_types : "*.*", 
//			file_types_description : "All Files",
//			file_upload_limit : 0,
//			file_queue_limit : 1,
//			
//			file_dialog_start_handler: fileDialogStart,
//			file_queued_handler : fileQueued,
//			file_queue_error_handler : fileQueueError,
//			file_dialog_complete_handler : fileDialogComplete,
//			
//			swfupload_preload_handler : preLoad,
//			swfupload_load_failed_handler : loadFailed,
//			upload_progress_handler : uploadProgress,
//			upload_error_handler : uploadError,
//			upload_success_handler : uploadSuccess,
//			upload_complete_handler : uploadComplete,
//
//			// Button Settings
//			//button_image_url : "<!--{$TPL_DIR}-->img/button/ButtonUpload_51x22.png",
//            button_image_url : "/user_data/packages/wanpi/img/button/ButtonUpload_51x22.png",
//
//			button_placeholder_id : "upload_btn"+index+"_"+order_id,
//			button_width: 51,
//			button_height: 22,
//			
//			// Flash Settings
//			//flash_url : "<!--{$TPL_DIR}-->js/_swfupload/swfupload.swf", // ishibashi
//            flash_url : "/user_data/packages/wanpi/js/_swfupload/swfupload.swf",
//
//			//flash9_url : "<!--{$TPL_DIR}-->js/_swfupload/swfupload_fp9.swf",
//            flash9_url : "/user_data/packages/wanpi/js/_swfupload/swfupload_fp9.swf",
//
//			custom_settings : {
//				progress_target : "upload_div"+index+"_"+order_id,
//				upload_successful : false,
//				upload_file_text : "upload_txt"+index+"_"+order_id,
//				uploaded_file_a : "uploaded_a"+index+"_"+order_id,
//				uploaded_file_hid : "uploaded_hid"+index+"_"+order_id,
//				uploaded_file_full_hid : "uploaded_full_hid"+index+"_"+order_id
//			},
//			
//			// Debug settings
//			debug: false
//		});
//
//		return new_obj;
//	}


    $(function() {
        $('#form1').on('change', 'input[type="file"]', function(e) {
            var file = e.target.files[0],
                reader = new FileReader(),
                $preview = $("#upload_1");
                t = this;
            
            if(file.type.indexOf("image") < 0){
                return false;
            }

            reader.onload = (function(file) {
                return function(e) {
                //既存のプレビューを削除
                $preview.empty();
                // .prevewの領域の中にロードした画像を表示するimageタグを追加
                $preview.append($('<img>').attr({
                    src: e.target.result,
                    width: "150px",
                    class: "preview",
                    title: file.name
                }));
            };
            })(file);
            reader.readAsDataURL(file);
        });
    });

	window.onload = function () {
		<!--{foreach from=$arrSelectCustomer key=key item=row}-->
		upload1_<!--{$row.order_id}--> = makeUploadObject(<!--{$row.order_id}-->, 1);
		upload2_<!--{$row.order_id}--> = makeUploadObject(<!--{$row.order_id}-->, 2);
		upload3_<!--{$row.order_id}--> = makeUploadObject(<!--{$row.order_id}-->, 3);
		<!--{/foreach}-->
	};

	var msg1 = "";
	var msg2 = "";
	var msg3 = "";//::N00026 Add 20130401
	var msg4 = "";//::N00026 Add 20130401
	var subject = "";
	
	function mailPopSubmit(URL,formName, Winname, order_id, index){
		template_id = find_template_id(order_id);
		if (template_id == ""){
			alert('テンプレートを選択ください。');
			return;
		}

		fm1 = document.form1;
		fm2 = document.forms[formName];
		template_id = find_template_id(order_id);
		getMSG(template_id);
		
		fm2["template_id"].value = template_id;
		fm2["use_status"].value = fm1["use_status_"+order_id].value;

		fm2["dirt_details"].value = "";
		//if(fm1["dirt_details_"+order_id].value != "<!--{$dirtDetail|escape}-->"){ 
			fm2["dirt_details"].value = fm1["dirt_details_"+order_id].value;
		//}
		
		insert1 = fm1["insertdata_one_"+order_id];
		insert2 = fm1["insertdata_two_"+order_id];
		insert3 = fm1["insertdata_three_"+order_id];//::N00026 Add 20130401
		insert4 = fm1["insertdata_four_"+order_id];//::N00026 Add 20130401
		fm2["insertdata_one"].value = "";
		fm2["insertdata_two"].value = "";
		fm2["insertdata_three"].value = "";//::N00026 Add 20130401
		fm2["insertdata_four"].value = "";//::N00026 Add 20130401
		if(insert1.value != msg1){
			fm2["insertdata_one"].value = insert1.value;
		}
		if(insert2.value != msg2){
			fm2["insertdata_two"].value = insert2.value;
		}
		//::N00026 Add 20130401
		if(insert3.value != msg3){
			fm2["insertdata_three"].value = insert3.value;
		}
		if(insert4.value != msg4){
			fm2["insertdata_four"].value = insert4.value;
		}
		//::N00026 end 20130401
		fm2["subject"].value = subject;

		fm2["deliv_date"].value = fm1["sel_deliv_date["+order_id+"]"].value;
		if(index != ""){
			fm2["add_file"].value = fm1["uploaded_hid"+index+"_"+order_id].value;
		}else{
			var cnt = 0;
			for(i = 1; i<4; i++){
				if(fm1["uploaded_hid"+i+"_"+order_id].value != ""){
					cnt ++;
				}
			}
			fm2["add_file"].value = cnt+"枚";
		}
		

		WIN = window.open('',Winname,"width=690,height=800,scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no");
	    document.forms[formName].target = Winname;
	    document.forms[formName].action= URL; 
	    document.forms[formName].submit();
		//WIN.focus();
	    document.forms[formName].action= "<!--{$smarty.server.PHP_SELF|escape}-->";
		document.forms[formName].target = window.name;
	}

	function process_focus(obj, order_id, index){
		template_id = find_template_id(order_id);

		if(template_id == ""){
			if(obj.value == "<!--{$dirtDetail|escape}-->"){
				obj.value = "";
			}
		}else{
			getMSG(template_id);
			if(index == '1'){
				if(obj.value == msg1){
					obj.value = "";
				}
			}else if(index == '2'){
				if(obj.value == msg2){
					obj.value = "";
				}
			//::N00026 Add 20130401
			}else if(index == '3'){
				if(obj.value == msg3){
					obj.value = "";
				}
			}else if(index == '4'){
				if(obj.value == msg4){
					obj.value = "";
				}
			//::N00026 end 20130401
			}
		}
		obj.style.color = "#000000";
	}

	function getMSG(template_id){
		<!--{foreach from=$arrInsertOneDetail key=key item=row}-->
			if(template_id == <!--{$key}-->){
				msg1 = "<!--{$arrInsertOneDetail[$key]}-->";
				msg2 = "<!--{$arrInsertTwoDetail[$key]}-->";
				msg3 = "<!--{$arrInsertThreeDetail[$key]}-->";//::N00026 Add 20130401
				msg4 = "<!--{$arrInsertFourDetail[$key]}-->";//::N00026 Add 20130401
				subject = "<!--{$arrMailSubject[$key]}-->";
			}
		<!--{/foreach}-->
	}
	
	function process_blur(obj, order_id, index){
		template_id = find_template_id(order_id);

		if(obj.value == ""){
			if(template_id == ""){
				obj.value = "<!--{$dirtDetail|escape}-->";
			}else{
				getMSG(template_id);
				
				var fm = document.form1;
				var objDiv = document.getElementById("div_msg"+index+"_"+order_id);
				var objLbl = document.getElementById("lbl_msg"+index+"_"+order_id);
				if(index == '1'){
					//obj.value = msg1;
					objLbl.innerHTML  = msg1;
				}else if(index == '2'){
					//obj.value = msg2;
					objLbl.innerHTML = msg2;
				//::N00026 Add 20130401
				}else if(index == '3'){
					objLbl.innerHTML = msg3;
				}else if(index == '4'){
					objLbl.innerHTML = msg4;
				//::N00026 end 20130401
				}
				objDiv.style.visibility = 'visible';
				objDiv.style.display = 'block';
				
				obj.style.visibility = "hidden";
				obj.style.display = 'none';
				objLbl.style.color = "#999999";
			}
			obj.style.color = "#999999";
		}		
	}

	function find_template_id(order_id){
		if(order_id == ""){
			return "";
		}
		
		var fm = document.form1;
		
		return fm["template_id_"+order_id].value;
	}
	
	function process_template_change(order_id){
		var fm = document.form1;
		insert1 = fm["insertdata_one_"+order_id];
		insert2 = fm["insertdata_two_"+order_id];
		insert3 = fm["insertdata_three_"+order_id];//::N00026 Add 20130401
		insert4 = fm["insertdata_four_"+order_id];//::N00026 Add 20130401
		dirt = fm["dirt_details_"+order_id];
		
		insert1.value = "";
		insert2.value = "";
		insert3.value = "";//::N00026 Add 20130401
		insert4.value = "";//::N00026 Add 20130401
		dirt.value = "";
		process_blur(insert1, order_id, '1');
		process_blur(insert2, order_id, '2');
		process_blur(insert3, order_id, '3');//::N00026 Add 20130401
		process_blur(insert4, order_id, '4');//::N00026 Add 20130401
		process_blur(dirt, '', '');
	}

	function fnMailSend(){
		if (!confirm('この内容でメールを送信しても宜しいですか')){
			return ;
		}
		var fm = document.form1;
		<!--{foreach from=$arrSelectCustomer key=key item=row}-->
			order_id = "<!--{$row.order_id}-->";
			template_id = find_template_id(order_id);
			getMSG(template_id);
			
			insert1 = fm["insertdata_one_"+order_id];
			insert2 = fm["insertdata_two_"+order_id];
			insert3 = fm["insertdata_three_"+order_id];//::N00026 Add 20130401
			insert4 = fm["insertdata_four_"+order_id];//::N00026 Add 20130401
			dirt = fm["dirt_details_"+order_id];

			if(insert1.value == msg1){
				insert1.value = "";
			}
			if(insert2.value == msg2){
				insert2.value = "";
			}
			//::N00026 Add 20130401
			if(insert3.value == msg3){
				insert3.value = "";
			}
			if(insert4.value == msg4){
				insert4.value = "";
			}
			//::N00026 end 20130401
			//if(dirt.value == "<!--{$dirtDetail|escape}-->"){
				//dirt.value = "";
			//}
		<!--{/foreach}-->
		
		document.form1.submit();
	}

	function div_click(objDiv, order_id, index){
		var fm = document.form1;
		insert = fm["insertdata_"+index+"_"+order_id];
		insert.style.visibility = 'visible';
		insert.style.display = 'block';
		insert.style.top = objDiv.style.top;

		objDiv.style.visibility = 'hidden';
		objDiv.style.display = 'none';
		insert.focus();
	}

	function chk_onclick(obj, order_id){
		obj_td = document.getElementById("td_"+order_id);
		if (obj.checked){
			obj_td.style.border = "solid #ff0000";
			obj_td.width = "99%";
		}else{ 
			obj_td.style.border = "none";
			obj_td.width = "100%";
		}
	}


	//::B00007 Add 20130729
    //バックスペースキーで前の画面に戻らないように。
	$(document).keydown(function(e) {
		if (e.keyCode === 8) {
			var tag = e.target.nodeName.toLowerCase();
			var $target = $(e.target);
			if ((tag !== 'input' && tag !== 'textarea') || $target.attr('readonly') || $target.is(':disabled')) {
				return false;
			}
		}
		return true;
	});
	//::B00007 end 20130729
   
    // flash部分の置き換え
    $(function(){
        var tmp = document.getElementById('upload_btn1_<!--{$index}-->');
        $('input[type=file]').each(function(i,e){
            $(e).on('change',function(){
                let file = this,
                files = file.files[0],
                fileRdr = new FileReader(),
                order_id = $(this).data('order_id'),
                idx = $(this).data('idx');
                if( !files.length )
                {
                    if(files.type.match('image.*') && files.type.match( /(gif|jpeg|jpg|png)/ ) )
                    {
                        fileRdr.onload = function(){

                            let formData = new FormData();
                            formData.append( 'Filedata', files );
                            formData.append( 'order_id', order_id );
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
                                $('#uploaded_hid'+idx+'_'+order_id).val(paths[0]);
                                $('#uploaded_full_hid'+idx+'_'+order_id).val(paths[1]);
                                $('#uploaded_a'+idx+'_'+order_id).attr('href',paths[2]+paths[0]);
                                $('#uploaded_a'+idx+'_'+order_id).html('<img src="'+paths[2]+paths[0]+'" style="max-width:150px;max-hegith:150px;">');
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

//-->
</script>
<div id="order" class="contents-main">
<form name="form2" id="form2" method="post" action="">
<!--KHS Add 2014.3.17-->
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<!--KHS End-->
<input type="hidden" name="order_id" value="">
<input type="hidden" name="template_id" value="">
<input type="hidden" name="use_status" value="">
<input type="hidden" name="dirt_details" value="">
<input type="hidden" name="insertdata_one" value="">
<input type="hidden" name="insertdata_two" value="">
<input type="hidden" name="insertdata_three" value=""><!--//::N00026 Add 20130401-->
<input type="hidden" name="insertdata_four" value=""><!--//::N00026 Add 20130401-->
<input type="hidden" name="subject" value="" >
<input type="hidden" name="deliv_date" value="" >
<input type="hidden" name="add_file" value="" >
</form>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

<input type="hidden" name="mode" value="send">
<input type="hidden" name="order_id" value="<!--{$tpl_order_id}-->">
<input type="hidden" name="upload_index" value="">

<!--{foreach key=key item=item from=$arrHidden}-->
    <!--{if is_array($item)}-->
        <!--{foreach key=c_key item=c_item from=$item}-->
        <input type="hidden" name="<!--{$key}-->[<!--{$c_key}-->]" value="<!--{$c_item|escape}-->" />
        <!--{/foreach}-->
    <!--{else}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->" />
    <!--{/if}-->
<!--{/foreach}-->

<!--{foreach key=key item=item from=$arrSearchHidden}-->
	<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->
                        <h2>返却完了メール</h2>
						<table border="0" cellspacing="0" cellpadding="0" summary=" ">
							<!--{foreach from=$arrSelectCustomer key=key item=row}-->
							<tr>
								<!--{assign var=index value=$row.order_id}-->
								<!--{assign var=key value="chk_$index"}-->
								<td id="td_<!--{$row.order_id}-->" <!--{if $arrForm.$key.value == 1}-->style = "border:solid #ff0000;" width = "99%"<!--{/if}-->>
								<table width="100%" border="0" cellspacing="1" cellpadding="5" summary=" ">
									<colgroup>
										<col style="width: 150px; background-color: #f2f1ec">
										<col style="width: 176px; background-color: #f2f1ec">
										<col style="width: 176px; background-color: #f2f1ec">
										<col style="width: 176px; background-color: #f2f1ec">
									</colgroup>
									<tr align="center">
										<td bgcolor="#ffffff"  colspan="4" height="40px;" valign="bottom">
											<!--{assign var=index value=$row.order_id}-->
											<!--{assign var=key value="chk_$index"}-->
											<input type="checkbox" value="1" name="<!--{$key}-->" id="<!--{$key}-->" onclick="chk_onclick(this, <!--{$row.order_id}-->);" <!--{if $arrForm.$key.value == 1}-->checked="checked"<!--{/if}--> >
											<span style="font-weight: bold; color: #EB2E08;">注文番号 : </span><a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="mailPopSubmit('./mail_return_view.php?order_id=<!--{$row.order_id}-->','form2','mail_return_pop_view','<!--{$row.order_id}-->', ''); return false;"><!--{$row.order_id}--></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<span style="font-weight: bold; color: #EB2E08;">名前(配送先) : </span><!--{$row.name}-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="mailPopSubmit('./mail_return_view.php?order_id=<!--{$row.order_id}-->','form2','mail_return_pop_view','<!--{$row.order_id}-->', ''); return false;">プレビュー</a>
										</td>
									</tr>
									<tr>
										<td bgcolor="#f2f1ec" >テンプレート</td>
										<td bgcolor="#cccccc" colspan="3" style="padding: 0px;" >
											<table width="100%" border="0" cellspacing="0" cellpadding="5" summary=" ">
												<tr >
													<td bgcolor="#ffffff"  align="left">
														<!--{assign var=index value=$row.order_id}-->
														<!--{assign var=key value="template_id_$index"}-->
								                        <span class="red12"><!--{$arrErr[$key]}--></span>
						                                     <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" onchange="process_template_change('<!--{$row.order_id}-->')">
						                                     <!--{html_options options=$arrMailTEMPLATE selected=$arrForm[$key].value}-->
						                                     </select>
								                    </td>
								                    <td bgcolor="#f2f1ec"  style="border-right:1px solid #cccccc;border-left:1px solid #cccccc;">ご利用状態</td>
								                    <td bgcolor="#ffffff"   align="left">
								                    	<!--{assign var=index value=$row.order_id}-->            
								                    	<!--{assign var=key value="use_status_$index"}-->
								                        <span class="red12"><!--{$arrErr[$key]}--></span>
								                        <select name="<!--{$key}-->" style="width: 330px;<!--{$arrErr[$key]|sfGetErrorColor}-->">
								                        <!--{html_options options=$arrUseStatus selected=$arrForm[$key].value}-->
								                        </select>
								                    </td>  
												</tr>
											</table>
										</td>
					                </tr>
					                <tr>
					                	<td bgcolor="#f2f1ec" >汚れ／傷の詳細</td>
					                    <td bgcolor="#ffffff"  colspan="3" align="left">
					                    	<!--{assign var=index value=$row.order_id}-->            
					                    	<!--{assign var=key value="dirt_details_$index"}-->
					                    	<!--{if $arrForm[$key].value eq ""}-->
												<textarea  name="<!--{$key}-->" onfocus="process_focus(this, '', '')"  onblur="process_blur(this, '', '')" style=" resize:none;width: 550px; height: 20px; color: #999999"><!--{$arrForm[$key].value|default:$dirtDetail|escape}--></textarea>
											<!--{else}-->
												<textarea  name="<!--{$key}-->" onfocus="process_focus(this, '', '')"  onblur="process_blur(this, '', '')" style=" resize:none;width: 550px; height: 20px; color: #000000"><!--{$arrForm[$key].value|default:$dirtDetail|escape}--></textarea>
											<!--{/if}-->
										</td>
									</tr>
									<tr>
										<td bgcolor="#f2f1ec" >差込</td>
										<td bgcolor="#ffffff"  colspan="3" align="left">
											<!--{assign var=index value=$row.order_id}-->            
					                    	<!--{assign var=key value="insertdata_one_$index"}-->
					                    	<!--{assign var=key1 value="msg1_$index"}-->
					                    	<!--{if $arrForm[$key].value == ""}-->
					                    		<div id="div_msg1_<!--{$index}-->" name="div_msg1_<!--{$index}-->" style="width: 546px; height: 35px ; color: #cccccc;border-right:1px solid;border-bottom:1px solid;border-left-style:groove;border-top-style:groove; "  onclick="div_click(this, <!--{$index}-->, 'one');">
													<label id="lbl_msg1_<!--{$index}-->" name="lbl_msg1_<!--{$index}-->" style="color: #999999;"><!--{$arrForm.$key1.value|default:$arrInsertOneDetail[$first_template_id]}--></label>
												</div>
					                    		<textarea name="<!--{$key}-->" onfocus="process_focus(this, '<!--{$index}-->', '1')"  onblur="process_blur(this, '<!--{$index}-->', '1')" style="width: 550px; height: 35px;color: #999999;resize:none; visibility:hidden;  display:none;" ></textarea>
					                    	<!--{else}-->
					                    		<div id="div_msg1_<!--{$index}-->" name="div_msg1_<!--{$index}-->" style="display:none;width: 546px; height: 35px ; color: #cccccc;border-right:1px solid;border-bottom:1px solid;border-left-style:groove;border-top-style:groove;"  onclick="div_click(this, <!--{$index}-->, 'one');">
													<label id="lbl_msg1_<!--{$index}-->" name="lbl_msg1_<!--{$index}-->" style="color: #000000;"><!--{$arrForm[$key].value|escape}--></label>
												</div>
					                    		<textarea name="<!--{$key}-->" onfocus="process_focus(this, '<!--{$index}-->', '1')"  onblur="process_blur(this, '<!--{$index}-->', '1')" style="width: 550px; height: 35px;color: #000000;resize:none;display:block;" ><!--{$arrForm[$key].value|escape}--></textarea>
					                    	<!--{/if}-->
											<!--{assign var=index value=$row.order_id}-->            
					                    	<!--{assign var=key value="insertdata_two_$index"}-->
					                    	<!--{assign var=key1 value="msg2_$index"}-->
					                    	<!--{if $arrForm[$key].value eq ""}-->
					                    		<div id="div_msg2_<!--{$index}-->" name="div_msg2_<!--{$index}-->" style="display:block;margin-top:2px; width: 546px; height: 35px ; color: #cccccc;border-right:1px solid;border-bottom:1px solid;border-left-style:groove;border-top-style:groove; "  onclick="div_click(this, <!--{$index}-->, 'two');">
													<label id="lbl_msg2_<!--{$index}-->" name="lbl_msg2_<!--{$index}-->" style="color: #999999;"><!--{$arrForm.$key1.value|default:$arrInsertTwoDetail[$first_template_id]}--></label>
												</div>
					                    		<textarea  name="<!--{$key}-->" onfocus="process_focus(this, '<!--{$index}-->', '2')"  onblur="process_blur(this, '<!--{$index}-->', '2')" style="margin-top:2px; position: relative;width: 550px; height: 35px;color: #999999;resize:none; visibility:hidden; display:none;" ></textarea>
					                    	<!--{else}-->
					                    		<div id="div_msg2_<!--{$index}-->" name="div_msg2_<!--{$index}-->" style="display:none;margin-top:2px; width: 546px; height: 35px ; color: #cccccc;border-right:1px solid;border-bottom:1px solid;border-left-style:groove;border-top-style:groove;"  onclick="div_click(this, <!--{$index}-->, 'two');">
													<label id="lbl_msg2_<!--{$index}-->" name="lbl_msg2_<!--{$index}-->" style="color: #000000;"><!--{$arrForm[$key].value|escape}--></label>
												</div>
					                    		<textarea name="<!--{$key}-->" onfocus="process_focus(this, '<!--{$index}-->', '2')"  onblur="process_blur(this, '<!--{$index}-->', '2')" style="margin-top:2px; position: relative;width: 550px; height: 35px;color: #000000; resize:none;display:block;" ><!--{$arrForm[$key].value|escape}--></textarea>
					                    	<!--{/if}-->
                                            <!--//::N00026-->
                                            <!--{assign var=index value=$row.order_id}-->            
					                    	<!--{assign var=key value="insertdata_three_$index"}-->
					                    	<!--{assign var=key1 value="msg3_$index"}-->
					                    	<!--{if $arrForm[$key].value eq ""}-->
					                    		<div id="div_msg3_<!--{$index}-->" name="div_msg3_<!--{$index}-->" style="display:block;margin-top:2px; width: 546px; height: 35px ; color: #cccccc;border-right:1px solid;border-bottom:1px solid;border-left-style:groove;border-top-style:groove; "  onclick="div_click(this, <!--{$index}-->, 'three');">
													<label id="lbl_msg3_<!--{$index}-->" name="lbl_msg3_<!--{$index}-->" style="color: #999999;"><!--{$arrForm.$key1.value|default:$arrInsertThreeDetail[$first_template_id]}--></label>
												</div>
					                    		<textarea  name="<!--{$key}-->" onfocus="process_focus(this, '<!--{$index}-->', '3')"  onblur="process_blur(this, '<!--{$index}-->', '3')" style="margin-top:2px; position: relative;width: 550px; height: 35px;color: #999999;resize:none; visibility:hidden; display:none;" ></textarea>
					                    	<!--{else}-->
					                    		<div id="div_msg3_<!--{$index}-->" name="div_msg3_<!--{$index}-->" style="display:none;margin-top:2px; width: 546px; height: 35px ; color: #cccccc;border-right:1px solid;border-bottom:1px solid;border-left-style:groove;border-top-style:groove;"  onclick="div_click(this, <!--{$index}-->, 'three');">
													<label id="lbl_msg3_<!--{$index}-->" name="lbl_msg3_<!--{$index}-->" style="color: #000000;"><!--{$arrForm[$key].value|escape}--></label>
												</div>
					                    		<textarea name="<!--{$key}-->" onfocus="process_focus(this, '<!--{$index}-->', '3')"  onblur="process_blur(this, '<!--{$index}-->', '3')" style="margin-top:2px; position: relative;width: 550px; height: 35px;color: #000000; resize:none;display:block;" ><!--{$arrForm[$key].value|escape}--></textarea>
					                    	<!--{/if}-->

                                            <!--{assign var=index value=$row.order_id}-->            
					                    	<!--{assign var=key value="insertdata_four_$index"}-->
					                    	<!--{assign var=key1 value="msg4_$index"}-->
					                    	<!--{if $arrForm[$key].value eq ""}-->
					                    		<div id="div_msg4_<!--{$index}-->" name="div_msg4_<!--{$index}-->" style="display:block;margin-top:2px; width: 546px; height: 35px ; color: #cccccc;border-right:1px solid;border-bottom:1px solid;border-left-style:groove;border-top-style:groove; "  onclick="div_click(this, <!--{$index}-->, 'four');">
													<label id="lbl_msg4_<!--{$index}-->" name="lbl_msg4_<!--{$index}-->" style="color: #999999;"><!--{$arrForm.$key1.value|default:$arrInsertFourDetail[$first_template_id]}--></label>
												</div>
					                    		<textarea  name="<!--{$key}-->" onfocus="process_focus(this, '<!--{$index}-->', '4')"  onblur="process_blur(this, '<!--{$index}-->', '4')" style="margin-top:2px; position: relative;width: 550px; height: 35px;color: #999999;resize:none; visibility:hidden; display:none;" ></textarea>
					                    	<!--{else}-->
					                    		<div id="div_msg4_<!--{$index}-->" name="div_msg4_<!--{$index}-->" style="display:none;margin-top:2px; width: 546px; height: 35px ; color: #cccccc;border-right:1px solid;border-bottom:1px solid;border-left-style:groove;border-top-style:groove;"  onclick="div_click(this, <!--{$index}-->, 'four');">
													<label id="lbl_msg4_<!--{$index}-->" name="lbl_msg4_<!--{$index}-->" style="color: #000000;"><!--{$arrForm[$key].value|escape}--></label>
												</div>
					                    		<textarea name="<!--{$key}-->" onfocus="process_focus(this, '<!--{$index}-->', '4')"  onblur="process_blur(this, '<!--{$index}-->', '4')" style="margin-top:2px; position: relative;width: 550px; height: 35px;color: #000000; resize:none;display:block;" ><!--{$arrForm[$key].value|escape}--></textarea>
					                    	<!--{/if}-->

                                            <!--//::N00026-->
										</td>
									</tr>
									<tr>
										<td bgcolor="#f2f1ec" >ファイル添付</td>
										<td bgcolor="#ffffff"  align="left">
											<!--{assign var=index value=$row.order_id}-->            
					                    	<!--{assign var=key value="uploaded_hid1_$index"}-->
					                    	<!--{assign var=key1 value="uploaded_full_hid1_$index"}-->
					                    	
					                    	<!--{if $arrForm[$key].value eq ""}-->
											<a href="" id="uploaded_a1_<!--{$row.order_id}-->" target="_blank"></a>
											<!--{else}-->
											<a href="<!--{$smarty.const.SITE_URL}--><!--{$arrForm[$key].value}-->" id="uploaded_a1_<!--{$row.order_id}-->" target="_blank">添付ファイル</a>
											<!--{/if}-->
											<!-- {{<a href="<!--{$smarty.server.PHP_SELF|escape}-->" id="uploaded_a1_<!--{$row.order_id}-->" onclick="mailPopSubmit('./mail_photo_view.php?order_id=<!--{$row.order_id}-->','form2','mail_photo_view','<!--{$row.order_id}-->', '1'); return false;"></a>}} -->
											<input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
											<input type="hidden" name="<!--{$key1}-->" id="<!--{$key1}-->" value="<!--{$arrForm[$key1].value}-->">
											<table  border="0" cellspacing="1" cellpadding="0" summary=" ">
												<tr>
													<!--<td><input type="text" name= "upload_txt1_<!--{$index}-->" id = "upload_txt1_<!--{$index}-->"  disabled="disabled" style="width: 58px;background-color: #FFFFFF;" /></td>-->
													<!--<td><span name="upload_btn1_<!--{$index}-->" id="upload_btn1_<!--{$index}-->"></span></td>-->
                                                    <input type="hidden" name="upload_txt1_<!--{$index}-->" id = "upload_txt1_<!--{$index}-->" disabled="disabled" style="width: 58px;background-color: #FFFFFF;" />
                                                    <td><input type="file" name="upload_btn1_<!--{$index}-->" id="upload_btn1_<!--{$index}-->" data-order_id="<!--{$index}-->" data-idx="1"></td>
                                                    <td>
														<!-- <img onclick="fnModeSubmit('delete_upload', 'upload_index', '1_<!--{$index}-->')" name="delete_btn1_<!--{$index}-->" id="delete_btn1_<!--{$index}-->" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png',this)" src="<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" > -->
                                                        <img onclick="fnModeSubmit('delete_upload', 'upload_index', '1_<!--{$index}-->')" name="delete_btn1_<!--{$index}-->" id="delete_btn1_<!--{$index}-->" onMouseover="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png',this)" onMouseout="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png',this)" src="/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >
													</td>
												</tr>
											</table>
											<div name="upload_div1_<!--{$index}-->" id="upload_div1_<!--{$index}-->"></div>
										</td>
										
										<td bgcolor="#ffffff"  align="left">
											<!--{assign var=index value=$row.order_id}-->            
					                    	<!--{assign var=key value="uploaded_hid2_$index"}-->
					                    	<!--{assign var=key1 value="uploaded_full_hid2_$index"}-->
					                    	
					                    	<!--{if $arrForm[$key].value eq ""}-->
											<a href="" id="uploaded_a2_<!--{$index}-->" target="_blank"></a>
											<!--{else}-->
											<a href="<!--{$smarty.const.SITE_URL}--><!--{$arrForm[$key].value}-->" id="uploaded_a2_<!--{$index}-->" target="_blank">添付ファイル</a>
											<!--{/if}-->
											<input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
											<input type="hidden" name="<!--{$key1}-->" id="<!--{$key1}-->" value="<!--{$arrForm[$key1].value}-->">
											<table border="0" cellspacing="1" cellpadding="0" summary=" ">
												<tr>
													<!--<td><input type="text" name = "upload_txt2_<!--{$index}-->" id = "upload_txt2_<!--{$index}-->" disabled="disabled" style="width: 58px;background-color: #FFFFFF;" /></td>-->
													<!--<td><span name="upload_btn2_<!--{$index}-->" id="upload_btn2_<!--{$index}-->"></span></td>--> 
                                                    <input type="hidden" name = "upload_txt2_<!--{$index}-->" id = "upload_txt2_<!--{$index}-->" disabled="disabled" style="width: 58px;background-color: #FFFFFF;" />
													<td><input type="file" name="upload_btn2_<!--{$index}-->" id="upload_btn2_<!--{$index}-->" data-order_id="<!--{$index}-->" data-idx="2"></td>

													<td>
														<!-- <img onclick="fnModeSubmit('delete_upload', 'upload_index', '2_<!--{$index}-->')" name="delete_btn2_<!--{$index}-->" id="delete_btn2_<!--{$index}-->" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png',this)" src="<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" > -->
                                                        <img onclick="fnModeSubmit('delete_upload', 'upload_index', '2_<!--{$index}-->')" name="delete_btn2_<!--{$index}-->" id="delete_btn2_<!--{$index}-->" onMouseover="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png',this)" src="/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >
													</td>
												</tr>
											</table>
											<div name="upload_div2_<!--{$index}-->" id="upload_div2_<!--{$index}-->"></div>
										</td>
										<td bgcolor="#ffffff"  align="left">
											<!--{assign var=index value=$row.order_id}-->            
					                    	<!--{assign var=key value="uploaded_hid3_$index"}-->
					                    	<!--{assign var=key1 value="uploaded_full_hid3_$index"}-->
					                    	
					                    	<!--{if $arrForm[$key].value eq ""}-->
											<a href="" id="uploaded_a3_<!--{$index}-->" target="_blank"></a>
											<!--{else}-->
											<a href="<!--{$smarty.const.SITE_URL}--><!--{$arrForm[$key].value}-->" id="uploaded_a3_<!--{$index}-->" target="_blank">添付ファイル</a>
											<!--{/if}-->
											<input type="hidden" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->">
											<input type="hidden" name="<!--{$key1}-->" id="<!--{$key1}-->" value="<!--{$arrForm[$key1].value}-->">
											<table border="0" cellspacing="1" cellpadding="0" summary=" ">
												<tr>
													<!--<td><input type="text" name = "upload_txt3_<!--{$index}-->" id = "upload_txt3_<!--{$index}-->" disabled="disabled" style="width: 58px;background-color: #FFFFFF;" /></td>-->
													<!--<td><span name="upload_btn3_<!--{$index}-->" id="upload_btn3_<!--{$index}-->"></span></td>-->
                                                    <input type="hidden" name = "upload_txt3_<!--{$index}-->" id = "upload_txt3_<!--{$index}-->" disabled="disabled" style="width: 58px;background-color: #FFFFFF;" />
													<td><input type="file" name="upload_btn3_<!--{$index}-->" id="upload_btn3_<!--{$index}-->" data-order_id="<!--{$index}-->" data-idx="3"></td>
													<td>
														<!-- <img onclick="fnModeSubmit('delete_upload', 'upload_index', '3_<!--{$index}-->')" name="delete_btn3_<!--{$index}-->" id="delete_btn3_<!--{$index}-->" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png',this)" src="<!--{$TPL_DIR}-->img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" > -->
                                                        <img onclick="fnModeSubmit('delete_upload', 'upload_index', '3_<!--{$index}-->')" name="delete_btn3_<!--{$index}-->" id="delete_btn3_<!--{$index}-->" onMouseover="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_2.png',this)" onMouseout="chgImgImageSubmit('/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png',this)" src="/user_data/packages/wanpi/img/button/ButtonDelete_51x22_1.png" width="51" height="22" alt="削除する" border="0" >
													</td>
												</tr>
											</table>
											<div name="upload_div3_<!--{$index}-->" id="upload_div3_<!--{$index}-->"></div>
										</td>
									</tr>
								</table>
								<!-- </div> -->
								</td>
							</tr>
							
							<!--{/foreach}-->
						</table>
						
						 



<!-- KHS ADD 2014.3.16                                       -->
        <div class="btn">
            <div class="btn-area">
                <ul>
                    <li><a class="btn-action" href="javascript:;" onclick="eccube.changeAction('<!--{$smarty.const.ADMIN_ORDER_URLPATH}-->mail_sending.php'); eccube.setModeAndSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
                    <li><a class="btn-action" href="javascript:;" onclick="fnMailSend(); "><span class="btn-next">メール送信</span></a></li>
                </ul>
            </div>
        </div>
<!--KHS END-->
</form>
<!--★★メインコンテンツ★★-->		
</div>
