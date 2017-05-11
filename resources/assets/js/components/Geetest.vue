<template>
  <div style="margin-bottom: 5px;">
    <div id="geetest-captcha"></div>
    <p id="wait">正在加载验证码...</p>
  </div>
</template>

<script>
  export default {
      created() {
           this.geetest('laravelchen/geetest');
      },
      methods: {
          geetest(url) {
                let vm = this;
                var handlerEmbed = function(captchaObj) {
                // $("#geetest-captcha").closest('form').submit(function(e) {
                //     vm.$emit('validate',captchaObj.getValidate())
                // });
                captchaObj.appendTo("#geetest-captcha");
                captchaObj.onReady(function() {
                  $("#wait")[0].className = "hide";
                });
                captchaObj.onSuccess(() => {
                    vm.$emit('validate',captchaObj.getValidate())
                })
                // if ('popup' == 'popup') {
                //     captchaObj.bindForm($('#geetest-captcha').closest('form').find(':submit'));
                //     captchaObj.appendTo("#geetest-captcha");
                // }
            };
            $.ajax({
                url: url + "?t=" + (new Date()).getTime(),
                type: "get",
                dataType: "json",
                success: function(data) {
                    initGeetest({
                        gt: data.gt,
                        challenge: data.challenge,
                        product: 'popup',
                        offline: !data.success,
                        new_captcha: true,
                        width: '100%',
                        lang: 'zh-cn'
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
