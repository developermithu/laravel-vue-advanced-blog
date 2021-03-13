<template>
  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
          <div class="col-auto">
            <Button type="primary" @click="addModal = true">
              <Icon type="md-add" /> Add Tag
            </Button>
          </div>
          <div class="col-auto">
            <h1 class="app-page-title mb-0">Tag List</h1>
          </div>

          <!--========= Add Tag Modal =========-->
          <Modal
            v-model="addModal"
            title="Add New Tag"
            :mask-closable="false"
            :closable="false"
          >
            <Input
              v-model="data.tagName"
              placeholder="Type tag name.."
              style="width: 100%"
              class="my-3"
            />
            <div slot="footer">
              <Button type="default" @click="addModal = false">Close</Button>
              <Button
                type="primary"
                @click="addTag"
                :disabled="isAdding"
                :loading="isAdding"
                >{{ isAdding ? "Adding.." : "Add Tag" }}
              </Button>
            </div>
          </Modal>
          <!--========= Edit Tag Modal =========-->
          <Modal
            v-model="editModal"
            title="Edit New Tag"
            :mask-closable="false"
            :closable="false"
          >
            <Input
              v-model="editData.tagName"
              placeholder="Type tag name.."
              style="width: 100%"
              class="my-3"
            />
            <div slot="footer">
              <Button type="default" @click="editModal = false">Close</Button>
              <Button
                type="primary"
                @click="editTag"
                :disabled="isAdding"
                :loading="isAdding"
                >{{ isAdding ? "Editing.." : "Update Tag" }}</Button
              >
            </div>
          </Modal>
          <!--========== Delete Model  ===========-->
          <deleteModal></deleteModal>
          <!-- <Modal v-model="showDeleteModal" width="360">
            <p slot="header" style="color: #f60; text-align: center">
              <Icon type="ios-information-circle"></Icon>
              <span>Delete confirmation</span>
            </p>
            <div style="text-align: center">
              <p>Are you sure to delete this data?</p>
            </div>
            <div slot="footer">
              <Button
                type="error"
                size="large"
                long
                :loading="isDeleting"
                :disabled="isDeleting"
                @click="deleteTag"
                >Delete
              </Button>
            </div>
          </Modal> -->
        </div>

        <div class="app-card app-card-orders-table mb-5">
          <div class="app-card-body">
            <div class="table-responsive">
              <table class="table app-table-hover mb-0 text-left">
                <thead>
                  <tr class="text-center">
                    <th class="cell">Id</th>
                    <th class="cell">Tag Name</th>
                    <th class="cell">Created At</th>
                    <th class="cell">Action</th>
                  </tr>
                </thead>

                <tbody>
                  <tr
                    v-for="(tag, i) in tags"
                    :key="i"
                    v-if="tags.length"
                    class="text-center"
                  >
                    <td class="cell">{{ tag.id }}</td>
                    <td class="cell">
                      {{ tag.tagName }}
                    </td>
                    <td class="cell">{{ tag.created_at }}</td>
                    <td class="cell">
                      <Button
                        type="primary"
                        size="small"
                        @click="showEditModal(tag, i)"
                        >Edit</Button
                      >
                      <Button
                        type="error"
                        size="small"
                        @click="showDeletingModal(tag, i)"
                        :loading="tag.isDeleteing"
                        >Delete</Button
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import deleteModal from "../reuse/deleteModal";
export default {
  data() {
    return {
      data: {
        tagName: "",
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      tags: [],
      editData: {
        tagName: "",
      },
      index: -1,
      showDeleteModal: false,
      // isDeleting: false,
      // deletingIndex: -1,
      deleteItem: {},
    };
  },

  methods: {
    async addTag() {
      if (this.data.tagName.trim() == "")
        return this.error("Tag name is required.");
      const result = await this.callApi("post", "/create-tag", this.data); // callApi(method, url, dataObj)

      // status = 201 means add
      // status = 200 means get
      // status = 422 means error
      if (result.status === 201) {
        this.tags.unshift(result.data); //data auto insert hobe
        this.success("Tag added successfully.");
        this.addModal = false;
        this.data.tagName = "";
      } else {
        if (result.status == 422) {
          if (result.data.errors.tagName) {
            this.warning(result.data.errors.tagName[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editTag() {
      if (this.editData.tagName.trim() == "")
        return this.error("Tag name is required.");
      const result = await this.callApi("post", "/edit/tag", this.editData);
      if (result.status === 200) {
        this.tags[this.index].tagName = this.editData.tagName; //stop realtime editing
        this.success("Tag edited successfully.");
        this.editModal = false;
      } else {
        if (result.status == 422) {
          if (result.data.errors.tagName) {
            this.warning(result.data.errors.tagName[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(tag, index) {
      let obj = {
        id: tag.id,
        tagName: tag.tagName,
      };
      this.editData = obj; //obj for stop realtime editing
      // this.editData = tag;
      this.editModal = true;
      this.index = index;
    },
    async deleteTag() {
      this.isDeleting = true;
      const result = await this.callApi("post", "delete/tag", this.deleteItem);
      if (result.status === 200) {
        this.tags.splice(this.deletingIndex, 1);
        this.success("Tag has been deleted successfully.");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(tag, i) {
      const deleteModalObj = {
        showDeleteModal: true,
        deleteUrl: "delete/tag",
        data: tag,
        deletingIndex: i,
        isDeleted: false,
      };
      this.$store.commit("setDeleteModalObj", deleteModalObj);
      // this.deleteItem = tag;
      // this.deletingIndex = i;
      // this.showDeleteModal = true;
    },
  },

  async created() {
    const result = await this.callApi("get", "/all-tags");
    if (result.status == 200) {
      this.tags = result.data;
    } else {
      this.swr();
    }
  },

  // get component
  components: {
    deleteModal,
  },
};
</script>

