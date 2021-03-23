<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

Manager::schema()->create("meta", function (Blueprint $table) {
    $table->id();
    $table->string("key");
    $table->text("value")->nullable();
    $table->unsignedBigInteger("metaable_id");
    $table->string("metaable_type");
    $table->timestamps();
});
