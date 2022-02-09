<?php
/*
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 */

/**
 * Modified by REMISE Corp.
 * @version remise_conveni.php,
 */
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Payment.php";

$objPage = new LC_Page_Mdl_Remise_Payment(PAY_REMISE_CONVENI);
$objPage->init();
$objPage->process();
?>
