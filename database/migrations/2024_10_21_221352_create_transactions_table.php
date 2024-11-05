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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string("id_transaction")->primary();
            $table->integer("montant");
            $table->string("type_trans", 10);
            $table->double("longitude");
            $table->double("latitude");
            $table->string("id_user");
            $table->string("id_client");
            $table->foreign("id_user")->references("id_user")->on("users")->onDelete("cascade");
            $table->foreign("id_client")->references("id_client")->on("client")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
