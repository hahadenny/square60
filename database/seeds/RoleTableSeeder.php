<?php
use Illuminate\Database\Seeder;
use App\Role;


class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->name = 'User';
        $role_user->description = 'User';
        $role_user->save();

        $role_owner = new Role();
        $role_owner->name = 'Owner';
        $role_owner->description = 'Owner user';
        $role_owner->save();

        $role_agent = new Role();
        $role_agent->name = 'Agent';
        $role_agent->description = 'Agent user';
        $role_agent->save();
    }
}
