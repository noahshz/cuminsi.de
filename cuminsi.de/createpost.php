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
    <title>Add post</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="index.php">Zurück</a>
    <h2>create post</h2>
    <?php
        displayMessageOrError();
    ?>
    <form action="logic.php?action=createpost" method="post" enctype="multipart/form-data">
        <input name="title" type="text" placeholder="title" required>
        <input name="link" type="text" placeholder="link" required>
        <input name="createpostSubmit" type="submit" value="create">
        <input name="thumbnail" type="file">
    </form>
</body>
</html>