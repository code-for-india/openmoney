<?php

date_default_timezone_set('Asia/Calcutta');
  
function connectToDatabase() {

		$mysqlConnection = mysql_connect("localhost","root","");
		if (!$mysqlConnection)
		{
		  die('Could not connect: ' . mysql_error());
		}

		mysql_select_db("CFI", $mysqlConnection);
		echo  mysql_error();
}


connectToDatabase();


function insertIntoDatabase($contractRow)
{


	$query = "insert into openContracts ("
				. " project_id , " 
				. " project_title , " 
				. " notice_no ," 
				. " notice_description ,"
				. " notice_type ,"
				. " notice_status ,"
				. " procurement_method ," 
				. " submission_deadline ,"
				. " published_date ,"
				. " contact_organization ,"
				. " contact_name ," 
				. " contact_address ,"
				. " contact_city ," 
				. " contact_state ," 
				. " contact_phone ," 
				. " contact_email ," 
				. " contact_website "
				. " ) values ("
				. " '" . $contractRow[0] ."',"
				. " '" . $contractRow[1] . "',"
				. " '" . $contractRow[2] . "',"
				. " '" . $contractRow[3] . "',"
				. " '" . $contractRow[4] . "',"
				. " '" . $contractRow[5] . "',"
				. " '" . $contractRow[6] . "',"
				. " '" . date ('Y-m-d', strtotime($contractRow[7])) . "',"  
				. " '" . date ('Y-m-d', strtotime($contractRow[8])) . "'," 
				. " '" . $contractRow[9] . "',"
				. " '" . $contractRow[10] . "',"
				. " '" . $contractRow[11] . "',"
				. " '" . $contractRow[12] . "',"
				. " '" . $contractRow[13] . "',"
				. " '" . $contractRow[14] . "',"
				. " '" . $contractRow[15] . "',"
				. " '" . $contractRow[16] . "'"
			    . ")" ;

echo $query;
  //echo("DATE = " .  date ('Y-m-d', strtotime($contractRow[7])) . "  string = " );
  $result = mysql_query($query)  or die(mysql_error());


}



	$contractFile = fopen("open_contracts.csv","r");
	while(! feof($contractFile))
  	{
  		$contractRow =  fgetcsv($contractFile);
  		insertIntoDatabase($contractRow);
    //echo ("\n \"" . $contractRow[2] . "\", \"" . $contractRow[1] . "  " . $contractRow[3] . "\"" );
	}	

?>	