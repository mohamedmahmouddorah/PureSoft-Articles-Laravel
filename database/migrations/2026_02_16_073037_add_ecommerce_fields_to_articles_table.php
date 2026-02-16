<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('content');
            }
            if (!Schema::hasColumn('articles', 'stock')) {
                $table->integer('stock')->default(0)->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['price', 'stock']);
        });
    }
};
