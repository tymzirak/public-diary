<?php
$pagin_amt = 25;
$pagin_page = 0;
if (!empty($_GET["step"]) && ctype_digit($_GET["step"]))
    $pagin_page = $_GET["step"];
$pagin_start = $pagin_amt*$pagin_page;

if (!empty($user)) $filters[] = "comment.user_id = ".$user['user_id'];

if (!empty($post)) $filters[] = "comment.post_id = ".$post['post_id'];

$order_by  = "comment_dt";
$table     = "comment";
if (!empty($_GET["sort"]) && $_GET["sort"] == "like") {
    $order_by  = "like_amt";
    $table     = "(SELECT comment.*, COUNT(comment_like.comment_id) AS like_amt
                  FROM comment JOIN comment_like
                  ON comment_like.comment_id = comment.comment_id
                  GROUP BY comment_like.comment_id) AS comment";
}

$filter_query = "";
if (!empty($filters)) $filter_query = "WHERE ".implode(" AND ", $filters);
