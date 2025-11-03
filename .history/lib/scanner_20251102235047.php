<?php

/**
 * Scans the 'tools/' directory for files starting with 'osb-*.php' and folders with 'anphp' in their name.
 *
 * @return array Array of menu items with 'name' and 'url' keys.
 */
function scanTools() {
    $menuItems = [];
    $toolsDir = __DIR__ . '/../tools/';

    if (!is_dir($toolsDir)) {
        return $menuItems; // Return empty array if directory doesn't exist
    }

    $files = scandir($toolsDir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $fullPath = $toolsDir . $file;

        // Check for files starting with 'osb-' and ending with '.php'
        if (is_file($fullPath) && preg_match('/^osb-.*\.php$/', $file)) {
            $menuItems[] = [
                'name' => ucfirst(str_replace(['osb-', '.php'], ['', ''], $file)),
                'url' => 'tools/' . $file
            ];
        }

        // Check for directories that contain a 'script.php' file
        elseif (is_dir($fullPath) && file_exists($fullPath . '/script.php')) {
            $menuItems[] = [
                'name' => ucfirst(str_replace(['-', '_'], ' ', $file)), // Make name more readable
                'url' => 'tools/' . $file . '/script.php'
            ];
        }
    }

    return $menuItems;
}

/**
 * Recursively checks if a directory or any of its subdirectories contain 'anphp' in file names.
 *
 * @param string $dir The directory path to check.
 * @return bool True if 'anphp' is found in any file or subdirectory name, false otherwise.
 */
function containsAnphp($dir) {
    if (!is_dir($dir)) {
        return false;
    }

    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;

        if (strpos($item, 'anphp') !== false) {
            return true;
        }

        if (is_dir($fullPath) && containsAnphp($fullPath)) {
            return true;
        }
    }

    return false;
}

?>
?>
