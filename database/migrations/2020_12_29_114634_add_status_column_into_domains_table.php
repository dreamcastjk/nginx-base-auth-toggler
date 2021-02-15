<?php

use App\Models\Domain;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnIntoDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Domain::TABLE_NAME, function (Blueprint $table) {
            $table->integer(Domain::COLUMN_STATUS)
                ->after(Domain::COLUMN_DOMAIN)
                ->default(Domain::STATUS_DISABLED);
            $table->index(Domain::COLUMN_STATUS);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Domain::TABLE_NAME, function (Blueprint $table) {
            $table->dropIndex([Domain::COLUMN_STATUS]);
            $table->dropColumn(Domain::COLUMN_STATUS);
        });
    }
}
