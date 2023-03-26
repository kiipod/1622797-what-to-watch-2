<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\DirectorFactory;

/**
 * App\Models\Director
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Film> $films
 * @property-read int|null $films_count
 * @method static Builder|Director newModelQuery()
 * @method static Builder|Director newQuery()
 * @method static Builder|Director query()
 * @method static Builder|Director whereCreatedAt($value)
 * @method static Builder|Director whereId($value)
 * @method static Builder|Director whereName($value)
 * @method static Builder|Director whereUpdatedAt($value)
 * @method static DirectorFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class Director extends Model
{
    use HasFactory;

    protected $table = 'directors';

    /**
     * Получение фильмов которые снимал режиссер
     *
     * @return BelongsToMany
     */
    public function films(): BelongsToMany
    {
        return $this->belongsToMany(
            Film::class,
            'film_directors',
            'film_id',
            'director_id'
        );
    }
}
