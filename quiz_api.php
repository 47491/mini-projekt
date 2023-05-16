<?php

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "quiz";

$db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

function skrivRandomRad()
{
    global $db;

    if (mysqli_connect_errno()) {
        echo "Anslutningen misslyckades <br>" . mysqli_error($db);
        exit();
    }

    $result = mysqli_query($db, "SELECT id, svar, bild FROM quiz_fragor ORDER BY RAND() LIMIT 1");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $svar = $row['svar'];
        $bild = $row['bild'];

        echo "<div id='bild-container'>";
        echo "<img id='bild' src='data:image/jpeg;base64," . base64_encode($bild) . "' alt='Bild'>";
        echo "</div>";

        $alternativ = array("alternativ_1", "alternativ_2", "alternativ_3", "alternativ_4");
        shuffle($alternativ);

        $usedAnswers = array($svar); // Array to store used answers

        foreach ($alternativ as $index => $altId) {
            if ($index == 0) {
                echo "<a class='alternativ' id='$altId' href='startsida.html'>$svar</a>";
            } else {
                $altResult = mysqli_query($db, "SELECT svar FROM quiz_fragor WHERE svar NOT IN ('" . implode("','", $usedAnswers) . "') ORDER BY RAND() LIMIT 1");
                $altRow = mysqli_fetch_assoc($altResult);
                $altSvar = $altRow['svar'];
                $usedAnswers[] = $altSvar; // Add the new answer to the usedAnswers array
                echo "<a class='alternativ' id='$altId' href='startsida.html'>$altSvar</a>";
            }
        }
    } else {
        echo "No results found.";
    }

    mysqli_close($db);
}

skrivRandomRad();
?>
