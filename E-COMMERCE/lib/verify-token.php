<?php
function verify_token($token) {
    if (isset($_SESSION["csrf_token"]) && $token == $_SESSION["csrf_token"]) {
        unset($_SESSION["csrf_token"]);
        return true;
    } else {
        return false;
    }
}
?>
