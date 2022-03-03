<?php
    require 'requirements.php';

    $session = new Session();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <title>Cuminsi.de</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h1>Login</h1>
        <a href="index.php">Zurück</a>
        <form method="post" action="logic.php?action=login">
            <input name="login_username" type="text" placeholder="Username" required>
            <input name="login_password" type="password" placeholder="Password" required>
            <input name="login_submit" type="submit" value="Login">
        </form>
        <?php
            displayMessageOrError();
        ?>
    </body>
</html>