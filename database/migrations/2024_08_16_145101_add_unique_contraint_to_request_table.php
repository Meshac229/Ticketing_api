<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToApiRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('api_requests', function (Blueprint $table) {
            $table->unique('api_key');
        });
    }

    public function down()
    {
        Schema::table('api_requests', function (Blueprint $table) {
            $table->dropUnique(['api_key']);
        });
    }
}
