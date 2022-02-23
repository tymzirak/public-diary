<?php
session_start();
require_once $dimport["app/main_funcs.php"]["path"];

session_unset();
session_destroy();

redirect($dimport["auth/login_page.phtml"]["redirect"]);
