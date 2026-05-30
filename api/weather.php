<?php
header('Content-Type: application/json');

if(isset($_GET['date']) && isset($_GET['location'])) {
    $date = $_GET['date'];
    $location = $_GET['location'];
    
    // OpenWeatherMap API (you need to register for a free API key)
    $apiKey = 'YOUR_API_KEY_HERE'; // Replace with actual API key
    $url = "http://api.openweathermap.org/data/2.5/weather?q={$location}&appid={$apiKey}&units=metric";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    echo $response;
} else {
    echo json_encode(['error' => 'Missing parameters']);
}

// Timezone API
if(isset($_GET['timezone']) && isset($_GET['country'])) {
    $timezone = $_GET['timezone'];
    $dateTime = new DateTime('now', new DateTimeZone($timezone));
    echo json_encode([
        'timezone' => $timezone,
        'current_time' => $dateTime->format('Y-m-d H:i:s'),
        'date' => $dateTime->format('l, F j, Y')
    ]);
}
?>