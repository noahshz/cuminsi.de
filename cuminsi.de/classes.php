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
            session_unset();
            session_destroy();
        }
        function isset() {
            if(isset($_SESSION['uid'])) {
                return true;
            }
            return false;
        }
    }

    class User { //todo
        function __construct() {
            //construtctor
        }
        function create($username, $email, $password) {
            //create user
        }
        function getData($username) {
            //get user details
        }
    }
?>