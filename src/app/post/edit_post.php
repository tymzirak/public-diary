<?php
session_start();
require_once $dimport["auth/accept_auth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["security/csrf_prevent.php"]["path"];

if (!csrf_check($csrf_key))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=csrf-error");

require_once $dimport["media/media_funcs.php"]["path"];

if ($media_within_time("post", "post_ts", $_SESSION["u_id"], $timeout))
    redirect($dimport["post/make_post_page.phtml"]["redirect"]."&error=frequent-post");

if (empty($_GET["post-id"]))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-post");

require_once $dimport["db/db_funcs.php"]["path"];

$post = $records_get("post", "post_id", $_GET["post-id"]);
if (empty($post))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-post");
$post = $post[0];

if ($post["user_id"] != $_SESSION["u_id"])
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-post");

require_once $dimport["security/xss_prevent.php"]["path"];

$title  = str_replace("\n", "~_", xss_prevent(trim($_POST["title"])));
$text   = str_replace("\n", "~_", xss_prevent(trim($_POST["text"])));

$post_id_uri = "&post-id=".$post["post_id"];
if (!(strlen($title) < 100) || !(strlen($text) < 10000))
    redirect($dimport["post/make_post_page.phtml"]["redirect"]."$post_id_uri&error=invalid-input");

$records_edit(
    "post",
    "post_id",
    $post["post_id"],
    ["title" => $title, "text" => $text]
);
redirect($dimport["post/post_page.phtml"]["redirect"]."$post_id_uri&success=post-edited");
