<?php

/**
 * Safely redirect to a location
 * @param  string $fileName  The name of the redirection location file
 * @param  array  $urlParams Get parameters to give to the URL
 */
function redirectTo(string $fileName, array $urlParams = array()) {
    $i = 0;
    $lastParam = count($urlParams) - 1;
    $formattedParams = '';

    foreach ($urlParams as $key => $param) {
        if ($i === $lastParam) {
            $formattedParams .= '?';
        }

        $formattedParams .= $key . '=' . $param;

        if ($i !== $lastParam) {
            $formattedParams .= '&';
        }
    }

    header('Location:' . $fileName . '.php' . $formattedParams);
    die();
}
// TODO : mettre la fct en auto ds chaque fichier
// TODO : installer le module atom phpdoc
