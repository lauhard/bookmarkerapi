<?php

declare(strict_types=1);

namespace App\Application\Dto\Bookmark;

use App\Application\Validation\ValidatePropertiesTrait;
use App\Domain\Exception\ValidationException;

class BookmarkCreateUpdateDto
{
    use ValidatePropertiesTrait;
    public const REQUIRED_FIELDS = ['url', 'page_title', 'user_id'];
    public const ALLOWED_FIELDS = ['url', 'page_title', 'user_id', 'id'];

    public function __construct(
        public ?string $url = null,
        public ?string $page_title = null,
        public ?string $user_id = null,
        public ?string $id = null
    ) {}

    public static function fromArray(array $data, bool $isPatch = false): self
    {
        //if patch is true, we allow missing fields
        if (!$isPatch) {
            //check required properties
            $requiredFieldError = self::requiredProperties($data, self::REQUIRED_FIELDS);
            if (!empty($requiredFieldError)) {
                throw new ValidationException(errors: $requiredFieldError);
            }
        }

        $allowedFieldError = self::allowedProperties($data, self::ALLOWED_FIELDS);
        if (!empty($allowedFieldError)) {
            throw new ValidationException(errors: $allowedFieldError);
        }

        //validate properties
        return new self(
            url: $data['url'] ?? null,
            page_title: $data['page_title'] ?? null,
            user_id: $data['user_id'] ?? null,
            id: $data['id'] ?? null
        );
    }

    //patch methode - returns just the properties that are set
    //implement with filter function
    public function toUpdateArray(): array
    {
        //take all properties of the dto
        $properties = get_object_vars($this);
        //filter out null values
        $filteredProperties = array_filter($properties, fn($prop) => $prop !== null);
        //return the filtered properties
        return $filteredProperties;
    }




    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPageTitle(): string
    {
        return $this->page_title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }
}
