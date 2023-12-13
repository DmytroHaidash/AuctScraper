<template>
    <div>
        <v-select
                id="selectStyle"
                :options="options"
                :label="label"
                :multiple="true"
                v-model="selected"
        ></v-select>
        <input type="hidden" :name="name" :value="select.id"
               v-for="(select, index) in selected" :key="index">

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
        type: Array,
        default: null,
      },
      multiple: {
        type: Boolean,
        default: false
      }
    },
    components: {
      vSelect,
    },
    data() {
      return {
        selected: [],
      }
    },
    created() {
      if (this.value) {
        this.setSelected();
      }
    },
    methods: {
      setSelected() {
        let ids = [];
        this.value.forEach(item => {
          ids.push(item.id);
        });
        console.log(ids);
         this.options.forEach((option)=> {
          if(ids.includes(option.id)) {
            this.selected.push(option);
          };

        });
      },
    },
  }
</script>
