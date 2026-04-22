<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategorySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_seeder_creates_makanan_and_minuman(): void
    {
        Storage::fake('public');

        $this->seed(CategorySeeder::class);

        $this->assertDatabaseCount('categories', 2);
        $this->assertDatabaseHas('categories', ['name' => 'Makanan', 'is_active' => true]);
        $this->assertDatabaseHas('categories', ['name' => 'Minuman', 'is_active' => true]);
    }

    public function test_category_seeder_is_idempotent(): void
    {
        Storage::fake('public');

        $this->seed(CategorySeeder::class);
        $this->seed(CategorySeeder::class);

        $this->assertDatabaseCount('categories', 2);
    }

    public function test_menu_seeder_creates_menus_for_each_category(): void
    {
        Storage::fake('public');

        $this->seed(CategorySeeder::class);
        $this->seed(MenuSeeder::class);

        $makanan = Category::where('name', 'Makanan')->first();
        $minuman = Category::where('name', 'Minuman')->first();

        $this->assertNotNull($makanan);
        $this->assertNotNull($minuman);

        $this->assertEquals(8, $makanan->menus()->count());
        $this->assertEquals(7, $minuman->menus()->count());
        $this->assertDatabaseCount('menus', 15);
    }

    public function test_menu_seeder_creates_menus_with_correct_data(): void
    {
        Storage::fake('public');

        $this->seed(CategorySeeder::class);
        $this->seed(MenuSeeder::class);

        $this->assertDatabaseHas('menus', [
            'name' => 'Nasi Goreng Spesial',
            'price' => 25000,
            'is_ready' => true,
        ]);

        $this->assertDatabaseHas('menus', [
            'name' => 'Es Teh Manis',
            'price' => 5000,
            'is_ready' => true,
        ]);

        $this->assertDatabaseHas('menus', [
            'name' => 'Rendang Sapi',
            'price' => 38000,
            'is_ready' => false,
        ]);
    }

    public function test_menu_seeder_is_idempotent(): void
    {
        Storage::fake('public');

        $this->seed(CategorySeeder::class);
        $this->seed(MenuSeeder::class);
        $this->seed(MenuSeeder::class);

        $this->assertDatabaseCount('menus', 15);
    }
}
