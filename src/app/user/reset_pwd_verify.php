<?php
session_start();
require_once $dimport["auth/accept_unauth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["user/user_funcs.php"]["path"];

if ($email_verify_get($_SESSION["token_u_id"]))
    redirect($dimport["auth/reset_pwd_page.phtml"]["redirect"]."&error=unverified-user");

if (empty($_SESSION["token_u_id"]))
    redirect($dimport["auth/reset_pwd_page.phtml"]["redirect"]."&error=invalid-user");

$user = $records_get("pd_user", "user_id", $_SESSION["token_u_id"]);
if (empty($user))
    redirect($dimport["auth/reset_pwd_page.phtml"]["redirect"]."&error=invalid-user");
$user = $user[0];

if (!$password_validate($_SESSION["u_new_password"]))
    redirect($dimport["auth/reset_pwd_page.phtml"]["redirect"]."&error=invalid-password");
$passwd_hashed = password_hash($_SESSION["u_new_password"], PASSWORD_DEFAULT);

if (!$email_verify_validate($_SESSION)) {
    $email_verify_send(
        $user,
        "localhost".$dimport["user/reset_pwd_verify.php"]["redirect"],
        "Password Reset Verification",
        ["session" => true]
    );
    redirect($dimport["auth/reset_pwd_page.phtml"]["redirect"]."&error=verification-resent");
}

$records_edit(
    "pd_user",
    "user_id",
    $user["user_id"],
    ["password" => $passwd_hashed]
);

session_unset();
session_destroy();
redirect($dimport["auth/login_page.phtml"]["redirect"]."&success=verification-done");
