<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class NewCompanyStaticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to avoid constraints violation during truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate the tables in the correct order
        $this->truncateTable('model_has_roles');
        $this->truncateTable('role_has_permissions');
        $this->truncateTable('users');
        $this->truncateTable('roles');
        $this->truncateTable('permissions');
        $this->truncateTable('plans');
        $this->truncateTable('stripe_prices');
        $this->truncateTable('stripe_products');
        $this->truncateTable('survey_answer_master');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Insert the data
        $this->insertRecord('static_data/plans.sql');
        $this->insertRecord('static_data/permissions.sql');
        $this->insertRecord('static_data/roles.sql');
        $this->insertRecord('static_data/users.sql');
        $this->insertRecord('static_data/role_has_permissions.sql');
        $this->insertRecord('static_data/model_has_roles.sql');
        $this->insertRecord('static_data/stripe_prices.sql');
        $this->insertRecord('static_data/stripe_products.sql');
        $this->insertRecord('static_data/survey_answer_master.sql');
    }

    /**
     * Truncate the table.
     *
     * @param string $tableName
     * @return void
     */
    private function truncateTable($tableName)
    {
        DB::table($tableName)->truncate();
    }

    /**
     * Execute the SQL file.
     *
     * @param string $filePath
     * @return void
     */
    private function insertRecord($filePath)
    {
        $fileFullPath = database_path($filePath);

        if(File::exists($fileFullPath)) {
            $fileContent = File::get($fileFullPath);
            DB::statement($fileContent);
        }
    }
}