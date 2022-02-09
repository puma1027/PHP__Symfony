<?php
/**
 * ルミーズ決済モジュール・インストール処理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version install.php,v 3.0
 */

/**
 * インストール処理のクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class install
{
    var $install_files;

    /**
     * コンストラクタ.
     *
     * @param string $customize カスタマイズのため上書きする？
     * @return void
     */
    function install($customize)
    {
        $this->install_files = array(
            // 本体クラスファイル
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/LC_Page_Shopping_Complete_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Complete_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            // add start 2017/06/29
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/LC_Page_Shopping_Payment_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Payment_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            // add end 2017/06/29
            // その他
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_recv.php',
                "dest"      => HTML_REALDIR . 'user_data/remise_recv.php',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/img/',
                "dest"      => HTML_REALDIR . 'user_data/mdl_remise/img/',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_order_cancel_mail.tpl',
                "dest"      => SMARTY_TEMPLATES_REALDIR . 'default/mail_templates/remise_order_cancel_mail.tpl',
                "overwrite" => true,
                "backup"    => true
            ),
        );

        // 2.13以降　仕様の差異反映のため
        if (substr(ECCUBE_VERSION,0,4) == '2.11') {
            array_push($this->install_files,
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.11/LC_Page_Admin_Order_Edit_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.11/LC_Page_Admin_Order_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/admin/order/LC_Page_Admin_Order_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_receipt_mail.tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'default/mail_templates/remise_receipt_mail.tpl',
                    "overwrite" => true,
                    "backup"    => true
                )
            );
            if (ECCUBE_VERSION >= '2.11.2') {
                array_push($this->install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.11/edit(2.11.2_upper).tpl',
                        "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/edit.tpl',
                        "overwrite" => $customize,
                        "backup"    => true
                    ),
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.11/index(2.11.2_upper).tpl',
                        "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/index.tpl',
                        "overwrite" => $customize,
                        "backup"    => true
                    )
                );
            } else {
                array_push($this->install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.11/edit.tpl',
                        "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/edit.tpl',
                        "overwrite" => $customize,
                        "backup"    => true
                    ),
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.11/index.tpl',
                        "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/index.tpl',
                        "overwrite" => $customize,
                        "backup"    => true
                    )
                );
            }
        }
        else if (substr(ECCUBE_VERSION,0,4) == '2.12') {
            array_push($this->install_files,
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/LC_Page_Admin_Order_Edit_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/LC_Page_Admin_Order_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/admin/order/LC_Page_Admin_Order_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/edit.tpl',
                    "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/edit.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/index.tpl',
                    "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/index.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_receipt_mail.tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'default/mail_templates/remise_receipt_mail.tpl',
                    "overwrite" => true,
                    "backup"    => true
                )
            );
        } else {
            array_push($this->install_files,
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/LC_Page_Admin_Order_Edit_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/LC_Page_Admin_Order_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/admin/order/LC_Page_Admin_Order_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/index.tpl',
                    "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/index.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_receipt_mail(2.13).tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'default/mail_templates/remise_receipt_mail.tpl',
                    "overwrite" => true,
                    "backup"    => true
                )
            );
            if (ECCUBE_VERSION >= '2.13.2') {
                array_push($this->install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/edit(2.13.2_upper).tpl',
                        "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/edit.tpl',
                        "overwrite" => $customize,
                        "backup"    => true
                    )
                );
            } else {
                array_push($this->install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/edit.tpl',
                        "dest"      => TEMPLATE_ADMIN_REALDIR . 'order/edit.tpl',
                        "overwrite" => $customize,
                        "backup"    => true
                    )
                );
            }
        }
    }

    /**
     * ファイルコピー.
     *
     * @return void
     */
    function filecopy()
    {
        $objUtils = new SC_Utils();
        $errmsg = "";

        foreach ($this->install_files as $file) {
            $source = $file['source'];
            $dest = $file['dest'];
            $overwrite = $file['overwrite'];
            $backup = $file['backup'];

            // ファイルがないか、もしくはオーバーライト可の場合
            if (!file_exists($dest) || $overwrite) {
                if (is_dir($source)) {
                    error_log(print_r("destDir：".$dest,true)."\r\n","3","C:/xampp/log/debug.log");

                    // ディレクトリの場合
                    $objUtils->sfMakeDir($dest);
                    $backupfile = dirname($dest) . '/' . basename($dest) . '_' . date("Ymd") . '.backup';
                    if ($backup) {
                        //$objUtils->sfCopyDir($dest, $backupfile);
                        $this->copyFiles($dest, $backupfile);
                    }
                    // フォルダをコピー
                    //$objUtils->sfCopyDir($source, $dest, $message, true);
                    $this->copyFiles($source, $dest);
                } else {
                    $backupfile = $file['dest'] . '_' . date("Ymd") . '.backup';
                    // バックアップを作成(ファイルが異なる場合のみ)
                    if ($backup && (!file_exists($backupfile) || (file_exists($backupfile) && sha1_file($source) != sha1_file($backupfile)))) {
                        copy($dest, $backupfile);
                    }
                    // ファイルをコピー
                    if (is_writable($dest) || (is_writable(dirname($dest)) && !file_exists($dest))) {
                        copy($source, $dest);
                    } else {
                        $errmsg = $dest . "に書き込み権限を与えてください。";
                        break;
                    }
                }
            }
        }
        return $errmsg;
    }

    function setInstallFiles($val)
    {
        $this->install_files = $val;
    }

    function getInstallFiles()
    {
        return $this->install_files;
    }

    /**
     * ファイルの複写
     *
     * @param  string  $src  複写元
     * @param  string  $dst  複写先
     */
    protected function copyFiles($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);

        while (($file = readdir($dir)) !== false)
        {
            if ($file == '.' || $file == '..') continue;

            if (is_dir($src . '/' . $file)) {
                $this->copyFiles($src . '/' . $file, $dst . '/' . $file);
            } else {
                if(!copy($src . '/' . $file, $dst . '/' . $file))
                {
                    $copyBool = false;
                }
            }
        }

        closedir($dir);
    }

}
?>
