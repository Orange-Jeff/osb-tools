<?php
header('Content-Type: application/json');

// Include the scanner library
require_once '../lib/scanner.php';

// Get menu items from scanner
$menuItems = scanTools();

// Output JSON
echo json_encode($menuItems);
?>
