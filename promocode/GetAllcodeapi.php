<?php
require_once "../../config.php";
global $DB;
global $CFG;


$Allpromocode = $DB->get_records_sql("SELECT * from {promocode}");

// Convert array of objects to JSON
$json_data = json_encode(array_values($Allpromocode));

// Output JSON
echo $json_data;

?>