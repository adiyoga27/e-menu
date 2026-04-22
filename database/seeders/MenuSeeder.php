<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('menus');

        $menus = [
            'Makanan' => [
                [
                    'name' => 'Nasi Goreng Spesial',
                    'description' => 'Nasi goreng dengan telur mata sapi, ayam suwir, dan kerupuk. Disajikan dengan acar timun dan tomat.',
                    'price' => 25000,
                    'is_ready' => true,
                    'image_keyword' => 'nasi+goreng+fried+rice',
                ],
                [
                    'name' => 'Mie Goreng Jawa',
                    'description' => 'Mie goreng khas Jawa dengan bumbu rempah pilihan, sayuran segar, dan telur dadar.',
                    'price' => 22000,
                    'is_ready' => true,
                    'image_keyword' => 'mie+goreng+fried+noodles',
                ],
                [
                    'name' => 'Ayam Bakar Madu',
                    'description' => 'Ayam bakar dengan olesan madu dan bumbu kecap. Disajikan dengan nasi putih, lalapan, dan sambal.',
                    'price' => 35000,
                    'is_ready' => true,
                    'image_keyword' => 'grilled+chicken+ayam+bakar',
                ],
                [
                    'name' => 'Soto Ayam',
                    'description' => 'Soto ayam kuah kuning dengan suwiran ayam, telur rebus, dan bihun. Disajikan dengan nasi dan sambal.',
                    'price' => 20000,
                    'is_ready' => true,
                    'image_keyword' => 'soto+ayam+chicken+soup',
                ],
                [
                    'name' => 'Nasi Uduk Komplit',
                    'description' => 'Nasi uduk gurih dengan ayam goreng, tempe orek, sambal, dan kerupuk.',
                    'price' => 28000,
                    'is_ready' => true,
                    'image_keyword' => 'nasi+uduk+coconut+rice',
                ],
                [
                    'name' => 'Gado-Gado',
                    'description' => 'Sayuran rebus segar dengan saus kacang, tahu, tempe, telur, dan lontong.',
                    'price' => 18000,
                    'is_ready' => true,
                    'image_keyword' => 'gado+gado+indonesian+salad',
                ],
                [
                    'name' => 'Bakso Urat Spesial',
                    'description' => 'Bakso urat jumbo dengan mie kuning, bihun, tahu, dan kuah kaldu sapi.',
                    'price' => 23000,
                    'is_ready' => true,
                    'image_keyword' => 'bakso+meatball+soup',
                ],
                [
                    'name' => 'Rendang Sapi',
                    'description' => 'Rendang daging sapi empuk dengan bumbu rempah khas Padang. Disajikan dengan nasi putih hangat.',
                    'price' => 38000,
                    'is_ready' => false,
                    'image_keyword' => 'rendang+beef+indonesian',
                ],
            ],
            'Minuman' => [
                [
                    'name' => 'Es Teh Manis',
                    'description' => 'Teh manis dingin yang menyegarkan dari daun teh pilihan.',
                    'price' => 5000,
                    'is_ready' => true,
                    'image_keyword' => 'iced+tea+sweet',
                ],
                [
                    'name' => 'Jus Jeruk Segar',
                    'description' => 'Jus jeruk segar tanpa tambahan air dan gula buatan.',
                    'price' => 12000,
                    'is_ready' => true,
                    'image_keyword' => 'fresh+orange+juice',
                ],
                [
                    'name' => 'Es Kopi Susu',
                    'description' => 'Kopi robusta pilihan dicampur susu segar dan gula aren. Disajikan dingin.',
                    'price' => 15000,
                    'is_ready' => true,
                    'image_keyword' => 'iced+coffee+latte',
                ],
                [
                    'name' => 'Jus Alpukat',
                    'description' => 'Jus alpukat creamy dengan susu coklat dan es serut.',
                    'price' => 15000,
                    'is_ready' => true,
                    'image_keyword' => 'avocado+juice+smoothie',
                ],
                [
                    'name' => 'Es Jeruk Nipis',
                    'description' => 'Air jeruk nipis segar dengan es batu dan madu.',
                    'price' => 8000,
                    'is_ready' => true,
                    'image_keyword' => 'lime+juice+drink',
                ],
                [
                    'name' => 'Teh Hangat',
                    'description' => 'Teh hangat dari seduhan daun teh premium.',
                    'price' => 4000,
                    'is_ready' => true,
                    'image_keyword' => 'hot+tea+cup',
                ],
                [
                    'name' => 'Air Mineral',
                    'description' => 'Air mineral kemasan 600ml.',
                    'price' => 4000,
                    'is_ready' => true,
                    'image_keyword' => 'mineral+water+bottle',
                ],
            ],
        ];

        foreach ($menus as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();

            if (! $category) {
                $this->command->warn("Category '{$categoryName}' not found. Skipping...");

                continue;
            }

            foreach ($items as $menuData) {
                $imagePath = $this->downloadImage(
                    $menuData['image_keyword'],
                    'menus'
                );

                Menu::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $menuData['name'],
                    ],
                    [
                        'description' => $menuData['description'],
                        'price' => $menuData['price'],
                        'is_ready' => $menuData['is_ready'],
                        'image' => $imagePath,
                    ]
                );

                $this->command->info("Menu '{$menuData['name']}' seeded.");
            }
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
