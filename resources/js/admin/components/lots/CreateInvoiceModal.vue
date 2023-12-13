<template>
    <div class="invoice-modal">
        <p class="d-flex justify-content-center">Create invoice?</p>
        <div class="form-group">
            <label>Send to email</label>
            <input type="checkbox" v-model="sendToEmail">
        </div>

        <button @click="remove" class="btn btn-danger">
            Close
        </button>
        <button @click="createInvoice" class="btn btn-outline-primary">Create</button>
    </div>
</template>

<script>
  export default {
    name: "CreateInvoiceModal",
    props: {
      client_id: Number,
      lots: Array,
    },
    data() {
      return {
        sendToEmail: false,
      }
    },
    methods: {
      remove() {
        this.$emit('remove');
      },
      createInvoice() {
        axios.post('/admin/invoices/store', {
          client_id: this.client_id,
          lots: this.lots,
          sendToEmail: this.sendToEmail,
        }).then(response => {
          console.log(response);
          if(response.data.id){
          const url = '/admin/invoices/get-pdf/' + response.data.id;
          let fileLink = document.createElement('a');
          fileLink.href = url;
          fileLink.setAttribute('download', 'invoice №'+ response.data.id +'.pdf');

          document.body.appendChild(fileLink);

          fileLink.click();
          }
          this.$emit('remove');
        })
          .catch(error => {
            console.log(error);
          });
      },

      downloadInvoice() {

      }
    }
  }
</script>

<style scoped>

</style>