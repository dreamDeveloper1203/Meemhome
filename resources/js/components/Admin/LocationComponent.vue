<template>
  <div class="d-flex align-items-center mb-3">
    <div class="flex-grow-1 h5 fw-bold m-0 ff-montserrat">Locations</div>
    <button class="btn btn-primary px-md-4 ms-auto" data-bs-toggle="modal" data-bs-target="#locationModal">Add Location</button>
  </div>
  <div class="border rounded-3 p-3 mb-3 bg-white border-light shadow-sm">
    <div class="position-relative mb-3">
      <input
        type="search"
        class="form-control w-auto"
        name="search"
        v-model="search"
        id="search"
        autocomplete="off"
        placeholder="Search..."
        style="padding-left: 2.5rem !important"
      />
      <div class="position-absolute top-50 start-0 translate-middle-y p-2">
        <SearchIcon />
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="location in locationList" :key="location.id">
            <td>{{ location.name }}</td>
            <td>
              <div class="form-check form-switch">
                <input
                  class="form-check-input cursor-pointer"
                  type="checkbox"
                  id="flexSwitchCheckChecked"
                  :checked="location.is_active"
                  v-on:change="updateStatus(location.id)"
                />
                <label class="form-check-label" for="flexSwitchCheckChecked">
                  {{ location.status }}
                </label>
              </div>
            </td>
            <td>
              <button class="btn btn-info btn-xs me-md-2" data-bs-toggle="modal" data-bs-target="#editlocationModal" v-on:click="openEditModal(location)">Edit</button>

              <button class="btn btn-danger btn-xs" v-on:click="deletelocation(location.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <NoContent v-if="locationList.length == 0" />
    </div>
  </div>

  <!-- Modal Create -->
  <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-md-down modal-dialog-scrollable modal-dialog-centered">
      <form @submit.prevent="storelocation" class="modal-content">
        <div class="modal-header d-flex align-items-center">
          <div class="d-flex align-items-center flex-grow-1">
            <BackBtn />
            <h5 class="modal-title" id="locationModalLabel">Add location</h5>
          </div>
          <div>
            <button type="button" class="btn btn-outline-danger px-md-4 me-2" data-bs-dismiss="modal" @click="restoreFormControl()">Discard</button>
            <button type="submit" class="btn btn-primary px-md-4" :disabled="loading">Save</button>
          </div>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label fw-bold">Location Name</label>
            <input type="text" class="form-control" v-model="data.name" id="name" :class="{ 'is-invalid': errors.hasOwnProperty('name') }" name="name" />
            <div class="invalid-feedback" v-if="errors.hasOwnProperty('name')">
              {{ errors.name[0] }}
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Update -->
  <div class="modal fade" id="editlocationModal" tabindex="-1" aria-labelledby="editlocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-md-down modal-dialog-scrollable modal-dialog-centered">
      <form @submit.prevent="updatelocation(editData.id)" class="modal-content">
        <div class="modal-header d-flex align-items-center">
          <div class="d-flex align-items-center flex-grow-1">
            <BackBtn />
            <h5 class="modal-title" id="editlocationModalLabel">Edit location</h5>
          </div>
          <div>
            <button type="button" class="btn btn-outline-danger px-md-4 me-2" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary px-md-4" :disabled="loading">Save</button>
          </div>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name-edit" class="form-label fw-bold">location Name</label>
            <input type="text" class="form-control" v-model="editData.name" id="name-edit" :class="{ 'is-invalid': errors.hasOwnProperty('name') }" />
            <div class="invalid-feedback" v-if="errors.hasOwnProperty('name')">
              {{ errors.name[0] }}
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { swalConfig } from '@/services/utils';
import InputNumber from 'primevue/inputnumber';

export default {
  components: { InputNumber },

  data() {
    return {
      Locations: [],
      search: '',
      data: {
        name: '',
      },

      editData: {
        id: '',
        name: '',
        _method: 'PUT'
      },
      errors: {},
      loading: false
    };
  },
  mounted() {},
  methods: {
    fetchLocations() {
      topbar.show();
      axios
        .get('/admin/locations/all')
        .then(response => (this.Locations = response.data.data))
        .catch(({ response }) => Swal.fire(`Error ${response.status}`, response.statusText, 'error'))
        .then(() => topbar.hide());
    },
    refreshTable() {
      this.fetchLocations();
    },

    deletelocation(id) {
      Swal.fire(swalConfig()).then(result => {
        if (result.value) {
          topbar.show();
          axios
            .delete(`/admin/location/${id}`)
            .then(response => {
              if (response.status == 200) {
                this.fetchLocations();
                this.$toast.success('location has been deleted');
              }
            })
            .catch(({ response }) => {
              Swal.fire(`Error ${response.status}`, response.statusText, 'error');
            })
            .then(() => {
              topbar.hide();
            });
        }
      });
    },
    storelocation() {
      this.errors = {};
      topbar.show();
      this.loading = true;
      axios
        .post('/admin/location', this.data)
        .then(response => {
          this.fetchLocations();
          this.restoreFormControl();
          this.$toast.success('New location added');
        })
        .catch(({ response }) => {
          if (response.status === 422 || response.status === 429) {
            this.errors = response.data.errors;
          } else {
            Swal.fire(`Error ${response.status}`, response.statusText, 'error');
          }
        })
        .then(() => {
          topbar.hide();
          this.loading = false;
        });
    },

    updatelocation(id) {
      this.errors = {};
      topbar.show();
      this.loading = true;

      axios
        .post(`/admin/location/${id}`, this.editData)
        .then(response => {
          this.fetchLocations();
          this.$toast.success('location has been updated');
        })
        .catch(({ response }) => {
          if (response.status === 422 || response.status === 429) {
            this.errors = response.data.errors;
          } else {
            Swal.fire(`Error ${response.status}`, response.statusText, 'error');
          }
        })
        .then(() => {
          topbar.hide();
          this.loading = false;
        });
    },
    openEditModal(location) {
      this.editData.id = location.id;
      this.editData.name = location.name;
    },

    updateStatus(id) {
      topbar.show();
      axios
        .put(`/admin/location/status/${id}`)
        .then(response => {
          this.fetchLocations();
          this.$toast.success('location has been updated');
        })
        .catch(({ response }) => Swal.fire(`Error ${response.status}`, response.statusText, 'error'))
        .then(() => topbar.hide());
    },
    restoreFormControl() {
      this.errors = {};
      this.data.name = '';
    }
  },

  created: function () {
    this.fetchLocations();
  },
  computed: {
    locationList() {
      const search = this.search.toLowerCase();
      if (!search) return this.Locations;
      return this.Locations.filter(location => {
        return (
          location.name.toLowerCase().includes(search)
        );
      });
    }
  }
};
</script>
