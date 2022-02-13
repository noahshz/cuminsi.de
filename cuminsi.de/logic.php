<?php
    require 'requirements.php';
    
    switch($_GET['action']) {
        case 'signup':
            /*
                Step before: Check if session is active

                Step 1: Check if username already exists
                Step 2: Check if email is already used
                Step 3: Check if password1 and password2 are equal

                Step else: redirect back to signup with $_GET['error_message']

                Step 4: Insert values into db with hashed pw
                Step 5: Send VerificationMail with code
                Step Success: Redirect to some page with success message
            */
            $session = new Session();
            if($session->isset()) {
                //session = active => not allowed to create new user
                header('Location: index.php');
                exit();
            }

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
            $verification_code = generate_verification_code();
            $hashed_upw = hash(HASH, $_POST['signup_password_2']);
            $email = str_replace(" ", "", $_POST['signup_email']);
            
            $stmt = $pdo->prepare("INSERT INTO `users` (`username`, `email`, `password`, `verification_code`, `verified`) VALUES (:username, :email, :pw, :verifycode, 'false');");
            $stmt->bindParam(':username', $_POST['signup_username']);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pw', $hashed_upw);
            $stmt->bindParam(':verifycode', $verification_code);
            $stmt->execute();

            //Step 5
            try {
                send_verification_email($email, $verification_code);
            } catch (Exception $e) {
                die($e->getMessage());
            }

            //Sign up Success
            //Step Success ->
            header('Location: login.php?message=9001');

            break;

        case 'login':
            /*
                Step before: check if user is already logged in

                Step 1: Check if username exists
                Step 2: Check if hashed input_password equals to password in database AND equals to the username
                Step 3: start session AND set session data (through function)
                Step 4: redirect to index

                Step else:
                    print error code
            */

            //Step before
            // ...
            $session = new Session();

            if($session->isset()) {
                header('Location: index.php');
            }

            //Step 1
            $stmt = $pdo->prepare("SELECT COUNT(`username`) FROM `users` WHERE `username` = :username;");
            $stmt->bindParam(':username', $_POST['login_username']);
            $stmt->execute();

            if($stmt->fetchAll()[0][0] == "0") {
                //Benutzername exisitiert nicht
                header('Location: login.php?error_code=2001');
                exit();
            }

            //Step 2
            $stmt = $pdo->prepare("SELECT `password` FROM `users` WHERE `username` = :username;");
            $stmt->bindParam(':username', $_POST['login_username']);
            $stmt->execute();

            if($stmt->fetchAll()[0][0] != hash(HASH, $_POST['login_password'])) {
                //Benutzername exisitiert nicht
                header('Location: login.php?error_code=2002');
                exit();
            }

            //Step 3
            $stmt = $pdo->prepare("SELECT `id`, `username`, `email`, `verified` FROM `users` WHERE `username` = :username;");
            $stmt->bindParam(':username', $_POST['login_username']);
            $stmt->execute();

            $userdata[] = $stmt->fetchAll()[0];
            /*
                in this sector, all session variables are set

                add variables with $session->set('varname', 'value');
            */
            $session->set('uid', $userdata[0]['id']);
            $session->set('username', $userdata[0]['username']);
            $session->set('email', $userdata[0]['email']);
            $session->set('verified', $userdata[0]['verified']);

            //Step 4
            header('Location: index.php');
            
            break;

        case 'logout':
            /*
                Step 1: Check if Session is active -> session destroy
                Step 2 & else: redirect
            */

            $session = new Session();
            if($session->isset()) {
                $session->destroy();
            }
            header('Location: login.php?message=9002');

            break;
        
        case 'verify':
            $session = new Session();
            /*
                Step 1: Check if mail and activation_code isset
                Step 2: Update verified where mail = mail and code = code
                Step 3: set verificationcode = /
                Step 4 destroy session and redirect to login

            */
            if(isset($_GET['email']) && isset($_GET['activation_code'])) {
                $stmt = $pdo->prepare("UPDATE `users` SET `verified` = 'true' WHERE `email` = :email AND `verification_code` = :code;");
                $stmt->bindParam(":email", $_GET['email']);
                $stmt->bindParam(":code", $_GET['activation_code']);
                $stmt->execute();
        
                $stmt = $pdo->prepare("UPDATE `users` SET `verification_code` = '/' WHERE `email` = :email;");
                $stmt->bindParam(":email", $_GET['email']);
                $stmt->execute();
            }
        
            $session->destroy();

            header('Location: login.php?message=9003');

            break;

        case 'resendverification':
            /*
                Step 1: create new vcerification key and update db
                Step 2: send mail with new key
            */


            //ToDo
            break;
    }
?>