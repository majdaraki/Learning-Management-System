<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Admin\{
    UpdateWallet
};
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Traits\Media;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UpdateBalanceInWallet;
class WalletsController extends Controller
{
    use Media;



    public function update(UpdateWallet $request, $id)
    {
        $user=User::FindOrFail($id);
        $newBalance = $request->balance + $user->wallet->balance;
        $user->wallet->update(['balance' => $newBalance]);
        Notification::route('mail', $user->email)
        ->notify(new UpdateBalanceInWallet($user, $request->balance,$newBalance));
        return $this->sudResponse('balance has been sent to wallet');

    }

}
