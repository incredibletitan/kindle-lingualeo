<?php
require 'vendor/autoload.php';
$config = \libs\helpers\ConfigHelper::getConfig();

$ll = new \libs\components\LinguaLeo($config['lingualeo_api_url'], $config['lingualeo_user_email'], $config['lingualeo_user_password']);