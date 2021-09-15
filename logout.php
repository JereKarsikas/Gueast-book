<?php
include "header.html";
session_start();
?>

<?php
header('location: index.html');
session_destroy();
?>

<?php
include "footer.html";
?>