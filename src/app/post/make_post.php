<?php
session_start();
require_once $dimport["auth/accept_auth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["security/csrf_prevent.php"]["path"];

if (!csrf_check($csrf_key))
    redirect($dimport["post/make_post_page.phtml"]["redirect"]."&error=csrf-error");

require_once $dimport["media/media_funcs.php"]["path"];

if ($media_within_time("post", "post_ts", $_SESSION["u_id"], $timeout))
    redirect($dimport["post/make_post_page.phtml"]["redirect"]."&error=frequent-post");

if (empty($_POST["title"]) || empty($_POST["text"]))
    redirect($dimport["post/make_post_page.phtml"]["redirect"]."&error=invalid-input");

require_once $dimport["security/xss_prevent.php"]["path"];

$title  = str_replace("\n", NEWLINER, xss_prevent(trim($_POST["title"])));
$text   = str_replace("\n", NEWLINER, xss_prevent(trim($_POST["text"])));

if (!(strlen($title) < 100) || !(strlen($text) < 10000))
    redirect($dimport["post/make_post_page.phtml"]["redirect"]."&error=invalid-input");

require_once $dimport["db/db_funcs.php"]["path"];

$record_add(
    "post",
    [
        "title"    => $title,
        "text"     => $text,
        "user_id"  => $_SESSION["u_id"],
        "post_dt"  => date("Y-m-d H:i:s")
    ]
);

$last_post_query = "SELECT * FROM post
                    WHERE user_id = ?
                    ORDER BY post_dt DESC
                    LIMIT 0,1;";
$last_post = $sql_query($last_post_query, [$_SESSION["u_id"]]);
$last_post = $last_post[0];

redirect($dimport["post/post_page.phtml"]["redirect"]."&post-id=".$last_post["post_id"]."&success=post-submitted");
