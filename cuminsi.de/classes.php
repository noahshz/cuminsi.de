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
?>