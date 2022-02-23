<?php require_once $dimport["db/db_funcs.php"]["path"];

$timeout = "3M";

$get_user_record = function(string $table, string $order_by, string $user_id) use ($sql_query) : array {
    $user_record_query = "SELECT * FROM $table
                          WHERE user_id = ?
                          ORDER BY $order_by DESC
                          LIMIT 0,1;";
    return $sql_query($user_record_query, [$user_id]);
};

$within_time = function(string $time, string $timeout) : bool {
    $date_time = new DateTime($time);
    $date_time->add(new DateInterval("PT".$timeout));
    return $date_time->format('Y-m-d H:i:s') > date("Y-m-d H:i:s");
};

$media_within_time = function(string $table, string $time_column, string $user_id, string $timeout) use ($get_user_record, $within_time) : bool {
    $user_record = $get_user_record($table, $time_column, $user_id);
    return empty($user_record) ? false : $within_time($user_record[0][$time_column], $timeout);
};
