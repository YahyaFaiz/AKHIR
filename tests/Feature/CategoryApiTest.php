<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    public function test_categories_api_returns_expected_structure(): void
    {
        $response = $this->getJson('/api/categories');

        $response
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'group',
                    'name',
                    'hint',
                    'allow_manual',
                    'subcategories' => [
                        '*' => [
                            'id',
                            'name',
                            'allow_manual',
                        ],
                    ],
                ],
            ]);
    }
}
