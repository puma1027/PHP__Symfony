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

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 商品選びのお役立ちメモのページクラス.
 *
 * @package Page
 * @author CHS
 * @version $Id$
 */
class LC_Page_Admin_Contents_UsefulMemo extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
	{
        parent::init();
        $this->tpl_mainpage = 'contents/useful_memo.tpl';
        $this->tpl_mainno = 'contents';
        $this->tpl_subno = "useful_memo";
		$this->tpl_maintitle = 'コンテンツ管理';
        $this->tpl_subtitle = 'お役立ちメモ';
        $this->arrRegistColumn = array(
	        array(  "column" => "title", "convert" => "aKV" ),
	        array(  "column" => "url", "convert" => "aKV" ),
	        array(  "column" => "id" ),
        );
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
	{
		$this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
		// フォーム操作クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_REQUEST);

		$is_error = false;

		switch ($this->getMode()) {
            // 登録
            case 'edit':
				$objFormParam->convParam();
				$this->arrErr = $objFormParam->checkError();
				$is_error = (!SC_Utils_Ex::isBlank($this->arrErr));
				if (!$is_error) {
					$result = $this->doRegist($objFormParam, $objBloc);
					if ($result !== false) {
                        $arrPram = array(
                            'msg' => 'on'
                        );
						SC_Response_Ex::reload($arrPram, true);
						SC_Response_Ex::actionExit();
					} else {
						$this->tpl_onload = "window.alert('登録が失敗しました。');";
					}
				}
                break;
            default:
                if (isset($_GET['msg']) && $_GET['msg'] == 'on') {
                    // 完了メッセージ
                    $this->tpl_onload = "alert('登録が完了しました。');";
                }
                break;
        }

		if (!$is_error) {
			$this->arrUsefulMemo = $this->lfGetUsefulMemos();
		} else {
			$this->arrUsefulMemo = $this->lfGetParam($objFormParam);
		}
    }

    /**
     * パラメーター情報の初期化
     *
     * @param  object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
		for($i = 1; $i <= PRODUCT_SELECT_USEFUL_MEMO_NUM; $i++) {
			$objFormParam->addParam('ID', 'id' . $i, INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
			$objFormParam->addParam('タイトル', 'title' . $i, STEXT_LEN, 'aKV', array('MAX_LENGTH_CHECK'));
			$objFormParam->addParam('URL', 'url' . $i, STEXT_LEN, 'aKV', array('URL_CHECK', 'MAX_LENGTH_CHECK'));
		}
    }
	
    /**
     * お役立ちメモの情報を取得する.
     *
     * @return array   お役立ちメモの配列
     */
    public function lfGetUsefulMemos()
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
		$objQuery->setLimitOffset(PRODUCT_SELECT_USEFUL_MEMO_NUM);

        return $objQuery->select('*', 'dtb_useful_memo');
    }
	
    /**
     * パラメトを取得する.
     *
     * @return array   パラメト
     */
    public function lfGetParam($objFormParam)
    {
		$arrParam = array();
		for($i = 1; $i <= PRODUCT_SELECT_USEFUL_MEMO_NUM; $i++) {
			$arrParam[] = array(
            			'id' => $objFormParam->getValue('id'.$i),
            			'title' => $objFormParam->getValue('title'.$i),
            			'url' => $objFormParam->getValue ('url'.$i)
            		);
		}

        return $arrParam;
    }

    /**
     * 登録を実行する.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function doRegist(&$objFormParam)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        for ($i = 1; $i <= PRODUCT_SELECT_USEFUL_MEMO_NUM; $i++) {
			// 入力文字の強制変換
			$regist_data = array(
				'id' => $objFormParam->getValue('id' . $i),
				'title' => $objFormParam->getValue('title' . $i),
				'url' => $objFormParam->getValue('url' . $i)
			);

			// データの登録	                
			if(!SC_Utils_Ex::isBlank($regist_data['id'])){
				$regist_data['update_date'] = 'NOW()';
				
				$objQuery->update('dtb_useful_memo', $regist_data, 'id=?', array($regist_data['id']));
			}else{
				unset($regist_data['id']);
				
				$regist_data['update_date'] = 'NOW()';
				$regist_data['create_date'] = 'NOW()';
			
				$objQuery->insert('dtb_useful_memo', $regist_data );
			}
        }
        $objQuery->commit();

		return true;
    }
}