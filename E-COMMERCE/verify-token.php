<?php
session_start();
if (isset($_POST["csrf_token"]) && isset($_SESSION['csrf_token']) && $_SESSION["csrf_token"] == $_POST["csrf_token"]) {
    unset($_SESSION["csrf_token"]);
    echo "CSRF token is valid";
} else {
    echo "Invalid CSRF token";
}
?>

