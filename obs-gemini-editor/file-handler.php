<?php
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        $files = glob('*');
        echo json_encode($files);
        break;

    case 'get':
        $filename = $_POST['filename'] ?? '';
        if ($filename && file_exists($filename)) {
            echo file_get_contents($filename);
        }
        break;

    case 'save':
        $filename = $_POST['filename'] ?? '';
        $content = $_POST['content'] ?? '';
        if ($filename && $content) {
            file_put_contents($filename, $content);
            echo 'File saved successfully.';
        }
        break;
}
