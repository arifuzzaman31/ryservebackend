<template>
  <Doughnut :data="doughtData" :options="options" />
</template>

<script>
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend)

export default {
  name: 'Top-sale-by-cate',
  components: {
    Doughnut
  },
  props:['doughtData'],

  data(){
    return {
      options: {
        responsive: true,
        cutout:90,
        aspectRatio: 1,
        maintainAspectRatio: false
      }
    }
  }
}
</script>