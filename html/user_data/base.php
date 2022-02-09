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
        // 開始時刻を設定する。
 /*       $this->timeStart = microtime(true);

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

		$this->tpl_column_num = 3;*/
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {

        // add ishibashi 20220125
        $this->scUtilsObj = new SC_Utils;

		parent::process();
        $this->sendResponse();

    }

}


// }}}
// {{{ generate page

$objPage = new LC_Page_User();
$objPage->init();
$objPage->process();


?>
