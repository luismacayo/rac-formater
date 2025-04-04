<?php

define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require __DIR__ .'/../vendor/autoload.php';

// configurar zona horaria
date_default_timezone_set('America/Lima');
// configuración de variables de entorno, etc.