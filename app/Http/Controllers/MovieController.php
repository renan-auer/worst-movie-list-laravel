<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Producer;

class MovieController extends Controller
{

    function getMaxAndMinIntervalWinProducers()
    {
        $intervals = $this->getIntervals();
        return array(
            "min" => $this->getMinIntervalsWinProducers($intervals),
            "max" => $this->getMaxIntervalsWinProducers($intervals)
        );
    }

    //Function that checks the intervals of each winning producer
    private function getIntervals()
    {
        $movies = Movie::orderBy('year')->get();
        $producers = Producer::all();

        $intervals = [];

        foreach ($producers as $producer) {
            foreach ($movies as $movie) {
                if ($movie->wasProducedByProducer($producer) && $movie->winner == true) {

                    foreach ($intervals as &$interval) {
                       
                        if ($interval["producer"] == $producer->name && !isset($interval["interval"])) {
                            $intervalNumber = intval($movie->year) - intval($interval['previousWin']);
                            $interval['interval'] = $intervalNumber;
                            $interval['followingWin'] = $movie->year;
                        }
                    }

                    array_push($intervals, [
                        'previousWin' =>  $movie->year,
                        'producer' =>  $producer->name,
                    ]);
                }
            }
        }

        return $intervals;
    }

    private function getMinIntervalsWinProducers($intervals)
    {
        $minIntervals = [];
        $minIntervalNumber = null;

        foreach ($intervals as $interval) {
            if (isset($interval["interval"]) && !$minIntervalNumber) {
                $minIntervalNumber = $interval["interval"];
                array_push($minIntervals, $interval);
            } else if (isset($interval["interval"]) && $interval["interval"] < $minIntervalNumber) {
                $minIntervalNumber = $interval["interval"];
                $minIntervals = [];
                array_push($minIntervals, $interval);
            }  else if (isset($interval["interval"]) && $interval["interval"] == $minIntervalNumber) {
                array_push($minIntervals, $interval);
            }
        }

        return $minIntervals;
    }

    private function getMaxIntervalsWinProducers($intervals)
    {
        $maxIntervals = [];
        $maxIntervalNumber = null;

        foreach ($intervals as $interval) {
            if (isset($interval["interval"]) && !$maxIntervalNumber) {
                $maxIntervalNumber = $interval["interval"];
                array_push($maxIntervals, $interval);
            } else if (isset($interval["interval"]) && $interval["interval"] > $maxIntervalNumber) {
                $maxIntervalNumber = $interval["interval"];
                $maxIntervals = [];
                array_push($maxIntervals, $interval);
            } else if (isset($interval["interval"]) && $interval["interval"] == $maxIntervalNumber) {
                array_push($maxIntervals, $interval);
            }
        }

        return $maxIntervals;
    }
}
