<?php
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
        $this->tpl_column_num = 3;
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_SiteView();
        $objLayout = new SC_Helper_PageLayout_Ex();

         // レイアウトデザインを取得
         if (!$this->skip_load_page_layout) {
            $layout = new SC_Helper_PageLayout_Ex();
            $layout->sfGetGuidePageLayout($this, false, $_SERVER['SCRIPT_NAME'],
                                     $this->objDisplay->detectDevice());
        }

        // 画面の表示
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


// }}}
// {{{ generate page

$objPage = new LC_Page_User();
register_shutdown_function(array($objPage, "destroy"));
$objPage->init();
$objPage->process();


?>
