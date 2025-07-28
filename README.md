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

### Допустимые атрибуты тега

- `wire:model` - обязательное поле, к которому будет привязано выбранное в select значение. Вы также можете использовать `wire:model.live` для моментальной синхронизации выбора с бэкендом;
- `values` - массив значений в формате `ключ` - `лейбл`. Может быть php-выражением или переменной компонента Livewire;
- `hidden` - булево значение, скрывать ли селект. Если `true`, элемент будет скрыт, но всё еще будет отображаться элемент, переданный в **слоте** `control`. Полезно для создания динамических форм;
- `disabled` - возможно ли изменение выбора селекта;
- `label` - заголовок над селектом;
- `placeholder` - текст элемента, когда ни одно из переданных в `values` значений не выбрано;
- `search-function` - название функции поиска в родительском Livewire-компоненте (см. пример ниже);
- `total-values` - общее количество доступных в селекте элементов. Используется вместе с функцией поиска;
- `hide-search` - скрывать ли поисковую строку;

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
        в т.ч. blade-компоненты, привязанные к Livewire, 
        такие как toggle.
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
