<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presence_details', function ($table) {
            $table->string('no_telp')->nullable()->after('asal_instansi');
        });
    }

    public function down(): void
    {
        Schema::table('presence_details', function ($table) {
            $table->dropColumn('no_telp');
        });
    }

};
