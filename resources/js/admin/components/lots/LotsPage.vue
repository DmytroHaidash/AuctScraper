<template>
  <div v-if="lots">
    <div id="loader-overlay" v-if="showLoader">
      <div class="loader"></div>
    </div>
    <div class="row mb-4">
      <div class="col pr-0">
        <input class="form-control" v-model="search" placeholder="search">
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-lg-6 pr-0">
        <input class="form-control" v-model="number" placeholder="Auction id or Id">
      </div>
      <div class="col-lg-5 pr-0">
        <div class="d-inline-flex form-group w-100">
          <label for="nullable" class="d-flex align-items-center" style="width: 150px;">Nullable field</label>
          <select v-model="nullable" id="nullable" class="form-control">
            <option value="">----</option>
            <option v-for="(value, title) in nullableFields" @click="changeSelectNullable(value)"
                    :selected="value == nullable">{{ title }}
            </option>
          </select>
        </div>
      </div>

      <div class="col-auto">
        <button class="btn btn-primary" @click.prevent="getAllLots(url)">Find</button>
      </div>

    </div>
    <div class="mb-4 d-inline-flex" v-if="lots.length">
      <div v-for="auction in auctions" :key="auction.id">
        <button class="btn btn-outline-primary mr-4" @click.prevent="getExport(auction.id)">Export {{
            auction.name
          }}
        </button>
      </div>
      <div>
        <button class="btn btn-outline-primary mr-4" @click.prevent="published(1)">Published</button>
        <button class="btn btn-outline-primary mr-4" @click.prevent="published(0)">Unpublished</button>
        <button class="btn btn-outline-primary mr-4" @click.prevent="publishedAdmin(1)">Published for admin</button>
        <button class="btn btn-outline-primary mr-4" @click.prevent="publishedAdmin(0)">Unpublished for admin</button>
      </div>
    </div>
    <div class="mb-4" v-if="client_id && hasSelectedItems">
      <button class="btn btn-outline-primary" @click.prevent="toggleModal=true">Generate Invoice</button>
    </div>
    <div class="mb-4 row" v-if="hasSelectedItems">
      <div class="col-1">
        <span>Change status</span>
      </div>
      <div class="col-3">
        <select v-model="selectedStatus" id="status" class="form-control">
          <option v-for="(value, title) in statuses" @click="changeSelectNullable(value)"
                  :selected="value == nullable">
            {{ title }}
          </option>
          <!--                @foreach(\App\Models\Lots::STATUSES as $status)-->
          <!--                <option value="{{ $status }}" {{ $status === $lot->status ? 'selected' : '' }}>{{ $status }}</option>-->
          <!--                @endforeach-->
        </select>
      </div>
      <div class="col-1">
        <button class="btn btn-outline-primary" :disabled="!selectedStatus" @click.prevent="changeItemStatus">
          Change
        </button>
      </div>

      <button class="btn btn-outline-primary" @click.prevent="getImagesArchive">
        Download images
      </button>
    </div>

    <div class="mb-4">
      <input style="display: none" type="file" @change="handleFileChange" accept=".xlsx, .xls" ref="fileInput"/>
      <button class="btn btn-primary" @click="uploadFile" :disabled="disableImport">Upload xml</button>
      <button class="btn btn-primary" @click="exportCatalog" :disabled="disableImport">Export catalog xml</button>
      <input style="display: none" type="file" @change="handleAuctionidFileChange" accept=".xlsx, .xls"
             ref="fileAuctionIdInput"/>
      <button class="btn btn-primary" @click="uploadAuctionIdFile" :disabled="disableImport">Import auction ids</button>
      <button class="btn btn-primary" @click="deleteAuctionIds" :disabled="disableDeleteAuctionIds"
              v-if="!hasSelectedItems">Delete all auction ids
      </button>
      <button class="btn btn-primary" @click="deleteAuctionIds" :disabled="disableDeleteAuctionIds"
              v-if="hasSelectedItems">Delete auction id in selected items
      </button>
      <div class="form-group mt-4">
        <label>Select all</label>
        <input type="checkbox" :checked="selectAll" @click="selectedAllItems">
        <span v-if="selectedItems.length">(now selected {{ selectedItems.length }} items)</span>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead class="threat">
        <tr>
          <td width="20">
            <input type="checkbox" :checked="isSelectedPage" @click="selectedCurrentPage">
          </td>
          <th @click.prevent="sorting('id')" class="ids">
            <div class="d-inline-flex">
              Id
              <div v-if="sort === 'id'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('number')">
            <div class="d-inline-flex">
              Auction id
              <div v-if="sort === 'number'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th width="100">
            <div class="d-inline-flex">
              Preview
            </div>
          </th>
          <th>
            <div class="d-inline-flex">
              Images
            </div>
          </th>
          <th @click.prevent="sorting('title')">
            <div class="d-inline-flex">
              Title
              <div v-if="sort === 'title'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('description')">
            <div class="d-inline-flex">
              Description
              <div v-if="sort === 'description'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('author')">
            <div class="d-inline-flex">
              Author
              <div v-if="sort === 'author'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('category')">
            <div class="d-inline-flex">
              Category
              <div v-if="sort === 'category'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('consigner')">
            <div class="d-inline-flex">
              Consigner
              <div v-if="sort === 'consigner'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('sale_price')" v-if="status == 'warehouse'">
            <div class="d-inline-flex">
              Sale price
              <div v-if="sort === 'sale_price'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('low_estimate')">
            <div class="d-inline-flex">
              Low estimate
              <div v-if="sort === 'low_estimate'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('high_estimate')">
            <div class="d-inline-flex">
              High estimate
              <div v-if="sort === 'high_estimate'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('starting_price')" width="150">
            <div class="d-inline-flex">
              Starting price
              <div v-if="sort === 'starting_price'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('status')">
            <div class="d-inline-flex">
              Status
              <div v-if="sort === 'status'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('buy_status')">
            <div class="d-inline-flex">
              Aviability
              <div v-if="sort === 'buy_status'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th @click.prevent="sorting('created_at')">
            <div class="d-inline-flex">
              Created
              <div v-if="sort === 'created_at'" class="ml-2">
                <svg width="16" height="16">
                  <use :href="icon"></use>
                </svg>
              </div>
            </div>
          </th>
          <th>
            <div class="d-inline-flex">Published</div>
          </th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(lot, index) in lots">
          <td width="20">
            <input :id="'select_'+index" type="checkbox" :checked="selectedItems.includes(lot.id)"
                   @change="addSelect(lot.id)">
          </td>
          <td class="ids">{{ lot.id }}</td>
          <td>{{ lot.number }}</td>
          <td>
            <img :src="lot.image" class="rounded-circle"
                 :alt="lot.name" style="width: 50px; height: 50px">
          </td>
          <td>{{ lot.count_images }}</td>
          <td><a :href="lot.edit">{{ lot.title }}</a></td>
          <td>{{ lot.description }}</td>
          <td>{{ lot.author }}</td>
          <td>
            <a :href="lot.edit_category">
              {{ lot.category }}
            </a>
          </td>
          <td>{{ lot.consigner }}</td>
          <td v-if="status == 'warehouse'">{{ lot.sale_price }}</td>
          <td>{{ lot.low_estimate }}</td>
          <td>{{ lot.high_estimate }}</td>
          <td>{{ lot.starting_price }}</td>
          <td>{{ lot.status }}</td>
          <td>{{ lot.buy_status }}</td>
          <td class="nobr">{{ lot.created }}</td>
          <td class="nobr">{{ lot.is_published }}</td>
          <td width="100" class="nobr">
            <a :href="lot.edit"
               class="btn btn-warning btn-squire rounded-circle">
              <i class="i-pencil"></i>
            </a>
            <button class="btn btn-danger btn-squire rounded-circle"
                    @click.prevent="deleteLot(lot, index)">
              <i class="i-trash"></i>
            </button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex h-10 justify-content-center align-items-center">
      <button class="btn btn-primary mr-4" @click="previousPage()" :disabled="!previous">Previous page</button>
      <p class="mr-4 mt-3">{{ currentPage }} / {{ lastPage }}</p>
      <button class="btn btn-primary" @click="nextPage()" :disabled="!next">Next page</button>
    </div>
    <create-invoice-modal :client_id="client_id" :lots="selectedItems" v-if="toggleModal" @remove="closeModal"/>
    <div class="modal-mask" v-if="toggleModal" @click="closeModal"></div>
  </div>
</template>

<script>
import CreateInvoiceModal from "./CreateInvoiceModal";

export default {
  name: "LotsPage",
  props: {
    in_status: String,
    with_export: {
      type: Boolean,
      default() {
        return false;
      }
    },
    client_id: {
      type: Number,
      default() {
        return null;
      }
    },
    consigner_id: {
      type: Number,
      default() {
        return null;
      }
    }
  },
  components: {
    CreateInvoiceModal,
  },
  data() {
    return {
      url: '/admin/get-lots',
      sort: 'created_at',
      order: 'asc',
      search: '',
      number: '',
      nullable: '',
      lots: [],
      next: '',
      previous: '',
      icon: '#asc',
      currentPage: 1,
      lastPage: 1,
      selectedItems: [],
      status: '',
      auctions: [],
      nullableFields: [],
      sendToEmail: false,
      toggleModal: false,
      showLoader: false,
      selectedStatus: null,
      statuses: {
        catalog: 'Catalog',
        auction: "Auction",
        "auction-2": "Auction Two",
        warehouse: "Warehouse",
        past: "Past",
      },
      allLotsIds: [],
      disableImport: false,
      disableDeleteAuctionIds: false,
      selectedPage: [],
      pageLotIds: [],
    }
  },
  created() {
    this.status = this.in_status;
    this.getAllLots();
    this.getAuctions();
  },
  methods: {
    uploadFile() {
      this.$refs.fileInput.click();
    },
    uploadAuctionIdFile() {
      this.$refs.fileAuctionIdInput.click();
    },
    deleteAuctionIds() {
      this.disableDeleteAuctionIds = true;
      if (window.confirm("Do you want delete all auction ids?")) {
        axios.post('/admin/delete-auction-ids', {ids: this.selectedItems})
            .then(response => {
              if (this.disableDeleteAuctionIds.length > 0) {
                alert('All auction ids was deleted for ' + this.disableDeleteAuctionIds.length + ' lots.');
              } else {
                alert('All auction ids was deleted.');
              }
              this.selectedItems = [];
              this.getLots(this.url)
              this.disableDeleteAuctionIds = false;
            });
      } else {
        this.disableDeleteAuctionIds = false;
      }
    },
    handleFileChange(event) {
      this.disableImport = true;
      const formData = new FormData();
      formData.append('file', event.target.files[0]);
      axios.post('/admin/import-lots', formData)
          .then(response => {
            this.getLots(this.url)
            this.disableImport = false;
            alert(response.data.count + ' Lots was created.');
          });
    },
    handleAuctionidFileChange(event) {
      this.disableImport = true;
      const formData = new FormData();
      formData.append('file', event.target.files[0]);
      axios.post('/admin/import-auction-ids', formData)
          .then(response => {
            this.getLots(this.url)
            this.disableImport = false;
            alert(response.data.count + ' Lots was updated.');
          });
    },
    getAuctions() {
      axios
          .get('/admin/get-auctions')
          .then(({data}) => {
            this.auctions = data
          })
    },
    getAllLotIds() {
      axios.post("/admin/get-lot-ids", {
        search: this.search,
        status: this.status,
        client_id: this.client_id,
        consigner_id: this.consigner_id,
        number: this.number,
        nullable: this.nullable,
      }).then(({data}) => {
        this.allLotsIds = data;
      })
    },
    selectedCurrentPage() {
      if (this.isSelectedPage) {
        this.selectedItems = this.selectedItems.filter((item) => !this.pageLotIds.includes(item));
      } else {
        this.pageLotIds.forEach((item) => {
          if (!this.selectedItems.includes(item)) {
            this.selectedItems.push(item);
          }
        });
      }
    },
    selectedAllItems() {
      if (this.selectAll) {
        this.selectedItems = [];
      } else {
        this.selectedItems = this.allLotsIds.slice();
      }
    },
    getLots(url) {
      axios
          .post(url, {
            sort: this.sort,
            order: this.order,
            search: this.search,
            status: this.status,
            client_id: this.client_id,
            consigner_id: this.consigner_id,
            number: this.number,
            nullable: this.nullable,
          })
          .then(({data}) => {
            this.pageLotIds = [];
            this.lots = data.items;
            this.lots.forEach((item) => {
              this.pageLotIds.push(item.id);
            })
            this.next = data.next;
            this.previous = data.previous;
            this.lastPage = data.last;
            this.currentPage = data.current;
          });
    },
    getAllLots() {
      this.getLots(this.url);
      this.getAllLotIds();
    },
    async changeItemStatus() {
      await axios
          .post('/admin/lots/changeStatus', {
            ids: this.selectedItems,
            status: this.selectedStatus,
          }).then((data) => {
            this.selectedItems = [];
            this.selectedStatus = [];
            this.lots = [];
            this.getAllLots();
          });
    },
    getImagesArchive() {
      this.showLoader = true;
      axios.post('/admin/get-images', {
        ids: this.selectedItems,
      }, {responseType: 'blob'})
          .then(response => {
            this.showLoader = false;
            if (response.data.error) {
              return;
            }
            const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/zip'}));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'images.zip');
            document.body.appendChild(link);
            link.click();

          });

    },
    sorting(sort) {
      if (this.sort === sort) {
        this.order = this.order === 'desc' ? 'asc' : 'desc';
      } else {
        this.sort = sort;
        this.order = 'asc';
      }
      this.getSortSvg();
      this.getLots(this.url)
    },
    async deleteLot(lot, index) {
      const conf = confirm('Sure?');

      if (conf) {
        await axios.delete(lot.destroy, lot)
            .then(resp => {
              this.lots.splice(index, 1);
            })
            .catch(error => {
              console.log(error);
            })
      }
    },
    getSortSvg() {
      this.icon = '#' + this.order;
    },
    nextPage() {
      this.getLots(this.next);
    },
    previousPage() {
      this.getLots(this.previous);
    },
    addSelect(id) {
      let index = this.selectedItems.indexOf(id);
      if (index !== -1) {
        this.selectedItems.splice(index, 1);
      } else {
        this.selectedItems.push(id);
      }
    },
    isSelected(id) {
      this.selectedItems.includes(id);
    },
    async getExportFile(exportLink, auctionId) {
      await axios
          .post(exportLink, {
            ids: this.selectedItems,
            sort: this.sort,
            order: this.order,
            search: this.search,
            status: this.status,
            auction_id: auctionId,
            number: this.number,
            nullable: this.nullable,
          }, {responseType: 'blob'})
          .then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            let fileLink = document.createElement('a');
            fileLink.href = url;
            fileLink.setAttribute('download', 'export.xlsx');

            document.body.appendChild(fileLink);

            fileLink.click();
          }).catch(error => {
            console.log(error);
          });
    },
    getExport(id) {
      this.getExportFile('/admin/export', id);
    },
    openDialog() {
      this.toggleModal = true;
    },
    changeSelectNullable(value) {
      this.nullable = value;
    },
    closeModal() {
      this.toggleModal = false;
    },
    async published(publish) {
      await axios
          .post('/admin/lots/published', {
            ids: this.selectedItems,
            search: this.search,
            nullable: this.nullable,
            number: this.number,
            published: publish,
            status: this.status
          }).then(response => {
            this.getLots(this.url);
          })
    },
    async publishedAdmin(publish) {
      await axios
          .post('/admin/lots/published-admin', {
            ids: this.selectedItems,
            search: this.search,
            nullable: this.nullable,
            number: this.number,
            published: publish,
            status: this.status
          }).then(response => {
            this.getLots(this.url);
          })
    },
    async exportCatalog() {
      this.disableImport = true;
      await axios
          .post('/admin/export-catalog', {
            ids: this.selectedItems,
            sort: this.sort,
            order: this.order,
            search: this.search,
            status: this.status,
            number: this.number,
            nullable: this.nullable,
          }, {responseType: 'blob'})
          .then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            let fileLink = document.createElement('a');
            fileLink.href = url;
            fileLink.setAttribute('download', 'export-catalog.xlsx');

            document.body.appendChild(fileLink);

            fileLink.click();
            this.disableImport = false;
          }).catch(error => {
            this.disableImport = false;
            console.log(error);
          });
    }
  },
  computed: {
    hasSelectedItems() {
      return this.selectedItems.length > 0;
    },
    selectAll() {
      return this.selectedItems.length > 0 && this.allLotsIds.length === this.selectedItems.length;
    },
    isSelectedPage() {
      for (let element of this.pageLotIds) {
        if (!this.selectedItems.includes(element)) {
          return false;
        }
      }
      return true;
    }
  },
  mounted() {
    this.nullableFields = {
      number: 'Auction id',
      description: 'Description',
      condition: 'Condition',
      sale_price: 'Sale price',
      low_estimate: 'Low estimate',
      high_estimate: 'High estimate',
      starting_price: 'Starting price',
      category_id: 'Category',
      client_id: 'Client',
      buy_now_price: 'Buy now price',
      reserve_price: 'Reserve price',
      height: 'Height',
      width: 'Width',
      depth: 'Depth',
      dimension_unit: 'Dimension unit',
      weight: 'Weight',
      weight_unit: 'Weight_unit'
    };
  }
}
</script>

<style scoped>
.ids {
  width: 80px;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
  max-height: 80vh;
  overflow: auto;
}

th, td {
  border: 1px solid #ddd;
  text-align: left;
  padding: 8px;
}

/*.threat {*/
/*min-width: 2500px;*/
/*overflow: auto;*/
/*}*/
#loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* полупрозрачный цвет фона */
  z-index: 9999; /* значение z-index, чтобы лоадер был поверх остальных элементов */
  display: flex;
  justify-content: center;
  align-items: center;
}

.loader {
  border: 4px solid #f3f3f3; /* цвет рамки лоадера */
  border-top: 4px solid #3498db; /* цвет верхней части лоадера */
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 1s linear infinite; /* анимация вращения */
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>