<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * MySQL 設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link http://wpdocs.osdn.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86
 *
 * @package WordPress
 */

// 注意:
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.osdn.jp/%E7%94%A8%E8%AA%9E%E9%9B%86#.E3.83.86.E3.82.AD.E3.82.B9.E3.83.88.E3.82.A8.E3.83.87.E3.82.A3.E3.82.BF 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('WP_DB_NAME', 'onepiece_wp');

/** MySQL データベースのユーザー名 */
define('WP_DB_USER', 'wanpi-wpadmin-ad');

/** MySQL データベースのパスワード */
define('WP_DB_PASSWORD', 'ggMrmc8PWzLVMeGW');

/** MySQL のホスト名 */
define('WP_DB_HOST', 'localhost');

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8mb4');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'b1-T7xyP&9/612lG,)}Qb|dKG5>UB08T[=@=nq8M%&^vrx6a5^OLI&#5RU$e^yl#');
define('SECURE_AUTH_KEY',  '&0XWu*Dw|>U(z`A3},fFxZDhO@n$g{2aHPC(FmQ12u^p:iRQp^(>XUe~<f$,Gm33');
define('LOGGED_IN_KEY',    ',iGV_o!06<Wg}Q%BY&<)w;idA-voM--X6`=L+UAdoN5BC>1EI;sk|#4)D+go@-?6');
define('NONCE_KEY',        'zoB_/3cWrKn%Ivx@%qtr)I1p<;QotG@VW-TGJ:hI6JaP_!g9n9^`/E3Cj4us>_`f');
define('AUTH_SALT',        '}q/F 0*L-*X.@)%aQG2I3CA5UwBs1uQ@7p*27.vmpM_M)~bZS/@Hk2RIjHtB,297');
define('SECURE_AUTH_SALT', 'rhYlr@O,VcjOo{BFRxT-#~hdqg^Jnq{&&%=5@^@ a:^hMNx[5=g0[<Y7<%P1x~I`');
define('LOGGED_IN_SALT',   '9@9&$p<$63Q4r 9x%cnECrS&WYK[ob8)rD-<qWMqw|H[CEf#gtBBD!D]ll?Dd%D!');
define('NONCE_SALT',       '[(f5/N+UtHg!G<m[OU.Ou2uBMEbH7GOY:m+YHV;q`jOh(t>XV_2qT^>n_8lWCi`S');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wp_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数については Codex をご覧ください。
 *
 * @link http://wpdocs.osdn.jp/WordPress%E3%81%A7%E3%81%AE%E3%83%87%E3%83%90%E3%83%83%E3%82%B0
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

