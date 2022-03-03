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
        function getTotal(): int
        {
            return $this->pdo->query('SELECT COUNT(*) FROM posts;')->fetchColumn();
        }
        function getAll(int $limit = 0): array
        {
            if($limit == 0) {
                $stmt = $this->pdo->prepare("SELECT * FROM `posts` ORDER BY `id` ASC;");
            } else {
                $stmt = $this->pdo->prepare("SELECT * FROM `posts` ORDER BY `id` ASC limit :lim;");
                $stmt->bindParam(":lim", $limit, PDO::PARAM_INT);
            }
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
        function getLikedPosts($uid): array
        {
            $stmt = $this->pdo->prepare('
                SELECT posts.*
                FROM posts 
                JOIN users_posts_liked as liked
                ON liked.postid = posts.id
                WHERE liked.uid = :userid;
            ');
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
        function edit($uid, $postid, $options) 
        {
            foreach($options as $item => $value) {
                switch($item) {
                    case "title":
                        $stmt = $this->pdo->prepare('UPDATE `posts` SET `title` = :title WHERE `id` = :postid AND `uid` = :userid;');
                        $stmt->bindParam(':title', $value, PDO::PARAM_STR);
                        $stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
                        $stmt->bindParam(':userid', $uid, PDO::PARAM_INT);
                        $stmt->execute();
                        break;
                    case "link":
                        $stmt = $this->pdo->prepare('UPDATE `posts` SET `link` = :link WHERE `id` = :postid AND `uid` = :userid;');
                        $stmt->bindParam(':link', $value, PDO::PARAM_STR);
                        $stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
                        $stmt->bindParam(':userid', $uid, PDO::PARAM_INT);
                        $stmt->execute();
                        break;
                }
            }
        }
        function isLikedByUser($postid, $uid) : bool
        {
            $stmt = $this->pdo->prepare('SELECT COUNT(`id`) FROM users_posts_liked WHERE `uid` = :userid AND `postid` = :postid;');
            $stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
            $stmt->bindParam(':userid', $uid, PDO::PARAM_INT);
            $stmt->execute();
            
            if($stmt->fetchAll()[0][0] != 0) {
                return true;
            }
            return false;
        }
        function like($postid, $uid) : void
        {
            $stmt = $this->pdo->prepare('INSERT INTO users_posts_liked (`uid`, `postid`) VALUES (:userid, :postid);');
            $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
            $stmt->bindParam(":userid", $uid, PDO::PARAM_INT);
            $stmt->execute();
        }
        function unlike($postid, $uid) : void
        {
            $stmt = $this->pdo->prepare('DELETE FROM users_posts_liked WHERE `uid` = :userid AND `postid` = :postid;');
            $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
            $stmt->bindParam(":userid", $uid, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    class Paginator 
    {
        private string $total;
        private int $limit;
        private $pdo;
        private int $pages;
        private int $page;
        private int $offset;
        private string $prevlink, $nextlink;
        private int $start, $end;

        private $stmt;

        function __construct($dbconnection) 
        {
            $this->pdo = $dbconnection;
            $this->limit = $this->getTotal();
        }
        public function setLimit(int $limit = 0) : void
        {
            if($limit == 0) {
                $this->limit = $this->getTotal();
            } else {
                $this->limit = $limit;
            }
            $this->build();
        }
        private function build()
        {
            $this->total = $this->getTotal();
            $this->pages = ceil($this->total / $this->limit);
            $this->page = min($this->pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
                'options' => array(
                    'default'   => 1,
                    'min_range' => 1,
                ),
            )));
            $this->offset = ($this->page - 1)  * $this->limit;
            $this->start = $this->offset + 1;
            $this->end = min(($this->offset + $this->limit), $this->total);
            $this->prevlink = ($this->page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($this->page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
            $this->nextlink = ($this->page < $this->pages) ? '<a href="?page=' . ($this->page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $this->pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

            $this->stmt = $this->pdo->prepare('SELECT * FROM posts ORDER BY id DESC LIMIT :limit OFFSET :offset;');

            // Bind the query params
            $this->stmt->bindParam(':limit', $this->limit, PDO::PARAM_INT);
            $this->stmt->bindParam(':offset', $this->offset, PDO::PARAM_INT);
            $this->stmt->execute();
        }
        private function getTotal() : int
        {
            return $this->pdo->query('SELECT COUNT(*) FROM posts;')->fetchColumn();
        }
        public function getResults() : Iterator
        {
            if ($this->stmt->rowCount() > 0) {
                $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
                $iterator = new IteratorIterator($this->stmt);
    
                return $iterator;
            }
            return null;
        }
        public function show() : void
        {
            echo '<div id="paging"><p>', $this->prevlink, ' Page ', $this->page, ' of ', $this->pages, ' pages, displaying ', $this->start, '-', $this->end, ' of ', $this->total, ' results ', $this->nextlink, ' </p></div>';
        }
    }
?>