<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-c-shopping-bag';

    protected static ?string $navigationGroup = "Menu";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->minLength('5')->maxLength('15'),
                Forms\Components\TextInput::make('description')->required()->minLength('10')->maxLength('100'),
                Forms\Components\TextInput::make('price')->required(),
                Forms\Components\Select::make('category_id')
                    ->required()
                    ->label('CategoryName')
                    ->options(Category::all()->pluck('name' , 'id')),
//                    ->default('name')
//                    ->native(false),
                Forms\Components\FileUpload::make('image')->columnSpanFull()
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file , Forms\Get $get): string => (string)
                            $get('title') . "-" . Carbon::now()->format('Y-m-d') .".".
                            $file->getClientOriginalExtension()
//                                            ->prepend('custom-prefix-'),
                    )->required()->rule([
                        // 'dimensions:min_width=100,min_height:100'
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price')->searchable()->sortable()->numeric(),
                Tables\Columns\ImageColumn::make('image')->searchable()->sortable()->circular(),

            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
