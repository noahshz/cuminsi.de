<?php
    class Session {
        function __construct() {
            session_start();
        }
        public function set($vname, $value) {
            $_SESSION[$vname] = $value;
        }
        public function delete() {
            session_unset();
            session_destroy();
        }
        public function reset() {
            session_reset();
        }
    }

?>