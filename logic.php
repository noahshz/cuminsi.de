<?php
    require 'requirements.php';
    
    switch($_GET['action']) {
        case 'signup':
            /*
                Step 1: Check if username already exists
                Step 2: Check if email is already used
                Step 3: Check if password1 and password2 are equal

                Step else: redirect back to signup with $_GET['error_message']

                Step 4: Insert values into db with hashed pw
                Step Success: Redirect to some page with success message
            */

            //Step 1
            $stmt = $pdo->prepare("SELECT COUNT(`username`) FROM `users` WHERE `username` = :username;");
            $stmt->bindParam(':username', $_POST['signup_username']);
            $stmt->execute();

            if($stmt->fetchAll()[0][0] == "1") {
                //Benutzername bereits vorhanden
                header('Location: signup.php?error_code=1001');
                exit();
            }

            //Step 2
            $stmt = $pdo->prepare("SELECT COUNT(`email`) FROM `users` WHERE `email` = :email;");
            $stmt->bindParam(':email', $_POST['signup_email']);
            $stmt->execute();

            if($stmt->fetchAll()[0][0] == "1") {
                //Benutzername bereits vorhanden
                header('Location: signup.php?error_code=1002');
                exit();
            }

            //Step 3
            if($_POST['signup_password_1'] != $_POST['signup_password_2']) {
                //Passwörter stimmen nicht überein
                header('Location: signup.php?error_code=1003');
                exit();
            }

            //Step 4
            $password = encode($_POST['signup_password_2']);

            $stmt = $pdo->prepare("INSERT INTO `users` (`username`, `email`, `password`, `verified`) VALUES (:username, :email, :pw, 'false');");
            $stmt->bindParam(':username', $_POST['signup_username']);
            $stmt->bindParam(':email', $_POST['signup_email']);
            $stmt->bindParam(':pw', $password);
            $stmt->execute();

            //Sign up Success
            //Step Success ->
            break;
    }
?>