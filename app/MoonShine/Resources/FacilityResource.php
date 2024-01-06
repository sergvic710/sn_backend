<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;

use Leeto\MoonShine\Fields\BelongsTo;
use Leeto\MoonShine\Fields\BelongsToMany;
use Leeto\MoonShine\Fields\Number;
use Leeto\MoonShine\Fields\Text;
use Leeto\MoonShine\Resources\Resource;
use Leeto\MoonShine\Fields\ID;
use Leeto\MoonShine\Decorations\Block;
use Leeto\MoonShine\Actions\FiltersAction;

class FacilityResource extends Resource
{
	public static string $model = 'App\Models\Facility';

	public static string $title = 'Объекты';

	public function fields(): array
	{
		return [
		    Block::make('form-container', [
                ID::make()->sortable(),
                Number::make('Код', 'code'),
                Text::make('Имя', 'name')->required(),
                Text::make('Адрес', 'address')->required(),
                BelongsToMany::make('Клиенты', 'users', 'name')
		    ])
        ];
	}

	public function rules(Model $item): array
	{
	    return [
            'code' => ['int','required'],
            'name' => ['string', 'required'],
            'address' => ['string']
        ];
    }

    public function search(): array
    {
        return ['id'];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
