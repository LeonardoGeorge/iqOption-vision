<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol')->unique();
            $table->enum('type', ['forex', 'crypto', 'stock', 'commodity'])->default('forex');
            $table->boolean('active')->default(true);
            $table->string('iqoption_id')->nullable();
            $table->decimal('current_price', 12, 5)->nullable();
            $table->timestamp('price_updated_at')->nullable();
            $table->timestamps();

            $table->index(['active', 'type']);
            $table->index('symbol');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
