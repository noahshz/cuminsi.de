<?php
    class Session {
        function __construct() {
            session_start();
        }
        function set($vname, $value) {
            $_SESSION[$vname] = $value;
        }
        function get($vname) {
            return $_SESSION[$vname];
        }
        function destroy() {
            try {
                session_unset();
                session_destroy();
            } catch(Exception $e) {
                die($e->getMessage());
            }

        }
        function isset() {
            if(isset($_SESSION['uid'])) {
                return true;
            }
            return false;
        }
    }
    class User {
        private $pdo;

        function __construct($dbconnection)
        {
            $this->pdo = $dbconnection;
        }
        function isVerified($uid): bool
        {
            $stmt = $this->pdo->prepare("SELECT `verified` FROM `users` WHERE `id` = :userid;");
            $stmt->bindParam(':userid', $uid);
            $stmt->execute();

            if($stmt->fetchAll()[0][0] == "true") {
                return true;
            }
            return false;
        }
        function getInfos($uid): array
        {
            $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `id` = :userid;");
            $stmt->bindParam(':userid', $uid);
            $stmt->execute();

            return $stmt->fetchAll();
        }
        function create($username, $email, $password, $verificationcode): void
        {
            $stmt = $this->pdo->prepare("INSERT INTO `users` (`username`, `email`, `password`, `verification_code`, `verified`) VALUES (:username, :email, :pw, :verifycode, 'false');");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pw', $password);
            $stmt->bindParam(':verifycode', $verificationcode);
            $stmt->execute();
        }
        function edit($uid, $options): void
        {
            foreach($options as $item => $value) {
                switch($item) {
                    case "username":
                        //setzte username neue value where id = uid
                        //...


                        break;
                    case "email":
                        //setzte email neue value where id = uid
                        $stmt = $this->pdo->prepare("UPDATE `users` SET `email` = :val WHERE `id` = :userid;");
                        $stmt->bindParam(":userid", $uid);
                        $stmt->bindParam(":val", $value);
                        $stmt->execute();
                        break;
                    case "password":
                        //password email neue value where id = uid
                        //...


                        break;
                    case "verified":
                        //set verified = value
                        $stmt = $this->pdo->prepare("UPDATE `users` SET `verified` = :val WHERE `id` = :userid;");
                        $stmt->bindParam(":val", $value);
                        $stmt->bindParam(":userid", $uid);
                        $stmt->execute();
                        break;
                    case "verification_code":
                        //set new verify code
                        $stmt = $this->pdo->prepare("UPDATE `users` SET `verification_code` = :val WHERE `id` = :userid;");
                        $stmt->bindParam(":userid", $uid);
                        $stmt->bindParam(":val", $value);
                        $stmt->execute();
                        break;
                }
            }
        }
    }
    class Post {
        private $pdo;

        function __construct($dbconnection)
        {
            $this->pdo = $dbconnection;
        }
        function getAll(int $limit): array
        {
            $stmt = $this->pdo->prepare("SELECT * FROM `posts` ORDER BY `id` ASC limit :lim;");
            $stmt->bindParam(":lim", $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        }
        function getUserPosts($uid): array
        {
            $stmt = $this->pdo->prepare("SELECT * FROM `posts` WHERE `uid` = :userid;");
            $stmt->bindParam(":userid", $uid, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        }
        function getInfos($postid):array 
        {
            $stmt = $this->pdo->prepare("SELECT * FROM `posts` WHERE `id` = :postid;");
            $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        function create( $uid, $title, $link, $imgpath): void
        {
            $stmt = $this->pdo->prepare("INSERT INTO `posts` (`uid`, `title`, `link`, `imgpath`) VALUES (:userid, :title, :link, :imgpath);");
            $stmt->bindParam(":userid", $uid);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":link", $link);
            $stmt->bindParam(":imgpath", $imgpath);
            $stmt->execute();
        }
        function edit() 
        {

        }
        function delete() 
        {

        }
    }
?>