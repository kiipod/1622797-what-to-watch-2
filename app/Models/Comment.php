<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\CommentFactory;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $film_id
 * @property string $text
 * @property int|null $rating
 * @property int|null $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Film $film
 * @property-read User $user
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereFilmId($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereParentId($value)
 * @method static Builder|Comment whereRating($value)
 * @method static Builder|Comment whereText($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @method static CommentFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    /**
     * Получение фильма к которому принадлежит комментарий
     *
     * @return BelongsTo
     */
    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * Получение пользователя оставившего комментарий
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault(['name' => 'Гость']);
    }

    /**
     * Получение всех отзывов к фильму
     *
     * @param int $filmId
     * @return Collection
     */
    public function getFilmComment(int $filmId): Collection
    {
        return $this->with(['user:id,name'])
            ->select(['id', 'text', 'rating', 'created_at', 'user_id'])
            ->where(['film_id' => $filmId])
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
