<?php

namespace App\Http\Controllers;

use App\Gravatar;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profile', compact('user'));
    }

    /**
     * show email change form
     */

    public function emailChangeForm()
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        return view('auth.email-change');
    }

    /**
     * show password change form
     */

    public function passwordChangeForm()
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        return view('auth.passwords.change');
    }

    /**
     * update user's email
     * @param Request $request
     */

    public function emailChange(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        $request->validate(['email' => ['required', 'string', 'email', 'max:255', 'unique:users']]);

        $user->update([
            'email' => $request->email,
            'email_verified_at' => null,
            'avatar_url' => Gravatar::url($request->email)
        ]);

        return redirect("/user/$user->id")->with('status', 'ელ-ფოსტა შეიცვალა');
    }

    /**
     * update user's password
     */

    public function passwordChange(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        $request->validate(['password' => ['required', 'string', 'min:8', 'confirmed']]);

        $user->update(['password' => Hash::make($request->password)]);

        return redirect("/user/$user->id")->with('status', 'პაროლი შეიცვალა');
    }

}
