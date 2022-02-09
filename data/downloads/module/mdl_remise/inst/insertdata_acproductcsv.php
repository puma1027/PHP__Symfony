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
class insert_productcsv_data
{
    var $insert_remiseac_data;

    /**
     * コンストラクタ.
     *
     * @param string $customize カスタマイズのため上書きする？
     * @return void
     */
    function insert_productcsv_data()
    {
        $this->insert_remiseac_data = array(
            // 本体クラスファイル
            array(
                "csv_id"                    => '1',
                "col"                       => 'plg_remiseautocharge_total',
                "disp_name"                 => '定期購買金額',
                "mb_convert_kana_option"    => 'n',
                "size_const_type"           => 'PRICE_LEN',
                "error_check_types"         => 'NUM_CHECK,MAX_LENGTH_CHECK'
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
                "csv_id"                    => '1',
                "col"                       => 'plg_remiseautocharge_refusal_not_allowd',
                "disp_name"                 => '定期購買最低利用機関',
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
        $rank = $objQuery->getone("SELECT max(rank) FROM dtb_csv WHERE csv_id = '1'")+ 1;
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
