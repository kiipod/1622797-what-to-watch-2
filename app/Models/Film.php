<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\FilmFactory;

/**
 * App\Models\Film
 *
 * @property int $id
 * @property string $title
 * @property string $poster_image
 * @property string $preview_image
 * @property string $background_image
 * @property string $background_color
 * @property string $released
 * @property string $description
 * @property int $run_time
 * @property string $video_link
 * @property string $preview_video_link
 * @property string $imdb_id
 * @property mixed|null $status
 * @property string $rating
 * @property int|null $scores_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Film newModelQuery()
 * @method static Builder|Film newQuery()
 * @method static Builder|Film query()
 * @method static Builder|Film whereBackgroundColor($value)
 * @method static Builder|Film whereBackgroundImage($value)
 * @method static Builder|Film whereCreatedAt($value)
 * @method static Builder|Film whereDescription($value)
 * @method static Builder|Film whereId($value)
 * @method static Builder|Film whereImdbId($value)
 * @method static Builder|Film wherePosterImage($value)
 * @method static Builder|Film wherePreviewImage($value)
 * @method static Builder|Film wherePreviewVideoLink($value)
 * @method static Builder|Film whereRating($value)
 * @method static Builder|Film whereReleased($value)
 * @method static Builder|Film whereRunTime($value)
 * @method static Builder|Film whereStatus($value)
 * @method static Builder|Film whereTitle($value)
 * @method static Builder|Film whereUpdatedAt($value)
 * @method static Builder|Film whereVideoLink($value)
 * @property-read Collection<int, Actor> $actors
 * @property-read int|null $actors_count
 * @property-read Collection<int, Director> $director
 * @property-read int|null $director_count
 * @property-read Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @property-read Collection<int, Genre> $genres
 * @property-read int|null $genres_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static FilmFactory factory($count = null, $state = [])
 * @method static Builder|Film whereScoresCount($value)
 * @mixin Eloquent
 */
class Film extends Model
{
    use HasFactory;

    protected $table = 'films';

    public $fillable = [
        'rating',
        'scores_count',
        'title',
        'poster_image',
        'preview_image',
        'background_image',
        'background_color',
        'video_link',
        'preview_video_link',
        'description',
        'director',
        'actors',
        'run_time',
        'released',
        'status',
        'imdb_id'
    ];

    /**
     * Получение пользователей у которых фильм в Избранном
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'favorites',
            'user_id',
            'film_id'
        );
    }

    /**
     * Получение списка жанров фильма
     *
     * @return BelongsToMany
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(
            Genre::class,
            'film_genres',
            'film_id',
            'genre_id'
        );
    }

    /**
     * Получение списка актеров к фильму
     *
     * @return BelongsToMany
     */
    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(
            Actor::class,
            'film_actors',
            'film_id',
            'actor_id'
        );
    }

    /**
     * Получение списка режиссеров к фильму
     *
     * @return BelongsToMany
     */
    public function director(): BelongsToMany
    {
        return $this->belongsToMany(
            Director::class,
            'film_directors',
            'film_id',
            'director_id'
        );
    }

    /**
     * Получение списка комментов к фильму
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
