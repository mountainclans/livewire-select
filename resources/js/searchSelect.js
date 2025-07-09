export default function selectComponent(
    values,
    searchFunction = false
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
            this.selectedLabel = label;
            this.$refs.hiddenSelect.value = value;
            this.$refs.hiddenSelect.dispatchEvent(new Event('change'));
            this.open = false;
            this.selectedKey = value ? value : -1;
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
                const response = await this.$wire[searchFunction](this.search, this.$refs.hiddenSelect.value);
                this.$nextTick(() => {
                    this.filteredOptions = response;
                    this.filteredOptionsLength = Object.keys(response).length.toString();
                    this.total = response.total;
                });
            } else {
                this.filteredOptions = Object.fromEntries(
                    Object.entries(this.options).filter(([key, value]) => {
                        return value.toLowerCase().includes(this.search.toLowerCase()) || this.$refs.hiddenSelect.value == key;
                    })
                );
            }
        },

        init() {
            this.$nextTick(() => {
                const selectEl = this.$refs.hiddenSelect;
                if (selectEl.value && this.options[selectEl.value]) {
                    this.selectedLabel = this.options[selectEl.value];
                    this.selectedKey = selectEl.value;
                }
            });
        }
    };
}
