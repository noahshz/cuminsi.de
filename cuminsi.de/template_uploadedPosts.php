<h2>Meine Posts</h2>

    <a href="createpost.php">Create Post</a>
    <?php
        echo "<hr>";
        displayMessageOrError();
        echo "<hr>";

        $post = new Post($pdo);
    
        foreach($post->getUserPosts($session->get('uid')) as $item) {
            if(file_exists($item['imgpath'])) {
                echo '<img width="150" src="' . $item['imgpath'] . '">';
            } else {
                echo '<img width="150" src="' . THUMBNAIL_UPLOAD_FOLDER . "thumbnail_placeholder.jpg" . '">';
            }

            echo "<br>";
            echo '<a href="' . $item['link'] . '" target="blank">' . $item['title'] . '</a>';
            echo "<br>";
            echo '<a href="editpost.php?postid=' . $item['id'] . '">Bearbeiten</a>';
            echo "<br>";
            echo '<a href="logic.php?action=deletepost&postid=' . $item['id'] . '">Löschen</a>';
            echo "<br><br><br>";
        }
    ?>