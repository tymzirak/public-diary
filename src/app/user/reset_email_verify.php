<?php
session_start();
require_once $dimport["auth/accept_auth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["user/user_funcs.php"]["path"];

$user = $records_get("pd_user", "user_id", $_SESSION["u_id"]);
if (empty($user))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=invalid-user");
$user = $user[0];

if ($email_verify_get($user["user_id"]))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=unverified-user");

if (empty($_SESSION["u_new_email"]))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=invalid-verify");
$new_email = $_SESSION["u_new_email"];

if (!$email_validate($new_email))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=invalid-email");

if (!$email_verify_validate($_SESSION)) {
    $email_verify_send(
        $user,
        "localhost".$dimport["user/reset_email_verify.php"]["redirect"],
        "Email Change Verification",
        ["email" => $new_email, "session" => true]
    );
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=email-resent");
}

$records_edit(
    "pd_user",
    "user_id",
    $user["user_id"],
    ["email" => $new_email]
);
$_SESSION["u_email"] = $new_email;
redirect($dimport["home/settings_page.phtml"]["redirect"]."&success=verification-done");
