<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up()
    {
        Permission::create(['name' => 'view relatorio-cestas', 'guard_name' => 'web']);
    }

    public function down()
    {
        Permission::where('name', 'view relatorio-cestas')->delete();
    }
};
