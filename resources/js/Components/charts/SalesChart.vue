<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import {
  Chart,
  LineController,
  LineElement,
  PointElement,
  LinearScale,
  CategoryScale,
  Tooltip,
  Legend,
} from "chart.js";

Chart.register(
  LineController,
  LineElement,
  PointElement,
  LinearScale,
  CategoryScale,
  Tooltip,
  Legend
);

const props = defineProps<{
  labels: string[];
  data: number[];
}>();

const canvasRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

const isDark = document.documentElement.classList.contains("dark");

const primary = isDark
  ? "rgb(96, 165, 250)" // blue-400
  : "rgb(59, 130, 246)"; // blue-500

onMounted(() => {
  if (!canvasRef.value) return;

  chartInstance = new Chart(canvasRef.value, {
    type: "line",
    data: {
      labels: props.labels,
      datasets: [
        {
          label: "Sales",
          data: props.data,

          borderColor: primary,
          backgroundColor: primary.replace("rgb", "rgba").replace(")", ",0.15)"),

          pointBackgroundColor: primary,
          pointBorderColor: primary,
          pointRadius: 4,
          pointHoverRadius: 6,

          borderWidth: 2,
          tension: 0.4,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        tooltip: { enabled: true },
        legend: {
          labels: {
            color: "rgb(156 163 175)", // gray-400
          },
        },
      },
      scales: {
        x: {
          ticks: { color: "rgb(156 163 175)" },
          grid: { display: false },
        },
        y: {
          ticks: { color: "rgb(156 163 175)" },
          grid: { color: "rgba(156,163,175,0.1)" },
        },
      },
    },
  });
});
</script>

<template>
  <div class="relative h-[350px] w-full">
    <canvas ref="canvasRef" />
  </div>
</template>
