<?php
    require 'requirements.php';

    $session = new Session();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'head.html'; ?>
        <title>Cuminsi.de</title>
    </head>
    <body>
        <div class="header">
            <div class="column left">
                <?php
                    if($session->isset()) {
                        echo '<a href="postmanagement.php?tab=create">Add Post</a>';
                        echo "<br>";
                        echo '<a href="postmanagement.php">Posts</a>';
                    }
                ?>
            </div>
            <div class="column center">
                <h1>Cuminsi.de</h1>
            </div>
            <div class="column right">
                <?php        
                    if($session->isset()) {
                        echo "Eingeloggt als: " . $session->get('username');
                        //logout is available
                        echo '<a href="logout.php">Logout</a>';
                        //settings are available
                        echo '<a href="settings.php">Settings</a>';
                    } else {
                        echo "ausgeloggt";
                        echo '<a href="signup.php">Registieren</a>';
                        echo '<a href="login.php">Login</a>';
                    }
                ?>
            </div>
        </div>
        <div class="content">
            <p>Haupt Content</p>
            <?php
                displayMessageOrError();


                $post = new Post($pdo);
                $currentpage = basename(__FILE__, '.php'); 
                
                foreach($post->getAll(20) as $item){
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
                    echo '<form action="reportpost.php" method="post">';
                    echo '<input name="postid" type="hidden" value=' . $item['id'] . '>';
                    echo '<input name="report" type="submit" value="report">';
                    echo '</form>';

                    echo "<br><br><br>";
                }   
            ?>
        </div>
        <div class="footer">
            <p>Impressum in so</p>
        </div>
    </body>
</html>