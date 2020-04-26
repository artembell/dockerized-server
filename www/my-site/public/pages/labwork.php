<main>
    <h1>Labwork</h1>

<?php

use models\TextFormatter;

function getOldValueByInputKey($inputKey): string
{
    return isset($_POST[$inputKey]) ? $_POST[$inputKey] : '';
}
?>
    <form action='labwork' method='POST'>
        <label for='user-text'>Text:</label>
        <textarea name='user-text'
            id='user-text'
            cols='30'
            rows='10'
        ><?= getOldValueByInputKey('user-text') ?></textarea>

        <input type='submit' value='Submit'/>
    </form>
<?php

$text = isset($_POST['user-text']) ? $_POST['user-text'] : '';
$formatter = new TextFormatter();

echo '<h2>Text</h2>';
echo $text;

echo '<h2>Formatted text</h2>';
echo $formatter->format($text);

?>
</main>
