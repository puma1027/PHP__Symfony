
<?php
/**
* ルミーズ決済モジュール・インストール処理
*
* PHP versions 4 and 5
* @project REMISE Payment Module for EC-CUBE 2.4.x
* @package mdl_remise
* @author REMISE Corp. <tech@remise.jp>
* @copyright Copyright(C) REMISE Corp. All Rights Reserved.
* @version install.php,v1.3
*
*/

/**
* インストール処理のクラス.
*
* @package mdl_remise
* @author REMISE Corp. <tech@remise.jp>
* @version v 1.3
*/
class install{
    private  $install_files;

    /**
     * コンストラクタ.
     *
     * @param string $customize カスタマイズのため上書きする？
     * @return void
     */
    function install($customize) {
        $this->install_files  = array(
            /* 本体クラスファイル */
            array(
                "source" => MODULE_PATH . 'mdl_remise/inst/LC_Page_Admin_Order_Edit.php',
                "dest" => DATA_REALDIR . 'class/pages/admin/order/LC_Page_Admin_Order_Edit.php',
                "overwrite" => $customize,
                "backup" => true
            ),
            array(
                "source" => MODULE_PATH . 'mdl_remise/inst/LC_Page_Admin_Order.php',
                "dest" => DATA_REALDIR . 'class/pages/admin/order/LC_Page_Admin_Order.php',
                "overwrite" => $customize,
                "backup" => true
            ),
            array(
                "source" => MODULE_PATH . 'mdl_remise/inst/LC_Page_Shopping_Complete.php',
                "dest" => DATA_REALDIR . 'class/pages/shopping/LC_Page_Shopping_Complete.php',
                "overwrite" => $customize,
                "backup" => true
            ),
            /* 本体テンプレート */
            array(
                "source" => MODULE_PATH . 'mdl_remise/inst/edit.tpl',
                "dest" => TEMPLATE_ADMIN_DIR . 'order/edit.tpl',
                "overwrite" => $customize,
                "backup" => true
            ),
            array(
                "source" => MODULE_PATH . 'mdl_remise/inst/index.tpl',
                "dest" => TEMPLATE_ADMIN_DIR . 'order/index.tpl',
                "overwrite" => $customize,
                "backup" => true
            ),
            array(
                "source" => MODULE_PATH . 'mdl_remise/inst/edit2.tpl',
                "dest" => TEMPLATE_ADMIN_DIR . 'customer/edit.tpl',
                "overwrite" => $customize,
                "backup" => true
            ),
            /* その他 */
            array(
                "source" => MODULE_PATH . 'mdl_remise/inst/remise_extset.php',
                "dest" => HTML_REALDIR . "user_data/remise_extset.php",
                "overwrite" => true,
                "backup" => false
            ),
            array(
                "source" => MODULE_PATH . 'mdl_remise/inst/remise_recv.php',
                "dest" => HTML_REALDIR . "user_data/remise_recv.php",
                "overwrite" => true,
                "backup" => false
            ),
            /* 画像 */
            array(
                "source" => MODULE_PATH . 'mdl_remise/img/remise_payment.gif',
                "dest" => HTML_REALDIR . "user_data/remise_payment.gif",
                "overwrite" => true,
                "backup" => false
            ),
            array(
                "source" => MODULE_PATH . 'mdl_remise/img/remise_payment_on.gif',
                "dest" => HTML_REALDIR . "user_data/remise_payment_on.gif",
                "overwrite" => true,
                "backup" => false
            ),
        );
    }

    /**
     * ファイルコピー.
     *
     * @return void
     */
    function filecopy() {
        $errmsg = "";

        foreach ($this->install_files as $file) {
            $source = $file['source'];
            $dest = $file['dest'];
            $overwrite = $file['overwrite'];
            $backup = $file['backup'];
            $backupfile = $file['dest'] . '_' . date(".Ymd") . '.backup';
            //ファイルがないか、もしくはオーバーライト可の場合
            if(!file_exists($source) || $overwrite) {
                //バックアップを作成(ファイルが異なる場合のみ)
                if($backup && sha1_file($source) != sha1_file($backupfile)) {
                    copy($dest, $backupfile);
                }
                //ファイルをコピー
                if(is_writable($dest) || is_writable(dirname($dest))) {
                    copy($source, $dest);
                } else {
                    $errmsg = $dest;
                    break;
                }
            }
        }
        return $errmsg;
    }

    function setInstallFiles($val) {
        $this->install_files = $val;
    }

    function getInstallFiles() {
        return $this->install_files;
    }
}
?>