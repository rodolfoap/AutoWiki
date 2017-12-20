<?php
require_once("config.php");
require_once("c/parser.php");
require_once("c/functs.php");

if(isset($_GET['page'])) wpage($_GET['page']); else wpage($_homepage);
