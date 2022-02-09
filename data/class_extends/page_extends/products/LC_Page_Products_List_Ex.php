<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
require_once CLASS_REALDIR . 'SC_PageNavi_Border.php';
 // 新機能カテゴリ複合検索 GET値取得用
define("SIZE","size"); //サイズ
define("COLOR","color"); //色
define("GARA","gara"); //　柄
define("LEN","len"); //丈
define("SODE","sode"); // 袖
define("OPTION","option"); //　こだわり条件
define("SILHOUETTE","silhouette"); // シルエット
define("_FUNCTION","function"); //機能
define("TYPE","type"); //　それぞれのタイプ
define("COLLAR","collar"); //　えりもと
define("ICON","icon"); //　アイコン
define("A_CUP","a_cup"); //　ブラのサイズ-カップ
define("A_UNDER","a_under"); //　ブラのサイズ-アンター
define("B_TOP","b_top"); //　ブラの実寸サイズ-トップ
define("B_UNDER","b_under"); //　ブラのサイズ-アンター
define("WEARING","wearing"); //　着用感を選択
define("SEASON", "season"); //　シーズンを選択(ワンピース)
//::N00080 Add 20130912
define("AGE","age"); //年齢
define("EVENT","event"); //シーン
define("QUALITY","quality"); //商品ステータス
define("SIZE_FAILURE","size_failure"); //サイズ
define("COMPLEX","complex"); //お困り
define("WORRY","worry"); //心配
//::N00080 end 20130912
//::N00190 Add 20140616
define("STOLE_TYPE",'78');
define("BOLERO_TYPE",'79');
define("NECKLACE_TYPE",'63');
define("BELT_TYPE",'144');
define("BRACELET_TYPE",'179');
define("CORSAGE_TYPE",'143');
define("BROOCH_TYPE",'188');
define("PANNER_TYPE",'145');
//::N00190 Add 201709
define("HAIRAC_TYPE",'352');
//:: Add 201806
define("CEREMONY_TYPE",'367');
define("CEREMONY_HAORI_TYPE",'366');

require_once CLASS_REALDIR . 'pages/products/LC_Page_Products_List.php';

/**
 * LC_Page_Products_List のページクラス(拡張).
 *
 * LC_Page_Products_List をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Products_List_Ex extends LC_Page_Products_List
{

	/** テンプレートクラス名1 */
	var $tpl_class_name1;

	/** テンプレートクラス名2 */
	var $tpl_class_name2;

	/** JavaScript テンプレート */
	var $tpl_javascript;

	var $device_type_id;
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
		parent::init();
		$this->tpl_header_area_title = "商品一覧";
        //$this->tpl_rental_calendar = "products/rental_calendar.tpl";
        // ishiabshi pc側とsp側を同時に読み込んでおり、カレンダーが表示されないためデバイス名で分岐
        $this->device = $this->is_mobile();
        if( $this->device === 1 )
        {
            $this->tpl_rental_calendar = "products/rental_calendar.tpl";
        }
        else
        {
            $this->tpl_rental_calendar = "products/rental_calendar.tpl";
        }
		$masterData = new SC_DB_MasterData_Ex();
		$this->arrSTATUS = $masterData->getMasterData("mtb_status");
		$this->arrSTATUS_IMAGE = $masterData->getMasterData("mtb_status_image");
		$this->arrDELIVERYDATE = $masterData->getMasterData("mtb_delivery_date");
		// 2014.1.29 RCHJ Change
		$this->arrPRODUCTLISTMAX = $masterData->getMasterData("mtb_product_list_max");
		$this->device_type_id = SC_Display_Ex::detectDevice();
    	if($this->device_type_id == DEVICE_TYPE_SMARTPHONE){
			// 2015.9.5 t.ishii デフォルト表示件数を6件から30件に変更 start
    		$this->arrPRODUCTLISTMAX = array(36 => '36件');
    		// 2015.9.5 t.ishii デフォルト表示件数を6件から30件に変更 end
            // 20201224 ishibashi spとpcで横が3と4で異なるので、公倍数で表示 30→24?36?
    	}

		$this->tpl_class_name1 = array();
		$this->tpl_class_name2 = array();
		$this->allowClientCache();

		$this->arrPriceTitle = array("3泊4日間");

        //サイズ(大人)
		$this->arrSIZEtext = array(
				'1'=>'SS',
				'2'=>'S',
				'3'=>'M',
				'4'=>'L',
				'5'=>'LL',
				'6'=>'3L',
				'7'=>'4L',//::N00140 Add 20140410
				'8'=>'マタニティ'
		);
        //サイズ(kids)
		$this->arrKisdsSIZEtext = array(
				'0'=>'100',
				'1'=>'105',
				'2'=>'110',
				'3'=>'115',
				'4'=>'120',
				'5'=>'125',
				'6'=>'130',
				'7'=>'135',
				'8'=>'140',
				'9'=>'150',
				'10'=>'160'
		);

		$this->arrSize = array(
                "1"=>array(DRESS_PRODUCT_TYPE=>"66", SET_DRESS_PRODUCT_TYPE=>"237", ONEPIECE_PRODUCT_TYPE=>"134", CEREMONYSUIT_PRODUCT_TYPE=>"237"), //SS
                "2"=>array(DRESS_PRODUCT_TYPE=>"67", SET_DRESS_PRODUCT_TYPE=>"236", ONEPIECE_PRODUCT_TYPE=>"133", CEREMONYSUIT_PRODUCT_TYPE=>"236"), //S
                "3"=>array(DRESS_PRODUCT_TYPE=>"68", SET_DRESS_PRODUCT_TYPE=>"235", ONEPIECE_PRODUCT_TYPE=>"132", CEREMONYSUIT_PRODUCT_TYPE=>"235"), //M
                "4"=>array(DRESS_PRODUCT_TYPE=>"69", SET_DRESS_PRODUCT_TYPE=>"234", ONEPIECE_PRODUCT_TYPE=>"131", CEREMONYSUIT_PRODUCT_TYPE=>"234"), //L
                "5"=>array(DRESS_PRODUCT_TYPE=>"70", SET_DRESS_PRODUCT_TYPE=>"233", ONEPIECE_PRODUCT_TYPE=>"130", CEREMONYSUIT_PRODUCT_TYPE=>"233",), //LL
                "6"=>array(DRESS_PRODUCT_TYPE=>"201",SET_DRESS_PRODUCT_TYPE=>"261", ONEPIECE_PRODUCT_TYPE=>"-1", CEREMONYSUIT_PRODUCT_TYPE=>"261",), //3L
                "7"=>array(DRESS_PRODUCT_TYPE=>"271",SET_DRESS_PRODUCT_TYPE=>"272", ONEPIECE_PRODUCT_TYPE=>"-1", CEREMONYSUIT_PRODUCT_TYPE=>"272"), //4L//::N00140 Add 20140410
                "8"=>array(DRESS_PRODUCT_TYPE=>"62", SET_DRESS_PRODUCT_TYPE=>"244", ONEPIECE_PRODUCT_TYPE=>"29", CEREMONYSUIT_PRODUCT_TYPE=>"244"), // マタニティOK
		);

		$this->arrColor = array(
				//cere 1,2,130,150
        		"1"   => array(DRESS_PRODUCT_TYPE => "46", SET_DRESS_PRODUCT_TYPE => "260", ONEPIECE_PRODUCT_TYPE => "10", CEREMONYSUIT_PRODUCT_TYPE => "260",), // 黒
        		"2"   => array(DRESS_PRODUCT_TYPE => "72", SET_DRESS_PRODUCT_TYPE => "253", ONEPIECE_PRODUCT_TYPE => "12", CEREMONYSUIT_PRODUCT_TYPE => "253",), //　ネイビー
        		"3"   => array(DRESS_PRODUCT_TYPE => "47", SET_DRESS_PRODUCT_TYPE => "259", ONEPIECE_PRODUCT_TYPE => "14", ), //　シルバー
        		"4"   => array(DRESS_PRODUCT_TYPE => "48", SET_DRESS_PRODUCT_TYPE => "258", ONEPIECE_PRODUCT_TYPE => "19", ), //　ブラウン
        		"5"   => array(DRESS_PRODUCT_TYPE => "49", SET_DRESS_PRODUCT_TYPE => "257", ONEPIECE_PRODUCT_TYPE => "18", CEREMONYSUIT_PRODUCT_TYPE => "257",), //　ベージュ系
        		"6"   => array(DRESS_PRODUCT_TYPE => "52", SET_DRESS_PRODUCT_TYPE => "256", ONEPIECE_PRODUCT_TYPE => "11", CEREMONYSUIT_PRODUCT_TYPE => "256",), //　ピンク
        		"7"   => array(DRESS_PRODUCT_TYPE => "50", SET_DRESS_PRODUCT_TYPE => "255", ONEPIECE_PRODUCT_TYPE => "-1", ), //　赤系
        		"8"   => array(DRESS_PRODUCT_TYPE => "77", SET_DRESS_PRODUCT_TYPE => "254", ONEPIECE_PRODUCT_TYPE => "-1", ), //　パープル系
        		"9"   => array(DRESS_PRODUCT_TYPE => "51", SET_DRESS_PRODUCT_TYPE => "252", ONEPIECE_PRODUCT_TYPE => "12", ), //　ブルー・緑
        		"10"  => array(DRESS_PRODUCT_TYPE => "71", SET_DRESS_PRODUCT_TYPE => "270", ONEPIECE_PRODUCT_TYPE => "13", CEREMONYSUIT_PRODUCT_TYPE => "270",), //　白
				"100" => array(DRESS_PRODUCT_TYPE => "46", SET_DRESS_PRODUCT_TYPE => "260", ONEPIECE_PRODUCT_TYPE => "10", CEREMONYSUIT_PRODUCT_TYPE => "260",), // 黒
				"101" => array(DRESS_PRODUCT_TYPE => "72", SET_DRESS_PRODUCT_TYPE => "253", ONEPIECE_PRODUCT_TYPE => "12", CEREMONYSUIT_PRODUCT_TYPE => "253",), // ネイビー
				"110" => array(DRESS_PRODUCT_TYPE => "47", SET_DRESS_PRODUCT_TYPE => "259", ONEPIECE_PRODUCT_TYPE => "14", CEREMONYSUIT_PRODUCT_TYPE => "259",), // シルバー・グレー, グレー系（ワンピ）
				"111" => array(DRESS_PRODUCT_TYPE => "77", SET_DRESS_PRODUCT_TYPE => "254", ONEPIECE_PRODUCT_TYPE => "15", CEREMONYSUIT_PRODUCT_TYPE => "254",), // パープル・ラベンダー, 紫（ワンピ）
				"120" => array(DRESS_PRODUCT_TYPE => "51", SET_DRESS_PRODUCT_TYPE => "252", ONEPIECE_PRODUCT_TYPE => "227", CEREMONYSUIT_PRODUCT_TYPE => "252",), // ブルー・緑, 青・緑（ワンピ）
				"130" => array(DRESS_PRODUCT_TYPE => "52", SET_DRESS_PRODUCT_TYPE => "256", ONEPIECE_PRODUCT_TYPE => "11", CEREMONYSUIT_PRODUCT_TYPE => "256",), // ピンク
				"131" => array(DRESS_PRODUCT_TYPE => "50", SET_DRESS_PRODUCT_TYPE => "255", ONEPIECE_PRODUCT_TYPE => "226", CEREMONYSUIT_PRODUCT_TYPE => "255",), // オレンジ・赤
				"140" => array(DRESS_PRODUCT_TYPE => "48", SET_DRESS_PRODUCT_TYPE => "258", ONEPIECE_PRODUCT_TYPE => "19", CEREMONYSUIT_PRODUCT_TYPE => "258",), // ブラウン, ブラウン系
				"141" => array(DRESS_PRODUCT_TYPE => "49", SET_DRESS_PRODUCT_TYPE => "257", ONEPIECE_PRODUCT_TYPE => "18", CEREMONYSUIT_PRODUCT_TYPE => "257",), // ベージュ・ゴールド・黄色, ベージュ系（ワンピ）
				"150" => array(DRESS_PRODUCT_TYPE => "71", SET_DRESS_PRODUCT_TYPE => "270", ONEPIECE_PRODUCT_TYPE => "13", CEREMONYSUIT_PRODUCT_TYPE => "270",), // 白
				"151" => array(DRESS_PRODUCT_TYPE => "-1", SET_DRESS_PRODUCT_TYPE => "-1", ONEPIECE_PRODUCT_TYPE => "24", CEREMONYSUIT_PRODUCT_TYPE => "-1",), // 華やか柄ワンピ（ワンピ）
		);

		$this->arrFunction = array(
        		"2"=>array(DRESS_PRODUCT_TYPE=>"74", SET_DRESS_PRODUCT_TYPE => "247", ONEPIECE_PRODUCT_TYPE=>"-1", ), // 大きめバストすっきり
        		"3"=>array(DRESS_PRODUCT_TYPE=>"73", SET_DRESS_PRODUCT_TYPE => "246", ONEPIECE_PRODUCT_TYPE=>"-1", ), // ぽっこりお腹をカバー
        		"4"=>array(DRESS_PRODUCT_TYPE=>"76", SET_DRESS_PRODUCT_TYPE => "245", ONEPIECE_PRODUCT_TYPE=>"-1", ), // 大きめヒップをカバー
        		"1"=>array(DRESS_PRODUCT_TYPE=>"61", SET_DRESS_PRODUCT_TYPE => "248", ONEPIECE_PRODUCT_TYPE=>"150", ), // ぽっちゃり二の腕カバー
        		"6"=>array(DRESS_PRODUCT_TYPE=>"62", SET_DRESS_PRODUCT_TYPE => "244", ONEPIECE_PRODUCT_TYPE=>"29", ), // マタニティOK
		);
		$this->arrFOUCTION = array(
        	'1'=>'ぽっちゃり二の腕カバー',
        	'2'=>'大きめバストをすっきり見せる',
        	'3'=>'ぽっこりお腹をカバー',
        	'4'=>'大きめヒップをふんわりカバー',
        	'5'=>'脚を細く見せるアシンメトリー丈',
        	'6'=>'マタニティOK');

		$this->arrLen = array(
			//ひざ上
			'1'=>array('from'=>'0','to'=>'87'),		// 150㎝ ひざ上（ひざ上3㎝以上）
			'2'=>array('from'=>'0','to'=>'88'),		// 155㎝ ひざ上（ひざ上3㎝以上）
			'3'=>array('from'=>'0','to'=>'90'),		// 160㎝ ひざ上（ひざ上3㎝以上）
			'4'=>array('from'=>'0','to'=>'92'),		// 165㎝ ひざ上（ひざ上3㎝以上）
			'5'=>array('from'=>'0','to'=>'95'),		// 170㎝ ひざ上（ひざ上3㎝以上）
			//ひざたけ
			'6'=>array('from'=>'88','to'=>'92'),		// 150㎝ ひざたけ（ひざ下2㎝～ひざ上2㎝）
			'7'=>array('from'=>'89','to'=>'93'),		// 155㎝ ひざたけ（ひざ下2㎝～ひざ上2㎝）
			'8'=>array('from'=>'91','to'=>'95'),		// 160㎝ ひざたけ（ひざ下2㎝～ひざ上2㎝）
			'9'=>array('from'=>'93','to'=>'97'),		// 165㎝ ひざたけ（ひざ下2㎝～ひざ上2㎝）
			'10'=>array('from'=>'96','to'=>'100'),		// 170㎝ ひざたけ（ひざ下2㎝～ひざ上2㎝）
			//ひざ下
			'11'=>array('from'=>'93', 'to'=>'1000'), 		// 150㎝ ひざ下（ひざ下3㎝以上）
			'12'=>array('from'=>'94', 'to'=>'1000'), 		// 155㎝ ひざ下（ひざ下3㎝以上）
			'13'=>array('from'=>'96', 'to'=>'1000'), 		// 160㎝ ひざ下（ひざ下3㎝以上）
			'14'=>array('from'=>'98', 'to'=>'1000'), 		// 165㎝ ひざ下（ひざ下3㎝以上）
			'15'=>array('from'=>'101', 'to'=>'1000'), 		// 170㎝ ひざ下（ひざ下3㎝以上）
		);

		$this->arrOptionBack = array(
			 "1"=>array(DRESS_PRODUCT_TYPE=>"-1", DRESS3_PRODUCT_TYPE=>"-1", DRESS4_PRODUCT_TYPE=>"-1", ONEPIECE_PRODUCT_TYPE=>"-1", ), // 背中の調節ひも付き
		);

		$this->arrSilhouette = array(
			"1"=>array(DRESS_PRODUCT_TYPE=>"59",  SET_DRESS_PRODUCT_TYPE => "251", ONEPIECE_PRODUCT_TYPE=>"30", ), //　全身美ライン
			"2"=>array(DRESS_PRODUCT_TYPE=>"60",  SET_DRESS_PRODUCT_TYPE => "250", ONEPIECE_PRODUCT_TYPE=>"28", ), //　全身ふんわり
			"3"=>array(DRESS_PRODUCT_TYPE=>"75",  SET_DRESS_PRODUCT_TYPE => "249", ONEPIECE_PRODUCT_TYPE=>"-1", ), // ウエスト切り替え
			"4"=>array(DRESS_PRODUCT_TYPE=>"209", SET_DRESS_PRODUCT_TYPE => "269", ONEPIECE_PRODUCT_TYPE=>"-1", ), // アンダーバスト切り替え
		);

		$this->arrCollar = array(
			"1"=>array(DRESS_PRODUCT_TYPE=>"203", SET_DRESS_PRODUCT_TYPE => "265", ONEPIECE_PRODUCT_TYPE=>"223", ), //　Vネック
			"2"=>array(DRESS_PRODUCT_TYPE=>"202", SET_DRESS_PRODUCT_TYPE => "266", ONEPIECE_PRODUCT_TYPE=>"224", ), //　スクェアネック
			"3"=>array(DRESS_PRODUCT_TYPE=>"204", SET_DRESS_PRODUCT_TYPE => "267", ONEPIECE_PRODUCT_TYPE=>"225", ), // ハイネック(ONEPIECE_PRODUCT_TYPE: タートルネック )
			"4"=>array(DRESS_PRODUCT_TYPE=>"205", SET_DRESS_PRODUCT_TYPE => "268", ONEPIECE_PRODUCT_TYPE=>"222", ), // その他
		);

		$this->arrGara = array(
			//::"1"=>array(DRESS_PRODUCT_TYPE=>"207", DRESS3_PRODUCT_TYPE=>"212", DRESS4_PRODUCT_TYPE=>"181", ONEPIECE_PRODUCT_TYPE=>"24", ), //　	柄つき
			"1"=>array(DRESS_PRODUCT_TYPE=>"207", SET_DRESS_PRODUCT_TYPE=>"262", ONEPIECE_PRODUCT_TYPE=>"24", ), //　	柄つき
		);

		$this->arrSode = array(
        		"1"=>array("from"=>"0", "to"=>"4"), // ノースリーブ
        		"2"=>array("from"=>"5", "to"=>"24" ), //　ミニ袖～半袖
        		"3"=>array("from"=>"25", "to"=>"40"), //　五分～七分袖
        		"4"=>array("from"=>"41", "to"=>"100"), //　長袖
		);

		$this->arrA_Bust = array(
        		"1"=>"10", // A
        		"2"=>"13", // B
				"3"=>"15", // C
				"4"=>"18", // D
				"5"=>"20", // E
				"6"=>"23", // F
		);

		$this->arrWearing = array(
        		"1"=>array("from"=>"2", "to"=>"1000"), //　サイズが入る商品をすべて選択
        		"2"=>array("from"=>"4", "to"=>"10" ), //　ゆったり着られる商品を選択
        		"3"=>array("from"=>"0", "to"=>"3"), //　ぴったり着られる商品を選択
		);


		$this->arrSeason = array(
			//::"1"=>array(DRESS_PRODUCT_TYPE=>"0", DRESS3_PRODUCT_TYPE=>"0", DRESS4_PRODUCT_TYPE=>"0", ONEPIECE_PRODUCT_TYPE=>"229", ), //オールシーズン
			//::"2"=>array(DRESS_PRODUCT_TYPE=>"0", DRESS3_PRODUCT_TYPE=>"0", DRESS4_PRODUCT_TYPE=>"0", ONEPIECE_PRODUCT_TYPE=>"228", ), //春夏
			//::"3"=>array(DRESS_PRODUCT_TYPE=>"0", DRESS3_PRODUCT_TYPE=>"0", DRESS4_PRODUCT_TYPE=>"0", ONEPIECE_PRODUCT_TYPE=>"149", ), //秋冬
			"1"=>array(DRESS_PRODUCT_TYPE=>"0", SET_DRESS_PRODUCT_TYPE => "0", ONEPIECE_PRODUCT_TYPE=>"229", ), //オールシーズン
			"2"=>array(DRESS_PRODUCT_TYPE=>"0", SET_DRESS_PRODUCT_TYPE => "0", ONEPIECE_PRODUCT_TYPE=>"228", ), //春夏
			"3"=>array(DRESS_PRODUCT_TYPE=>"0", SET_DRESS_PRODUCT_TYPE => "0", ONEPIECE_PRODUCT_TYPE=>"149", ), //秋冬
		);

        //::N00080 Add 20130912
        //商品検索カテゴリ
        $this->arrMPSC_AGE = $masterData->getMasterData("mtb_products_search_category_age");
        //$this->arrMPSC_EVENT = $masterData->getMasterData("mtb_products_search_category_event");
		$this->arrMPSC_EVENT = array('1'=>'結婚式お呼ばれ','2'=>'結婚式ご親族','3'=>'結婚式花嫁2次会','4'=>'パーティー','5'=>'謝恩会','6'=>'結婚式二次会');
        $this->arrMPSC_SIZE = $masterData->getMasterData("mtb_products_search_category_size");
        $this->arrMPSC_COMPLEX = $masterData->getMasterData("mtb_products_search_category_complex");
        $this->arrMPSC_WORRY = $masterData->getMasterData("mtb_products_search_category_worry");

        //::N00080 Add 20130912
        $this->arrAge = array(
                              "cb_age_10"    =>"^1.......",	//10代
                              "cb_age_20fh"  =>"^.1......",	//20代前半
                              "cb_age_20sh"  =>"^..1.....",	//20代後半
                              "cb_age_30fh"  =>"^...1....",	//30代前半
                              "cb_age_30sh"  =>"^....1...",	//30代後半
                              "cb_age_40fh"  =>"^.....1..",	//40代前半
                              "cb_age_40sh"  =>"^......1.",	//40代後半
                              "cb_age_50over"=>"^.......1",	//50代〜
        );
        $this->arrEvent = array(
                              "cb_event1"  =>"^1....",	//結婚式お呼ばれ
                              "cb_event2"  =>"^.1...",	//結婚式ご親族
                              "cb_event3"  =>"^..1..",	//結婚式花嫁2次会
                              "cb_event4"  =>"^...1.",	//パーティー
                              "cb_event5"  =>"^....1",	//謝恩会
                              "cb_event6"  =>"^.....1",	//二次会
        );
        $this->arrQuality = array(
                              "cb_quality1"  =>"^....1",	//新品同様
        );
        $this->arrSizeFailure = array(
                              "cb_size1"    =>"^1.",	//背中のひもでサイズを調節でき、体にぴったりフィットするドレス
                              "cb_size2"    =>"^.1",	//着心地が楽な、締めつけ感のないゆったりドレス
        );
        $this->arrComplex = array(
                              "cb_complex1"  =>"^1....",	//ぽっこりお腹カバー
                              "cb_complex2"  =>"^.1...",	//ぽっちゃり二の腕カバー
                              "cb_complex3"  =>"^..1..",	//大きめバストすっきり
                              "cb_complex4"  =>"^...1.",	//ひかえめバストふっくら
                              "cb_complex5"  =>"^....1",	//大きめヒップカバー
        );
        $this->arrWorry = array(
                              "cb_worry1"  =>"^1.",	//生地が丈夫で、抱っこしやすい袖つきドレス
                              "cb_worry2"  =>"^.1",	//授乳しやすいドレス
        );
        //$this->tpl_title = 'ドレス一覧';
				if(isset($_REQUEST['n_age'])){ $this->tpl_title = 'ネックレス一覧'; }
        //::N00080 end 20130912


	}
    /* init() end */

	/**
	 * Page のプロセス.
	 *
	 * @return void
	 */
	function process() {

	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0");
	header("Pragma: no-cache");

		if (isset($_REQUEST['call_type']) && $_REQUEST['call_type'] == "json"){
			foreach ($_GET as $key=>$value){
				$new_key = str_ireplace("&", "", $key);
				$new_key = str_ireplace("amp;", "", $new_key);
				unset($_GET[$key]);
				$_GET[$new_key] = $value;
			}
		}

		$objView = new SC_SiteView();
		$conn = SC_Query_Ex::getSingletonInstance();
		$objDb = new SC_Helper_DB_Ex();

		//表示件数の選択
		if(isset($_POST['disp_number'])
		&& SC_Utils_Ex::sfIsInt($_POST['disp_number'])) {
			$this->disp_number = $_POST['disp_number'];
		} else {
            //::N00191 Add 20140702
            if (isset($_GET['req_all_products'])) {
                if($this->device_type_id == DEVICE_TYPE_SMARTPHONE){
                    $this->arrPRODUCTLISTMAX = array(100 => '100件');
                }
            }
            //::N00191 Add 20140702
			//最小表示件数を選択
			$this->disp_number = current(array_keys($this->arrPRODUCTLISTMAX));
		}
		// GETにcategory_idが入らないケースがある
		if(empty($_GET['category_id'])){
			$_GET['category_id'] = $_POST['category_id'];
		}

		// 2015.09.22 カテゴリ未選択かつ商品コード検索でない場合はカテゴリをドレスに変更
		if(empty($_GET['category_id']) && empty($_GET['name'])) {
			$_GET['category_id']  = "dress";
		}
        // 20210115 add パンツドレスにcategory_idが入ってきてしまうのでnullで対応
        if( $_GET['name'] === PCODE_PANTSDRESS )
        {
            $_GET['category_id'] = '';
        }
		//表示順序の保存
		$this->orderby = isset($_POST['orderby']) ? $_POST['orderby'] : "";
		// GETのカテゴリIDを元に正しいカテゴリIDを取得する。
		if(is_numeric($_GET['category_id']) && $_GET['category_id'] > 0){
			$arrCategory_id = $objDb->sfGetCategoryId("", $_GET['category_id']);
		}else{
			//$arrCategory_id[0] = 0;
			$arrCategory_id[0] = $_GET['category_id'];
		}

		// カテゴリーが空の配列でくる場合はドレス商品
		if (empty($arrCategory_id) == true) {
			// GETを修正
			$_GET['category_id'] = "";
			// カテゴリ作成 強制でドレス商品を表示
			$arrCategory_id = array();
			$arrCategory_id[0] = "0";
		}

		if (!isset($_GET['mode'])) $_GET['mode'] = "";
		if (!isset($_REQUEST['name']) || (isset($_REQUEST['name']) && $_REQUEST['name']=='商品コード')) {
			$_REQUEST['name'] = "";
			$_GET['name'] = "";
			$_POST['name'] = "";
		}
		if (!isset($_POST['orderby'])) $_POST['orderby'] = "";
		if (empty($arrCategory_id)) $arrCategory_id = array("0");


		if (($_GET['category_id'] === CATEGORY_DRESS_ALL)){
			$this->selectKind2val = $_GET['kind2'];
			$this->selectKind3val = $_GET['kind3'];

			//::if (!isset($_GET['kind4'])) $_GET['kind4'] = CATEGORY_DRESS4;
			//::if (!isset($_GET['kind3'])) $_GET['kind3'] = CATEGORY_DRESS3;
			//print_r($_GET['kind3']);die();
			if (empty($_GET['kind3']) && empty($_GET['kind2'])){
				$_GET['kind3'] = CATEGORY_SET_DRESS;
				$_GET['kind2'] = CATEGORY_DRESS;
			}
			//print_r(empty($_GET['kind2']);die();
//			if (!isset($_GET['kind3'])) $_GET['kind3'] = CATEGORY_SET_DRESS;//::N00083 Add 20131201
//			if (!isset($_GET['kind2']))	$_GET['kind2'] = CATEGORY_DRESS;
		}

		if($_GET['category_id'] == CATEGORY_ONEPIECE){
			 if(!isset($_GET['kind1'])){$_GET['kind1'] = CATEGORY_ONEPIECE;}

			 //::$_GET['kind4'] = "";
			 //::$_GET['kind3'] = "";
			 $_GET['kind3'] = "";//::N00083 Add 20131201
			 $_GET['kind2'] = "";
		}

		if (!isset($_GET[GARA])){
			$_GET[GARA] = 0;
		}

		//pankuzu, h1 ,title
		$this->tpl_pkx_detail = '';
		$this->tpl_subtitle = "";
		$this->h1_title = "";
		$sub_kind_title = "";

	  $arr_pkz_cki_name = array('pankuzu_color', 'pankuzu_scene', 'pankuzu_age', 'pankuzu_size', 'pankuzu_complex', 'pankuzu_size_failure', 'pankuzu_worry', 'pankuzu_quality', 'pankuzu_age_necklace');

	  foreach ($arr_pkz_cki_name as $key => $value) {
	      setcookie($value, '', time() - 30);
	      unset($_COOKIE[$value]);
	  }

		$array_color = array(100=>'ブラック', 101=>'ネイビー', 110=>'シルバー/グレー', 111=>'パープル/ラベンダー', 120=>'ブルー/グリーン', 131=>'レッド/オレンジ', 130=>'ピンク', 140=>'ブラウン', 141=>'イエロー/ベージュ', 150=>'ホワイト');
		$arr_event = array("cb_event1"=>"結婚式・お呼ばれ", "cb_event2"=>"結婚式・ご親族", "cb_event3"=>"花嫁様向け二次会", "cb_event4"=>"パーティー", "cb_event5"=>"謝恩会・同窓会", "cb_event6"=>"結婚式二次会");
		$arr_age = array("cb_age_10"=>"10代", "cb_age_20fh"=>"20代前半", "cb_age_20sh"=>"20代後半", "cb_age_30fh"=>"30代前半", "cb_age_30sh"=>"30代後半", "cb_age_40fh"=>"40代前半", "cb_age_40sh"=>"40代後半", "cb_age_50over"=>"50代〜");
		$arr_size = array(1=>'SSサイズ', 2=>'Sサイズ', 3=>'Mサイズ', 4=>'Lサイズ', 5=>'LLサイズ', 6=>'3Lサイズ', 7=>'4Lサイズ', 8=>'');
		$arr_complex = array("cb_complex1"=>"お腹カバー", "cb_complex2"=>"袖付きドレス", "cb_complex3"=>"大きめバストカバー", "cb_complex4"=>"控えめバストカバー");
		$arr_size_failure = array("cb_size1"=>"背中の調節紐", "cb_size2"=>"ゆったりドレス");
		$arr_worry = array("cb_worry1"=>"抱っこしやすい", "cb_worry2"=>"授乳口付き");
		$arr_len_chk = array(1=>'ひざ上', 2=>'ひざ丈', 3=>'ひざ下');
		$arr_season = array(1=>'オールシーズン', 2=>'春夏', 3=>'秋冬');

		//羽織
		$arr_haori = array("000_78"=>"ストール", "001_79"=>"ボレロ");
		$arr_haori_size = array("000_80"=>"S", "001_81"=>"M", "002_82"=>"L", "003_200"=>"LL", "004_273"=>"3L", "005_274"=>"4L");
		$arr_haori_color = array("000_84"=>"白", "001_85"=>"シルバー", "002_86"=>"ベージュ/ゴールド", "003_87"=>"黒", "004_88"=>"ピンク");

		//ネックレス
		$arr_NECKLACE_AGE = array("000_294"=>'10代', "001_295"=>'20代', "002_296"=>'30代', "003_297"=>'40代', "004_298"=>'50代〜');
		$arr_NECKLACE_LEN = array("000_290"=>"ショート丈", "001_291"=>"ミディアム丈", '002_141'=>'ロング丈');
		$arr_NECKLACE_COLOR = array("003_137"=>'シルバー', "002_138"=>'ゴールド', "000_140"=>'パール', "001_139"=>'ピンク', "004_136"=>'黒', '005_135'=>'その他');

		//小物系
		$arr_belt_color = array("002_318"=>'シルバー', "003_319"=>'ゴールド', "004_320"=>'ピンク', "001_317"=>'ネイビー', "000_316"=>'黒', "005_321"=>'白/その他');
		$arr_belt_size = array("000_307"=>'S', "001_308"=>'M', "002_309"=>'L', "003_310"=>'LL', "004_311"=>'３L');
		$arr_corsage_color = array("000_335"=>'シルバー', "001_336"=>'ゴールド', "002_337"=>'緑', "003_338"=>'青', "005_340"=>'赤/ピンク', "004_339"=>'黒', "006_341"=>'白/その他');

		if(isset($_GET[SIZE])){
			//ドレス
				for ($i=0; $i < count($arr_size); $i++) {
					$jp_size .= $arr_size[$_GET[SIZE][$i]] ? $arr_size[$_GET[SIZE][$i]].',' : '';
				}
				setcookie('pankuzu_size', $jp_size);
				$sub_kind_title .= $jp_size;
			//羽織
				for ($i=0; $i < count($arr_haori_size); $i++) {
					$jp_haori_size .= $arr_haori_size[$_GET[SIZE][$i]] ? $arr_haori_size[$_GET[SIZE][$i]].',' : '';
				}
				$haori_title .= $jp_haori_size;
		}
		if(isset($_GET[COLOR])){
				//ドレス
				for ($i=0; $i < count($array_color); $i++) {
					$jp_color .= $array_color[$_GET[COLOR][$i]] ? $array_color[$_GET[COLOR][$i]].',' : '';
				}
				setcookie('pankuzu_color', $jp_color);
				$sub_kind_title .= $jp_color;

				//羽織
				for ($i=0; $i < count($arr_haori_color); $i++) {
					$jp_haori_color .= $arr_haori_color[$_GET[COLOR][$i]] ? $arr_haori_color[$_GET[COLOR][$i]].',' : '';
				}
				$haori_title .= $jp_haori_color;

				//ネックレス
				for ($i=0; $i < count($arr_NECKLACE_COLOR); $i++) {
					$jp_n_color .= $arr_NECKLACE_COLOR[$_GET[COLOR][$i]] ? $arr_NECKLACE_COLOR[$_GET[COLOR][$i]].',' : '';
				}
				$sub_kind_title .= $jp_n_color;
		}
		//ドレスの年代
		if(isset($_GET[AGE])){
			for ($i=0; $i < count($arr_age); $i++) {
				$jp_age .= $arr_age[$_GET[AGE][$i]] ? $arr_age[$_GET[AGE][$i]].',' : '';
			}
			setcookie('pankuzu_age', $jp_age);
			$sub_kind_title .= $jp_age;
		}

		//季節(ワンピース)
		if(isset($_GET[SEASON])){
			for ($i=0; $i < count($arr_season); $i++) {
				$jp_season .= $arr_season[$_GET[SEASON][$i]] ? $arr_season[$_GET[SEASON][$i]].',' : '';
			}
			$sub_kind_title .= $jp_season;
		}
		//丈
		if(isset($_GET['len_chk'])){
				for ($i=0; $i < count($arr_len_chk); $i++) {
					$len_chk .= $arr_len_chk[$_GET['len_chk'][$i]] ? $arr_len_chk[$_GET['len_chk'][$i]].',' : '';
				}
				$sub_kind_title .= $_GET['len_knee_sel'].'cm-'.$len_chk;
		}
		//ネックレス長さ
		if(isset($_GET[LEN])){
			for ($i=0; $i < count($arr_NECKLACE_LEN); $i++) { 
				$ncless_len .= $arr_NECKLACE_LEN[$_GET[LEN][$i]] ? $arr_NECKLACE_LEN[$_GET[LEN][$i]].',' : '';
			}
				$sub_kind_title .= $ncless_len;
		}
		//羽織
		if(isset($_GET[TYPE])){
			for ($i=0; $i < count($arr_haori); $i++) {
				$jp_haori .= $arr_haori[$_GET[TYPE][$i]] ? $arr_haori[$_GET[TYPE][$i]] : '';
			}
		}
		//ドレス詳細条件
		if(isset($_GET[EVENT])){
			for ($i=0; $i < count($arr_event); $i++) {
				$jp_event .= $arr_event[$_GET[EVENT][$i]] ? $arr_event[$_GET[EVENT][$i]].',' : '';
			}
			$sub_kind_title .= $jp_event;
			setcookie('pankuzu_scene', $jp_event);
		}
		if(isset($_GET[COMPLEX])){
			for ($i=0; $i < count($arr_complex); $i++) {
				$jp_complex .= $arr_complex[$_GET[COMPLEX][$i]] ? $arr_complex[$_GET[COMPLEX][$i]].',' : '';
			}
			$sub_kind_title .= $jp_complex;
			setcookie('pankuzu_complex', $jp_complex);
		}
		if(isset($_GET[SIZE_FAILURE])){
			foreach ($arr_size_failure as $key => $value) {
				if( $key == $_GET[SIZE_FAILURE][$key] ){ $jp_size_failure = $value; }
			}
			$sub_kind_title .= $jp_size_failure;
			setcookie('pankuzu_size_failure', $jp_size_failure);
		}
		if (isset($_GET[WORRY])) {
				foreach ($arr_worry as $key => $value) {
				if( $key == $_GET[WORRY][$key] ){ $jp_worry = $value; }
			}
			$sub_kind_title .= $jp_worry;
			setcookie('pankuzu_worry', $jp_worry);
		}
		if(isset($_GET[QUALITY][0])){
			setcookie('pankuzu_quality', '新品同様');
			$sub_kind_title .= '新品同様';
		}
		if(isset($_REQUEST['n_age'])){
			for ($i=0; $i < count($arr_NECKLACE_AGE); $i++) { 
				$jp_necklace_age .= $arr_NECKLACE_AGE[$_REQUEST['n_age'][$i]] ? $arr_NECKLACE_AGE[$_REQUEST['n_age'][$i]].',' : '';
			}
			setcookie('pankuzu_age_necklace', $jp_necklace_age);
			setcookie('age_necklace_url', $_REQUEST['n_age'][0]);
			$sub_kind_title .= $jp_necklace_age;
		}
		//小物系
		foreach ($_GET['belt_color'] as $key => $value) {
			$sub_kind_title .= $arr_belt_color[$value].',';
		}
		setcookie('pkz_belt_color', $sub_kind_title);

		foreach ($_GET['belt_size'] as $key => $value) {
			$sub_kind_title .= $arr_belt_size[$value].',';
		}
		setcookie('pkz_belt_size', $sub_kind_title);

		foreach ($_GET['corsage_color'] as $key => $value) {
			$sub_kind_title .= $arr_corsage_color[$value].',';
		}
		setcookie('pkz_corsage_color', $sub_kind_title);

		//文字の最後に,があったら外す
		if (substr($sub_kind_title , -1) == ',') {
			$sub_kind_title = rtrim($sub_kind_title, ',');
		}

	//ドレス系：単品、セット
	if(strpos($_SERVER['REQUEST_URI'],'category_id=dress') !== false && $_REQUEST[SIZE][0] == 8){
					$this->tpl_subtitle = "マタニティードレス一覧";
					$this->h1_title = $sub_kind_title ? $sub_kind_title.'｜' : '';

	}elseif(strpos($_SERVER['REQUEST_URI'],'category_id=dress&kind3=232') !== false){
					$this->tpl_subtitle = "セットドレス一覧";
					$this->h1_title = $sub_kind_title ? $sub_kind_title.'｜' : '';

	}elseif(strpos($_SERVER['REQUEST_URI'],'category_id=dress') !== false || strpos($_SERVER['REQUEST_URI'],'kind2=44') !== false){
					$this->tpl_subtitle = "ドレス一覧";
					$this->h1_title = $sub_kind_title ? $sub_kind_title.'｜' : '';

	//cate_idが空の商品
	}elseif(empty($_GET['category_id'])) {
			if($_GET['name'] == '19-'){
					$this->tpl_subtitle = "パンツドレス一覧";
			}elseif($_GET['name'] == 'black_f'){
					$this->tpl_subtitle = "ブラックフォーマル一覧";
			}elseif($_GET['name'] == 'boy'){
					$this->tpl_subtitle = "キッズフォーマル(boy)一覧";
			}elseif($_GET['name'] == 'girl'){
					$this->tpl_subtitle = "キッズフォーマル(girl)一覧";
			}elseif(!empty($_GET['name']) && !preg_match("/^[0-9]+$/",$_GET['name'])){
				//ブランド名を取得するなどで使用
				$this->tpl_subtitle = $_GET['name'];
			}
	}else{
			switch ($arrCategory_id[0]){
				case CATEGORY_NECKLACE:
					$this->tpl_subtitle = "ネックレス一覧";
					$this->h1_title = $sub_kind_title ? $sub_kind_title.'｜' : '';
					break;

				case CATEGORY_STOLE:
					$this->tpl_subtitle = $jp_haori ? $jp_haori."一覧" : '羽織物一覧';
					$this->h1_title = $haori_title ? $haori_title.'｜' : '';
					break;

				case CATEGORY_OTHERS:
					$this->tpl_subtitle = "その他の小物一覧";

					if ($_REQUEST[TYPE][0] == '005_352') {
						$this->tpl_subtitle = "ヘアアクセサリー一覧";
					}elseif($_REQUEST[TYPE][0] == '001_144' || $_REQUEST[TYPE][0] == '003_144'){
						$this->tpl_subtitle = "ベルト一覧";
						$this->h1_title = $sub_kind_title ? $sub_kind_title.'｜' : '';
					}elseif($_REQUEST[TYPE][0] == '006_370'){
						$this->tpl_subtitle = "イヤリング一覧";
					}elseif($_REQUEST[TYPE][0] == '000_143' || $_REQUEST[TYPE][0] == '005_143'){
						$this->tpl_subtitle = "コサージュ・ブローチ一覧";
						$this->h1_title = $sub_kind_title ? $sub_kind_title.'｜' : '';
					}elseif($_REQUEST[TYPE][0] == '003_179' || $_REQUEST[TYPE][0] == '004_179'){
						$this->tpl_subtitle = "ブレスレット一覧";
					}
					break;

				case CATEGORY_ONEPIECE:
					$this->tpl_subtitle = "ワンピース一覧";
					$this->h1_title = $sub_kind_title ? $sub_kind_title.'｜' : '';
					break;

				case CATEGORY_BAG:
					$this->tpl_subtitle = "バッグ一覧";
					break;

				case CATEGORY_CEREMONYSUIT:
					$this->tpl_subtitle = "セレモニースーツ一覧";
					break;

				case CATEGORY_KIDS:
					$this->tpl_subtitle = "キッズフォーマル一覧";
					break;

				case CATEGORY_KIDS_DRESS:
					$this->tpl_subtitle = "キッズドレス一覧";
					break;

				case CATEGORY_COAT:
					$this->tpl_subtitle = "コート一覧";
					break;

				default:
					$this->tpl_subtitle = "商品一覧";
					break;
			}
		}

	$this->pankuzu_title = $this->h1_title != NULL ? '> '.rtrim($this->h1_title, '｜') : rtrim($this->h1_title, '｜');

		$count = 0;
		if ($arrCategory_id[0] == CATEGORY_DRESS_ALL){
			$arrCategory_id[0] = 0;
		}
		if ($arrCategory_id[0] === CATEGORY_SNO){
			$arrCategory_id[0] = CATEGORY_STOLE;

		}

		$objQuery = new SC_Query();
		$count = $objQuery->count("dtb_best_products", "category_id = ?", $arrCategory_id);

		// 以下の条件でBEST商品を表示する
		// ・BEST最大数の商品が登録されている。
		// ・カテゴリIDがルートIDである。
		// ・検索モードでない。
		if(($count >= BEST_MIN) && $this->lfIsRootCategory($arrCategory_id[0]) && ($_GET['mode'] != 'search') ) {
			// 商品TOPの表示処理
			$this->arrBestItems = SC_Utils_Ex::sfGetBestProducts($conn, $arrCategory_id[0]);
			$this->BEST_ROOP_MAX = ceil((BEST_MAX-1)/2);
		} else {
			if (($_GET['mode'] == 'search' || $_GET['mode'] == 'category_search' ) && strlen($_GET['category_id']) == 0 ){
				// 検索時にcategory_idがGETに存在しない場合は、仮に埋めたIDを空白に戻す
				$arrCategory_id = array(0);
			}


			if(empty($_GET['category_id']) && isset($_REQUEST['kind_all'])){
				$arrCategory_id[0] = CATEGORY_ALL;
			}

			if(empty($_GET['category_id']) && empty($arrCategory_id[0])){

				$this->lfDispProductsList1($arrCategory_id[0], $_GET['name'], $this->disp_number, $_POST['orderby']);
			}else{
				$this->lfDispProductsList($arrCategory_id[0], $_GET['name'], $this->disp_number, $_POST['orderby']);

			}

			//サイズを表示
			$this->cntSize = array();
			$this->pro_size_text = array();
			$this->kidsSizeText = array();

			foreach ($this->arrProducts AS $key=>$val) {
					//kidsフォーマル、kidsドレス
			    if($_GET['category_id'] == 371 || $_GET['category_id'] == 375 || $_GET['name'] == 'boy' || $_GET['name'] == 'girl'){
			    	$arrExtKids = $objQuery->select("figure_detail_kids", "dtb_products_ext", "product_id = ?", array($val['product_id']));
						$pd_size_kids = $arrExtKids[0]['figure_detail_kids'];
						$cntSizekids = count($pd_size_kids);

			        //サイズが複数ある場合
			        if ($cntSizekids > 1) {
				        foreach ($this->arrKisdsSIZEtext as $num => $value) {
				        	for ($i=0; $i < $cntSizekids; $i++) {
					        	if ($num == intval($pd_size_kids[$i])) {
					        		array_push($multipleSize, $value . ',');
					        	}
				        	}
				        }
				      $multipleSize[$cntSizekids-1] = substr($multipleSize[$cntSizekids-1], 0, -1);
				      array_push($this->kidsSizeText, $multipleSize);				   
			        }else{
			        	foreach ($this->arrKisdsSIZEtext as $num => $value) {
				        	if ($num == intval($pd_size_kids[0])) {
				        		array_push($this->kidsSizeText, $value);
				        	}
			        	}
			        }
			    //kids以外
			    }else{
			        $arrExtRet = $objQuery->select("figure_detail", "dtb_products_ext", "product_id = ?", array($val['product_id']));
			        $product_size = unserialize($arrExtRet[0]['figure_detail']);
			        $cntSize = count($product_size);
			        array_push($this->cntSize, $cntSize);
			        $multipleSize = array();
			        $lastText = '';

			        //サイズが複数ある場合
			        if ($cntSize > 1) {
				        foreach ($this->arrSIZEtext as $num => $value) {
				        	for ($i=0; $i < $cntSize; $i++) {
						        if($i == 0 && $num != 8 && $num == intval($product_size[$i])){
						        	array_push($multipleSize, $value.'〜');

						        //マタニティだったら頭にカンマ
				        		}elseif($num == 8 && $num == intval($product_size[$i])){
				        			array_push($multipleSize, ','.$value);

				        		}elseif($_GET['category_id'] != 64 && $i == $cntSize-2 && $num == intval($product_size[$i])){
				        				if($value != NULL){ array_push($multipleSize, $value); }

				        		}elseif($i == $cntSize-1 && $num == intval($product_size[$i])){
				        			array_push($multipleSize, $value);
				        		}
				        	}
				        }
								if (count($multipleSize) == 2 && $multipleSize[1] == ',マタニティ') {
									$multipleSize[0] = rtrim($multipleSize[0], '〜');
								}
				      	array_push($this->pro_size_text, $multipleSize);

				      //サイズが１つしかない場合
			        }else{
			        	foreach ($this->arrSIZEtext as $num => $value) {
				        	if ($num == intval($product_size[0])) {
				        		array_push($this->pro_size_text, $value);
				        	}
			        	}
			        }
			    }
			}
     //::N00171 Add 20140520
        $objCookie = new SC_Cookie(28);
        $product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);
        if (!empty($product_ids)) {
            $this->arrFavProducts = explode(",", $product_ids);
        }
        foreach ($this->arrProducts AS $key=>$val) {
            if (in_array($val['product_id'],$this->arrFavProducts)) {
                $this->arrProducts[$key]['fav_pid'] = $val['product_id'];
            }
            // add ishibashi 20220121
            $this->arrProducts[$key] = SC_Utils_Ex::productReplaceWebp($val);
        }

        switch ($_REQUEST['mode']) {
        case 'add_favorite':
        case 'add_favorite_sphone':
            //$objCookie = new SC_Cookie(28);
            //$product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);

            $this->favorite_product_id = isset($_GET['favorite_product_id']) ? $_GET['favorite_product_id'] : "";

            $arrFavProducts = array();
            if (!empty($product_ids)) {
                $arrFavProducts = explode(",", $product_ids);
                //$this->arrFavProducts = explode(",", $product_ids);
            }

            if (in_array($this->favorite_product_id, $arrFavProducts)) {

                $this->tpl_javascript .= "alert('すでに登録されています。');\n";
                //if ($_GET['mode'] == "add_favorite_sphone" ){echo "true"; exit;}
                break;
            }
            $this->tpl_javascript .= "alert('登録しました');\n";
            $arrFavProducts = array_merge(array(strval($this->favorite_product_id)), $arrFavProducts);

            if (count($arrFavProducts) > FAVOFITE_PRODUCT_MAX) {
                array_splice($arrFavProducts, FAVOFITE_PRODUCT_MAX);
            }

            $objCookie->setCookie(FAVORITE_PRODUCT_COOKIE, implode(",", $arrFavProducts));

            //if ($_GET['mode'] == "add_favorite_sphone" ){echo "true"; exit;}

            header("Location: " . $_SERVER['REQUEST_URI']);

            break;
        default:
            break;
        }
        //::N00171 end 20140520


			// 検索条件を画面に表示
			// カテゴリー検索条件
			if (strlen($_GET['category_id']) == 0) {
				$arrSearch['category'] = "指定なし";
			}else{
				$arrCat = $conn->getOne("SELECT category_name FROM dtb_category WHERE category_id = ?", $arrCategory_id);
				$arrSearch['category'] = $arrCat;
			}

			// 商品名検索条件
			if ($_GET['name'] === "") {
				$arrSearch['name'] = "指定なし";
			}else{
				$arrSearch['name'] = $_GET['name'];
			}
		}

		// レイアウトデザインを取得
		//$layout = new SC_Helper_PageLayout_Ex();
		//$layout->sfGetPageLayout($this, false, "products/list.php");

		if (isset($_REQUEST['call_type']) && $_REQUEST['call_type'] == "json"){
			$this->doJson();
		}


$this->tpl_mainpage =  'products/list.tpl';
		if(isset($_POST['mode']) && $_POST['mode'] == "cart" && $_POST['product_id'] != "") {
			// 値の正当性チェック
			if(!SC_Utils_Ex::sfIsInt($_POST['product_id']) || !$objDb->sfIsRecord("dtb_products", "product_id", $_POST['product_id'], "del_flg = 0 AND status = 1")) {
				SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
			} else {

				// 入力値の変換
				$this->arrErr = $this->lfCheckError($_POST['product_id']);
				if(count($this->arrErr) == 0) {
					$objCartSess = new SC_CartSession();
					$classcategory_id = "classcategory_id". $_POST['product_id'];
					$classcategory_id1 = $_POST[$classcategory_id. '_1'];
					$classcategory_id2 = $_POST[$classcategory_id. '_2'];
					$quantity = "quantity". $_POST['product_id'];
					// 規格1が設定されていない場合
					if(!$this->tpl_classcat_find1[$_POST['product_id']]) {
						$classcategory_id1 = '0';
					}
					// 規格2が設定されていない場合
					if(!$this->tpl_classcat_find2[$_POST['product_id']]) {
						$classcategory_id2 = '0';
					}
					$objCartSess->setPrevURL($_SERVER['REQUEST_URI']);
					$objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $_POST[$quantity]);
					SC_Response_Ex::sendRedirect($this->getLocation(URL_CART_TOP));
					SC_Response_Ex::actionExit();
				}
			}
		}

		//$this->tpl_subtitle = $this->tpl_subtitle;

		// 支払方法の取得
		$this->arrPayment = $this->lfGetPayment();
		// 入力情報を渡す
		$this->arrForm = $_POST;

		$this->lfConvertParam();
		$this->category_id = $_GET["category_id"];//$arrCategory_id[0];
		$this->arrSearch = $arrSearch;

		$objReserveUtil = new SC_Reserve_Utils();
		$reserve_days = $objReserveUtil->getReserveDays();

		$cal_link_days = "{";
		foreach ($reserve_days as $main_key=>$row_day){
			$cal_link_days .= "'".$main_key."':{";
			foreach ($row_day as $key=>$value) {
				$cal_link_days .= $key.":'".$value."',";
			}
			$cal_link_days = rtrim($cal_link_days, ",");
			$cal_link_days .= "},";
		}
		$cal_link_days = rtrim($cal_link_days, ",");
		$cal_link_days .= "};";
		$this->tpl_javascript = "var rental_possible_date = ".$cal_link_days;

		$this->tpl_javascript .= "\nvar server_date = '".date("Y-m-d")."';";
		$this->tpl_javascript .= "\nvar limit_date = '".$objReserveUtil->getLimitDate()."';";

		// ブランド一覧追加
		$this->arrBrand = $this->lfGetBrand();

		if($_GET['name'] == '19-' || $_GET['name'] == '15-' || $_GET['name'] == '82-' || $_GET['name'] == NULL){
			//nameをカテゴリとして使っている場合、nameがnullの場合はブランド名じゃない
		}else{
			$this->title_brand = '｜'.$_GET['name'];
		}

		$this->arrUsefulMemo = $this->lfGetUsefulMemo();

		/*201809 add*/
		$this->arrProductCount = $this->lfGetProductCount();

        /* 最近チェックした商品 20201211 add*/
        $this->arrRecent = $this->lfPreGetRecentProducts($_POST['product_id']);

		// set month
		$this->tpl_current_month = date("n");
		$this->tpl_next_month = $this->tpl_current_month + 1;
		if ($this->tpl_next_month > 12){$this->tpl_next_month = $this->tpl_next_month - 12;}
		$this->tpl_next_next_month = $this->tpl_current_month + 2;
		if ($this->tpl_next_next_month > 12){$this->tpl_next_next_month = $this->tpl_next_next_month - 12;}

        // add ishibashi 日程検索
        $_SESSION['rental_date'] = isset($_GET['rental_date']) ? $_GET['rental_date'] : '';

	    $this->sendResponse();
	}

	/**
	 * モバイルページを初期化する.
	 *
	 * @return void
	 */
	function mobileInit() {
		$this->init();
	}

	/**
	 * Page のプロセス(モバイル).
	 *
	 * FIXME スパゲッティ...
	 *
	 * @return void
	 */
	function mobileProcess() {
		$objView = new SC_MobileView();
		$conn = new SC_DBConn();
		$objDb = new SC_Helper_DB_Ex();

		//表示件数の選択
		if(isset($_REQUEST['disp_number'])
		&& SC_Utils_Ex::sfIsInt($_REQUEST['disp_number'])) {
			$this->disp_number = $_REQUEST['disp_number'];
		} else {
			//最小表示件数を選択
			$this->disp_number = current(array_keys($this->arrPRODUCTLISTMAX));
		}

		//表示順序の保存
		$this->orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : "";

		// GETのカテゴリIDを元に正しいカテゴリIDを取得する。
		$arrCategory_id = $objDb->sfGetCategoryId("", $_GET['category_id']);


		// タイトル編集
		$this->tpl_subtitle = "";
		$tpl_search_mode = false;

		if (!isset($_GET['mode'])) $_GET['mode'] = "";
		if (!isset($_POST['mode'])) $_POST['mode'] = "";
		if (!isset($_GET['name'])) $_GET['name'] = "";
		if (!isset($_REQUEST['orderby'])) $_REQUEST['orderby'] = "";
		if (empty($arrCategory_id)) $arrCategory_id = array("0");

		if($_GET['mode'] == 'search'){
			// $this->tpl_subtitle = "検索結果";
			$tpl_search_mode = true;
		}elseif (empty($arrCategory_id)) {
			$this->tpl_subtitle = "商品一覧";
		}else{
			$arrFirstCat = $objDb->sfGetFirstCat($arrCategory_id[0]);
			$this->tpl_subtitle = $arrFirstCat['name'];
		}

		$objQuery = new SC_Query();
		$count = $objQuery->count("dtb_best_products", "category_id = ?", $arrCategory_id);

		// 以下の条件でBEST商品を表示する
		// ・BEST最大数の商品が登録されている。
		// ・カテゴリIDがルートIDである。
		// ・検索モードでない。
		if(($count >= BEST_MIN) && $this->lfIsRootCategory($arrCategory_id[0]) && ($_GET['mode'] != 'search') ) {
			// 商品TOPの表示処理

			$this->arrBestItems = SC_Utils_Ex::sfGetBestProducts($conn, $arrCategory_id[0]);
			$this->BEST_ROOP_MAX = ceil((BEST_MAX-1)/2);
		} else {
			if ($_GET['mode'] == 'search' && strlen($_GET['category_id']) == 0 ){
				// 検索時にcategory_idがGETに存在しない場合は、仮に埋めたIDを空白に戻す
				$arrCategory_id = array("");
			}

			// 商品一覧の表示処理
			$this->lfDispProductsList($arrCategory_id[0], $_GET['name'], $this->disp_number, $_REQUEST['orderby']);

			// 検索条件を画面に表示
			// カテゴリー検索条件
			if (strlen($_GET['category_id']) == 0) {
				$arrSearch['category'] = "指定なし";
			}else{
				$arrCat = $conn->getOne("SELECT category_name FROM dtb_category WHERE category_id = ?",array($category_id));
				$arrSearch['category'] = $arrCat;
			}

			// 商品名検索条件
			if ($_GET['name'] === "") {
				$arrSearch['name'] = "指定なし";
			}else{
				$arrSearch['name'] = $_GET['name'];
			}
		}

		if($_POST['mode'] == "cart" && $_POST['product_id'] != "") {
			// 値の正当性チェック
			if(!SC_Utils_Ex::sfIsInt($_POST['product_id']) || !SC_Utils_Ex::sfIsRecord("dtb_products", "product_id", $_POST['product_id'], "del_flg = 0 AND status = 1")) {
				SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND, "", false, "", true);
			} else {
				// 入力値の変換
				$this->arrErr = $this->lfCheckError($_POST['product_id']);
				if(count($this->arrErr) == 0) {
					$objCartSess = new SC_CartSession();
					$classcategory_id = "classcategory_id". $_POST['product_id'];
					$classcategory_id1 = $_POST[$classcategory_id. '_1'];
					$classcategory_id2 = $_POST[$classcategory_id. '_2'];
					$quantity = "quantity". $_POST['product_id'];
					// 規格1が設定されていない場合
					if(!$this->tpl_classcat_find1[$_POST['product_id']]) {
						$classcategory_id1 = '0';
					}
					// 規格2が設定されていない場合
					if(!$this->tpl_classcat_find2[$_POST['product_id']]) {
						$classcategory_id2 = '0';
					}
					$objCartSess->setPrevURL($_SERVER['REQUEST_URI']);
					$objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $_POST[$quantity]);
					SC_Response_Ex::sendRedirect(MOBILE_URL_CART_TOP, array(session_name() => session_id()));
					SC_Response_Ex::actionExit();
				}
			}
		}


		// ページ送り機能用のURLを作成する。
		$objURL = new Net_URL($_SERVER['PHP_SELF']);
		foreach ($_REQUEST as $key => $value) {
			if ($key == session_name() || $key == 'pageno') {
				continue;
			}
			$objURL->addQueryString($key, mb_convert_encoding($value, 'SJIS', CHAR_CODE));
		}

		if ($this->objNavi->now_page > 1) {
			$objURL->addQueryString('pageno', $this->objNavi->now_page - 1);
			$this->tpl_previous_page = $objURL->path . '?' . $objURL->getQueryString();
		}
		if ($this->objNavi->now_page < $this->objNavi->max_page) {
			$objURL->addQueryString('pageno', $this->objNavi->now_page + 1);
			$this->tpl_next_page = $objURL->path . '?' . $objURL->getQueryString();
		}

		//$this->tpl_subtitle = $this->tpl_subtitle;
		$this->tpl_search_mode = $tpl_search_mode;

		// 支払方法の取得
		$this->arrPayment = $this->lfGetPayment();
		// 入力情報を渡す
		$this->arrForm = $_POST;

		$this->category_id = $arrCategory_id[0];
		$this->arrSearch = $arrSearch;
		$this->tpl_mainpage = MOBILE_TEMPLATE_DIR . "products/list.tpl";

		$objView->assignobj($this);
		$objView->display(SITE_FRAME);
	}

	/**
	 * デストラクタ.
	 *
	 * @return void
	 */
	function destroy() {
		parent::destroy();
	}

	/* カテゴリIDがルートかどうかの判定 */
	function lfIsRootCategory($category_id) {
		$objQuery = new SC_Query();
		$level = $objQuery->get("dtb_category", "level", "category_id = ?", array($category_id));
		if($level == 1) {
			return true;
		}
		return false;
	}

	/* 商品一覧の表示 */
	function lfDispProductsList($category_id, $name, $disp_num, $orderby) {
 		// ==== 2014.2.6 RCHJ Add ====
		$arrCategory_id = array();
		$arrCategory_id[0] = $category_id;
		if($_GET['category_id'] === CATEGORY_SNO){
			$arrCategory_id[0] = CATEGORY_STOLE;
			$arrCategory_id[1] = CATEGORY_NECKLACE;
			$arrCategory_id[2] = CATEGORY_OTHERS;
		}
		// ====== End =====

		$objQuery = new SC_Query();
		$objDb = new SC_Helper_DB_Ex();
		$this->tpl_pageno = defined("MOBILE_SITE") ? @$_GET['pageno'] : @$_REQUEST['pageno'];

		if(!isset($this->tpl_pageno)) $this->tpl_pageno = 1;

		// get sending date
		$date1 = ""; $date2 = "";
		if(empty($_REQUEST['hdn_send_day2']) && !empty($_REQUEST['hdn_send_day1'])){
			$date1 = $_REQUEST['hdn_send_day1'];
		}
		if(isset($_REQUEST['chk_use1'])){
			$date1 = $_REQUEST['hdn_send_day1'];
		}
		if(isset($_REQUEST['chk_use2'])){
			$date2 = $_REQUEST['hdn_send_day2'];
		}

		if ($date1 != '') {
			$this->tpl_date1 = '&date1=' . $date1;
			$this->tpl_date2 = '&date2=' . $date2;
		//カレンダーが押されたら
		}else{
			$this->tpl_date1 = $date1;
			$this->tpl_date2 = $date2;
		}

        $wend_day = "";
        if(empty($this->tpl_date2)/* && $week == 3*/){
            $wend_day = $date1;
        }else{
            $wend_day = $date2;
        }
//::B00098 Add 20140618
$sql_zaiko_kakuninn = "";
if (!empty($wend_day)) {
    $sql_zaiko_kakuninn .= <<<EOF
        and pc.stock > (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id = ps.product_id  and ((reserved_from <= '$wend_day' and  reserved_to >= '$wend_day') or sending_date = '$wend_day'))
EOF;
}
//::B00098 Add 20140618


// B00170 20141103 日程指定時に検索結果にドレスが表示されない start
// B00170 20141103 以下のセット商品の在庫確認処理を実行するとドレスが検索されない
// B00170 20141103 また著しい速度低下を招くためコメントアウトし暫定対応とする
/*//::B00125 Add 20140703*/
//::if ((!empty($wend_day)) && ($category_id == CATEGORY_SET_DRESS)) {
// if ((!empty($wend_day)) && (isset($_REQUEST['kind3']))) {//::B00154 Change 20140902
//     $sql_zaiko_kakuninn .= <<<EOF
//         AND
//         /*セットの羽織物*/
//          (SELECT AAAA.stock FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
//           (SELECT set_pcode_stole    FROM dtb_products_class WHERE product_id = pc.product_id) AND BBBB.status <> 2
//          )
//          >
//          (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id =
//           (SELECT AAAA.product_id FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
//            (SELECT set_pcode_stole    FROM dtb_products_class WHERE product_id = pc.product_id) AND BBBB.status <> 2
//           )
//           and ((reserved_from <= '$wend_day' and  reserved_to >= '$wend_day') or sending_date = '$wend_day')
//          )
//         /*セットのネックレス*/
//         and
//          (SELECT AAAA.stock FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
//           (SELECT set_pcode_necklace    FROM dtb_products_class WHERE product_id = pc.product_id) AND BBBB.status <> 2
//          )
//          >
//          (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id =
//           (SELECT AAAA.product_id FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
//            (SELECT set_pcode_necklace    FROM dtb_products_class WHERE product_id = pc.product_id) AND BBBB.status <> 2
//           )
//           and ((reserved_from <= '$wend_day' and  reserved_to >= '$wend_day') or sending_date = '$wend_day')
//          )
//         /*セットのバッグ*/
//         and
//          (SELECT AAAA.stock FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
//           (SELECT set_pcode_bag    FROM dtb_products_class WHERE product_id = pc.product_id)
//          )
//          >
//          (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id =
//           (SELECT AAAA.product_id FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
//            (SELECT set_pcode_bag    FROM dtb_products_class WHERE product_id = pc.product_id)
//           )
//           and ((reserved_from <= '$wend_day' and  reserved_to >= '$wend_day') or sending_date = '$wend_day')
//          )
// EOF;
// }
/*//::B00125 end 20140703*/
// B00170 20141103 日程指定時に検索結果にドレスが表示されない end


$date_times_temp = strtotime($wend_day);
$date_times = mktime(0, 0, 0, date("n", $date_times_temp), date("j", $date_times_temp), date("Y", $date_times_temp));
$now_times = strtotime("now");
$bln_ok = false;

if(($date_times - $now_times) <= (6*24*3600 + (24-21)*3600)){
	$bln_ok = true;
}
		$sql_stole_length = "";
		//if($category_id == CATEGORY_STOLE){
		if(in_array(CATEGORY_STOLE, $arrCategory_id)){
			$sql_stole_length .= <<<EOF
			, CASE
		WHEN ((shoulders_from between 36 and 45) OR (shoulders_to between 36 and 45)) OR ((slength_from between 36 and 45) OR (slength_to between 36 and 45)) THEN '標準丈'
		WHEN (shoulders_from > 45 OR shoulders_to > 45) OR (slength_from > 45 OR slength_to > 45) THEN '長め丈'
	    ELSE '短め丈'
	END as shoulders_txt
EOF;
		}

		$sql = ""; $arr_val = array();
// brand_id追加


		$main_list_image = " ps.main_list_image ";
    	if($this->device_type_id == DEVICE_TYPE_SMARTPHONE){
    		$main_list_image = " ps.main_image as main_list_image ";
    	}

			$sql_bust_size = "";
			if ($orderby == 'bust_size') {
				$sql_bust_size1 = ", CASE WHEN bust2 is null THEN bust1 ELSE bust2 END as bust ";
				$sql_bust_size2 = <<<EOF
				,CAST(substring(bust from '(.|..|...)($|～)') as float) as bust1,
				CAST(substring(bust from '～(.|..|...)($|（)') as float) as bust2
EOF;
			}

		$sql = <<<EOF
Select A.*,
    CASE WHEN A.item_size is null OR A.item_size='' THEN 'なし' ELSE regexp_replace(A.item_size, '（タ(.+)）', '') END as item_size,
	CASE
	    WHEN (garment1 is null OR garment1<86) THEN 'ショート丈'
	    WHEN (garment1 between 86 and 90) OR (garment2 between 86 and 90) THEN 'ひざ丈短め'
	    WHEN (garment1 between 91 and 95) OR (garment2 between 91 and 95) THEN 'ひざ丈'
	    WHEN (garment1 between 96 and 100) OR (garment2 between 96 and 100) THEN 'ひざ丈長め'
	    WHEN (garment1 between 101 and 110) OR (garment2 between 101 and 110) THEN 'ハーフロング丈'
	    ELSE 'ロング丈'
	END as garment_txt,
	CASE
	    WHEN (overall_from is null OR overall_from<45) THEN 'ショート丈'
	    WHEN (overall_from between 45 and 60) OR (overall_to between 45 and 60) THEN 'ミディアム丈'
	    ELSE 'ロング丈'
	END as length_overall_txt
	$sql_stole_length
	$sql_bust_size1
From(
     Select  ps.product_id, pc.product_code, ps.name, ps.icon_flag, pc.price01, pc.price02, ps.update_date,pc.stock,
     ps.create_date,
		  $main_list_image, ps.main_list_comment, ps.mens_review_count, ps.womens_review_count,
		  round(ps.mens_review_avg,1) as mens_review_avg, round(ps.womens_review_avg,1) as womens_review_avg,
		  ps.item_size, CAST(substring(garment_length from '(.|..|...)($|～)') as float) as garment1,
		  CAST(substring(garment_length from '～(.|..|...)($|（)') as float) as garment2, ps.product_type,
		  ps.length_overall, ps.brand_id,
			CAST(substring(length_overall from '([0-9]{1,3})($|～)') as float) as overall_from,
			CAST(substring(length_overall from '[～・]([0-9]{1,3})') as float) as overall_to
			$sql_bust_size2

    ,ps.mpsc_age,ps.mpsc_event,ps.mpsc_size,ps.mpsc_complex,ps.mpsc_worry

	From dtb_products AS ps
	INNER JOIN dtb_products_class as pc ON ps.product_id=pc.product_id
	LEFT JOIN dtb_products_ext AS pe ON pe.product_id=ps.product_id
	Where
		ps.del_flg<>1 and  ps.status = 1

     and  (ps.haiki <> 1 OR ps.haiki IS NULL)
     $sql_zaiko_kakuninn
EOF;

		// ******make enabled table condition****
		// 平日
		$bln_day_cond1 = false; $bln_day_cond2 = false;
		if(empty($date1) || (!empty($date1) && $_REQUEST['hdn_day_mode1'] == RESERVE_PATTEN_SPECDAY)){
			$bln_day_cond1 = true;
		}
		if(empty($date2) || (!empty($date2) && $_REQUEST['hdn_day_mode2'] == RESERVE_PATTEN_SPECDAY)){
			$bln_day_cond2 = true;
		}
		if(empty($date1) && empty($date2)){
			$bln_day_cond1 = false; $bln_day_cond2 = false;
		}
		if($bln_day_cond1 && $bln_day_cond2){
			$sql .= " and ps.order_enable_flg = ? ";
			$arr_val[] = RESERVE_PATTEN_SPECDAY;
		}

		// 休業日
		$bln_day_cond1 = false; $bln_day_cond2 = false;
		if(empty($date1) || (!empty($date1) && $_REQUEST['hdn_day_mode1'] == RESERVE_PATTEN_RESTDAY)){
			$bln_day_cond1 = true;
		}
		if(empty($date2) || (!empty($date2) && $_REQUEST['hdn_day_mode2'] == RESERVE_PATTEN_RESTDAY)){
			$bln_day_cond2 = true;
		}
		if(empty($date1) && empty($date2)){
			$bln_day_cond1 = false; $bln_day_cond2 = false;
		}
		if($bln_day_cond1 && $bln_day_cond2){
			$sql .= " and ps.order_disable_flg <> ? ";
			$arr_val[] = 1;
		}

   //::N00080 Add 20130917
    //拡張検索[年齢]
    if(isset($_REQUEST[AGE])){
        $sql .= " and (";
        $sql .= "substring(ps.mpsc_age from '";
        $or_flg = false;
        foreach ($_REQUEST[AGE] as $key) {
            if ($or_flg == true) {
                $sql .= "|";
            }
            $sql .= $this->arrAge[$key];
            $or_flg = true;
        }
        $sql .= "') is not null ";
        $sql .= ")";
    }

    //拡張検索[シーン]
    if(isset($_REQUEST[EVENT])){
        $sql .= " and (";
        $sql .= "substring(ps.mpsc_event from '";
        $or_flg = false;
        foreach ($_REQUEST[EVENT] as $key) {
            if ($or_flg == true) {
                $sql .= "|";
            }
            $sql .= $this->arrEvent[$key];
            $or_flg = true;
        }
        $sql .= "') is not null ";
        $sql .= ")";
    }

    //拡張検索[新品同様]
    if(isset($_REQUEST[QUALITY])) {
        $sql .= " and (";
        $sql .= "substring(product_flag from '";
        $or_flg = false;
        foreach ($_REQUEST[QUALITY] as $key) {
            if ($or_flg == true) {
                $sql .= "|";
            }
            $sql .= $this->arrQuality[$key];
            $or_flg = true;
        }
        $sql .= "') is not null ";
        $sql .= ")";
    }

    //拡張検索[サイズ]
    if(isset($_REQUEST[SIZE_FAILURE])){
        $sql .= " and (";
        $sql .= "substring(ps.mpsc_size from '";
        $or_flg = false;
        foreach ($_REQUEST[SIZE_FAILURE] as $key) {
            if ($or_flg == true) {
                $sql .= "|";
            }
            $sql .= $this->arrSizeFailure[$key];
            $or_flg = true;
        }
        $sql .= "') is not null ";
        $sql .= ")";
    }

    //拡張検索[お悩み]
    if(isset($_REQUEST[COMPLEX])){
        $sql .= " and (";
        $sql .= "substring(ps.mpsc_complex from '";
        $or_flg = false;
        foreach ($_REQUEST[COMPLEX] as $key) {
            if ($or_flg == true) {
                $sql .= "|";
            }
            $sql .= $this->arrComplex[$key];
            $or_flg = true;
        }
        $sql .= "') is not null ";
        $sql .= ")";
    }

    //拡張検索[心配]
    if(isset($_REQUEST[WORRY])){
        $sql .= " and (";
        $sql .= "substring(ps.mpsc_worry from '";
        $or_flg = false;
        foreach ($_REQUEST[WORRY] as $key) {
            if ($or_flg == true) {
                $sql .= "|";
            }
            $sql .= $this->arrWorry[$key];
            $or_flg = true;
        }
        $sql .= "') is not null ";
        $sql .= ")";
    }

    //::N00080 end 20130917

		// 新機能 カテゴリ複合検索
		switch ($category_id) {
			case CATEGORY_DRESS_ALL:

			case CATEGORY_ONEPIECE:
					//201710 add 商品コードでコートを除外
					$sql .= " and (product_code not like ?) ";
					$arr_val[] = '%'.PCODE_COAT.'%';

			case CATEGORY_DRESS:
					//202011 add 商品コードでセレスーツ、キッズスーツを除外
					$sql .= " and (product_code not like ?) ";
					$sql .= " and (product_code not like ?) ";
					$sql .= " and (product_code not like ?) ";
					$sql .= " and (parent_flg != ?) ";
					$arr_val[] = '%'.'CM'.'%';
					$arr_val[] = '%'.PCODE_KIDS.'%';
					$arr_val[] = PCODE_KIDS_DRESS.'%';
					$arr_val[] = 0;
			case CATEGORY_DRESS3:
			case CATEGORY_DRESS4:
			case CATEGORY_SET_DRESS://::N00083 Add 20131201
					//201806 add 商品コードでセレスーツ、キッズスーツを除外
					$sql .= " and (product_code not like ?) ";
					$sql .= " and (product_code not like ?) ";
					$arr_val[] = '%'.'CM'.'%';
					$arr_val[] = '%'.PCODE_KIDS.'%';

				$sql_temp1 = "";
            //::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
					for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i]) && !empty($_GET["kind".$i])){
							$sql_temp1 .= " ps.product_type= ? or";
							$arr_val[] = $i;
						}
					}
				//
				if(empty($sql_temp1)){
					return;
				}else{
					$sql_temp1 = trim($sql_temp1, "or");
					$sql .= " and ($sql_temp1) ";
				}

				// ***********keyword condition************
				if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){
					$sql .= " and ((ps.comment3 ilike ?) or (ps.name  ilike ?)) ";
					$arr_val[] = '%'.$_REQUEST['name'].'%';
					$arr_val[] = '%'.$_REQUEST['name'].'%';
				}

				//---detail--背中の調節ひも付き
				if(isset($_REQUEST[OPTION]) && in_array(2, $_REQUEST[OPTION])){
					$sql .= " and ((pc.product_code ilike ?) or (pc.product_code  ilike ?)) ";
					$arr_val[] = '%F%';
					$arr_val[] = '%J%';
				}

				// ******** detail condition's new goods and icon
				//---detail--- 新品同様
				if(isset($_REQUEST[OPTION]) && in_array(3, $_REQUEST[OPTION])){
					$sql .= "and product_flag = ?";
					$arr_val[] = "00001";
				}
				//---detail--- アイコン
				if(isset($_REQUEST[ICON])){
					$ary_temp = array(1=>".",".",".",".",".",".",);
					foreach ($_REQUEST[ICON] as $value) {
						$ary_temp[$value] = "1";
					}
					$str_temp = '^'.implode("",$ary_temp);
					$str_temp = preg_replace("/(1+)\.*$/", "$1", $str_temp);
					$arr_val[] = $str_temp;

					$sql .= " and substring(icon_flag from ?) is not null ";
				}

				$sql .= ") AS A ";

				$bln_inner = false;
				$sql_pre = "Select distinct(B1.product_id) From (" ;
				$sql_after = " ) AS B1";
				// **************Size condition***********
				$sql_temp1 = "";
				if(isset($_REQUEST[SIZE])){
					$sql_temp2 = "";
					// get category_id
					//::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
					for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i])){
							foreach ($_REQUEST[SIZE] as $value) {
								if(!empty($this->arrSize[$value][$i])){
									$sql_temp2 .= $this->arrSize[$value][$i].",";
								}
							}
						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					// make sql
					$sql_temp1 .= <<<EOF
$sql_pre
	Select  distinct(product_id)
	From dtb_product_categories
	Where  category_id in ($sql_temp2)
$sql_after
EOF;
					$bln_inner = true;
				}

				// **************color condition***********
				if(isset($_REQUEST[COLOR])){

					$sql_temp2 = "";
					//::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
					for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i])){
							foreach ($_REQUEST[COLOR] as $value) {
								if(!empty($this->arrColor[$value][$i])){
									$sql_temp2 .= $this->arrColor[$value][$i].",";
								}
							}
						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					if($bln_inner){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
					$sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
					if($bln_inner){
						$sql_temp1 .= " ) AS B2 ON B1.product_id=B2.product_id ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}

				// **************function condition***********
				if(isset($_REQUEST[_FUNCTION])){
					$sql_temp2 = "";
					//::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
					for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i])){
							$sql_temp2 .= " (";
							foreach ($_REQUEST[_FUNCTION] as $value) {
								if(!empty($this->arrFunction[$value][$i])){
									$sql_temp2 .= " category_id = ".$this->arrFunction[$value][$i]." and";
								}else{
									$sql_temp2 .= " category_id = 0 and";
								}
							}

							$sql_temp2 = rtrim($sql_temp2, "and");
							$sql_temp2 .= ") or";
						}
					}
					$sql_temp2 = rtrim($sql_temp2, "or");

					$ary_temp = array(1=>".",".",".",".",".",".",);
					foreach ($_REQUEST[_FUNCTION] as $value) {
						$ary_temp[$value] = "1";
					}
					$arr_val[] = implode("",$ary_temp);

					if($bln_inner){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
					$sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where ($sql_temp2)
union
Select  product_id
From dtb_products
Where substring(funct_flag from ?) is not null
EOF;
					if($bln_inner){
						$sql_temp1 .= " ) AS B3 ON B1.product_id=B3.product_id ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}

				// **********detail search**********
				// ---detail--シルエット
				if(isset($_REQUEST[SILHOUETTE])){
				$sql_temp2 = "";
                //::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
					for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i])){
							foreach ($_REQUEST[SILHOUETTE] as $value) {
								if(!empty($this->arrSilhouette[$value][$i])){
									$sql_temp2 .= $this->arrSilhouette[$value][$i].",";
								}
							}
						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					$ary_temp = array(1=>".",".",".",".",);
					foreach ($_REQUEST[SILHOUETTE] as $value) {
						$ary_temp[$value] = "1";
					}
					$arr_val[] = implode("",$ary_temp);

					if($bln_inner){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
					$sql_temp1 .= <<<EOF
Select  product_id
From dtb_product_categories
Where  category_id in ($sql_temp2)
Union
Select  product_id
From dtb_products
Where substring(silhouette_flag from ?) is not null
EOF;
					if($bln_inner){
						$sql_temp1 .= " ) AS B4 ON B1.product_id=B4.product_id  ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}

				// ---detail--「えりもと」
				if(isset($_REQUEST[COLLAR])){
					$sql_temp2 = "";
					//::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
					for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i])){
							foreach ($_REQUEST[COLLAR] as $value) {
								if(!empty($this->arrCollar[$value][$i])){
									$sql_temp2 .= $this->arrCollar[$value][$i].",";
								}
							}
						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					if($bln_inner){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
					$sql_temp1 .= <<<EOF
Select  product_id
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
					if($bln_inner){
						$sql_temp1 .= " ) AS B5 ON B1.product_id = B5.product_id ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}

				// ---detail--「柄」
				if(isset($_REQUEST[GARA]) && !empty($_REQUEST[GARA])){
					$sql_temp2 = "";
					//::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
					for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i])){
							if(!empty($this->arrGara[1][$i])){
								$sql_temp2 .= $this->arrGara[1][$i].",";
							}
						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					$str_temp = "";
					if($_REQUEST[GARA] == 1){
						$str_temp = <<<EOF
Select distinct(product_id)
from dtb_product_categories
where product_id not in	(
EOF;
					}

					if($bln_inner){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
					$sql_temp1 .= $str_temp;
					$sql_temp1 .= <<<EOF
Select  product_id
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
					if($_REQUEST[GARA] == 1){
						$sql_temp1 .= " ) ";
					}
					if($bln_inner){
						$sql_temp1 .= " ) AS B6 ON B1.product_id = B6.product_id ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}

				//============ 2013.04.05 RCHJ Add Season search- case onepiece ===========
				// ---detail--「シーズン」
				if(isset($_REQUEST[SEASON])){
					$sql_temp2 = "";
					for($i=ONEPIECE_PRODUCT_TYPE; $i <= ONEPIECE_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i])){
							foreach ($_REQUEST[SEASON] as $value) {
								if(!empty($this->arrSeason[$value][$i])){
									$sql_temp2 .= $this->arrSeason[$value][$i].",";
								}
							}
						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					if($bln_inner){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
					$sql_temp1 .= <<<EOF
Select  product_id
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
					if($bln_inner){
						$sql_temp1 .= " ) AS B7 ON B1.product_id = B7.product_id ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}
				//===================== End =========================

				/*//---detail--背中の調節ひも付き			2012.07.10 RCHJ Remark
				if(isset($_REQUEST[OPTION]) && in_array(2, $_REQUEST[OPTION])){
					$sql_temp2 = "";
					for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
						if(isset($_GET["kind".$i])){
							foreach ($this->arrOptionBack as $ary_value) {
								if(!empty($ary_value[$i])){
									$sql_temp2 .= $ary_value[$i].",";
								}
							}
						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					if($bln_inner){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
$sql_temp1 .= <<<EOF
	Select  product_id
	From dtb_product_categories
	Where  category_id in ($sql_temp2)
EOF;
					if($bln_inner){
						$sql_temp1 .= " ) AS B7 ON B1.product_id = B7.product_id ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}*/


				if(!empty($sql_temp1)){
					$sql .= " INNER JOIN( $sql_temp1 ) AS D ON A.product_id=D.product_id ";
				}

				// ***********len condition(product_ext)**********
				$sql_temp1 = "";
				if(isset($_REQUEST[LEN])){
					$sql_temp1 = "(";
					// get category_id
					foreach ($_REQUEST[LEN] as $value) {
						$sql_temp2 = $this->arrLen[$value]["from"]." and ".$this->arrLen[$value]["to"];

						if(!empty($sql_temp2)){
							$sql_temp1 .= " (CAST(substring(garment_length from '(.|..|...)($|～)') as float) between ".$sql_temp2;
				 			$sql_temp1 .= " OR CAST(substring(garment_length from '～(.|..|...)$') as float) between ".$sql_temp2.") or";
						}
					}
					$sql_temp1 = rtrim($sql_temp1, "or");

					$sql_temp1 .= ")";
				}

				// detail search
				// ---detail---バスト
				$bust_value1 = "0"; $under_value1 = "0";
				$bust_value2 = "0"; $under_value2 = "0";
				if(isset($_REQUEST[A_CUP]) && isset($_REQUEST[A_UNDER])){
					$under_value1 = $_REQUEST[A_UNDER];
					$bust_value1 = $under_value1 + $this->arrA_Bust[$_REQUEST[A_CUP]];

					$under_value2 = $under_value1;
					$bust_value2 = $bust_value1;
				}
				if(empty($bust_value1) && empty($under_value1)
						&& isset($_REQUEST[B_TOP]) && isset($_REQUEST[B_UNDER])
						&& !empty($_REQUEST[B_TOP]) && !empty($_REQUEST[B_UNDER])){
					$under_value1 = is_numeric($_REQUEST[B_TOP])?$_REQUEST[B_TOP]:0;
					$bust_value1 =  is_numeric($_REQUEST[B_UNDER])?$_REQUEST[B_UNDER]:0;

					$under_value2 = $under_value1;
					$bust_value2 = $bust_value1;
				}

				if(isset($_REQUEST[WEARING])){
					if(!empty($under_value1))$under_value1 += $this->arrWearing[$_REQUEST[WEARING]]["from"];
					if(!empty($under_value2))$under_value2 += $this->arrWearing[$_REQUEST[WEARING]]["to"];

					if(!empty($bust_value1))$bust_value1 += $this->arrWearing[$_REQUEST[WEARING]]["from"];
					if(!empty($bust_value2))$bust_value2 += $this->arrWearing[$_REQUEST[WEARING]]["to"];
				}

				if(!empty($under_value1) && !empty($under_value2)
						&& !empty($bust_value1) && !empty($bust_value2)){
					if(!empty($sql_temp1)){
						$sql_temp1 .= " and ";
					}

					$str_bust_sql = $bust_value1." and ".$bust_value2;
					$str_under_sql = $under_value1." and ".$under_value2;

					$sql_temp1 .= " ((CAST(substring(bust from '(.|..|...)($|～|（)') as float) between ".$str_bust_sql.") ";
					$sql_temp1 .= " or (CAST(substring(bust from '～(.|..|...)($|（)') as float) between ".$str_bust_sql.")) ";
					$sql_temp1 .= " and ((CAST(substring(under_text from '(.|..|...)($|～|（)') as float) between ".$str_under_sql.") ";
					$sql_temp1 .= " or (CAST(substring(under_text from '～(.|..|...)($|（)') as float) between ".$str_under_sql.")) ";
					if($_REQUEST[WEARING] == 1){
						$sql_temp1 .= " or (bust is null or under_text is null) ";
					}
				}

				// ---detail---袖の長さ
				if(isset($_REQUEST[SODE])){
					if(!empty($sql_temp1)){
						$sql_temp1 .= " and ";
					}else{
						$sql_temp1 .= " ( ";
					}
					// get category_id
					foreach ($_REQUEST[SODE] as $value) {
						if($value == 1){
							$sql_temp1 .= " (sleeve_length is NULL or  CAST(substring(sleeve_length from '(.|..|...)($|～|（)') as float) <= 4) or";
						}else{
							$sql_temp2 = $this->arrSode[$value]["from"]." and ".$this->arrSode[$value]["to"];

							$sql_temp1 .= " ((CAST(substring(sleeve_length from '(.|..|...)($|～|（)') as float) between ".$sql_temp2.") or";
							$sql_temp1 .= " (CAST(substring(sleeve_length from '～(.|..|...)($|（)') as float) between ".$sql_temp2.")) or";
						}
					}
					$sql_temp1 = rtrim($sql_temp1, "or");
					$sql_temp1 .= ")";
				}

				// ---detail---こだわり条件 : アシンメトリー丈
				if(isset($_REQUEST[OPTION]) && in_array(1, $_REQUEST[OPTION])){
					if(!empty($sql_temp1)){
						$sql_temp1 .= " and ";
					}
					$sql_temp1 .= " substring(garment_length from '(.|..|...)(～)')  is not null ";
				}

				// make sql
				if(!empty($sql_temp1)){
				$sql .= <<<EOF
INNER JOIN
(
	Select  distinct(product_id)
	From dtb_products_ext
	Where $sql_temp1

) AS E ON A.product_id=E.product_id
EOF;
				}

				break;
			case CATEGORY_ALL:
				// ***********keyword condition************
				if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){
					$sql .= " and ((ps.comment3 ilike ?) or (ps.name  ilike ?)) ";
					$arr_val[] = '%'.$_REQUEST['name'].'%';
					$arr_val[] = '%'.$_REQUEST['name'].'%';
				}

				// 商品コードで21-XXCMがつくものは除外
				$sql .= " and (product_code not like ?) ";
				$arr_val[] = '21-%CM';
				$sql .= " and (product_code not like ?) ";
				$arr_val[] = '21-0285';
				$sql .= ") AS A ";

				break;
			default:
// ========== dtb_products table ===========
				$product_type = "";
        //::N00083 Change 20131201
				if($category_id == CATEGORY_NECKLACE){
					$product_type = NECKLACE_PRODUCT_TYPE;
				}else if($category_id == CATEGORY_STOLE){
					$product_type = STOLE_PRODUCT_TYPE;
				}else if($category_id == CATEGORY_OTHERS){
					$product_type = OTHERS_PRODUCT_TYPE;
				}
                //::N00083 end 20131201

				// スマホでその他小物を指定した場合
/*				
				if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE
				&& $category_id == CATEGORY_OTHERS)
				{
						// ﾈｯｸﾚｽも追加
						$arrCategory_id[1] = 63;
				}
*/

				if(count($arrCategory_id) == 1 && !empty($product_type)){
					$sql .= " and (ps.product_type=?) ";
					$arr_val[] = $product_type;
				}else{
					// スマホでその他小物を指定した場合
					if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE
					&& $category_id == CATEGORY_OTHERS)
					{
						// ﾈｯｸﾚｽも追加
						$sql .= " and (ps.product_type=? or ps.product_type=?) ";
						$arr_val[] = NECKLACE_PRODUCT_TYPE;
						$arr_val[] = OTHERS_PRODUCT_TYPE;
					}
				}

				// ***********keyword condition************
				if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){
					$sql .= " and ((ps.comment3 ilike ?) or (ps.name  ilike ?)) ";
					$arr_val[] = '%'.$_REQUEST['name'].'%';
					$arr_val[] = '%'.$_REQUEST['name'].'%';
				}

				// キッズドレスが選択された場合
				if($category_id == CATEGORY_KIDS_DRESS){
					// 商品コードでkids dressを指定
					$sql .= " and (product_code like ?) ";
					$arr_val[] = '%'.PCODE_KIDS_DRESS.'%';
				}

				// バッグが選択された場合
				if($category_id == CATEGORY_BAG){
					// 商品コードでバッグを指定
					$sql .= " and (product_code like ?) ";
					$arr_val[] = '%'.PCODE_BAG.'%';
				}
				// イヤリングが選択された場合
				if($category_id == CATEGORY_EARRINGS){
					// 商品コードでバッグを指定
					$sql .= " and (product_code like ?) ";
					$arr_val[] = '%'.PCODE_EARRINGS.'%';
				}

				// その他小物が指定された場合
				if($category_id == CATEGORY_OTHERS){
					// 商品コードでバッグを除外
					$sql .= " and (product_code not like ?) ";
					$arr_val[] = '%'.PCODE_BAG.'%';
				}

				// 羽織が指定された場合 201806 add
				if($category_id == CATEGORY_STOLE){
					// 商品コードでコート・ガウンを除外
					$sql .= " and (product_code not like ?) ";
					$sql .= " and (product_code not like ?) ";
					$sql .= " and (product_code not like ?) ";
					$sql .= " and (product_code not like ?) ";
					$arr_val[] = '%CM';
					$arr_val[] = '21-0285';
					$arr_val[] = '15-%';
					$arr_val[] = '%k';
				}

				// コート
				if($category_id == CATEGORY_COAT){
					$sql .= " and (product_code like ?) ";
					$arr_val[] = '15-%';
				}

// セレスーツが選択された場合 cate_id->367
				if($category_id == CATEGORY_CEREMONYSUIT){
					// 商品コードでセレスーツを指定
					$sql .= " and (product_code like ?) ";
					$arr_val[] = '%'.PCODE_SET_DRESS.'%CM';
				}

// キッズが選択された場合 cate_id->371
				if($category_id == CATEGORY_KIDS){
					// 商品コードでキッズを指定
					$sql .= " and (product_code like ?) ";
					$arr_val[] = '%'.PCODE_KIDS.'%';
				}

				$sql .= ") AS A ";

// ============= dtb_products_ext table ========
				//if($category_id == CATEGORY_STOLE){
				if(in_array(CATEGORY_STOLE, $arrCategory_id)){
					$sql .= <<<EOF
INNER JOIN
(
Select product_id, shoulders, shoulders_length,
	CAST(substring(shoulders from '([0-9]{1,3})($|～)') as float) as shoulders_from,
	CAST(substring(shoulders from '～([0-9]{1,3})') as float) as shoulders_to,
	CAST(substring(shoulders_length from '([0-9]{1,3})($|～)') as float) as slength_from,
	CAST(substring(shoulders_length from '～([0-9]{1,3})') as float) as slength_to
from dtb_products_ext
) AS E ON A.product_id=E.product_id
EOF;
				}


// ======= dtb_categories table ============
				$bln_inner = false;
				$sql_pre = "Select distinct(B1.product_id) From(" ;
				$sql_after = " ) AS B1";
				// **************Size condition***********
				$sql_temp1 = "";

				if(isset($_REQUEST[SIZE])){

					$sql_temp2 = "";
					// get category_id
					if($category_id == CATEGORY_CEREMONYSUIT){
						foreach ($_REQUEST[SIZE] as $value) {
							if(!empty($this->arrSize[$value][CEREMONYSUIT_PRODUCT_TYPE])){
								$sql_temp2 .= $this->arrSize[$value][CEREMONYSUIT_PRODUCT_TYPE].",";
							}
						}
					}else{
						foreach ($_REQUEST[SIZE] as $value) {
							$arrIndex = explode("_", $value);
							$_GET[SIZE][abs($arrIndex[0])] = $arrIndex[1];
							$sql_temp2 .= $arrIndex[1].",";

						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					// make sql
					$sql_temp1 .= <<<EOF
$sql_pre
	Select  distinct(product_id)
	From dtb_product_categories
	Where  category_id in ($sql_temp2)
$sql_after
EOF;
					$bln_inner = true;
				}


				// **************color condition test***********
				if(isset($_REQUEST[COLOR])){

					$sql_temp2 = "";
					if($category_id == CATEGORY_CEREMONYSUIT){
								foreach ($_REQUEST[COLOR] as $value) {
									//if(!empty($this->arrColor[$value][8])){
									//	$sql_temp2 .= $this->arrColor[$value][8].",";
									//}
                                    if(!empty($this->arrColor[$value]['CEREMONYSUIT_PRODUCT_TYPE'])){
										$sql_temp2 .= $this->arrColor[$value]['CEREMONYSUIT_PRODUCT_TYPE'].",";
									}
							}
					}else{
						foreach ($_REQUEST[COLOR] as $value) {
							$arrIndex = explode("_", $value);
							$_GET[COLOR][abs($arrIndex[0])] = $arrIndex[1];
							$sql_temp2 .= $arrIndex[1].",";
							//$sql_temp2 .= "?,";
							//$arr_val[] = $arrIndex[1];
						}
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					if($bln_inner){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
					$sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
					if($bln_inner){
						$sql_temp1 .= " ) AS B2 ON B1.product_id=B2.product_id ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}


				// **************type condition***********
				if(isset($_REQUEST[TYPE])){
					$sql_temp2 = "";
					foreach ($_REQUEST[TYPE] as $value) {
						$arrIndex = explode("_", $value);
						$_GET[TYPE][abs($arrIndex[0])] = $arrIndex[1];

						if($arrIndex[1] > 0){
							$sql_temp2 .= $arrIndex[1].",";
						}

					}
                    //::N00190 Add 20140616
                    //コサージュとブローチを一緒に検索する
                    if (substr($_REQUEST[TYPE][0],4,3) == CORSAGE_TYPE) {
                        $sql_temp2 .= "188,";
                    }
                    //::N00190 end 20140616

					$sql_temp2 = rtrim($sql_temp2, ",");

					if(!empty($sql_temp2)){
						if(!empty($sql_temp1)){
							$sql_temp1 .= " INNER JOIN ( ";
						}else{
							$sql_temp1 .= $sql_pre;
						}
						$sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
						if($bln_inner){
							$sql_temp1 .= " ) AS B3 ON B1.product_id=B3.product_id ";
						}else{
							$sql_temp1 .= $sql_after;
							$bln_inner = true;
						}
					}
				}
                //::N00190 Add 20140616
                if($this->device_type_id == DEVICE_TYPE_PC){
                    if ($_REQUEST['category_id'] == CATEGORY_STOLE) {
                        $_REQUEST[TYPE][0] = '000_78';
                    }
                    if ($_REQUEST['category_id'] == CATEGORY_NECKLACE) {
                        $_REQUEST[TYPE][0] = '002_63';
                    }
                }

// **************necklace age condition***********
    if(isset($_REQUEST['n_age'])){
        $sql_temp2 = "";
        foreach ($_REQUEST['n_age'] as $value) {
            $arrIndex = explode("_", $value);
            $_GET['n_age'][abs($arrIndex[0])] = $arrIndex[1];

            if($arrIndex[1] > 0){
                $sql_temp2 .= $arrIndex[1].",";
            }

        }
        $sql_temp2 = rtrim($sql_temp2, ",");

        if(!empty($sql_temp2)){
            if(!empty($sql_temp1)){
                $sql_temp1 .= " INNER JOIN ( ";
            }else{
                $sql_temp1 .= $sql_pre;
            }
            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
            if($bln_inner){
                $sql_temp1 .= " ) AS B9 ON B1.product_id=B9.product_id ";
            }else{
                $sql_temp1 .= $sql_after;
                $bln_inner = true;
            }
        }
    }
// **************necklace len condition***********
	  if(isset($_REQUEST[LEN])){
	      $sql_temp2 = "";
	      foreach ($_REQUEST[LEN] as $value) {
	          $arrIndex = explode("_", $value);
	          $_GET[LEN][abs($arrIndex[0])] = $arrIndex[1];

	          if($arrIndex[1] > 0){
	              $sql_temp2 .= $arrIndex[1].",";
	          }

	      }
	      $sql_temp2 = rtrim($sql_temp2, ",");

	      if(!empty($sql_temp2)){
	          if(!empty($sql_temp1)){
	              $sql_temp1 .= " INNER JOIN ( ";
	          }else{
	              $sql_temp1 .= $sql_pre;
	          }
	          $sql_temp1 .= <<<EOF
	Select  distinct(product_id)
	From dtb_product_categories
	Where  category_id in ($sql_temp2)
	EOF;
	          if($bln_inner){
	              $sql_temp1 .= " ) AS B7 ON B1.product_id=B7.product_id ";
	          }else{
	              $sql_temp1 .= $sql_after;
	              $bln_inner = true;
	          }
	      }
	  }

/* 以下switch、$_REQUEST[TYPE][0]NULLで、機能していないぽい */
                switch (substr($_REQUEST[TYPE][0],4,3)) {
                case STOLE_TYPE:
                case BOLERO_TYPE:
                    // **************sleeve_length condition***********
                    if(isset($_REQUEST['sleeve_length'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['sleeve_length'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['sleeve_length'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B4 ON B1.product_id=B4.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }
                    // **************thickness condition***********
                    if(isset($_REQUEST['thickness'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['thickness'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['thickness'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B5 ON B1.product_id=B5.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }
                    // **************fits_color condition***********
                    if(isset($_REQUEST['fits_color'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['fits_color'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['fits_color'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B6 ON B1.product_id=B6.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    break;
                case NECKLACE_TYPE:

                    // **************necklace scene condition***********
                    if(isset($_REQUEST['n_scene'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['n_scene'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['n_scene'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B8 ON B1.product_id=B8.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    break;
                case BELT_TYPE:
                    // **************belt_color condition***********
                    if(isset($_REQUEST['belt_color'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['belt_color'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['belt_color'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B10 ON B1.product_id=B10.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    // **************belt_type condition***********
                    if(isset($_REQUEST['belt_type'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['belt_type'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['belt_type'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B11 ON B1.product_id=B11.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    // **************belt_size condition***********
                    if(isset($_REQUEST['belt_size'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['belt_size'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['belt_size'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B12 ON B1.product_id=B12.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    break;
                case BRACELET_TYPE:
                    // **************bracelet_color condition***********
                    if(isset($_REQUEST['bracelet_color'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['bracelet_color'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['bracelet_color'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B13 ON B1.product_id=B13.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }


                    // **************fits_bracelet_color condition***********
                    if(isset($_REQUEST['fits_bracelet_color'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['fits_bracelet_color'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['fits_bracelet_color'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B14 ON B1.product_id=B14.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }



                    // **************bracelet_fits_neck condition***********
                    if(isset($_REQUEST['bracelet_fits_neck'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['bracelet_fits_neck'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['bracelet_fits_neck'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B15 ON B1.product_id=B15.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    break;
                case CORSAGE_TYPE:
                case BROOCH_TYPE:
                    // **************corsage_color condition***********
                    if(isset($_REQUEST['corsage_color'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['corsage_color'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['corsage_color'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B16 ON B1.product_id=B16.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    // **************fits_corsage_color condition***********
                    if(isset($_REQUEST['fits_corsage_color'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['fits_corsage_color'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['fits_corsage_color'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B16 ON B1.product_id=B16.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    break;
                case PANNER_TYPE:
                    // **************pannier_color condition***********
                    if(isset($_REQUEST['pannier_color'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['pannier_color'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['pannier_color'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B17 ON B1.product_id=B17.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }
                    break;

                case HAIRAC_TYPE:
                    // **************hair fastener condition*********** 201709 add
                    if(isset($_REQUEST['hairac_fastener'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['hairac_fastener'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['hairac_fastener'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B18 ON B1.product_id=B18.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }

                    // **************hairac_color condition***********
                    if(isset($_REQUEST['hairac_color'])){
                        $sql_temp2 = "";
                        foreach ($_REQUEST['hairac_color'] as $value) {
                            $arrIndex = explode("_", $value);
                            $_GET['hairac_color'][abs($arrIndex[0])] = $arrIndex[1];

                            if($arrIndex[1] > 0){
                                $sql_temp2 .= $arrIndex[1].",";
                            }

                        }
                        $sql_temp2 = rtrim($sql_temp2, ",");

                        if(!empty($sql_temp2)){
                            if(!empty($sql_temp1)){
                                $sql_temp1 .= " INNER JOIN ( ";
                            }else{
                                $sql_temp1 .= $sql_pre;
                            }
                            $sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
                            if($bln_inner){
                                $sql_temp1 .= " ) AS B19 ON B1.product_id=B19.product_id ";
                            }else{
                                $sql_temp1 .= $sql_after;
                                $bln_inner = true;
                            }
                        }
                    }
                	break;

                default:
                    break;
                }
//::N00190 end 20140616




				// **************len condition***********
				if($category_id != CATEGORY_NECKLACE && isset($_REQUEST[LEN])){
					$sql_temp2 = "";
					foreach ($_REQUEST[LEN] as $value) {
						$arrIndex = explode("_", $value);
						$_GET[LEN][abs($arrIndex[0])] = $arrIndex[1];
						$sql_temp2 .= $arrIndex[1].",";
					}
					$sql_temp2 = rtrim($sql_temp2, ",");

					if(!empty($sql_temp1)){
						$sql_temp1 .= " INNER JOIN ( ";
					}else{
						$sql_temp1 .= $sql_pre;
					}
					$sql_temp1 .= <<<EOF
Select  distinct(product_id)
From dtb_product_categories
Where  category_id in ($sql_temp2)
EOF;
					if($bln_inner){
						$sql_temp1 .= " ) AS B4 ON B1.product_id=B4.product_id ";
					}else{
						$sql_temp1 .= $sql_after;
						$bln_inner = true;
					}
				}

				if($bln_inner){
					$sql .= " INNER JOIN( $sql_temp1 ) AS D ON A.product_id=D.product_id ";
				}
		}


		// make enabled table condition
		$sql_temp1 = "";
		if(!empty($date1)){
			$send_day_time = strtotime($date1);

			/*$sql_temp1 = " ((reserved_from >= ? and reserved_to <= ?) or sending_date = ?)";
			$arr_val[] = date("Y-m-d",strtotime("-5 days", $send_day_time));
			$arr_val[] = date("Y-m-d",strtotime("+5 days", $send_day_time));
			$arr_val[] = $date1;*/
			$sql_temp1 .= " ((reserved_from <= ? and reserved_to >= ?) or sending_date = ?)";
			$arr_val[] = $date1;
			$arr_val[] = $date1;
			$arr_val[] = $date1;
		}
		if(!empty($date2)){
			$send_day_time = strtotime($date2);

			if(!empty($sql_temp1))$sql_temp1 .= " or ";
			/*$sql_temp1 .= " ((reserved_from >= ? and reserved_to <= ?) or sending_date = ?) ";
			$arr_val[] = date("Y-m-d",strtotime("-5 days", $send_day_time));
			$arr_val[] = date("Y-m-d",strtotime("+5 days", $send_day_time));
			$arr_val[] = $date2;*/
			$sql_temp1 .= " ((reserved_from <= ? and reserved_to >= ?) or sending_date = ?) ";
			$arr_val[] = $date2;
			$arr_val[] = $date2;
			$arr_val[] = $date2;
		}

		if(!empty($sql_temp1) && $bln_ok){
			$sql_temp1 .= " or (reserved_type=0 AND sending_date = date(?) - 6) ";
			if(empty($date2)){
				$arr_val[] = $date1;
			}else{
				$arr_val[] = $date2;
			}
		}

		$sql .= " ";
		if(!empty($sql_temp1)){
			$sql .= <<<EOF
left join
(
	Select distinct(product_id)
	From dtb_products_reserved
	Where $sql_temp1
) AS G on A.product_id=G.product_id
Where G.product_id is null
EOF;
		}

		// ***********Order by**********

		$order = "";
		switch ($orderby) {
			//価格順
			case 'price':
				$order = "";

				break;

			//名前順
			case 'name':
				$order = "A.name";

				break;
			//レビュー順で並び替える
			case "mens_review":
				$order = "A.mens_review_avg Desc, A.mens_review_count Desc";

				break;
			case "womens_review":
				$order = "A.womens_review_avg Desc , A.womens_review_count Desc";

				break;
			// ↓ s2 201303対応 20130311
			// レビュー数順
			case "womens_review_cnt":
				$order = "A.womens_review_count Desc, A.womens_review_avg Desc";

				break;
			// レビュー平均順
			case "womens_review_avg":
				$order = "A.womens_review_avg Desc , A.womens_review_count Desc";

				break;
			// ↑ s2 201303対応 20130311
			// 2015.09.22 丈の長さ順追加
			case 'garment_length':
				$order = "A.garment1 Desc";

				break;
			// 2016.11.23 バストサイズ順追加
			case 'bust_size':
				$order = "bust Desc NULLS LAST ";
				break;

			//新着順
			case 'date':
			default:
				//::$order = "A.product_id Desc";
				$order = "create_date Desc";//::N00115 Change 20140217

				break;
		}
		$sql .= " ORDER BY ".$order;
/*
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);
var_dump($sql);
var_dump($arr_val);
die();
*/
		// 20141103 商品データを2度取得している問題対応 start
		// 20141103 検索実行(行数取得時に検索データを捨てているのでいったん保存する)
		$arrAllProducts = $objQuery->getall($sql, $arr_val);
		$linemax = count($arrAllProducts);

		// 20141103 以下の行数の取得処理は無駄なのでコメントアウト
		// 行数の取得
		// $linemax = count($objQuery->getall($sql, $arr_val));
		$this->tpl_linemax = $linemax;   // 何件が該当しました。表示用
		// 20141103  商品データを2度取得している問題対応 end

		// ページ送りの取得
		$this->objNavi = new SC_PageNavi_Border($this->tpl_pageno, $linemax, $disp_num, "fnNaviPage", NAVI_PMAX);
		$strnavi = $this->objNavi->strnavi;
		$strnavi = str_replace('onclick="fnNaviPage', 'onclick="form1.mode.value=\''.'\'; fnNaviPage', $strnavi);
		// 表示文字列
		$this->tpl_strnavi = empty($strnavi) ? "&nbsp;" : $strnavi;
		$startno = $this->objNavi->start_row;                 // 開始行

		// 20141103 商品データを2度取得している問題対応 start
		// 20141103 商品データの取得を一度のみに修正
		// 取得範囲の指定(開始行番号、行数のセット)
		// $objQuery->setlimitoffset($disp_num, $startno);
		// $sql .= " LIMIT ". $disp_num. " OFFSET ". $startno;

		// 検索結果の取得
		// $this->arrProducts = $objQuery->getall($sql, $arr_val);
		// 20141103 検索結果を表示に必要な分だけ切り出す
		$this->arrProducts = array_slice($arrAllProducts, $startno, $disp_num);
		// 20141103 商品データを2度取得している問題対応 end

		$this->tpl_pagePrevno = $this->objNavi->arrPagenavi['before'];
		$this->tpl_pageNextno = $this->objNavi->arrPagenavi['next'];
		$this->tpl_maxPage = $this->objNavi->max_page;

		// 規格名一覧
		$arrClassName = $objDb->sfGetIDValueList("dtb_class", "class_id", "name");
		// 規格分類名一覧
		$arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");
		// 規格セレクトボックス設定
		if($disp_num == 15) {
			for($i = 0; $i < count($this->arrProducts); $i++) {
				$this->lfMakeSelect($this->arrProducts[$i]['product_id'], $arrClassName, $arrClassCatName);
				// 購入制限数を取得
				$this->lfGetSaleLimit($this->arrProducts[$i]);
			}
		}
	}

	/* 商品一覧の表示 */
    function lfDispProductsList1($category_id, $name, $disp_num, $orderby) {
       $objQuery = new SC_Query();
        $objDb = new SC_Helper_DB_Ex();
        $this->tpl_pageno = defined("MOBILE_SITE") ? @$_GET['pageno'] : @$_REQUEST['pageno'];

		if(!isset($this->tpl_pageno)) $this->tpl_pageno = 1;

        //表示順序
        switch($orderby) {

        //価格順
        case 'price':
            $col = "DISTINCT price02_min, product_id, product_code_min, product_code_max,"
                . " name, comment1, comment2, comment3,"
                . " main_list_comment, main_image, main_list_image,"
                . " price01_min, price01_max, price02_max,"
                . " stock_min, stock_max, stock_unlimited_min, stock_unlimited_max,"
                . " point_rate, sale_limit, sale_unlimited, deliv_date_id, deliv_fee,"
                . " status, product_flag, create_date, del_flg";
            $from = "vw_products_allclass AS T1";
            $order = "price02_min, product_id";
            break;

        //新着順
        case 'date':
            $col = "DISTINCT create_date, product_id, product_code_min, product_code_max,"
                . " name, comment1, comment2, comment3,"
                . " main_list_comment, main_image, main_list_image,"
                . " price01_min, price01_max, price02_min, price02_max,"
                . " stock_min, stock_max, stock_unlimited_min, stock_unlimited_max,"
                . " point_rate, sale_limit, sale_unlimited, deliv_date_id, deliv_fee,"
                . " status, product_flag, del_flg";
            $from = "vw_products_allclass AS T1";
            $order = "create_date DESC, product_id";
            break;

        //名前順
        case 'name':
            $col = "DISTINCT name, product_id, product_code_min, product_code_max,"
                . " comment1, comment2, comment3,"
                . " main_list_comment, main_image, main_list_image,"
                . " price01_min, price01_max, price02_min, price02_max,"
                . " stock_min, stock_max, stock_unlimited_min, stock_unlimited_max,"
                . " point_rate, sale_limit, sale_unlimited, deliv_date_id, deliv_fee,"
                . " status, product_flag, create_date, del_flg";
            $from = "vw_products_allclass AS T1";
            $order = "name ASC, product_id";
            break;
        //レビュー順で並び替える
        case "mens_review":
            $col = "DISTINCT name, product_id, product_code_min, product_code_max,"
                . " comment1, comment2, comment3,"
                . " main_list_comment, main_image, main_list_image,"
                . " price01_min, price01_max, price02_min, price02_max,"
                . " stock_min, stock_max, stock_unlimited_min, stock_unlimited_max,"
                . " point_rate, sale_limit, sale_unlimited, deliv_date_id, deliv_fee,"
                . " status, product_flag, create_date, del_flg, mens_review_avg, mens_review_count";
            $from = "vw_products_allclass AS T1";
            $order = "mens_review_avg DESC, mens_review_count DESC, product_id";
            break;
        case "womens_review":
            $col = "DISTINCT name, product_id, product_code_min, product_code_max,"
                . " comment1, comment2, comment3,"
                . " main_list_comment, main_image, main_list_image,"
                . " price01_min, price01_max, price02_min, price02_max,"
                . " stock_min, stock_max, stock_unlimited_min, stock_unlimited_max,"
                . " point_rate, sale_limit, sale_unlimited, deliv_date_id, deliv_fee,"
                . " status, product_flag, create_date, del_flg, womens_review_avg, womens_review_count";
            $from = "vw_products_allclass AS T1";
            $order = "womens_review_avg DESC, womens_review_count DESC, product_id";
            break;
        default:
            $col = "DISTINCT T1.product_id, product_code_min, product_code_max AS product_code,"
                . " price01_min, price01_max, price02_min, price02_max,"
                . " stock_min, stock_max, stock_unlimited_min,"
                . " stock_unlimited_max, del_flg, status, name, comment1,"
                . " comment2, comment3, main_list_comment, main_image,"
                . " main_list_image, product_flag, deliv_date_id, sale_limit,"
                . " point_rate, sale_unlimited, create_date, deliv_fee, "
                . " T4.product_rank, T4.category_rank,"
                . " round(womens_review_avg,1) AS womens_review_avg, womens_review_count,wear_comment_model1,wear_comment_model2";//::N00199 Add 20140717
            $from = "vw_products_allclass AS T1"
                . " JOIN ("
                . " SELECT max(T3.rank) AS category_rank,"
                . "        max(T2.rank) AS product_rank,"
                . "        T2.product_id"
                . "   FROM dtb_product_categories T2"
                . "   JOIN dtb_category T3 USING (category_id)"
                . " GROUP BY product_id) AS T4 USING (product_id)";
            $order = "T4.category_rank DESC, T4.product_rank DESC";
            break;
        }

        // 木曜お届けOK判定
        $objDate = new SC_Helper_Date_Ex();
        $orderDate = $objDate->getDelivDay();
		$col .= ", T1.wed_flag, date('".$orderDate."') - date(T1.shipping_date) as wed_diff_days ";

        // 商品検索条件の作成（未削除、表示）
        $where = "del_flg = 0 AND status = 1 ";


        //::N00199 Add 20140717
        $this->tpl_staff1_id = "";
        $this->tpl_staff2_id = "";
        if ((!empty($_REQUEST['staff1_id'])) || (!empty($_REQUEST['staff2_id']))) {
            if (!empty($_REQUEST['staff1_id'])) {
                $req_staff_id = $_REQUEST['staff1_id'];
                $this->tpl_staff1_id = $req_staff_id;
            }
            if (!empty($_REQUEST['staff2_id'])) {
                $req_staff_id = $_REQUEST['staff2_id'];
                $this->tpl_staff2_id = $_REQUEST['staff2_id'];
            }
            $where .= "AND (wear_comment_model1 =".$req_staff_id." OR wear_comment_model2 =".$req_staff_id.") ";
            $arrRetModel = $objQuery->getAll("SELECT name FROM dtb_model WHERE model_id =".$req_staff_id." ");
            $this->tpl_subtitle = "スタッフの「".$arrRetModel[0]['name']."」が着用した商品一覧";
        }
        //::N00199 end 20140717




        // 新機能 カテゴリ複合検索
        if ($_GET['mode'] == 'category_search') {
            $arrSearchValues = $this->lfGetSearchCategory($category_id);
            // 検索項目数
            $searchCount = count($arrSearchValues);
            if($searchCount != 0){
                $seachCategoryId = array();
                $tmpRet = array();
                foreach ($arrSearchValues as $value) {
                    foreach ($value as $_value) {
                        $tmp = explode("_", $_value);
                        // 数値以外認めない
                        if(!is_numeric($tmp[1])){
                           return;
                        }
                        $seachCategoryId[] = $tmp[1];
                    }
                    $tmpRet[] = $objQuery->getAll("SELECT DISTINCT product_id FROM dtb_product_categories WHERE  category_id IN ( " . implode(',' , $seachCategoryId).") ");
                    // この時点で0になった場合は検索結果は0件確定
                    if (count($tmpRet) == 0){
                        return;
                    }
                    unset($seachCategoryId);
                    $seachCategoryId = array();
                }

                $arrTmpRet = array();
                // 検索結果をまとめる
                foreach ($tmpRet as $value) {
                  $arrTmpRet = array_merge($value,$arrTmpRet);
                }
                $arrProductId = array();
                // 検索結果を取り出す
                foreach ($arrTmpRet as $value) {
                    foreach ($value as $_value) {
                        $arrProductId[] = $_value;
                    }
                }
                // 重複チェック
                $checkCount = array_count_values ($arrProductId);
                $arrProductId = array();
                foreach ($checkCount as $key => $value) {
                    // 検索項目数と等しいもののみ抽出
                    if ($value == $searchCount) {
                       $arrProductId[] = $key;
                    }
                }
                if (count($arrProductId) != 0) {
                    $where .= " AND product_id IN (".implode("," , $arrProductId).") ";
                } else {
                    return;
                }
            }else{ // チェックボックスが未チェックの場合は全件表示
                // カテゴリからのWHERE文字列取得
                if ( $category_id ) {
                    list($tmp_where, $arrval) = $objDb->sfGetCatWhere($category_id);
                    if($tmp_where != "") {
                        $where.= " AND $tmp_where";
                    }
                }
            }
        }else{
            // カテゴリからのWHERE文字列取得
            if ( $category_id ) {
                list($tmp_where, $arrval) = $objDb->sfGetCatWhere($category_id);
                if($tmp_where != "") {
                    $where.= " AND $tmp_where";
                }
            }
         }
        // 商品名をwhere文に
        $name = preg_replace("/,/", "", $name);// XXX
        // 全角スペースを半角スペースに変換
        $name = str_replace('　', ' ', $name);
        // スペースでキーワードを分割
        $names = preg_split("/ +/", $name);
        // 分割したキーワードを一つずつwhere文に追加
        foreach ($names as $val) {
            if ( strlen($val) > 0 ){
                $where .= " AND ( name ILIKE ? OR comment3 ILIKE ?) ";
                $ret = SC_Utils_Ex::sfManualEscape($val);
                $arrval[] = "%$ret%";
                $arrval[] = "%$ret%";
            }
        }

        if (empty($arrval)) {
            $arrval = array();
        }

        // 行数の取得
        $linemax = count($objQuery->getAll("SELECT DISTINCT product_id "
                                         . "FROM vw_products_allclass AS allcls "
                                         . (!empty($where) ? " WHERE " . $where
                                                           : ""), $arrval));

        $this->tpl_linemax = $linemax;   // 何件が該当しました。表示用

        // ページ送りの取得
        $this->objNavi = new SC_PageNavi($this->tpl_pageno, $linemax, $disp_num, "fnNaviPage", NAVI_PMAX);

        $strnavi = $this->objNavi->strnavi;
        $strnavi = str_replace('onclick="fnNaviPage', 'onclick="form1.mode.value=\''.'\'; fnNaviPage', $strnavi);
        // 表示文字列
        $this->tpl_strnavi = empty($strnavi) ? "&nbsp;" : $strnavi;
        $startno = $this->objNavi->start_row;                 // 開始行

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setlimitoffset($disp_num, $startno);
        // 表示順序
        $objQuery->setorder($order);

        // 検索結果の取得
        $this->arrProducts = $objQuery->select($col, $from, $where, $arrval);
		$this->tpl_pagePrevno = $this->objNavi->arrPagenavi['before'];
		$this->tpl_pageNextno = $this->objNavi->arrPagenavi['next'];
		$this->tpl_maxPage = $this->objNavi->max_page;

        // 規格名一覧
        $arrClassName = $objDb->sfGetIDValueList("dtb_class", "class_id", "name");
        // 規格分類名一覧
        $arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");
        // 規格セレクトボックス設定
        if($disp_num == 15) {
            for($i = 0; $i < count($this->arrProducts); $i++) {
                $this->lfMakeSelect($this->arrProducts[$i]['product_id'], $arrClassName, $arrClassCatName);
                // 購入制限数を取得
                $this->lfGetSaleLimit($this->arrProducts[$i]);
            }
        }


    }



	/* 規格セレクトボックスの作成 */
	function lfMakeSelect($product_id, $arrClassName, $arrClassCatName) {

		$classcat_find1 = false;
		$classcat_find2 = false;
		// 在庫ありの商品の有無
		$stock_find = false;

		// 商品規格情報の取得
		$arrProductsClass = $this->lfGetProductsClass($product_id);

		// 規格1クラス名の取得
		$this->tpl_class_name1[$product_id] =
		isset($arrClassName[$arrProductsClass[0]['class_id1']])
		? $arrClassName[$arrProductsClass[0]['class_id1']]
		: "";

		// 規格2クラス名の取得
		$this->tpl_class_name2[$product_id] =
		isset($arrClassName[$arrProductsClass[0]['class_id2']])
		? $arrClassName[$arrProductsClass[0]['class_id2']]
		: "";

		// すべての組み合わせ数
		$count = count($arrProductsClass);

		$classcat_id1 = "";

		$arrSele = array();
		$arrList = array();

		$list_id = 0;
		$arrList[0] = "\tlist". $product_id. "_0 = new Array('選択してください'";
		$arrVal[0] = "\tval". $product_id. "_0 = new Array(''";

		for ($i = 0; $i < $count; $i++) {
			// 在庫のチェック
			if($arrProductsClass[$i]['stock'] <= 0 && $arrProductsClass[$i]['stock_unlimited'] != '1') {
				continue;
			}

			$stock_find = true;

			// 規格1のセレクトボックス用
			if($classcat_id1 != $arrProductsClass[$i]['classcategory_id1']){
				$arrList[$list_id].=");\n";
				$arrVal[$list_id].=");\n";
				$classcat_id1 = $arrProductsClass[$i]['classcategory_id1'];
				$arrSele[$classcat_id1] = $arrClassCatName[$classcat_id1];
				$list_id++;

				$arrList[$list_id] = "";
				$arrVal[$list_id] = "";
			}

			// 規格2のセレクトボックス用
			$classcat_id2 = $arrProductsClass[$i]['classcategory_id2'];

			// セレクトボックス表示値
			if($arrList[$list_id] == "") {
				$arrList[$list_id] = "\tlist". $product_id. "_". $list_id. " = new Array('選択してください', '". $arrClassCatName[$classcat_id2]. "'";
			} else {
				$arrList[$list_id].= ", '".$arrClassCatName[$classcat_id2]."'";
			}

			// セレクトボックスPOST値
			if($arrVal[$list_id] == "") {
				$arrVal[$list_id] = "\tval". $product_id. "_". $list_id. " = new Array('', '". $classcat_id2. "'";
			} else {
				$arrVal[$list_id].= ", '".$classcat_id2."'";
			}
		}

		$arrList[$list_id].=");\n";
		$arrVal[$list_id].=");\n";

		// 規格1
		$this->arrClassCat1[$product_id] = $arrSele;

		$lists = "\tlists".$product_id. " = new Array(";
		$no = 0;
		foreach($arrList as $val) {
			$this->tpl_javascript.= $val;
			if ($no != 0) {
				$lists.= ",list". $product_id. "_". $no;
			} else {
				$lists.= "list". $product_id. "_". $no;
			}
			$no++;
		}
		$this->tpl_javascript.= $lists.");\n";

		$vals = "\tvals".$product_id. " = new Array(";
		$no = 0;
		foreach($arrVal as $val) {
			$this->tpl_javascript.= $val;
			if ($no != 0) {
				$vals.= ",val". $product_id. "_". $no;
			} else {
				$vals.= "val". $product_id. "_". $no;
			}
			$no++;
		}
		$this->tpl_javascript.= $vals.");\n";

		// 選択されている規格2ID
		$classcategory_id = "classcategory_id". $product_id;

		$classcategory_id_2 = $classcategory_id . "_2";
		if (!isset($classcategory_id_2)) $classcategory_id_2 = "";
		if (!isset($_POST[$classcategory_id_2]) || !is_numeric($_POST[$classcategory_id_2])) $_POST[$classcategory_id_2] = "";

		$this->tpl_onload .= "lnSetSelect('" . $classcategory_id ."_1', "
		. "'" . $classcategory_id_2 . "',"
		. "'" . $product_id . "',"
		. "'" . $_POST[$classcategory_id_2] ."'); ";

		// 規格1が設定されている
		if($arrProductsClass[0]['classcategory_id1'] != '0') {
			$classcat_find1 = true;
		}

		// 規格2が設定されている
		if($arrProductsClass[0]['classcategory_id2'] != '0') {
			$classcat_find2 = true;
		}

		$this->tpl_classcat_find1[$product_id] = $classcat_find1;
		$this->tpl_classcat_find2[$product_id] = $classcat_find2;
		$this->tpl_stock_find[$product_id] = $stock_find;
	}

	/* 商品規格情報の取得 */
	function lfGetProductsClass($product_id) {
		$arrRet = array();
		if(SC_Utils_Ex::sfIsInt($product_id)) {
			// 商品規格取得
			$objQuery = new SC_Query();
			$col = "product_class_id, classcategory_id1, classcategory_id2, class_id1, class_id2, stock, stock_unlimited";
			$table = "vw_product_class AS prdcls";
			$where = "product_id = ?";
			$objQuery->setorder("rank1 DESC, rank2 DESC");
			$arrRet = $objQuery->select($col, $table, $where, array($product_id));
		}
		return $arrRet;
	}

	/* 入力内容のチェック */
	function lfCheckError($id) {

		// 入力データを渡す。
		$objErr = new SC_CheckError();

		$classcategory_id1 = "classcategory_id". $id. "_1";
		$classcategory_id2 = "classcategory_id". $id. "_2";
		$quantity = "quantity". $id;
		// 複数項目チェック
		if ($this->tpl_classcat_find1[$id]) {
			$objErr->doFunc(array("規格1", $classcategory_id1, INT_LEN), array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
		}
		if ($this->tpl_classcat_find2[$id]) {
			$objErr->doFunc(array("規格2", $classcategory_id2, INT_LEN), array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
		}
		$objErr->doFunc(array("個数", $quantity, INT_LEN), array("EXIST_CHECK", "ZERO_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));

		return $objErr->arrErr;
	}

	// 購入制限数の設定
	function lfGetSaleLimit($product) {
		//在庫が無限または購入制限値が設定値より大きい場合
		if($product['sale_unlimited'] == 1 || $product['sale_limit'] > SALE_LIMIT_MAX) {
			$this->tpl_sale_limit[$product['product_id']] = SALE_LIMIT_MAX;
		} else {
			$this->tpl_sale_limit[$product['product_id']] = $product['sale_limit'];
		}
	}

	//支払方法の取得
	//payment_id    1:代金引換　2:銀行振り込み　3:現金書留
	function lfGetPayment() {
		$objQuery = new SC_Query;
		$col = "payment_id, rule, payment_method";
		$from = "dtb_payment";
		$where = "del_flg = 0";
		$order = "payment_id";
		$objQuery->setorder($order);
		$arrRet = $objQuery->select($col, $from, $where);
		return $arrRet;
	}

	function lfconvertParam () {
		foreach ($this->arrForm as $key => $value) {
			if (preg_match('/^quantity[0-9]+/', $key)) {
				$this->arrForm[$key]
				= htmlspecialchars($this->arrForm[$key], ENT_QUOTES, CHAR_CODE);
			}
		}
	}

	// ======= 2012.05.19 RCHJ Change =======
	function lfGetSearchCategory($category_id, $bln_special = false){
		$arrSearchValues  =array();
		$count = 0;

		switch ($category_id) {
			// ワンピース
			case CATEGORY_DRESS_ALL:
			case CATEGORY_ONEPIECE:
			case CATEGORY_DRESS:
			case CATEGORY_DRESS3:
			case CATEGORY_DRESS4:
			case CATEGORY_SET_DRESS://::N00083 Add 20131201
				$arrSearchValues = array();

				if(!$bln_special){
					if(isset($_REQUEST[SIZE])){
						$arr_temp = array();
						//::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
						for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
							if(isset($_REQUEST["kind".$i])){
								$category_id_temp = $_REQUEST["kind".$i];
								foreach ($_REQUEST[SIZE] as $value) {
									if(!empty($this->arrSize[$value][$category_id_temp])){
										$arr_temp[] = $category_id_temp."_".$this->arrSize[$value][$category_id_temp];
									}
								}
							}
						}
						$arrSearchValues[] = $arr_temp;
					}

					if(isset($_REQUEST[COLOR])){

						$arr_temp = array();
						//::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
						for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
							if(isset($_REQUEST["kind".$i])){
								$category_id_temp = $_REQUEST["kind".$i];
								foreach ($_REQUEST[COLOR] as $value) {
									if(!empty($this->arrColor[$value][$category_id_temp])){
										$arr_temp[] = $category_id_temp."_".$this->arrColor[$value][$category_id_temp];
									}
								}
							}
						}
						$arrSearchValues[] = $arr_temp;
					}
				}else{
					if(isset($_REQUEST[_FUNCTION])){
						$arr_temp = array();
						$ary_function = $_REQUEST[_FUNCTION];
						sort($ary_function, SORT_NUMERIC);

						//::for($i=ONEPIECE_PRODUCT_TYPE; $i<=DRESS4_PRODUCT_TYPE; $i++){
						for($i=ONEPIECE_PRODUCT_TYPE; $i<=SET_DRESS_PRODUCT_TYPE; $i++){
							if(isset($_REQUEST["kind".$i])){
								$category_id_temp = $_REQUEST["kind".$i];
								foreach ($ary_function as $value) {
									if(!empty($this->arrFunction[$value][$category_id_temp])){
										$arr_temp[] = _FUNCTION."_".$this->arrFunction[$value][$category_id_temp]."_".$value."_".$i;
									}
								}
							}
						}
						$arrSearchValues[] = $arr_temp;
					}
				}

				break;
				// ネックレス
			case CATEGORY_NECKLACE:
				if($bln_special){ break; }

				if ($_GET[COLOR] != null ){
					$tmp = $_GET[COLOR];
					$arrSearchValues[$count] = $tmp;
					foreach ($arrSearchValues[$count] as $value) {
						$arrIndex = explode("_", $value);
						$_GET[COLOR][abs($arrIndex[0])] = $arrIndex[1];
					}
					$count++;
				}
				if ($_GET[LEN] != null ){
					$tmp = $_GET[LEN];
					$arrSearchValues[$count] = $tmp;
					foreach ($arrSearchValues[$count] as $value) {
						$arrIndex = explode("_", $value);
						$_GET[LEN][abs($arrIndex[0])] = $arrIndex[1];
					}
					$count++;
				}
				break;
				// ストール
			case CATEGORY_STOLE:
				if($bln_special){ break; }

				if ($_GET[TYPE] != null ){
					$tmp = $_GET[TYPE];
					$arrSearchValues[$count] = $tmp;
					foreach ($arrSearchValues[$count] as $value) {
						$arrIndex = explode("_", $value);
						$_GET[TYPE][abs($arrIndex[0])] = $arrIndex[1];
					}
					$count++;
				}
				if ($_GET[SIZE] != null ){
					$tmp = $_GET[SIZE];
					$arrSearchValues[$count] = $tmp;
					foreach ($arrSearchValues[$count] as $value) {
						$arrIndex = explode("_", $value);
						$_GET[SIZE][abs($arrIndex[0])] = $arrIndex[1];
					}
					$count++;
				}
				if ($_GET[COLOR] != null ){
					$tmp = $_GET[COLOR];
					$arrSearchValues[$count] = $tmp;
					foreach ($arrSearchValues[$count] as $value) {
						$arrIndex = explode("_", $value);
						$_GET[COLOR][abs($arrIndex[0])] = $arrIndex[1];
					}
					$count++;
				}

				break;
				// その他小物
			case CATEGORY_OTHERS:
				if($bln_special){ break; }

				if ($_GET[TYPE] != null ){
					$tmp = $_GET[TYPE];
					$arrSearchValues[$count] = $tmp;
					foreach ($arrSearchValues[$count] as $value) {
						$arrIndex = explode("_", $value);
						$_GET[TYPE][abs($arrIndex[0])] = $arrIndex[1];
					}
					$count++;
				}

				break;
			default:
				break;
		}
		return $arrSearchValues;
	}

	// ↓s2 201303対応 20130311
	/**
	 * Get brand data
	 *
	 * @return array
	 */
	function lfGetBrand() {
		$objQuery = new SC_Query();
		$where = "del_flg <> 1";
		$objQuery->setorder("name ASC");
		$results = $objQuery->select("brand_id, name", "dtb_brand", $where);
		foreach ($results as $result) {
			$arrBrand[$result['brand_id']] = $result['name'];
		}
		return $arrBrand;
	}

	// ↑s2 201303対応 20130311

	function lfGetUsefulMemo() {
		$objQuery = new SC_Query();
		$sql = "SELECT * FROM dtb_useful_memo limit ". PRODUCT_SELECT_USEFUL_MEMO_NUM. " offset 0";

		return $objQuery->getall($sql);
	}

	/**
     * @param type $objProduct
     * @return void
     * @author RCHJ add 2013.06.22
     */
    function doJson() {
        // 一覧メイン画像の指定が無い商品のための処理
        foreach ($this->arrProducts as $key=>$val) {
        	$icon_len = strlen($val['icon_flag']);
        	$product_id = $val['product_id'];

        	$this->arrProducts['productStatus'][$product_id] = null;
        	for($i = 0 ; $i<$icon_len;$i++){
        		$icon_flag = substr($val['icon_flag'], $i, 1);
        		$arrProductStatus = array();
        		if ($i == "1" && $icon_flag == "1"){
        			$arrProductStatus['status_name'] = "NEW";
        		}elseif($i == "0" && $icon_flag == "1"){
        			$arrProductStatus['status_name'] = "オススメ";
        		}elseif($i == "2" && $icon_flag == "1"){
        			$arrProductStatus['status_name'] = "季節限定";
        		}
        		if(!empty($arrProductStatus)){
        			$this->arrProducts['productStatus'][$product_id][] = $arrProductStatus;
        		}
        	}

        	$this->arrProducts[$key]['price02_min_tax_format'] = number_format(SC_Utils_Ex::sfPreTax($this->arrProducts[$key]['price02']));
            $this->arrProducts[$key]['main_list_image'] = SC_Utils_Ex::sfNoImageMainList($val['main_list_image']);
        }

		$this->arrProducts['page_prevNo'] = $this->tpl_pagePrevno;
		$this->arrProducts['page_nextNo'] = $this->tpl_pageNextno;
		$this->arrProducts['maxPage'] = $this->tpl_maxPage;
		$this->arrProducts['page_no'] = $this->tpl_pageno;

        echo SC_Utils_Ex::jsonEncode($this->arrProducts);
        exit;
    }
    /* 201809 add */
	 function lfGetProductCount(){
    	$objQuery = new SC_Query();
    	$result = array();
		$result['onepiece_count'] = $objQuery->count("dtb_products", "product_type = ? and status = ? and del_flg = 0", array(ONEPIECE_PRODUCT_TYPE, 1));
        //::N00083 Change 20131201
        //::$result['dress_count'] = $objQuery->count("dtb_products", "product_type in (?, ?, ?) and status = ? and del_flg = 0", array(DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, 1));
        //::$sql = "select sum(womens_review_count) from dtb_products where product_type in (?, ?, ?, ?) and status = ? and del_flg = 0";
        //::$result['women_review_count'] = $objQuery->getone($sql, array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, 1));
        $result['dress_count'] = $objQuery->count("dtb_products", "product_type in (?, ?, ?, ?) and status = ? and del_flg = 0", array(DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));
        $sql = "select sum(womens_review_count) from dtb_products where product_type in (?, ?, ?, ?, ?) and status = ? and del_flg = 0";
        $result['women_review_count'] = $objQuery->getone($sql, array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));
        //::N00083 end 20131201

		return $result;
     }
    
    function is_mobile ()
    {
        $useragents = array('iPhone','iPod','Android.*Mob','Opera.*Mini','blackberry','Windows.*Phone');
        $pattern = '/'.implode('|', $useragents).'/i';

        return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
    }

}