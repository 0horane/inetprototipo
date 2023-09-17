<?php 
require_once('r_sqlinit.php');


//gets tables and pages from $get
if(isset($_GET['table'])){
	$currenttable=$_GET['table'];
} else {
	$currenttable=db_initial_table;
}

$perpage=10;
if (isset($_GET['page'])){
	$page = $_GET['page'];
} else {$page = 0;}

//elejir tabla
$tables=getRows("SHOW TABLES");

$colarr=getRows("SHOW COLUMNS from $currenttable");


//genera query sql
$selectClause="SELECT * FROM $currenttable ";
$whereClause='';
$limitClause='';
$fieldsset=0;
if (isset($_GET["query"])){
	foreach( $colarr as $pforsearch){
		$whereClause.= $fieldsset ? "OR " : 'WHERE ';		
		$whereClause.="({$pforsearch['Field']} LIKE '%{$_GET['query']}%' ) "; 
		$fieldsset++;
	}
}

$limitClause.="limit " . $page*$perpage . ",". $perpage;
$cquery=$selectClause.$whereClause.$limitClause;


//paginador
$countEntriesQuery = "SELECT COUNT({$colarr[0]['Field']}) AS entriesCount FROM {$currenttable} {$whereClause}";
$pageCount=ceil(mysqli_fetch_assoc(query($countEntriesQuery))['entriesCount']/$perpage);


if ($page >=$pageCount-5){
	$startpage=$pageCount-10;
	$endpage=$pageCount;
	
} else if ($page>=5){
	$startpage=$page-5;
	$endpage=$page+5;

} else{
	$startpage=0;
	$endpage=10;
	
}
if ($startpage<0){
	$startpage=0;
}

$spchar=strpos($_SERVER['REQUEST_URI'],"?") ? '&' : '?';

$genericPageURL=$_SERVER['REQUEST_URI'].$spchar."page=";

$pageSymbols=[];
$pageNumbers=[];

if ($page!=0){
	$pageSymbols=["<<", "<"];
	$pageNumbers=[0, $page-1];
}
for ($i=$startpage;$i<$endpage;$i++){
	$pageSymbols[] = "$i";
	$pageNumbers[] = $i;

}
if ($page!=$pageCount-1){
	$pageSymbols=[...$pageSymbols, ">", ">>"];
	$pageNumbers=[...$pageNumbers, $page+1, $pageCount-1];
}




///////////// genera tabla
$tableRows=getRows($cquery);

$addlink=stripos("pacientes dispositivos especialidades llamadas medico usuarios visitas zonas", $currenttable)!==false;

$tienePermisosDeEditar=1;
include('views/i_menu.php');
require_once("views/index.php");

//genera agregar
