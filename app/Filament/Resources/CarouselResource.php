<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarouselResource\Pages;
use App\Models\Carousel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CarouselResource extends Resource
{
    protected static ?string $model = Carousel::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Hero & Carousel';

    protected static ?string $modelLabel = 'Hero / Carousel';

    protected static ?string $pluralModelLabel = 'Hero & Carousel';

    protected static ?int $navigationSort = 10;

    public static array $heroPages = [
        'home' => 'Beranda',
        'properti' => 'Katalog Properti',
        'autoshow' => 'Autoshow',
        'search' => 'Pencarian',
        'rumah.index' => 'Rumah',
        'tanah.index' => 'Tanah',
        'mobil.index' => 'Mobil',
        'motor.index' => 'Motor',
        'about' => 'Tentang Kami',
        'pinjaman.index' => 'Pinjaman Dana',
        'ads.guide' => 'Pasang Iklan',
        'faq' => 'FAQ',
        'terms' => 'Syarat & Ketentuan',
        'privacy' => 'Kebijakan Privasi',
        'testimoni.index' => 'Testimoni',
        'testimoni.create' => 'Buat Testimoni',
        'profile.edit' => 'Profil',
        'careers.index' => 'Karir',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan Tampilan')
                    ->description('Pilih apakah item ini tampil sebagai hero halaman atau carousel konten beranda.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('placement')
                                    ->label('Jenis')
                                    ->options([
                                        'hero' => 'Hero Section',
                                        'content' => 'Carousel Konten Beranda',
                                    ])
                                    ->default('hero')
                                    ->live()
                                    ->required(),
                                Forms\Components\Select::make('page_key')
                                    ->label('Halaman Hero')
                                    ->options(self::$heroPages)
                                    ->searchable()
                                    ->visible(fn (Forms\Get $get): bool => $get('placement') === 'hero')
                                    ->required(fn (Forms\Get $get): bool => $get('placement') === 'hero'),
                            ]),
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar')
                            ->disk('public')
                            ->directory(fn (Forms\Get $get): string => $get('placement') === 'hero' ? 'hero-carousel' : 'carousel')
                            ->image()
                            ->imageEditor()
                            ->maxSize(4096)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Gunakan gambar landscape. Hero ideal 1920 x 1080.'),
                    ]),

                Forms\Components\Section::make('Konten Hero')
                    ->visible(fn (Forms\Get $get): bool => $get('placement') === 'hero')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Label kecil')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required(fn (Forms\Get $get): bool => $get('placement') === 'hero')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('text')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\ColorPicker::make('label_color')
                                    ->label('Warna Label')
                                    ->default('#0f172a'),
                                Forms\Components\ColorPicker::make('title_color')
                                    ->label('Warna Judul')
                                    ->default('#0f172a'),
                                Forms\Components\ColorPicker::make('text_color')
                                    ->label('Warna Deskripsi')
                                    ->default('#1f2937'),
                            ]),
                    ]),

                Forms\Components\Section::make('Tombol Hero')
                    ->description('Opsional. Isi maksimal dua tombol untuk slide hero.')
                    ->visible(fn (Forms\Get $get): bool => $get('placement') === 'hero')
                    ->schema([
                        Forms\Components\Repeater::make('buttons')
                            ->label('Tombol')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Label Tombol')
                                    ->maxLength(60)
                                    ->required(fn (Forms\Get $get): bool => filled($get('url'))),
                                Forms\Components\TextInput::make('url')
                                    ->label('Link Tombol')
                                    ->maxLength(2048)
                                    ->required(fn (Forms\Get $get): bool => filled($get('label'))),
                                Forms\Components\Select::make('variant')
                                    ->label('Gaya')
                                    ->options([
                                        'primary' => 'Utama',
                                        'secondary' => 'Outline',
                                    ])
                                    ->default('primary')
                                    ->required(),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\ColorPicker::make('background_color')
                                            ->label('Warna Tombol')
                                            ->default('#f3bd12'),
                                        Forms\Components\ColorPicker::make('text_color')
                                            ->label('Warna Teks')
                                            ->default('#08234c'),
                                    ]),
                            ])
                            ->columns(2)
                            ->maxItems(2)
                            ->defaultItems(0)
                            ->reorderable(false),
                    ]),

                Forms\Components\Section::make('Carousel Konten Beranda')
                    ->visible(fn (Forms\Get $get): bool => $get('placement') === 'content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('link_url')
                            ->label('Link Tujuan')
                            ->maxLength(2048)
                            ->helperText('Boleh berupa /nama-halaman, #bagian, atau URL lengkap.'),
                    ]),

                Forms\Components\Section::make('Publikasi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->required(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->orderBy('placement')->orderBy('page_key')->orderBy('sort_order'))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(64)
                    ->width(112),
                Tables\Columns\TextColumn::make('placement')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'hero' ? 'Hero' : 'Konten')
                    ->color(fn (string $state): string => $state === 'hero' ? 'primary' : 'success'),
                Tables\Columns\TextColumn::make('page_key')
                    ->label('Halaman')
                    ->formatStateUsing(fn (?string $state): string => $state ? (self::$heroPages[$state] ?? $state) : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('placement')
                    ->label('Jenis')
                    ->options([
                        'hero' => 'Hero Section',
                        'content' => 'Carousel Konten',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarousels::route('/'),
            'create' => Pages\CreateCarousel::route('/create'),
            'edit' => Pages\EditCarousel::route('/{record}/edit'),
        ];
    }
}
