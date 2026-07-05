<?php

namespace Modules\Branding\Application\DTOs;

readonly class BrandingMutationData
{
    public function __construct(
        public ?string $name,
        public ?string $logoUrl,
        public ?string $faviconUrl,
        public ?string $primaryColor,
        public ?string $secondaryColor,
        public ?string $accentColor,
        public ?string $fontFamily,
        public ?string $themeMode,
        public array $extraTokens,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            name: isset($payload['name']) ? (string) $payload['name'] : null,
            logoUrl: isset($payload['logo_url']) ? (string) $payload['logo_url'] : null,
            faviconUrl: isset($payload['favicon_url']) ? (string) $payload['favicon_url'] : null,
            primaryColor: isset($payload['primary_color']) ? (string) $payload['primary_color'] : null,
            secondaryColor: isset($payload['secondary_color']) ? (string) $payload['secondary_color'] : null,
            accentColor: isset($payload['accent_color']) ? (string) $payload['accent_color'] : null,
            fontFamily: isset($payload['font_family']) ? (string) $payload['font_family'] : null,
            themeMode: isset($payload['theme_mode']) ? (string) $payload['theme_mode'] : null,
            extraTokens: isset($payload['extra_tokens']) && is_array($payload['extra_tokens']) ? $payload['extra_tokens'] : [],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'logo_url' => $this->logoUrl,
            'favicon_url' => $this->faviconUrl,
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'accent_color' => $this->accentColor,
            'font_family' => $this->fontFamily,
            'theme_mode' => $this->themeMode,
            'extra_tokens' => $this->extraTokens,
        ];
    }
}
