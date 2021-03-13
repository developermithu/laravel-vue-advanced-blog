<template>
  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
          <div class="col-auto">
            <Button type="primary" @click="addModal = true">
              <Icon type="md-add" /> Add Category
            </Button>
          </div>
          <div class="col-auto">
            <h3 class="app-page-title mb-0">Category List</h3>
          </div>

          <!--========= Add Category Modal =========-->
          <Modal
            v-model="addModal"
            title="Add New Category"
            :mask-closable="false"
            :closable="false"
          >
            <!-- handleError() for backend laravel $request validation -->
            <div class="body mb-3">
              <Input
                v-model="data.categoryName"
                placeholder="Category name.."
                class="mb-3 w-100"
              />
              <Upload
                ref="uploadFileNameEditing"
                type="drag"
                :headers="{
                  'x-csrf-token': token,
                  'X-Requested-With': 'XMLHttpRequest',
                }"
                :on-success="handleSuccess"
                :on-error="handleError"
                action="category/upload-image"
              >
                <div style="padding: 20px 0">
                  <Icon
                    type="ios-cloud-upload"
                    size="52"
                    style="color: #3399ff"
                  >
                  </Icon>
                  <p>Click or drag files here to upload</p>
                </div>
              </Upload>
              <div class="demo-upload-list" v-if="data.categoryImage">
                <img :src="`${data.categoryImage}`" />
                <div class="demo-upload-list-cover">
                  <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                </div>
              </div>
            </div>

            <div slot="footer">
              <Button type="default" @click="addModal = false">Close</Button>
              <Button
                type="primary"
                @click="addCategory"
                :disabled="isAdding"
                :loading="isAdding"
              >
                {{ isAdding ? "Adding.." : "Add Category" }}
              </Button>
            </div>
          </Modal>

          <!--========= Edit Category Modal =========-->
          <Modal
            v-model="editModal"
            title="Edit Category"
            :mask-closable="false"
            :closable="false"
          >
            <!-- handleError() for backend laravel $request validation -->
            <div class="body mb-3">
              <Input v-model="editData.categoryName" class="mb-3 w-100" />
              <Upload
                v-show="isCategoryImage"
                ref="uploadFileName"
                type="drag"
                :headers="{
                  'x-csrf-token': token,
                  'X-Requested-With': 'XMLHttpRequest',
                }"
                :on-success="handleSuccess"
                :on-error="handleError"
                action="category/upload-image"
              >
                <div style="padding: 20px 0">
                  <Icon
                    type="ios-cloud-upload"
                    size="52"
                    style="color: #3399ff"
                  >
                  </Icon>
                  <p>Click or drag files here to upload</p>
                </div>
              </Upload>
              <div class="demo-upload-list" v-if="editData.categoryImage">
                <img :src="`${editData.categoryImage}`" />
                <div class="demo-upload-list-cover">
                  <Icon type="ios-trash-outline" @click="deleteImage(false)">
                  </Icon>
                </div>
              </div>
            </div>
            <div slot="footer">
              <Button type="default" @click="closeEditModal">Close</Button>
              <Button
                type="primary"
                @click="editCategory"
                :disabled="isAdding"
                :loading="isAdding"
                >{{ isAdding ? "Editing.." : "Update Category" }}</Button
              >
            </div>
          </Modal>
          <!--========== Delete Model  ===========-->
          <deleteModal></deleteModal>
          <!--========== Delete Model  ===========-->
        </div>

        <div class="app-card app-card-orders-table mb-5">
          <div class="app-card-body">
            <div class="table-responsive">
              <table class="table app-table-hover mb-0 text-left">
                <thead>
                  <tr class="text-center">
                    <th class="cell">Id</th>
                    <th class="cell">Category Name</th>
                    <th class="cell">Category Image</th>
                    <th class="cell">Created At</th>
                    <th class="cell">Action</th>
                  </tr>
                </thead>

                <tbody>
                  <tr
                    v-for="(category, i) in categoryList"
                    :key="i"
                    v-if="categoryList.length"
                    class="text-center"
                  >
                    <td class="cell">{{ category.id }}</td>
                    <td class="cell">{{ category.categoryName }}</td>
                    <td class="cell">
                      <img :src="category.categoryImage" width="60px" />
                    </td>
                    <td class="cell">{{ category.created_at }}</td>
                    <td class="cell">
                      <Button
                        type="primary"
                        size="small"
                        @click="showEditModal(category, i)"
                        >Edit
                      </Button>
                      <Button
                        type="error"
                        size="small"
                        @click="deleteTagData(category, i)"
                        :loading="category.isDeleteing"
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
        categoryName: "",
        categoryImage: "",
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      categoryList: [],
      editData: {
        categoryName: "",
        categoryImage: "",
      },
      index: -1,
      showDeleteModal: false,
      deleteItem: {},
      token: "",
      isCategoryImage: false,
      isEditingCategory: false,
    };
  },

  methods: {
    async addCategory() {
      if (this.data.categoryName.trim() == "")
        return this.error("Category name is required.");
      if (this.data.categoryImage.trim() == "")
        return this.error("Category image is required.");
      this.data.categoryImage = `/media/${this.data.categoryImage}`; // imageFullPath
      const result = await this.callApi("post", "/category/create", this.data);
      // callApi(method, url, dataObj)
      // status = 201 means add
      // status = 200 means get
      // status = 422 means error
      if (result.status === 201) {
        this.categoryList.unshift(result.data); //data auto insert hobe
        this.success("Category added successfully.");
        this.addModal = false;
        this.data.categoryName = "";
        this.data.categoryImage = "";
      } else {
        if (result.status == 422) {
          if (result.data.errors.categoryName) {
            this.warning(result.data.errors.categoryName[0]);
          }
          if (result.data.errors.categoryImage) {
            this.warning(result.data.errors.categoryImage[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editCategory() {
      if (this.editData.categoryName.trim() == "")
        return this.error("Category name is required.");
      if (this.editData.categoryImage.trim() == "")
        return this.error("Category image is required.");
      const result = await this.callApi(
        "post",
        "/category/edit",
        this.editData
      );
      if (result.status === 200) {
        this.categoryList[this.index].categoryName = this.editData.categoryName; //stop realtime editing
        this.success("Category edited successfully.");
        this.editModal = false;
      } else {
        if (result.status == 422) {
          if (result.data.errors.categoryName) {
            this.warning(result.data.errors.categoryName[0]);
          }
          if (result.data.errors.categoryImage) {
            this.warning(result.data.errors.categoryImage[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(category, index) {
      this.editData = category;
      this.editModal = true;
      this.index = index;
      this.isEditingCategory = true;
    },
    deleteTagData(tag, i) {
      this.deleteItem = tag;
      this.deletingIndex = i;
      this.showDeleteModal = true;
    },

    // file upload function
    handleSuccess(res, file) {
      res = `/media/${res}`;
      if (this.isEditingCategory) {
        return (this.editData.categoryImage = res);
      }
      this.data.categoryImage = res;
    },
    handleError(res, file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc: `${
          file.errors.file.length
            ? file.errors.file[0]
            : "Something went wrong!"
        }`,
      });
    },
    async deleteImage(isAdd = true) {
      let image;
      if (!isAdd) {
        //for editing
        this.isCategoryImage = true;
        image = this.editData.categoryImage;
        this.editData.categoryImage = "";
        this.$refs.uploadFileNameEditing.clearFiles();
      } else {
        image = this.data.categoryImage;
        this.data.categoryImage = "";
        this.$refs.uploadFileName.clearFiles();
      }
      const res = await this.callApi("post", "category/delete-image", {
        imageName: image,
      });
      if (res.status != 200) {
        this.data.categoryImage = image;
        this.swr();
      }
    },
    closeEditModal() {
      this.isEditingCategory = false;
      this.editModal = false;
    },
  }, //method end

  async created() {
    this.token = window.Laravel.csrfToken;
    const result = await this.callApi("get", "/categories");
    if (result.status == 200) {
      this.categoryList = result.data;
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


<style>
.demo-upload-list {
  display: inline-block;
  width: 60px;
  height: 60px;
  text-align: center;
  line-height: 60px;
  border: 1px solid transparent;
  border-radius: 4px;
  overflow: hidden;
  background: #fff;
  position: relative;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
  margin-right: 4px;
}
.demo-upload-list img {
  width: 100%;
  height: 100%;
}
.demo-upload-list-cover {
  display: none;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.6);
}
.demo-upload-list:hover .demo-upload-list-cover {
  display: block;
}
.demo-upload-list-cover i {
  color: #fff;
  font-size: 20px;
  cursor: pointer;
  margin: 0 2px;
}
</style>

