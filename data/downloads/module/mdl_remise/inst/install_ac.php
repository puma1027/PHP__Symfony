<?php
/**
 * ルミーズ決済モジュール・定期購買機能インストール処理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version install_ac.php,v 3.1
 */

/**
 * 定期購買機能インストール処理のクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class install_ac
{
    var $install_files;

    /**
     * コンストラクタ.
     *
     * @param string $customize カスタマイズのため上書きする？
     * @return void
     */
    function install_ac($customize)
    {
        $this->install_files  = array(
            // 本体クラスファイル
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/LC_Page_Admin_Products_ProductClass_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/page_extends/admin/products/LC_Page_Admin_Products_ProductClass_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            // コピーファイル（バックアップを作成します）
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/LC_Page_Admin_Products_Product_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/page_extends/admin/products/LC_Page_Admin_Products_Product_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/LC_Page_Mypage_History_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/page_extends/mypage/LC_Page_Mypage_History_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/SC_CartSession_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/SC_CartSession_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/LC_Page_Shopping_Deliv_Ex.php',
                "dest"      => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Deliv_Ex.php',
                "overwrite" => $customize,
                "backup"    => true
            ),
            // その他
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_order.php',
                "dest"      => HTML_REALDIR . ADMIN_DIR . 'order/remise_ac_order.php',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_order_edit.php',
                "dest"      => HTML_REALDIR . ADMIN_DIR . 'order/remise_ac_order_edit.php',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/pdf_ac.php',
                "dest"      => HTML_REALDIR . ADMIN_DIR . 'order/pdf_ac.php',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/mail_ac.php',
                "dest"      => HTML_REALDIR . ADMIN_DIR . 'order/mail_ac.php',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_recv.php',
                "dest"      => HTML_REALDIR . 'user_data/remise_ac_recv.php',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_update.php',
                "dest"      => HTML_REALDIR . 'user_data/remise_ac_update.php',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_refusal.php',
                "dest"      => HTML_REALDIR . 'user_data/remise_ac_refusal.php',
                "overwrite" => true,
                "backup"    => false
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_card_update_mail.tpl',
                "dest"      => SMARTY_TEMPLATES_REALDIR . 'default/mail_templates/remise_ac_card_update_mail.tpl',
                "overwrite" => true,
                "backup"    => true
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_order_refusal_mail.tpl',
                "dest"      => SMARTY_TEMPLATES_REALDIR . 'default/mail_templates/remise_ac_order_refusal_mail.tpl',
                "overwrite" => true,
                "backup"    => true
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_card_update_mail(mbl).tpl',
                "dest"      => SMARTY_TEMPLATES_REALDIR . 'mobile/mail_templates/remise_ac_card_update_mail.tpl',
                "overwrite" => true,
                "backup"    => true
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_order_refusal_mail(mbl).tpl',
                "dest"      => SMARTY_TEMPLATES_REALDIR . 'mobile/mail_templates/remise_ac_order_refusal_mail.tpl',
                "overwrite" => true,
                "backup"    => true
            ),
            array(
                "source"    => MODULE_REALDIR . 'mdl_remise/inst/remise_ac_refusal_another_mail.tpl',
                "dest"      => SMARTY_TEMPLATES_REALDIR . 'default/mail_templates/remise_ac_refusal_another_mail.tpl',
                "overwrite" => true,
                "backup"    => true
            ),
        );
        // バージョン毎の本体テンプレート追加
        if (substr(ECCUBE_VERSION,0,4) == '2.12') {
            array_push($this->install_files,
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/pc/history.tpl',
                    "dest"      => TEMPLATE_REALDIR . 'mypage/history.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mbl/history.tpl',
                    "dest"      => MOBILE_TEMPLATE_REALDIR . 'mypage/history.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/smp/history.tpl',
                    "dest"      => SMARTPHONE_TEMPLATE_REALDIR . 'mypage/history.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/product_class.tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/products/product_class.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mail_ac.tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/order/mail_ac.tpl',
                    "overwrite" => true,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mail_ac_confirm.tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/order/mail_ac_confirm.tpl',
                    "overwrite" => true,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/pc/payment.tpl',
                    "dest"      => TEMPLATE_REALDIR  . 'shopping/payment.tpl',
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
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mbl/payment.tpl',
                    "dest"      => MOBILE_TEMPLATE_REALDIR . 'shopping/payment.tpl',
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
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/smp/payment.tpl',
                    "dest"      => SMARTPHONE_TEMPLATE_REALDIR . 'shopping/payment.tpl',
                    "overwrite" => $customize,
                   "backup"     => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/smp/confirm.tpl',
                    "dest"      => SMARTPHONE_TEMPLATE_REALDIR . 'shopping/confirm.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                )
            );
            if (preg_match('/\ [0-9]\.[0-9]+\.[0-2]/', ECCUBE_VERSION)) {
                array_push($this->install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/LC_Page_Admin_Order_Mail_AC.php',
                        "dest"      => MODULE_REALDIR . 'mdl_remise/class_ac/LC_Page_Admin_Order_Mail_AC.php',
                        "overwrite" => $customize,
                        "backup"    => true
                    ),
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mail_ac.tpl',
                        "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/order/mail_ac.tpl',
                        "overwrite" => true,
                        "backup"    => true
                    ),
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mail_ac_confirm.tpl',
                        "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/order/mail_ac_confirm.tpl',
                        "overwrite" => true,
                        "backup"    => true
                    )
                );
            } else {
                array_push($this->install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/LC_Page_Admin_Order_Mail_AC(2.12.3_upper).php',
                        "dest"      => MODULE_REALDIR . 'mdl_remise/class_ac/LC_Page_Admin_Order_Mail_AC.php',
                        "overwrite" => $customize,
                        "backup"    => true
                    ),
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mail_ac(2.12.3_upper).tpl',
                        "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/order/mail_ac.tpl',
                        "overwrite" => true,
                        "backup"    => true
                    ),
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.12/mail_ac_confirm(2.12.3_upper).tpl',
                        "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/order/mail_ac_confirm.tpl',
                        "overwrite" => true,
                        "backup"    => true
                    )
                );
            }
        } else {
            array_push($this->install_files,
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/pc/history.tpl',
                    "dest"      => TEMPLATE_REALDIR . 'mypage/history.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/mbl/history.tpl',
                    "dest"      => MOBILE_TEMPLATE_REALDIR . 'mypage/history.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/product_class.tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/products/product_class.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/mail_ac.tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/order/mail_ac.tpl',
                    "overwrite" => true,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/mail_ac_confirm.tpl',
                    "dest"      => SMARTY_TEMPLATES_REALDIR . 'admin/order/mail_ac_confirm.tpl',
                    "overwrite" => true,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/LC_Page_Admin_Order_Mail_AC.php',
                    "dest"      => MODULE_REALDIR . 'mdl_remise/class_ac/LC_Page_Admin_Order_Mail_AC.php',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/pc/payment.tpl',
                    "dest"      => TEMPLATE_REALDIR  . 'shopping/payment.tpl',
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
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/mbl/payment.tpl',
                    "dest"      => MOBILE_TEMPLATE_REALDIR . 'shopping/payment.tpl',
                    "overwrite" => $customize,
                    "backup"    => true
                ),
                array(
                    "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/smp/payment.tpl',
                    "dest"      => SMARTPHONE_TEMPLATE_REALDIR . 'shopping/payment.tpl',
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
                array_push($this->install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/smp/history(2.13.3_upper).tpl',
                        "dest"      => SMARTPHONE_TEMPLATE_REALDIR . 'mypage/history.tpl',
                        "overwrite" => $customize,
                        "backup"    => true
                    )
                );
            } else {
                array_push($this->install_files,
                    array(
                        "source"    => MODULE_REALDIR . 'mdl_remise/inst/2.13/smp/history.tpl',
                        "dest"      => SMARTPHONE_TEMPLATE_REALDIR . 'mypage/history.tpl',
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
        $this->install_files = $val;
    }

    function getInstallFiles()
    {
        return $this->install_files;
    }
}
?>
