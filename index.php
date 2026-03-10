<?php

require_once __DIR__ . '/vendor/autoload.php';
use App\BaseTemplate;

$template = BaseTemplate::getTemplate();
$resultTemplate =  sprintf($template, "Основная страница", "Просто текст");
echo $resultTemplate;