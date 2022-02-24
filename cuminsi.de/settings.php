<?php
    require 'requirements.php';
    
    $session = new Session();
    if(!$session->isset()) {header('Location: index.php');}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="index.php">Zurück</a>
    <h2>User info</h2>
    <?php
        echo "Uid: " . $session->get('uid') . "<br>";
        echo "Email: " . $session->get('email') . "<br>";
        echo "Username: " . $session->get('username') . "<br>";
        echo "Verified: " . $session->get('verified') . "<br>";
        echo "<br>";

        if($session->get('verified') == "false") {
            echo "resend the verification email\n";
            echo "<a href='logic.php?action=resendverification'>send</a>";
        }
        
        displayMessageOrError();
    ?>
    <br><br>
    <label for="settingsChangeEmail">Change Email</label>
    <form method="post" action="logic.php?action=changeUserMail">
        <input name="settingsChangeEmail" type="email" placeholder="new email" required>
        <input name="settingsChangeEmailSubmit" type="submit" value="Change">
    </form>
</body>
</html>