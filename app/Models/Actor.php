<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\ActorFactory;

/**
 * App\Models\Actor
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Film> $films
 * @property-read int|null $films_count
 * @method static Builder|Actor newModelQuery()
 * @method static Builder|Actor newQuery()
 * @method static Builder|Actor query()
 * @method static Builder|Actor whereCreatedAt($value)
 * @method static Builder|Actor whereId($value)
 * @method static Builder|Actor whereName($value)
 * @method static Builder|Actor whereUpdatedAt($value)
 * @method static ActorFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class Actor extends Model
{
    use HasFactory;

    protected $table = 'actors';

    /**
     * Получение фильмов, в которых снимался актер
     *
     * @return BelongsToMany
     */
    public function films(): BelongsToMany
    {
        return $this->belongsToMany(
            Film::class,
            'film_actors',
            'film_id',
            'actor_id'
        );
    }
}
