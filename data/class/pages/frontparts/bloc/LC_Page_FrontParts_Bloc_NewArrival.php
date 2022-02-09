<?php

// {{{ requires
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * Product_List のページクラス.
 *
 * @package Page
 */
class LC_Page_FrontParts_Bloc_NewArrival extends LC_Page_FrontParts_Bloc {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        //$bloc_file = 'bloc/new_arrival.tpl';
        //$this->setTplMainpage($bloc_file);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        //$objQuery = new SC_Query_Ex();
        $objQuery = new SC_Query();
        // 2020.09.09 hori ドレスが消費税10%に対応するためにSQLにtmp_aomを追加
        $sql = <<<EOL
select
  pro.product_id
  , cls.product_code
  , CEILING(cls.price02 * (select (tax / 100 + 1) from dtb_baseinfo)) as aom
  , pro.name
  , pro.main_image
  , (select count(1) from dtb_review where product_id = pro.product_id) as cnt
  , (select round(avg(recommend_level), 1) from dtb_review where product_id = pro.product_id) as star
  , CEILING(cls.price02 * (select ( tax / 100 + 1) from dtb_baseinfo) ) as tmp_aom
from
  dtb_products pro
    inner join dtb_products_class cls
      on pro.product_id = cls.product_id
where
  cls.product_code like '11-%'
  and pro.icon_flag = '01000000'
  and pro.del_flg = '0'
  and pro.haiki is null
  and pro.status = 1
  and pro.parent_flg = 1
order by
  cls.product_code desc
limit ?
EOL;
$item_limit_pc = 8;
$item_limit_sp = 9;

$arrRes_pc = $objQuery->getAll($sql, $item_limit_pc);
$arrRes_sp = $objQuery->getAll($sql, $item_limit_sp);

// add ishibashi 20220121
foreach($arrRes_pc as $key => $val)
{
    $arrRes_pc[$key] = SC_Utils_Ex::productReplaceWebp($val);
}

foreach($arrRes_sp as $key => $val)
{
    $arrRes_sp[$key] = SC_Utils_Ex::productReplaceWebp($val);
}
// add ishibashi 20220121
$this->arrRes_pc = $arrRes_pc;
$this->arrRes_sp = $arrRes_sp;

/*
        $objProduct = new SC_Product_Ex();

        $objQuery->setLimitOffset(6);
        $objQuery->setOrder("update_date desc");
        //$this->arrProducts = $objProduct->lists(&$objQuery);
        $this->arrProducts = $objProduct->lists($objQuery);
        //$objView->assignobj($this);
        //$objView->display($this->tpl_mainpage);
*/
        $this->sendResponse();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
}

?>
