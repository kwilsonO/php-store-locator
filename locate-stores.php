
<html>
<body>

<?php
chdir(dirname(__FILE__));
require_once 'secrets/zip-code-api-key.php';
$zip_code = $_POST["zipcode"];
$radius= $_POST["radius"];
?>

Current Zip Code: <?php echo $zip_code; ?><br>
Radius Selected: <?php echo $radius; ?><br>

<?php
echo $api_key;
$result=CallApi("GET", "https://www.zipcodeapi.com/rest/" . $api_key . "/radius.csv/" . $zip_code . "/" . $radius . "/miles");
echo $result;
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
