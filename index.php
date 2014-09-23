<?php
include('vendor/autoload.php');
Twig_Autoloader::register();

include('common.php');

// User logic: enable to set exemplary sequence via POST
$result = NULL;
if (isset($_POST['sequence']) && isset($_POST['signature'])) {
    $result = create_compared($_POST['sequence'], $_POST['signature']);
}

try {
    $loader = new Twig_Loader_Filesystem('templates');
    $twig = new Twig_Environment($loader);
    $template = $twig->loadTemplate('submit.html');

    // set template variables
    // render template
    echo $template->render(array(
        'result' => $result
    ));
} catch (Exception $e) {
    die('ERROR: ' . $e->getMessage());
}

?>
