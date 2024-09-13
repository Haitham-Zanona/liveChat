<?php
// app/Traits/AvatarTrait.php

namespace App\Traits;

use Unsplash\Photo;
use Illuminate\Support\Facades\Cache;

trait AvatarTrait
{
    public function getRandomAvatar($user)
    {
        $cacheKey = 'user_avatar_' . $user->id;

        // Check if avatar is cached
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $photo = Photo::random(['query' => 'person', 'orientation' => 'squarish']);
        $avatarUrl = $photo->urls['small'];
        Cache::put($cacheKey, $avatarUrl, now()->addDay());
        return $avatarUrl;
    }
}
