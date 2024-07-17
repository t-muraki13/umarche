<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        //dd('ttt');
        $user = User::findOrFail(Auth::id());
        //dd($user);
        $email = $user->email;
        $name = $user->name;
        
        return view('user.infomation', compact('user', 'name', 'email'));
    }

    public function confirm(Request $request, $id)
    {
        //dd('confirm');
        //dd($request);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::findOrFail(Auth::id());

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        //dd($name, $email, $password);

        // フラッシュメッセージをセッションに保存
        session()->flash('message', 'ユーザー情報を更新しますか？');
        session()->flash('status', 'confirm');

        return view('user.confirm', compact('user', 'name', 'email', 'password', 'id'));

    }

    public function update(Request $request, $id)
    {
        //dd($request);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
        ->route('user.items.index')
        ->with(['message' => 'ユーザー情報を更新しました。',
        'status' => 'info']);
    }
}
