<?php
require_once $dimport["db/db_funcs.php"]["path"];

$username_validate = function(string $username, bool $signup=true) use ($records_get) : bool {
    if (empty($username)) return false;
    if (!preg_match("/^[a-zA-Z0-9_-]{3,20}$/", $username)) return false;
    $user = $records_get("pd_user", "username", $username);
    if ($signup && count($user) != 0) return false;
    else if (!$signup && count($user) != 1) return false;
    return true;
};

$password_validate = function(string $password, string $passwd_confirm="", bool $signup=true) : bool {
    if (empty($password)) return false;
    if (strlen($password) < 8) return false;
    if (!empty($password_confirm)) {
        if ($signup && $password != $password_confirm) return false;
        else if (!$signup && !password_verify($password, $password_confirm)) return false;
    } return true;
};

$email_validate = function(string $email, bool $signup=true) use ($records_get) : bool {
    if (empty($email)) return false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    $user = $records_get("pd_user", "email", $email);
    if ($signup && count($user) != 0) return false;
    else if (!$signup && count($user) != 1) return false;
    return true;
};

$email_verify_send = function(array $user, string $url, string $subject, array $opt=[]) use ($record_add, $records_delete) : void {
    $records_delete("email_verify", "user_id", $user["user_id"]);
    $_SESSION["email_token"] = hash_hmac("sha256", "message", bin2hex(random_bytes(32)));
    $timeout = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")." + 10 minutes"));
    if (empty($opt["session"]))
        $record_add(
            "email_verify",
            [
                "user_id"         => $user["user_id"],
                "email_token"     => $_SESSION["email_token"],
                "email_token_dt"  => $timeout
            ]
        );
    else $_SESSION["email_token_dt"] = $timeout;
    $message  = "Your Verification Link: ".$url."&email-token=".$_SESSION["email_token"];
    $email    = empty($opt["email"]) ? $user["email"] : $opt["email"];
    mail($email, "Public Diary | $subject", $message);
};

$email_verify_get = function(string $user_id) use ($records_get) : array {
    if (empty($user_id)) return [];
    $email_verify = $records_get("email_verify", "user_id", $user_id);
    return empty($email_verify) ? [] : $email_verify[0];
};

$email_verify_validate = function(array $email_verify) : bool {
    if (empty($_SESSION["email_token"]) && empty($_GET["email-token"])) return false;
    if ($email_verify["email_token_dt"] < date("Y-m-d H:i:s")) return false;
    if (!hash_equals($email_verify["email_token"], $_GET["email-token"])) return false;
    return true;
};
