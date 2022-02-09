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

if ($_POST['mode'] == 'update') {
    $_SESSION['order_id'] = $_POST['order_id'];
}

$objPage = new LC_Page_Mypage_Remise_AC(AC_REMISE_UPDATE);
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
