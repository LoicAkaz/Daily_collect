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
        Schema::create('messages', function (Blueprint $table) {
            $table->integer("id_message")->primary()->autoIncrement();
            $table->string("contenu");
            $table->string("envoyeur", 10);
            $table->string("receptioneur");
//            $table->string("id_chat");
//            $table->foreign("id_chat")->references("id_chat")->on("chats")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
