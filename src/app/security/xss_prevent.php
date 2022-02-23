<?php
function xss_prevent(string $input) : string {
    return htmlspecialchars($input, ENT_QUOTES, "UTF-8");
}
