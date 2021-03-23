<?php


namespace App\Helper;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

class File
{
    private UploadedFile $file;

    /**
     * @param UploadedFile $file
     * @return self
     */
    public static function instance(UploadedFile $file)
    {
        $self = new self();
        $self->file = $file;
        return $self;
    }

    public function store($folder = null)
    {
        $target = BASEDIR . "/resources/View/assets/images/";
        $folder ? $target .= $folder . "/" : null;
        $name = uniqid() . "." . $this->file->getClientOriginalExtension();
        (new Filesystem())->move($this->file->path(), $target . $name);
        return $folder . "/" . $name;
    }
}