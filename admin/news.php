<?php

require_once("auth.php");
require_once("../config/database.php");

$db = new Database();
$pdo = $db->connect();

require_once("news-save.php");

include("header.php");

require_once("news-form.php");

include("footer.php");