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
?>

<?php
//ei kaytetty 
$chainID = (1 + $_SESSION['userID']);

?>

<div class="main-banner">

<div class="row" id="tekstikentta">
    <div class="col-md-12">

<?php
print "<form action=\"logout.php\" method=\"POST\">
                <button class=\"message-btn\" name =\"poista2\" type=\"submit\" value =\"ulos\">Ulos</button>
        </form>";
//printataan formi jonka avulla kayttajat voivat kirjoittaa asioita.
echo '<form class="form-floating" action="print.php" method="POST">
            <fieldset>
                <legend><h1>Uusi viesti</h1></legend>
                Mitä haluat jakaa: <br><textarea class="form-control" id="exampleFormControlTextarea1" name="newMessage" placeholder="Kirjoita viesti..." value=""></textarea><br>
                <input class="post-btn" type ="submit" value ="Post">
            </fieldset>
        </form>';
?>

</div>
</div>

</div>

<?php
//Tassa otetaan tuolta formilta kayttajan kirjoittama viesti vastana ja laitetaan se muuttujaan.
$newMessage = $_POST["newMessage"];

//Jos on viesti variaabelissa, haetaan lisataan se viesti tietokantaan ja palataan print.php-sivulle.
if (!empty($newMessage)){

$userID = $_SESSION['userID'];

echo $newMessage;
$stmt=$conn->prepare("INSERT INTO post (userID, postChainID, message) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $userName, $chainID, $newMessage,);
$stmt->execute();
$conn->close();
header('location: print.php');

}

//Jos viesti on tyhja, haetaan vain tietokannasta viesteja. Jokaisen viestin kohtalla haetaan myos sen "aliviestit".
else{
 
    $result=mysqli_query($conn, "SELECT * FROM post");

    while ($row=mysqli_fetch_object($result)){
        $identifyKey = $row->userID;
        print "<div class =\"postDiv\"> <b>$row->userID</b> kirjoittaa... <br>
        $row->message <br> Julkaistu:$row->date_entered<br>

        <form action=\"respond.php\" method=\"POST\">
            <button class=\"message-btn\" name =\"vastaa\" type=\"submit\" value =\"$row->postID\">Vastaa</button>";
        
            if ($identifyKey==$userName || $userName == "superuser"){
                print "<button class=\"message-btn\" name =\"poista\" type=\"submit\" value =\"$row->postID\">Poista</button>";
            }
        
            print "</form></div>";
        
        $numero = $row->postID;

        $result2=mysqli_query($conn, "SELECT * FROM subpost WHERE postID =$numero;");
        while ($row2=mysqli_fetch_object($result2)){
            $identifyKey2 = $row2->userID;
            print "<div class =\"subDiv\"> <b>$row2->userID</b> kirjoittaa... <br>
            $row2->message <br> Julkaistu:$row2->date_entered";
            
            if ($identifyKey2==$userName || $userName == "superuser"){
                print "<form action=\"edit.php\" method=\"POST\">
                <button class=\"nappula\" name =\"poistaEdit\" type=\"submit\" value =\"$row2->postID\">Poista</button>
                </form>";
            }
            print "</div><br>";
        }
    }
}
?>
      
<?php
include "footer.html";
?>