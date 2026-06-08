<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\Log;

/**
 * Resolves an IP address to a country using the local MaxMind GeoLite2 database.
 *
 * Offline, no external calls, no rate limits. Returns ['code' => 'KI', 'name' =>
 * 'Kiribati'] or nulls for local/private/unresolvable IPs. NEVER throws — a
 * lookup failure must not break audit logging.
 *
 * Requires:
 *   composer require geoip2/geoip2
 *   the GeoLite2-Country.mmdb file (see install notes) at the path below.
 */
class GeoLocator
{
    /** Path to the GeoLite2 country database. */
    protected function databasePath(): string
    {
        // storage/app/geoip/GeoLite2-Country.mmdb
        return storage_path('app/geoip/GeoLite2-Country.mmdb');
    }

    /**
     * @return array{code: ?string, name: ?string}
     */
    public function lookup(?string $ip): array
    {
        $empty = ['code' => null, 'name' => null];

        if (! $ip || $this->isLocalOrPrivate($ip)) {
            return $empty;
        }

        $path = $this->databasePath();
        if (! is_file($path)) {
            // DB not installed yet — fail silently, audit row still saves.
            return $empty;
        }

        try {
            $reader = new Reader($path);
            $record = $reader->country($ip);

            return [
                'code' => $record->country->isoCode,        // e.g. 'KI'
                'name' => $record->country->name,           // e.g. 'Kiribati'
            ];
        } catch (\Throwable $e) {
            // AddressNotFoundException, corrupt DB, etc. — never propagate.
            return $empty;
        }
    }

    /**
     * Loopback, private ranges, and reserved addresses never geolocate.
     */
    protected function isLocalOrPrivate(string $ip): bool
    {
        if (in_array($ip, ['127.0.0.1', '::1'], true)) {
            return true;
        }

        // FILTER_FLAG_NO_PRIV_RANGE | NO_RES_RANGE → returns false for private/reserved
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}