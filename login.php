<?php
include "header.html";
?>

<?php
//Otetaan login.html-sivun formista POST-methodilla tulevat arvot vastaan ja sailotaan variaabeleihin.
$userName = $_POST["userName"];
$userPass = $_POST["userPass"];

//sanitoidaan muuttujat filter var funktiolla
$usersname = filter_var($userName,FILTER_SANITIZE_STRING);
$usersPass = filter_var($userPass,FILTER_SANITIZE_STRING);

//Tassa myohempaa kayttoa (katso alla) varten sailotaan mySQL-tietokantaan yhdistamiseen tarvittavia tietoja.
$servername = "localhost";
$username = "amkoodariryhma";
$password = "6V3hKahTw4gnXte1";
$dbname = "amkoodariryhma";

//Luodaan yhteys MySQL-tietokantaan yllamainituilla tiedoilla variaabeleita kayttaen. Laitetaan tuo yhteys conn-muuttujaan.
$conn = new mysqli($servername, $username, $password, $dbname);

//If-lause joka kertoo jos yhteys ei onnistunut.
if ($conn->connect_error){
    die("Connect failed: " . $conn->connect_error);
}
//Viesti joka kertoo kun yhteys onnistuu (enemmankin debugia varten, ei tarvitse olla lopullisessa ohjelmassa ehka mukana)
//echo "Connected successfully";

//Prepared statementtia jolla kaydaan katsomassa tietokannasta, onko kayttajaa:
$query= $conn->prepare("SELECT userID FROM users WHERE userName=? AND userPass=?");
$query->bind_param('ss', $userName, $userPass);
$query->execute();
$result = $query->get_result();
$r = $result->fetch_array(MYSQLI_ASSOC);
$conn->close();

$userID = $r['userID'];

//Jos saamme palautukseksi positiivisen arvon, vahvistaa tama olemassa olevan salasana-kayttaja-yhdistelman ja lahetetaan kayttaja eteenpain.
if ($userID > 0){
    session_start();
    $_SESSION['userID'] = $r['userID'];
    $_SESSION['userName'] = $userName;
    header('location: print.php');
}
//Jos ei loydy olemassa olevaa paria, ohjataan kayttaja sivulle joka ilmoittaa asiasta.
else{
header('location: incorrect.html');
}
?>

<? 
include "footer.html";
?>