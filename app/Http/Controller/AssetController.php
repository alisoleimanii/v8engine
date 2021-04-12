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
            $this->stream($file);
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
        // Last Modified
        $lastModified = filemtime(__FILE__);

        // Get a unique hash of this file (etag)
        $etagFile = md5_file(__FILE__);

        // Get the HTTP_IF_MODIFIED_SINCE header if set
        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);

        // Get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        // Set last-modified header
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");


        header('Content-Type: ' . $this->getMimeType($file));
//        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
        // Set etag-header
        header("Etag: $etagFile");

        header('Cache-Control: public');
        header('Content-Length: ' . filesize($file));

        if (@strtotime($ifModifiedSince) == $lastModified || $etagHeader == $etagFile) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
        readfile($file);
        die();
    }

    public function getMimeType($file)
    {
        $mimeTypes = ["css" => "text/css",
            "js" => "text/javascript",
        ];
        return @$mimeTypes[$this->getFileExtension($file) ?? null];
    }

    public function getFileExtension($file)
    {
        return last(explode(".", $file));
    }


}