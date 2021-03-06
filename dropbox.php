<?php

# Include the Dropbox SDK libraries
require_once "dropbox-sdk/Dropbox/autoload.php";
use \Dropbox as dbx;

function getWebAuth()
{
   $appInfo = dbx\AppInfo::loadFromJsonFile("dropbox_config.json");
   $clientIdentifier = "my-app/1.0";
   $redirectUri = "https://example.org/dropbox-auth-finish";
   $csrfTokenStore = new dbx\ArrayEntryStore($_SESSION, 'dropbox-auth-csrf-token');
   return new dbx\WebAuth($appInfo, $clientIdentifier, $redirectUri, $csrfTokenStore);
}


$appInfo = dbx\AppInfo::loadFromJsonFile("dropbox_config.json");

/*
	get the values for $getDropBoxAuthenticated, $authcode.
*/


//$getDropBoxAuthenticated = true;
//$webAuth = new dbx\WebAuthRedirect($appInfo, "localhost/software/dropbox.php");
$authorizeUrl = getWebAuth()->start();

header("Location: $authorizeUrl");


list($accessToken, $userId, $urlState) = getWebAuth()->finish($_GET);
assert($urlState === null);  // Since we didn't pass anything in start()

echo $accessToken.$userId;

/*if ($getDropBoxAuthenticated == true) {
	$authCode = "x22uT6fzw0wAAAAAAAAAAXMx2HOYRs9GtFZS2wAgN8A";	
} else {
	echo "1. Go to: " . "<a href = $authorizeUrl> $authorizeUrl </a>" . "\n";
	echo "2. Click \"Allow\" (you might have to log in first).\n";
	echo "3. Copy the authorization code.\n";
	$authCode = \trim(\readline("Enter the authorization code here: "));
}
list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
*/
print "Access Token: " . $accessToken . "\n";
print "";

$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
$accountInfo = $dbxClient->getAccountInfo();

print_r($accountInfo);

$f = fopen("working-draft.txt", "rb");
$result = $dbxClient->uploadFile("/working-draft.txt", dbx\WriteMode::add(), $f);
fclose($f);
print_r($result);

$folderMetadata = $dbxClient->getMetadataWithChildren("/");
print_r($folderMetadata);

$f = fopen("working-draft.txt", "w+b");
$fileMetadata = $dbxClient->getFile("/working-draft.txt", $f);
fclose($f);
print_r($fileMetadata);

?>