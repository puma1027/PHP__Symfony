<?php

// {{{ requires
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * Product_List のページクラス.
 *
 * @package Page
 */
class LC_Page_FrontParts_Bloc_ranking_list_for_age extends LC_Page_FrontParts_Bloc {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        //$bloc_file = 'bloc/ranking_list_for_age.tpl';
        //$this->setTplMainpage($bloc_file);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objQuery = new SC_Query_Ex();

        $sql = "SELECT * FROM vw_product_ranking_count where aetas=? limit 12";
        $arrRet20 = $objQuery->getAll($sql, array("20"));
        $arrRet30 = $objQuery->getAll($sql, array("30"));
        $arrRet40 = $objQuery->getAll($sql, array("40"));
        $arrRet50 = $objQuery->getAll($sql, array("50"));

        foreach ($arrRet20 as $key => $value)
        {
            $arrRet20[$key] = SC_Utils_Ex::productReplaceWebp($value);
            if ($arrRet20[$key]['round'] == 5500) { $arrRet20[$key]['round'] += 3480; }
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

        $this->sendResponse();
/*
        $sql = "SELECT * FROM vw_ranking_sptop where aetas >= ?";
        $arrRet = $objQuery->getAll($sql, array("20"));
        $arrRet20 = array();
        if( $key20 !== false )
        {
            for ($i = 0; $i <= 8; $i++){
              $arrRet20[$i] = $arrRet[$i];
            } 
        }

        $arrRet30 = array();
        $key30 = array_search(30, array_column($arrRet, 'aetas'));
        if( $key30 !== false )
        {
            for ($i = $key30; $i <= $key30 + 8; $i++){
              $arrRet30[$i] = $arrRet[$i]; 
            } 
        }

        $arrRet40 = array();
        $key40 = array_search(40, array_column($arrRet, 'aetas'));
        if( $key40 !== false )
        {
            for ($i = $key40; $i <= $key40 + 8; $i++){
              $arrRet40[$i] = $arrRet[$i]; 
            } 
        }

        $arrRet50 = array();
        $key50 = array_search(50, array_column($arrRet, 'aetas'));
        if( $key50 !== false )
        {
            for ($i = $key50; $i <= $key50 + 8; $i++){
              $arrRet50[$i] = $arrRet[$i]; 
            } 
        }

        $this->arrRank20 = $arrRet20;
        $this->arrRank30 = $arrRet30;
        $this->arrRank40 = $arrRet40;
        $this->arrRank50 = $arrRet50;

        $this->sendResponse();
*/
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
}

?>
