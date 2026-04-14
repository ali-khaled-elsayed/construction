<?php

namespace App\Modules\JobCategory\Repositories\Contracts;

use App\Modules\Shared\Repositories\Contracts\BaseRepositoryInterface;

interface JobCategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all active job categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActive();

    /**
     * Get job categories with translations for a specific language.
     *
     * @param string $languageCode
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWithTranslations(string $languageCode);

    /**
     * Get job category with attributes.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByIdWithAttributes(int $id);

    /**
     * Get job category by code.
     *
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByCode(string $code);

    /**
     * Get job category by code with attributes.
     *
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByCodeWithAttributes(string $code);
}
