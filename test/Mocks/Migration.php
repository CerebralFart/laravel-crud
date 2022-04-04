<?php

namespace Cerebralfart\LaravelCRUD\Test\Mocks;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('pokemon', function (Blueprint $table) {
            $table->string('id');
            $table->string('name');
            $table->float('weight');
            $table->float('height');
        });
    }

    public function down() {
        Schema::dropIfExists('pokemon');
    }
};
