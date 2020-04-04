<?php

use models\PathResolver;

require_once __DIR__ . '/bootstrap.php';
require_once 'models/PathResolver.php';
require_once 'models/TextFormatter.php';

$pathResolver = new PathResolver(
    $serverRoot = $_SERVER['DOCUMENT_ROOT'],
    $serverPagesDir = 'public/pages'
);

$targetPage = $_GET['urn'] ?? $pathResolver->$defaultPage;

echo $twig->render('index.html', [
    'links' => $pathResolver->getLinks()
]);

require_once($pathResolver->resolvePathTo($targetPage));
