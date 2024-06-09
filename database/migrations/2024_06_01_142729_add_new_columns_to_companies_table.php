<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('profile_image')->nullable();
            $table->string('email');
            $table->string('phone_number');
            $table->string('telephone_number')->nullable();
            $table->string('website')->nullable();
            $table->text('about_company')->nullable();
            $table->string('street_address');
            $table->string('city');
            $table->string('state_province');
            $table->string('zipcode');
            $table->string('country');
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('skype')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->enum('status', ['Active', 'Private', 'Inactive'])->default('Active');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
};
