<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('document')->unique();
            $table->string('phone');
            $table->boolean('website_origin');
            $table->boolean('facebook_origin');
            $table->boolean('indication_origin');
            $table->boolean('other_origin');
            $table->string('state', 5);
            $table->string('city');
            $table->enum('status', ['Ativo', 'Inativo'])->default('ativo');
            $table->string('observation')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
