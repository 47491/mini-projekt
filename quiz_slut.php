<?php
session_start();

// Check if the 'score' variable is set in the session
if (isset($_SESSION['score'])) {
    $score = $_SESSION['score'];
} else {
    $score = 0;
}

// Check if the 'questionCounter' and 'totalQuestions' variables are set in the session
if (isset($_SESSION['questionCounter']) && isset($_SESSION['totalQuestions'])) {
    $questionCounter = $_SESSION['questionCounter'];
    $totalQuestions = $_SESSION['totalQuestions'];
} else {
    $questionCounter = 0;
    $totalQuestions = 0;
}

// Calculate the number of correct answers
$correctAnswers = $questionCounter - $score;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="quiz.css" />
    <title>Quiz Result</title>
    <style>
        .refresh-symbol {
            position: fixed;
            top: 60%;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }
        .refresh-symbol a {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }
        .refresh-symbol span {
            font-size: 150px;
        }
        .custom-button {
            background-color: #10B7FE;
            width: 400pt;
            height: 60pt;
            border: 3px solid black;
            color: black;
            text-align: center;
            text-decoration: none;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            cursor: pointer;
            border-radius: 10px;
            position: absolute;
            top: calc(60% - 80pt);
            left: 50%;
            transform: translateX(-50%);
        }
        .custom-button a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <h1><?php echo "Correct Answers: $correctAnswers / Total Questions: $totalQuestions"; ?></h1>

    <div class="custom-button">
        <a href="startsida.html">meny</a>
    </div>

    <div class="refresh-symbol">
        <a href="quiz.php">
            <span>&#10227;</span>
        </a>
    </div>

</body>
</html>
