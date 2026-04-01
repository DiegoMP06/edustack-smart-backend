<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Traits\ApiQueryable;
use Illuminate\Http\Request;

class ApiUsersController extends Controller
{
    use ApiQueryable;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $users = $this->buildQuery(
            User::where(fn($query)  =>
                $query->whereNot('id', '=', $request->user()?->id)
                    ->where('is_active', '=', true)
            ), 
            defaultIncludes: ['roles']
        )->paginate(20)->withQueryString();

        return new UserCollection($users);
    }
}
