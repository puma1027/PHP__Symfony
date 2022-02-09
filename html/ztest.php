<?php

$fileName = 'sitemap.xml';
$backName = 'sitemap.xml.bak';
rename($fileName, $backName);

$handle = fopen($fileName, 'w');
fwrite($handle, 'hoge');
fclose($handle);
