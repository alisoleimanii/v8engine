<?php

namespace App\Http\Controller;

use Core\Module;

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


    private function stream($file)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        return readfile($file);
    }
}