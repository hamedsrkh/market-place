<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::create(
            [
                'name' => 'admin',
                'label' => 'admin type user'
            ]
        );
        $seller_role = Role::create(
            [
                'name' => 'seller',
                'label' => 'seller type user'
            ],
        );

        $admin_permission = Permission::create(
            [
                'name' => 'admin_all',
                'label' => 'all admin permissions'
            ],
        );

        $seller_permission = Permission::create(
            [
                'name' => 'seller_all',
                'label' => 'all seller permissions'
            ]
        );

        $admin_role->permissions()->attach($admin_permission->id);
        $seller_role->permissions()->attach($seller_permission->id);


       // admin user create
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => '12345678'
        ]);

        $user->roles()->sync(
            Role::where('name', 'admin')->first()->id
        );

        // customer create
        User::factory()
            ->count(20)
            ->create();

        // seller create
        User::factory()->count(30)->create()->each(function ($user) use ($seller_role) {
            $store = $user->store()->create(Store::factory()->make()->toArray());
            foreach (range(1,10) as $item){
                $store->products()->create(Product::factory()->make()->toArray());
            }
            $user->roles()->sync($seller_role->id);
        });

    }
}
