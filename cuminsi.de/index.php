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
        <header>
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
        </header>
        <main>
            <div class="posts">
                <?php
                    displayMessageOrError();

                    /* Initializes the Paginator */
                    $paginator = new Paginator($pdo);
                    $paginator->setLimit(20);

                    $post = new Post($pdo);
                    $currentpage = basename(__FILE__, '.php'); 
                    
                    /*
                        PLACEHOLDER:

                        %%THUMBNAIL_PATH%% = path to img
                        %%POSTID%% = id of curr post
                        %%CURRENTPAGE%% = name of current page
                        %%LINK%% = link
                        %%TITLE%% = title of post

                        %%INPUT_NAME%% = name of ratepost
                        %%INPUT_VALUE%% = placeholder for ratepost action
                    */
                    
                    $post_template = file_get_contents("post.html");

                    foreach($paginator->getResults() as $item) {
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
            </div>
            <div class="paginator">
                <?php 
                    //shows the paginator bar
                    $paginator->show(); 
                ?>
            </div>
        </main>
        <footer>
            <p>Impressum und so</p>
        </footer>
    </body>
</html>