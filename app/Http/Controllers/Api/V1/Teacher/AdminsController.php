<?php

namespace App\Http\Controllers\Api\V1\Teacher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use App\Traits\FirebaseNotification;
class AdminsController extends  Controller
{
    // use FirebaseNotification;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->indexOrShowResponse('body',$admins=User::role('admin')->get());

       // $this->sendNotification('e2ZyfgYmRSm1OAOson2i5w:APA91bGfCE1mMGgcqHde0Fz8mibeE2H0U4kfR4SRzKcFSTpwAnVyZ8tRZiAiSg4ZUchBHT6TjHk5kCwb87Ok4VwyO_anlkHTn0OcOIqEaK_pOP3s1oIw9120OnAsIaUkwix4OXtQSqQx','تجريب','باك عم الجميغ');
       // return 'done';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
