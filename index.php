<?php

require __DIR__ . '/vendor/autoload.php';

use Exchange\Rates\RatePharser;

$parser = new RatePharser();

$result = $parser->getRatesForBank('ID Bank');

if ($result) {
    dump($result);
} else {
    echo "Bank not found or error reading rates.";
}
