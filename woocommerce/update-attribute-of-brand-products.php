<?php

$page = 1;
$all_pdts = [];
$f_pdts = [];
$brands = ["muttofcourse"];
$products = wc_get_products(["page" => $page]);
do {
  $all_pdts = array_merge($all_pdts, $products);
  $page++;
  $products = wc_get_products(["page" => $page]);
} while (count($products) > 0);

foreach ($all_pdts as $p) {
  $brand = strtolower(trim(strip_tags(do_shortcode('[pwb-brand product_id="' . strval($p->get_id()) . '" as_link="false"]'))));
  $brand = preg_replace('/\s+/', '', $brand);
  if (in_array($brand, $brands)) {
    array_push($f_pdts, $p);
  }
}

// update product attribute

foreach ($f_pdts as $fp) {
  // update product attribute

  $attr_name = 'pa_return-exchange';
  $data = [
    'attribute_names' => [],
    'attribute_values' => [],
    'attribute_visibility' => [],
    'attribute_variation' => [],
    'attribute_position' => [],
  ];
  $prevAttrs = $fp->get_attributes();
  if (count($prevAttrs) > 0) {
    foreach ($prevAttrs as $_attr) {
      if ($_attr['name'] != $attr_name) {
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
  array_push($data['attribute_values'], [435]);
  array_push($data['attribute_visibility'], true);
  array_push($data['attribute_variation'], false);
  array_push($data['attribute_position'], count($data['attribute_position']));
  op_setAttributes($data, $fp);
}

function op_setAttributes($data = false, $pdt)
{
  $attributes = array();

  if (isset($data['attribute_names'], $data['attribute_values'])) {
    $attribute_names         = $data['attribute_names'];
    $attribute_values        = $data['attribute_values'];
    $attribute_visibility    = isset($data['attribute_visibility']) ? $data['attribute_visibility'] : array();
    $attribute_variation     = isset($data['attribute_variation']) ? $data['attribute_variation'] : array();
    $attribute_position      = $data['attribute_position'];
    $attribute_names_max_key = max(array_keys($attribute_names));

    for ($i = 0; $i <= $attribute_names_max_key; $i++) {
      if (empty($attribute_names[$i]) || !isset($attribute_values[$i])) {
        continue;
      }
      $attribute_id   = 0;
      $attribute_name = wc_clean(esc_html($attribute_names[$i]));

      if ('pa_' === substr($attribute_name, 0, 3)) {
        $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);
        $options = isset($attribute_values[$i]) ? $attribute_values[$i] : '';
      } else {
        if (isset($attribute_values[$i])) {
          if (is_array($attribute_values[$i])) {
            $options = implode(' | ', $attribute_values[$i]);
          } else {
            $options = $attribute_values[$i];
          }
        } else {
          $options = '';
        }
      }


      if (is_array($options)) {
        // Term ids sent as array.
        $options = wp_parse_id_list($options);
      } else {
        // Terms or text sent in textarea.
        $options = 0 < $attribute_id ? wc_sanitize_textarea(esc_html(wc_sanitize_term_text_based($options))) : wc_sanitize_textarea(esc_html($options));
        $options = wc_get_text_attributes($options);
      }

      if (empty($options)) {
        continue;
      }

      $attribute = new WC_Product_Attribute();
      $attribute->set_id($attribute_id);
      $attribute->set_name($attribute_name);
      $attribute->set_options($options);
      $attribute->set_position($attribute_position[$i]);
      $attribute->set_visible($attribute_visibility[$i]);
      $attribute->set_variation($attribute_variation[$i]);
      $attributes[] = apply_filters('woocommerce_admin_meta_boxes_prepare_attribute', $attribute, $data, $i);
    }
  }
  $pdt->set_attributes($attributes);
  $pdt->save();
}

// end attribute