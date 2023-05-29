<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "quiz";

$db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

session_start();

function skrivRandomRad()
{
    global $db;

    $prevQuestionIds = isset($_SESSION['prevQuestionIds']) ? $_SESSION['prevQuestionIds'] : array(); 
    $questionCounter = isset($_SESSION['questionCounter']) ? $_SESSION['questionCounter'] : 0;

    if ($questionCounter % 11 === 1) {
        $prevQuestionIds = array();
    }

    $result = mysqli_query($db, "SELECT id, svar, bild FROM quiz_fragor WHERE id NOT IN ('" . implode("','", $prevQuestionIds) . "') ORDER BY RAND() LIMIT 1"); // Get a random question that has not been shown before

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "Inga fler tillgängliga bilder.";
        return;
    }

    $row = mysqli_fetch_assoc($result);
    $id = $row['id'];
    $svar = $row['svar'];
    $bild = $row['bild'];

    $prevQuestionIds[] = $id;
    if (count($prevQuestionIds) > 10) {
        array_shift($prevQuestionIds);
    }

    $_SESSION['prevQuestionIds'] = $prevQuestionIds;

    $imagePath = 'data:image/jpeg;base64,' . base64_encode($bild);
    //list($width, $height) = adjustImageSize($imagePath, 100, 100);

    echo "<div id='bild-container'>";
    echo "<img id='bild' src='$imagePath' alt='Bild' style='width: 100px; height: 100px;'>";
    echo "</div>";

    $_SESSION['correctAnswer'] = $svar;

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

    echo "<form id='answer-form' action='quiz_api.php' method='POST'>";
    foreach ($alternativ as $index => $altSvar) {
        $altId = "alternativ_" . ($index + 1);
        $buttonClass = ($altSvar == $_SESSION['correctAnswer']) ? 'correct' : 'wrong';

        echo "<button type='submit' class='alternativ $buttonClass' id='$altId' name='answer' value='$altSvar'>$altSvar</button>";
    }
    echo "</form>";

    $_SESSION['questionCounter']++;

    if ($_SESSION['questionCounter'] > $_SESSION['totalQuestions']) {
        $_SESSION['questionCounter'] = $_SESSION['totalQuestions'];
    }
}

$result = mysqli_query($db, "SELECT COUNT(*) AS total FROM quiz_fragor");
$row = mysqli_fetch_assoc($result);
$_SESSION['totalQuestions'] = $row['total'];

if (!isset($_SESSION['questionCounter']) || isset($_POST['answer'])) {
    $_SESSION['questionCounter'] = 1;
} else {
    $_SESSION['questionCounter']++;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="quiz.css" />
    <title>Quiz</title>
    <style>
        .correct {
            background-color: green;
            color: white;
        }

        .wrong {
            background-color: red;
            color: white;
        }

        .selected-correct {
            background-color: green;
            color: white;
        }

        .selected-wrong {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <a id="meny" href="startsida.html">meny</a>
    <div id="quiz-container">
        <?php
        skrivRandomRad();
        ?>
    </div>
    <p id="fragorna"><?php echo $_SESSION['questionCounter'] . "/" . $_SESSION['totalQuestions']; ?></p>
    <script>
        // Get the answer buttons
        const answerButtons = document.querySelectorAll('.alternativ');

        // Add event listener to each button
        answerButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Check if the selected answer is correct
                const isCorrect = this.classList.contains('correct');

                // Add CSS classes based on the correctness
                this.classList.add(isCorrect ? 'selected-correct' : 'selected-wrong');
                this.disabled = true;

                // Refresh the page after 1.5 seconds
                setTimeout(() => {
                    location.reload();
                }, 1500);
            });
        });
    </script>
</body>
</html>
