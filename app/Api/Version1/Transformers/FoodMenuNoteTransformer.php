<?php
namespace App\Api\Version1\Transformers;

use League\Fractal\TransformerAbstract;

class FoodMenuNoteTransformer extends TransformerAbstract {

    public function transform(array $note): array {
        return [
            'date'   => $note['date'],
            'time'   => $note['time'],
            'tags'   => $note['tags'],
            'foods'  => $note['foods'],
            'remark' => $note['remark'],
        ];
    }

}
