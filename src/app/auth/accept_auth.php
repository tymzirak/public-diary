<?php
if (empty($_SESSION["u_id"])) {
	require_once $dimport["app/main_funcs.php"]["path"];
	redirect($dimport["auth/logout.php"]["redirect"]);
}
