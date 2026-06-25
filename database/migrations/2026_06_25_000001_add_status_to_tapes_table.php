<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('tapes', function (Blueprint $table) {
            $table->enum('status', ['available', 'rented'])->default('available')->after('condition');
        });
    }
    public function down(): void {
        Schema::table('tapes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
