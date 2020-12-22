<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToMaintenanceCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintenance_categories', function (Blueprint $table) { 
            $table->foreignId('category_id')->nullable()->constrained('maintenance_categories'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_categories', function (Blueprint $table) { 
            $table->dropForeign('maintenance_categories_category_id_foreign');  
            $table->dropColumn('category_id'); 
        });
    }
}
