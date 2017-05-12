<template>
  <div>
    <canvas id="canvas" width="1000" height="1000"></canvas>
  </div>
</template>

<script>
export default {
  data() {
      return {
          interval_id: '',
          currentSeconds: 60,
          countDownSeconds: 3
      }
  },
  props: {

  },
  created() {
      console.log($('#canvas'));
      this.interval_id = setInterval(this.drawDoubleCircle(this), 1000);
  },
  methods: {
      drawDoubleCircle(vm) {
         if (vm.currentSeconds <= 0) {
           clearInterval(interval_id);
         }
         console.log($('#canvas'));
         var context = $('#canvas').getContext('2d');
         progress = 360 * vm.currentSeconds / vm.countDownSeconds;
         progress_pi = Math.PI * (progress / 180 - 1 / 2);
         context.beginPath();
         context.moveTo(700, 400);
         context.arc(700, 400, 80, 0, Math.PI * 2, false);
         context.closePath();
         context.fillStyle = '#4BF41B';
         context.fill();

         context.beginPath();
         context.moveTo(700, 400);
         context.arc(700, 400, 80, -Math.PI * 1 / 2, progress_pi, false);
         context.closePath();
         context.fillStyle = 'red'
         context.lineCap = 'round';
         context.fill();

         context.beginPath();
         context.arc(700, 400, 65, 0, Math.PI * 2, false);
         context.closePath();
         context.fillStyle = 'white';
         context.fill();

         context.font = "bold 40pt Arial";
         context.fillStyle = "red";
         context.textAlign = "center";
         context.textBaseline = 'middle';
         context.fillText(currentSeconds, 700, 400);
         // 抗锯齿
         context.globalCompositeOperation = 'source-atop';
         currentSeconds--;
      }
  }
}
</script>

<style lang="css">
</style>
