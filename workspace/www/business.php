<?php
error_reporting(E_ERROR | E_PARSE);

header('Content-Type: application/json');

$sector = $_GET['sector'];
$notice_type = $_GET['notice_type'];
$state = $_GET['state'];
$description_matches = $_GET['description_matches'];
$deadline_start_date = $_GET['deadline_start_date'];
$deadline_end_date = $_GET['deadline_end_date'];


$con=mysqli_connect("localhost","root","","CFI");


	// Check connection
	if (mysqli_connect_errno()) {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}


function getDeadlineQuery ()
{

  global $deadline_start_date, $deadline_start_date;
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
    $query_part = $query_part . " and ( submission_deadline < '"
			          .  date('Y-m-d', strtotime($deadline_end_date))	
			          . " ) ";
	}

	$query_part = $query_part . " ) ";

  // echo "QUERY-PART" . $query_part;
	return $query_part;

}


function getStateQuery ()
{
  global $state;
	$query_part = "";

	if ( $state && strlen($state)>0) {

		$query_part = $query_part . " and (  state like "
		  				. " '%" . $state . "%' )";


	}

		

	return $query_part;

}



function getNoticeTypeQuery()
{

  global $notice_type;
	$query_part = "";

	if ($notice_type && strlen($notice_type))
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
  global $description_matches;
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


  return " order by submission_deadline desc ";
}

function getJSONData($result)
{

	$count = mysql_num_rows($result);
	$data = array();

	if ($count > 0 )
	{
    for ($i=0; $i<$count; $i++)
    {
      
      $row = mysql_fetch_array($result);
      $data[i]= array('notice_title'=> $row['notice_description'], 
                 'notice_title' => $row['notice_description'],
                 'notice_type' => $row['notice_type'],
                 'state' => $row['state'],
                 'deadline' => $row['submission_deadline']           
                );
      
      
     }
		
	}

	return json_encode($data);
}

function getResults()
{
  
  global $con;

	$query = " select * from openContracts where " 
         . getDeadlineQuery()
         . getStateQuery()
         . getNoticeTypeQuery()
         . getDescriptionQuery()
         . getSortParam();

  //echo "QUERY = "  .  $query;

  	$result = mysqli_query($con,$query);
  	$data = array();

    $i=0;
	while($row = mysqli_fetch_array($result)) {

		$row = mysqli_fetch_array($result);
      $data[$i]= array('notice_title'=> $row['notice_description'], 
                 'notice_title' => $row['notice_description'],
                 'notice_type' => $row['notice_type'],
                 'state' => $row['state'],
                 'deadline' => $row['submission_deadline']           
                );
      $i++;
  
	}

	  
 

	echo json_encode($data); 

}


	
	

	

getResults();


?>