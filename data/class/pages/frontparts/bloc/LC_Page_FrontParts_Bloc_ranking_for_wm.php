<?php

// {{{ requires
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * Product_List のページクラス.
 *
 * @package Page
 */
class LC_Page_FrontParts_Bloc_ranking_for_wm extends LC_Page_FrontParts_Bloc {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $bloc_file = 'bloc/ranking_list_for_wm.tpl';
        $this->setTplMainpage($bloc_file);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objQuery = new SC_Query_Ex();

/*
        $sql = "SELECT * FROM vw_ranking_sptop where aetas=? limit 9";
        $arrRet20 = $objQuery->getAll($sql, array("20"));
        $arrRet30 = $objQuery->getAll($sql, array("30"));
        $arrRet40 = $objQuery->getAll($sql, array("40"));
        $arrRet50 = $objQuery->getAll($sql, array("50"));
*/
        $sql = "SELECT to_json(vw_ranking_sptop) from vw_ranking_sptop where aetas >= ?"
        //$sql = "SELECT * FROM vw_ranking_sptop where aetas >= ?";
        $arrRet = $objQuery->getAll($sql, array("20"));

        $arrRet20 = array();
        
        for ($i = 0; $i <= 8; $i++){
          $arrRet20[$i] = $arrRet[$i];
        } 
        
        $arrRet30 = array();
        $key30 = array_search(30, array_column($arrRet, 'aetas'));
        for ($i = $key30; $i <= $key30 + 8; $i++){
          $arrRet30[$i] = $arrRet[$i]; 
        } 
        
        $arrRet40 = array();
        $key40 = array_search(40, array_column($arrRet, 'aetas'));
        for ($i = $key40; $i <= $key40 + 8; $i++){
          $arrRet40[$i] = $arrRet[$i]; 
        } 
        
        $arrRet50 = array();
        $key50 = array_search(50, array_column($arrRet, 'aetas'));
        for ($i = $key50; $i <= $key50 + 8; $i++){
          $arrRet50[$i] = $arrRet[$i]; 
        } 


        //$this->arrRank20 = $arrRet20;
        $this->arrRank30 = $arrRet30;
        $this->arrRank40 = $arrRet40;
        $this->arrRank50 = $arrRet50;

        $this->sendResponse();

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
