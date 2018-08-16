<?php

use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Engineering;
use App\Models\User;
use App\Models\Member;
use App\Models\Material;

class BatchesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = app(Faker\Generator::class);

        $user_ids = User::all()->pluck('id')->toArray();

        $batches = factory(Batch::class)
            ->times(500)
            ->make()
            ->each(function ($batch, $index)
            use($faker, $user_ids)
            {
                $batch->user_id = $faker->randomElement($user_ids);
                $batch->company_id = User::find($batch->user_id)->company_id;

                $engineering_ids = Engineering::where('company_id', $batch->company_id)->pluck('id')->toArray();
                $batch->engineering_id = $faker->randomElement($engineering_ids);

                // 若是该公司没有成员，则需手动修改 members 表
                $user_group_ids = Member::where('company_id', $batch->company_id)->select('id', 'role_id')->get()->toArray();
                foreach($user_group_ids as $user){
                    $users_array[$user['role_id']][] = (String)$user['id'];
                }

                $material_ids = Material::where('company_id', $batch->company_id)->pluck('id')->toArray();
                foreach($material_ids as $material_id) {
                    $materials[$material_id] =  $faker->numberBetween(5, 25);
                }

                $batch->groups = json_encode([
                    'technicians' => $faker->randomElements($users_array[1], 3),
                    'custodians'  => $faker->randomElements($users_array[2], 3),
                    'safety_officers' => $faker->randomElements($users_array[3], 3),
                    'powdermen' => $faker->randomElements($users_array[4], 3),
                    'manager' => $faker->randomElement($users_array[1]),
                ]);
                $batch->materials = json_encode($materials);
            });

        Batch::insert($batches->toArray());
    }

}

