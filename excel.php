<?php  
 //excel.php  
 header('Content-Type: application/vnd.ms-excel');  
 header('Content-disposition: attachment; filename=raport.xls');  
 echo $_GET['excel_data'];  
 ?>