<?php


use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

Manager::schema()->create("config", function (Blueprint $table) {
    $table->id();
    $table->string("key");
    $table->text("value")->nullable();
    $table->timestamps();
});