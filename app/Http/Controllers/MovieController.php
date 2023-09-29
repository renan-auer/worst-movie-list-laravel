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
            "min" => $this->getMinIntervalWinProducers($intervals),
            "max" => $this->getMaxIntervalWinProducers($intervals)
        );
    }

    //Function that checks the intervals of each winning producer
    private function getIntervals() {
        $movies = Movie::all();
        $producers = Producer::all();

        $intervals = [];

        foreach ($producers as $producer) {
            foreach ($movies as $movie) {
                if ($movie->wasProducedByProducer($producer) && $movie->winner == true) {
                    if (isset($intervals[$producer->id])) {
                        $previousWin = $intervals[$producer->id]['previousWin'];
                        $interval = intval($movie->year) - intval($previousWin);
                        $intervals[$producer->id]['interval'] = $interval;
                        $intervals[$producer->id]['followingWin'] = $movie->year;
                    } else {

                        $intervals[$producer->id] = [
                            'previousWin' =>  $movie->year,
                            'producer' =>  $producer->name,
                        ];
                    }
                }
            }
        }

        return $intervals;
    }

    private function getMinIntervalWinProducers($intervals)
    {
        $minInterval = null;
        foreach ($intervals as $interval) {
            if (isset($interval["interval"]) && !$minInterval) {
                $minInterval = $interval;
            }
            if (isset($interval["interval"]) && $interval["interval"] < $minInterval["interval"]) {
                $minInterval = $interval;
            }
        }

        return $minInterval;
    }

    private function getMaxIntervalWinProducers($intervals)
    {
        $maxInterval = null;
        foreach ($intervals as $interval) {
            if (isset($interval["interval"]) && !$maxInterval) {
                $maxInterval = $interval;
            }
            if (isset($interval["interval"]) && $interval["interval"] > $maxInterval["interval"]) {
                $maxInterval = $interval;
            }
        }

        return $maxInterval;
    }
}
