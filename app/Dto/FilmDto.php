<?php

namespace App\Dto;

class FilmDto
{
    /**
     * @param string|null $title
     * @param string|null $posterImage
     * @param string|null $previewImage
     * @param string|null $backgroundImage
     * @param string|null $backgroundColor
     * @param string|null $videoLink
     * @param string|null $previewVideoLink
     * @param string|null $description
     * @param string|null $director
     * @param array|null $actors
     * @param array|null $genres
     * @param int|null $runTime
     * @param int|null $released
     * @param string|null $imdbId
     * @param string|null $status
     */
    public function __construct(
        public ?string $title,
        public ?string $posterImage,
        public ?string $previewImage,
        public ?string $backgroundImage,
        public ?string $backgroundColor,
        public ?string $videoLink,
        public ?string $previewVideoLink,
        public ?string $description,
        public ?string $director,
        public ?array $actors,
        public ?array $genres,
        public ?int $runTime,
        public ?int $released,
        public ?string $imdbId,
        public ?string $status
    ) {
    }
}
