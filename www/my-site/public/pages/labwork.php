<main>
    <h1>Labwork</h1>

    <h3>Create cookie</h3>
    <form action="labwork" method="post">
        <label for="name">Name:</label>
        <input id="name" name="name" type="text">

        <label for="value">Value:</label>
        <input id="value" name="value" type="text">

        <label for="age">Age:</label>
        <input id="age" name="age" type="number">

        <button name="save">Save</button>
    </form>
<?php

function trimValue(string $value): string
{
    return preg_replace('/[=,;\t\r\n\013\014\s]/', '', $value);
}

function saveCookie(string $name, string $value, int $age): void
{
    setcookie(trimValue($name), trimValue($value), time() + $age);
}

function deleteCookie(string $name)
{
    setcookie($name, '', 0);
}

if (isset($_POST['save'])) {
    $cookieName = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $cookieValue = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $cookieAge = isset($_POST['age']) ? htmlspecialchars($_POST['age']) : 0;

    if ($cookieName !== '' && $cookieValue !== '' && $cookieAge !== 0) {
        saveCookie($cookieName, $cookieValue, $cookieAge);
    }
} elseif (isset($_POST['delete'])) {
    $cookieName = isset($_POST['delete']) ? htmlspecialchars($_POST['delete']) : '';
    deleteCookie($cookieName);
}

echo $twig->render('cookie_list.html', [
    'cookies' => $_COOKIE,
    'predefined' => ['_identity', 'PHPSESSID', '_csrf'],
]);
?>
</main>
