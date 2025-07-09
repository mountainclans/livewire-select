@props([
    'values',
    'label' => false,
    'totalValues' => false,
    'placeholder' => __('Please, select'),
    'hidden' => false,
    'hideSearch' => false,
    'searchFunction' => false,
    'debounceDelay' => 300,
])

@php
    foreach ($attributes as $key => $value) {
        if (str_contains($key, 'wire:model')){
            $name = $value;
        }
    }

    $placeholderKey = is_string(array_keys($values)[0]) ? "" : 0;
@endphp


<div x-data="selectComponent(@js($placeholder ? ([$placeholderKey => $placeholder] + $values) : $values), '{{ $searchFunction }}')"
     x-init="init()"
     x-on:keydown="handleKeydown($event)"
     x-cloak
     class="relative"
>
    @if ($label)
        <div class="flex flex-wrap justify-between items-center">
            <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{ $label }}
            </label>

            @if(!empty($control))
                {{ $control }}
            @endif
        </div>
    @endif

    <select x-ref="hiddenSelect"
            hidden
            id="{{ $name }}"
        {{ $attributes->except(['class']) }}
    >
        <option value="{{ $placeholderKey }}">{{ $placeholder }}</option>

        @foreach ($values as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>

    <div @click.away="open = false"
        @class([
            'w-full',
            'hidden' => $hidden,
        ])
    >
        <div tabindex="0"
             x-ref="selectBox"
             @click="toggle()"
             class="imitate-select border border-gray-300 text-gray-600 dark:text-white font-bold rounded-lg focus:ring-blue-300 dark:focus:ring-blue-500 focus:border-blue-500 block w-full p-3 cursor-pointer bg-gray-50 dark:bg-gray-600 dark:border-gray-500 text-sm select-none"
        >
			<span x-text="selectedLabel ? selectedLabel : '{{ $placeholder }}'"
                  x-bind:class="{
                    '!font-normal text-gray-400': selectedKey == -1 || selectedKey == '{{ $placeholderKey }}'
                  }"
            ></span>
        </div>

        <div x-show="open"
             class="absolute z-10 w-full mt-1 bg-gray-400 dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-lg shadow-lg select-none overflow-auto max-h-[300px]"
        >
            <div @class([
                'bg-gray-200 dark:bg-gray-700 p-3 relative',
                'hidden' => $hideSearch,
            ])>
                <input type="text"
                       x-model="search"
                       x-ref="searchInput"
                       placeholder="{{ __('Search...') }}"
                       @input.debounce.{{ $debounceDelay }}ms="updateSearch()"
                       class="w-full border border-gray-300 p-2 rounded text-sm"
                />

                <template x-if="search.length">
                    <button type="button" @click="clearSearch()"
                            class="absolute w-5 h-5 right-5 top-5 text-gray-600 dark:text-white h-100 inline-flex items-center justify-center select-none"
                            wire:loading.class="hidden"
                    >
                        ✕
                    </button>
                </template>
            </div>

            @if ($totalValues && $totalValues > count($values))
                <div class="text-sm text-gray-800 dark:text-white bg-gray-200 dark:bg-gray-700 italic px-3 pb-1">
                    {{ __('Show') }}

                    <span x-text="filteredOptionsLength > 0 ? filteredOptionsLength : initialOptionsLength"></span>

                    {{ __('of :total. Use search input for select another values.', ['total' => $totalValues]) }}
                </div>
            @endif

            <ul class="max-h-60 overflow-auto bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-sm">
                <template x-for="(item, index) in Object.entries(filteredOptions)" :key="item[0]">
                    <li @click="selectOption(item[0], item[1])"
                        :class="{
                            'p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-primary-600 dark:hover:text-gray-100 select-none': true,
                            'bg-blue-500 dark:bg-blue-800 text-white': selectedKey === item[0],
                            '!text-gray-400': '{{ $placeholderKey }}' == item[0]
                        }"
                    >
                        <span x-text="item[1]"></span>
                    </li>
                </template>
            </ul>
        </div>
    </div>

    @error($name)
    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
    @enderror
</div>
