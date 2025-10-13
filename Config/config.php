<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//define is for declaring the constant ("name of the constant",value)
define('JWT_SECRET_KEY',"W!eA2sD9fGhJkLmNpQrT3uVxYz@#45CvBn");
define('ACCESS_TOKEN',3600);
define('REFRESH_TOKEN_EXP',604800);

?>