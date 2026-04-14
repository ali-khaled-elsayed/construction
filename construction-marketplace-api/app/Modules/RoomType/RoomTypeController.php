<?php

namespace App\Modules\RoomType;

use App\Http\Controllers\Controller;
use App\Modules\RoomType\Requests\CreateRoomTypeRequest;
use App\Modules\RoomType\Requests\UpdateRoomTypeRequest;
use App\Modules\RoomType\Resources\RoomTypeResource;
use App\Modules\RoomType\Services\RoomTypeService;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function __construct(private RoomTypeService $roomTypeService) {}

    /**
     * Display a listing of room types.
     */
    public function index(Request $request)
    {
        $result = $this->roomTypeService->getAll($request->all());
        return successJsonResponse(
            data_get($result, 'data'),
            __('room_type.success.get_all_room_types'),
            data_get($result, 'meta')
        );
    }

    /**
     * Display all room types with specific language translation.
     */
    public function getAllWithLanguage(string $languageCode)
    {
        $result = $this->roomTypeService->getAll(['lang' => $languageCode]);
        return successJsonResponse(
            data_get($result, 'data'),
            __('room_type.success.get_all_room_types_with_language')
        );
    }

    /**
     * Store a newly created room type.
     */
    public function store(CreateRoomTypeRequest $request)
    {
        $roomType = $this->roomTypeService->create($request->validated());
        return successJsonResponse(
            $roomType,
            __('room_type.success.create_room_type'),
            HttpStatusCodeEnum::Created->value
        );
    }

    /**
     * Display the specified room type.
     */
    public function show(int $id)
    {
        $roomType = $this->roomTypeService->getById($id);
        return successJsonResponse(
            $roomType,
            __('room_type.success.get_room_type')
        );
    }

    /**
     * Display the specified room type by code.
     */
    public function showByCode(string $code)
    {
        $roomType = $this->roomTypeService->getByCode($code);
        return successJsonResponse(
            $roomType,
            __('room_type.success.get_room_type')
        );
    }

    /**
     * Display the specified room type with language translation.
     */
    public function showWithLanguage(string $code, string $languageCode)
    {
        $roomType = $this->roomTypeService->getByCodeWithLanguage($code, $languageCode);
        return successJsonResponse(
            $roomType,
            __('room_type.success.get_room_type_with_language')
        );
    }

    /**
     * Update the specified room type.
     */
    public function update(UpdateRoomTypeRequest $request, int $id)
    {
        $roomType = $this->roomTypeService->update($id, $request->validated());
        return successJsonResponse(
            $roomType,
            __('room_type.success.update_room_type')
        );
    }

    /**
     * Remove the specified room type.
     */
    public function destroy(int $id)
    {
        $this->roomTypeService->delete($id);
        return successJsonResponse(
            [],
            __('room_type.success.delete_room_type')
        );
    }

    /**
     * Get room type name in a specific language.
     */
    public function getName(int $id, string $languageCode)
    {
        $name = $this->roomTypeService->getRoomTypeNameInLanguage($id, $languageCode);

        if (!$name) {
            return errorJsonResponse(
                __('room_type.errors.room_type_not_found'),
                HttpStatusCodeEnum::Not_Found->value
            );
        }

        return successJsonResponse(
            ['id' => $id, 'language_code' => $languageCode, 'name' => $name],
            __('room_type.success.get_room_type_name')
        );
    }
}
