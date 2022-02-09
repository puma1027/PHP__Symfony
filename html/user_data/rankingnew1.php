<?php
require_once '../require.php';
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
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        #parent::process();
        #$this->action();

        $objQuery = new SC_Query();
        //---- 全データ取得
        $sql = "SELECT * FROM vw_product_ranking_count where aetas=? limit 15";
        $arrRet20 = $objQuery->getAll($sql, array("20"));
        $arrRet30 = $objQuery->getAll($sql, array("30"));
        $arrRet40 = $objQuery->getAll($sql, array("40"));
        $arrRet50 = $objQuery->getAll($sql, array("50"));

        //コーデセットの価格修正
        foreach ($arrRet20 as $key => $value)
        {
            $arrRet20[$key] = SC_Utils_Ex::productReplaceWebp($value);
            if ($arrRet20[$key]['round'] == 5500){ $arrRet20[$key]['round'] += 3480; }
        }

        foreach ($arrRet30 as $key => $value)
        {
            $arrRet30[$key] = SC_Utils_Ex::productReplaceWebp($value);
            if ($arrRet30[$key]['round'] == 5500){ $arrRet30[$key]['round'] += 3480; }
        }

        foreach ($arrRet40 as $key => $value)
        {
            $arrRet40[$key] = SC_Utils_Ex::productReplaceWebp($value);
            if ($arrRet40[$key]['round'] == 5500){ $arrRet40[$key]['round'] += 3480; }
        }

        foreach ($arrRet50 as $key => $value)
        {
            $arrRet50[$key] = SC_Utils_Ex::productReplaceWebp($value);
            if ($arrRet50[$key]['round'] == 5500){ $arrRet50[$key]['round'] += 3480; }
        }

        $this->arrRank20 = $arrRet20;
        $this->arrRank30 = $arrRet30;
        $this->arrRank40 = $arrRet40;
        $this->arrRank50 = $arrRet50;

        // add ishibashi 20220125
        $this->scUtilsObj = new SC_Utils;

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
