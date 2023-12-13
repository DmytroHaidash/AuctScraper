<template>
    <div>
        <v-select
                id="selectStyle"
                :options="options"
                :label="label"
                v-model="selected"
        ></v-select>
        <input type="hidden" :name="name" :value="this.selected ? this.selected.id : ''">
    </div>
</template>

<script>
  import vSelect from "vue-select";

  export default {
    props: {
      options: Array,
      label: {
        type: String,
        default: 'title',
      },
      name: String,
      value: {
        type: String,
        default: null,
      }
    },
    components: {
      vSelect,
    },
    data() {
      return {
        selected: '',
        selectedId: '',
      }
    },
    created() {
      if (this.value) {
        this.setSelected();
      }
    },
    methods: {
      setSelected() {
        this.selected = this.options.find((option)=> {
          return this.value == option.id;
        });
      }
    },
  }
</script>
