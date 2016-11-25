<?php
require 'vendor/autoload.php';

use libs\helpers\ConfigHelper;
use libs\components\LinguaLeo;
use libs\models\Vocabulary;

$config = ConfigHelper::getConfig();

try {
    $linguaLeo = new LinguaLeo($config['lingualeo_user_email'], $config['lingualeo_user_password']);
    $words = (new Vocabulary())->getVocabulary();

    foreach ($words as $word) {
        $linguaLeo->addWord($word->stem,  $linguaLeo->getTopRatedTranslation($word->stem), $word->usage);
    }
} catch (\libs\components\LinguaLeoApiException $ex) {
    echo 'General error: ' . $ex->getMessage();

    if ($previous = $ex->getPrevious()) {
        echo "<br/>" . $previous;
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}