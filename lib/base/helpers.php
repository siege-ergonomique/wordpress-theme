<?php
// SVG support
//_________
function custom_mtypes($m)
{
    $m['svg'] = 'image/svg+xml';
    $m['svgz'] = 'image/svg+xml';
    return $m;
}
add_filter('upload_mimes', 'custom_mtypes');

