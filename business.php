<?php

header('Content-Type: application/json');

$starting_record = $_GET['starting_record'];
$number_of_rows = $_GET['number_of_rows'];
$sort_on_closest_submission = $_GET ['sort_on_closest_submission'];
$location_latitude = $_GET ['location_latitude'];
$location_longitude = $_GET['location_longitude'];
$sector = $_GET['sector'];
$notice_type = $_GET['notice_type'];
$geo_tagged_only = $_GET['geo_tagged_only'];
$state = $_GET['state'];
$description_matches = $_GET['description_matches'];
$deadline_start_date = $_GET['deadline_start_date'];
$deadline_end_date = $_GET['deadline_end_date'];


function getDeadlineQuery ()
{

	if ( !$deadline_start_date) {

		$t=time();

		$deadline_start_date = date("Y-m-d",$t);
	}
	else
	{
		$deadline_start_date = date('Y-m-d', strtotime($deadline_start_date));
	}

	$query_part = " ( (submission_deadline > '" 
		           . $deadline_start_date 
		           . "' ) " ;

	if ($deadline_end_date)
	{
		$query_part += " and ( submission_deadline < '"
			          .  date('Y-m-d', strtotime($deadline_end_date));	
			          . " ) ";
	}

	$query_part += " ) ";

	return $query_part;

}


function getStateQuery ()
{
	$query_part = "";

	if ( $state && strlen($state)>0) {

		$query_part += " and (  state like "
		  				. " '%" . $state . "%' )";


	}

		

	return $query_part;

}



function getNoticeTypeQuery()
{

	$query_part = "";

	if ($notice_type && $strlen($notice_type))
	{
		switch ($notice_type) {
	    case "ifb":
	    	$notice_type = 	"Bids"  ;
	    	break;
	    case "rfefi":
	       	$notice_type = 	"Expression"  ;
	    	break;
	    case "gpn":
	    	$notice_type = "Procurement";
	    	break;
	    case "ifp":
	    	$notice_type = "Prequalification";
	    	break;
		}

		$query_part = " and  ( notice_type like '%" 
			          . $notice_type . "%' )";
	}

	return $query_part;

}

function getDescriptionQuery()
{
	$query_part = "";

	if ($description_matches && strlen(description_matches) > 0  )
	{
		$query_part = " and ( matches(notice_description) against "
					  .  $description_matches . " IN NATURAL LANGUAGE MODE )"; 
	}

	return $query_part;
}

function getSortParam()
{


	" order by submission_deadline desc "
}

function getJSONData($result)
{

	$count = mysql_num_rows($result);
	$data = array();

	if ($count > 0 )
	{
		$row = mysql_fetch_array($result);
		$resultRow['notice_title'] = row['notice_description'];
		$resultRow['notice_type'] = row['notice_type'];
		$resultRow['state'] = row['state'];
		$resultRow['deadline'] = row['submission_deadline'];
		array_push($data, $resultRow);
	}

	return json_encode($data);
}

function getResults()
{

	$query = " select * from openContracts where " 
         . getDeadlineQuery()
         . getStateQuery()
         . getNoticeTypeQuery()
         . getDescriptionQuery()
         . getSortParam();


	$result = mysql_query($query);

	echo getJSONData($result);

}


getResults();


?>