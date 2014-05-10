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

	$url = "https://maps.googleapis.com/maps/api/geocode/json" ;
	$data["key"] = "AIzaSyB_zurNDJjqlaodgahzU0IjUuzHmNqUeO0";
	$data["sensor"] = "false";
	$data["address"] =  " , Karnataka ";

	
$result = CallAPI("GET", $url, $data);
//print_r($result);  
	$resultData = json_decode($result,true);
//print_r($resultData);  
//print_r( $resultData);
echo   ($resultData['results'][0]['geometry']['location']['lat']);
echo   ($resultData['results'][0]['geometry']['location']['lng']);
?>
