<?php
/**
 * ルミーズ決済モジュール・インストール処理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version install.php,v 3.0
 */

require_once MODULE_REALDIR . "mdl_remise/inc/include.php";

/**
 * インストール処理のクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class insert_ordercsv_data
{
    var $insert_remiseac_data;

    /**
     * コンストラクタ.
     *
     * @param string $customize カスタマイズのため上書きする？
     * @return void
     */
    function insert_ordercsv_data()
    {
        $this->insert_remiseac_data = array(
            // 本体クラスファイル
            array(
                "csv_id"                    => '6',
                "col"                       => 'member_id',
                "disp_name"                 => 'メンバーID',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => MEMBER_ID_COUNT,
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK'
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'customer_id',
                "disp_name"                 => '顧客ID',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => 'INT_LEN',
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'customer_name',
                "disp_name"                 => '顧客名',
                "mb_convert_kana_option"    => 'KVa',
                "size_const_type"           => 'STEXT_LEN',
                "error_check_types"         => 'SPTAB_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'customer_name_kana',
                "disp_name"                 => '顧客名カナ',
                "mb_convert_kana_option"    => 'KVCa',
                "size_const_type"           => 'STEXT_LEN',
                "error_check_types"         => 'SPTAB_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'mail',
                "disp_name"                 => 'メールアドレス',
                "mb_convert_kana_option"    => 'a',
                "size_const_type"           => 'null',
                "error_check_types"         => 'NO_SPTAB,EMAIL_CHECK,EMAIL_CHAR_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'ac_total',
                "disp_name"                 => '請求金額',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => 'PRICE_LEN',
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'result',
                "disp_name"                 => '結果',
                "mb_convert_kana_option"    => 'KVa',
                "size_const_type"           => 'STEXT_LEN',
                "error_check_types"         => 'SPTAB_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'tranid',
                "disp_name"                 => 'トランザクションID',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => TRANID_COUNT,
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'err_cd',
                "disp_name"                 => 'エラーコード',
                "mb_convert_kana_option"    => 'an',
                "size_const_type"           => 'INT_LEN',
                "error_check_types"         => 'MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'err_detail',
                "disp_name"                 => 'エラー詳細',
                "mb_convert_kana_option"    => 'an',
                "size_const_type"           => 'INT_LEN',
                "error_check_types"         => 'MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'ac_next_date',
                "disp_name"                 => '次回課金日',
                "mb_convert_kana_option"    => 'a',
                "size_const_type"           => '',
                "error_check_types"         => 'CHECK_DATE',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'paydate',
                "disp_name"                 => '課金処理日',
                "mb_convert_kana_option"    => 'a',
                "size_const_type"           => '',
                "error_check_types"         => 'CHECK_DATE',
            ),
        );

        $this->insert_productcsv_data = array(
            // 本体クラスファイル
            array(
                "csv_id"                    => '1',
                "col"                       => 'plg_remiseautocharge_total',
                "disp_name"                 => '定期購買金額',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => 'PRICE_LEN',
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '1',
                "col"                       => 'plg_remiseautocharge_first_interval',
                "disp_name"                 => '定期購買開始月',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => 'INT_LEN',
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '1',
                "col"                       => 'plg_remiseautocharge_next_date',
                "disp_name"                 => '定期購買課金日',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => 'INT_LEN',
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '1',
                "col"                       => 'plg_remiseautocharge_interval',
                "disp_name"                 => '定期購買決済間隔',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => 'INT_LEN',
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK',
            ),
            array(
                "csv_id"                    => '6',
                "col"                       => 'plg_remiseautocharge_refusal_not_allowd',
                "disp_name"                 => '定期購買最低利用期間',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => 'INT_LEN',
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK',
            ),
        );
    }

    /**
     * ファイルコピー.
     *
     * @return void
     */
    function datainsert()
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objDB = new SC_Helper_DB_Ex();
        $errmsg = "";

        $no = $objQuery->getone("SELECT max(no) FROM dtb_csv") + 1;
        $rank = '1';
        foreach ($this->insert_remiseac_data as $data) {
            $data += array("no" => $no);
            $data += array("rank" => $rank);
            $data += array("rw_flg" => '1');
            $data += array("status" => '1');
            $data += array("create_date" => CURRENT_TIMESTAMP);
            $data += array("update_date" => CURRENT_TIMESTAMP);

            $objQuery->insert("dtb_csv", $data);

            $no++;
            $rank++;
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
