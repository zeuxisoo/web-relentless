<?php
namespace App\Models;

use Spatie\Tags\Tag as BaseTag;

class Tag extends BaseTag {

    public static function getLocale() {
        return env('TAG_DEFAULT_LOCALE', 'en');
    }

}
