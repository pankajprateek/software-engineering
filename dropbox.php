<?php

# Include the Dropbox SDK libraries
require_once "dropbox-sdk/Dropbox/autoload.php";
use \Dropbox as dbx;

$appInfo = dbx\AppInfo::loadFromJsonFile("dropbox_config.json");

/*
	get the values for $getDropBoxAuthenticated, $authcode.
*/


$getDropBoxAuthenticated = true;
$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");
$authorizeUrl = $webAuth->start();

if ($getDropBoxAuthenticated == true) {
	$authCode = "LwvBdcKwZ0MAAAAAAAAAAaRijabv5UFKpl172-LpQHk";	
} else {
	echo "1. Go to: " . "<a href = $authorizeUrl> $authorizeUrl </a>" . "\n";
	echo "2. Click \"Allow\" (you might have to log in first).\n";
	echo "3. Copy the authorization code.\n";
	$authCode = \trim(\readline("Enter the authorization code here: "));
}
list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);

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