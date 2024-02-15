<template>
  <Line :data="totalEarning" :options="options" />
</template>

<script>
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)

export default {
  name: 'TotalEarning',
  components: {
    Line
  },
  props:['totalEarning'],
  data() {
    return {
      options : {
        responsive: true,
        maintainAspectRatio: false
      }
    }
  }
}
</script>
