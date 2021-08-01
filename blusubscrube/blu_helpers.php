<?php
/**
 *   Query
 */
function get_subscribers($all = false){
    global $wpdb;
    $pagination_params = pagination_params();

    if($all){
        return $wpdb->get_results("SELECT * FROM blu_subsciption_email", ARRAY_A);
    }

    return $wpdb->get_results("SELECT * FROM blu_subsciption_email LIMIT {$pagination_params['start_pos']}, {$pagination_params['perpage']}", ARRAY_A);
}


/**
 *   Pagination Params
 */
function pagination_params(){
    global $wpdb;

    // Subscribers Count
    $perpage = 10;

    // All Count Posts.
    $count = $wpdb->get_var("SELECT COUNT(*) FROM blu_subsciption_email");

    // Nesesery Count Page.
    $count_pages = ceil( $count / $perpage );

    if(!$count_pages) $count_pages = 1;

    if(isset( $_GET['paged'] )){
        $page = (int)$_GET['paged'];
        if($page < 1) $page = 1;
    }else{
        $page = 1;
    }

    // If needed pages more than max number page.
    if($page > $count) $page = $count_pages;

    // Start position for query.
    $start_pos = ($page - 1) * $perpage;

    $pagination_params = array(
        'page' => $page,
        'count' => $count,
        'count_pages' => $count_pages,
        'perpage' => $perpage,
        'start_pos' => $start_pos
    );
    return $pagination_params;
}


/**
 *   Pagination
 */
function pagination($page, $count_pages){
    // << < 3 4 5 6 7 > >>
    $back = null; // Url back
    $forward = null; // Url forward
    $startpage = null; // Url to begin
    $endpage = null; // Url to end
    $page2left = null; // Second page from left
    $page1left = null; // First page from left
    $page2right = null; // Second page from right
    $page1right = null; // First page from right

    $uri = "?";
    if( $_SERVER['QUERY_STRING'] ){
        foreach( $_GET as $key => $value){
            if($key != 'paged') $uri .= "{$key}=$value&";
        }
    }

    if( $page > 1 ){
        $back = "<a class='nav-link' href='{$uri}paged=". ($page - 1) ."'>Назад</a>";
    }
    if( $page < $count_pages ){
        $forward = "<a class='nav-link' href='{$uri}paged=". ($page + 1) ."'>Вперёд</a>";
    }
    if( $page > 3 ){
        $startpage = "<a class='nav-link' href='{$uri}paged=1'>В начало</a>";
    }
    if( $page > ($count_pages - 2) ){
        $endpage = "<a class='nav-link' href='{$uri}paged={$count_pages}'>В конец</a>";
    }
    if( $page - 2 > 0 ){
        $page2Left = "<a class='nav-link' href='{$uri}paged=". ($page - 2) ."'>". ($page - 2) ."</a>";
    }
    if( $page - 1 > 0 ){
        $page1left = "<a class='nav-link' href='{$uri}paged=". ($page - 1) ."'>". ($page - 1) ."</a>";
    }
    if( $page + 1 <= $count_pages ){
        $page1rigt = "<a class='nav-link' href='{$uri}paged=". ($page + 1) ."'>". ($page + 1) ."</a>";
    }
    if( $page + 2 <= $count_pages ){
        $back = "<a class='nav-link' href='{$uri}paged=". ($page + 2) ."'>". ($page + 2) ."</a>";
    }

    return $startpage . $back . $page2left . $page1left . '<a class="active-page">' . $page . '</a>' . $page1right . $page2right . $forward . $endpage;
}