<?php

namespace App\Modules\JobCategory;

use App\Http\Controllers\Controller;
use App\Modules\JobCategory\Requests\CreateJobCategoryRequest;
use App\Modules\JobCategory\Requests\UpdateJobCategoryRequest;
use App\Modules\JobCategory\Resources\JobCategoryResource;
use App\Modules\JobCategory\Services\JobCategoryService;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    public function __construct(private JobCategoryService $jobCategoryService) {}

    /**
     * Display a listing of job categories.
     */
    public function index(Request $request)
    {
        $result = $this->jobCategoryService->getAll($request->all());
        return successJsonResponse(
            data_get($result, 'data'),
            __('job_category.success.get_all_job_categories'),
            data_get($result, 'meta')
        );
    }

    /**
     * Display all job categories with specific language translation.
     */
    public function getAllWithLanguage(string $languageCode)
    {
        $result = $this->jobCategoryService->getAll(['lang' => $languageCode]);
        return successJsonResponse(
            data_get($result, 'data'),
            __('job_category.success.get_all_job_categories_with_language')
        );
    }

    /**
     * Store a newly created job category.
     */
    public function store(CreateJobCategoryRequest $request)
    {
        $jobCategory = $this->jobCategoryService->create($request->validated());
        return successJsonResponse(
            new JobCategoryResource($jobCategory),
            __('job_category.success.create_job_category'),
            HttpStatusCodeEnum::Created->value
        );
    }

    /**
     * Display the specified job category.
     */
    public function show(int $id)
    {
        $jobCategory = $this->jobCategoryService->getById($id);
        return successJsonResponse(
            new JobCategoryResource($jobCategory),
            __('job_category.success.get_job_category')
        );
    }

    /**
     * Display the specified job category by code.
     */
    public function showByCode(string $code)
    {
        $jobCategory = $this->jobCategoryService->getByCode($code);
        return successJsonResponse(
            new JobCategoryResource($jobCategory),
            __('job_category.success.get_job_category')
        );
    }

    /**
     * Display the specified job category with language translation.
     */
    public function showWithLanguage(string $code, string $languageCode)
    {
        $jobCategory = $this->jobCategoryService->getByCodeWithLanguage($code, $languageCode);
        return successJsonResponse(
            new JobCategoryResource($jobCategory),
            __('job_category.success.get_job_category_with_language')
        );
    }

    /**
     * Update the specified job category.
     */
    public function update(UpdateJobCategoryRequest $request, int $id)
    {
        $jobCategory = $this->jobCategoryService->update($id, $request->validated());
        return successJsonResponse(
            new JobCategoryResource($jobCategory),
            __('job_category.success.update_job_category')
        );
    }

    /**
     * Remove the specified job category.
     */
    public function destroy(int $id)
    {
        $this->jobCategoryService->delete($id);
        return successJsonResponse(
            [],
            __('job_category.success.delete_job_category')
        );
    }

    /**
     * Get job category name in a specific language.
     */
    public function getName(int $id, string $languageCode)
    {
        $name = $this->jobCategoryService->getJobCategoryNameInLanguage($id, $languageCode);

        if (!$name) {
            return errorJsonResponse(
                __('job_category.errors.job_category_not_found'),
                HttpStatusCodeEnum::Not_Found->value
            );
        }

        return successJsonResponse(
            ['id' => $id, 'language_code' => $languageCode, 'name' => $name],
            __('job_category.success.get_job_category_name')
        );
    }
}
