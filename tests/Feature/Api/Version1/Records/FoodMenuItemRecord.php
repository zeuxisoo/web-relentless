<?php
namespace Tests\Feature\Api\Version1\Records;

class FoodMenuItemRecord {

    public function __construct(
        public string $name = '',
        public string $unit = '',
        public string|int $quantity = ''
    ) {}

    public function toArray(): array {
        return [
            'name'     => $this->name,
            'unit'     => $this->unit,
            'quantity' => $this->quantity,
        ];
    }

}
