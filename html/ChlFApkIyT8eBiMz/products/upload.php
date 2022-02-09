<?php
require_once("../../require.php");
//require_once '../require.php';
// die( "qqqqqqqqqqqqqqqqqqqq");          
// ユーザー別のフォルダを作成する。
$creator_id = $_POST['creator_id'];
$order_id = $_POST['image_id'];
if(empty($creator_id)){
	echo "qqqqqqqqqqqqqqqqqqqq"; 
	return;
}

$upload_path = INSPECT_IMAGE_DIR;
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
//$temporoy_file = IMAGE_TEMP_DIR.$filename;
$temporoy_file = IMAGE_TEMP_REALDIR.$filename;

move_uploaded_file($temp_file, $temporoy_file);

SC_Image::MakeThumb($temporoy_file, $temp_path, INSPECT_IMAGE_WIDTH, INSPECT_IMAGE_HEIGHT);

@unlink($temporoy_file);

echo $upload_path.';'.$full_filename.';'.SITE_URL;

?>
