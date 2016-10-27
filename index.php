<?php
require_once 'classes/ConfigHelper.php';
require_once 'classes/DbConnection.php';
require_once 'classes/Vocabulary.php';

$vocabulary = new Vocabulary();
//TODO: delete this
echo "<pre>";
print_r(var_export($vocabulary->getVocabulary(), true));
echo "</pre>";