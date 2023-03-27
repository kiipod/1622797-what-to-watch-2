<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Database\Factories\FilmDirectorFactory;

/**
 * App\Models\FilmDirector
 *
 * @method static Builder|FilmDirector newModelQuery()
 * @method static Builder|FilmDirector newQuery()
 * @method static Builder|FilmDirector query()
 * @property int $id
 * @property int $film_id
 * @property int $director_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static FilmDirectorFactory factory($count = null, $state = [])
 * @method static Builder|FilmDirector whereCreatedAt($value)
 * @method static Builder|FilmDirector whereDirectorId($value)
 * @method static Builder|FilmDirector whereFilmId($value)
 * @method static Builder|FilmDirector whereId($value)
 * @method static Builder|FilmDirector whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FilmDirector extends Model
{
    use HasFactory;

    protected $table = 'film_directors';
}
