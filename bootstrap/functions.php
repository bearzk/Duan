<?php

use Symfony\Component\HttpFoundation\RedirectResponse;

function redirect($url, $headers = []) {
    return new RedirectResponse($url, 302, $headers);
}
