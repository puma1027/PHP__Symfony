<?php
	/** On/Off */
	define('ON', "1");
	define('OFF', "0");
	
	define('URL_DIR', ROOT_URLPATH); 

	/** 祝日料金 */
	define('HOLIDAY_MONEY_APPLY', "0"); // 0:not apply 1: apply 
	
	/** 管理者ページ全体を閲覧できる権限限界 */
	define('ADMIN_ALLOW_LIMIT', "2");

	/** 糸川さん権限 **/
	define('ITOKAWA_ALLOW_LIMIT', "3");
	/** アルバイト権限 **/
	define('PARTTIME_ALLOW_LIMIT', "4");  
	
	/** 注文番号の長さ */
	define('ORDER_ID_LEN', 5);
	/** 3点セット */
	define('THREE_PIECE_SET1', "01-");
	/** 3点セット */
	define('THREE_PIECE_SET2', "02-");
	/** 4点セット */
	define('FOUR_PIECE_SET1', "91-");
	/** 4点セット */
	define('FOUR_PIECE_SET2', "92-");
	define("PCODE_SET_DRESS","01-");
	define("PCODE_DRESS","11-");
	define("PCODE_ONEPIECE_ALL","12-");
	define("PCODE_ONEPIECE_SUMMER","13-");
	define("PCODE_ONEPIECE_WINTER","14-");
    define("PCODE_COAT","15-");
	define("PCODE_STOLE","21-");
	define("PCODE_PANNIER","22-");
	define("PCODE_NECKLACE_SMALL","31-");
	define("PCODE_NECKLACE_LARGE","32-");
	define("PCODE_BELT","41-");
	define("PCODE_CORSAGE","81-");
	define("PCODE_BRACELET","61-");
	define("PCODE_BROOCH","71-");
	define("PCODE_BAG","51-");
	
	/** 検品画像の横幅、縦幅 */
	define('INSPECT_IMAGE_WIDTH', "300");
	define('INSPECT_IMAGE_HEIGHT', "300");
	/** 検品画像のサムネイル横幅、縦幅 */
	define('INSPECT_IMAGE_THUMB_WIDTH', "200");
	define('INSPECT_IMAGE_THUMB_HEIGHT', "200");
	
	/** 検品表検索ページ */
	define('URL_INSPECT_SEARCH', URL_DIR . ADMIN_DIR . "products/product_inspect_search.php");
	
	/** 検品画像保存先 */
	define('INSPECT_IMAGE_DIR', "upload/inspect_pattern/");
	define('INSPECT_IMAGE_FULL_DIR', HTML_REALDIR."upload/inspect_pattern/");
	/** 検品画像保存先URL */
	define('INSPECT_IMAGE_URL', URL_DIR."upload/inspect_pattern/");
	
	/** 商品検品画像保存先 */
	define('PRODUCT_INSPECT_IMAGE_DIR', "upload/inspect_image/");
	define('PRODUCT_INSPECT_IMAGE_FULL_DIR', HTML_REALDIR."upload/inspect_image/");
	/** 商品検品画像保存先URL */
	define('PRODUCT_INSPECT_IMAGE_URL', URL_DIR."upload/inspect_image/");
	
	/** レンタルワンピース */
	define('ONEPIECE_PRODUCT_TYPE', 1);
	/** レンタルドレス  */
	define('DRESS_PRODUCT_TYPE', 2);
	//::N00083 Change 20131201
	/** レンタルドレス3点セット */
	//::define('DRESS3_PRODUCT_TYPE', 3);
	define('DRESS3_PRODUCT_TYPE', 93);
	/** レンタルドレス4点セット */
	//::define('DRESS4_PRODUCT_TYPE', 4);
	define('DRESS4_PRODUCT_TYPE', 94);
	/** セットドレス(3点4点統合) */
	define('SET_DRESS_PRODUCT_TYPE', 3);
	//::N00083 end 20131201
	/** ストール・ボレロ */
	define('STOLE_PRODUCT_TYPE', 5);
	/** ネックレス */
	define('NECKLACE_PRODUCT_TYPE', 6);
	/** その他小物 */
	define('OTHERS_PRODUCT_TYPE', 7);
	
	/** 検品画像タイプ（ ドレス） */
	define('DRESS_INSPECT_IMAGE_TYPE', 1);
	/** 検品画像タイプ（ 羽織物） */
	define('STOLE_INSPECT_IMAGE_TYPE', 2);
	/** 検品画像タイプ（ ネックレス） */
	define('NECKLACE_INSPECT_IMAGE_TYPE', 3);
	/** 検品画像タイプ（バッグ） */
	define('BAG_INSPECT_IMAGE_TYPE', 4);
	/** 検品画像タイプ（ その他小物） */
	define('OTHERS_INSPECT_IMAGE_TYPE', 5);
	
	/** 商品のグレード */
	define('GRADE_VERY_GOOD', 5); //新品同様
	define('GRADE_GOOD', 4); //非常に良い
	define('GRADE_NORMAL', 3); //良い
	define('GRADE_BAD', 2); //やや使用感あり
	define('GRADE_VERY_BAD', 1); //使用感あり
	
	/** 商品のグレードが落ちる購入回数 */
	define('GRADE_DROP_ORDER_COUNT', 25);
	/** 商品のグレードが落ちる購入回数（新品同様） */
	define('NEW_GRADE_DROP_ORDER_COUNT', 10);
	
	/** 評価 */
	define('EVALUATE_1', 1); //全く目立たない
	define('EVALUATE_2', 2); //あまり目立たない 
	define('EVALUATE_3', 3); //やや目立つ
	define('EVALUATE_4', 4); //目立つ
	
	/** グレード下げる原因ID */
	define('REASON_0', "0"); //手動
	define('REASON_1', 1); //25回注文
	define('REASON_2', 2); //検品表データあり
	define('REASON_3', 3); //「目立つ」の評価が1つ以上
	define('REASON_4', 4); //「全体」の場所で「やや目立つ」の評価が1つ
	define('REASON_5', 5); //寿命が近づいている
	define('REASON_6', 6); //10回注文
	
	/** 注文のステータス(MNG, USER) */
	define('ORDER_STATUS_NEW', 1); //新規受付, 注文済み
	define('ORDER_STATUS_CONFIRM', 2); //注文確定, 発送準備中
	define('ORDER_STATUS_DELIV', 3);//発送済み, 発送済み
	define('ORDER_STATUS_RETURN', 4); //返却完了, 返却済み
	define('ORDER_STATUS_RETURN_BAD', 5); //返却不良, 返却不良
	define('ORDER_STATUS_UNDO', 6); //予約取り消し, 予約取り消し
	define('ORDER_STATUS_ADD', 7); //追加注文, 追加注文
	define('ORDER_STATUS_CANCEL', 8); //キャンセル, キャンセル
	
	/**  過去にレンタルした商品表示数 */
	define('HISTORY_PRODUCT_MAX', 4);
	
	/**  最近チェックした商品表示数 */
	define('RECENT_PRODUCT_MAX', 6);
	/**  最近チェックした商品のCookie */
	define('RECENT_PRODUCT_COOKIE', 'recent_products');
	/**  最近チェックした商品の有効期間 */
	define('RECENT_PRODUCT_EXPIRE', 3600 * 24 * 28); // 4週間
	
	/** お気に入り商品表示数 */
	define('FAVOFITE_PRODUCT_MAX', 40);
	/**  お気に入り商品のCookie */
	define('FAVORITE_PRODUCT_COOKIE', "fav_products");
	/**  お気に入り商品の有効期間 */
	define('FAVORITE_PRODUCT_EXPIRE', 3600 * 24 * 28); // 4週間
	
	/** この商品と、よく一緒にレンタルされている商品 */
	define('RELATED_PRODUCT_MAX', 6);

	/** ベストドレッサー画像保存先 */
    define('DRESSER_IMAGE_DIR', HTML_REALDIR . "upload/dresser_image/");
    /** ベストドレッサー画像保存先URL */
    define('DRESSER_IMAGE_URL', URL_DIR . "upload/dresser_image/");
    /** ベストドレッサー画像横 */
    define('DRESSER_IMAGE_WIDTH', 150);
    /** ベストドレッサー画像縦 */
    define('DRESSER_IMAGE_HEIGHT', 200);
    
	/** 予約可能な週数 */
	define('RESERVE_WEEKS', 9);
	define('RESERVE_WEEKS_MNG', 13); // manager page
	
	/** 予約パターン */
	define('RESERVE_PATTEN_SPECDAY', 1);// 平日
	define('RESERVE_PATTEN_RESTDAY', 2);// 休業日
	define('RESERVE_PATTEN_WEEK', 3); // 水、木発送
	define('RESERVE_PATTEN_HOLIDAY', 4);// 祝日
	
	/** カテゴリ */
	// main
	define('CATEGORY_DRESS_ALL', 'dress');
	define('CATEGORY_DRESS', 44);
	define('CATEGORY_DRESS3', 90);
	define('CATEGORY_DRESS4', 148);
	define('CATEGORY_ONEPIECE', 1);
	define('CATEGORY_NECKLACE', 63);
	define('CATEGORY_STOLE', 64);
	define('CATEGORY_OTHERS', 65);
	define('CATEGORY_ALL', 'all');
	define('CATEGORY_SET_DRESS', 232);
    define('CATEGORY_BAG', 231);
    define('CATEGORY_CEREMONYSUIT', 367);
	
	/** 予約種類 */
	define('RESERVED_TYPE_ORDER', "0"); //注文
	define('RESERVED_TYPE_SETTING', 1); //予約不可の設定 
	
	/** 注文の変更回数 */
	define('ORDER_CHANGE_COUNT', 2);
	
	/** 受注詳細変更フラグ */
	define('ORDER_DETAIL_CHANGE_NO', "0");// 変更なし
	define('ORDER_DETAIL_CHANGE_UPDATE', 1);// 変更
	define('ORDER_DETAIL_CHANGE_ADD', 2); // 追加
	define('ORDER_DETAIL_CHANGE_CANCEL', -1);// キャンセル
	define('ORDER_DETAIL_CHANGE_UNDO', -1);// 取り消し
	
	/** 関連商品表示数 */
	define('COORDINATE_RECOMMEND_PRODUCT_MAX', 4); //コーディネートで使用している商品
	define('SIZE_COLOR_RECOMMEND_PRODUCT_MAX', 4); //サイズ・色違いの商品
	
	/** 商品選びのお役立ちメモ数 */
	define('PRODUCT_SELECT_USEFUL_MEMO_NUM', "10");
	
	/** オススメ管理の商品表示数 */
	define('ADMIN_RECOMMEND_PRODUCT_COUNT', "8");
	
	/** 社員画像保存先 */
    define('STAFF_IMAGE_DIR', HTML_REALDIR . "upload/staff_image/");
    /** 社員画像保存先URL */
    define('STAFF_IMAGE_URL', URL_DIR . "upload/staff_image/");
    /** 社員画像横 */
    define('STAFF_IMAGE_WIDTH', 150);
    /** 社員画像縦 */
    define('STAFF_IMAGE_HEIGHT', 200);
    
    /** 婚活ワンピ画像保存先 */
    define('AMOUR_IMAGE_DIR', HTML_REALDIR . "upload/amour_image/");
    /** 婚活ワンピ画像保存先URL */
    define('AMOUR_IMAGE_URL', URL_DIR . "upload/amour_image/");

	/** ダウンロードモジュール保存ディレクトリ */
	define('MODULE_PATH', DATA_REALDIR . MODULE_DIR);	
	define ('SITE_URL', HTTPS_URL);

	define('DIR_INDEX_FILE', 'index.php');
	define('DIR_INDEX_PATH', DIR_INDEX_FILE);      

	/** サイトトップ */
	define('TOP_URLPATH', URL_SITE_TOP);
	/** カートトップ */
	define('URL_CART_TOP', HTTP_URL . "cart/" . DIR_INDEX_PATH);	
	define('CART_URLPATH', URL_CART_TOP);	

	/** 画像がない場合に表示 */
	define('NO_IMAGE_URL', URL_DIR . "misc/blank.gif");
	/*2014/0313 pwb add */
	define('DEF_LAYOUT', 'mypage/index.php');

    // 最大レンタル数
    define('MAXIMUM_RENTAL_NUMBER', 12);

    /** 返却時間 */
    define('RETURN_TIME', '昼14時');

    /** UTF-8依存文字が入力された際に表示する文字(Unicode値の整数 デフォルト: ?) */
    define('SUBSTITUTE_CHAR', 63); // 2020.10.14 SG.Yamauchi mtb_constants_init.phpから移動
