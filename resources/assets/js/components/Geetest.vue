<template>
  <div style="margin-bottom: 5px; min-width: 220px">
    <div id="geetest-captcha"></div>
    <p id="wait">{{$t('el.form.code_placeholder')}}</p>
  </div>
</template>

<script>
  export default {
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
                  console.log(captchaObj.getValidate());
                    vm.$emit('validate',captchaObj.getValidate())
                })
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
