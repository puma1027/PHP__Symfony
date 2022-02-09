<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2013 REMISE Corp. All Rights Reserved.
 *
 * http://www.remise.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once '../require.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac/LC_Page_Mypage_Remise_AC.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac/LC_Page_Admin_Remise_AC.php';

if ($_GET['mode'] == 'admin_quit' || $_SESSION['mode'] == 'admin_quit') {
    $objPage = new LC_Page_Admin_Remise_AC(AC_REMISE_REFUSAL);
}
else if ($_POST['mode'] == 'quit') {
    $_SESSION['order_id'] = $_POST['order_id'];
    $objPage = new LC_Page_Mypage_Remise_AC(AC_REMISE_REFUSAL);
}
else {
    $objPage = new LC_Page_Mypage_Remise_AC(AC_REMISE_REFUSAL);
}

register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
