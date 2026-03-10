<?php

require_once __DIR__ . '/vendor/autoload.php';
use App\Views\BaseTemplate;
$template = BaseTemplate::getTemplate();
$resultTemplate =  sprintf($template, "Основная страница", "Просто текст");
echo $resultTemplate;