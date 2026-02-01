<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Budget;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add uuid column as nullable first
        Schema::table('budgets', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
        });
        
        // Step 2: Generate UUIDs for existing records
        Budget::whereNull('uuid')->each(function ($budget) {
            $budget->uuid = (string) Str::uuid();
            $budget->save();
        });
        
        // Step 3: Make uuid unique and add index
        Schema::table('budgets', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
