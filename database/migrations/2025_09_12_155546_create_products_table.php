<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('slug')->unique(); // <--- Thêm dòng này
    $table->decimal('price', 10, 2);
    $table->text('description')->nullable();
    $table->string('image')->nullable();
    $table->integer('stock')->default(0);
    $table->boolean('status')->default(true);
    $table->integer('sold')->default(0);
    $table->timestamps();
});

}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
