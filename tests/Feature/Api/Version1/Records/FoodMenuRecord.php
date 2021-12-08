<?php
namespace Tests\Feature\Api\Version1\Records;

class FoodMenuRecord {

    public function __construct(
        public string $start_at,
        public string|array $foods = [],
        public string|array $tags = [],
        public string $remark = ''
    ) {}

    public function toArray(): array {
        $foods = $this->foods;

        if (is_array($foods)) {
            $foods = [];

            foreach($this->foods as $food) {
                $foods[] = $food->toArray();
            }
        }

        return [
            'start_at' => $this->start_at,
            'foods'    => $foods,
            'tags'     => $this->tags,
            'remark'   => $this->remark,
        ];
    }

}
