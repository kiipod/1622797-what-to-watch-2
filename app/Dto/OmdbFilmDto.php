<?php

namespace App\Dto;

class OmdbFilmDto
{
    /**
     * @param string|null $title
     * @param string|null $released
     * @param string|null $runTime
     * @param string|null $genres
     * @param string|null $director
     * @param string|null $actors
     * @param string|null $description
     * @param string|null $posterImage
     * @param string|null $rating
     * @param string|null $scoresCount
     */
    public function __construct(
        public ?string $title,
        public ?string $released,
        public ?string $runTime,
        public ?string $genres,
        public ?string $director,
        public ?string $actors,
        public ?string $description,
        public ?string $posterImage,
        public ?string $rating,
        public ?string $scoresCount
    ) {
    }
}
