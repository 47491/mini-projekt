<?php
declare (strict_types=1);

// Funktion för att koppla till databasen
function connectDB(): PDO {
    static $db = null;

    if ($db === null) {
        // Koppla mot databasen
        $dsn = 'mysql:dbname=quiz;host=localhost';
        $dbUser = 'root';
        $dbPassword = "";
        $db = new PDO($dsn, $dbUser, $dbPassword);
    }

    return $db;
}

// Funktion för att skicka JSON
function skickaJSON(array $content): void {
    $json = json_encode($content, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
    echo $json;
    exit;
}
?>
