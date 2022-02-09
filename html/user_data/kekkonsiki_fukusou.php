<?php
// {{{ requires
require_once "../require.php";
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * ユーザーカスタマイズ用のページクラス
 *
 * 管理画面から自動生成される
 *
 * @package Page
 */
class LC_Page_User extends LC_Page_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
		  parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objQuery = new SC_Query_Ex();

        $sql = "SELECT * FROM vw_ranking_sptop where aetas >= ?";
        $arrRet = $objQuery->getAll($sql, array("20"));
        $arrRet20 = array();
        
        for ($i = 0; $i <= 8; $i++){
          // add ishibashi 20220125
          //$arrRet20[$i] = $arrRet[$i];
          $arrRet20[$i] = SC_Utils_Ex::productReplaceWebp($arrRet[$i]);
        }

        $arrRet30 = array();
        $key30 = array_search(30, array_column($arrRet, 'aetas'));
        for ($i = $key30; $i <= $key30 + 8; $i++){
          // add ishibashi 20220125
          //$arrRet30[$i] = $arrRet[$i]; 
          $arrRet30[$i] = SC_Utils_Ex::productReplaceWebp($arrRet[$i]);
        } 
        
        $arrRet40 = array();
        $key40 = array_search(40, array_column($arrRet, 'aetas'));
        for ($i = $key40; $i <= $key40 + 8; $i++){
          // add ishibashi 20220125
          //$arrRet40[$i] = $arrRet[$i]; 
          $arrRet40[$i] = SC_Utils_Ex::productReplaceWebp($arrRet[$i]);
        } 
        
        $arrRet50 = array();
        $key50 = array_search(50, array_column($arrRet, 'aetas'));
        for ($i = $key50; $i <= $key50 + 8; $i++){
          // add ishibashi 20220125
          //$arrRet50[$i] = $arrRet[$i];
          $arrRet50[$i] = SC_Utils_Ex::productReplaceWebp($arrRet[$i]);
        } 

        $this->arrRank20 = $arrRet20;
        $this->arrRank30 = $arrRet30;
        $this->arrRank40 = $arrRet40;
        $this->arrRank50 = $arrRet50;

        // add ishibashi 20220125
        $this->scUtilsObj = new SC_Utils;

		parent::process();
        $this->sendResponse();

    }

    /**
     * デストラクタ.
     *
     * @return void*/
    function destroy() {
        parent::destroy();
    }

}

// }}}
// {{{ generate page

$objPage = new LC_Page_User();
$objPage->init();
$objPage->process();


?>
