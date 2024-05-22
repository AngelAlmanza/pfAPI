<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("pets", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->string("name");
            $table
                ->enum("type", [
                    "dog",
                    "cat",
                    "bird",
                    "fish",
                    "hamster",
                    "rabbit",
                    "other",
                ])
                ->default("dog");
            $table->string("breed");
            $table->string("age");
            $table->string("personality");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("pets");
    }
};
