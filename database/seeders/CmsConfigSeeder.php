<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            ['key' => 'app_name', 'value' => 'DAY-RENT'],
            ['key' => 'hero_title', 'value' => 'CMS PENYEWAAN BARANG HARIAN UNIVERSAL'],
            ['key' => 'hero_subtitle', 'value' => 'Semua kebutuhan barang dan kendaraan yang kamu perlukan ada di sini.'],
            ['key' => 'hero_button_text', 'value' => 'SEWA SEKARANG'],
            ['key' => 'hero_button_url', 'value' => '/catalog'],
            ['key' => 'hero_bg_image', 'value' => 'default_hero_bg.jpg'],
        ];

        foreach ($configs as $config) {
            DB::table('cms_configs')->updateOrInsert(['key' => $config['key']], $config);
        }
    }
}
