<h2>liked Posts</h2>
<?php
    //displayMessageOrError();

    $post = new Post($pdo);

    foreach($post->getLikedPosts($session->get('uid')) as $item) {
        if(file_exists($item['imgpath'])) {
            echo '<img width="150" src="' . $item['imgpath'] . '">';
        } else {
            echo '<img width="150" src="' . THUMBNAIL_UPLOAD_FOLDER . "thumbnail_placeholder.jpg" . '">';
        }

        echo "<br>";
        echo '<a href="' . $item['link'] . '" URL target="blank">' . $item['title'] . '</a>';
        echo "<br>";
        //if post is not liked -> display like else display unlike
        echo '<form action="logic.php?action=ratepost" method="post">';
        echo '<input name="postid" type="hidden" value=' . $item['id'] . '>';
        echo '<input name="currentpage" type="hidden" value=' . $currentpage . '>';
        if($post->isLikedByUser($item['id'], $session->get('uid'))) {
            echo '<input name="unlike" type="submit" value="unlike">';
        } else {
            echo '<input name="like" type="submit" value="like">';
        }
        echo '</form>';

        echo "<br><br><br>";
    }
?>