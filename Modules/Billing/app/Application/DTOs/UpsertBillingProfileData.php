<?php

namespace Modules\Billing\Application\DTOs;

readonly class UpsertBillingProfileData
{
    public function __construct(
        public ?string $legalName,
        public ?string $email,
        public ?string $phone,
        public ?string $country,
        public ?string $city,
        public ?string $addressLine,
        public ?string $postalCode,
        public ?string $taxId,
        public ?string $currency,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            legalName: isset($input['legal_name']) ? (string) $input['legal_name'] : null,
            email: isset($input['email']) ? (string) $input['email'] : null,
            phone: isset($input['phone']) ? (string) $input['phone'] : null,
            country: isset($input['country']) ? (string) $input['country'] : null,
            city: isset($input['city']) ? (string) $input['city'] : null,
            addressLine: isset($input['address_line']) ? (string) $input['address_line'] : null,
            postalCode: isset($input['postal_code']) ? (string) $input['postal_code'] : null,
            taxId: isset($input['tax_id']) ? (string) $input['tax_id'] : null,
            currency: isset($input['currency']) ? (string) $input['currency'] : null,
        );
    }
}
