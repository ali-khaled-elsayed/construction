<?php

namespace App\Modules\Language;

use App\Http\Controllers\Controller;
use App\Modules\Language\Requests\CreateLanguageRequest;
use App\Modules\Language\Requests\UpdateLanguageRequest;
use App\Modules\Language\Resources\LanguageCollection;
use App\Modules\Language\Resources\LanguageResource;
use App\Modules\Language\Services\LanguageService;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;

class LanguageController extends Controller
{
    public function __construct(private LanguageService $languageService) {}

    /**
     * Display a listing of languages.
     */
    public function index()
    {
        $result = $this->languageService->getAll(request()->all());
        return successJsonResponse(
            data_get($result, 'data'),
            __('language.success.get_all_languages'),
            data_get($result, 'meta')
        );
    }

    /**
     * Store a newly created language.
     */
    public function store(CreateLanguageRequest $request)
    {
        $language = $this->languageService->create($request->validated());
        return successJsonResponse(
            new LanguageResource($language),
            __('language.success.create_language'),
            HttpStatusCodeEnum::Created->value
        );
    }

    /**
     * Display the specified language.
     */
    public function show(int $id)
    {
        $language = $this->languageService->getById($id);
        return successJsonResponse(
            new LanguageResource($language),
            __('language.success.get_language')
        );
    }

    /**
     * Display the specified language by code.
     */
    public function showByCode(string $code)
    {
        $language = $this->languageService->getByCode($code);
        return successJsonResponse(
            new LanguageResource($language),
            __('language.success.get_language')
        );
    }

    /**
     * Update the specified language.
     */
    public function update(UpdateLanguageRequest $request, int $id)
    {
        $language = $this->languageService->update($id, $request->validated());
        return successJsonResponse(
            new LanguageResource($language),
            __('language.success.update_language')
        );
    }

    /**
     * Remove the specified language.
     */
    public function destroy(int $id)
    {
        $this->languageService->delete($id);
        return successJsonResponse(
            [],
            __('language.success.delete_language')
        );
    }

    /**
     * Get the default language.
     */
    public function default()
    {
        $language = $this->languageService->getDefault();

        if (!$language) {
            return errorJsonResponse(
                __('language.errors.no_default_language'),
                HttpStatusCodeEnum::Not_Found->value
            );
        }

        return successJsonResponse(
            new LanguageResource($language),
            __('language.success.get_default_language')
        );
    }

    /**
     * Search languages by name.
     */
    public function search(string $searchTerm)
    {
        $languages = $this->languageService->search($searchTerm);
        return successJsonResponse(
            $languages,
            __('language.success.search_languages')
        );
    }
}
