<?php
if (is_file(ROOT_PATH . '.env')) {
    $GLOBALS['ENV'] = parse_ini_file(ROOT_PATH . '.env', true);
}
