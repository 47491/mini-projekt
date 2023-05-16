<?php

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";

$db = mysqli_connect($dbHost, $dbUser, $dbPassword, 'quiz');

function skrivRandomRad()
{
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPassword = "";

    $db = mysqli_connect($dbHost, $dbUser, $dbPassword, 'quiz');

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

        echo "<script>
                document.getElementById('bild').innerHTML = '<img src=\"data:image/jpeg;base64," . base64_encode($bild) . "\" alt=\"Bild\">';
                var randomAlternativ = Math.floor(Math.random() * 4) + 1;
                document.getElementById('alternativ_' + randomAlternativ).innerHTML = '$svar';
              </script>";
    } else {
        echo "No results found.";
    }

    mysqli_close($db);
}

skrivRandomRad();
?>
