<?php
require_once 'require.php';
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * ユーザーカスタマイズ用のページクラス
 *
 * 管理画面から自動生成される
 *
 * @package Page
 */
class LC_Page_User extends LC_Page_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        //privacy.tpl
        $this->tpl_mainpage = "ibaragi.tpl";

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        //parent::process();
        //$this->action();
        $objQuery = new SC_Query();
        //---- 全データ取得
        $sql = "SELECT * FROM vw_product_ranking_count where aetas=? limit 15";
        $arrRet20 = $objQuery->getAll($sql, array("20"));
        $arrRet30 = $objQuery->getAll($sql, array("30"));
        $arrRet40 = $objQuery->getAll($sql, array("40"));
        $arrRet50 = $objQuery->getAll($sql, array("50"));

        $this->arrRank20 = $arrRet20;
        $this->arrRank30 = $arrRet30;
        $this->arrRank40 = $arrRet40;
        $this->arrRank50 = $arrRet50;
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
    }
}
$objPage = new LC_Page_User();
$objPage->init();
$objPage->process();

