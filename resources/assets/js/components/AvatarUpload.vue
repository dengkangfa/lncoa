<template>
  <div class="cover-avatar text-center">
    <img :src="src ? src : 'http://lncoa.app/images/default.png'" class="avatar">
    <a href="javascript:;" class="btn btn-success file">
      <span>{{ $t('el.form.modify_avatar') }}</span>
      <input type="file" name="avatar" :accept="imageType" @change="upload">
    </a>

    <modal :show="dialogVisible" @cancel="dialogVisible = false">
        <div slot="title">{{ $t('el.form.crop_avatar') }}</div>
        <cropper :image="cropImage" @canceled="dialogVisible = false"  @succeed="succeed"></cropper>
    </modal>
  </div>
</template>

<script>
  import Cropper from '../components/Cropper'
  import Modal from '../components/Modal'

  export default {
    components: { Modal, Cropper },
    props: {
      src: {
        type: String,
        default() {
          return ''
        }
      }
    },
    data() {
      return {
        cropImage: undefined,
        dialogVisible: false,
        imageType: 'image/png,image/gif,image/jpeg,image/jpg,image/tiff'
      }
    },
    methods: {
      upload(e) {
        let files = e.target.files
        let formData = new FormData()

        formData.append('file', files[0]);

        axios.post('/api/user/avatar', formData)
            .then((response) => {
              this.cropImage = response.data

              this.dialogVisible = true
            })
      },
      succeed() {
        this.dialogVisible = false
        // this.$router.push('/user/profile');
        window.location = "/user/profile";
      },
    }
  }
</script>

<style lang="scss" scoped>
  .file {
    position: relative;
    margin: 0 auto;
    display: block;
    max-width: 100px;
    height: 30px;
    line-height: 30px;
    font-size: 10px;

    span {
      display: block;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
    }
    input {
      position: absolute;
      left: 0;
      right: 0;
      top: 0;
      width: 100px;
      height: 30px;
      opacity: 0;
    }
  }
  .profile .cover-avatar:hover .avatar {
      transform: rotateZ(360deg);
  }
  .profile .cover-avatar .avatar{
      width: 150px;
      height: 150px;
      border-radius: 50%;
      -webkit-transition: transition .6s ease-in;
      -webkit-transition: -webkit-transform .6s ease-in;
      transition: -webkit-transform .6s ease-in;
      transition: transform .6s ease-in;
      transition: transform .6s ease-in,-webkit-transform .6s ease-in;
      margin-bottom: 20px;
  }
  input[type="file"] {
      display: block;
  }
  @media (max-width: 640px){
      .profile .cover-avatar .avatar {
          width: 50px;
          height: 50px;
      }
  }
</style>
