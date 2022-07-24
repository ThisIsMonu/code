<?php

$params = [
    'post_type' => 'team',
    'posts_per_page ' => -1,
    'meta_key' => 'p',
    'orderby' => 'meta_value',
    'order' => 'ASC'
];
$query = new WP_Query($params);

$posts = $query->posts;

$positions = ['alpha', 'beta', 'gamma'];

uasort($posts, 'gasort');

function gasort($prev, $nxt)
{
    global $positions;
    $nxtP = get_post_meta((int) $nxt->ID, 'p', true);
    $prevP = get_post_meta((int) $prev->ID, 'p', true);
    $nxtTitle = $nxt->post_title;
    $prevTitle = $prev->post_title;
    $nxtPIndex = array_search($nxtP, $positions);
    $prevPIndex = array_search($prevP, $positions);

    if($nxtPIndex == $prevPIndex){
        if(strcmp($nxtTitle, $prevTitle) == 0){
            return 0;
        } elseif(strcmp($nxtTitle, $prevTitle) < 0){
            return 1;
        } else{
            return -1;
        }
    } elseif($nxtPIndex < $prevPIndex){
        if(strcmp($nxtTitle, $prevTitle) == 0){
            return 0;
        } elseif(strcmp($nxtTitle, $prevTitle) < 0){
            return 1;
        } else{
            return -1;
        }
    } else{
        return -1;
    }
}

foreach ($posts as $post) {
    // echo get_post_meta((int) $post->ID, 'p', true);
    echo <<<HTML
            <div>
                {$post->post_title}
            </div>
        HTML;
}
