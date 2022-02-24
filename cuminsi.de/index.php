<?php
    require 'requirements.php';

    $session = new Session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="column left">
            <?php
                if($session->isset()) {
                    echo '<a href="createpost.php">Add Post</a>';
                }
            ?>
        </div>
        <div class="column center">
            <h1>Cuminsi.de</h1>
        </div>
        <div class="column right">
            <?php        
                if($session->isset()) {
                    echo "Eingeloggt als: " . $session->get('username');
                    //logout is available
                    echo '<a href="logout.php">Logout</a>';
                    //settings are available
                    echo '<a href="settings.php">Settings</a>';
                } else {
                    echo "ausgeloggt";
                    echo '<a href="signup.php">Registieren</a>';
                    echo '<a href="login.php">Login</a>';
                }
            ?>
        </div>
    </div>
    <div class="content">
        <p>Haupt Content</p>
        <?php
            $post = new Post($pdo);
            foreach($post->show(20) as $item){
                echo '<img width="150" src="' . $item['imgpath'] . '">';
                echo "<br>";
                echo '<a href="' . $item['link'] . '" target="blank">' . $item['title'] . '</a>';
                echo "<br><br><br>";
            }   
        ?>
    </div>
    <div class="footer">
        <p>Impressum in so</p>
    </div>
</body>
</html>