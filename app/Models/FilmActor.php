<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\FilmActorFactory;

/**
 * App\Models\FilmActor
 *
 * @property int $id
 * @property int $film_id
 * @property int $actor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FilmActor newModelQuery()
 * @method static Builder|FilmActor newQuery()
 * @method static Builder|FilmActor query()
 * @method static Builder|FilmActor whereActorId($value)
 * @method static Builder|FilmActor whereCreatedAt($value)
 * @method static Builder|FilmActor whereFilmId($value)
 * @method static Builder|FilmActor whereId($value)
 * @method static Builder|FilmActor whereUpdatedAt($value)
 * @method static FilmActorFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class FilmActor extends Model
{
    use HasFactory;

    protected $table = 'film_actors';
}
