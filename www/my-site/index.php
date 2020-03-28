<?php

use models\PathResolver;

require_once __DIR__ . '/bootstrap.php';
require_once 'models/PathResolver.php';

$serverRoot = $_SERVER["DOCUMENT_ROOT"];
$pathResolver = new PathResolver(
    $serverRoot,
    $serverPagesDir = "public/pages"
);

echo "<pre>";
echo "</pre>";

$targetPath = isset($_GET['urn']) ? $_GET['urn'] : PathResolver::$defaultPage;


echo $twig->render('index.html', [
    'links' => PathResolver::$paths,
    'contentPage' => $targetPath,
]);

$page = require_once($pathResolver->resolvePath($targetPath));
