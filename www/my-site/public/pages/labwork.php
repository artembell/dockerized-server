<main>
    <h1>Labwork</h1>
    <form action='/labwork' method='GET'>
        <label for='first'>First array</label>
        <input id='first' name='first-array' type='text'/>

        <label for='second'>Second array</label>
        <input id='second' name='second-array' type='text'/>

        <input type='submit' value='Save'/>
    </form>

<?php

    function convertStringToArray($str): array
    {
        $arrayString = preg_split('/\s+/', trim($str));
        return filter_var_array($arrayString, FILTER_VALIDATE_INT);
    }

    function mergeArrays($first, $second): array
    {
        foreach ($second as $value) {
            $first[] = $value;
        }
        return $first;
    }

    function getArrayOfEven($arr): array
    {
        function isEven($value): bool
        {
            return $value % 2 === 0;
        }

        return array_filter($arr, 'isEven');
    }

    function printArray($array): void
    {
        $array_str = '';
        foreach ($array as $value) {
            $array_str .= strval($value).' ';
        }
        echo $array_str;
    }

    if (isset($_GET['first-array']) && isset($_GET['second-array'])) {
        $first = $_GET['first-array'];
        $second = $_GET['second-array'];

        $first = convertStringToArray($first);
        $second = convertStringToArray($second);
        $merged = mergeArrays($first, $second);
        $filtered = getArrayOfEven($merged);

        echo '<h3>First: </h3>';
        printArray($first);

        echo '<h3>Second: </h3>';
        printArray($second);

        echo '<h3>Merged: </h3>';
        printArray($merged);

        echo '<h3>Filtered (even only): </h3>';
        printArray($filtered);
    }
?>
</main>
