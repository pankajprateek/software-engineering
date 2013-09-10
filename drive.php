<?php
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_DriveService.php';

$client = new Google_Client();
// Get your credentials from the APIs Console
$client->setClientId('1057221066577-tk81bpa02v8k1hbokp3sampjkgeh1ee2.apps.googleusercontent.com');
$client->setClientSecret('CX7WZ7uJOuSD2pYB68ZnkoGk');
$client->setRedirectUri('http://localhost/cs455/cs455/drive.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

$service = new Google_DriveService($client);

$authUrl = $client->createAuthUrl();

echo $authUrl."\n";

//Request authorization
//print "Please visit:\n$authUrl\n\n";
//print "Please enter the auth code:\n";

//echo $_GET['code']."get\n";
//echo $_POST['code']."post\n";
//$authCode = " ";	

if(isset($_GET['code'])) {

	$authCode = $_GET['code'];
}

echo $authCode;
// Exchange authorization code for access token
$accessToken = $client->authenticate($authCode);

echo "here";
$client->setAccessToken($accessToken);

//Insert a file
$file = new Google_DriveFile();
$file->setTitle('My document');
$file->setDescription('A test document');
$file->setMimeType('text/plain');

$data = file_get_contents('document.txt');

echo $data."\n";

$createdFile = $service->files->insert($file, array(
      'data' => $data,
      'mimeType' => 'text/plain',
    ));

print_r($createdFile);
?>