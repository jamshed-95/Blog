<?php

use App\Core\App;

$configPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
$config = require $configPath;

return new App($config);
