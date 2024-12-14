<script setup>
import Header from '@/components/ui/Header.vue';
import { useAuthStore } from '@/stores/auth';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Chart from 'primevue/chart';

const authStore = useAuthStore();

const data = ref(null); // Dados do gráfico
const data2 = ref(null); // Dados do gráfico
const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: {
      labels: {
        color: '#FFF' // Cor do texto da legenda
      }
    }
  },
  scales: {
    x: {
      ticks: {
        color: '#FFF' // Cor dos ticks no eixo X
      }
    },
    y: {
      ticks: {
        color: '#FFF' // Cor dos ticks no eixo Y
      }
    }
  }
}); // Opções do gráfico

const generateHorizontalGradient = (ctx, chartArea) => {
  const { left, right } = chartArea; // Área do gráfico
  const gradient = ctx.createLinearGradient(left, 0, right, 0); // Gradiente horizontal
  gradient.addColorStop(0, 'rgba(204, 204, 255, 0.3)'); // Azul claro no início
  gradient.addColorStop(1, 'rgba(204, 204, 255, 1)'); // Azul forte no final
  return gradient;
};

onMounted(() => {
  const chartData = {
    labels: [],
    datasets: [
      {
        label: 'Users',
        data: [],
        backgroundColor: null, // O gradiente será aplicado dinamicamente
        borderColor: '#ffffff'
      }
    ]
  };
  const chartData2 = {
    labels: [],
    datasets: [
      {
        label: 'Purchases',
        data: [],
        backgroundColor: null, // O gradiente será aplicado dinamicamente
        borderColor: '#ffffff'
      }
    ]
  };

  axios
    .get('stats/users/month')
    .then(response => {
      response.data.forEach(element => {
        chartData.labels.push(element.month);
        chartData.datasets[0].data.push(element.count);
      });

      // Configurar o backgroundColor como uma função dinâmica
      data.value = {
        ...chartData,
        datasets: [
          {
            ...chartData.datasets[0],
            backgroundColor: (ctx) => {
              const chart = ctx.chart;
              const { ctx: canvasCtx, chartArea } = chart;
              if (!chartArea) return; // Aguarda a área do gráfico ser calculada
              return generateHorizontalGradient(canvasCtx, chartArea);
            }
          }
        ]
      };
    })
    .catch(error => {
      console.log(error);
    });

    axios
    .get('stats/purchases/week')
    .then(response => {
      response.data.forEach(element => {
        chartData2.labels.push(element.week);
        chartData2.datasets[0].data.push(element.count);
      });

      // Configurar o backgroundColor como uma função dinâmica
      data2.value = {
        ...chartData2,
        datasets: [
          {
            ...chartData2.datasets[0],
            backgroundColor: (ctx) => {
              const chart = ctx.chart;
              const { ctx: canvasCtx, chartArea } = chart;
              if (!chartArea) return; // Aguarda a área do gráfico ser calculada
              return generateHorizontalGradient(canvasCtx, chartArea);
            }
          }
        ]
      };
    })
    .catch(error => {
      console.log(error);
    });
});
</script>

<template>
  <div class="min-h-screen md:grid bg-gray-800">
    <Header></Header>
    <div class="flex">
        <Chart 
        type="bar" 
        :data="data" 
        :chartOptions="chartOptions" 
        class="w-full md:w-[30rem]" 
        />
        <Chart 
        type="bar" 
        :data="data2" 
        :chartOptions="chartOptions" 
        class="w-full md:w-[30rem]"/>
    </div>
  </div>
</template>