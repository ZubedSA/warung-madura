<?php

// Standalone test script to verify Turso connection without Laravel overhead
$url = "https://turso-db-create-warung-madura-zubedsa.aws-ap-northeast-1.turso.io/v2/pipeline";
$token = "eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3Njc5ODk2MDIsImlkIjoiOTVlNWM3MGUtZTU4YS00MDFjLWI5Y2MtNWM3ZWMxNWZkZTUwIiwicmlkIjoiZjM1NTFhNWEtNjg2OS00MDc1LThhYTAtMjEyYjI5MDBhMDkzIn0.CoBmITRsgBq1jWm6WfFLf3AaEwokwPScM6cNbLZOgwZ7_GuuT7S7Q7r0D95e2oWxe6VrAPC3hsLRM-hsY51sAQ";

$query = [
    "requests" => [
        ["type" => "execute", "stmt" => ["sql" => "SELECT 1"]]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
