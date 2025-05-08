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
        Schema::create('module_actions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('controller');
            $table->string('method');
            $table->text('action');
            $table->set('route_type', ['post', 'get', 'put', 'patch', 'delete'])->comment('post,get,put,patch,delete');
            $table->enum('menu_type', ['Admin', 'Admin Backend']);
            $table->string('menu_label')->nullable();
            $table->enum('menu_status', ['0', '1'])->default('0');
            $table->integer('menu_sequence');
            $table->integer('menu_order');
            $table->string('menu_icon');
            $table->string('module_label');
            $table->tinyInteger('status')->default(1)->comment('0 = INACTIVE, 1 = ACTIVE, 2 = MAINTENANCE');
            $table->dateTime('created_date')->default(now())->useCurrentOnUpdate();
            $table->json('extra_options')->default('{}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_actions');
    }
};
