<main>
    <h1>Labwork</h1>

<?php

use models\FormValidator;

$formValidator = new FormValidator(
    ['path'],
    $root = $pathResolver->getRootPath()
);

function getOldValueByInputKey($inputName): string
{
    return isset($_POST[$inputName]) ? $_POST[$inputName] : '';
}

$formValidator->validateInput($_POST);

?>

    <form method='POST' action='labwork'>
        <label for='path'>Enter absolute path to the folder (e.g. '/' or '/public/pages'):</label>
        <input id='path'
            name='path'
            type='text' 
            class="<?= $formValidator->getCorrectnessClass('path') ?>" 
            value="<?= getOldValueByInputKey('path') ?>"
            autofocus/>

        <div class='path-errors'>
            <?= $formValidator->getErrorsInfo('path') ?>
        </div>

        <input type='submit' value='Submit'/>
    </form>
<?php

function printFolderDataTable(array $data, object $twig): void
{
    echo $twig->render('folder_table.html', ['data' => $data]);
}

function getFolderSize(string $path): int
{
    $innerFiles = scandir($path);
    $totalFolderSize = 0;
    $filteredFiles = array_filter($innerFiles, function (string $name): bool {
        return $name !== '.' && $name !== '..';
    });

    foreach ($filteredFiles as $filename) {
        $newPath = $path.'/'.$filename;
        $totalFileSize += is_dir($filename) ? getFolderSize($newPath) : filesize($newPath);
    }

    return $totalFileSize;
}

function analyzeFolder(string $absolutePath, string $rootPath): array
{
    $fullTargetPath = $rootPath.$absolutePath;
    $searchResults = scandir($fullTargetPath);
    $filteredFiles = array_filter($searchResults, function (string $name): bool {
        return $name !== '.' && $name !== '..';
    });

    $files = [];
    $totalFileSize = 0;

    foreach ($filteredFiles as $filename) {
        $isDir = is_dir($filename);

        $displayPath = $isDir ? $filename . '/' : $filename;
        $size = $isDir ? getFolderSize($fullTargetPath.$filename) : filesize($fullTargetPath.'/'.$filename);
        $totalFileSize += $size;

        array_push($files, ['path' => $displayPath, 'size' => $size]);
    }

    return [
        'files' => $files,
        'totalFileSize' => $totalFileSize,
    ];
}

if ($formValidator->formHasCorrectInput()) {
    $specifiedPath = $_POST['path'];

    $folderAnalysis = analyzeFolder($specifiedPath, $pathResolver->getRootPath());
    printFolderDataTable($folderAnalysis, $twig);
}

?>
</main>
