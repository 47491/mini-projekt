<?php
$dbHost = "localhost"; // Värd för databasen
$dbUser = "root"; // Användarnamn för att ansluta till databasen
$dbPassword = ""; // Lösenord för att ansluta till databasen
$dbName = "quiz"; // Namnet på databasen

session_start(); // Starta sessionshantering

function skrivRandomRad()
{
    global $dbHost, $dbUser, $dbPassword, $dbName;

    // Anslut till databasen
    $db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

    $prevQuestionIds = isset($_SESSION['prevQuestionIds']) ? $_SESSION['prevQuestionIds'] : array();
 

    // Anslut till databasen (endast om anslutningen är stängd)
    if (!mysqli_ping($db)) {
        mysqli_close($db); // Close the current connection
        $db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName); // Reconnect to the database
    }

    // Hämta en slumpmässig fråga som inte har visats tidigare
    $result = mysqli_query($db, "SELECT id, svar, bild FROM quiz_fragor WHERE id NOT IN ('" . implode("','", $prevQuestionIds) . "') ORDER BY RAND() LIMIT 1");

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Spara frågans id i sessionen
        $prevQuestionIds[] = $row['id'];
        $_SESSION['prevQuestionIds'] = $prevQuestionIds;

        // Räkna upp frågetäljaren


        mysqli_close($db); // Close the database connection

        return $row;
    }

    mysqli_close($db); // Close the database connection

    return null;
}

function sparaSvar($answer, $id)
{
    global $dbHost, $dbUser, $dbPassword, $dbName;

    // Anslut till databasen
    $db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

    $query = "SELECT svar FROM quiz_fragor WHERE id = '$id'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $correctAnswer = $row['svar'];

        $isCorrect = $answer === $correctAnswer ? 1 : 0;

        // Spara svaret i databasen
        $insertQuery = "INSERT INTO quiz_svar (fraga_id, svar, korrekt) VALUES ('$id', '$answer', '$isCorrect')";
        mysqli_query($db, $insertQuery);

        mysqli_close($db); // Close the database connection

        return $isCorrect;
    }

    mysqli_close($db); // Close the database connection

    return 0;
}

$question = skrivRandomRad();

if ($question) {
    $response = array(
        'id' => $question['id'],
        'bild' => $question['bild']
    );

    echo json_encode($response);
} else {
    echo json_encode(null);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['answer']) && isset($data['ID'])) {
        $answer = $data['answer'];
        $id = $data['ID'];

        $isCorrect = sparaSvar($answer, $id);

        $response = array(
            'correct' => $isCorrect
        );

        echo json_encode($response);
    }
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
        #quiz-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #bild-container {
            margin-bottom: 20px;
        }

        #alternativ {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .alternativ {
            margin: 5px;
        }
    </style>
</head>
<body>
    
    <div id="quiz-container">
        <?php
        
        if ($question) {
            // Display the image
            $imageData = base64_encode($question['bild']);
            echo '<div id="bild-container">';
            echo '<img id="bild" src="data:image/jpeg;base64,' . $imageData . '" alt="Frågebild" />';
            echo '</div>';
        
            // Display the answer options
            echo '<div id="alternativ">';
            // Display the correct answer
            echo '<button id="alternativ_1" class="alternativ correct">' . $question['svar'] . '</button>';
        
            // Display three additional random answer options
            $db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
            $query = "SELECT svar FROM quiz_fragor WHERE id != '{$question['id']}' ORDER BY RAND() LIMIT 3";
            $result = mysqli_query($db, $query);
            $counter = 2;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<button id="alternativ_'.$counter.'" class="alternativ">' . $row['svar'] . '</button>';
                $counter++;
            }
            mysqli_close($db);
            echo '</div>';
        }
        
        ?>
        
    </div>

    <script>
        // Hämta svarsknapparna
        const answerButtons = document.querySelectorAll('#alternativ .alternativ');

        // Lägg till händelselyssnare på svarsknapparna
        answerButtons.forEach(button => {
            button.addEventListener('click', function() {
                const selectedButton = this;

                // Markera rätt svar genom att tilldela CSS-klassen "correct"
                const correctButton = document.querySelector('#alternativ .correct');
                correctButton.classList.add('correct');

                // Markera felaktiga svar genom att tilldela CSS-klassen "wrong"
                const wrongButtons = document.querySelectorAll('#alternativ .alternativ:not(.correct)');
                wrongButtons.forEach(button => button.classList.add('wrong'));

                // Markera det valda svaret
                if (selectedButton.classList.contains('correct')) {
                    selectedButton.classList.add('selected-correct');
                } else {
                    selectedButton.classList.add('selected-wrong');
                }

                // Inaktivera svarsknapparna
                answerButtons.forEach(button => {
                    button.disabled = true;
                });

                // Ladda om sidan efter 1,5 sekunder
                setTimeout(() => {
                    location.reload();
                }, 1500);
            });
        });
    </script>
</body>
</html>
