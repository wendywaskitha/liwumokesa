<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatisticsResource\Pages;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Review;
use App\Models\User;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;

class StatisticsResource extends Resource
{
    protected static ?string $model = Visit::class; // Using Visit model as base
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Statistik';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 91;
    protected static ?string $pluralLabel = 'Statistik';

    public static function getNavigationBadge(): ?string
    {
        return null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Filter Periode')
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->default(now()->subDays(30))
                                    ->required(),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Tanggal Akhir')
                                    ->default(now())
                                    ->required(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Pilihan Metrik')
                            ->schema([
                                Forms\Components\CheckboxList::make('metrics')
                                    ->label('Metrik yang Ditampilkan')
                                    ->options([
                                        'visitors' => 'Pengunjung',
                                        'bookings' => 'Pemesanan',
                                        'reviews' => 'Ulasan',
                                        'ratings' => 'Rating',
                                        'users' => 'Pengguna Baru',
                                        'revenue' => 'Pendapatan',
                                    ])
                                    ->columns(2)
                                    ->default(['visitors', 'bookings', 'reviews']),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('period')
                    ->label('Periode')
                    ->sortable(),
                Tables\Columns\TextColumn::make('destination_name')
                    ->label('Destinasi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('visitors')
                    ->label('Pengunjung')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('avg_rating')
                    ->label('Rating Rata-rata')
                    ->formatStateUsing(fn ($state) => number_format($state, 1))
                    ->sortable(),
                Tables\Columns\TextColumn::make('reviews_count')
                    ->label('Jumlah Ulasan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Jumlah Pemesanan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('revenue')
                    ->label('Pendapatan')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('destination_id')
                    ->label('Destinasi')
                    ->relationship('destination', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\Filter::make('period')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['end_date'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatistics::route('/'),
            'view' => Pages\ViewStatistics::route('/{record}'),
            'dashboard' => Pages\StatisticsDashboard::route('/dashboard'),
            'destinations' => Pages\DestinationStatistics::route('/destinations'),
            'users' => Pages\UserStatistics::route('/users'),
            'reviews' => Pages\ReviewStatistics::route('/reviews'),
            'bookings' => Pages\BookingStatistics::route('/bookings'),
        ];
    }

    // Helper method for getting visitor stats
    public static function getVisitorStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30);
        $endDate = $endDate ?: now();

        return Visit::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(DISTINCT user_id) as visitors')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M');
                return $item;
            });
    }

    // Helper method for getting destination stats
    public static function getDestinationStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30);
        $endDate = $endDate ?: now();

        return Destination::withCount([
                'visits' => function (Builder $query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                },
                'reviews' => function (Builder $query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                },
                'bookings' => function (Builder $query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                },
            ])
            ->withAvg('reviews', 'rating')
            ->orderBy('visits_count', 'desc')
            ->get();
    }

    // Helper method for getting review stats
    public static function getReviewStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30);
        $endDate = $endDate ?: now();

        return Review::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('ROUND(AVG(rating), 1) as avg_rating'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M');
                return $item;
            });
    }

    // Helper method for getting booking stats
    public static function getBookingStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30);
        $endDate = $endDate ?: now();

        return Booking::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M');
                return $item;
            });
    }

    // Helper method for getting user registration stats
    public static function getUserRegistrationStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30);
        $endDate = $endDate ?: now();

        return User::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M');
                return $item;
            });
    }

    // Get summary statistics
    public static function getSummaryStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30);
        $endDate = $endDate ?: now();

        return [
            'total_visitors' => Visit::whereBetween('created_at', [$startDate, $endDate])
                ->distinct('user_id')
                ->count('user_id'),

            'total_reviews' => Review::whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            'average_rating' => Review::whereBetween('created_at', [$startDate, $endDate])
                ->avg('rating') ?? 0,

            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            'total_revenue' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount'),

            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            'top_destination' => Destination::withCount([
                    'visits' => function (Builder $query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                ])
                ->orderBy('visits_count', 'desc')
                ->first(),
        ];
    }
}
