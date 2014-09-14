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
    $data = preg_replace('/.\{1\}/', '&\n', $data);
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
    $signature = base64_encode($signature);
    $handle = fopen('./data/' . $signature, 'w');
    fwrite($handle, $data);
    fclose($handle);
}

?>