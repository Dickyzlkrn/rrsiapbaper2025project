<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');
        });
    }
};
