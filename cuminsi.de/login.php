<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        if(isset($_GET['error_code'])) {
            switch($_GET['error_code']) {
                case '2001':
                    echo "Der Benutzer existiert nciht";
                    break;
            }
        }
        if(isset($_GET['message'])) {
            switch($_GET['message']) {
                case '9001':
                    echo "Benutzer erfolgreich erstellt sie können sich nun einloggen.";
                    break;
                case '9002':
                    echo "Sie haben sich erfolgreich ausgeloggt. Sie können sich nun wieder einloggen.";
                    break;
                case '9003':
                    echo "erfolgreich verifiviert, bitte melden sie sich erneut an";
                    break;
            }
        }
    ?>
</body>
</html>