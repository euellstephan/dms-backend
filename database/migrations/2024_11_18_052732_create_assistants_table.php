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
        Schema::create('assistants', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('resident_id')->unsigned();
            $table->foreign('resident_id')->references('id')->on('residences');

            $table->string('assitant_type')->nullable();
            $table->text('description')->nullable();
            $table->date('date_request')->nullable();
            $table->string('status')->nullable();	
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistants');
    }
};
