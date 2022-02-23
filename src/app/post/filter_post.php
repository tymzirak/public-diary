<?php
$pagin_amt = 25;
$pagin_page = 0;
if (!empty($_GET["step"]) && ctype_digit($_GET["step"])) $pagin_page = $_GET["step"];
$pagin_start = $pagin_amt*$pagin_page;

if (!empty($user)) {
    include_once $dimport["profile/profile_menu.phtml"]["path"];
    $filters[] = "post.user_id = ".$user["user_id"];
}

$search = "";
if (!empty($_GET["search"])) {
    $search     = $_GET["search"];
    $filters[]  = "(title LIKE CONCAT('%',?,'%') OR text LIKE CONCAT('%',?,'%'))";
}

if (!empty($_GET["date"])) {
    $dates = [
        "today"  => 1,
        "week"   => 7,
        "month"  => 30,
        "year"   => 365
    ];
    if (isset($dates[$_GET["date"]]))
        $filters[] = "post_dt >= (CURDATE() - INTERVAL ".$dates[$_GET["date"]]." DAY)";
}

$filter_query = "";
if (!empty($filters)) $filter_query = "WHERE ".implode(" AND ", $filters);

$order_by  = "post_dt";
$table     = "post";
if (!empty($_GET["sort"])) {
    if ($_GET["sort"] == "like") {
        $order_by  = "like_amt";
        $table     = "(SELECT post.*, COUNT(post_like.post_id) AS like_amt
                      FROM post JOIN post_like
                      ON post_like.post_id = post.post_id
                      GROUP BY post_like.post_id) AS post";
    } else if ($_GET["sort"] == "comment") {
        $order_by  = "comment_amt";
        $table     = "(SELECT post.*, COUNT(comment.post_id) AS comment_amt
                      FROM post JOIN comment
                      ON comment.post_id = post.post_id
                      GROUP BY comment.post_id) AS post";
    }
}
