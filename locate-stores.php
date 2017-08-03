<html>
<body>

<?php
chdir(dirname(__FILE__));
require_once 'secrets/zip-code-api-key.php';
$servername = "localhost";
$username = "testuser";
$password = "password";
$dbname = "testdb";
$zip_code = $_POST["zipcode"];
$radius= $_POST["radius"];
$apiurl = sprintf("https://www.zipcodeapi.com/rest/%s/radius.csv/%s/%s/miles?minimal", $api_key, $zip_code, $radius);

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>

Current Zip Code: <?php echo $zip_code; ?><br>
Radius Selected: <?php echo $radius; ?><br>
API Key: <?php echo $api_key; ?><br>
URL: <?php echo $apiurl; ?><br>

<?php
$result=CallApi("GET", $apiurl);
echo $result . "<br>";
//replace the zip_code text from results
$result = str_replace("zip_code","",$result);
$result = preg_replace('#\s+#',', ',trim($result));
echo $result . "<br>";
$sql= sprintf("SELECT id, name, address, zip_code, phone  FROM CLIENT_DATA WHERE zip_code IN (%s)",$result);
echo $sql . "<br>";
$queryresult = $conn->query($sql);

if ($queryresult->num_rows > 0) {
    while($row = $queryresult->fetch_assoc()) {
	echo $row;
    }
} else {
    echo "0 results";
}
$conn->close();

?>


</body>
<html>

<?php


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

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

?>
