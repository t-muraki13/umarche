<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\once;

class InfomationController extends Controller
{
    public function infomation()
    {
        $owner = Owner::findOrFail(Auth::id());
        //dd($owner);
        $name = $owner->name;
        $email = $owner->email;

        return view('owner.infomation', compact('owner', 'name', 'email'));
        
    }

    public function confirm(Request $request, $id)
    {
        //dd($request);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $owner = Owner::findOrFail(Auth::id());
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        // フラッシュメッセージをセッションに保存
        session()->flash('message', 'ユーザー情報を更新しますか？');
        session()->flash('status', 'confirm');

        return view('owner.confirm', compact('owner', 'name', 'email', 'password', 'id'));
        
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $owner = Owner::findOrFail(Auth::id());
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('owner.dashboard')
        ->with(['message' => 'オーナー情報を更新しました。',
        'status' => 'info']);
    }
}
