<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables(User::query())->toJson();
        }

        return view('user.index');
    }


    public function create()
    {
        return view('user.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'avatar' => 'nullable|image|max:2048'
        ]);

        return response()->report(User::create($validated));
    }


    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|confirmed',
            'avatar' => 'nullable|image|max:2048'
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        return response()->report($user->update($validated));
    }

    public function destroy(User $user)
    {
        if($user->id === Auth::id()) {
            return response()->error('Can\'t delete self');
        }
        return response()->report($user->delete());
    }

    public function portal(User $user)
    {
        abort_if(!Auth::user()->isA('admin'), 403);
        $cid = uniqid();
        Cache::put($cid, $user->id, 60);
        $url = URL::temporarySignedRoute(
            'portal', now()->addMinute(), ['user' => $user->id, 'cid' => $cid]
        );
        return <<<HTML
<body style="padding: 2rem;">
Open <a href="$url" target="_blank">$url</a> in incognito window.
<script type="text/javascript">
window.onblur = function() {
  window.close();
}
</script>
</body>
HTML;
    }
}
