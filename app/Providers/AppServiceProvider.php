<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $settings = \App\Models\Setting::firstOrCreate(
                ['id' => 1],
                [
                    'primary_color' => '#4F46E5',
                    'secondary_color' => '#10B981',
                    'bg_gradient_start' => '#f6d365',
                    'bg_gradient_end' => '#fda085',
                    'logo_image' => 'img/logo.png',
                    'slider_images' => ['img/slider.jpg'],
                    'slider_interval' => 5000,
                    'app_name' => 'Pengumuman Kelulusan MTsN 2 Pesawaran'
                ]
            );
            
            // Retrofit existing setting if slider_images is null
            if (empty($settings->slider_images)) {
                $settings->slider_images = ['img/slider.jpg'];
                $settings->save();
            }

            \Illuminate\Support\Facades\View::share('settings', $settings);
        } catch (\Exception $e) {
            // Do nothing if table doesn't exist yet
        }
    }
}
