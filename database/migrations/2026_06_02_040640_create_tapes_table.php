<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('tapes', function (Blueprint $table) {
            $table->id();
            $table->string('tape_number', 50)->unique();
            $table->foreignId('movie_id')->constrained('movies');
            $table->enum('format', ['VHS', 'Beta', 'DVD']);
            $table->string('shelf_location', 50)->nullable();
            $table->enum('condition', ['Good', 'Fair', 'Poor'])->default('Good');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tapes'); }
};