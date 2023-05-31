<?php
session_start();

$ratt = isset($_SESSION['ratt']) ? $_SESSION['ratt'] : 0;
$questionCounter = isset($_SESSION['questionCounter']) ? $_SESSION['questionCounter'] : 0;


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

        #ratt-counter {
            font-size: 24px;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <h1>Slut</h1>

    <div id="ratt-counter">rätt svar <?php echo $ratt; ?> av <?php echo $questionCounter ?></div>

    <div class="refresh-symbol">
        <a href="quiz.php">
            <span>&#10227;</span>
        </a>
    </div>

</body>
</html>
<?php

$_SESSION['questionCounter']=0;
$_SESSION['ratt']=0;

