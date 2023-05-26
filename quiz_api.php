<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "quiz";

$db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

session_start();

if (!function_exists('skrivRandomRad')) {
    function skrivRandomRad()
    {
        global $db;

        if (mysqli_connect_errno()) {
            echo "Anslutningen misslyckades <br>" . mysqli_error($db);
            exit();
        }

        $prevPictureIds = isset($_SESSION['prevPictureIds']) ? $_SESSION['prevPictureIds'] : array();
        $questionCounter = isset($_SESSION['questionCounter']) ? $_SESSION['questionCounter'] : 0;

        if ($questionCounter % 11 === 1) {
            $prevPictureIds = array();
        }

        $result = mysqli_query($db, "SELECT id, svar, bild FROM quiz_fragor WHERE id NOT IN ('" . implode("','", $prevPictureIds) . "') ORDER BY RAND() LIMIT 1");

        if (!$result || mysqli_num_rows($result) == 0) {
            echo "No more available pictures.";
            mysqli_close($db);
            return;
        }

        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $svar = $row['svar'];
        $bild = $row['bild'];

        $prevPictureIds[] = $id;
        if (count($prevPictureIds) > 10) {
            array_shift($prevPictureIds);
        }

        $_SESSION['prevPictureIds'] = $prevPictureIds;

        echo "<div id='bild-container'>";
        echo "<img id='bild' src='data:image/jpeg;base64," . base64_encode($bild) . "' alt='Bild'>";
        echo "</div>";

        $alternativ = array($svar);

        while (count($alternativ) < 4) {
            $altResult = mysqli_query($db, "SELECT svar FROM quiz_fragor WHERE svar != '$svar' ORDER BY RAND() LIMIT 1");
            $altRow = mysqli_fetch_assoc($altResult);
            $altSvar = $altRow['svar'];

            if (!in_array($altSvar, $alternativ)) {
                $alternativ[] = $altSvar;
            }
        }

        shuffle($alternativ);

        $correctAnswerId = "";

        foreach ($alternativ as $index => $altSvar) {
            $altId = "alternativ_" . ($index + 1);

            if ($index == 0) {
                $correctAnswerId = $altId;
            }

            echo "<a class='alternativ' id='$altId' data-correct-answer='$svar'>$altSvar</a>";
        }

        $_SESSION['questionCounter']++;

        if ($_SESSION['questionCounter'] > $_SESSION['totalQuestions']) {
            $_SESSION['questionCounter'] = $_SESSION['totalQuestions'];
        }

        mysqli_close($db);
    }
}

$result = mysqli_query($db, "SELECT COUNT(*) AS total FROM quiz_fragor");
$row = mysqli_fetch_assoc($result);
$_SESSION['totalQuestions'] = $row['total'];

if (!isset($_SESSION['questionCounter']) || isset($_POST['answer'])) {
    $_SESSION['questionCounter'] = 1;
    $_SESSION['wrongAnswers'] = 0;
} else {
    $_SESSION['questionCounter']++;
}

if (isset($_POST['answer'])) {
    $selectedAnswer = $_POST['answer'];
    $correctAnswer = $_POST['correctAnswer'];

    if ($selectedAnswer !== $correctAnswer) {
        $_SESSION['wrongAnswers']++;
    }
}

skrivRandomRad();
?>
