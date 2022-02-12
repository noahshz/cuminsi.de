<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link rel="stylesheet" href="css/style.css">
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
        require 'requirements.php';

        displayMessageOrError();
    ?>
</body>
</html>