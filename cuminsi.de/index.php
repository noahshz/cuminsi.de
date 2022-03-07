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
            <div class="posts">
                <!--div class="post">
                    <div class="top">
                        <div class="thumbnail">
                            <img src="img/post_template-v1.png">  
                        </div>
                        <div class="actions">
                            <form action="" method="post">
                                <input type="submit" value="like">
                            </form>
                            <form action="" method="">
                                <input type="submit" value="report">
                            </form>
                        </div>
                    </div>
                    <div class="bot">
                        <a href="link">Titel</a>
                    </div>
                </div-->

                <?php
                    displayMessageOrError();

                    /* Initializes the Paginator */
                    $paginator = new Paginator($pdo);
                    $paginator->setLimit(15);

                    $post = new Post($pdo);
                    $currentpage = basename(__FILE__, '.php'); 
                    
                    $post_template = file_get_contents("post.html");
                    /*
                        PLACEHOLDER:

                        %%THUMBNAIL_PATH%% = path to img
                        %%POSTID%% = id of curr post
                        %%CURRENTPAGE%% = name of current page
                        %%LINK%% = link
                        %%TITLE%% = title of post
                    */

                    foreach($paginator->getResults() as $item) {
                        /*
                        $post_filled = $post_template;
                        $post_filled = str_replace("%%THUMBNAIL_PATH%%", $item['imgpath'], $post_filled);
                        $post_filled = str_replace("%%POSTID%%", $item['id'], $post_filled);
                        $post_filled = str_replace("%%CURRENTPAGE%%", $currentpage, $post_filled);
                        $post_filled = str_replace("%%LINK%%", $item['link'], $post_filled);
                        $post_filled = str_replace("%%TITLE%%", $item['title'], $post_filled);

                        echo $post_filled;
                        $post_filled = $post_template;
                        */
                    
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
                        if($post->isLikedByUser($item['id'], @$session->get('uid'))) {
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
        </div>
        <div class="footer">
            <?php 
                //shows the paginator bar
                $paginator->show(); 
            ?>
            <p>Impressum und so</p>
        </div>
    </body>
</html>