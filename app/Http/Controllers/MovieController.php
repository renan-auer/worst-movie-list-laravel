<?php

namespace App\Http\Controllers;

use App\Models\Movie;

class MovieController extends Controller
{
    
    function getMaxAndMinIntervalWinProducers()
    {
       dd(Movie::all());
    }
}
