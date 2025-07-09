# Livewire Select Component

## Установка

Установите пакет с помощью Composer:

```bash
composer require mountainclans/livewire-select
```

Добавьте в файл проекта `resources/js/app.js` строки

```js
import selectComponent from '../../vendor/mountainclans/livewire-select/resources/js/searchSelect';
Alpine.data('selectComponent', selectComponent);
```

Добавьте в файл проекта `resources/js/app.css` строки:

```
@import '../../vendor/mountainclans/livewire-select/resources/css/searchSelect.css';
```

_Обратите внимание, что для корректной стилизации в вашем проекте должен использоваться TailwindCSS._

Добавьте в `tailwind.config.js` в секцию `content`:

```js
'./vendor/mountainclans/livewire-select/resources/views/**/*.blade.php'
```

---
Опционально, Вы можете опубликовать `views` для их переопределения:

```bash
php artisan vendor:publish --tag="livewire-select-views"
```

## Использование

### Простое использование

```bladehtml
<x-ui.select wire:model="modelId"
             :hide-search="true"
             :label="__('Model *')"
             :placeholder="__('Select model')"
             :values="$modelsArray"
/>
```

Если у Вас небольшое количество опций для выбора, установите `:hide-search="true"`, чтобы отключить функциональность поисковой строки.

### Продвинутое использование с внешней функцией поиска и слотом

Компонент может обновлять значения при вводе пользователем текста, с которым должны совпадать допустимые значения выбора: 

```bladehtml
<x-ui.select wire:model="modelId"
             :values="$modelsArray"
             :hidden="$createNewProvider"
             :label="__('Model *')"
             :placeholder="__('Select model')"
             search-function="searchModels"
             :total-values="$totalModels"
>
    <x-slot:control>
        В этот slot можно передать любую вёрстку, 
        в т.ч. управляемые Livewire компоненты, 
        такие как toggle
    </x-slot:control>
</x-ui.select>
```

Пример функции поиска, расположенной в родительском Livewire-компоненте:

```php 
public function searchModels(string $search, string $selectedValueId): array
{
    $query = Model::query();

    if (!empty($search)) {
        $query
        ->where('company_name', 'like', "%$search%")
        ->orWhere('id', '=', "$selectedValueId");
    }

    return $query
        ->orderBy('company_name')
        ->get()
        ->mapWithKeys(function (Provider $provider) {
            return [$provider->id => $provider->company_name];
        })
        ->toArray();
}
```

## Авторы

- [Vladimir Bajenov](https://github.com/mountainclans)
- [All Contributors](../../contributors)

## Лицензия

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
