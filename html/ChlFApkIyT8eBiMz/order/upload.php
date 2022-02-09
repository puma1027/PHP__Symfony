<?php
require_once("../../require.php");

// ユーザー別のフォルダを作成する。
$creator_id = $_POST['creator_id'];
$order_id = $_POST['order_id'];
if(empty($creator_id)){
	echo "";
	return;
}

$upload_path = "upload/return_mail/";
$temp_path = HTML_REALDIR.$upload_path;
if(!file_exists($temp_path)){
	mkdir($temp_path, 0777);
}

$upload_path .= $creator_id."/";
$temp_path = HTML_REALDIR.$upload_path;
if(!file_exists($temp_path)){
	mkdir($temp_path, 0777);
}
 
$path_parts = pathinfo($_FILES["Filedata"]['name']);
$file_ext = strtolower($path_parts["extension"]);
$filename = $order_id."_".time().".".$file_ext;

$upload_path .= $filename;
$full_filename = $temp_path.$filename;

// ファイルの保存
$temp_file = $_FILES["Filedata"]['tmp_name'];
move_uploaded_file($temp_file, $full_filename);
chmod($temp_file, 0777);
chmod($full_filename, 0777);
echo $upload_path.';'.$full_filename.';'.SITE_URL;

?>
