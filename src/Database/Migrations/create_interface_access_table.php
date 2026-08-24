<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interface_access', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); 
            $table->set('access_type', ['backend','frontend','api'])->comment('backend,frontend,api');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interface_access');
    }
};
