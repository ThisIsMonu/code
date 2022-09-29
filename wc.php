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
  
//  filter products based on category
$allowedCats = ["Dry Cat Food", "Wet Cat Food", "Cat Grooming", "Brushes and Combs", "Training and Cleaning"];

$fp = array_filter($all_pdts, function($pdt){
global $allowedCats;
foreach($allowedCats as $cat){
  if(str_contains( strip_tags( $pdt->get_categories() ), $cat )){
    return true;
  }
}
});

// end filter


// update product attribute

$attr_name = 'pa_return-exchange';
$data = [
  'attribute_names' => [],
  'attribute_values' => [],
  'attribute_visibility' => [],
  'attribute_variation' => [],
  'attribute_position' => [],
];
$prevAttrs = $pdt->get_attributes();
if(count($prevAttrs) > 0){
  foreach($prevAttrs as $_attr){
    if($_attr['name'] != $attr_name){
      array_push($data['attribute_names'], $_attr['name']);
      array_push($data['attribute_values'], $_attr['options']);
      array_push($data['attribute_visibility'], $_attr['visible']);
      array_push($data['attribute_variation'], $_attr['variation']);
      array_push($data['attribute_position'], $_attr['position']);
    }
  }
}
 // add return/exchange unavailable
array_push($data['attribute_names'], $attr_name);
array_push($data['attribute_values'], [ 435 ]);
array_push($data['attribute_visibility'], true);
array_push($data['attribute_variation'], false);
array_push($data['attribute_position'], count($data['attribute_position']));
op_setAttributes($data, $pdt);


function op_setAttributes( $data = false, $pdt ) {
  $attributes = array();

  if ( isset( $data['attribute_names'], $data['attribute_values'] ) ) {
    $attribute_names         = $data['attribute_names'];
    $attribute_values        = $data['attribute_values'];
    $attribute_visibility    = isset( $data['attribute_visibility'] ) ? $data['attribute_visibility'] : array();
    $attribute_variation     = isset( $data['attribute_variation'] ) ? $data['attribute_variation'] : array();
    $attribute_position      = $data['attribute_position'];
    $attribute_names_max_key = max( array_keys( $attribute_names ) );

    for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
      if ( empty( $attribute_names[ $i ] ) || ! isset( $attribute_values[ $i ] ) ) {
        continue;
      }
      $attribute_id   = 0;
      $attribute_name = wc_clean( esc_html( $attribute_names[ $i ] ) );

      if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {
        $attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
        $options = isset( $attribute_values[ $i ] ) ? $attribute_values[ $i ] : '';
      } else {
        if(isset( $attribute_values[ $i ] )){
          if(is_array($attribute_values[ $i ])){
            $options = implode(' | ', $attribute_values[ $i ]);
          } else {
            $options = $attribute_values[ $i ];
          }
        } else {
          $options = '';
        }
      }


      if ( is_array( $options ) ) {
        // Term ids sent as array.
        $options = wp_parse_id_list( $options );
      } else {
        // Terms or text sent in textarea.
        $options = 0 < $attribute_id ? wc_sanitize_textarea( esc_html( wc_sanitize_term_text_based( $options ) ) ) : wc_sanitize_textarea( esc_html( $options ) );
        $options = wc_get_text_attributes( $options );
      }

      if ( empty( $options ) ) {
        continue;
      }

      $attribute = new WC_Product_Attribute();
      $attribute->set_id( $attribute_id );
      $attribute->set_name( $attribute_name );
      $attribute->set_options( $options );
      $attribute->set_position( $attribute_position[ $i ] );
      $attribute->set_visible( $attribute_visibility[ $i ] );
      $attribute->set_variation( $attribute_variation[ $i ] );
      $attributes[] = apply_filters( 'woocommerce_admin_meta_boxes_prepare_attribute', $attribute, $data, $i );
    }
  }
  $pdt->set_attributes( $attributes );
  $pdt->save();
}

// end attribute
