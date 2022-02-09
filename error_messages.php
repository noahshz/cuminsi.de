<?php
    switch($_POST['error_code']) {
        case '1001':
            echo "Benutzername bereits vorhanden";
            break;
        case '1002':
            echo "Email bereits vorhanden";
            break;
        case '1003':
            echo "Passwörter stimmen nicht überein";
            break;
    }
?>