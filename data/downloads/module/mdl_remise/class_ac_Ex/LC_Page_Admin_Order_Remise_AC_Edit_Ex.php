<?php
/**
 * ルミーズ決済モジュール　定期購買 受注管理（拡張）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version LC_Page_Admin_Order_Remise_AC_Edit_Ex.php,v 3.0
 *
 */

require_once MODULE_REALDIR . 'mdl_remise/class_ac/LC_Page_Admin_Order_Remise_AC_Edit.php';

/**
 * 定期購買　受注管理 のページクラス（拡張）.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class LC_Page_Admin_Order_Remise_AC_Edit_Ex extends LC_Page_Admin_Order_Remise_AC_Edit
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        parent::destroy();
    }

    function lfInitParam(&$objFormParam)
    {
        // 検索条件のパラメーターを初期化
        parent::lfInitParam($objFormParam);

        // 定期購買情報を追加
        $objFormParam->addParam('定期購買　メンバーID', 'plg_remiseautocharge_member_id');
        $objFormParam->addParam('定期購買　金額',       'plg_remiseautocharge_total');
        $objFormParam->addParam('定期購買　次回課金日', 'plg_remiseautocharge_next_date');
        $objFormParam->addParam('定期購買　課金間隔',   'plg_remiseautocharge_interval');
    }
}
?>
