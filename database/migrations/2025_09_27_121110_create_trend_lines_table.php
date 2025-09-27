<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrendLinesTable extends Migration
{
    public function up()
    {
        Schema::create('trend_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->enum('direction', ['up', 'down', 'sideways']);
            $table->decimal('strength', 5, 2)->default(0); // 0.00 to 1.00
            $table->decimal('breakout_point', 12, 5)->nullable();
            $table->decimal('support_level', 12, 5)->nullable();
            $table->decimal('resistance_level', 12, 5)->nullable();
            $table->string('timeframe'); // 1m, 5m, 15m, 1h, 4h, 1d
            $table->integer('touch_count')->default(0);
            $table->boolean('is_valid')->default(true);
            $table->timestamp('last_touch_at')->nullable();
            $table->timestamps();

            $table->index(['asset_id', 'direction']);
            $table->index(['is_valid', 'timeframe']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trend_lines');
    }
}
