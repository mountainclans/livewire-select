export default function selectComponent(
    values,
    searchFunction = false,
    model
) {
    return {
        open: false,
        search: '',
        selectedLabel: '',
        selectedKey: -1,
        options: values,
        initialOptionsLength: Object.keys(values).length,
        filteredOptions: values,
        filteredOptionsLength: 0,
        total: -1,
        model,

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
            this.filteredOptions = this.options;
            this.filteredOptionsLength = 0;
            this.total = -1;
        },

        async updateSearch() {
            if ('' === this.search) {
                this.clearSearch();
                return;
            }

            if (searchFunction) {
                const response = await this.$wire[searchFunction](this.search, this.model);
                this.$nextTick(() => {
                    this.filteredOptions = response;
                    this.filteredOptionsLength = Object.keys(response).length.toString();
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

        init() {
            this.updateSelectedFromModel();

            this.$watch('model', () => {
                this.updateSelectedFromModel();
            });
        }
    };
}
