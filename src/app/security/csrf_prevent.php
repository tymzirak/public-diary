<?php
if (empty($_SESSION["csrf_key"])) $_SESSION["csrf_key"] = bin2hex(random_bytes(32));

$csrf_key = hash_hmac("sha256", "message", $_SESSION["csrf_key"]);

$csrf_form_get = "<input type='hidden' name='csrf_key' value='".$csrf_key."'>";

function csrf_check(string $csrf_key) : bool {
    if (!isset($_POST["submit"]) && empty($_POST["csrf_key"])) return false;
    return hash_equals($csrf_key, $_POST["csrf_key"]) ? true : false;
}
