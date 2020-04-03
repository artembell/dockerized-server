<main>
    <h1>Labwork</h1>

<?php

use models\FormValidator;

$formValidator = new FormValidator(
    $inputNamesToValidate = ['path'],
    $root = $pathResolver->getRootPath()
);

function getOldValueByInputKey($inputKey): string
{
    return isset($_POST[$inputKey]) ? $_POST[$inputKey] : '';
}

$formValidator->validateInput($_POST);

?>

    <form method='POST' action='labwork'>
        <label for='path'>Enter absolute path to the folder (e.g. '/' or '/public/pages'):</label>
        <input id='path' name='path' type='text' 
            class="<?= $formValidator->getCorrectnessClass('path') ?>" 
            value="<?= getOldValueByInputKey('path') ?>"/>

        <div class='path-errors'>
            <?= $formValidator->getErrorsInfo('path') ?>
        </div>

        <input type='submit' value='Submit'/>
    </form>
<?php

function printFolderDataTable(array $data): void
{
    echo '<table>';
    echo '<tr>' . '<th>Name</th>' . '<th>Size (bytes)</th>' . '</tr>';
    foreach ($data['files'] as $file) {
        echo '<tr>' . '<td>' . $file['path'] . '</td>' . '<td>' . $file['size'] . '</td>' . '</tr>';
    }
    echo '<tr>' . '<td>' . 'Total file size:' . '</td>' . '<td>' . $data['totalFileSize'] . '</td>' . '</tr>';
    echo '</table>';
}

function filterFiles(string $name): bool
{
    return $name !== '.' && $name !== '..';
}

function getFolderSize(string $path): int
{
    $innerFiles = scandir($path);
    $totalFolderSize = 0;
    $filteredFiles = array_filter($innerFiles, 'filterFiles');

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
    $filteredFiles = array_filter($searchResults, 'filterFiles');

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

if ($formValidator->isCorrectInput('path')) {
    $specifiedPath = $_POST['path'];

    $folderAnalysis = analyzeFolder($specifiedPath, $pathResolver->getRootPath());
    printFolderDataTable($folderAnalysis);
}

?>
</main>
