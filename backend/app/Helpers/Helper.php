<?php 
function test(){
    return 'Sayem';
}


/** base64 image upload function */
function base64_to_image($base64_string, $location)
{
 
    $filename = time().rand(100,999).".jpg";
    $local_path  = $_SERVER['DOCUMENT_ROOT'];

    $path        = env('APP_PATH')."/public/" . $location . "/" . $filename;
    $output_file = $local_path . "/" . $path; //save to local address

    // open the output file for writing
    $ifp = fopen($output_file, 'wb');
    if($base64_string){
        $data = explode(',', $base64_string);

        print_r($data);
        exit();
        if(sizeof($data) > 1)
        {
            // we could add validation here with ensuring count( $data ) > 1
            fwrite($ifp, base64_decode($data[1]));
            // clean up the file resource
            fclose($ifp);
        }
        else
        {
            $filename = NULL;
        }
    }

    return $filename;
}


/** base64 image validate function */
function base64_to_image_validate($base64_string )
{
    return strlen(base64_decode($base64_string ));
    // $filename = time().rand(100,999).".jpg";
    // $local_path  = $_SERVER['DOCUMENT_ROOT']; 
    // $path        = env('APP_PATH')."/public/" . $location . "/" . $filename;
    // $output_file = $local_path . "/" . $path; //save to local address 
    // // open the output file for writing
    // $ifp = fopen($output_file, 'wb');
    // if($base64_string){
    //     $data = explode(',', $base64_string);
    //     if(sizeof($data) > 1)
    //     {
    //         // we could add validation here with ensuring count( $data ) > 1
    //         fwrite($ifp, base64_decode($data[1]));
    //         // clean up the file resource
    //         fclose($ifp);
    //     }
    //     else
    //     {
    //         $filename = NULL;
    //     }
    // }

    // return $filename;
}

 function checkBase64($data)
{
    if (base64_encode(base64_decode($data)) === $data) {
        return true;
    } else {
        return false;
    }
}

function onSignalVendor($title , $text , $user){
    
    $rest_api_key =  env('ELV_ONE_SIGNAL_REST_API_KEY') ; 
    $app_id  =  env('ELV_ONE_SIGNAL_APP_ID') ;  
    $content      = array(
        "en" => $text ,
    );
    $heading = array(
      "en" => $title  ,
  ); 
    $Additional_Data =  array('type' => 'order' ); 
    $fields = array(
        'app_id' => $app_id  , 
        'data' => $Additional_Data ,
        'contents' => $content, 
        'headings' => $heading,
        "large_icon" => " ", 
    );  
    //$fields['included_segments'] = array('All') ;
    $fields['include_player_ids'] = array($user) ; 
    $fields = json_encode($fields);  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic '.$rest_api_key,
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch); 
    curl_close($ch); 
    return  $data = json_decode($response, true); 
}

function onSignalUser($title , $text , $user){
    $rest_api_key =  env('EL_ONE_SIGNAL_REST_API_KEY') ; //   'YzhjM2E2NTUtYjUwOC00ZTFjLTk2MmEtOGNmOWY0YjUyZTli';
    $text = "Category : " ;
    $content      = array(
        "en" => $text ,
    );
    $heading = array(
      "en" => 'New Job' ,
  ); 
    $Additional_Data =  array('type' => 'order'  ); 
    $fields = array(
        'app_id' =>  env('EL_ONE_SIGNAL_APP_ID')  , 
        'data' => $Additional_Data ,
        'contents' => $content, 
        'headings' => $heading,
        "large_icon" => " ", 
    );  
    $fields['include_player_ids'] = array($user) ; 
    $fields = json_encode($fields);  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic '.$rest_api_key,
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch); 
    curl_close($ch); 
    return  $data = json_decode($response, true); 
}


function smsApiUrl(){
    return "https://gpcmp.grameenphone.com/ecmapigw/webresources/ecmapigw.v2";
}

function sendSms($phone, $smg)
{
    $url = smsApiUrl();
    $response = Http::withHeaders([
        'Content-Type' => 'application/json'
    ])->post($url, [
        'username' => "IRbulbadmin",
        'password' => "*Ssg@2023",
        'apicode' => "1",
        'msisdn' => $phone,
        'countrycode' => "880",
        'cli' => "S.S.G",
        'messagetype' => "3",
        'message' => $smg,
        'messageid' => "0"
    ]);
    // echo "Status code: " . $response->status() . "\n";
    // echo "Response body: " . $response->body() . "\n";
    // exit;
    if ($response->ok()) {
        echo 'SMS sent successfully.';
    } else {
        $a = 'Failed to send SMS. Error message: ' . $response->body();
        print_r($a);
        // exit;
    }
}

function automationUrl(){
    // return "https://ssforcenewdev.ssgbd.com/api/";
    return "https://ssforce.ssgbd.com/api/";
}