<main>
    <h1>Labwork</h1>

    <form action="labwork" method="post">
        <label for="limit">Limit:</label>
        <input id="limit" name="limit" type="number">
        <button>Submit</button>
    </form>

<?php

$mysqli = new mysqli('localhost', 'admin', 'rootroot', 'labwork5');

if ($mysqli->connect_errno) {
    echo "Error (code: "
    . $mysqli->connect_errno . ") while establishing the connection with db: "
    . $mysqli->connect_error . "\n";
} else {
    if ($mysqli->set_charset("utf8")) {
        function query(string $query, object $mysqli): void {
            if ($result = $mysqli->query($query)) {
                echo '<br>';
                while ($country = $result->fetch_assoc()) {
                    echo $country['name'] . '<br>';
                }
            } else {
                echo "Error: " . $mysqli->error . "\n";
            }
        }

        function queryAll(object $mysqli): void
        {
            $queryAll = "SELECT * FROM countries";
            query($queryAll, $mysqli);
        }

        function queryRandomByLimit(int $limit, object $mysqli): void
        {
            $queryRandom = "SELECT * FROM countries ORDER BY rand() LIMIT $limit";
            query($queryRandom, $mysqli);
        }

        queryAll($mysqli);
        if (isset($_POST['limit']) && $_POST['limit'] > 0) {
            queryRandomByLimit(intval($_POST['limit']), $mysqli);
        }
    } else {
        echo "Error: " . $mysqli->error . "\n";
    }

    $mysqli->close();
}
?>
</main>
