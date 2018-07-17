<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Comment;

class CommentController extends Controller
{
    //
    public function __invoke()
    {
        return Comment::all();
    }
}
