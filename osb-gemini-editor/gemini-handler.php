<?php
$message = $_POST['message'] ?? '';

if ($message) {
    $response = 'This is a mocked response from Gemini. To connect to the real Gemini API, you would need to add your API key here.';
    echo $response;
}
