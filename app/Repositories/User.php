<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use Illuminate\Http\Request;
use App\Traits\HasDashboard;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class User extends RepositoryAbstract
{
    use HasDashboard;

    protected string $model = 'App\Models\User';

    /**
     * @var string
     */
    private string $role = 'employee';

    public function create(Request $request)
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self) {
            if (getenv('INSTALL') == 'false') {
                $session = $request->session()->all()['user'];
                $request->merge($session);
            }
            $request->merge(['password' => bcrypt($request->password)]);
            $user = new $self->model();
            $user = $user->fill($request->all());
            $user->save();
            if ($request->role) {
                $self->role = $request->role;
            }
            $role = Role::whereName($self->role)->first();
            $user->assignRole($role);

            return $user;
        });
    }

    public function update(Request $request, $user)
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self, $user) {
            if (getenv('INSTALL') == 'false') {
                $session = $request->session()->all()['user'];
                $request->merge($session);
            }
            if ($request->password) {
                $request->merge(['password' => bcrypt($request->password)]);
            } else {
                $request->merge(['password' => $user->password]);
            }
            $user = $user->fill($request->all());
            $user->save();
            if ($request->role) {
                $self->role = $request->role;
            }
            $role = Role::whereName($self->role)->first();
            $user->syncRoles($role);

            return $user;
        });
    }

    public function updatePassword(Request $request, $user)
    {
        return $user->update([
            'password' => bcrypt($request->only('password'))
        ]);
    }

    public function role(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get user only with the specific roles
     *
     * @param  $roles mixed string | array
     * @return list users
     */
    public function onlyRoles($roles)
    {
        if (gettype($roles) == 'string') {
            $roles = [$roles];
        }

        return (new $this->model())
            ->whereHas('roles', function($sub_query) use ($roles) {
                $sub_query->whereIn('name',  $roles);
            })->get();
    }

    public function totalForDashboard()
    {
        return $this->dataInfo('Employeeres', 'fa-user', 'bg-green',  $this->onlyRoles('employee')->count());
    }
}
