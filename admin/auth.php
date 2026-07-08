<?php

require_once("../config/config.php");

// Entwicklung:
// Login-Schutz vorübergehend deaktiviert.

// Später wieder aktivieren:
//
// if (!isset($_SESSION["user_id"])) {
//     header("Location: login.php");
//     exit;
// }