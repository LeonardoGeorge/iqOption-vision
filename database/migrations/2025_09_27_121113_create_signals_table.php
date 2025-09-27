<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignalsTable extends Migration
{
    public function up()
    {
        Schema::create('signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['buy', 'sell', 'hold']);
            $table->decimal('confidence', 5, 2); // 0.00 to 1.00
            $table->decimal('price', 12, 5);
            $table->timestamp('timestamp');
            $table->decimal('trend_strength', 5, 2)->default(0);
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('medium');
            $table->string('recommended_action')->nullable();
            $table->decimal('stop_loss', 12, 5)->nullable();
            $table->decimal('take_profit', 12, 5)->nullable();
            $table->string('timeframe');
            $table->string('signal_source')->default('system');
            $table->json('analysis_data')->nullable();
            $table->timestamps();

            $table->index(['asset_id', 'timestamp']);
            $table->index(['type', 'confidence']);
            $table->index('risk_level');
        });
    }

    public function down()
    {
        Schema::dropIfExists('signals');
    }
}
