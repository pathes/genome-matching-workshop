<?php
include('vendor/autoload.php');
Twig_Autoloader::register();

include('common.php');

// User validation
// TODO make it more sophisticated one day
function validate_user($user, $pass) {
    if ($user === 'admin' && $pass === 'polimeraza') {
        return true;
    }
    return false;
}

if (!validate_user($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="Genome matching"');
    header('HTTP/1.0 401 Unauthorized');
    echo "You need to enter a valid username and password.";
    exit;
}

// Admin logic: enable to set exemplary sequence via POST
if (isset($_POST['exemplar'])) {
    create_exemplar($_POST['exemplar']);
}

// Admin routing: comparing exemplar and picked sequence
if (isset($_GET['submission'])) {
    $template = 'admin_compare.html';
    $vars = array(
        'diff' => compare_with_exemplar($_GET['submission'])
    );
} else {
    $template = 'admin.html';
    $vars = array(
        'submissions' => list_submissions()
    );
}

try {
    $loader = new Twig_Loader_Filesystem('templates');
    $twig = new Twig_Environment($loader);
    $template = $twig->loadTemplate($template);

    // set template variables
    // render template
    echo $template->render($vars);
} catch (Exception $e) {
    die('ERROR: ' . $e->getMessage());
}

?>