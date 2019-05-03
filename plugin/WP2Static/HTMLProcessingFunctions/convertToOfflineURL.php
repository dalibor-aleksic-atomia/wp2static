<?php

/*
    When publishing our static site for offline usage, we need to ensure a few
    transformations are made:

     - with no site relativity possible to rely on, we need all links to be
       document relative, ie to go to parent page, use ../page not /page or
       file:///page (allowing for C:\page,/home/me/page, etc

     - with no webserver to render default documents like /index.html, we need
       to rewrite all links to full post/index.html style URLs

*/
function convertToOfflineURL( $url_to_change, $page_url, $placeholder_url  ) {
    $current_page_path_to_root = '';
    $current_page_path = parse_url( $page_url, PHP_URL_PATH );
    $number_of_segments_in_path = explode( '/', $current_page_path );
    $num_dots_to_root = count( $number_of_segments_in_path ) - 2;

    for ( $i = 0; $i < $num_dots_to_root; $i++ ) {
        $current_page_path_to_root .= '../';
    }

    $rewritten_url = str_replace(
        $placeholder_url,
        '',
        $url_to_change
    );

    $offline_url = $current_page_path_to_root . $rewritten_url;

    // add index.html if no extension
    if ( substr( $offline_url, -1 ) === '/' ) {
        // TODO: check XML/RSS case
        $offline_url .= 'index.html';
    }

    return $offline_url;
}
