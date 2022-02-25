<?php
    require 'requirements.php';

    $session = new Session();
    $post = new Post($pdo);

    if(!$session->isset()) {header('Location: index.php?error_code=9050');}
    if($session->get('verified') == 'false') {header('Location: index.php?error_code=9051');}

    if(!isset($_GET['postid'])) {header('Location: postmanagement.php?error_code=7001');}

    if($session->get('uid') != $post->getInfos($_GET['postid'])[0]['uid']) {
        //der angemeldete user stimmt nicht mit dem ersteller überein
        header('Location: postmanagement.php?error_code=7002');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="postmanagement.php">Zurück</a>
    <h2>Edit Post</h2>
    <img src=<?php echo $post->getInfos($_GET['postid'])[0]['imgpath'];?>>
    <form method="post" action="logic.php?action=editpost" enctype="multipart/form-data">
        <input type="hidden" name="postid" value=<?php echo $post->getInfos($_GET['postid'])[0]['id'];?>>
        <input name="title" type="text" value=<?php echo $post->getInfos($_GET['postid'])[0]['title'];?> required>
        <input name="link" type="text" value=<?php echo $post->getInfos($_GET['postid'])[0]['link'];?> required>
        <input name="thumbnail" type="file">
        <input type="hidden" name="oldimgpath" value=<?php echo $post->getInfos($_GET['postid'])[0]['imgpath'];?>>
        <input name="editpostSubmit" type="submit" value="edit">
    </form>
</body>
</html>