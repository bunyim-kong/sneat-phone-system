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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->nullable();
            $table->string('product_name');
            $table->string('product_imei');
            $table->foreignId('brand_id')->references('id')->on('brands')->cascadeOnDelete()->comment('brands.id');
            $table->foreignId('series_id')->references('id')->on('series')->cascadeOnDelete()->comment('series.id');
            $table->foreignId('color_id')->references('id')->on('colors')->cascadeOnDelete()->comment('colors.id');
            $table->foreignId('model_type_id')->references('id')->on('model_types')->cascadeOnDelete()->comment('model_types.id');
            $table->integer('condition')->default(1)->comment('1:Used, 2:New');
            $table->foreignId('storage_id')->references('id')->on('storages')->cascadeOnDelete()->comment('storages.id');
            $table->integer('type_of_machine')->default(1)->comment('1:iCloud, 2:Unlock, 3:Original, 4:Sim Lock');
            $table->foreignId('network_id')->nullable()->references('id')->on('networks')->cascadeOnDelete()->comment('networks.id');
            $table->unsignedInteger('battery_percentage')->nullable();
            $table->string('percentage')->nullable();
            $table->string('purchase_price');
            $table->string('selling_price')->nullable();
            $table->foreignId('employee_id')->references('id')->on('employees')->cascadeOnDelete()->comment('employees.id');
            $table->date('purchase_date')->nullable();
            $table->string('status')->default(1)->comment('1:Instock, 2:Sold, 3:Load, 4:Broken');
            $table->string('image', 500)->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
