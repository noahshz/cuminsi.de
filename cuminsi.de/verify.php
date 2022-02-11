<?php
    require 'requirements.php';

    $session = new Session();

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
?>