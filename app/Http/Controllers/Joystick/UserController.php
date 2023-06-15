<?php

namespace App\Http\Controllers\Joystick;

use Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Joystick\Controller;

use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use App\Models\Region;
use App\Models\Company;

class UserController extends Controller
{
    public $companyId;

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::orderBy('created_at')
            ->when( ! auth()->user()->roles()->firstWhere('name', 'admin'), function($query) {
                return $query->where('company_id', $this->companyId);
            })->paginate(50);

        $regions = Region::get();

        return view('joystick.users.index', compact('users', 'regions'));
    }

    public function create($lang)
    {
        $this->authorize('create', User::class);

        $regions = Region::orderBy('sort_id')->get()->toTree();
        $companies = Company::where('company_id', $this->companyId)->where('sn_client', true)->orderBy('sort_id')->get();
        // $companies->push(auth()->user()->company);
        $roles = Role::all();

        return view('joystick.users.create', compact('regions', 'companies', 'roles'));
    }

    public function store(Request $request, $lang)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'tel' => ['required', 'string', 'max:15', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $this->authorize('create', User::class);

        $user = new User;

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $user->company_id = $request->company_id;
        } else {
            $user->company_id = $this->companyId;
            Company::where('id', $this->companyId)->update('sn_client', 1);
        }

        // $user->user_id = auth()->user()->id;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->address = $request->address;
        $user->balance = $request->balance;
        $user->is_customer = ($request->is_customer == 'on') ? 1 : 0;
        $user->is_worker = ($request->is_worker == 'on' OR $request->role_id) ? 1 : 0;
        $user->status = ($request->status == 'on') ? 1 : 0;
        $user->save();

        if (is_null($request->role_id)) {
            $user->roles()->detach();
            $user->is_worker = 0;
        } else {
            $user->roles()->sync($request->role_id);
        }

        $profile = new Profile;
        $profile->user_id = $user->id;
        $profile->region_id = $request->region_id;
        $profile->company_id = $request->company_id;
        $profile->tel = $request->phone;
        $profile->birthday = $request->birthday;
        $profile->gender = $request->gender;
        $profile->about = $request->about;
        $profile->is_debtor = ($request->debt_sum > 0) ? 1 : 0;
        $profile->debt_sum = $request->debt_sum;
        $profile->bonus = $request->bonus;
        $profile->discount = $request->discount;
        $profile->save();

        return redirect($lang.'/admin/users')->with('status', 'Запись добавлена!');
    }

    public function edit($lang, $id)
    {
        $user = User::query()
            ->when( ! auth()->user()->roles()->firstWhere('name', 'admin'), function($query) {
                return $query->where('company_id', $this->companyId);
            })->findOrFail($id);

        $this->authorize('update', $user);

        $regions = Region::orderBy('sort_id')->get()->toTree();
        $companies = Company::where('company_id', $this->companyId)->orderBy('sort_id')->get();
        $companies->push(auth()->user()->company);
        $roles = Role::all();

        if ($user->profile == null) {
            return view('joystick.users.create', compact('user', 'regions', 'companies', 'roles'));
        }

        return view('joystick.users.edit', compact('user', 'regions', 'companies', 'roles'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:60',
        ]);

        $user = User::query()
            ->when( ! auth()->user()->roles()->firstWhere('name', 'admin'), function($query) {
                return $query->where('company_id', $this->companyId);
            })->findOrFail($id);

        $this->authorize('update', $user);

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $user->company_id = $request->company_id;
        } else {
            $user->company_id = $this->companyId;
            Company::where('id', $this->companyId)->update('sn_client', 1);
        }

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->balance = $request->balance;
        $user->is_customer = ($request->is_customer == 'on') ? 1 : 0;
        $user->is_worker = ($request->is_worker == 'on' OR $request->role_id) ? 1 : 0;
        $user->status = ($request->status == 'on') ? 1 : 0;

        if (is_null($request->role_id)) {
            $user->roles()->detach();
            $user->is_worker = 0;
        } else {
            $user->roles()->sync($request->role_id);
        }

        $user->save();

        if (!$user->profile) {

            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->region_id = $request->region_id;
            $profile->company_id = $request->company_id;
            $profile->tel = $request->phone;
            $profile->birthday = $request->birthday;
            $profile->gender = $request->gender;
            $profile->about = $request->about;
            $profile->is_debtor = ($request->debt_sum > 0) ? 1 : 0;
            $profile->debt_sum = $request->debt_sum;
            $profile->bonus = $request->bonus;
            $profile->discount = $request->discount;
            $profile->save();

            return redirect($lang.'/admin/users')->with('status', 'Запись обновлена!');
        }

        $user->profile->region_id = $request->region_id;
        $user->profile->company_id = $request->company_id;
        $user->profile->tel = $request->tel;
        $user->profile->birthday = $request->birthday;
        $user->profile->gender = $request->gender;
        $user->profile->about = $request->about;
        $user->profile->is_debtor = ($request->debt_sum > 0) ? 1 : 0;
        $user->profile->debt_sum = $request->debt_sum;
        $user->profile->bonus = $request->bonus;
        $user->profile->discount = $request->discount;
        $user->profile->save();

        return redirect($lang.'/admin/users')->with('status', 'Запись обновлена!');
    }

    public function passwordEdit($lang, $id)
    {
        $user = User::findOrFail($id);

        return view('joystick.users.password', compact('user'));
    }

    public function passwordUpdate(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            // 'old_password' => 'required|min:6|max:255',
            'password' => 'required|confirmed|min:6|max:255'
        ]);

        $user = User::query()
            ->when( ! auth()->user()->roles()->firstWhere('name', 'admin'), function($query) {
                return $query->where('company_id', $this->companyId);
            })->findOrFail($id);

        if ($user->email != $request->email) {
            return redirect()->back()->with('danger', 'Email не совпадает!');
        }

        // if (Hash::check($request->old_password, $user->password)) {
        //     return redirect()->back()->with('danger', 'Старый пароль не совпадает!');
        // }

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        return redirect($lang.'/admin/users')->with('status', 'Запись обновлена!');
    }

    public function destroy($lang, $id)
    {
        $user = User::query()
            ->when( ! auth()->user()->roles()->firstWhere('name', 'admin'), function($query) {
                return $query->where('company_id', $this->companyId);
            })->find($id);

        $this->authorize('delete', $user);

        $user->roles()->detach();

        if ($user->profile) {
            $user->profile->delete();
        }

        $user->delete();

        return redirect($lang.'/admin/users')->with('status', 'Запись удалена.');
    }
}