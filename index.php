<?php
require 'vendor/autoload.php';
$config = \libs\helpers\ConfigHelper::getConfig();

try {
    $ll = new \libs\components\LinguaLeo($config['lingualeo_api_url'], $config['lingualeo_user_email'], $config['lingualeo_user_password']);
    $words = (new \libs\models\Vocabulary())->getVocabulary();
//    $ll->addWord('dog', $ll->getTopRatedTranslation('dog'),"what a nice dog");

//    foreach ($words as $word) {
//    }

} catch (\libs\components\LinguaLeoApiException $ex) {
    echo 'General error: ' . $ex->getMessage();

    if ($previous = $ex->getPrevious()) {
        echo "<br/>" . $previous;
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}