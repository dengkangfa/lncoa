<template lang="html">
  <span style="margin-bottom: 0">{{timeinterval}}</span>
</template>

<script>
export default {
    data() {
        return {
            timeinterval: '',
        }
    },
    props: {
      date: {
          required: true,
          default() {
            return
          }
      },
    },
    created() {
        this.timeinterval = this.formatMsgTime(this.date);
    },
    methods: {
      formatMsgTime (timespan) {
        let date = {};
        if(typeof(timespan) == 'number'){
            //php时间戳转换成js时间戳
            let d = new Date(parseInt(timespan) * 1000);
            date = (d.getFullYear()) + "-" + (d.getMonth() + 1) + "-" + (d.getDate()) + "-" + (d.getHours()) + ":" + (d.getMinutes()) + ":" + (d.getSeconds());
        }else if(typeof(timespan) == 'string') {
            date = timespan
        }
        //当前时间
        let now = new Date();

        let startTime = date;
        startTime = startTime.replace(/\-/g, "/");
        let sTime = new Date(startTime);
        let totalTime = now.getTime() - sTime.getTime();

        let days = parseInt(totalTime / parseInt(1000 * 60 * 60 * 24));
        totalTime = totalTime % parseInt(1000 * 60 * 60 * 24);
        let hours = parseInt(totalTime / parseInt(1000 * 60 * 60));
        totalTime = totalTime % parseInt(1000 * 60 * 60);
        let minutes = parseInt(totalTime / parseInt(1000 * 60));
        totalTime = totalTime % parseInt(1000 * 60);
        let seconds = parseInt(totalTime / parseInt(1000));
        let time = "";
        let day_str= this.$t('el.table.day');
        let hour_str= this.$t('el.table.hour');
        let minute_str= this.$t('el.table.minute');
        let second_str= this.$t('el.table.second');
        if (days >= 1) {
            time = days + day_str;
        } else if (hours >= 1) {
            time = hours + hour_str;
        } else if (minutes >= 1) {
            time = minutes + minute_str;
        } else {
            time = seconds + second_str;
        }
        return time;
      }
    }
}
</script>

<style lang="css">
</style>
