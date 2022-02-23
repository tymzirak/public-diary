<?php
session_start();
require_once $dimport["auth/accept_auth.php"]["path"];
require_once $dimport["app/main_funcs.php"]["path"];
require_once $dimport["security/csrf_prevent.php"]["path"];

if (!csrf_check($csrf_key))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=csrf-error");

if (empty($_POST["media-id"]))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-comment");

require_once $dimport["db/db_funcs.php"]["path"];

$comment = $records_get("comment", "comment_id", $_POST["media-id"]);
if (empty($comment))
    redirect($dimport["home/home_page.phtml"]["redirect"]."&error=invalid-comment");
$comment = $comment[0];

$query_like = "SELECT * FROM comment_like
               WHERE comment_id = ? AND user_id = ?;";
$like = $sql_query($query_like, [$comment["comment_id"], $_SESSION["u_id"]]);

if (empty($like)) {
    $record_add(
        "comment_like",
        ["comment_id" => $comment["comment_id"], "user_id" => $_SESSION["u_id"]]
    );
    redirect($dimport["home/home_page.phtml"]["redirect"]."&success=comment-liked");
}

$query_unlike = "DELETE FROM comment_like
                 WHERE comment_id = ? AND user_id = ?;";
$sql_query($query_unlike, [$comment["comment_id"], $_SESSION["u_id"]]);

redirect($dimport["home/home_page.phtml"]["redirect"]."&success=comment-unliked");
