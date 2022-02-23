<?php
session_start();
require_once $dimport["auth/accept_auth.php"]["path"];
require_once $dimport["user/user_funcs.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];

if ($email_verify_get($_SESSION["u_id"]))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=invalid-verify");

$user = $records_get("pd_user", "user_id", $_SESSION["u_id"]);
if (empty($user))
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=invalid-user");
$user = $user[0];

if (!$email_verify_validate($_SESSION)) {
    $email_verify_send(
        $user,
        "localhost".$dimport["user/delete_user_verify.php"]["redirect"],
        "Account Delete Verification",
        ["session" => true]
    );
    redirect($dimport["home/settings_page.phtml"]["redirect"]."&error=email-resent");
}

$records_delete("pd_user", "user_id", $user["user_id"]);
require_once $dimport["auth/logout.php"]["path"];
