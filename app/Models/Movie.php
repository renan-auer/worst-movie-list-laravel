<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'title',
        'winner'
    ];

    public function producers() {
        return $this->belongsToMany(Producer::class);
    }

    public function studios() {
        return $this->belongsToMany(Studio::class);
    }

    public function wasProducedByProducer($producer) {
        return array_search($producer->id, array_column($this->producers->toArray(), "id")) !== false;
    }
}
