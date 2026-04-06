<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class CacheManagementService
{
    /**
     * Hapus semua cache aplikasi (cache API, cache menu,
     * cache parent-child, session cache, dsb).
     *
     * WARNING:
     * - Tidak menghapus Redis database lain (session, queue).
     * - Hanya menghapus kunci cache dari connection CACHE_CONNECTION.
     */
    public static function clearAppDataCache()
    {
        // Clear redis DB
        cache()->flush();
    }

    /**
     * Hapus cache berdasarkan prefix — jika ada kebutuhan khusus lain.
     */
    public static function clearPrefix(string $prefix): void
    {
        $connection = config('cache.stores.redis.connection', 'cache');
        $redis = Redis::connection($connection);

        $keys = $redis->keys($prefix . '*');
        if (!empty($keys)) {
            $redis->del($keys);
        }
    }
}