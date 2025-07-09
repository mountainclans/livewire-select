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

```bladehtml

```

## Изменения

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Авторы

- [Vladimir Bajenov](https://github.com/mountainclans)
- [All Contributors](../../contributors)

## Лицензия

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
