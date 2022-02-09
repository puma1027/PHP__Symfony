<?php
/**
 * ルミーズ決済モジュール　定期購買一覧管理（拡張）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version LC_Page_Admin_Order_Remise_AC_Ex.php,v 3.0
 *
 */

require_once MODULE_REALDIR . 'mdl_remise/class_ac/LC_Page_Admin_Order_Remise_AC.php';

/**
 * 定期購買の受注一覧のクラス（拡張）.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class LC_Page_Admin_Order_Remise_AC_Ex extends LC_Page_Admin_Order_Remise_AC
{
    function LC_Page_Admin_Order_Remise_AC_Ex()
    {
        parent::LC_Page_Admin_Order_Remise_AC();
    }
}
?>
