<?php
    define("DB_CHARSET",    "charset=utf8");

    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define("DB_NAME",       "");
        define("DB_HOST",       "localhost");
        define("DB_USER",       "root");
        define("DB_PASSWORD",   "");
    }
    else {
        define("DB_NAME",       "");
        define("DB_HOST",       "");
        define("DB_USER",       "");
        define("DB_PASSWORD",   "");
    }

?>
