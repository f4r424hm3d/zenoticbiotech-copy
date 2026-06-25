<?php

namespace App\Http\Requests\Concerns;

use App\Models\Category;

trait PreparesProductData
{
    /**
     * Normalize incoming product fields before validation:
     *  - resolve `category` / `categoryId` (id or name) to `category_id`
     *  - coerce `features` (newline string or array) into a clean array
     */
    protected function preparedProductPayload(): void
    {
        $merge = [];

        $categoryRaw = $this->input('category', $this->input('categoryId'));
        if ($categoryRaw !== null && $categoryRaw !== '') {
            // Resolve to an id when possible; otherwise pass through so the
            // `exists` rule produces a validation error.
            $merge['category_id'] = Category::resolveId($categoryRaw) ?? $categoryRaw;
        }

        if ($this->has('features')) {
            $merge['features'] = $this->normalizeFeatures($this->input('features'));
        }

        if ($merge) {
            $this->merge($merge);
        }
    }

    protected function normalizeFeatures(mixed $input): array
    {
        if (is_array($input)) {
            $items = array_map(fn ($f) => trim((string) $f), $input);
        } elseif (is_string($input)) {
            $items = array_map('trim', preg_split('/\r\n|\r|\n/', $input) ?: []);
        } else {
            return [];
        }

        return array_values(array_filter($items, fn ($f) => $f !== ''));
    }
}
