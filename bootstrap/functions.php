<?php

use Symfony\Component\HttpFoundation\RedirectResponse;

function redirect($url) {
    header('HTTP/1.1 302 Found');
    header("Location: {$url}");
    exit(0);
}
