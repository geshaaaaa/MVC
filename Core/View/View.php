<?php

namespace Core\View;
use Exception;

class View implements ViewInterface
{
    static public function page($name, array $data = []) : void
    {
       $file =  BASE_DIR . "/public/assets/pages/$name.php";
       if (!file_exists($file))
       {
           throw new Exception("View $name not found");
       }

        extract($data);

        include_once $file;

    }


}