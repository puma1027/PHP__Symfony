<?php


require_once './require.php';

$objQuery = SC_Query_Ex::getSingletonInstance();


$res = $objQuery->select( '*', 'dtb_products' );

foreach( $res as $key => $val )
{
    // imageをコピーする
    //exec( 'cp -p ' . '画像元', $val['main_image'] );
    //exec( 'cp -p ' . 'upload/save_image/dummy.jpg', $val['main_list_image'] );


    if ( $key === 5) exit();
}
