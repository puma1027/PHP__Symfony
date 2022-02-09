<?php

//$self = pathinfo(__FILE__, PATHINFO_BASENAME);
//$log_file = dirname(dirname(dirname(__FILE__)))

$log_file = "/virtual/logs/error_log";
//$log_file_ren = "/virtual/logs/error_log_20140613";
//rename($log_file, $log_file_ren);

$filename = "/virtual/logs/error_log";

if (is_readable($filename)) {
    echo 'The file is readable';

    if (!$handle = fopen($filename, 'r')) {
         echo "Cannot open file ($filename)";
         exit;
    }

	while (!feof($handle)) {
	  $contents = fread($handle, 1024*1024);
	}
	fclose($handle);
    //echo "Success, wrote ($somecontent) to file ($filename)";
	print_r(nl2br($contents));

} else {
    echo 'The file is not readable';
}

exit();


?> 