<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmocrmIntegrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amocrm_integration', function (Blueprint $table) {
            $table->uuid('client_id')->primary();
            $table->string('client_secret', 64);
            $table->string('base_domain');
            $table->string('redirect_uri');
            $table->json('auth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amocrm_integration');
    }
}
