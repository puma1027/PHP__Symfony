<?php

$conn = pg_connect ("host=localhost port=5664 dbname=wanpi_db_ver2131 user=fs_eccube_user password=efqruvWX45");

processAutoIncrement($conn, "dtb_order_detail", "order_detail_id");

function processAutoIncrement($connection, $table_name, $autoincrement_column){
	$result = pg_query($connection, "SELECT * FROM ".$table_name);

	if (!$result) {
		pg_close($connection);

		echo "An error occured.\n";
		exit;
	}
	$table_array = pg_fetch_all($result);
	
	$filename = '/virtual/www/eccube/html/temp/dtb_order_detail_update.sql';
	$handle = fopen($filename, 'wb');
	foreach($table_array as $key=>$value){
		$counter ++;
		$sql = "UPDATE ".$table_name." SET product_class_id = ";
                                    
        $pclassid="select product_class_id from dtb_products_class where product_id='".$value['product_id']."' AND product_code ='".$value['product_code']."'"; 
        $result = pg_query($connection, $pclassid);          
        $xx = pg_fetch_all($result);      
        $insert_val = is_null($xx[0]['product_class_id']) ? 0 : $xx[0]['product_class_id'];
        $sql = $sql . $insert_val . " WHERE order_detail_id = " . $value['order_detail_id'];
		fwrite($handle, $sql . ";\n");
	}

	fclose ($handle);

	echo("ok");
}

?>