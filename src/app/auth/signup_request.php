<?php
session_start();
require_once $dimport["auth/accept_unauth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["security/csrf_prevent.php"]["path"];

if (!csrf_check($csrf_key))
    redirect($dimport["auth/signup_page.phtml"]["redirect"]."&error=csrf-error");

if (!isset($_POST["submit"]))
    redirect($dimport["auth/signup_page.phtml"]["redirect"]."&error=submit-error");

require_once $dimport["user/user_funcs.php"]["path"];

if (!$username_validate($_POST["username"]))
    redirect($dimport["auth/signup_page.phtml"]["redirect"]."&error=invalid-username");
$username = $_POST["username"];

if (!$email_validate($_POST["email"]))
    redirect($dimport["auth/signup_page.phtml"]["redirect"]."&error=invalid-email");
$email = $_POST["email"];

if (!$password_validate($_POST["password"], $_POST["password-confirm"]))
    redirect($dimport["auth/signup_page.phtml"]["redirect"]."&error=invalid-password");
$password_hashed = password_hash($_POST["password"], PASSWORD_DEFAULT);

$record_add(
    "pd_user",
    [
        "username"  => $username,
        "email"     => $email,
        "password"  => $password_hashed,
        "user_dt"   => date("Y-m-d H:i:s")
    ]
);

$user = $records_get("pd_user", "username", $username);
if (empty($user))
    redirect($dimport["auth/signup_page.phtml"]["redirect"]."&error=invalid-signup");
$user = $user[0];

$_SESSION["token_u_id"] = $user["user_id"];
$email_verify_send(
    $user,
    "localhost".$dimport["auth/signup_verify.php"]["redirect"],
    "Account Verification"
);
redirect($dimport["auth/login_page.phtml"]["redirect"]."&success=verification-sent");
