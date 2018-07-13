<?php

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $company_ids = Company::all()->pluck('id')->toArray();

        $role_ids = Role::all()->pluck('id')->toArray();

        // 生成数据集合
        $users = factory(User::class)
            ->times(50)
            ->make()
            ->each(function ($user, $index)
            use ($faker, $company_ids, $role_ids)
            {
                // 从头像数组中随机取出一个并赋值
                $user->company_id = $faker->randomElement($company_ids);
                $user->role_id = $faker->randomElement($role_ids);
            });

        // 让隐藏字段可见，并将数据集合转换为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($user_array);

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'scuba';
        $user->password = bcrypt('s19832002');
        $user->save();
    }
}
