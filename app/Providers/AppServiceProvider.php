<?php

namespace App\Providers;

use App\Models\ImportedFile;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

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
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {

            $event->menu->addAfter('dataImport', [
                'key' => 'importedFiles',
                'text' => 'Imported Files',
                'icon' => 'far fa-folder',
            ]);

            $items = ImportedFile::all()->map(function (ImportedFile $importedFile) {
                return [
                    'text' => $importedFile['filename'],
                    'icon' => 'far fa-fw fa-file',
                    'url' => route('data.view', $importedFile)
                ];
            });

            $event->menu->addIn('importedFiles', ...$items);
        });
    }
}
