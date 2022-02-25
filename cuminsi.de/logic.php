<?php
    require 'requirements.php';
    print_r($_POST);
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
                //Email bereits vorhanden
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
            
            $user = new User($pdo);
            $user->create($_POST['signup_username'], $email, $hashed_upw, $verification_code);

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
                Step before: Check if user already verified
                Step 1: create new vcerification key and update db
                Step 2: send mail with new key
            */
            $session = new Session();
            if(!$session->isset()) {header('Location: index.php?error_code=9050');}

            //Step before
            if($session->get('verified') == "true") {
                header('Location: settings.php?error_code=8002');
            }

            //Step 1
            $newVerifyCode = generate_verification_code();

            $stmt = $pdo->prepare("UPDATE `users` SET `verification_code` = :newcode WHERE `id` = :userid;");
            $stmt->bindParam(":userid", $session->get('uid'));
            $stmt->bindParam(":newcode", $newVerifyCode);
            $stmt->execute();

            //Step 2
            try {
                send_verification_email($session->get('email'), $newVerifyCode);
            } catch (Exception $e) {
                die($e->getMessage());
            }

            header('Location: settings.php?message=8001');
            
            break;
        
        case 'changeUserMail':
            $user = new User($pdo);
            $session = new Session();

            if(!$session->isset()) {header('Location: index.php?error_code=9050');}
            /*
                new mail: $_POST['settingsChangeEmail']
                Step 1: check if input email already exists
                
                Step maybe: if email is changed -> set verified = false and create new token

                Step 2: update db with new mail
                Step 3: update session variable 'email'
            */
            if(!isset($_POST['settingsChangeEmail'])) {die("mail not set");}

            $session = new Session();

            //Step 1
            $stmt = $pdo->prepare("SELECT COUNT(`email`) FROM `users` WHERE `email` = :newmail;");
            $stmt->bindParam(":newmail", $_POST['settingsChangeEmail']);
            $stmt->execute();

            if($stmt->fetchAll()[0][0] == "1") {
                //Email bereits vorhanden
                header('Location: settings.php?error_code=1002');
                exit();
            }

            //Step maybe
            $newVerifyCode = generate_verification_code();

            $stmt = $pdo->prepare("UPDATE `users` SET `verified` = 'false' WHERE `id` = :userid;");
            $stmt->bindParam(":userid", $session->get('uid'));
            $stmt->execute();

            $session->set('verified', 'false');

            //$user->edit($session->get('uid'), ['verification_code' => $newVerifyCode]);
            $stmt = $pdo->prepare("UPDATE `users` SET `verification_code` = :newcode WHERE `id` = :userid;");
            $stmt->bindParam(":userid", $session->get('uid'));
            $stmt->bindParam(":newcode", $newVerifyCode);
            $stmt->execute();

            try {
                send_verification_email($session->get('email'), $newVerifyCode);
            } catch (Exception $e) {
                die($e->getMessage());
            }

            //Step 2
            //$user->edit($session->get('uid'), ['email' => $_POST['settingsChangeEmail']]);
            $stmt = $pdo->prepare("UPDATE `users` SET `email` = :newmail WHERE `id` = :userid;");
            $stmt->bindParam(":userid", $session->get('uid'));
            $stmt->bindParam(":newmail", $_POST['settingsChangeEmail']);
            $stmt->execute();

            //Step 3
            $session->set('email', $_POST['settingsChangeEmail']);

            header('Location: settings.php?message=8003');

            break;

        case 'createpost':
            /*
                Step 1: check if user is logged in
                Step 2: check if user is verified
                Step 3: create post in db
                Step 4: upload img to server 

            */
            $session = new Session();
            //$user = new User($pdo);
            $post = new Post($pdo);

            //Step 1
            if(!$session->isset()) {header('Location: index.php?error_code=9050');}

            //Step 2:
            if($session->get('verified') == 'false') {header('Location: index.php?error_code=9051');}
                //if(!$user->isVerified($session->get('uid'))) {header('Location: index.php');}

            //Step 3 + 4
            if(!isset($_POST['title']) && !isset($_POST['link'])) {
                $title = "[empty title]";
                $link = "cuminsi.de";
            } else {
                $title = $_POST['title'];
                $link = $_POST['link'];
            }

            //creates uplaod / file name
            $uploadFolder = THUMBNAIL_UPLOAD_FOLDER;
            $imgtype = substr($_FILES['thumbnail']['type'], 6);
            $filename = "u" . $session->get('uid') . "__" . $title . "__" . date("Ymdmis") . "." . $imgtype;
            $imgpath = $uploadFolder . $filename;

            //inserts into db
            $post->create($session->get('uid'), $title, $link,  $imgpath);

            //uploads file to path
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $imgpath)) {
                //data is valid
            }

            header('Location: index.php');

            break;

        case 'deletepost':
            $session = new Session();
            $post = new Post($pdo);
            
            if(!$session->isset()) {header('Location: index.php?error_code=9050');}
            if($session->get('verified') == 'false') {header('Location: index.php?error_code=9051');}

            if(!isset($_GET['postid'])) {header('Location: postmanagement.php?error_code=7001');}

            /*
                checke, ob die aktive session id mit der uid vom post übereinstimmt
            */
            if($session->get('uid') != $post->getInfos($_GET['postid'])[0]['uid']) {
                //der angemeldete user stimmt nicht mit dem ersteller überein
                header('Location: postmanagement.php?error_code=7002');
            }
            $stmt = $pdo->prepare('DELETE FROM `posts` WHERE id = :postid AND `uid` = :userid;');
            $stmt->bindParam(":postid", $_GET['postid'], PDO::PARAM_INT);
            $stmt->bindParam(":userid", $session->get('uid'), PDO::PARAM_INT);
            $stmt->execute();

            header('Location: postmanagement.php?message=7050');
            break;
        
        case 'editpost':
            print_r($_POST);
            die();

            break;
    }
?>