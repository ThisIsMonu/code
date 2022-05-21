<?php

// get all products
  $page = 1;
  $all_pdts = [];
  $products = wc_get_products( ["page"=>$page] );
  do{
    $all_pdts = array_merge($all_pdts, $products);
    $page++;
    $products = wc_get_products( ["page"=>$page] );
  } while(count($products) > 0)

//code to get a product
$product = wc_get_product( 387482 );

// update products
$pIds = "";

$all = explode(",", $pIds);

foreach($all as $id){
    wp_update_post([
        'ID' => $id
    ]);
}
