<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

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
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        return response()->report($user->delete(), 'User deleted successfully');
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
