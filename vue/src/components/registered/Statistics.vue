<script setup>
import Header from '@/components/ui/Header.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Chart from 'primevue/chart';


const numberOfPlayers = ref(0);
const numberOfGames = ref(0);
const numberOfGamesLastWeek = ref(0);
const numberOfGamesLastMonth = ref(0);

let chartDataDoughnut = null;
let chartOptionsDoughnut = null;

let data = null;
let data2 = null;
const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      labels: {
        color: '#FFF'
      }
    }
  },
  scales: {
    x: {
      ticks: {
        color: '#FFF'
      }
    },
    y: {
      ticks: {
        color: '#FFF'
      }
    }
  }
};
const setChartOptions = () => {
    return {
      plugins: {
        legend: {
            labels: {
              cutout: '60%',
              color: '#FFF'
            }
        }
      }
    };
};

const setChartData = () => {
  let games = {
        labels: [],
        datasets: [
            {
                data: [],
                backgroundColor: ['#6b7280','#09b9d7','#f97316','#9a08f9'],
                hoverBackgroundColor: ['#9ca3af','#22d3ee','#fb923c','#5c08a0']
            }
        ]
    };

  axios.get('stats/games/total/status')
    .then(response => {
      response.data.forEach(element => {
        var string = "";
        switch(element.status) {
          case "PE":
            string = "Pending";
            break;
          case "PL":
            string = 'In Progress';
            break;
          case "E":
            string = 'Ended';
            break;
          case "I":
            string = 'Interrupted';
            break;
        }
        games.labels.push(string);
        games.datasets[0].data.push(element.count);
      });
    })
    .catch(error => {
      console.log(error);
    });
    console.log(games)
    return games;
};

const generateHorizontalGradient = (ctx, chartArea) => {
  const { left, right } = chartArea;
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
        backgroundColor: null, 
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
        backgroundColor: null,
        borderColor: '#ffffff'
      }
    ]
  };


  axios.get('stats/users/month')
    .then(response => {
      response.data.forEach(element => {
        chartData.labels.push(element.month);
        chartData.datasets[0].data.push(element.count);
      });

      // Configurar o backgroundColor como uma função dinâmica
      data = {
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

    

    axios.get('stats/purchases/week')
    .then(response => {
      response.data.forEach(element => {
        chartData2.labels.push(element.week);
        chartData2.datasets[0].data.push(element.count);
      });

      // Configurar o backgroundColor como uma função dinâmica
      data2 = {
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

    chartDataDoughnut = setChartData();
    chartOptionsDoughnut = setChartOptions();

    // Gets para non-authenticated users
    axios.get('stats/users/total')
    .then(response => {
      numberOfPlayers.value = response.data;
    })
    .catch(error => {
      console.log(error);
    });

    axios.get('stats/games/total')
    .then(response => {
      numberOfGames.value = response.data;
    })
    .catch(error => {
      console.log(error);
    });

    axios.get('stats/games/lastWeek')
    .then(response => {
      numberOfGamesLastWeek.value = response.data;
    })
    .catch(error => {
      console.log(error);
    });

    axios.get('stats/games/lastMonth')
    .then(response => {
      numberOfGamesLastMonth.value = response.data;
    })
    .catch(error => {
      console.log(error);
    });


});
</script>

<template>
  <div class="min-h-screen bg-gray-800">
    <Header></Header>
    <div class="flex flex-wrap justify-center gap-6 mt-20">

      <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
        <h2 class="text-md font-semibold text-gray-700 mb-1">Number of Players Registered</h2>
        <p class="text-2xl font-bold text-blue-500">{{ numberOfPlayers }}</p>
      </div>

      <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
        <h2 class="text-md font-semibold text-gray-700 mb-1">Total Games Played</h2>
        <p class="text-2xl font-bold text-green-500">{{ numberOfGames }}</p>
      </div>
      
      <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
        <h2 class="text-md font-semibold text-gray-700 mb-1">Games Played Last Month</h2>
        <p class="text-2xl font-bold text-red-500">{{ numberOfGamesLastMonth }}</p>
      </div>
      

      <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
        <h2 class="text-md font-semibold text-gray-700 mb-1">Games Played Last Week</h2>
        <p class="text-2xl font-bold text-orange-500">{{ numberOfGamesLastWeek }}</p>
      </div>

    </div>

    <div class="flex flex-wrap justify-center gap-4 mt-6">
      <Chart 
        type="bar" 
        :data="data" 
        :chartOptions="chartOptions" 
        class="w-full md:w-[20rem]" 
      />
      <Chart 
        type="bar" 
        :data="data2" 
        :chartOptions="chartOptions" 
        class="w-full md:w-[20rem]" 
      />

      <Chart 
        type="doughnut" 
        :data="chartDataDoughnut" 
        :chartOptions="chartOptionsDoughnut" 
        class="w-full md:w-[30rem]" />

      
    </div>
  </div>
</template>