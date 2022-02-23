<?php
session_start();
require_once $dimport["auth/accept_auth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["security/csrf_prevent.php"]["path"];

if (!csrf_check($csrf_key))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=csrf-error");

require_once $dimport["user/user_funcs.php"]["path"];

$user = $records_get("pd_user", "user_id", $_SESSION["u_id"]);
if (empty($user))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=invalid-user");
$user = $user[0];

if ($email_verify_get($user["user_id"]))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=unverified-user");

if ($email_validate($_POST["email"]) && $_SESSION["u_email"] != $_POST["email"]) {
    $email_verify_send(
        $user,
        "localhost".$dimport["user/reset_email_verify.php"]["redirect"],
        "Email Change Verification",
        ["email" => $_POST["email"], "session" => true]
    );
    $_SESSION["u_new_email"] = $_POST["email"];
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&success=verification-sent");
}

if ($username_validate($_POST["username"]) && $_SESSION["u_name"] != $_POST["username"]) {
    $user_edits["username"]  = $_POST["username"];
    $_SESSION["u_name"]      = $_POST["username"];
}

if ($password_validate($_POST["password-new"], $_POST["password-confirm"]) &&
    $password_validate($_POST["password-current"])) {
    $password_hashed         = password_hash($_POST["password-new"], PASSWORD_DEFAULT);
    $user_edits["password"]  = $password_hashed;
    $_SESSION["u_password"]  = $password_hashed;
}

if (empty($user_edits))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=invalid-info");

$records_edit(
    "pd_user",
    "user_id",
    $user["user_id"],
    $user_edits
);
redirect($dimport["home/settings_page.phtml"]["redirect"]."&success=edited-user");
