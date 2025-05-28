<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterTipoColumnOnTransfersTable extends Migration
{
    public function up()
    {
        // Passo 1: Alterar temporariamente o tipo de ENUM para string
        Schema::table('transfers', function (Blueprint $table) {
            $table->string('tipo_temp')->nullable();
        });

        // Passo 2: Copiar os dados da coluna antiga para a nova
        DB::table('transfers')->update([
            'tipo_temp' => DB::raw('tipo')
        ]);

        // Passo 3: Remover a coluna enum antiga
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });

        // Passo 4: Renomear a nova coluna para o nome original
        Schema::table('transfers', function (Blueprint $table) {
            $table->string('tipo')->nullable()->after('data_hora');
            $table->dropColumn('tipo_temp');
        });
    }

    public function down()
    {
        // Reverte a mudança e volta a ser enum
        Schema::table('transfers', function (Blueprint $table) {
            $table->enum('tipo', ['transfer', 'passeio'])->after('data_hora');
        });
    }
}
