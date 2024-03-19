<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class GetZipCode{
    public $zip = '';
    public function __construct(){
        $this->formApifunction();
    }
    public function formApifunction(){
        $zipcode = $_REQUEST['zipcode'];    
        $data = ['codes'=>$zipcode];
        $url = "https://app.zipcodebase.com/api/v1/search?";
        $apiKey = "7504e340-c0d8-11ed-b321-2b7d343eb3eb";
        if($apiKey){
            $content = $url.http_build_query($data);   
        }
        $response = $this->getAddressApi($content,$apiKey);

        print_R($response);
    }
    public function getAddressApi($url,$key){
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);


curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
    "apikey: $key",  
));

$response = curl_exec($ch);
curl_close($ch);
$res=json_decode($response,true);
//print_R($res['results']);
echo json_encode($res['results']);   
    }


}
$cl = new GetZipCode();

?>