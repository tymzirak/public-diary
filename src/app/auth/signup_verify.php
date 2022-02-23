<?php
session_start();
require_once $dimport["auth/accept_unauth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["user/user_funcs.php"]["path"];

if (!$email_verify = $email_verify_get($_SESSION["token_u_id"]))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-verify");

$user = $records_get("pd_user", "user_id", $email_verify["user_id"]);
if (empty($user))
    redirect($dimport["auth/login_page.phtml"]["redirect"]."&error=invalid-user");
$user = $user[0];

if (!$email_verify_validate($email_verify)) {
    $email_verify_send(
        $user,
        "localhost".$dimport["auth/signup_verify.php"]["redirect"],
        "Account Verification"
    );
    redirect($dimport["auth/login_page.phtml"]["redirect"]."&error=verification-resent");
}

session_unset();
session_destroy();
$records_delete("email_verify", "user_id", $user["user_id"]);
redirect($dimport["auth/login_page.phtml"]["redirect"]."&success=verification-done");
