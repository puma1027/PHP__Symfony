<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("../require.php");
require_once (MODULE_PATH . "mdl_remise/LC_Page_Mdl_Remise_Extset_Ex.php");

$objPage = new LC_Page_Mdl_Remise_Extset_Ex();
register_shutdown_function(array($objPage, "destroy"));
$objPage->init();
$objPage->process();
?>
