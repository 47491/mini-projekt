<?php

declare (strict_types=1);
session_start();
// Inkludera gemensamma funktioner
require_once "funktioner.php";

// Läs indata och sanera
// Kontrollera metod
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = ["meddelande" => ["Fel metod", "Sidan ska anropas med POST"]];
    skickaJSON($error, 405);
}

// Koppla mot databas
$db = connectDB();

// Läs indata
$svar = filter_input(INPUT_POST, 'svar', FILTER_SANITIZE_SPECIAL_CHARS);
$id = filter_input(INPUT_POST, 'ID');

// Spara data
$sql = "SELECT Svar, ID FROM quiz_fragor WHERE ID=:questionID";
$stmt = $db->prepare($sql);
$stmt->execute(['questionID' => $id]);

// Hämta resultatet
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Bearbeta den hämtade datan
if ($result) {
    $dbsvar = strtoupper($result['Svar']);
    // Utför ytterligare operationer med $svar eller annan hämtad data

    // Exempel: Omvandla $svar till stora bokstäver
    $svarratt = strtoupper($svar);

    // Exempel: Räkna antalet tecken i $svar
    $antalTecken = strlen($svar);

    // Exempel: Formatera utskrift med den bearbetade datan
    if ($dbsvar == $svarratt){ 
        $utskrift = 1;
       $ratt=$_SESSION['ratt'];
       $ratt++;
       $_SESSION['ratt']=$ratt;
    } else {
        $utskrift = 0;
    }

    // Skicka svar
    skickaJSON(['utskrift' => $utskrift]);
} else {
    // Hantera fallet när ingen data hittas för det angivna ID:et

    // Exempel: Skapa ett felmeddelande
    $felmeddelande = "Ingen data hittades för ID: $id";

    // ...

    // Skicka felmeddelande som svar
    skickaJSON(['felmeddelande' => $felmeddelande]);
}
?>
