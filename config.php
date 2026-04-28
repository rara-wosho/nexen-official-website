
<?php

define('BASE_URL', 'http://localhost/nexen-official-website/');

function url($path = '')
{
    return BASE_URL . ltrim($path, '/');
}
