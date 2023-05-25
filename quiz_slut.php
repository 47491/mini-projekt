<?php
session_start();

$totalQuestions = $_SESSION['totalQuestions'] ?? 0;
$correctAnswers = $_SESSION['correctAnswers'] ?? 0;

// Assuming you have an array $userAnswers that stores the user's selected answers

$userAnswers = $_SESSION['userAnswers'] ?? [];

// Assuming you have an array $correctOptions that stores the correct options for each question

$correctOptions = [
    1 => 2, // For question 1, the correct option is 2
    2 => 3, // For question 2, the correct option is 3
    // Add more entries for other questions
];

// Iterate through the user's answers and check if they are correct
foreach ($userAnswers as $questionNumber => $selectedOption) {
    if (isset($correctOptions[$questionNumber]) && $selectedOption == $correctOptions[$questionNumber]) {
        $correctAnswers++;
    }
}

// Store the updated correctAnswers count in the session
$_SESSION['correctAnswers'] = $correctAnswers;

// Reset the userAnswers array for the next quiz
unset($_SESSION['userAnswers']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="quiz.css" />
    <title>Document</title>
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