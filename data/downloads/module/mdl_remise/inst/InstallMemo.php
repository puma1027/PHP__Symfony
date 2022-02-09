モジュール設定時に以下のファイルをコピーする。
インストールするファイル等の情報はどうディレクトリ内のinstall.phpに書き込むこと！

①LC_Page_Admin_Order_Edit_Ex.php ->data/class_extends/page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php (orverwrite)　（※）

②edit.tpl -> data/Smarty/templates/admin/order/edit.tpl (orverwrite) （※）
  （2.13.2の変更対応のため、「edit(2.13.2_upper).tpl」というファイルを「2.13」ディレクトリ内に別途作成）

③remise_recv.php -> html/user_data/remise_recv.php(orverwrite)

④LC_Page_Admin_Order_Ex.php -> data/class_extends/page_extends/admin/order/LC_Page_Admin_Order_Ex.php (orverwrite)　（※）

⑤index.tpl -> data/Smarty/templates/admin/order/index.tpl (orverwrite) （※）

⑥LC_Page_Shopping_Complete_Ex.php -> data/class_extends/page_extends/shopping/LC_Page_Shopping_Complete_Ex.php(orverwrite)

⑦remise_receipt_mail.php -> Smarty/templates/default/mail_templates/remise_receipt_mail.tpl(orverwrite)
  (2.13系対応のため、remise_receipt_mail(2.13).tpl)

⑧img -> html/user_data/mdl_remise/img(orverwrite) ※フォルダ

⑨remise_order_cancel_mail.tpl -> Smarty/templates/default/mail_templates/remise_order_cancel_mail.tpl(orverwrite)


（ここまで、「決済画面カスタマイズ」のコピーファイル）


⑩LC_Page_Admin_Products_Product_Ex.php -> data/class_extends/page_extends/admin/products/LC_Page_Admin_Products_Product_Ex.php

⑪remise_ac_order.php -> html/admin/order/remise_ac_order.php(orverwrite)

⑫remise_ac_order_edit.php -> html/admin/order/remise_ac_order_edit.php(orverwrite)

⑬SC_CartSession_Ex.php -> data/class_extends/SC_CartSession_Ex.php

⑭product_class.tpl -> data/Smarty/templates/admin/order/product_class.tpl (orverwrite)　（※）

⑮LC_Page_Admin_Products_ProductClass_Ex.php -> data/class_extends/page_extends/admin/product/LC_Page_Admin_Products_ProductClass_Ex.php(orverwrite)

⑯ pdf_ac.php -> html/admin/order/pdf_ac.php

⑰ LC_Page_Shopping_Deliv_Ex.php -> data/class_extends/page_extends/shopping/LC_Page_Shopping_Deliv_Ex.php(orverwrite)

⑱ pc/history.tpl -> data/Smarty/templates/default/shopping/history.tpl　（※）

⑲ mbl/history.tpl -> data/Smarty/templates/mobile/shopping/history.tpl　（※）

20 smp/history.tpl -> data/Smarty/templates/sphone/shopping/history.tpl　（※）

21 pc/payment.tpl -> data/Smarty/templates/default/shopping/payment.tpl　（※）

22 mbl/payment.tpl -> data/Smarty/templates/mobile/shopping/payment.tpl　（※）

23 smp/payment.tpl -> data/Smarty/templates/sphone/shopping/payment.tpl　（※）

24 pc/confirm.tpl -> data/Smarty/templates/default/shopping/confirm.tpl　（※）

25 mbl/confirm.tpl -> data/Smarty/templates/mobile/shopping/confirm.tpl　（※）

26 smp/confirm.tpl -> data/Smarty/templates/sphone/shopping/confirm.tpl　（※）

27 remise_ac_card_update_mail.tpl -> data/Smarty/templates/default/mail_templates/remise_ac_card_update_mail.tpl

28 remise_ac_card_update_mail(mbl).tpl -> data/Smarty/templates/mobile/mail_templates/remise_ac_card_update_mail.tpl

29 remise_ac_order_refusal_mail.tpl -> data/Smarty/templates/default/mail_templates/remise_ac_order_refusal_mail.tpl

30 remise_ac_order_refusal_mail(mbl).tpl -> data/Smarty/templates/mobile/mail_templates/remise_ac_order_refusal_mail.tpl

31 remise_ac_refusal_another_mail.tpl -> data/Smarty/templates/default/mail_templates/remise_ac_refusal_another_mail.tpl


（ここまで、「定期購買カスタマイズ」のコピーファイル）


32 LC_Page_Cart_Ex.php -> data/class_extends/page_extends/cart/LC_Page_Cart_Ex.php(orverwrite)　（※）

33 LC_Page_Products_Detail_Ex.php -> data/class_extends/page_extends/products/LC_Page_Products_Detail_Ex.php(orverwrite)　（※）

34 LC_Page_Shopping_Confirm_Ex.php -> data/class_extends/page_extends/shopping/LC_Page_Shopping_Confirm_Ex.php(orverwrite)

35 LC_Page_Shopping_Ex.php -> data/class_extends/page_extends/shopping/LC_Page_Shopping_Ex.php(orverwrite)（※）

36 twoclick_help.php -> html/user_data/twoclick_help.php

（ここまで、「２クリックカスタマイズ」のコピーファイルで、なお、24～26は2クリックカスタマイズと共通です）

（※）印はバージョン間の差異解決のため、instディレクトリの対応バージョンディレクトリ内にあります。
