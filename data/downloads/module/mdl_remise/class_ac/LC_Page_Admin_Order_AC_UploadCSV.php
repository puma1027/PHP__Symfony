<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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

// ルミーズ決済モジュール
if (file_exists(MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php')) {
    require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
}

/**
 * 定期購買受注登録CSVのページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id:LC_Page_Admin_Order_AC_UploadCSV.php $
 *
 */
class LC_Page_Admin_Order_AC_UploadCSV extends LC_Page_Admin_Ex
{
    /**
     * TAGエラーチェックフィールド情報
     */
    public $arrTagCheckItem;

    /**
     * 受注テーブルカラム情報 (登録処理用)
     */
    public $arrACOrderColumn;

    /**
     * 登録フォームカラム情報
     */
    public $arrFormKeyList;

    public $arrRowErr;

    public $arrRowResult;

    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'order/upload_csv.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'upload_csv';
        $this->tpl_maintitle = '受注登録';
        $this->tpl_subtitle = '定期購買　受注登録CSV';
        $this->csv_id = '6';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrAllowedTag = $masterData->getMasterData('mtb_allowed_tag');
        $this->arrTagCheckItem = array();
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
        $this->objDb = new SC_Helper_DB_Ex();

        // CSV管理ヘルパー
        $objCSV = new SC_Helper_CSV_Ex();
        // CSV構造読み込み
        $arrCSVFrame = $objCSV->sfGetCsvOutput($this->csv_id);

        // CSV構造がインポート可能かのチェック
        if (!$objCSV->sfIsImportCSVFrame($arrCSVFrame)) {
            // 無効なフォーマットなので初期状態に強制変更
            $arrCSVFrame = $objCSV->sfGetCsvOutput($this->csv_id, '', array(), 'no');
            $this->tpl_is_format_default = true;
        }
        // CSV構造は更新可能なフォーマットかのフラグ取得
        $this->tpl_is_update = $objCSV->sfIsUpdateCSVFrame($arrCSVFrame);

        // CSVファイルアップロード情報の初期化
        $objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        $this->lfInitFile($objUpFile);

        // パラメーター情報の初期化
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam, $arrCSVFrame);

        $this->max_upload_csv_size = SC_Utils_Ex::getUnitDataSize(CSV_SIZE);

        $objFormParam->setHtmlDispNameArray();
        $this->arrTitle = $objFormParam->getHtmlDispNameArray();

        switch ($this->getMode()) {
            case 'csv_upload':
                $this->doUploadACOrderCsv($objFormParam, $objUpFile);
                break;
            case 'csv_confirm':
                $this->countUploadCsv($objFormParam, $objUpFile);
                if (empty($this->arrErr['csv_file'])) {
                    $this->realfile = $objUpFile->temp_file[0];
                    $this->tpl_mainpage = 'order/upload_ac_csv_confirm.tpl';
                }
                break;
            default:
                break;
        }
    }

    /**
     * 登録/編集結果のメッセージをプロパティへ追加する
     *
     * @param  integer $line_count 行数
     * @param  stirng  $message    メッセージ
     * @return void
     */
    public function addRowResult($line_count, $message)
    {
        $this->arrRowResult[] = $line_count . '行目：' . $message;
    }

    /**
     * 登録/編集結果のエラーメッセージをプロパティへ追加する
     *
     * @param  integer $line_count 行数
     * @param  stirng  $message    メッセージ
     * @return void
     */
    public function addRowErr($line_count, $message)
    {
        $this->arrRowErr[] = $line_count . '行目：' . $message;
    }

    /**
     * CSVファイルを読み込んで、保存処理を行う
     *
     * @param $objFormParam
     * @param $fp CSVファイルポインタ
     * @param $objQuery 保存を行うためのクエリ(指定がない場合、テストのみを行う)
     * @return boolean errFlag. 読み込みに失敗した場合true
     */
    public function lfReadCSVFile(&$objFormParam, &$fp, $objQuery = null)
    {
        $dry_run = ($objQuery===null) ? true : false;
        // 登録対象の列数
        $col_max_count = $objFormParam->getCount();
        // 行数
        $line_count = 0;
        // 処理に失敗した場合にtrue
        $errFlag = false;

        while (!feof($fp)) {
            $arrCSV = fgetcsv($fp, CSV_LINE_MAX);

            // 行カウント
            $line_count++;
            // ヘッダ行はスキップ
            if ($line_count == 1) {
                continue;
            }
            // 空行はスキップ
            if (empty($arrCSV)) {
                continue;
            }
            // 列数が多すぎる場合はエラー、列数が少ない場合は未設定として配列を補う
            $col_count = count($arrCSV);
            if ($col_count > $col_max_count) {
                $this->addRowErr($line_count, '※ 項目数が' . $col_count . '個検出されました。項目数は' . $col_max_count . '個になります。');
                $errFlag = true;
                break;
            }
            else if ($col_count < $col_max_count) {
                $arrCSV = array_pad($arrCSV, $col_max_count, "");
                if (!$dry_run) {
                    $this->addRowResult($line_count, ($col_count + 1) . "項目以降を空欄として読み込みました");
                }
            }

            // シーケンス配列を格納する。
            $objFormParam->setParam($arrCSV, true);
            // 入力値の変換
            $objFormParam->convParam();

            // GWバグ対策(会員IDとメールアドレスの両方がCSVファイルに無い場合、受注テーブルの一致するメンバーID情報に記載の情報で上書きする)
            $customer_id = $objFormParam->getValue('customer_id');
            $order_email = $objFormParam->getValue('order_email');
            if ($customer_id == '' && $order_email != '') {
                $sqlval = $this->GetOrderFromMemberId($objFormParam->getValue('plg_remiseautocharge_member_id'));
                $objFormParam->setValue('customer_id', $sqlval['customer_id']);
                $objFormParam->setValue('order_email', $sqlval['order_email']);
            }

            // <br>なしでエラー取得する。
            $arrCSVErr = $this->lfCheckError($objFormParam);
            if (count($arrCSVErr) > 0) {
                foreach ($arrCSVErr as $err) {
                    $this->addRowErr($line_count, $err);
                }
                $errFlag = true;
                break;
            }

            if (!$dry_run) {
                $this->lfRegistACOrder($objQuery, $line_count, $objFormParam);
                $arrParam = $objFormParam->getHashArray();

                $this->addRowResult($line_count, '顧客ID：' . $arrParam['customer_id'] .
                    '/ ルミーズメンバーID：' . $arrParam['plg_remiseautocharge_member_id'] .
                    ' / 顧客名：' . $arrParam['customer_name'] . ' / 金額：' . $arrParam['plg_remiseautocharge_total']);
            }
            SC_Utils_Ex::extendTimeOut();
        }

        return $errFlag;
    }

    /**
     * CSVアップロードを実行します.
     *
     * @return void
     */
    public function doUploadACOrderCsv(&$objFormParam, &$objUpFile)
    {
        // 一時ファイル名の取得
        $filepath = $objUpFile->getTempFilePath('csv_file');
        // CSVファイルの文字コード変換
        $enc_filepath = SC_Utils_Ex::sfEncodeFile($filepath, CHAR_CODE, CSV_TEMP_REALDIR);
        // CSVファイルのオープン
        $fp = fopen($enc_filepath, 'r');
        // 失敗した場合はエラー表示
        if (!$fp) {
            SC_Utils_Ex::sfDispError('');
        }

        // 登録先テーブル カラム情報の初期化
        $this->lfInitTableInfo();

        // 登録フォーム カラム情報
        $this->arrFormKeyList = $objFormParam->getKeyList();

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        // CSVからの読み込み、入力エラーチェック
        $errFlag = $this->lfReadCSVFile($objFormParam, $fp);
        if (!$errFlag) {
            rewind($fp);
            // CSVからの読み込み、保存
            $errFlag = $this->lfReadCSVFile($objFormParam, $fp, $objQuery);
        }

        // 実行結果画面を表示
        $this->tpl_mainpage = 'order/upload_csv_complete.tpl';

        fclose($fp);

        if ($errFlag) {
            $objQuery->rollback();

            return;
        }

        $objQuery->commit();

        // 商品件数カウント関数の実行
        $this->objDb->sfCountCategory($objQuery);
        $this->objDb->sfCountMaker($objQuery);
    }

    /**
     * ファイル情報の初期化を行う.
     *
     * @return void
     */
    public function lfInitFile(&$objUpFile)
    {
        $objUpFile->addFile('CSVファイル', 'csv_file', array('csv'), CSV_SIZE, true, 0, 0, false);
    }

    /**
     * 入力情報の初期化を行う.
     *
     * @param array CSV構造設定配列
     * @return void
     */
    public function lfInitParam(&$objFormParam, &$arrCSVFrame)
    {
        // CSV項目毎の処理
        foreach ($arrCSVFrame as $item) {
            if ($item['status'] == CSV_COLUMN_STATUS_FLG_DISABLE) continue;

            // サブクエリ構造の場合は AS名 を使用
            if (preg_match_all('/\(.+\)\s+as\s+(.+)$/i', $item['col'], $match, PREG_SET_ORDER)) {
                $col = $match[0][1];
            } else {
                $col = $item['col'];
            }
            $error_check_types = $item['error_check_types'];
            $arrErrorCheckTypes = explode(',', $error_check_types);
            foreach ($arrErrorCheckTypes as $key => $val) {
                if (trim($val) == '') {
                    unset($arrErrorCheckTypes[$key]);
                } else {
                    $arrErrorCheckTypes[$key] = trim($val);
                }
            }
            // パラメーター登録
            $objFormParam->addParam(
                    $item['disp_name']
                    , $col
                    , constant($item['size_const_type'])
                    , $item['mb_convert_kana_option']
                    , $arrErrorCheckTypes
                    , $item['default']
                    , ($item['rw_flg'] != CSV_COLUMN_RW_FLG_READ_ONLY) ? true : false
                    );
        }
    }

    /**
     * 入力チェックを行う.
     *
     * @return void
     */
    public function lfCheckError(&$objFormParam)
    {
        // 入力データを渡す。
        $arrRet =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrRet);
        $objErr->arrErr = $objFormParam->checkError(false);
        // HTMLタグチェックの実行
        foreach ($this->arrTagCheckItem as $item) {
            $objErr->doFunc(array($item['disp_name'], $item['col'], $this->arrAllowedTag), array('HTML_TAG_CHECK'));
        }
        // このフォーム特有の複雑系のエラーチェックを行う
        if (count($objErr->arrErr) == 0) {
            $objErr->arrErr = $this->lfCheckErrorDetail($arrRet, $objErr->arrErr);
        }

        return $objErr->arrErr;
    }

    /**
     * 保存先テーブル情報の初期化を行う.
     *
     * @return void
     */
    public function lfInitTableInfo()
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $this->arrACOrderColumn = $objQuery->listTableFields('dtb_order');
    }

    /**
     * 受注登録を行う.
     *
     * @param  SC_Query       $objQuery SC_Queryインスタンス
     * @param  string|integer $line     処理中の行数
     * @return void
     */
    public function lfRegistACOrder($objQuery, $line = '', &$objFormParam)
    {
        // 登録データ対象取得
        $arrList = $objFormParam->getHashArray();

        // 登録時間を生成(DBのCURRENT_TIMESTAMPだとcommitした際、全て同一の時間になってしまう)
        $arrList['update_date'] = $this->lfGetDbFormatTimeWithLine($line);

        $sqlval = $this->GetOrderFromMemberId($arrList['plg_remiseautocharge_member_id']);
        $arrDetail = SC_Helper_Purchase_Ex::getOrderDetail($sqlval['order_id']);

        // 次回課金日整形
        $nextdate = str_replace('/', '', $arrList['plg_remiseautocharge_next_date']);
        $sqlval['plg_remiseautocharge_next_date'] =
                substr($nextdate, 0, 4) . '年' . substr($nextdate, 4, 2) . '月' . substr($nextdate, 6, 2) . '日';

        // 新規登録
        $sqlval['order_id'] = $objQuery->nextVal('dtb_order_order_id');
        $sqlval['memo06'] = 'CAPTURE';
        $sqlval['memo10'] = 'continue';
        $sqlval['subtotal'] = $arrList['plg_remiseautocharge_total'] - $sqlval['deliv_fee'];
        $sqlval['tax'] = '0';
        $sqlval['payment_total'] = $sqlval['total'] = $arrList['plg_remiseautocharge_total'];
        $sqlval['memo10'] = 'continue';
        $order_id = $sqlval['order_id'];
        $sqlval['create_date'] = $sqlval['payment_date'] = $arrList['paydate'];
        $sqlval['update_date'] = $arrList['update_date'];
        $sqlval['commit_date'] = '';
        // 元の決済情報の次回課金日を更新
        if ($arrList['result'] == '成功') {
            $sqlval['status'] = ORDER_PRE_END;
            $sqlval['memo04'] = $arrList['tranid'];
            $memberval['plg_remiseautocharge_next_date'] = $sqlval['plg_remiseautocharge_next_date'];
            $where = 'plg_remiseautocharge_member_id = ? AND memo10 = ?';
            $objQuery->update('dtb_order', $memberval, $where, array($arrList['plg_remiseautocharge_member_id'], 'autocharge'));
        } else {
            $sqlval['status'] = ORDER_PAY_WAIT;
            $sqlval['memo04'] = '';
        }

        $objQuery->insert('dtb_order', $sqlval);
        if (!empty($arrDetail)) {
            SC_Helper_Purchase_Ex::registerOrderDetail($order_id, $arrDetail);
        }
    }

    /**
     * このフォーム特有の複雑な入力チェックを行う.
     *
     * @param array 確認対象データ
     * @param array エラー配列
     * @return array エラー配列
     */
    public function lfCheckErrorDetail($item, $arrErr)
    {
        // メンバーIDの存在チェック
        if (!$this->lfIsDbRecord('dtb_order', 'plg_remiseautocharge_member_id', $item)) {
            $arrErr['plg_remiseautocharge_member_id'] = '※ 指定のメンバーIDは、登録されていません。';
        }

        if (strlen($item['plg_remiseautocharge_member_id']) != MEMBER_ID_COUNT) {
            $arrErr['plg_remiseautocharge_member_id'] = '※ メンバーIDの入力が不正です。';
        }

        if (strlen($item['tranid']) != TRANID_COUNT) {
            $arrErr['tranid'] = '※ トランザクションIDの入力が不正です。';
        }

        // 既にDBに存在するトランザクションIDはエラーとする
        if ($this->lfIsDbRecord_TranID('dtb_order', 'tranid', $item)) {
            //$arrErr['tranid'] = '※ 既に登録済みの受注情報です。';
        }

        // 会員IDの存在チェック
        if (!$this->lfIsDbRecord('dtb_customer', 'customer_id', $item)) {
            $arrErr['customer_id'] = '※ 指定の顧客IDは、登録されていません。';
        }

        // メールアドレスの存在チェック
        if (!$this->lfIsDbRecord('dtb_order', 'order_email', $item)) {
            $arrErr['order_email'] = '※ 指定のメールアドレスは、登録されていません。';
        }
        // 電話番号の存在チェック
        if (strlen($item['order_tel']) < 9 && strlen($item['order_tel']) > 11) {
            $arrErr['plg_remiseautocharge_member_id'] = '※ 電話番号の入力が不正です。';
        }

        // 結果情報のチェック
        if (array_search('result', $this->arrFormKeyList) !== FALSE && $item['result'] != '') {
            if (!($item['result'] == '成功' or $item['result'] == '失敗')) {
                $arrErr['result'] = '※ 結果情報に不正な値が設定されています。';
            }
        }

        return $arrErr;
    }

    /**
     * CSVアップロード行数をカウントします.
     *
     * @return void
     */
    public function countUploadCsv(&$objFormParam, &$objUpFile)
    {
        // ファイルアップロードのチェック
        $this->arrErr['csv_file'] = $objUpFile->makeTempFile('csv_file');
        if (strlen($this->arrErr['csv_file']) >= 1) {
            return;
        }
        $arrErr = $objUpFile->checkExists();
        if (count($arrErr) > 0) {
            $this->arrErr = $arrErr;
            return;
        }

        // 一時ファイル名の取得
        $filepath = $objUpFile->getTempFilePath('csv_file');
        // CSVファイルの文字コード変換
        $enc_filepath = SC_Utils_Ex::sfEncodeFile($filepath, CHAR_CODE, CSV_TEMP_REALDIR);
        // CSVファイルのオープン
        $fp = fopen($enc_filepath, 'r');

        // 失敗した場合はエラー表示
        if (!$fp) {
            SC_Utils_Ex::sfDispError('');
        }
        $count = $this->sfGetCSVRecordDetailCount($fp);
        $this->total_count = '計' . $count . '件';
        $this->success_count = '計' . $this->success . '件';
        $this->failure_count = '計' . $this->failure . '件';

        fclose($fp);
    }

    // TODO: ここから下のルーチンは汎用ルーチンとして移動が望ましい
    /**
     * 指定された行番号をmicrotimeに付与してDB保存用の時間を生成する。
     * トランザクション内のCURRENT_TIMESTAMPは全てcommit()時の時間に統一されてしまう為。
     *
     * @param  string $line_no 行番号
     * @return string $time DB保存用の時間文字列
     */
    public function lfGetDbFormatTimeWithLine($line_no = '')
    {
        $time = date('Y-m-d H:i:s');
        // 秒以下を生成
        if ($line_no != '') {
            $microtime = sprintf('%06d', $line_no);
            $time .= ".$microtime";
        }

        return $time;
    }

    /**
     * 指定されたキーと値の有効性のDB確認
     *
     * @param  string  $table   テーブル名
     * @param  string  $keyname キー名
     * @param  array   $item    入力データ配列
     * @return boolean true:有効なデータがある false:有効ではない
     */
    public function lfIsDbRecord($table, $keyname, $item) {
        if (array_search($keyname, $this->arrFormKeyList) !== FALSE                 // 入力対象である
            && $item[$keyname] != ''                                                // 空ではない
            && !$this->objDb->sfIsRecord($table, $keyname, (array) $item[$keyname]) //DBに存在するか
        ) {
            return false;
        }

        return true;
    }

    /**
     * メンバーIDから定期会員受注情報を取得する。
     *
     * @param  string  $member_id     チェック対象配列
     * @return boolean true:有効なデータがある false:有効ではない
     */
    public function GetOrderFromMemberId($member_id)
    {
        // 定期購買受注より受注情報を取得する。
        $objQuery = new SC_Query_Ex();
        $where = "plg_remiseautocharge_member_id = ? AND memo10 = ?";
        $arrOrder = $objQuery->select('*', 'dtb_order', $where, array($member_id, 'autocharge'));
        return $arrOrder[0];
    }

    /**
     * 指定されたキーと値の有効性のDB確認(トランザクションID用)
     *
     * @param  string  $table   テーブル名
     * @param  string  $keyname キー名
     * @param  array   $item    入力データ配列
     * @return boolean true:有効なデータがある false:有効ではない
     */
    public function lfIsDbRecord_TranID($table, $keyname, $item)
    {
        if (array_search($keyname, $this->arrFormKeyList) !== FALSE                 // 入力対象である
            && $item[$keyname] != ''                                                // 空ではない
            && !$this->objDb->sfIsRecord($table, 'memo04', (array) $item[$keyname]) // DBに存在するか
        ) {
            return false;
        }
        return true;
    }
}
