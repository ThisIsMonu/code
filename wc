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
