<?php 
require_once('../../config.php');
require_login();
global $DB,$CFG,$OUTPUT;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $curriculam1 = $_POST["realnumber"];
    $curriculam2 = $_POST["polynomials"];
    $curriculam3 = $_POST["pair"];
    $curriculam4 = $_POST["quadraticequations"];
    $curriculam5 = $_POST["arithmeticprogressions"];
    $curriculam6 = $_POST["triangles"];
 }