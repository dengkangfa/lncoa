<template>
  <div style="margin-bottom: 5px; min-width: 220px">
    <div id="geetest-captcha"></div>
    <p id="wait">{{$t('el.form.code_placeholder')}}</p>
  </div>
</template>

<script>
  export default {
      data() {
        return {
            gtserver: '',
            user_id: ''
        }
      },
      created() {
           this.geetest('/geetest');
      },
      methods: {
          geetest(url) {
            let vm = this;
            var handlerEmbed = function(captchaObj) {
                if(!$('#geetest-captcha').html()){
                  captchaObj.appendTo("#geetest-captcha");
                }
                captchaObj.onReady(function() {
                  $("#wait")[0].className = "hide";
                });
                captchaObj.onSuccess(() => {
                    let data = captchaObj.getValidate()
                    data.gtserver = vm.gtserver;
                    data.user_id = vm.user_id
                    vm.$emit('validate',data)
                })
            };
            $.ajax({
                url: url + "?t=" + (new Date()).getTime(),
                type: "get",
                dataType: "json",
                success: function(data) {
                    vm.gtserver = data.gtserver;
                    vm.user_id = data.user_id;
                    initGeetest({
                        gt: data.gt,
                        challenge: data.challenge,
                        product: 'popup',
                        offline: !data.success,
                        new_captcha: true,
                        width: '100%',
                        lang: window.Language
                    }, handlerEmbed);
                }
            });
          }
      }
  }
</script>

<style lang="css" scoped>
  .hide {
      display: none;
  }
  .show {
      display: block;
  }
</style>
