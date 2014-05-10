<?php
function getAddressesFromTags($geoTags)
{
	$addresses = array();

	foreach($geoTags as $state => $cityMap)
	{
		if ( sizeof($cityMap) < 1)		   
		{
			array_push($addresses, $state); 
		}
		else
		{
       
      if (sizeof($cityMap[0]) > 0 )
      {
          
        for ($i=0; $i < sizeof($cityMap); $i++)
        {
          array_push($addresses,  $cityMap[$i] .  " , " . $state ); 
         }
      }
      else
      {
          
        foreach ($cityMap as $city=>$locality)
        {
         
         for ($j=0; $j < sizeof($locality) ; $j++)
         {
          array_push($addresses, $locality[$j] . "," .  $city .  " , " . $state );  
         }
          
        }
      }
        
        
		}


		}
    
    return $addresses;
		
	}


function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    return curl_exec($curl);
}

function getAllLatLngs($geo_tags)
{
	$addresses = getAddressesFromTags($geo_tags);
  
  //echo ("\naddress obtained");
  //print_r($addresses);
	$latLngs = array();

	$url = "https://maps.googleapis.com/maps/api/geocode/json" ;
	$data["key"] = "AIzaSyB_zurNDJjqlaodgahzU0IjUuzHmNqUeO0";
	$data["sensor"] = "false";
	
	for ($i=0; $i < sizeof($addresses); $i++)
	{
		$data["address"] = $addresses[$i];
		$result = CallAPI("GET", $url, $data);

		$decodedResult = json_decode($result, true);
    // print_r($decodedResult);

		$lat = $decodedResult['results'][0]['geometry']['location']['lat'];
		$lng = $decodedResult['results'][0]['geometry']['location']['lng'];
		array_push($latLngs, $lat . "  , " . $lng );  
	}

	return $latLngs;
}


/**
 *  Function: Connect to database
 */
	function connectToDatabase() {

		$mysqlConnection = mysql_connect("localhost","root","");
		if (!$mysqlConnection)
		{
		  die('Could not connect: ' . mysql_error());
		}

		mysql_select_db("CFI", $mysqlConnection);
		echo  mysql_error();
	}

/**
 * Function : Tokenize Contract Metadata
 */
	function tokenize($contractMetadata)
    {


		$numberOfWords = preg_match_all('/([a-zA-Z]|\xC3[\x80-\x96\x98-\xB6\xB8-\xBF]|\xC5[\x92\x93\xA0\xA1\xB8\xBD\xBE]){4,}/', $contractMetadata, $unprocessedTokens);

		$tokens = array();
		for ($i=0; $i<$numberOfWords ; $i++ )
		{
			$tokens[$i] = $unprocessedTokens[0][$i];
		} 

		return $tokens;

	}

	
/**
 * Function : Get geo_tags
 */

	function getGeoTags ($token)
	{
		$geo_tags = array();

			for ($i = 0; $i < sizeof ($token); $i++ )
		{

			
			$query = "select locality,city, state from locality_city_state where UCASE(locality) like UCASE('"  . $token[$i] . "')";
			$result = mysql_query($query);
			
			$count = mysql_num_rows($result);
		
						
			for ($j=1; $j <= $count ; $j++)
			{
						
				$row = mysql_fetch_array($result);
				if (!array_key_exists($row['state'], $geo_tags))
				{
					$geo_tags[$row['state']]= array();
				}
				if (!array_key_exists($row['city'], $geo_tags))
				{
					$geo_tags[$row['state']][$row['city']]= array();
				}
	
				array_push($geo_tags[$row['state']][$row['city']], $row['locality']); 
			}


			$query = "select city, state from city_state where UCASE(city) like UCASE('"  . $token[$i] . "')";
			$result = mysql_query($query);
			
			$count = mysql_num_rows($result);
		
						
			for ($j=1; $j <= $count ; $j++)
			{
						
				$row = mysql_fetch_array($result);
				if (!array_key_exists($row['state'], $geo_tags))
				{
					$geo_tags[$row['state']]= array();
				}
				if (!array_key_exists($row['city'], $geo_tags))
				{
					array_push($geo_tags[$row['state']] , $row['city']); 
				}
	
				
			}




			$query = "select state from state where UCASE(state) like UCASE('"  . $token[$i] . "')";
			$result = mysql_query($query);
			
			$count = mysql_num_rows($result);
		
						
			for ($j=1; $j <= $count ; $j++)
			{
						
				$row = mysql_fetch_array($result);
				if (!array_key_exists($row['state'], $geo_tags))
				{
					array_push($geo_tags, $row['state']); 
				}
					
			}

		}

		return $geo_tags;


	}

/**
 * Function : Assign Geo Tags to contract metadata rows 
 */

	function assignGeoTagTo ($contractMetadata)
	{
		$tokens = tokenize($contractMetadata);
		
		$geo_tags = getGeoTags($tokens);

		return $geo_tags;
	}



	
	connectToDatabase();
	

	$contractFile = fopen("geocode_input","r");
	$total_contracts = 0;
	$tagged_contracts = 0;
	while(! feof($contractFile))
  	{
  		$total_contracts++;
		//echo ("\nCOUNT = " . $total_contracts );

	  $contractRow =  fgetcsv($contractFile);
	  $contractId = $contractRow[0];
	   $geo_tags = assignGeoTagTo ($contractRow[1]);		
	   	
	   if (sizeof($geo_tags) )
	   {
	   		$tagged_contracts++;
       // echo "\ntag = " . $tagged_contracts;
	   		$latLngs = getAllLatLngs($geo_tags);
			
			for ($j=0; $j< sizeof($latLngs) ; $j++)
			{
				echo ("\n" . $contractId . " , " . $latLngs[$j]);
			}
			
	   }
	  else
           {
      // echo "\ntotal = " . $total_contracts;
		//echo ("\n" . $contractRow[0] );
	    }	
	
	}	

	echo ("\n Total Contracts" . $total_contracts);
	echo ("\n tagged_contracts" . $tagged_contracts);
	



fclose($contractFile);
?>