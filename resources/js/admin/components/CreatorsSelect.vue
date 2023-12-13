<template>
    <div>
          <v-select
                  id="selectStyle"
                  :options="options"
                  :label="label"
                  v-model="selected"
                  @search="onInput"
          ></v-select>
        <input type="hidden" :name="name" :value="this.selected ? this.selected.id : ''">
    </div>
</template>

<script>
  import CustomSelect from "./CustomSelect";
  import vSelect from "vue-select";

  export default {
    props: {
      name: String,
      value: {
        type: String,
        default: null,
      }
    },
    components: {
      CustomSelect,
      vSelect
    },
    data() {
      return {
        label: 'title',
        options: [],
        selected: '',
      }
    },
    created() {
      if (this.value) {
        this.setSelected();
      }
    },
    methods: {
      async setSelected() {
        if (!this.options.length) {
          await axios.post('/admin/get-creators', {selectedId: this.value})
            .then(({data}) => {
              this.options = data;

            });
        }
        this.selected = this.options.find((option) => {
          return this.value == option.id;
        });
      },

      async onInput(val, loading ) {
        if(val.length === 0) {
          this.options = []
        } else {
        await axios
          .post('/admin/get-creators', {search: val})
          .then(({data}) => {
            this.options = data
          });
        }
      }
    }
  }
</script>

<style scoped>
</style>