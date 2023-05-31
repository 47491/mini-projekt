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
    $questionCounter = isset($_SESSION['questionCounter']) ? $_SESSION['questionCounter'] : 0;

    // Återställ frågelistan om var 11:e fråga har visats
    if ($questionCounter % 11 === 1) {
        $prevQuestionIds = array();
    }

    // Anslut till databasen (endast om anslutningen är stängd)
    if (!mysqli_ping($db)) {
        mysqli_close($db); // Close the current connection
        $db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName); // Reconnect to the database
    }

    // Hämta en slumpmässig fråga som inte har visats tidigare
    //$result = mysqli_query($db, "SELECT ID, Svar, Bild FROM quiz_fragor WHERE ID NOT IN ('" . implode("','", $prevQuestionIds) . "') ORDER BY RAND() LIMIT 1");
    
    // Check if $prevQuestionIds is empty
if (empty($prevQuestionIds)) {
    $result = mysqli_query($db, "SELECT ID, Svar, Bild FROM quiz_fragor ORDER BY RAND() LIMIT 1");
} else {
    // Prepare the statement
    $stmt = mysqli_prepare($db, "SELECT ID, Svar, Bild FROM quiz_fragor WHERE ID NOT IN (" . str_repeat('?,', count($prevQuestionIds) - 1) . "?) ORDER BY RAND() LIMIT 1");

    // Bind the IDs as parameters
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($prevQuestionIds)), ...$prevQuestionIds);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
}
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Spara frågans id i sessionen
        $prevQuestionIds[] = $row['ID'];
        $_SESSION['prevQuestionIds'] = $prevQuestionIds;

       

    $maxQuestionCount = getMaxQuestionCount();

    if ($questionCounter >= $maxQuestionCount) {
        $_SESSION['questionCounter'] = 0; 
        header("Location: quiz_slut.php");
        exit;
    }
        mysqli_close($db);

        return $row;
    }

    mysqli_close($db); 

    return null;
}

$question = skrivRandomRad();
//var_dump($question);



function getMaxQuestionCount()
{
    global $dbHost, $dbUser, $dbPassword, $dbName;

    // Connect to the database
    $db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

    // Execute the SQL query to get the maximum question count
    $result = mysqli_query($db, "SELECT COUNT(*) AS maxQuestionCount FROM quiz_fragor");

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $maxQuestionCount = $row['maxQuestionCount'];

        mysqli_free_result($result);
        mysqli_close($db);

        return $maxQuestionCount;
    }

    mysqli_close($db);

    return null;
}


$maxQuestions = getMaxQuestionCount();
echo "Maximum Questions: " . $maxQuestions;









?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="quiz.css">
    <script src="quiz.js"></script>
    <title>Quiz</title>

</head>
<body>
    
<div id="quiz-container">
    <?php
    if ($question) {
        // Display the question text
        echo '<div id="fraga-container">';
        echo '<p id="fraga">' . $question['Svar'] . '</p>';
        echo '</div>';

        // Display the image
        $imageData = base64_encode($question['Bild']);
        echo '<div id="bild-container">';
        echo '<img id="bild" data-qId="'. $question['ID'].'" src="data:image/jpeg;base64,' . $imageData . '" alt="Frågebild" />';
        echo '</div>';

        // Display the answer options
        echo '<div id="alternativ">';
        // Display the correct answer
        echo '<button id="'.$question['ID'].'" class="alternativ">' . $question['Svar'] . '</button>';

        // Display three additional random answer options
        $db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
        $query = "SELECT Svar, ID FROM quiz_fragor WHERE ID != '{$question['ID']}' ORDER BY RAND() LIMIT 3";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<button id="'.$row['ID'].'" class="alternativ">' . $row['Svar'] . '</button>';
        }
        mysqli_close($db);

        echo '</div>';
    }
    ?>
</div>


    <script>
        

        
    </script>
</body>
</html>
