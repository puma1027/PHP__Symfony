<?php
/**
 * ルミーズ決済モジュール・定期購買　編集画面用
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version remise_ac_order_edit.php,v 3.0
 *
 */

require_once '../require.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac_Ex/LC_Page_Admin_Order_Remise_AC_Edit_Ex.php';

if ($_GET["opt"] == "quit_complete"
 || $_GET["opt"] == "quit_return"
 || $_GET["opt"] == "already_quit"
 || $_GET["opt"] == "quit_notfound"
 || $_GET["opt"] == "error") {
    $_REQUEST["mode"] = "pre_edit";
    $_REQUEST["order_id"] = $_SESSION["order_id"];
    $_REQUEST["search_page_max"] = $_SESSION["search_page_max"];
}

$objPage = new LC_Page_Admin_Order_Remise_AC_Edit_Ex();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
?>
