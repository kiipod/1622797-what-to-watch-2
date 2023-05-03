<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\GenreFactory;

/**
 * App\Models\Genre
 *
 * @property int $id
 * @property string $genre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Film> $films
 * @property-read int|null $films_count
 * @method static Builder|Genre newModelQuery()
 * @method static Builder|Genre newQuery()
 * @method static Builder|Genre query()
 * @method static Builder|Genre whereCreatedAt($value)
 * @method static Builder|Genre whereId($value)
 * @method static Builder|Genre whereTitle($value)
 * @method static Builder|Genre whereUpdatedAt($value)
 * @method static GenreFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class Genre extends Model
{
    use HasFactory;

    protected $table = 'genres';

    /**
     * Получение фильмов относящихся к жанру
     *
     * @return BelongsToMany
     */
    public function films(): BelongsToMany
    {
        return $this->belongsToMany(
            Film::class,
            'film_genres',
            'film_id',
            'genre_id'
        );
    }

    /**
     * Получение списка жанров
     *
     * @return Collection
     */
    public function getAllGenre(): Collection
    {
        return $this->all();
    }
}
