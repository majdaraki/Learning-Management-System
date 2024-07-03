<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Student\StoreIssueRequest;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IssuesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $student = Auth::user();
            $student->issues()->create($request->validated());
            return $this->sudResponse('Issue sent successfully.');
        });
    }

}
