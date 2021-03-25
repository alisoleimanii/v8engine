<?php

namespace App\Http\Controller;

use Core\Module;
use Core\View;

class AssetController
{
    public function module($module, $asset)
    {
        $file = Module::getModuleDir($module) . "/View/assets/{$asset}";
        if (file_exists($file)) {
            return $this->stream($file);
        }
        return abort("File Not Found", 404);
    }

    public function asset($asset)
    {
        $file = View::baseViewsPath() . "/assets/{$asset}";
        if (file_exists($file)) {
            $this->stream($file);
            die();
        }
        return abort("File Not Found", 404);
    }


    private function stream($file)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $this->getMimeType($file));
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        return readfile($file);
    }

    public function getMimeType($file)
    {
        $mimeTypes = ["css" => "text/css",
            "js" => "text/javascript",
        ];
        return @$mimeTypes[$this->getFileExtention($file) ?? null];
    }

    public function getFileExtention($file)
    {
        return last(explode(".", $file));
    }

}