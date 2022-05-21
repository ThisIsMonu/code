<?php

// get all products
  $page = 1;
  $all_pdts = [];
  $products = wc_get_products( ["page"=>$page] );
  do{
    $all_pdts = array_merge($all_pdts, $products);
    $page++;
    $products = wc_get_products( ["page"=>$page] );
  } while(count($products) > 0);

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




// get product variants
$variationIDs = $product->get_children();
        $variants = [];
        foreach ($variationIDs as $varID) {
          $var =  new WC_Product_Variation($varID);
          array_push($variants, [
            "id" => $varID,
            "name" => $var->name,
            "regular_price" => $var->regular_price,
            "sale_price" => $var->sale_price,
            "avail" => $var->is_in_stock() ? 'true' : 'false',
            "total_sales" => $var->total_sales,
          ]);
        }
