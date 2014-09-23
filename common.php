<?php

/**
 * Sanitize input data - remove whitespaces, lowercase, add \n between letters for diff.
 */
function sanitizeData($data) {
    // Remove whitespaces
    $data = trim($data);
    // Convert to lowercase
    $data = strtolower($data);
    // Add \n between characters
    $data = preg_replace('/(.)/', '${1}'."\n", $data);
    return $data;
}

function sanitizeName($name) {
    // Remove all whitespaces
    $name = preg_replace('/\s+/', '', $name);
    // Convert to lowercase
    $name = strtolower($name);
    return $name;
}

function create_exemplar($data) {
    $data = sanitizeData($data);
    $handle = fopen('./data/exemplar.txt', 'w');
    fwrite($handle, $data);
    fclose($handle);
}

function create_compared($data, $signature) {
    $data = sanitizeData($data);
    $signature = base64_encode($signature . ' - ' . date('h:i:s'));
    $handle = fopen('./data/submissions/' . $signature, 'w');
    fwrite($handle, $data);
    fclose($handle);
    return 'OK';
}

function list_submissions() {
    $submissions = array();
    foreach (glob('data/submissions/*[^.php]') as $path) {
        $url = substr($path, strlen('data/submissions/'));
        $name = base64_decode($url);
        $submissions[$url] = $name;
    }
    return $submissions;
}

function compare_with_exemplar($filename) {
    // Check if $filename is base64-encoded value
    if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $filename)) {
        return '';
    }
    return shell_exec('diff data/exemplar.txt data/submissions/' . $filename);
}

?>