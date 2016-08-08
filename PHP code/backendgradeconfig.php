<?php
header('Access-Control-Allow-Origin: *');
include 'database_config.php';

extract($_POST);
//extract the paramters from the form submitted in front end , here all the parameters in single quotores are the names of the ranges given in html form.
$ARangestart=$_GET['Arangestart'];
$ARangeend=$_GET['Arangeend'];
$BRangestart=$_GET['Brangestart'];
$BRangeend=$_GET['Brangeend'];
$CRangestart=$_GET['Crangestart'];
$CRangeend=$_GET['Crangeend'];
$DRangestart=$_GET['Drangestart'];
$DRangeend=$_GET['Drangeend'];
$FRangestart=$_GET['Frangestart'];
$FRangeend=$_GET['Frangeend'];
$ID = $_GET['ID'];


$Errors = array();
if($ARangeend < $ARangestart)
{
	array_push( $Errors, "A end range must be higher than start range");
}
if($BRangeend < $BRangestart)
{
	array_push( $Errors, "B end range must be higher than start range");
}
if($CRangeend < $CRangestart)
{
	array_push( $Errors, "C end range must be higher than start range");
}
if($DRangeend < $DRangestart)
{
	array_push( $Errors, "D end range must be higher than start range");
}
if($FRangeend < $FRangestart)
{
	array_push( $Errors, "F end range must be higher than start range");
}

if($Brangeend !=($ARangestart-1))
{
	array_push( $Errors, "Range overlap for A and B grades");
}

if($Crangeend !=$BRangestart-1)
{
	array_push( $Errors, "Range overlap for B and C grades");
}
if($Drangeend !=$CRangestart-1)
{
	array_push( $Errors, "Range overlap for C and D grades");
}
if($Frangeend !=$DRangestart-1)
{
	array_push( $Errors, "Range overlap for D and F grades");}

if(count($Errors))
{
  echo json_encode($Errors);
}
else
{	
   
}


?>