<?php
require_once $dimport["db/db_conn.php"]["path"];

$sql_query = function(string $query, array $temps=[]) use ($conn) : array {
	$sth = $conn->prepare($query);
	$sth->execute($temps);

	return (preg_match("/^SELECT\b/i", $query)) ? $sth->fetchAll() : [];
};

$record_add = function(string $table, array $attrs) use ($conn) : void {
	$columns_str = ""; $columns_temp = "";
	foreach ($attrs as $column => $value) {
		$columns_str .= ",$column"; $columns_temp .= ",?";
		$values[] = $value;
	}
	$columns_str   = trim($columns_str, ",");
	$columns_temp  = trim($columns_temp, ",");

	$sql = "INSERT INTO $table ($columns_str) VALUES ($columns_temp);";
	$sth = $conn->prepare($sql);
	$sth->execute($values);
};

$records_delete = function(string $table, string $id_column, string $id_value) use ($conn) : void {
	$sql = "DELETE FROM $table WHERE $id_column = ?;";
	$sth = $conn->prepare($sql);
	$sth->execute([$id_value]);
};

$records_edit = function(string $table, string $id_column, string $id_value, array $attrs) use ($conn) : void {
	$columns_temp = "";
	foreach ($attrs as $column => $value) {
		$columns_temp .= ",$column=?"; $values[] = $value;
	}
	$values[]      = $id_value;
	$columns_temp  = trim($columns_temp, ",");

	$sql = "UPDATE $table SET $columns_temp WHERE $id_column = ?;";
	$sth = $conn->prepare($sql);
	$sth->execute($values);
};

$records_get = function(string $table, string $id_column, string $id_value) use ($conn) : array {
	$sql = "SELECT * FROM $table WHERE $id_column = ?;";
	$sth = $conn->prepare($sql);
	$sth->execute([$id_value]);
	return $sth->fetchAll();
};
