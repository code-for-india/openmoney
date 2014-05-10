<?php


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


	$query = "insert into openContractsGeoTags ("
				. " notice_no ," 
				. " lat ," 
				. " lng "
				. " ) values ("
				. " '" . $contractRow[0] ."',"
				. " '" . $contractRow[1] . "',"
				. " '" . $contractRow[2] . "'"
			  . ")" ;



	$result = mysql_query($query)  or die(mysql_error());


}



	$contractFile = fopen("open_contracts_geotags.csv","r");
	while(! feof($contractFile))
  	{
  		$contractRow =  fgetcsv($contractFile);
  		insertIntoDatabase($contractRow);
	
	}	

?>	