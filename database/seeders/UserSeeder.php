<?php

namespace Database\Seeders;

use App\Classes\Helper\ReferralCode;
use Database\Factories\UserFactory;
use Faker\Generator;
use Faker\Provider\Image;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! File::exists('storage/app/public/users/image')){
            File::makeDirectory('storage/app/public/users/image',0777,true);
        }

        $faker = new Image(new Generator());
        $user = User::create([
            'name' => 'Admin One',
            'user_name' => 'admin',
            'email' => 'admin@dev.com',
            'gender' => 'Male',
            'age' => '25',
            'password' => Hash::make('secret'),
            'profile_image' => "users/image/" . $faker->image('storage/app/public/users/image',640,480, 'people', false)
        ]);
        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

        $referralCode = new ReferralCode();
        $userTwo = User::create([
		    'name' => 'Seller One',
		    'user_name' => 'seller',
		    'email' => 'seller@dev.com',
            'gender' => 'Male',
            'age' => '25',
		    'password' => Hash::make('secret'),
            'referral_code' => $referralCode->createReferralCode(),
            'profile_image' => "users/image/" . $faker->image('storage/app/public/users/image',640,480, 'people', false)
	    ]);
	    $roleTwo = Role::create(['name' => 'Seller']);
	    $permissionsTwo = Permission::where('name','LIKE','%seller%')->pluck('id','id')->all();
	    $roleTwo->syncPermissions($permissionsTwo);
	    $userTwo->assignRole([$roleTwo->id]);
        $userTwo->assignFreePlan();

        $users = User::factory()
            ->count(5)
            ->create();

        foreach($users as $u){
            $emptyNodeId = User::findEmptyNode($userTwo->id);
            $u->parent_id = $emptyNodeId;
            $u->root_id = $userTwo->id;
            $u->save();
            $u->assignRole([$roleTwo->id]);
            $u->assignFreePlan();
        }
    }
}
