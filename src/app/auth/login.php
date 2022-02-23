<?php
session_start();
require_once $dimport["auth/accept_unauth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["security/csrf_prevent.php"]["path"];

if (!csrf_check($csrf_key))
    redirect($dimport["auth/login_page.phtml"]["redirect"]."&error=csrf-error");

if (!isset($_POST["submit"]))
    redirect($dimport["auth/login_page.phtml"]["redirect"]."&error=submit-error");

require_once $dimport["user/user_funcs.php"]["path"];

if (!$username_validate($_POST["username"], false))
    redirect($dimport["auth/login_page.phtml"]["redirect"]."&error=invalid-username");
$user = $records_get("pd_user", "username", $_POST["username"])[0];

if ($email_verify_get($user["user_id"]))
    redirect($dimport["auth/login_page.phtml"]["redirect"]."&error=unverified-user");

if (!$password_validate($_POST["password"], $user["password"], false))
    redirect($dimport["auth/login_page.phtml"]["redirect"]."&error=invalid-password");

$_SESSION["csrf_key"]    = bin2hex(random_bytes(32));
$_SESSION["u_id"]        = $user["user_id"];
$_SESSION["u_name"]      = $user["username"];
$_SESSION["u_email"]     = $user["email"];
$_SESSION["u_password"]  = $user["password"];

redirect($dimport["home/home_page.phtml"]["redirect"]."&success=successful-login");
