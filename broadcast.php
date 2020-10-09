<?php

//set the upload directory for our CSV file
$upload_path = "upload/";

//append the file name to our path so that we have a complete path
$upload_path = $upload_path . basename( $_FILES['csv_file']['name']); 

//test to see if we can successfully move the file into our $upload_path directory
if(move_uploaded_file($_FILES['csv_file']['tmp_name'], $upload_path)) {
    echo "The file ".  basename( $_FILES['csv_file']['name']). 
    " has been uploaded </br>";
} else{ //if not then return an error
    echo "There was an error uploading the file, please try again! </br>";
}

//open the CSV file for reading
$handle = fopen($upload_path, 'r');

//include the twilio-php library
include "twilio-php/Services/Twilio.php";

//include out credentials file with our Account SID and Auth Token
include "twilio-php/credentials.php";

//set our outgoing number
$outgoing_number = "";

//instantiate a new $client object
$client = new Services_Twilio($ACCOUNT_SID, $AUTH_TOKEN);

/*step through our CSV file, up to 1000 lines, read in the data using the fgetcsv() function
and insert the data into array elements, send an sms message to each $number element that is read*/
while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
	$contact = $data[0];
	$number = $data[1];
	$message = $client->account->sms_messages->create($outgoing_number,$number,"Click to download our app on the Apple App Store: http://itunes.apple.com/us/app/angry-birds-space/id499511971");
	echo "Message ID: " . $message->sid . " sent to: " . $number . "</br>";

}

unlink($upload_path);

?>