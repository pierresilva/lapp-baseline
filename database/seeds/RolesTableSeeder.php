<?php

use Illuminate\Database\Seeder;
use pierresilva\Sentinel\Models\Role;
use pierresilva\Sentinel\Models\Permission;
use App\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Pierre Silva',
            'email' => 'pierremichelsilva@gmail.com',
            'password' => bcrypt('colombia1'),
            'active' => true,
            'activation_token' => null,
            'avatar' => 'avatar.jpg',
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);

        // $avatar = \Avatar::create($user->name)->getImageObject()->encode('png');
        // \Storage::disk('public')->put('avatars/' . $user->id . '/avatar.png', (string)$avatar);

        //
        $roleAdmin = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Administrator role.'
        ]);

        $roleUser = Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Registered user role.'
        ]);

        $permissionAdmin = Permission::create([
            'name' => 'Administrator default',
            'slug' => 'admin.default',
            'description' => 'Administrator default permisssion.'
        ]);

        $permissionUser = Permission::create([
            'name' => 'User default',
            'slug' => 'user.default',
            'description' => 'Registered user default permisssion.'
        ]);

        $roleAdmin->syncPermissions([$permissionAdmin->id]);
        $roleUser->syncPermissions([$permissionUser->id]);

        $userAdmin = User::find(1);
        $userAdmin->syncRoles([$roleAdmin->id, $roleUser->id]);

    }
}
