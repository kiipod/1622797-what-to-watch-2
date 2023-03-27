<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\FilmGenreFactory;

/**
 * App\Models\FilmGenre
 *
 * @property int $id
 * @property int $film_id
 * @property int $genre_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FilmGenre newModelQuery()
 * @method static Builder|FilmGenre newQuery()
 * @method static Builder|FilmGenre query()
 * @method static Builder|FilmGenre whereCreatedAt($value)
 * @method static Builder|FilmGenre whereFilmId($value)
 * @method static Builder|FilmGenre whereGenreId($value)
 * @method static Builder|FilmGenre whereId($value)
 * @method static Builder|FilmGenre whereUpdatedAt($value)
 * @method static FilmGenreFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class FilmGenre extends Model
{
    use HasFactory;

    protected $table = 'film_genres';
}
