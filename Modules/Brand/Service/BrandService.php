<?php

namespace Modules\Brand\Service;

use Modules\Brand\Repository\BrandRepository;
use Modules\Core\Service\CoreService;
use Modules\Core\Traits\ImageUpload;
use Reliese\Coders\Model\Model;

class BrandService extends CoreService
{
    use ImageUpload;

    public BrandRepository $brand_repository;

    public function __construct(BrandRepository $brand_repository)
    {
        $this->brand_repository = $brand_repository;
    }

    /**
     * Store a new attribute.
     *
     * @param  array<string, mixed>  $data  The data to create the attribute.
     * @return Model|null
     */
    public function store(array $data): ?Model
    {
        $processedData = collect($data)->except(['photo'])->toArray() + [
                'photo' => $this->verifyAndStoreImage($data['photo'] ?? null),
            ];
        return $this->brand_repository->create($processedData);
    }

    /**
     * Update an existing attribute.
     *
     * @param  int  $id  The attribute ID to update.
     * @param  array<string, mixed>  $data  The data for updating the attribute.
     * @return mixed
     */
    public function update(int $id, array $data): mixed
    {
        if (!empty($data['photo'])) {
            $processedData = collect($data)->except(['photo'])->toArray() + [
                    'photo' => $this->verifyAndStoreImage($data['photo']),
                ];
            return $this->brand_repository->update($id, $processedData);
        }

        return $this->brand_repository->update($id, $data);
    }
}
