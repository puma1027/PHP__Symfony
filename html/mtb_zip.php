<?php

$conn = pg_connect ("host=localhost port=5664 dbname=wanpi_db_ver2131 user=fs_eccube_user password=efqruvWX45");

processAutoIncrement($conn, "mtb_zip", "zip_id");

function processAutoIncrement($connection, $table_name, $autoincrement_column){
	$result = pg_query($connection, "SELECT * FROM ".$table_name);

	if (!$result) {
		pg_close($connection);

		echo "An error occured.\n";
		exit;
	}
	
	// select table data
	$table_array = pg_fetch_all($result);

	
	$counter = 0;
	$filename = '/virtual/www/eccube/html/temp/mtb_zip.sql';
	$handle = fopen($filename, 'wb');

	// delete table data
	//pg_query($connection, "DELETE FROM ".$table_name);
		fwrite($handle, "DELETE FROM ".$table_name . ";\n");
	// insert data
	foreach($table_array as $key=>$value){
		$counter ++;
		$sql = "INSERT INTO ".$table_name." VALUES (";
		$column_order = 0;
		foreach($value as $key2=>$value2){
			if($key2 == $autoincrement_column)
				$insert_val = $counter;
			else
				$insert_val = str_replace("'", "\'", $value2);
			
			if ($insert_val == ''){
				$sql = $sql . "NULL";
			}else{
				$sql = $sql . "'".$insert_val."'";
			}
			
			$column_order ++;
			if($column_order < count($value))
				$sql .= ",";
		}
		$sql .= ")";
		fwrite($handle, $sql . ";\n");

		
//echo($sql."<br>");		
		//pg_query($connection, $sql);
		
	}
	//pg_query($connection, "ALTER TABLE ONLY ".$table_name." ADD CONSTRAINT ".$table_name."_pkey PRIMARY KEY (".$autoincrement_column.")");
	fwrite($handle, "ALTER TABLE ONLY ".$table_name." ADD CONSTRAINT ".$table_name."_pkey PRIMARY KEY (".$autoincrement_column.")" . ";\n");
	fclose ($handle);

	echo("ok");
}

?>