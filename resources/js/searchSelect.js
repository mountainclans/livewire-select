export default function selectComponent(
    model,
    fieldName,
    live,
    searchFunction = false
) {
    return {
        renderKey: 0,
        open: false,
        search: '',
        selectedLabel: '',
        selectedKey: -1,
        options: {},
        defaultOptions: {},
        defaultOptionsLength: 0,
        filteredOptions: {},
        filteredOptionsLength: 0,
        total: -1,
        model,
        fieldName,
        live,

        get safeOptions() {
            return Object.entries(this.filteredOptions);
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.$refs.searchInput.focus();
                    }, 50);
                });
            }
        },

        selectOption(value, label) {
            const isEmptyValue = (value === '' || value === null);

            this.selectedKey = isEmptyValue ? -1 : value;
            this.selectedLabel = isEmptyValue ? '' : label;
            this.model = value;
            this.open = false;

            if (this.live) {
                this.$nextTick(async () => {
                    await this.$wire.set(this.fieldName, value);
                });
            }
        },

        handleKeydown(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (!this.open) {
                    this.open = true;
                }
            } else if (e.key === 'ArrowUp' || e.key === 'Escape' || e.key === 'Tab') {
                this.open = false;
            } else if (e.key === ' ' && document.activeElement === this.$refs.selectBox) {
                e.preventDefault();
                this.toggle();
            }
        },

        clearSearch() {
            this.search = '';
            this.filteredOptions = this.defaultOptions;
            this.total = -1;
        },

        async updateSearch() {
            if (!this.search.length) {
                this.clearSearch();
                return;
            }

            if (searchFunction) {
                const response = await this.$wire[searchFunction](this.search, this.model);
                this.$nextTick(() => {
                    this.filteredOptions = response;
                    this.total = response.total;
                });
            } else {
                this.filteredOptions = Object.fromEntries(
                    Object.entries(this.options).filter(([key, value]) => {
                        return value.toLowerCase().includes(this.search.toLowerCase()) || this.model == key;
                    })
                );
            }
        },

        updateSelectedFromModel() {
            const value = this.model;
            const isEmptyValue = (value === '' || value === null);

            this.selectedKey = isEmptyValue ? -1 : value;
            this.selectedLabel = this.options[value] || '';
        },

        init(values) {
            if (values === undefined) return;

            this.options = values;
            this.defaultOptions = values;
            this.filteredOptions = values;
            this.filteredOptionsLength = Object.keys(values).length;
            this.defaultOptionsLength = Object.keys(values).length;

            this.updateSearch(); // todo посмотреть на оптимизацию этого элемента, т.к. сейчас делается доп запрос при обновлении с бэкенда
            this.updateSelectedFromModel();

            this.$watch('model', () => {
                this.updateSelectedFromModel();
            });
        }
    };
}
