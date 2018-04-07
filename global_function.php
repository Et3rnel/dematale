<?php

/**
 * Safely redirect to a location
 * @param  string $fileName  The name of the redirection location file
 * @param  array  $urlParams Get parameters to give to the URL
 */
function redirectTo(string $fileName, array $urlParams = array())
{
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

    header('Location:http://' . $_SERVER['HTTP_HOST'] . '/' . $fileName . '.php' . $formattedParams);
    die();
}

/**
 * Get the current DateTime to insert into the MySQL DB format
 * @return DateTime The formatted DateTime
 */
function getDateTime()
{
    $dateTimeObject = new DateTime();
    return $dateTimeObject->format('Y-m-d H:i:s');
}

/**
 * Cast the value to a number and set its value to zero if it's negative
 * @param mixed $number The value to set as a number
 * @return int The result number
 */
function negativeZero($number)
{
    return max((int) $number, 0);
}

// TODO : créer une fonction qui check if user connecté
// TODO : add a format function like number_format but cleaner to use
