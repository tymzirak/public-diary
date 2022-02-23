<?php
function redirect(string $path) : void {
	header("Location: ".$path); exit();
}

function get(string $name, string $fallback="") : string {
	return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $fallback;
}

$get_error_page = function(string $title, string $error_img) use ($dimport) : void {
	require_once $dimport["error/error_page.phtml"]["path"]; exit();
};
