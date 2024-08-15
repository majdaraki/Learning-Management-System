<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\ChatEvent;
class ChatController extends  Controller
{
    public function message(Request $request){
        event(new ChatEvent($request->input('message'),$request->input('id')));
        return $this->sudResponse('done');
    }
}
