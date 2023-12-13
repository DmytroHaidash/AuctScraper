export default {
  name: 'SearchAutocomplete',
  props: {
    items: {
      type: Array,
      required: false,
      default: () => [],
    },
  },
  data() {
    return {
      search: '',
      results: [],
      isOpen: false,
    };
  },
  methods: {
    filterResults() {
      this.results = this.items.filter(item => item.toLowerCase().indexOf(this.search.toLowerCase()) > -1);
    },
    onChange() {
      this.filterResults();
      this.isOpen = true;
    },
    setResult(result) {
      this.search = result;
      this.isOpen = false;
    },
    handleClickOutside(event) {
      if (!this.$el.contains(event.target)) {
        this.isOpen = false;
      }
    }
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
  },
  destroyed() {
    document.removeEventListener('click', this.handleClickOutside);
  }
}