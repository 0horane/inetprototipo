<?php
require_once('r_sqlinit.php');

if(!isset($_POST['table']) || !isset($_POST['id'])){
    header("HTTP/1.0 501 Internal Server Error");die();
}

$idcol=query("show columns from {$_POST['table']}")->fetch_array()[0];
if(isset($_POST['delete'])){
    query("DELETE FROM {$_POST['table']} WHERE $idcol = {$_POST['id']}");
    echo "true";die();
}

$rows=getRows("show columns from {$_POST['table']}");


$updatequery="UPDATE {$_POST['table']} SET ";
foreach ($rows as $row){
    $updatequery.="{$row['Field']}='{$_POST[$row['Field']]}', ";
}

$updatequery=substr($updatequery, 0,  strlen($updatequery)-2);
$updatequery.=" WHERE {$idcol}={$_POST['id']}";
query($updatequery);
echo "[\" $updatequery \"]";die();