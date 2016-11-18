<?php
require 'vendor/autoload.php';

use libs\helpers\ConfigHelper;
use libs\components\LinguaLeo;
use libs\models\Vocabulary;
$config = ConfigHelper::getConfig();

try {

} catch (\libs\components\LinguaLeoApiException $ex) {
    echo 'General error: ' . $ex->getMessage();

    if ($previous = $ex->getPrevious()) {
        echo "<br/>" . $previous;
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}