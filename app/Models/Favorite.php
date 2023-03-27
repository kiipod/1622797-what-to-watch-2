<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\FavoriteFactory;

/**
 * App\Models\Favorite
 *
 * @property int $id
 * @property int $user_id
 * @property int $film_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Film> $films
 * @property-read int|null $films_count
 * @property-read User $user
 * @method static Builder|Favorite newModelQuery()
 * @method static Builder|Favorite newQuery()
 * @method static Builder|Favorite query()
 * @method static Builder|Favorite whereCreatedAt($value)
 * @method static Builder|Favorite whereFilmId($value)
 * @method static Builder|Favorite whereId($value)
 * @method static Builder|Favorite whereUpdatedAt($value)
 * @method static Builder|Favorite whereUserId($value)
 * @method static FavoriteFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorites';

    /**
     * Получение фильмов добавленных пользователем в Избранное
     *
     * @return BelongsToMany
     */
    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class);
    }

    /**
     * Получение пользователя добавившего фильм в Избранное
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
