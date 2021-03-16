<?php

namespace App\Modules\Setting\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Setting\Requests\ProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param ProfileRequest $profileRequest
     * @return JsonResponse
     */
    public function update(ProfileRequest $profileRequest)
    {
        /** @var User $user */
        $user = auth()->user();

        $data = $profileRequest->validated();
        $user->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    public function password(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $request->validate([
            'current_password' => ['required', function ($attribute, $value) use ($user) {
                return Hash::check($value, $user->getAuthPassword());
            }],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        $user->update([
            'password_hash' => Hash::make($request->post('new_password'))
        ]);

        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Успешно сохранено'
        ]);
    }
}
