<h2>liked Posts</h2>
<?php
    //displayMessageOrError();

    $post = new Post($pdo);

    $post_template = file_get_contents("post.html");

    foreach($post->getLikedPosts($session->get('uid')) as $item) {
        $post_filled = $post_template;

        $post_filled = str_replace("%%POSTID%%", $item['id'], $post_filled);
        $post_filled = str_replace("%%CURRENTPAGE%%", $currentpage, $post_filled);
        $post_filled = str_replace("%%LINK%%", $item['link'], $post_filled);
        $post_filled = str_replace("%%TITLE%%", $item['title'], $post_filled);
    
        if(file_exists($item['imgpath'])) {
            $post_filled = str_replace("%%THUMBNAIL_PATH%%", $item['imgpath'], $post_filled);
        } else {
            $post_filled = str_replace("%%THUMBNAIL_PATH%%", THUMBNAIL_UPLOAD_FOLDER . "thumbnail_placeholder.jpg", $post_filled);
        }

        if($post->isLikedByUser($item['id'], @$session->get('uid'))) {
            $rate_input = '<input id="unlike" name="unlike" type="submit" value="&#9829">';
        } else {
            $rate_input = '<input id="like" name="like" type="submit" value="&#9829">';
        }
        $post_filled = str_replace("%%RATE_ACTION%%", $rate_input, $post_filled);

        echo $post_filled;
        $post_filled = $post_template;
    }
?>