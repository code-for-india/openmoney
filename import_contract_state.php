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


function UpdateDatabase($contractRow)
{


	$query = "update openContracts "
				. " set state = " 
				. " '" . $contractRow[1] ."' "
				. " where notice_no = "
				. " '" . $contractRow[0] . "'";



	$result = mysql_query($query)  or die(mysql_error());


}



	$contractFile = fopen("open_contracts_state.csv","r");
	while(! feof($contractFile))
  	{
  		$contractRow =  fgetcsv($contractFile);
  		UpdateDatabase($contractRow);
	
	}	

?>	