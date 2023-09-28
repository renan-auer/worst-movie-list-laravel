<?php

namespace App\Http\Services;

use Storage;
use App\Models\Studio;
use App\Models\Producer;
use App\Models\Movie;

class StarterDBService
{
    static function populateWithData()
    {
        //Delete all database content
        Studio::truncate();
        Producer::truncate();
        Movie::truncate();

        // Read CSV
        $csvFile = Storage::disk('local')->get('movielist.csv');
        $movieArray = str_getcsv($csvFile, "\n");

        //Remove first line
        array_shift($movieArray);

        //Extract data and save on database
        StarterDBService::getDistinctStudiosAndSave($movieArray);
        StarterDBService::getDistinctProducersAndSave($movieArray);
        StarterDBService::saveMovies($movieArray);
    }

    static function getDistinctStudiosAndSave($movieArray)
    {
        $studiosToSave = [];
        foreach ($movieArray as $value) {
            $studiosColumn = explode(";", $value)[2];
            $studiosArray = explode(",", $studiosColumn);
            foreach ($studiosArray as $studioName) {
                $studioName = trim($studioName, ' ');
                if (array_search($studioName, array_column($studiosToSave, 'name')) === false) {
                    array_push($studiosToSave, ['name' => $studioName]);
                }
            }
        }
        Studio::insert($studiosToSave);
    }

    static function getDistinctProducersAndSave($movieArray)
    {
        $producersToSave = [];
        foreach ($movieArray as $value) {
            $producersColumn = explode(";", $value)[3];
            $producersArray = explode(",", $producersColumn);
            foreach ($producersArray as $producerName) {
                $producerName = trim($producerName, ' ');
                if (array_search($producerName, array_column($producersToSave, 'name')) === false) {
                    array_push($producersToSave, ['name' => $producerName]);
                }
            }
        }
        Producer::insert($producersToSave);
    }

    static function saveMovies($movieArray)
    {
        $studios = Studio::all()->toArray();
        $producers = Producer::all()->toArray();
        foreach ($movieArray as $movieItem) {
            $movieToSave = explode(";", $movieItem);

            $movie = Movie::create(array(
                'year' => $movieToSave[0],
                'title' => $movieToSave[1],
                'winner' => $movieToSave[4] == 'yes'
            ));

            $idsProducers = StarterDBService::getIds($producers, explode(",", $movieToSave[2]));
            $idsStudios = StarterDBService::getIds($studios, explode(",", $movieToSave[3]));

            $movie->producers()->sync($idsProducers);
            $movie->studios()->sync($idsStudios);
        }
    }

    static function getIds($allList, $listToWithNames)
    {
        $ids = [];
        foreach ($listToWithNames as $name) {
            $key = array_search($name, array_column($allList, 'name'));
            array_push($ids, $allList[$key]['id']);
        }
        return $ids;
    }
}
