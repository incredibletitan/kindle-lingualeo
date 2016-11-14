<?php
require 'vendor/autoload.php';
$config = \libs\helpers\ConfigHelper::getConfig();

try {
    $ll = new \libs\components\LinguaLeo($config['lingualeo_api_url'], $config['lingualeo_user_email'], $config['lingualeo_user_password']);
} catch (\libs\components\LinguaLeoApiException $ex) {
    echo 'General error: ' . $ex->getMessage();

    if ($previous = $ex->getPrevious()) {
        echo "<br/>" . $previous;
    }
}