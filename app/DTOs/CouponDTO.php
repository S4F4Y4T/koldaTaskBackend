<?php

namespace App\DTOs\V1;

use Illuminate\Http\Request;

class CouponDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $type,
        public readonly float $value,
        public readonly ?float $min_purchase,
        public readonly ?string $expires_at,
        public readonly ?int $usage_limit,
        public readonly bool $is_active,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            code: $request->validated('code'),
            type: $request->validated('type'),
            value: $request->validated('value'),
            min_purchase: $request->validated('min_purchase'),
            expires_at: $request->validated('expires_at'),
            usage_limit: $request->validated('usage_limit'),
            is_active: $request->validated('is_active') ?? true,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            type: $data['type'],
            value: $data['value'],
            min_purchase: $data['min_purchase'] ?? null,
            expires_at: $data['expires_at'] ?? null,
            usage_limit: $data['usage_limit'] ?? null,
            is_active: $data['is_active'] ?? true,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->min_purchase,
            'expires_at' => $this->expires_at,
            'usage_limit' => $this->usage_limit,
            'is_active' => $this->is_active,
        ], fn($value) => !is_null($value));
    }
}
