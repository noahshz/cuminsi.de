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