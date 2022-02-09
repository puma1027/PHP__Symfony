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
class LC_Page_Selection_Assistance extends LC_Page_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        // 開始時刻を設定する。
        $this->timeStart = microtime(true);

        $this->tpl_authority = $_SESSION['authority'];

        // ディスプレイクラス生成
        $this->objDisplay = new SC_Display_Ex();
    	$this->tpl_force_device = $this->objDisplay->prepareProcess();

		if (!$this->skip_load_page_layout) {
            $layout = new SC_Helper_PageLayout_Ex();
            $layout->sfGetGuidePageLayout($this, false, $_SERVER['SCRIPT_NAME'],
                                     $this->objDisplay->detectDevice());
        }

        // スーパーフックポイントを実行.
        $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
        $objPlugin->doAction('LC_Page_preProcess', array($this));

        // 店舗基本情報取得
        $this->arrSiteInfo = SC_Helper_DB_Ex::sfGetBasisData();

        // トランザクショントークンの検証と生成
        $this->doValidToken();
        $this->setTokenTo();

        // ローカルフックポイントを実行.
        $this->doLocalHookpointBefore($objPlugin);

		$this->tpl_column_num = 3;
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {

		parent::process();

        $this->arrProductCount = $this->lfGetProductCount();

        $objQuery = new SC_Query();
        //---- 全データ取得
        //$sql = "SELECT * FROM dtb_model ORDER BY model_id";
        //$sql = "SELECT * FROM dtb_model ORDER BY height";
        //$sql = "SELECT * FROM dtb_model ORDER BY name";
        $sql = "SELECT * FROM dtb_model ORDER BY type,create_date DESC";
        $arrRet = $objQuery->getAll($sql);

        $this->arrModel = $arrRet;

        $this->sendResponse();

    }

    function lfGetProductCount(){
        $objQuery = new SC_Query();
        $result = array();
        $result['onepiece_count'] = $objQuery->count("dtb_products", "product_type = ? and status = ? and del_flg = 0", array(ONEPIECE_PRODUCT_TYPE, 1));
        $result['dress_count'] = $objQuery->count("dtb_products", "product_type in (?, ?, ?, ?) and status = ? and del_flg = 0", array(DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));
        $sql = "select sum(womens_review_count) from dtb_products where product_type in (?, ?, ?, ?, ?) and status = ? and del_flg = 0";
        $result['women_review_count'] = $objQuery->getone($sql, array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));

        return $result;
    }

}


// }}}
// {{{ generate page

$objPage = new LC_Page_Selection_Assistance();
$objPage->init();
$objPage->process();


?>
