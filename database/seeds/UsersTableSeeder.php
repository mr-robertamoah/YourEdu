<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = User::create([
            'first_name' => 'robert',
            'last_name' => 'amoah',
            'other_names' => '',
            'username' => 'mr_robertamoah',
            'email' => 'mr_robertamoah@yahoo.com',
            'dob' => Carbon::parse('27 May 1990'),
            'password' => Hash::make('password'),
        ]);

        $user->admins()->create([
            'role' => 'SUPERADMIN'
        ]);
        
        $user2 = User::create([
            'first_name' => 'kojo',
            'last_name' => 'amoah',
            'other_names' => '',
            'username' => 'kkkamoah',
            'email' => 'kamoah@yahoo.com',
            'dob' => Carbon::parse('27 May 1990'),
            'password' => Hash::make('password'),
        ]);

        $user2->facilitator()->create();
        $user2->parent()->create();
        
        $user3 = User::create([
            'first_name' => 'junior',
            'last_name' => 'amoah',
            'other_names' => '',
            'username' => 'junioramoah',
            'email' => 'junioramoah@yahoo.com',
            'dob' => Carbon::parse('27 May 1990'),
            'password' => Hash::make('password'),
        ]);

        $learner = $user3->learner()->create();
        $learner->parents()->attach($user2->parent->id);
        
        
        $user4 = User::create([
            'first_name' => 'chairman',
            'last_name' => 'amoah',
            'other_names' => '',
            'username' => 'chairmanamoah',
            'email' => 'chairmanamoah@yahoo.com',
            'dob' => Carbon::parse('27 May 1990'),
            'password' => Hash::make('password'),
        ]);

        $user4->schools()->create([
            'company_name' => 'first school international',
            'role' => 'TRADITIONAL',
        ]);

        $user4->schools()->create([
            'company_name' => 'second school',
        ]);
    }
}
