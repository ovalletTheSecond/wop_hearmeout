<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SeenCrushService
{
    protected const COOKIE_NAME = 'seen_crushes';
    protected const COOKIE_LIFETIME = 43200; // 30 days in minutes

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function markAsSeen(int $crushId): void
    {
        $seenCrushes = $this->getSeenCrushes();
        if (!in_array($crushId, $seenCrushes)) {
            $seenCrushes[] = $crushId;
            $this->storeSeenCrushes($seenCrushes);
        }
    }

    public function getSeenCrushes(): array
    {
        $cookie = $this->request->cookie(self::COOKIE_NAME);
        if (!$cookie) {
            return [];
        }

        try {
            $decoded = json_decode($cookie, true);
            return is_array($decoded) ? $decoded : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function storeSeenCrushes(array $crushIds): void
    {
        Cookie::queue(
            self::COOKIE_NAME,
            json_encode(array_values(array_unique($crushIds))),
            self::COOKIE_LIFETIME
        );
    }

    public function shouldExcludeSeen(): bool
    {
        return !config('app.dev_mode', false);
    }

    public function clearSeenCrushes(): void
    {
        Cookie::queue(Cookie::forget(self::COOKIE_NAME));
    }
}