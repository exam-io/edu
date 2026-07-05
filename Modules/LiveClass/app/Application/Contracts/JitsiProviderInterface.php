<?php

namespace Modules\LiveClass\Application\Contracts;

interface JitsiProviderInterface
{
    /**
     * @param array<string, mixed> $context
     * @return array{provider:string,provider_meeting_id:string,room_name:string,meeting_url:string,meeting_password:?string,meta:array<string,mixed>}
     */
    public function buildMeeting(array $context): array;
}
