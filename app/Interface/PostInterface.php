<?php


namespace App\Interfaces;


interface PostInterface
{
    public function title(): string;

    public function slug(): ?string;

    public function content(): string;

    public function excerpt(): ?string;

//    public function parent() : ?PostInterface;
//    public function metaTags(): array;

    
    public function tags(): ?array;

    public function category(): array;

    public function image(): string;

    public function url(): string;

}