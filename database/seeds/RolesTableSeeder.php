<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = factory(Role::class)
            ->times(4)
            ->make();

        Role::insert($roles->toArray());

        Role::find(1)->update(['name' => '工程技术员']);
        Role::find(2)->update(['name' => '保管员']);
        Role::find(3)->update(['name' => '安全员']);
        Role::find(4)->update(['name' => '爆破员']);

    }
}
