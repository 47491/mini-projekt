
function adjustImageSize($imagePath, $containerWidth, $containerHeight)
    {
        list($imageWidth, $imageHeight) = getimagesize($imagePath);

        if ($imageWidth > $containerWidth || $imageHeight > $containerHeight) {
            $containerAspectRatio = $containerWidth / $containerHeight;
            $imageAspectRatio = $imageWidth / $imageHeight;

            if ($imageAspectRatio > $containerAspectRatio) {
                $width = '100%';
                $height = 'auto';
            } else {
                $width = 'auto';
                $height = '100%';
            }
        } else {
            $width = 'auto';
            $height = 'auto';
        }

        return array($width, $height);
    }


    function sparaSvar($answer, $id)
{
    global $dbHost, $dbUser, $dbPassword, $dbName;

    // Anslut till databasen
    $db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

    $query = "SELECT Svar FROM quiz_fragor WHERE ID = '$id'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $correctAnswer = $row['Svar'];

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




if ($question) {
    $response = array(
        'id' => $question['ID'],
        'bild' => $question['Bild']
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