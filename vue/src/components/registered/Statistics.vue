<script setup>
import Header from '@/components/ui/Header.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Chart from 'primevue/chart';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';


const numberOfPlayers = ref(0);
const numberOfGames = ref(0);
const numberOfGamesLastWeek = ref(0);
const numberOfGamesLastMonth = ref(0);

const top5Buyers = ref();
const top5Spenders = ref();

const modeChosen = ref('users');

//let chartDataDoughnut = null;
//let chartOptionsDoughnut = null;

let data = null;
let data2 = null;
let data3 = null;
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
// const setChartOptions = () => {
//     return {
//       plugins: {
//         legend: {
//             labels: {
//               cutout: '60%',
//               color: '#FFF'
//             }
//         }
//       }
//     };
// };
const chartOptionsDoughnut = {
      responsive: true,
      plugins: {
              legend: {
                  labels: {
                    cutout: '60%',
                    color: '#FFF'
                  }
              }
            }
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
    return games;
};

const generateHorizontalGradient = (ctx, chartArea, startingColor, endingColor) => {
  const { left, right } = chartArea;
  const gradient = ctx.createLinearGradient(left, 0, right, 0); // Gradiente horizontal
  gradient.addColorStop(0, startingColor);
  gradient.addColorStop(1, endingColor);
  return gradient;
};

const changeMode = (mode) => {
  if (mode == 1) {
    modeChosen.value = 'users'
  } else if (mode == 2) {
    modeChosen.value = 'games'
  }
}

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
  const chartDataDoughnut = {
    labels: [],
    datasets: [
      {
        data: [],
        backgroundColor: ['#6b7280','#09b9d7','#f97316','#9a08f9'],
        hoverBackgroundColor: ['#9ca3af','#22d3ee','#fb923c','#5c08a0']
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
              return generateHorizontalGradient(canvasCtx, chartArea, 'rgba(31, 41, 55, 0.3)' , 'rgba(31, 41, 55, 1)');
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
        chartData2.labels.push(element.week+1);
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
              return generateHorizontalGradient(canvasCtx, chartArea, 'rgba(204, 204, 255, 0.6)' , 'rgba(204, 204, 255, 1)');
            }
          }
        ]
      };
    })
    .catch(error => {
      console.log(error);
    });

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
        chartDataDoughnut.labels.push(string);
        chartDataDoughnut.datasets[0].data.push(element.count);

        data3 = chartDataDoughnut;
      });
    })

    //chartDataDoughnut = setChartData();
    //chartOptionsDoughnut = setChartOptions();

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


    axios.get('stats/buyers/top5').then(response => {
      top5Buyers.value = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/spenders/top5').then(response => {
      top5Spenders.value = response.data;
    }).catch(error => {
      console.log(error);
    });
});
</script>

<template>
  <div class="min-h-screen bg-gray-800">
    <Header></Header>
    <nav class="-mb-0.5 flex flex-wrap gap-x-6 justify-center">
            <a
              class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap hs-tab-active:border  focus:border-blue-500 dark:focus:border-blue-500 hover:text-blue-500 dark:hover:text-blue-500 hover:cursor-pointer"
              @click.prevent="changeMode(1)"
              :class="{ 'text-blue-500 border-b-2 !border-blue-500': modeChosen === 'users' }"
            >
              <i class="pi pi-user"></i>
              Users
            </a>
            <a
              class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap  focus:border-blue-500 dark:focus:border-blue-500 neutral-700 hover:text-blue-500 dark:hover:text-blue-500 hover:cursor-pointer"
              @click.prevent="changeMode(2)"
              :class="{ 'text-blue-500 border-b !border-blue-500': modeChosen === 'games' }"
              aria-current="page"
            >
              <i class="pi pi-trophy"></i>
              Games
            </a>
    </nav>
    <div v-if="modeChosen === 'users'">
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
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">Unique Users this year</h2>
          <Chart 
            type="bar" 
            :data="data" 
            :chartOptions="chartOptions" 
            class="w-full md:w-[20rem]" 
          />
        </div>
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">Purchases this year by week</h2>
          <Chart 
            type="bar" 
            :data="data2" 
            :chartOptions="chartOptions" 
            class="w-full md:w-[20rem]"
          />
        </div>  
      </div>
      <div class="flex flex-wrap justify-center gap-4 mt-6">
        <!-- Tabela top 5 users que mais dinheiro gastaram em brain_coins -->
        <div class="bg-white shadow-md rounded-lg p-4 text-center flex flex-col justify-top w-full max-w-sm">
          <h2 class="text-md font-bold text-black mb-4">Top 5 Buyers</h2>
          <table class="table-auto w-full text-sm border-collapse">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-1 border-r border-gray-300 text-left">Name</th>
                <th class="px-3 py-1 text-right">Total Bought</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="buyer in top5Buyers" :key="buyer.name" class="odd:bg-white even:bg-gray-50">
                <td class="px-3 py-1 border-r border-gray-300 text-left text-gray-600">{{ buyer.name }}</td>
                <td class="px-3 py-1 text-right text-gray-600">{{ buyer.total_bought }}€</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Tabela top 5 users que mais gastaram brain_coins-->
        <div class="bg-white shadow-md rounded-lg p-4 text-center flex flex-col justify-center w-full max-w-sm">
          <h2 class="text-md font-bold text-black mb-4">Top 5 Spenders</h2>
          <table class="table-auto w-full text-sm border-collapse">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-1 border-r border-gray-300 text-left">Name</th>
                <th class="px-3 py-1 text-right">Total Spent (brain coins)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="spender in top5Spenders" :key="spender.name" class="odd:bg-white even:bg-gray-50">
                <td class="px-3 py-1 border-r border-gray-300 text-left text-gray-600">{{ spender.name }}</td>
                <td class="px-3 py-1 text-right text-gray-600">{{ parseInt(spender.total_spent) * -1.0}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex justify-center gap-4 mt-6">
        <Chart 
          type="doughnut" 
          :data="data3" 
          :chartOptions="chartOptionsDoughnut" 
          class="w-full md:w-[20rem]" />
      </div>
    </div>
    <div v-if="modeChosen === 'games'">
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
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">Unique Users this year</h2>
          <Chart 
            type="bar" 
            :data="data" 
            :chartOptions="chartOptions" 
            class="w-full md:w-[20rem]" 
          />
        </div>
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">Purchases this year by week</h2>
          <Chart 
            type="bar" 
            :data="data2" 
            :chartOptions="chartOptions" 
            class="w-full md:w-[20rem]"
          />
        </div>  
      </div>
      <div class="flex flex-wrap justify-center gap-4 mt-6">
        <!-- Tabela top 5 users que mais dinheiro gastaram em brain_coins -->
        <div class="bg-white shadow-md rounded-lg p-4 text-center flex flex-col justify-top w-full max-w-sm">
          <h2 class="text-md font-bold text-black mb-4">Top 5 Buyers</h2>
          <table class="table-auto w-full text-sm border-collapse">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-1 border-r border-gray-300 text-left">Name</th>
                <th class="px-3 py-1 text-right">Total Bought</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="buyer in top5Buyers" :key="buyer.name" class="odd:bg-white even:bg-gray-50">
                <td class="px-3 py-1 border-r border-gray-300 text-left text-gray-600">{{ buyer.name }}</td>
                <td class="px-3 py-1 text-right text-gray-600">{{ buyer.total_bought }}€</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Tabela top 5 users que mais gastaram brain_coins-->
        <div class="bg-white shadow-md rounded-lg p-4 text-center flex flex-col justify-center w-full max-w-sm">
          <h2 class="text-md font-bold text-black mb-4">Top 5 Spenders</h2>
          <table class="table-auto w-full text-sm border-collapse">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-1 border-r border-gray-300 text-left">Name</th>
                <th class="px-3 py-1 text-right">Total Spent (brain coins)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="spender in top5Spenders" :key="spender.name" class="odd:bg-white even:bg-gray-50">
                <td class="px-3 py-1 border-r border-gray-300 text-left text-gray-600">{{ spender.name }}</td>
                <td class="px-3 py-1 text-right text-gray-600">{{ parseInt(spender.total_spent) * -1.0}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex justify-center gap-4 mt-6">
        <Chart 
          type="doughnut" 
          :data="data3" 
          :chartOptions="chartOptionsDoughnut" 
          class="w-full md:w-[20rem]" />
      </div>
    </div>
  </div>
</template>