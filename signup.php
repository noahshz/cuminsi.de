<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
</head>
<body>
    <form method="post" action="logic.php?action=signup">
        <input name="signup_username" type="text" placeholder="Username" required>
        <input name="signup_email" type="email" placeholder="Email" required>
        <input name="signup_password_1" type="password" placeholder="Password" required>
        <input name="signup_password_2" type="password" placeholder="Repeat Password" required>
        <input name="signup_submit" type="submit" value="Sign up">
    </form>
    <?php
        if(isset($_GET['error_code'])) {
            switch($_GET['error_code']) {
                case '1001':
                    echo "Der Benutzername ist bereits vorhanden. Bitte wählen sie einen anderen Benutzernamen aus.";
                    break;
                case '1002':
                    echo "Die Email adresse wird bereits verwendet.";
                    break;
                case '1003':
                    echo "Die eingegebenen Passwörter stimmen nicht überein.";
            }
        }
    ?>
</body>
</html>