<?php
session_start();
require_once $dimport["auth/accept_auth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["security/csrf_prevent.php"]["path"];

if (!csrf_check($csrf_key))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=csrf-error");

if (empty($_POST["media-id"]))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-post");

require_once $dimport["db/db_funcs.php"]["path"];

$post = $records_get("post", "post_id", $_POST["media-id"]);
if (empty($post))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-post");
$post = $post[0];

if ($post["user_id"] != $_SESSION["u_id"])
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-post");

$records_delete("post", "post_id", $_POST["media-id"]);
redirect($dimport["home/home_page.phtml"]["redirect"]."&success=post-deleted");
