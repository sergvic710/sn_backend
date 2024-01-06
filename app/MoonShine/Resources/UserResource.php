<?php

namespace App\MoonShine\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use Leeto\MoonShine\Fields\BelongsTo;
use Leeto\MoonShine\Fields\Email;
use Leeto\MoonShine\Fields\Password;
use Leeto\MoonShine\Fields\PasswordRepeat;
use Leeto\MoonShine\Fields\Text;
use Leeto\MoonShine\Resources\Resource;
use Leeto\MoonShine\Fields\ID;
use Leeto\MoonShine\Decorations\Block;
use Leeto\MoonShine\Actions\FiltersAction;

class UserResource extends Resource
{
	public static string $model = User::class;

	public static string $title = 'Клиенты';

	public function fields(): array
	{
		return [
		    Block::make('form-container', [
		        ID::make()->sortable(),
                Text::make('Имя', 'name')->required(),
                Email::make('Email', 'email')->required(),
                Password::make('Пароль', 'password'),
                PasswordRepeat::make('Повторите пароль', 'password_repeat')->hideOnIndex(),
                BelongsTo::make('Тип экспорта', 'exportType', 'name')
		    ])
        ];
	}

	public function rules(Model $item): array
	{
	    return [
            'name' => ['required'],
            'email' => ['required'],
            'password' => ['sometimes', 'nullable', 'min:6', 'required_with:password_repeat', 'same:password_repeat' ]
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
