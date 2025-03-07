<?php

namespace Core\Redirect;

class Redirect implements RedirectInterface
{
    static public function to(string $url)
    {
        header("Location: $url");
        exit;
    }


}