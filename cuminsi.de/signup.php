<?php
    require 'requirements.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'head.html'; ?>
    <title>SignUp</title>
</head>
<body>
    <a href="index.php">Zurück</a>
    <form method="post" action="logic.php?action=signup">
        <input name="signup_username" type="text" placeholder="Username" required>
        <input name="signup_email" type="email" placeholder="Email" required>
        <input name="signup_password_1" type="password" placeholder="Password" required>
        <input name="signup_password_2" type="password" placeholder="Repeat Password" required>
        <input name="signup_submit" type="submit" value="Sign up">
    </form>
    <?php
        displayMessageOrError();
    ?>
</body>
</html>