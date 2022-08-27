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

<?php
// // update products
//  $page = 1;
//   $all_pdts = [];
//   $products = wc_get_products(["page" => $page]);
//   do {
//     $all_pdts = array_merge($all_pdts, $products);
//     $page++;
//     $products = wc_get_products(["page" => $page]);
//   } while (count($products) > 0);
//   foreach($all_pdts as $p){
//       wp_update_post([
//           'ID' => $p->get_id()
//       ]);
//   }
 
//   echo "done";
//   // end update products

// update product meta by brand

//  $page = 1;
//   $all_pdts = [];
//   $f_pdts = [];
//   $brands = ["farminavetlife"];
//   $products = wc_get_products(["page" => $page]);
//   do {
//     $all_pdts = array_merge($all_pdts, $products);
//     $page++;
//     $products = wc_get_products(["page" => $page]);
//   } while (count($products) > 0);

//   foreach($all_pdts as $p){
// 	  $brand = strtolower(trim(strip_tags(do_shortcode('[pwb-brand product_id="'. strval($p->get_id()) .'" as_link="false"]'))));
// 	  $brand = preg_replace('/\s+/', '', $brand);	
//      if(in_array($brand,$brands)){
// 		 array_push($f_pdts, $p);
// 	 }
//   }

// 	foreach($f_pdts as $pd){
// 		$bool = update_post_meta($pd->get_id(), 'exclude_from_search', "0", "1");
// 		if($bool){
// 			wp_update_post([
// 			  'ID' => $pd->get_id()
// 		  ]);
// 		}
// 		echo $bool ? $pd->get_title() : "fail: " . $pd->get_title(); 
// 	}

// end update product meta by brand

//$product = wc_get_product(396157);
// var_dump($product->is_in_stock());
// $order = get_user_meta( 4182 );
// $usermerow = $wpdb->get_row(
//         $wpdb->prepare(
//             'SELECT * FROM ' . $wpdb->usermeta . '
//         WHERE meta_value = %s AND meta_key= %s LIMIT 1',
//             "8087901671", "digits_phone"
//         )
//     );
// $order = wc_get_order(397197);
// echo "<pre>";
// print_r($order);
// echo "</pre>";
// 


// $args = [
// 	'post_type' => 'product',
// 	'meta_query' => [
// 		'relation' => 'OR',
// 		[
// 			'key' => 'exclude_from_search',
// 			'compare' => "NOT EXISTS",
// 		],
// 		[
// 			'key' => 'exclude_from_search',
// 			'value' => '1',
// 			'compare' => '!=',
// 		]
// 	],
// 	'posts_per_page' => 12,
// ];

// $query1 = new WP_Query( $args );
// while ( $query1->have_posts() ) {
//     $query1->the_post();
//     echo '<li>' . get_the_title() . '</li>';
// }
// wp_reset_postdata();

// $post = get_post(398397);

// $pdt = get_post_meta(398397, 'exclude_from_search', true);
// echo "<pre>";
// print_r($post->ID);
// echo "</pre>";
