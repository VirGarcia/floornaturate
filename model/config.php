<?php
    define("DB_CHARSET",    "charset=utf8");

    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define("DB_NAME",       "floornaturate");
        define("DB_HOST",       "localhost");
        define("DB_USER",       "root");
        define("DB_PASSWORD",   "");
    }
    else {
        define("DB_NAME",       "n260m_35643929_floornaturate");
        define("DB_HOST",       "sql105.260mb.net");
        define("DB_USER",       "n260m_35643929");
        define("DB_PASSWORD",   "123456");
    }

?>