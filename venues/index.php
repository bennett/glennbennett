<?php




ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Set up cURL request
$apiKey = 'AIzaSyCfWxFe-Sj6eAKhUs5rukfk-bJfkny1S6Y';
$apiUrl = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';
$location = '34.2804922,-119.2945203'; // Coordinates for Ventura County
$radius = 5000; // Radius in meters

$type = 'bar'; // Set the type to 'bar' or any other relevant live music venue type

$url = $apiUrl . '?location=' . $location . '&radius=' . $radius . '&type=' . $type . '&key=' . $apiKey;

// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($curl);

// Close cURL session
curl_close($curl);

// Decode JSON response
$data = json_decode($response, true);
var_dump($data);

// Connect to your database using appropriate PHP MySQL functions
$servername = 'localhost';
$username = 'tsgimh_hapmag';
$password = '2276midi';
$dbname = 'tsgimh_venues';

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Clear existing data from the database table
$query = "TRUNCATE TABLE venues";
mysqli_query($conn, $query);

// Insert new data into the database
foreach ($data['results'] as $venue) {
    $name = mysqli_real_escape_string($conn, $venue['name']);
    $address = mysqli_real_escape_string($conn, $venue['vicinity']);

    $query = "INSERT INTO venues (name, address) VALUES ('$name', '$address')";
    mysqli_query($conn, $query);
}

// Close database connection
mysqli_close($conn);

// Output success message
echo "Data inserted into database successfully!";
?>   
