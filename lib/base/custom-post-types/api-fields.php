<?php

function rest_get_post_field($post, $field_name, $request)
{
  return get_post_meta($post['id'], $field_name, true);
}

function rest_get_post_categories($post, $field_name, $request)
{
  return array_map(function ($term) {
    return $term->name;
  }, wp_get_post_terms($post['id'], array($field_name))); // get_the_category($post['id']);
}

function rest_get_featured_media($post, $field_name, $request)
{
  $feat_img_array = wp_get_attachment_image_src(
    $post['featured_media'], // Image attachment ID
    'thumbnail',  // Size.  Ex. "thumbnail", "large", "full", etc..
    true // Whether the image should be treated as an icon.
  );
  return $feat_img_array[0];
}

