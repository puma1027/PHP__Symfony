<?php 

//$hostname = "real-db-20210309.cwihh2qropry.ap-northeast-1.rds.amazonaws.com";
$hostname = "test-202107.cwihh2qropry.ap-northeast-1.rds.amazonaws.com";
$dbname = "wanpi_db_201603";
$user = "f01-rumw";
$password = "dnt4tkwd";
$port = 5432;

$conn = "host = $hostname dbname = $dbname user = $user password = $password port = $port";

$link = pg_connect($conn);

if (!$link) {
    die('connection error ... '.pg_last_error());
} else {
    //print('connection is ok!'.PHP_EOL);
}

$firstHeader = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
$secondHeader = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'.PHP_EOL;
$topPage = '<url>'.PHP_EOL.'  <loc>https://onepiece-rental.net/</loc>'.PHP_EOL;
$topPagePriority = '  <priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
$footer = '</urlset>';

$select  = ' select';
$select .= '   product_id';
$select .= ' from';
$select .= '   dtb_products';
$select .= ' where';
$select .= '   dtb_products.del_flg = 0';
$select .= '   and dtb_products.status = 1';
$select .= '   and dtb_products.haiki IS NULL';
$select .= ' order by';
$select .= '  product_id';
//$select .= ' limit 5';

$result = pg_query($select);
if (!$result) {
    pg_close($link);
    die('query is die.'.pg_last_error());
}

$close_flag = pg_close($link);
if ($close_flag) {
    //print('disconnected.'.PHP_EOL);
}

// file rename
$fileName = 'sitemap.xml';
$backName = 'sitemap.xml.bak';
if (!rename($fileName, $backName)){
    print('file rename failed.');
}

$handle = fopen($fileName, 'w');
if(!$handle){
    die('file open error ... '.PHP_EOL);
}

fwrite($handle, $firstHeader);
fwrite($handle, $secondHeader);
fwrite($handle, $topPage);
for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
    fwrite($handle, '<url>'.PHP_EOL);
    $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
    fwrite($handle, '  <loc>'.'https://onepiece-rental.net/products/detail.php?product_id='.$rows['product_id'].'</loc>'.PHP_EOL);
    fwrite($handle, '</url>'.PHP_EOL);
}

$fileNameBase = 'sitemap_base.xml';
$lines = file($fileNameBase);
foreach ($lines as $line) {
  fwrite($handle, '<url>'.PHP_EOL);
  fwrite($handle, '  '.$line);
  fwrite($handle, '</url>'.PHP_EOL);
}

fwrite($handle, $footer);

fclose($handle);
//unlink($backName);

print('done.'.PHP_EOL);

