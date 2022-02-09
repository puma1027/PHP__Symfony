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
 * おすすめ商品管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Contents_RScan extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'contents/r_scan.tpl';
        $this->tpl_mainno = 'contents';
        $this->tpl_subno = 'r_scan';
        $this->tpl_maintitle = 'コンテンツ管理';
        $this->tpl_subtitle = 'レンタル票電子化';
        //$this->tpl_pager = 'pager.tpl';
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
        $objQuery = new SC_Query();
        $where = "status <> 8 AND ? <= sending_date AND sending_date <= ?";
        $objQuery->setorder("order_id");

        switch ($this->getMode()) {
            // レンタル票pdfファイル名をダウンロード
            case 'pdf_name_download_1_weeks_ago':
                $arrRet = $objQuery->select("order_id, customer_id", "dtb_order", $where, array(date('Ymd', strtotime("2 weeks ago Sunday")), date('Ymd', strtotime("1 weeks ago Saturday"))));
                foreach ($arrRet as $key=>$val) {
                    $customer_id = sprintf('%05d', $val['customer_id']);
                    $out[$key] = $val['order_id']."_".$customer_id;
                    $out[$key] .= "\n";
                }
                $this->arrForm = $out;
                break;

            case 'pdf_name_download_2_weeks_ago':
                $arrRet = $objQuery->select("order_id, customer_id", "dtb_order", $where, array(date('Ymd', strtotime("3 weeks ago Sunday")), date('Ymd', strtotime("2 weeks ago Saturday"))));
                foreach ($arrRet as $key=>$val) {
                    $customer_id = sprintf('%05d', $val['customer_id']);
                    $out[$key] = $val['order_id']."_".$customer_id;
                    $out[$key] .= "\n";
                }
                $this->arrForm = $out;
                break;

            case 'pdf_name_download_3_weeks_ago':
                $arrRet = $objQuery->select("order_id, customer_id", "dtb_order", $where, array(date('Ymd', strtotime("4 weeks ago Sunday")), date('Ymd', strtotime("3 weeks ago Saturday"))));
                foreach ($arrRet as $key=>$val) {
                    $customer_id = sprintf('%05d', $val['customer_id']);
                    $out[$key] = $val['order_id']."_".$customer_id;
                    $out[$key] .= "<br />";
                }
                $this->arrForm = $out;
                break;

            // 初期表示
            default:
                break;
        }

    }

}
