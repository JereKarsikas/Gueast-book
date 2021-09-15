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

$postID = $_POST["vastaa"];
$removeID = $_POST["poista"];

?>

<div class="main-banner">
<div class="row" class="tekstikentta">
    <div class="col-md-12">

<?php

//Jos 
if(!empty($postID)){

print "<form class=\"form-floating\" action=\"respond.php\" method=\"POST\">
            <fieldset>
                <legend>Uusi viesti</legend>
                Mitä haluat jakaa: <br><textarea class=\"form-control\" id=\"exampleFormControlTextarea1\" name=\"newMessage\" placeholder=\"Kirjoita viesti...\" value=\"\"></textarea><br>
                <button class=\"message-btn\" name =\"vastaan\" type=\"submit\" value =\"$postID\">Vastaa</button>
            </fieldset>
        </form>";

}

if (!empty($removeID)) {
    print "<form action=\"remove.php\" method=\"POST\">
            <fieldset>
                <legend>Poistetaanko viesti?</legend>
                <button class=\"message-btn\" name =\"poista2\" type=\"submit\" value =\"peruuta\">Peruuta</button>
                <button class=\"message-btn\" name =\"poista1\" type=\"submit\" value =\"$removeID\">Poista</button>
            </fieldset>
        </form>";
}

?>

</div>
</div>
</div>

<?php

$newMessage = $_POST["newMessage"];
$userName = $_SESSION['userName'];
$postID = $_POST["vastaan"];

if (!empty($newMessage)){
    $stmt=$conn->prepare("INSERT INTO subpost (postID, userID, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $postID, $userName, $newMessage);
    $stmt->execute();
    $conn->close();
    header('location: print.php');
    } 
?>

<?php
include "footer.html";
?>