<template>
  <div>
    <!--========== Delete Model  ===========-->
    <Modal
      :value="getDeleteModalObj.showDeleteModal"
      :mask-closable="false"
      :closable="false"
      width="360"
    >
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
    </Modal>
  </div>
</template>


<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      isDeleting: false,
    };
  },

  methods: {
    async deleteTag() {
      this.$store.commit("setDeleteModal");
      //   this.isDeleting = true;
      //   const result = await this.callApi("post", this.getDeleteModalObj.deleteUrl, this.getDeleteModalObj.data);
      //   if (result.status === 200) {
      //     this.success("Category has been deleted successfully.");
      //   } else {
      //     this.swr();
      //   }
      //   this.isDeleting = false;
    },
  },

  computed: {
    ...mapGetters(["getDeleteModalObj"]),
  },
};
</script>
