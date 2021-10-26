<?php
namespace App\Api\Version1\Supports;

use Spatie\Fractal\Fractal as BaseFractal;

class Fractal extends BaseFractal {

    protected $ok = true;

    /**
     * Add `ok($value)` method for set the status value.
     *
     * looks like `fractal()->ok(true)->item([])->etc`
     *
     * @param bool $value
     *
     * @return parent
     */
    public function ok(bool $value = true): parent {
        $this->ok = $value;

        return $this;
    }

    /**
     * Override the toArray() method.
     *
     * add `ok` keywords to response data, and make it like `{ ok: true, data: [] }`
     *
     * @return array
     */
    public function toArray(): array {
        return array_merge(
            ['ok' => $this->ok],
            parent::toArray()
        );
    }

}
