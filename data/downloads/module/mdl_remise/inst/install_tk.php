<?php
/**
 * ルミーズ決済モジュール・2クリック用ファイルインストール処理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.12.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version install_tk.php,v 3.0
 */

/**
 * インストール処理のクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class install_tk
{
    var $tk_install_files;

    /**
     * コンストラクタ.
     *
     * @param string $customize カスタマイズのため上書きする？
     * @return void
     */
    function install_tk($customize)
    {
        $this->tk_install_files  = array(
            // 本体クラスファイル
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/LC_Page_Shopping_Confirm_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Confirm_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/LC_Page_Shopping_Complete_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Complete_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            // その他ファイル
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/twoclick_help.php',
                "dest"      => HTML_REALDIR . 'user_data/twoclick_help.php',
                "overwrite" => true,
                "backup"    => false
            ),
        );
        if (substr(ECCUBE_VERSION,0,4) == '2.12') {
            array_push($this->tk_install_files,
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/LC_Page_Shopping_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/LC_Page_Cart_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/cart/LC_Page_Cart_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/LC_Page_Products_Detail_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/products/LC_Page_Products_Detail_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/pc/confirm.tpl',
                    "dest"      => TEMPLATE_REALDIR  . 'shopping/confirm.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mbl/confirm.tpl',
                    "dest"      => MOBILE_TEMPLATE_REALDIR . 'shopping/confirm.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/smp/confirm.tpl',
                    "dest"      => SMARTPHONE_TEMPLATE_REALDIR . 'shopping/confirm.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                )
            );
        } else {
            array_push($this->tk_install_files,
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/LC_Page_Shopping_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/LC_Page_Cart_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/cart/LC_Page_Cart_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/LC_Page_Products_Detail_Ex.php',
                    "dest"      => DATA_REALDIR . 'class_extends/page_extends/products/LC_Page_Products_Detail_Ex.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/pc/confirm.tpl',
                    "dest"      => TEMPLATE_REALDIR  . 'shopping/confirm.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/smp/confirm.tpl',
                    "dest"      => SMARTPHONE_TEMPLATE_REALDIR . 'shopping/confirm.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                )
            );
            if (ECCUBE_VERSION >= '2.13.3') {
                array_push($this->tk_install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/mbl/confirm(2.13.3_upper).tpl',
                        "dest"      => MOBILE_TEMPLATE_REALDIR . 'shopping/confirm.tpl',
                        "overwrite" => $customize,
                        "backup"    => true
                    )
                );
            } else {
                array_push($this->tk_install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/mbl/confirm.tpl',
                        "dest"      => MOBILE_TEMPLATE_REALDIR . 'shopping/confirm.tpl',
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

        foreach ($this->tk_install_files as $file) {
            $source = $file['source'];
            $dest = $file['dest'];
            $overwrite = $file['overwrite'];
            $backup = $file['backup'];

            // ファイルがないか、もしくはオーバーライト可の場合
            if (!file_exists($dest) || $overwrite) {
                if (is_dir($source)) {
                    // ディレクトリの場合
                    $objUtils->sfMakeDir($dest);
                    $backupfile = dirname($dest) . '/' . basename($dest) . '_' . date("Ymd") . '.backup';
                    if ($backup) {
                        $objUtils->sfCopyDir($dest, $backupfile);
                    }
                    // フォルダをコピー
                    $objUtils->sfCopyDir($source, $dest, $message, true);
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
        $this->tk_install_files = $val;
    }

    function getInstallFiles()
    {
        return $this->tk_install_files;
    }
}
?>
