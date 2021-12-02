<?php
namespace App\Api\Version1\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait GetOrInsertFoodFields {

    protected static function getOrInsertFoodFields(array $foods, string $field): array {
        // Convert name list from foods by field name
        $names = static::getFoodFieldValues($foods, $field);

        // Get the exists names by name list
        $existsNames = static::select('name')
            ->whereIn('name', $names)
            ->where('user_id', Auth::id())
            ->pluck('name')
            ->toArray();

        // Get the not exists names by compare exists names
        $notExistsNames = array_diff($names, $existsNames);

        // Bulk insert the not exists names into database
        if (!empty($notExistsNames)) {
            $notExistsNames = collect($notExistsNames)
                ->map(fn($name) => [
                    'user_id'    => Auth::id(),
                    'name'       => $name,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ])
                ->toArray();

            static::insert($notExistsNames);
        }

        // Re-get the latest exists names and covert to `name: id` structure
        $ids = static::select('id', 'name')
            ->whereIn('name', $names)
            ->get()
            ->mapWithKeys(fn($row) => [
                $row->name => $row->id
            ])
            ->toArray();

        return $ids;
    }

    protected static function getFoodFieldValues(array $foods, string $field): array {
        $names = [];

        foreach($foods as $food) {
            if (!in_array($food[$field], $names)) {
                array_push($names, $food[$field]);
            }
        }

        return $names;
    }

}
