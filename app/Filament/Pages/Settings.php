<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $title = 'Pengaturan Sistem';
    protected static ?string $slug = 'settings';
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        // Debug: Log the start of mounting
        Log::info('Mounting Settings page...');

        // Get all settings and log the count
        $settings = Setting::all();
        Log::info('Found ' . $settings->count() . ' settings');

        // Transform settings into key-value pairs
        foreach ($settings as $setting) {
            $key = "{$setting->group}.{$setting->key}";
            $this->data[$key] = $this->formatValue($setting);
            Log::info("Loading setting: {$key} = " . (is_string($setting->value) ? $setting->value : json_encode($setting->value)));
        }

        // Log the final data array
        Log::info('Data array: ' . json_encode($this->data));

        // Fill the form with the data
        $this->form->fill($this->data);
    }

    protected function formatValue($setting)
    {
        if ($setting->value === null) {
            return null;
        }

        return match($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($setting->value) ?
                (str_contains($setting->value, '.') ? (float) $setting->value : (int) $setting->value)
                : $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('general.site_name')
                                    ->label('Nama Situs')
                                    ->required(),
                                Forms\Components\TextInput::make('general.site_tagline')
                                    ->label('Tagline Situs'),
                                Forms\Components\Select::make('general.site_language')
                                    ->label('Bahasa')
                                    ->options([
                                        'id' => 'Indonesia',
                                        'en' => 'English',
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Website')
                            ->schema([
                                Forms\Components\Toggle::make('website.maintenance_mode')
                                    ->label('Mode Maintenance'),
                                Forms\Components\TextInput::make('website.home_banner_title')
                                    ->label('Judul Banner'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Contact')
                            ->schema([
                                Forms\Components\TextInput::make('contact.contact_email')
                                    ->label('Email')
                                    ->email(),
                                Forms\Components\TextInput::make('contact.contact_phone')
                                    ->label('Telepon')
                                    ->tel(),
                            ]),
                    ])
                    ->columnSpanFull()
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Log::info('Saving settings...');
        Log::info('Data to save: ' . json_encode($this->data));

        foreach ($this->data as $key => $value) {
            if (str_contains($key, '.')) {
                [$group, $settingKey] = explode('.', $key, 2);

                Log::info("Saving setting: {$group}.{$settingKey} = " . (is_string($value) ? $value : json_encode($value)));

                Setting::updateOrCreate(
                    [
                        'group' => $group,
                        'key' => $settingKey,
                    ],
                    [
                        'value' => $value,
                        'type' => $this->determineType($value),
                        'display_name' => ucwords(str_replace('_', ' ', $settingKey))
                    ]
                );
            }
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }

    protected function determineType($value): string
    {
        return match(true) {
            is_bool($value) => 'boolean',
            is_numeric($value) => 'number',
            is_array($value) => 'json',
            default => 'text',
        };
    }
}
