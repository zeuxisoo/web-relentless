<?php
namespace App\Api\Version1\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait GetOrInsertFoodFields {

    protected static function getOrInsertFoodFields(string $field, array $values): array {
        $names = $values[$field];

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

}
