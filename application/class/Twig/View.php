<?php

namespace App\Twig;

use App\Twig\Twig;

class View {

    
    public function __construct($template , array $data = [])
    {
        $twig = new Twig($template);
        return $twig->render($data);
    }

}