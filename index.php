<?php
require 'vendor/autoload.php';

use libs\helpers\ConfigHelper;
use libs\components\LinguaLeo;
use libs\models\Vocabulary;
use libs\components\Logger;

$config = ConfigHelper::getConfig();
$logger = new Logger(__DIR__ . '/logs/test.log');
$logger->insert("Sync has started");

try {
    $linguaLeo = new LinguaLeo($config['lingualeo_user_email'], $config['lingualeo_user_password']);
    $vocabularyObject = new Vocabulary();
    $words = $vocabularyObject->getVocabulary();

    if (count($words) > 0) {
        foreach ($words as $word) {
            if ($linguaLeo->addWord($word->stem, $linguaLeo->getTopRatedTranslation($word->stem), $word->usage)) {
                $logger->insert("Word \"{$word->stem}\" added successfully");
                $vocabularyObject->markAsImported($word->id);
            }
        }
    } else {
        $logger->insert("No words for import found");
    }

} catch (\libs\components\LinguaLeoApiException $ex) {
    $logger->insert('General error: ' . $ex->getMessage());
    if ($previous = $ex->getPrevious()) {
        $logger->insert($previous);
    }
} catch (Exception $ex) {
    $logger->insert($ex->getMessage());
}