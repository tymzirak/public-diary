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

if (!$password_validate($_POST["password"], $_POST["password-confirm"]) &&
    !$password_validate($_POST["password"], $user["password"], false))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=invalid-password");

$email_verify_send(
    $user,
    "localhost".$dimport["user/delete_user_verify.php"]["redirect"],
    "Account Delete Verification",
    ["session" => true]
);
redirect($dimport["home/settings_page.phtml"]["redirect"]."&success=verification-sent");
