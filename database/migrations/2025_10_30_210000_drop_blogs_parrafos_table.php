<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('blogs_parrafos');
    }

    public function down(): void
    {
        // Si quieres restaurar la tabla, puedes definir la estructura aquí
    }
};
