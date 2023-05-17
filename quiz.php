<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="quiz.css" />
    <script src="quiz.js"></script>
    <title>Quiz</title>
</head>
<body>
    <a id="meny" href="startsida.html">meny</a>
    <div id="quiz-container">
        <?php include 'quiz_api.php'; ?>
    </div>
    <p id="fragorna"><?php echo $_SESSION['questionCounter'] . "/20"; ?></p>
</body>
</html>
