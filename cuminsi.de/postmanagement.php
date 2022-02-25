<?php
    require 'requirements.php';

    $session = new Session();
    if(!$session->isset()){header('Location: index.php');}
    if($session->get('verified') == "false"){header('Location: index.php');}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="index.php">Zurück</a>
    <h2>Post Management</h2>
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
            echo '<a href="logic.php?action=deletepost&postid=' . $item['id'] . '">Löschen</a>';
            echo "<br><br><br>";
        }
    ?>
</body>
</html>