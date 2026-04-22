<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('categories');

        $categories = [
            [
                'name' => 'Makanan',
                'is_active' => true,
                'image_keyword' => 'indonesian+food+plate',
            ],
            [
                'name' => 'Minuman',
                'is_active' => true,
                'image_keyword' => 'refreshing+drinks+beverages',
            ],
        ];

        foreach ($categories as $categoryData) {
            $imagePath = $this->downloadImage(
                $categoryData['image_keyword'],
                'categories'
            );

            Category::updateOrCreate(
                ['name' => $categoryData['name']],
                [
                    'is_active' => $categoryData['is_active'],
                    'image' => $imagePath,
                ]
            );

            $this->command->info("Category '{$categoryData['name']}' seeded.");
        }
    }

    /**
     * Download an image from loremflickr and save to storage.
     */
    private function downloadImage(string $keyword, string $folder): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withOptions(['allow_redirects' => true])
                ->get("https://loremflickr.com/640/480/{$keyword}");

            if ($response->successful()) {
                $filename = $folder.'/'.uniqid().'.jpg';
                Storage::disk('public')->put($filename, $response->body());

                return $filename;
            }
        } catch (\Exception $e) {
            $this->command->warn("Could not download image for '{$keyword}': {$e->getMessage()}");
        }

        return null;
    }
}
