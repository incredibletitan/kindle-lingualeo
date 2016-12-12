<?php
require 'vendor/autoload.php';

use libs\helpers\ConfigHelper;
use libs\components\LinguaLeo;
use libs\models\Vocabulary;

$config = ConfigHelper::getConfig();

try {
    $linguaLeo = new LinguaLeo($config['lingualeo_user_email'], $config['lingualeo_user_password']);
    $vocabularyObject = new Vocabulary();
    $words = $vocabularyObject->getVocabulary();

    if (count($words) > 0) {
        foreach ($words as $word) {
            if ($linguaLeo->addWord($word->stem, $linguaLeo->getTopRatedTranslation($word->stem), $word->usage)) {
                echo  "Word \"{$word->stem}\" added successfully<br/>";
                $vocabularyObject->markAsImported($word->id);
            }
        }
    } else {
        echo "No words for import found";
    }

} catch (\libs\components\LinguaLeoApiException $ex) {
    echo 'General error: ' . $ex->getMessage();

    if ($previous = $ex->getPrevious()) {
        echo "<br/>" . $previous;
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}