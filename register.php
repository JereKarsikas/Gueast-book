<?php
include "header.html";
?>

<?php

$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$userName = $_POST["userName"];
$userPass = $_POST["userPass"];

//sanitoidaan muuttujat filter var funktiolla

$firstsName = filter_var($firstName,FILTER_SANITIZE_STRING);
$lastsPass = filter_var($lastPass,FILTER_SANITIZE_STRING);
$usersName = filter_var($userName,FILTER_SANITIZE_STRING);
$usersPass = filter_var($userPass,FILTER_SANITIZE_STRING);


$servername = "localhost";
$username = "amkoodariryhma";
$password = "6V3hKahTw4gnXte1";
$dbname = "amkoodariryhma";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connect failed: " . $conn->connect_error);
}

//tassa vaan yksinkertaisesti lisataan kayttaja jarjestelmaan prepared statementilla.
echo "Connected successfully";

$stmt=$conn->prepare("INSERT INTO users (firstName, lastName, userName, userPass) values (?, ?, ?, ?)");
$stmt->bind_param("ssss", $firstName, $lastName, $userName, $userPass);
$stmt->execute();

$stmt->close();
$conn->close();
header('location: index.html');
?>

<? 
include "footer.html";
?>