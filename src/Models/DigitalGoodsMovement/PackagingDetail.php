<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Models\Type;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * @version 2.0.1
 */
class PackagingDetail extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'packagingType',
        'quantity',
        'otherPackagingTypeTitle',
    ];

    protected array $casts = [
        'packagingType' => PackagingType::class,
    ];

    public function __construct(PackagingType $packagingType = null, int $quantity = null)
    {
        parent::__construct();

        if ($packagingType !== null) {
            $this->setPackagingType($packagingType);
        }

        if ($quantity !== null) {
            $this->setQuantity($quantity);
        }
    }

    /**
     * @return PackagingType|null Είδος Συσκευασίας
     * @version 2.0.1
     */
    public function getPackagingType(): ?PackagingType
    {
        return $this->get('packagingType');
    }

    /**
     * @param PackagingType $packagingType Είδος Συσκευασίας
     * @version 2.0.1
     */
    public function setPackagingType(PackagingType $packagingType): static
    {
        return $this->set('packagingType', $packagingType);
    }

    /**
     * @return int|null Ποσότητα Συσκευασίας
     * @version 2.0.1
     */
    public function getQuantity(): ?int
    {
        return $this->get('quantity');
    }

    /**
     * @param int $quantity Ποσότητα Συσκευασίας
     * @version 2.0.1
     */
    public function setQuantity(int $quantity): static
    {
        return $this->set('quantity', $quantity);
    }

    /**
     * @return string|null Τίτλος για Λοιπά Είδη Συσκευασίας
     * @version 2.0.1
     */
    public function getOtherPackagingTypeTitle(): ?string
    {
        return $this->get('otherPackagingTypeTitle');
    }

    /**
     * @param string|null $otherPackagingTypeTitle Τίτλος για Λοιπά Είδη Συσκευασίας (max 150 χαρακτήρες)
     * @version 2.0.1
     */
    public function setOtherPackagingTypeTitle(?string $otherPackagingTypeTitle): static
    {
        return $this->set('otherPackagingTypeTitle', $otherPackagingTypeTitle);
    }
}