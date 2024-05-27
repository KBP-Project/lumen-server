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
        Schema::create('header_messages', function (Blueprint $table) {
            $table->id();
            $table->string('code_subkategori');
            $table->string('code_message')->unique();
            $table->string('title');
            $table->integer('pembuat_percakapan');
            $table->boolean('status')->nullable()->default(true);
            
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('code_subkategori')->references('code_subkategori')->on('faq_subcategories')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_messages');
    }
};
