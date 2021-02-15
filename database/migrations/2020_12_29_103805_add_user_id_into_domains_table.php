<?php

use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdIntoDomainsTable extends Migration
{
    private ?User $user;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!$this->user = User::first()) {
            $this->user = User::factory()->create([
               User::COLUMN_IS_ALLOWED => false
            ]);
        }

        Schema::table(Domain::TABLE_NAME, function (Blueprint $table) {
            $table->foreignId(Domain::COLUMN_USER_ID)
                ->default($this->user->id)
                ->constrained(User::TABLE_NAME);
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
            $table->dropForeign([Domain::COLUMN_USER_ID]);
            $table->dropColumn(Domain::COLUMN_USER_ID);
        });
    }
}
