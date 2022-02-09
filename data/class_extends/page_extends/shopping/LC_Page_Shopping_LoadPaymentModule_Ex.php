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

require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_LoadPaymentModule.php';

/**
 * 決済モジュールの呼び出しを行うクラス(拡張).
 *
 * LC_Page_Shopping_LoadPaymentModule をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author Kentaro Ohkouchi
 * @version $Id$
 */
class LC_Page_Shopping_LoadPaymentModule_Ex extends LC_Page_Shopping_LoadPaymentModule
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    /* 20200525 sg nakagawa 旧ソース */
/*
    function process()
    {
        parent::process();
        $objSiteSess = new SC_SiteSession_Ex();
		$objCartSess = new SC_CartSession_Ex();
        $objQuery = new SC_Query_Ex();

		// 前のページで正しく登録手続きが行われた記録があるか判定
		SC_Utils_Ex::sfIsPrePage($objSiteSess);

		// アクセスの正当性の判定
		$uniqid = SC_Utils_Ex::sfCheckNormalAccess($objSiteSess, $objCartSess);
        var_dump($uniqid);
		$payment_id = $_SESSION["payment_id"];
		// 支払いIDが無い場合にはエラー
		if($payment_id == ""){
			SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true);
		}

		// 決済情報を取得する
		$objDB = new SC_Helper_DB_Ex();

		if($objDB->sfColumnExists("dtb_payment", "memo01")){
			$sql = "SELECT module_path, memo01, memo02, memo03, memo04, memo05, memo06, memo07, memo08, memo09, memo10 FROM dtb_payment WHERE payment_id = ?";
			$arrPayment = $objQuery->getall($sql, array($payment_id));
		}

		if(count($arrPayment) > 0) {
			$path = $arrPayment[0]['module_path'];
			if(file_exists($path)) {
				require_once($path);
				exit;
			} else {
                $msg = 'モジュールファイルの取得に失敗しました。<br />この手続きは無効となりました。';
				SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, $msg);
			}
		}
    }
*/
}
