<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Requests;

class OffersListRequest extends BaseRequest
{
    public function search(string $search): self
    {
        return $this->addParam('search', $search);
    }

    /**
     * @param  array<int, int>  $countries
     */
    public function countries(array $countries): self
    {
        return $this->addParam('countries', implode(',', $countries));
    }

    /**
     * @param  array<int, int>  $categories
     */
    public function categories(array $categories): self
    {
        return $this->addParam('categories', implode(',', $categories));
    }

    /**
     * @param  array<int, int>  $tags
     */
    public function tags(array $tags): self
    {
        return $this->addParam('tags', implode(',', $tags));
    }

    public function goalsTypes(int $goalsTypes): self
    {
        return $this->addParam('goalsTypes', $goalsTypes);
    }

    public function onlyFeatured(bool $onlyFeatured = true): self
    {
        return $this->addParam('onlyFeatured', $onlyFeatured ? 'yes' : 'no');
    }

    public function onlyNew(bool $onlyNew = true): self
    {
        return $this->addParam('onlyNew', $onlyNew ? 'yes' : 'no');
    }
}
