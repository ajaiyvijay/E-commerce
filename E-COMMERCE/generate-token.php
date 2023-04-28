<?php
function generate_token() {
    $token = bin2hex(random_bytes(32));
    $_SESSION["csrf_token"] = $token;
    return $token;
}
?>
