<?php

namespace App\Dto;

class HtmlAcademyFilmDto
{
    /**
     * @param string|null $title
     * @param string|null $previewImage
     * @param string|null $backgroundImage
     * @param string|null $videoLink
     * @param string|null $previewVideoLink
     */
    public function __construct(
        public ?string $title,
        public ?string $previewImage,
        public ?string $backgroundImage,
        public ?string $videoLink,
        public ?string $previewVideoLink
    ) {
    }
}
