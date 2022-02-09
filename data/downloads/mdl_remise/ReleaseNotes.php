=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
■EC-CUBEルミーズ決済モジュール 2.4.x対応版
　会社名：ルミーズ株式会社


【リリースノート】
---------------------------------------------------------------------
  2011/05/11  ・PHPエラー及びPostgreSQL利用時のエラー修正

　　・修正ファイル（ルミーズ決済モジュール）
　　　data/downloads/module/mdl_remise/LC_Page_Mdl_Remise_Config.php
      data/downloads/module/mdl_remise/LC_Page_Mdl_Remise_Payment.php
　　　data/downloads/module/mdl_remise/install.php
---------------------------------------------------------------------
  2011/04/26  ・カスタマイズ対象ファイルを、管理画面にてチェックすることで
自動コピーするよう編集

　　・修正ファイル（ルミーズ決済モジュール）
　　　data/downloads/module/mdl_remise/LC_Page_Mdl_Remise_Config.php
　　　data/downloads/module/mdl_remise/config.tpl
　　・追加ファイル（ルミーズ決済モジュール）
　　　data/downloads/module/mdl_remise/install.php
    ・移動ファイル（ルミーズ決済モジュール）
　　　data/class/pages/admin/order/LC_Page_Admin_Order_Edit.php
　　　data/class/pages/admin/order/LC_Page_Admin_Order.php
　　　data/class/pages/shopping/LC_Page_Shopping_Complete.php
　　　data/Smarty/templates/default/admin/order/edit.tpl
　　　data/Smarty/templates/default/admin/order/index.tpl
　　　data/Smarty/templates/default/admin/customer/edit.tpl
　　　html/user_data/remise_recv.php
      html/user_data/remise_extset.php
    ここまで、「mdl_remise/inst/」へ移動
      html/user_data/remise_payment.gif
      html/user_data/remise_payment_on.gif
    ここまで、「mdl_remise/img/」へ移動
---------------------------------------------------------------------

  2010/11/15  ・モバイルカード決済、結果通知にて決済情報書込を
　　　　　　　　完了通知にて書込に変更
		
　　・修正ファイル（ルミーズ決済モジュール）
　　　data/downloads/module/mdl_remise/remise_recv.php
　　　html/user_data/remise_recv.php


---------------------------------------------------------------------

　2010/11/8　・決済合否判定のバグを修正
             ・コンビニ決済、ローソン、セイコーマート、ペイジーの
　　　　　　　 払出番号２の出力に対応

　　・修正ファイル（ルミーズ決済モジュール）
　　　data/downloads/module/mdl_remise/remise_recv.php
      data/downloads/module/mdl_remise/LC_Page_Mdl_Remise_Payment.php
      html/user_data/remise_recv.php

---------------------------------------------------------------------

　2010/09/15　EC-CUBE Version 2.4.4リリースによる改修
　　・修正ファイル(本体)
　　　data/class/pages/admin/order/LC_Page_Admin_Order_Edit.php
　　　data/Smarty/templates/default/admin/order/edit.tpl
　　　data/class/pages/admin/order/LC_Page_Admin_Order.php
　　　data/Smarty/templates/default/admin/order/index.tpl
　　　data/Smarty/templates/default/admin/customer/edit.tpl
　　　data/class/pages/shopping/LC_Page_Shopping_Complete.php

---------------------------------------------------------------------

　2010/09/14　コンビニ決済選択時にEC-CUBEより送付されるThanksメールに
　　　　　　　おいて不要なHTMLタグが表示されてしまうバグを修正。

　　・修正ファイル(本体)
　　data/class/pages/shopping/LC_Page_Shopping_Complete.php
　・修正ファイル(ルミーズ決済モジュール)
　　 data/downloads/module/mdl_remise/LC_Page_Mdl_Remise_Payment.php
　　data/downloads/module/mdl_remise/remise_recv.php
　　html/user_data/remise_recv.php

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=