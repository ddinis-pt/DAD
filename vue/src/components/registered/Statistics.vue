<script setup>
import Header from '@/components/ui/Header.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Chart from 'primevue/chart';


const numberOfPlayers = ref(0);
const numberOfGames = ref(0);
const numberOfGamesLastWeek = ref(0);
const numberOfGamesLastMonth = ref(0);
const numberOfAdmins = ref(0);
const numberOfMultiplayerGames = ref(0);
const numberOfPurchases = ref(0);
const numberOfTransactions = ref(0);
const totalMoneySpent = ref(0);
const numberOfBrainCoins = ref(0);
const numberOfActiveUsers = ref(0);

const top5Buyers = ref(0);
const top5Spenders = ref(0);
const top5Winners = ref(0);


const mode = ref(null);

//TODO, atualizar para usar session ? talvez
const setCookie = (name, value, minutes) => {
    const now = new Date();
    now.setTime(now.getTime() + minutes * 60 * 1000);
    document.cookie = `${name}=${value}; expires=${now.toUTCString()}; path=/`;
};

const getCookie = (name) => {
    const cookies = document.cookie.split('; ');
    for (const cookie of cookies) {
      const [key, value] = cookie.split('=');
      if (key === name) {
        return value;
      }
    }
    return null;
};

const setMode = (chosenMode) => {
    mode.value = chosenMode;
    setCookie('modeChosen', chosenMode, 15); //Cookies expiram em 15 min
};

const top5Losers = ref(0);

let uniqueUsers = null;
let purchasesYearByWeek = null;
let gamesByStatus = null;
let gamesByBoard = null;
let blockedUsers = null;
let gamesByTypeAndMonth = null;
let brainCoinsUsed = null;

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

const generateHorizontalGradient = (ctx, chartArea, startingColor, endingColor) => {
  const { left, right } = chartArea;
  const gradient = ctx.createLinearGradient(left, 0, right, 0); // Gradiente horizontal
  gradient.addColorStop(0, startingColor);
  gradient.addColorStop(1, endingColor);
  return gradient;
};

/*
const changeMode = (mode) => {
  if (mode == 1) {
    mode.value = 'users'
  } else if (mode == 2) {
    mode.value = 'games'
  } else if (mode == 3) {
    mode.value = 'transactions'
  }
}*/

onMounted(() => {
  mode.value = getCookie('modeChosen') || 'users';

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

  const chartDataBlockedUsers = {
    labels: [],
    datasets: [
      {
        label: 'users',
        data: [],
        backgroundColor: ['#22cc90','#df6464','#f97316'],
        hoverBackgroundColor: ['#2bffb4','#ff7b72','#ff8516']
      }
    ]
  };

  const chartDataGamesByBoard = {
    labels: [],
    datasets: [
      {
        label: 'Games',
        data: [],
        backgroundColor: ['#fbcd8a','#b7e8a7','#c193b9'],
        borderColor: ['#ffdf8c','#bfffb7','#ffc2f4']
      }
    ]
  };

  const chartDateGamesByTypeAndMonth = {
    labels: [],
    datasets: [
      {
        label: 'Singleplayer',
        data: [],
        backgroundColor: ['#fbcd8a','#b7e8a7','#c193b9'],
        borderColor: ['#ffdf8c','#bfffb7','#ffc2f4']
      },
      {
        label: 'Multiplayer',
        data: [],
        backgroundColor: ['#fbcd8a','#b7e8a7','#c193b9'],
        borderColor: ['#ffdf8c','#bfffb7','#ffc2f4']
      }
    ]
  };

  const chartDataGamesCoinsUsed = {
    labels: [],
    datasets: [
      {
        label: 'Brain Coins',
        data: [],
        backgroundColor: null, 
        borderColor: '#ffffff'
      }
    ]
  };


  axios.get('stats/users/month')
    .then(response => {
      response.data.forEach(element => {
        switch (element.month){
          case 1:
            element.month = "January";
            break;
          case 2:
            element.month = "February";
            break;
          case 3:
            element.month = "March";
            break;
          case 4:
            element.month = "April";
            break;
          case 5:
            element.month = "May";
            break;
          case 6:
            element.month = "June";
            break;
          case 7:
            element.month = "July";
            break;
          case 8:
            element.month = "August";
            break;
          case 9:
            element.month = "September";
            break;
          case 10:
            element.month = "October";
            break;
          case 11:
            element.month = "November";
            break;
          case 12:
            element.month = "December";
            break;
        }
        chartData.labels.push(element.month);
        chartData.datasets[0].data.push(element.count);
      });

      //Gradient
      uniqueUsers = {
        ...chartData,
        datasets: [
          {
            ...chartData.datasets[0],
            backgroundColor: (ctx) => {
              const chart = ctx.chart;
              const { ctx: canvasCtx, chartArea } = chart;
              if (!chartArea) return;
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

      //Gradient
      purchasesYearByWeek = {
        ...chartData2,
        datasets: [
          {
            ...chartData2.datasets[0],
            backgroundColor: (ctx) => {
              const chart = ctx.chart;
              const { ctx: canvasCtx, chartArea } = chart;
              if (!chartArea) return;
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

        gamesByStatus = chartDataDoughnut;
      });
    })

    axios.get('stats/users/blocked').then(response => {
      response.data.forEach(element => {
        if(element.blocked == 1) {
          element.blocked = "Blocked";
        } else if (element.blocked == 0) {
          element.blocked = "Active";
        } else {
          element.blocked = "Deleted";
        }
        chartDataBlockedUsers.labels.push(element.blocked);
        chartDataBlockedUsers.datasets[0].data.push(element.count);
      });
      blockedUsers = chartDataBlockedUsers;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/games/total/board').then(response => {
      response.data.forEach(element => {
        chartDataGamesByBoard.labels.push(element.board_size);
        chartDataGamesByBoard.datasets[0].data.push(element.total_games);
      });
      gamesByBoard = chartDataGamesByBoard;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/games/total/typeAndMonth').then(response => {
      response.data.forEach(element => {
        chartDateGamesByTypeAndMonth.labels.push(element.month);
        chartDateGamesByTypeAndMonth.datasets[0].data.push(element.singleplayer);
        chartDateGamesByTypeAndMonth.datasets[1].data.push(element.multiplayer);
      });
      gamesByTypeAndMonth = {
        ...chartDateGamesByTypeAndMonth,
        datasets: [
          {
            ...chartDateGamesByTypeAndMonth.datasets[0],
            backgroundColor: (ctx) => {
              const chart = ctx.chart;
              const { ctx: canvasCtx, chartArea } = chart;
              if (!chartArea) return;
              return generateHorizontalGradient(canvasCtx, chartArea, 'rgba(72,198,247,0.3)' , 'rgba(72,198,247 ,1)');
            }
          },
          {
            ...chartDateGamesByTypeAndMonth.datasets[1],
            backgroundColor: (ctx) => {
              const chart = ctx.chart;
              const { ctx: canvasCtx, chartArea } = chart;
              if (!chartArea) return;
              return generateHorizontalGradient(canvasCtx, chartArea, 'rgba(247, 189, 72, 0.3)' , 'rgba(247, 189, 72, 1)');
            }
          }
        ]
      }
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/transactions/brainCoinsUsed')
    .then(response => {
      response.data.forEach(element => {
        chartDataGamesCoinsUsed.labels.push(element.day_of_week);
        chartDataGamesCoinsUsed.datasets[0].data.push(element.total_brain_coins);
      });

      //Gradient
      brainCoinsUsed = {
        ...chartDataGamesCoinsUsed,
        datasets: [
          {
            ...chartDataGamesCoinsUsed.datasets[0],
            backgroundColor: (ctx) => {
              const chart = ctx.chart;
              const { ctx: canvasCtx, chartArea } = chart;
              if (!chartArea) return;
              return generateHorizontalGradient(canvasCtx, chartArea, 'rgba(204, 204, 255, 0.6)' , 'rgba(204, 204, 255, 1)');
            }
          }
        ]
      };
    })
    .catch(error => {
      console.log(error);
    });

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

    axios.get('stats/winners/top5').then(response => {
      top5Winners.value = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/losers/top5').then(response => {
      top5Losers.value = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/users/admins').then(response => {
      numberOfAdmins.value = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/games/multiplayer').then(response => {
      numberOfMultiplayerGames.value = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/purchases/total').then(response => {
      numberOfPurchases.value = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/transactions/total').then(response => {
      numberOfTransactions.value = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/purchases/totalMoney').then(response => {
      totalMoneySpent.value = response.data;
    }).catch(error => {
      console.log(error);
    });
    
    axios.get('stats/users/totalBrainCoins').then(response => {
      numberOfBrainCoins.value = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('stats/users/active').then(response => {
      numberOfActiveUsers.value = response.data;
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
              @click.prevent="setMode('users')"
              :class="{ 'text-blue-500 border-b-2 !border-blue-500': mode === 'users' }"
            >
              <i class="pi pi-user"></i>
              Users
            </a>
            <a
              class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap  focus:border-blue-500 dark:focus:border-blue-500 neutral-700 hover:text-blue-500 dark:hover:text-blue-500 hover:cursor-pointer"
              @click.prevent="setMode('games')"
              :class="{ 'text-blue-500 border-b !border-blue-500': mode === 'games' }"
              aria-current="page"
            >
              <i class="pi pi-trophy"></i>
              Games
            </a>
            <a
              class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap  focus:border-blue-500 dark:focus:border-blue-500 neutral-700 hover:text-blue-500 dark:hover:text-blue-500 hover:cursor-pointer"
              @click.prevent="setMode('transactions')"
              :class="{ 'text-blue-500 border-b !border-blue-500': mode === 'transactions' }"
              aria-current="page"
            >
              <i class="pi pi-money-bill"></i>
              Transactions
            </a>
    </nav>
    <div v-if="mode === 'users'">
      <div class="flex flex-wrap justify-center gap-6 mt-20">
        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Number of Users Registered</h2>
          <p class="text-2xl font-bold text-blue-500">{{ numberOfPlayers }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Number of Admins</h2>
          <p class="text-2xl font-bold text-green-500">{{ numberOfAdmins }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Active Users this Month</h2>
          <p class="text-2xl font-bold text-red-500">{{ numberOfActiveUsers }}</p>
        </div>
        
        <!--
        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Games Played Last Week</h2>
          <p class="text-2xl font-bold text-orange-500">{{ numberOfGamesLastWeek }}</p>
        </div>-->
      </div>

      <div class="flex flex-wrap justify-center gap-4 mt-6">
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">New Users This Year</h2>
          <Chart 
            type="bar" 
            :data="uniqueUsers" 
            :chartOptions="chartOptions" 
            class="w-full md:w-[20rem] lg:w-[30rem]" 
          />
        </div>
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">User Distribuition</h2>
          <Chart 
            type="doughnut" 
            :data="blockedUsers"
            :chartOptions="chartOptionsDoughnut" 
            class="w-full md:w-[20rem]"
          />
        </div>  
      </div>
    </div>
    <div v-if="mode === 'games'">
      <div class="flex flex-wrap justify-center gap-6 mt-20">

        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Total Games Played</h2>
          <p class="text-2xl font-bold text-blue-500">{{ numberOfGames }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Total Multiplayer Games Played</h2>
          <p class="text-2xl font-bold text-green-500">{{ numberOfMultiplayerGames }}</p>
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
          <h2 class="text-md font-bold text-black mb-2">Games by Month</h2>
          <Chart 
            type="bar" 
            :data="gamesByTypeAndMonth" 
            :chartOptions="chartOptions" 
            class="w-full md:w-[20rem] lg:w-[30rem]" 
          />
        </div>
      </div>
      <div class="flex flex-wrap justify-center gap-4 mt-6">
        <!-- Tabela top 5 users que tem mais vitórias -->
        <div class="bg-white shadow-md rounded-lg p-4 text-center flex flex-col justify-top w-full max-w-sm">
          <h2 class="text-md font-bold text-black mb-4">Top 5 Winners</h2>
          <table class="table-auto w-full text-sm border-collapse">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-1 border-r border-gray-300 text-left">Name</th>
                <th class="px-3 py-1 text-right">Total Wins</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="winner in top5Winners" :key="winner.name" class="odd:bg-white even:bg-gray-50">
                <td class="px-3 py-1 border-r border-gray-300 text-left text-gray-600">{{ winner.name }}</td>
                <td class="px-3 py-1 text-right text-gray-600">{{ winner.total_victories }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Tabela top 5 users com mais derrotas WALL OF SHAME-->
        <div class="bg-white shadow-md rounded-lg p-4 text-center flex flex-col justify-center w-full max-w-sm">
          <h2 class="text-md font-bold text-black mb-4">Top 5 Losers</h2>
          <table class="table-auto w-full text-sm border-collapse">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-1 border-r border-gray-300 text-left">Name</th>
                <th class="px-3 py-1 text-right">Total Losses</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="loser in top5Losers" :key="loser.name" class="odd:bg-white even:bg-gray-50">
                <td class="px-3 py-1 border-r border-gray-300 text-left text-gray-600">{{ loser.name }}</td>
                <td class="px-3 py-1 text-right text-gray-600">{{ loser.total_defeats}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex flex-wrap justify-center gap-4 mt-6">
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">Games by status</h2>
          <Chart 
            type="doughnut"
            :data="gamesByStatus"
            :chartOptions="chartOptionsDoughnut"
            class="w-full md:w-[15rem]" />
        </div>
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">Games by Board</h2>
          <Chart 
            type="doughnut" 
            :data="gamesByBoard" 
            :chartOptions="chartOptionsDoughnut"
            class="w-full md:w-[15rem]" />
        </div>
      </div>
    </div>
    <div v-if="mode === 'transactions'">
      <div class="flex flex-wrap justify-center gap-6 mt-20">

        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Total transactions done</h2>
          <p class="text-2xl font-bold text-blue-500">{{ numberOfTransactions }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Number of Purchases</h2>
          <p class="text-2xl font-bold text-green-500">{{ numberOfPurchases }}</p>
        </div>
        
        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Total Money Spent</h2>
          <p class="text-2xl font-bold text-red-500">{{ totalMoneySpent }}€</p>
        </div>
        

        <div class="bg-white shadow-md rounded-lg p-4 text-center sm:h-24 md:h-30 lg:h-36 flex flex-col justify-center w-46">
          <h2 class="text-md font-semibold text-gray-700 mb-1">Total Number of Brain Coins</h2>
          <p class="text-2xl font-bold text-[#ffa500] flex justify-center ml-4">{{ numberOfBrainCoins }}
            <svg class="shrink-0 size-10 mt-[7px] ml-1" xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                    viewBox="0 0 40 40" fill="none" stroke="orange" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="8" cy="8" r="6" />
                    <path d="M18.09 10.37A6 6 0 1 1 10.34 18" />
                    <path d="M7 6h1v4" />
                    <path d="m16.71 13.88.7.71-2.82 2.82" />
                </svg>
          </p>
        </div>

      </div>

      <div class="flex flex-wrap justify-center gap-4 mt-6">
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">Purchases this year by week</h2>
          <Chart 
            type="bar" 
            :data="purchasesYearByWeek" 
            :chartOptions="chartOptions" 
            class="w-full md:w-[20rem]"
          />
        </div>  
        <div class="bg-white shadow-md rounded-lg p-4 text-center lg:h-full flex flex-col justify-center w-46">
          <h2 class="text-md font-bold text-black mb-2">Purchases this year by week</h2>
          <Chart 
            type="bar" 
            :data="brainCoinsUsed" 
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
    </div>
  </div>
</template>