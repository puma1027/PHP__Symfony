ルミーズ決済モジュール (ルミーズ株式会社)
リリースノート

■ver3.0.14（2020/03/18）
・セイコーマートの支払方法改定のため、購入時の案内文を修正しました。

■ver3.0.13（2019/9/20）
・脆弱性対応を行いました。
・定期購買のカード情報更新時、ルミーズ加盟店バックヤードシステムにて、
　「決済成功時の確認メール」の送信を有効にしている場合、
　メールが送信されてしまう問題を修正しました。

■ver3.0.12（2018/12/11）
・カード決済の下記の利用カードブランドのロゴを変更しました。
　　VISA
　　楽天カード
・カード決済の利用カードブランドを追加しました。
　　オリコ
　　ライフ
　　セゾン
　　DISCOVER
　　セディナ
・マルチ決済の利用収納機関から、下記を削除しました。
　　サークルK
　　サンクス
　　モバイルＥｄｙ
　　モバイルＳｕｉｃａ
・マルチ決済の下記の決済下限金額を、200円から1円に変更しました。
　　セブンイレブン
　　ローソン
　　ファミリーマート
　　デイリーヤマザキ
　　ヤマザキデイリーストア
　　ミニストップ
　　セイコーマート
　　楽天Ｅｄｙ
　　ペイジー
　　ジャパンネット銀行
　　Ｓｕｉｃａインターネットサービス
　　楽天銀行
・ホストIDを数値のみから文字列を許容するよう変更

＜修正ファイル＞
・inc/include.php
・tmp/config.tpl
・inst/img/card_img/visa.gif
・inst/img/card_img/rakuten.gif
・class/LC_Page_Mdl_Remise_Config.php
＜追加ファイル＞
・inst/img/card_img/orico.gif
・inst/img/card_img/life.gif
・inst/img/card_img/saison.gif
・inst/img/card_img/discover.gif
・inst/img/card_img/cedyna.gif

■ver3.0.11（2018/06/29）
・auかんたん決済、ソフトバンクまとめて支払いに対応しました。
・ドコモ ケータイ払いからドコモ払いへ名称変更しました。

＜修正ファイル＞
・inc/include.php
・tmp/config.tpl

■ver3.0.10（2018/05/15）
・IE利用時にトークン決済で支払方法に一括以外を選択した場合に決済できない不具合を修正しました。

＜修正ファイル＞
・tmp/remise.js

■ver3.0.9（2017/06/29）
・トークン決済に対応しました。

＜追加ファイル＞
・inst/LC_Page_Shopping_Payment_Ex.php

＜修正ファイル＞
・class/gateway.php
・class/LC_Page_Mdl_Remise_Config.php
・inc/card_common.php
・inst/install.php
・tmp/config.tpl
・tmp/remise.js
・tmp/remise_card.tpl
・tmp/remise_card_smartphone.tpl

■ver3.0.8（2017/05/25）
・ドコモケータイ払いに対応しました。
・カード決済時のパラメータ情報を追加しました。

＜修正ファイル＞
・class/paycard.php
・inc/include.php
・inst/install.php
・inst/install_ac.php
・inst/install_tk.php
・tmp/config.tpl

■ver3.0.7（2017/01/18）
・接続形態がWEB連携接続の場合、カード情報入力画面からサーバーを経由せずにルミーズに送信するよう修正しました。

＜追加ファイル＞
・tmp/remise_conveni.js

＜修正ファイル＞
・class/LC_Page_Mdl_Remise_Payment.php
・class/paycard.php
・class_ac/ac_remise_update.php
・inst/install_ac.php
・tmp/remise.js
・tmp/remise_btn_pc.tpl
・tmp/remise_btn_smartphone.tpl
・tmp/remise_card.tpl
・tmp/remise_card_smartphone.tpl
・tmp/remise_conveni.tpl
・tmp/remise_conveni_smartphone.tpl
・tmp_ac/remise_ac_update.tpl
・tmp_ac/remise_ac_update_smartphone.tpl
・tmp_ac/remise_btn_smartphone_ac_update.tpl

■ver3.0.6（2016/01/18）
以下の不具合修正を行っております。
　・管理画面の受注管理にて、複数配送のお届け先が表示されない不具合を修正
　　対象ver　EC-CUBE2.11.2、2.11.3、2.11.4、2.11.5

＜修正ファイル＞
・inst/install.php

＜追加ファイル＞
・inst/2.11/index(2.11.2_upper).tpl
・inst/2.11/edit(2.11.2_upper).tpl
・tmp/order_search_result(2.11.2).tpl

■ver3.0.5（2015/06/19）
・JCBプレモカードに対応しました。

＜修正ファイル＞
・inc/include.php

■ver3.0.4（2015/01/23）
・EC-CUBE2.13.3に対応しました。
・利用可能カードブランド・収納機関を追加しました。
・定期購買管理の検索項目に会員ID、メンバーID、次回課金日を追加しました。
・拡張セット利用時、受注管理画面からの一括実売上の決済処理中に画面操作がされないよう仕様変更しました。
・カード決済の結果通知受信処理で、受注データの削除フラグをチェックする機能を追加しました。

　その他、以下の不具合修正を行っております。
　・定期購買関連の顧客宛メールのヘッダー、フッターについて、管理画面での編集内容が実際のメールに反映されない不具合を修正
　・スマホ用カード決済画面のレイアウトが崩れる不具合を修正
　・拡張セット利用時、受注管理画面でカードの売上状態のデータが検索できない不具合を修正
　・定期購買管理の一括帳票出力時、帳票に次回課金日が出力されない不具合を修正
　・受注購買管理でメール一括通知を行うと、受注管理画面に戻ってしまう不具合を修正
　・EC-CUBE2.11.0でカード決済画面から「戻る」を押した際、システムエラーになる不具合を修正
　・2クリック決済でマルチ決済利用時、エラーの場合に正常動作しない不具合を修正
　・定期購買ダウンロード商品登録時、ダウンロードファイルの指定無しでも商品登録できてしまう不具合を修正
　・カード決済で「受注未確定」の状態の場合、マイページの購入履歴の「ご注文状況」が空になる不具合を修正
　・定期購買管理メニューでの検索時にシステムエラーになることがある不具合を修正

＜追加ファイル＞
・class_ac/LC_Page_Admin_Order_Mail_AC.php
・class_ac_Ex/LC_Page_Admin_Order_Mail_AC_Ex.php
・inst/2.12/LC_Page_Admin_Order_Mail_AC(2.12.3_upper).php
・inst/2.12/LC_Page_Admin_Order_Mail_AC.php
・inst/2.12/mail_ac(2.12.3_upper).tpl
・inst/2.12/mail_ac.tpl
・inst/2.12/mail_ac_confirm(2.12.3_upper).tpl
・inst/2.12/mail_ac_confirm.tpl
・inst/2.13/LC_Page_Admin_Order_Mail_AC.php
・inst/2.13/mail_ac.tpl
・inst/2.13/mail_ac_confirm.tpl
・inst/img/card_img/aeon.gif
・inst/img/card_img/rakuten.gif
・inst/mail_ac.php
・tmp_ac/remise_pdf_AC_input.tpl

＜修正ファイル＞
・class/LC_Page_Mdl_Remise_Config.php
・class/LC_Page_Mdl_Remise_Payment.php
・class/paycard.php
・class/paycvs.php
・class/paycvs_complete.php
・class_ac/ac_remise_update_complete.php
・class_ac/LC_Page_Admin_Order_Remise_AC.php
・class_ac/LC_Page_Admin_Order_Pdf_AC.php
・inc/include.php
・inst/2.11/index.tpl
・inst/2.12/index.tpl
・inst/2.12/LC_Page_Admin_Order_Ex.php
・inst/2.12/product_class.php
・inst/2.13/edit(2.13.2_upper).tpl
・inst/2.13/index.tpl
・inst/2.13/LC_Page_Admin_Order_Ex.php
・inst/2.13/LC_Page_Products_Detail_Ex.php
・inst/install_ac.php
・inst/LC_Page_Admin_Products_Product_Ex.php
・inst/LC_Page_Admin_Products_ProductClass_Ex.php
・inst/remise_recv.php
・tmp/order_search_result(2.13).tpl
・tmp/order_search_result_salessubmit.tpl
・tmp/remise.css
・tmp/remise_conveni_smartphone.tpl
・tmp_ac/remise_ac_order.tpl



■ver3.0.3（2014/07/23）
・EC-CUBE2.13.2に対応しました。

・EC-CUBEの管理画面より、定期購買の退会を行う機能を追加しました。

・拡張セットの処理をEC-CUBEの対応状況と連動させ、実売上処理で「入金済み」、
　取消・返品処理で「キャンセル」となるよう改修しました。

　その他、以下の不具合修正を行っております。
　・EC-CUBE2.13系でGW接続利用時、エラーメッセージが表示されない不具合の修正
　・EC-CUBE2.13系でGW接続利用時、モバイル端末でマイページからカード更新が
　　できない不具合の修正
　・GW接続にて、モバイル端末でマイページから定期購買のカード更新及び退会を
　　行った際のメールの内容が空になる不具合を修正
　・EC-CUBE2.13系にて、マルチ決済の入金お知らせメールの商品価格が税抜き
　　表示となってしまう不具合の修正
　・EC-CUBE2.13.2にて、定期購買管理メニューからCSVダウンロードを行うと、
　　内容の全角文字が文字化けする不具合の修正
　・カード決済のエラーメッセージの見直し

＜追加ファイル＞
・class_ac/LC_Page_Admin_Remise_AC.php
・inst/2.13/edit(2.13.2_upper).tpl
・inst/remise_receipt_mail(2.13).tpl
・inst/remise_ac_card_update_mail(mbl).tpl
・inst/remise_ac_order_refusal_mail(mbl).tpl
・inst/remise_ac_refusal_another_mail.tpl

＜修正ファイル＞
・class/LC_Page_Mdl_Remise_Config.php
・class/gateway.php
・class_ac/ac_remise_refusal.php
・class_ac/ac_remise_refusal_complete.php
・class_ac/ac_remise_update.php
・class_ac/gateway.php
・class_ac/LC_Page_Admin_Order_Remise_AC.php
・class_ac/LC_Page_Admin_Order_Remise_AC_Edit.php
・inc/include.php
・inc/errinfo.php
・inst/2.11/LC_Page_Admin_Order_Ex.php
・inst/2.11/LC_Page_Admin_Order_Edit_Ex.php
・inst/2.12/index.tpl
・inst/2.12/LC_Page_Admin_Order_Ex.php
・inst/2.12/LC_Page_Admin_Order_Edit_Ex.php
・inst/2.13/index.tpl
・inst/2.13/LC_Page_Admin_Order_Ex.php
・inst/2.13/LC_Page_Admin_Order_Edit_Ex.php
・inst/install_ac.php
・inst/install.php
・inst/LC_Page_Mypage_History_Ex.php
・inst/remise_ac_refusal.php
・inst/remise_ac_order_edit.php
・tmp_ac/remise_ac_order.tpl
・tmp_ac/remise_ac_order_edit.tpl

■ver3.0.2（2014/03/13）
・定期購買機能の課金金額に本体の消費税率を反映するよう修正しました。
・定期購買管理メニューにて、
　定期の金額・次回課金日・決済間隔を編集できるよう改修しました。
・商品のダウンロードファイルや画像ファイルを登録・削除した際に、
　定期購買設定の内容が消える不具合を修正しました。
・定期購買機能にて、ルミーズモジュールの独自ファイルをコピーせず、
　モジュール内にて処理するように変更しました。
・2.13系にて２クリックプラグイン利用時、ゲスト購入の際に他の支払先にて
　エラーが発生する不具合を修正しました。
・定期購買及び２クリックカスタマイズについて、プラグインが有効でない場合は
　モジュール設定画面に表示されないように修正しました。
・結果通知機能との連携により、ルミーズでカード決済成立後、EC-CUBEに
　注文が反映されていない状態のステータスを追加しました。
・その他、細部バグを修正しております。

＜移動ファイル＞

・inst/LC_Page_Admin_Order_Remise_AC.php
・inst/LC_Page_Admin_Order_Remise_AC_Edit.php
・inst/LC_Page_Admin_Order_Pdf_AC.php
・inst/SC_Fpdf_AC.php
以上を「class_ac」の中へ移動

・inst/LC_Page_Admin_Order_Remise_AC_Ex.php
・inst/LC_Page_Admin_Order_Remise_AC_Edit_Ex.php
・inst/LC_Page_Admin_Order_Pdf_AC_Ex.php
・inst/SC_Fpdf_AC_Ex.php
以上を「class_ac_Ex（新設）」の中へ移動

・inst/LC_Page_Shopping_Ex.php
以上を「inst/2.12」及び「inst/2.13」へ移動（場合分け）

＜修正ファイル＞

・class/gateway.php
・class/LC_Page_Mdl_Remise_Config.php
・class/paycard.php
・class/paycard_complete.php
・class/paycvs.php
・class_ac/ac_remise_update.php
・class_ac/gateway.php
・class_ac/LC_Page_Admin_Order_Remise_AC_Edit.php
・inc/include.php
・inst/2.11/index.tpl
・inst/2.12/index.tpl
・inst/2.12/LC_Page_Admin_Order_Ex.php
・inst/2.12/LC_Page_Shopping_Ex.php
・inst/2.13/index.tpl
・inst/2.13/LC_Page_Admin_Order_Ex.php
・inst/2.13/LC_Page_Shopping_Ex.php
・inst/install_ac.php
・inst/install_tk.php
・inst/LC_Page_Admin_Products_Product_Ex.php
・inst/LC_Page_Shopping_Confirm_Ex.php
・inst/pdf_ac.php
・inst/remise_ac_order.php
・inst/remise_ac_order_edit.php
・inst/remise_recv.php
・tmp/config.php
・tmp/order_search_result.php
・tmp/order_search_result(2.12).php
・tmp/order_search_result(2.13).php
・tmp_ac/remise_ac_order.tpl
・tmp_ac/remise_ac_order_edit.tpl



■ver3.0.1（2013/12/26）
・一部コンビニの支払方法変更に伴う画面表示及びメール文面の表記見直しを行っています。
・決済モジュールの画面上にて、ＰＣ及びスマートフォンでの決済情報送信時、送信ボタンと
　戻るボタンのいずれかを押したら他方を押せないよう、処理の見直しを行っております。
・2.13系にて、ダウンロード用の定期購買商品のダウンロードができない不具合を修正しました。
・2クリックプラグインとの連携について、本体側テンプレートの修正を行っております。
・その他、細部バグを修正しております。

＜修正ファイル＞

（以下、2.12系以降のみ）
・inst/pc/confirm.tpl
・inst/smp/confirm.tpl

（以下、2.13系以降のみ）
・inst/pc/confirm.tpl
・inst/pc/history.tpl
・inst/pc/payment.tpl
・inst/mbl/confirm.tpl
・inst/mbl/history.tpl
・inst/mbl/payment.tpl
・inst/smp/confirm.tpl
・inst/smp/history.tpl
・inst/smp/payment.tpl

（以下、共通ファイル）
・class/paycard.php
・class/paycvs.php
・class/gateway.php
・class/paycvs_complete.php
・inst/install_ac.php
・inst/install_tk.php
・inst/LC_Page_Shopping_Confirm_Ex.php


■ver3.0.0（2013/10/03）
メジャーアップデートに伴うテンプレート及び処理の見直し
ダウンロード用定期購買商品のお届け先が表示される不具合の修正
ロールバックによる削除データを確認・復旧できる機能を追加

＜追加・修正及びフォルダ構成変更ファイル＞
・inst/edit.tpl
・inst/index.tpl
・inst/LC_Page_Admin_Order_Ex.php
・inst/LC_Page_Admin_Order_Edit_Ex.php

（以下、2.12系以降のみ）
・inst/pc/confirm.tpl（追加）
・inst/pc/history.tpl
・inst/pc/payment.tpl（追加）
・inst/mbl/confirm.tpl
・inst/mbl/history.tpl
・inst/mbl/payment.tpl（追加）
・inst/smp/confirm.tpl（追加）
・inst/smp/history.tpl
・inst/smp/payment.tpl（追加）
・inst/LC_Cart_Ex.php
・inst/LC_Page_Products_Detail_Ex.php
・inst/product_class.tpl

以上のファイルを、「2.11」「2.12」「2.13」の
バージョン毎に分けてinstフォルダの下に格納しています。

＜修正ファイル＞
・class/LC_Mdl_Remise_Config.php
・inst/install.php
・inst/install_ac.php
・inst/install_tk.php
・inst/LC_Page_Admin_Order_Pdf_AC.php
・inst/LC_Page_Admin_Order_Ex.php
・inst/LC_Page_Admin_Order_Remise_AC.php
・inst/LC_Page_Admin_Order_Remise_AC_Edit.php
・inst/LC_Page_Admin_Products_Product_Ex.php
・inst/LC_Page_Admin_Products_ProductClass_Ex.php

・inst/LC_Page_Shopping_Confirm_Ex.php
・tmp/config.tpl
・tmp_ac/remise_ac_order_edit.tpl

＜追加ファイル＞
・tmp/order_search_result(2.13).tpl



■ver2.2.1（2013/08/12）
ミニストップの支払方法変更に伴い、文言の修正

＜修正ファイル＞
・class/paycvs_complete.php
・inc/include.php

■ver2.2.0（2013/07/17）
2クリック（かんたん決済）機能実装
2.12.3以上にて定期購買機能利用時、商品登録の際にエラーとなる不具合修正
その他細部バグ修正

＜追加ファイル＞
・class/LC_Mdl_Remise_TwoClick_Help.php
・inc/twoclickinfo.php
・inst/LC_Cart_Ex.php
・inst/LC_Page_Products_Detail_Ex.php
・inst/LC_Page_Shopping_Confirm_Ex.php
・inst/LC_Page_Shopping_Ex.php
・inst/install_tk.php
・inst/twoclick_help.php
・inst/mbl/confirm.tpl
・tmp/help.tpl
・tmp/help_smp.tpl
・tmp/help_mbl.tpl

＜修正ファイル＞
・class/LC_Mdl_Remise_Payment.php
・class/LC_Mdl_Remise_Config.php
・class/paycard.php
・class/paycvs.php
・class/extsetcard.php
・class/paycvs_complete.php
・inst/LC_Page_Shopping_Complete_Ex.php
・inst/LC_Page_Admin_Products_Product_Ex.php
・inst/LC_Page_Admin_Products_ProductClass_Ex.php
・tmp/config.tpl
・tmp/remise_card.tpl
・tmp/remise_card_mobile.tpl
・tmp/remise_card_smartphone.tpl

■ver2.1.6（2013/04/25）
2.12.2以前で定期購買機能利用時、配送選択の時にエラーとなる不具合修正

＜修正ファイル＞
・inst/LC_Page_Shopping_Deliv_Ex.php

■ver2.1.5（2013/04/12）
定期購買ダウンロード商品購入時、配送方法を選ぶ部分をスキップするよう修正
定期購買注文の帳簿発行を実装
2.12.3での改修により、定期購買カスタマイズを行うと商品登録できなくなる点を修正
定期購買商品規格登録時、登録のチェックボックスが入ってなくても登録できてしまったバグ修正
iOS5にて、カード番号入力内容が異なって表示される点を修正
バックアップファイル消失防止のため、生成されたバックアップファイルを上書きしないよう修正
決済モジュール設定ウィンドウの文言及び表示を修正

＜追加ファイル＞
・inst/LC_Page_Shopping_Deliv_Ex.php
・inst/LC_Page_Admin_Order_Pdf_AC.php
・inst/LC_Page_Admin_Order_Pdf_AC_Ex.php
・inst/pdf_ac.php
・inst/SC_Fpdf_AC.php
・inst/SC_Fpdf_AC_Ex.php

＜修正ファイル＞
・inst/install.php
・inst/install_ac.php
・inst/LC_Page_Admin_Products_Product_Ex.php
・inst/LC_Page_Admin_Products_ProductClass_Ex.php
・tmp/config.tpl
・tmp/remise_card_smartphone.tpl
・tmp_ac/remise_ac_update_smartphone.tpl

■ver2.1.4（2013/01/17）
定期購買ダウンロード商品を追加。
定期購買商品の規格登録機能を実装。
拡張セット利用時に、オーソリエラーが認識されないバグ等修正。

＜追加ファイル＞
・inst/LC_Page_Admin_Products_ProductClass_Ex.php
・inst/product_class.tpl
・inst/edit(2.12).tpl (edit.tpl_2.12より名称変更)
・inst/index(2.12).tpl (index.tpl_2.12より名称変更)

＜修正ファイル＞
・class/extsetcard.php
・class/gateway.php
・class/paycard.php
・class/paycard_complete.php
・class_ac/ac_remise_refusal.php
・class_ac/ac_remise_refusal_complete.php
・class_ac/ac_remise_update.php
・class_ac/gateway.php
・inc/include.php
・inst/pc/history.tpl
・inst/mbl/history.tpl
・inst/smp/history.tpl
・inst/index.tpl
・inst/install.php
・inst/install_ac.php
・inst/LC_Page_Admin_Products_Product_Ex.php
・inst/SC_CartSession_Ex.php
・tmp/order_search_result.tpl
・tmp/order_search_result(2.12).tpl
・tmp_ac/remise_ac_order_edit.tpl


■ver2.1.3（2012/10/19）
定期購買プラグイン連携機能の受注一覧画面改修及びバグ修正。
管理画面カスタマイズのテンプレートを2.12系に対応するよう改修。
決済送信情報にモジュールのバージョン番号を付与。
モジュール設定画面の記載を変更。
＜追加ファイル＞
・inst/edit.tpl_2.12
・inst/index.tpl_2.12
・tmp/order_search_result(2.12).tpl
＜修正ファイル＞
・class/paycard.php
・class/paycard_complete.php
・class/paycvs.php
・class/gateway.php
・class_ac/ac_remise_refusal.php
・class_ac/ac_remise_update.php
・class_ac/gateway.php
・inc/include.php
・inst/install.php
・inst/LC_Page_Admin_Order_Remise_AC.php
・inst/LC_Page_Admin_Order_Remise_AC_Edit.php
・inst/pc/history.tpl
・tmp/config.tpl
・tmp_ac/remise_ac_order.tpl
・tmp_ac/remise_ac_order_edit.tpl

■ver2.1.2（2012/08/08）
マイページからのカード更新、退会の機能追加。
adminディレクトリの名称変更の際、インストールできない不具合を修正。

＜追加ファイル＞
・定期購買の更新・退会処理のため、
　「class_ac」「tmp_ac」ディレクトリを新設し、
　その中身ファイルを追加

・inst/pc/history.tpl
・inst/mbl/history.tpl
・inst/smp/history.tpl
・inst/install_ac.php
・inst/LC_Page_Mypage_History_Ex.php
・inst/remise_ac_recv.php
・inst/remise_ac_update.php
・inst/remise_ac_refusal.php
・inst/remise_ac_card_update_mail.tpl
・inst/remise_ac_order_refusal_mail.tpl

＜修正ファイル＞
・class/LC_Page_Mdl_Remise_Config.php
・inc/include.php
・inst/install.php
・inst/LC_Page_Admin_Order_Remise_AC.php
・inst/LC_Page_Admin_Order_Remise_AC_Edit.php
・inst/LC_Page_Admin_Products_Product_Ex.php
・tmp/config.tpl
（定期購買に関する修正テンプレートは、「tmp_ac」ディレクトリ内に
　移動しております）

■ver2.1.1（2012/06/07）
2.11系、プラグイン未インストール時に商品の編集・複製が出来ない不具合を修正。
PayPalに対応。
楽天Edyへの名称変更、ファイルパスの参照方法を修正。

＜修正ファイル＞
・inst/LC_Page_Admin_Products_Product_Ex.php
・class/gateway.php
・class/extsetcard.php
・class/LC_Page_Mdl_Remise_Payment.php
・inc/include.php

■ver2.1.0（2012/05/21）
自動継続課金機能と連携（定期購買）。
＜追加ファイル＞
・inst/LC_Page_Admin_Order_Remise_AC.php
・inst/LC_Page_Admin_Order_Remise_AC_Ex.php
・inst/LC_Page_Admin_Order_Remise_AC_Edit.php
・inst/LC_Page_Admin_Order_Remise_AC_Edit_Ex.php
・inst/LC_Page_Admin_Products_Product_Ex.php
・inst/SC_CartSession_Ex.php
・inst/remise_ac_order.php
・inst/remise_ac_order_edit.php
・inst/SC_CartSession_Ex.php
・tmp/remise_ac_order.tpl
・tmp/remise_ac_order_edit.tpl
＜修正ファイル＞
・class/gateway.php
・class/extsetcard.php
・class/LC_Page_Mdl_Remise_Payment.php
・inc/include.php
・class/paycard.php
・class/paycard_complete.php


■ver2.0.6(2012/03/09)
細部バグ修正。
＜修正ファイル＞
・class/gateway.php
・class/paycvs.php
・class/paycvs_complete.php
・tmp/remise_btn_pc.tpl

■ver2.0.5(2012/02/10)
ゲートウェイモジュールの整合性向上。
マルチ決済用ホスト設定機能を追加。
＜修正ファイル＞
・inc/include.php
・class/gateway.php
・class/LC_Page_Mdl_Remise_Config.php
・class/paycard_complete.php
・inst/install.php
・tmp/config.tpl
＜追加ファイル＞
・inst/remise_order_cancel_mail.tpl

■ver2.0.4(2011/10/31)
拡張セット用ホスト番号登録機能を追加。
EC-CUBE2.11.2及び2.11.3対応のため、
テンプレート表示及びマルチ決済完了画面を改修。
(2011/12/06 EC-CUBE2.11.4の対応を確認しています。)
＜修正ファイル＞
・inc/include.php
・class/LC_Page_Mdl_Remise_Config.php
・class/paycvs_complete.php
・class/extsetcard.php
・inst/LC_Page_Admin_Order_Ex.php
・inst/LC_Page_Admin_Order_Edit_Ex.php
・inst/LC_Page_Shopping_Complete_Ex.php
・tmp/config.tpl
・tmp/remise_card_smartphone.tpl
・tmp/remise_btn_smartphone.tpl
・tmp/remise_conveni_smartphone.tpl

■ver2.0.3(2011/05/11)
PostgreSQLを利用している場合に生じる不具合を修正。
＜修正ファイル＞
・inc/include.php
・class/gateway.php
・class/LC_Page_Mdl_Remise_Config.php

■ver2.0.2(2011/05/02)
ディレクトリ構成を変更されている場合、画像が正常に表示されない不具合を修正。
＜修正ファイル＞
・class/LC_Page_Mdl_Remise_Payment.php
・inc/include.php
・inst/install.php
・tmp/remise_card.tpl
・tmp/remise_card_smartphone.tpl
・tmp/remise_conveni.tpl
・tmp/remise_conveni_smartphone.tpl

■ver2.0.1(2011/04/28)
PHP4の場合、一部関数が正常動作しない不具合を修正。
＜修正ファイル＞
・class/extset.php
・class/gateway.php
・class/paycard.php
・class/paycard_complete.php
・class/paycvs.php
・class/paycvs_complete.php
・inc/errinfo.php
・inc/include.php
・inst/install.php
・inst/LC_Page_Admin_Order_Edit_Ex.php

■ver2.0.0(2011/04/22)
EC-CUBE ver2.11.0に対応。
