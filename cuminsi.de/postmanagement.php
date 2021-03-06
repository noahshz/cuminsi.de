<?php
    require 'requirements.php';

    $session = new Session();

    if(!$session->isset()){header('Location: index.php?error_code=9050');}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'head.html'; ?>
        <title>Cuminsi.de</title>
    </head>
    <body>
        <a href="index.php">Zurück</a>

        <hr>
        <a href="postmanagement.php?tab=uploaded">Uploaded Posts</a>
        <a href="postmanagement.php?tab=liked">Liked Posts</a>
        <a href="postmanagement.php?tab=create">Create Post</a>
        <hr>

        <?php
            displayMessageOrError();
            $currentpage = basename(__FILE__, '.php'); 

            if(isset($_GET['tab'])) {
                switch($_GET['tab']) {
                    case 'uploaded':
                        //show tab with own uploaded posts - allow only verified
                        if($session->get('verified') == "false") {
                            echo "to use this function pleaser verify first";
                        } else {
                            include 'template_uploadedPosts.php';
                        }
                        break;
                    case 'liked':
                        //show tab with liked posts - allow for everyone
                        include 'template_likedPosts.php';
                        break;
                    case 'create':
                        //show tab to create post - allow only verified
                        if($session->get('verified') == "false") {
                            echo "to use this function pleaser verify first";
                        } else {
                            include 'template_createPost.php';
                        }
                        break;
                    default:
                        //default case: show uploaded posts
                        if($session->get('verified') == "false") {
                            include 'template_likedPosts.php';
                        } else {
                            include 'template_uploadedPosts.php';
                        }
                        break;
                }
            } else {
                //if site is opened without get variable then own posts are displayed
                if($session->get('verified') == "false") {
                    include 'template_likedPosts.php';
                } else {
                    include 'template_uploadedPosts.php';
                }
            }
        ?>
    </body>
</html>