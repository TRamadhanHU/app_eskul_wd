<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->role ?? null;
        $data = User::select('id', 'name', 'email', 'role_id')
            ->when($role, function ($query, $role) {
                return $query->where('role_id', $role);
            })
            ->get()
            ->toArray();

        $data = array_map(function ($item) {
            $item['role'] = config('accessrole')[$item['role_id']]['name'];
            return $item;
        }, $data);

        return view('cms.master.users.index', compact('data'));
    }

    public function upsert(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password ?? '12345678'),
            'role_id' => $request->role,
        ];
        try {
            DB::beginTransaction();
            if ($request->has('id')) {
                User::find($request->id)->update($data);
            } else {
                User::create($data);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('users');
    }

    public function show($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            User::find($id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
