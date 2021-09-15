<?php
include "header.html";
session_start();
?>

<?php
//Tassa myohempaa kayttoa (katso alla) varten sailotaan mySQL-tietokantaan yhdistamiseen tarvittavia tietoja.
$servername = "localhost";
$username = "amkoodariryhma";
$password = "6V3hKahTw4gnXte1";
$dbname = "amkoodariryhma";

$userName = $_SESSION['userName'];

//Luodaan yhteys MySQL-tietokantaan yllamainituilla tiedoilla variaabeleita kayttaen. Laitetaan tuo yhteys conn-muuttujaan.
$conn = new mysqli($servername, $username, $password, $dbname);

//If-lause joka kertoo jos yhteys ei onnistunut.
if ($conn->connect_error){
    die("Connect failed: " . $conn->connect_error);
}
//Viesti joka kertoo kun yhteys onnistuu (enemmankin debugia varten, ei tarvitse olla lopullisessa ohjelmassa ehka mukana)
echo "Connected successfully";

$removeID = $_POST['poista1'];


//Jos removeID on olemassa, poistetaan paaviesti ja sen aliviestit samalla.
if (!empty($removeID)){
    $SQL = $conn->prepare("DELETE FROM post WHERE postID=?");
    $SQL->bind_param('i', $removeID);
    $SQL->execute();
    /********/
    $SQL = $conn->prepare("DELETE FROM subpost WHERE postID=?");
    $SQL->bind_param('i', $removeID);
    $SQL->execute();
    $conn->close();
    header('location: print.php');
}   
else{
    header('location: print.php');
}
?>

<?php
include "footer.html";
?>