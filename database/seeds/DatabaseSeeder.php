<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompaniesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
		$this->call(MaterialsTableSeeder::class);
		$this->call(SupervisionsTableSeeder::class);
        $this->call(EngineeringsTableSeeder::class);
        $this->call(MembersTableSeeder::class);
        $this->call(BatchesTableSeeder::class);
    }
}
