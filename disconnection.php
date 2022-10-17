<?php
session_start(); // INITIALISE LA SESSION
session_unset(); // DESACTIVE LA SESSION
session_destroy(); // DETRUIT LA SESSION
setcookie('log', '', time()-3444, '/', null, false, true); // DESTRUCTION COOKIE

header('location: index.php');