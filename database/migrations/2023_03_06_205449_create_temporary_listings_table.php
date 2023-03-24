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
    public function up()
    {
        Schema::create('temporary_listings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('user_uuid');
            $table->string('title');
            $table->unsignedInteger('industry_id');
            $table->unsignedInteger('company_id')->nullable();
            $table->string('apply_link');
            $table->unsignedInteger('job_type_id');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporary_listings');
    }
};
