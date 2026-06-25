<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tape_id')->constrained('tapes')->onDelete('cascade');
            $table->string('rented_by'); // customer name
            $table->date('rented_at');
            $table->date('due_date');
            $table->date('returned_at')->nullable();
            $table->timestamps();

            $table->index('rented_by');
            $table->index('returned_at');
        });
    }
    public function down(): void {
        Schema::dropIfExists('rentals');
    }
};
