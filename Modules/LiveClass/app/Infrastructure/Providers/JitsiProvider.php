<?php

namespace Modules\LiveClass\Infrastructure\Providers;

use Illuminate\Support\Str;
use Modules\LiveClass\Application\Contracts\JitsiProviderInterface;

class JitsiProvider implements JitsiProviderInterface
{
    public function buildMeeting(array $context): array
    {
        $baseUrl = rtrim((string) config('services.jitsi.base_url', 'https://meet.jit.si'), '/');
        $prefix = trim((string) config('services.jitsi.room_prefix', 'edus'), '-');

        $tenantId = (int) ($context['tenant_id'] ?? 0);
        $slug = Str::slug((string) ($context['title'] ?? 'live-class'));
        $uniq = Str::lower(Str::random(8));

        $roomName = sprintf('%s-t%d-%s-%s', $prefix, $tenantId, $slug, $uniq);

        return [
            'provider' => 'jitsi',
            'provider_meeting_id' => $roomName,
            'room_name' => $roomName,
            'meeting_url' => $baseUrl . '/' . $roomName,
            'meeting_password' => null,
            'meta' => [
                'lobby' => true,
                'recording' => false,
                'chat' => true,
            ],
        ];
    }
}
