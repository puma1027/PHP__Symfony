<?php
/*
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 */
require_once(realpath(dirname( __FILE__)) . "/LC_Page_Mdl_Remise_Payment.php");

$objPage = new LC_Page_Mdl_Remise_Payment(PAY_REMISE_CREDIT);
$objPage->init();
$objPage->process();
?>
