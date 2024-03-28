    
// const sideMenu = document.querySelector('aside');
// const menuBtn = document.getElementById('menu-btn');
// const closeBtn = document.getElementById('close-btn');

// const darkMode = document.querySelector('.dark-mode');




//     if (localStorage.getItem('theme') === null)localStorage.setItem('theme', "light");
//     function myFunction() {
//         let th = localStorage.getItem('theme')
//         if(th == 'dark'){
//             document.body.classList.remove('dark-mode-variables');
//         }else if(th =='light'){
//             document.body.classList.add('dark-mode-variables');
//         }
//         console.log(th)
  
// }

 

//     darkMode.addEventListener('click', () => {

//         document.body.classList.toggle('dark-mode-variables');
//         darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
//         darkMode.querySelector('span:nth-child(2)').classList.toggle('active');
//         let th = localStorage.getItem('theme')
//       if(th == 'light'){
        
        
//         localStorage.setItem('theme','dark')   
        
//       }else if(th == 'dark'){ 
       
       
//       localStorage.setItem('theme','light')

//       }
   

//     })



// window.onload = myFunction;
// const dashboard = document.querySelector(".Dashboard")
// const MyCourses = document.querySelector(".MyCourses")
// const UserManagement = document.querySelector(".User-Management")
// const Calender = document.querySelector(".Calender")
// const ManageCourses = document.querySelector(".Courses")

// console.log()
// const currentUrl = window.location.href;
// switch(currentUrl){
// case "https://yislms.com/yatharthriti/my/":
//   dashboard.classList.add('active')
//   break;
// case "https://yislms.com/yatharthriti/my/courses.php":
//   MyCourses.classList.add('active')
//   break;
// case "https://yislms.com/yatharthriti/admin/category.php?category=accounts#":
//   UserManagement.classList.add('active')
//   break;
// case "https://yislms.com/yatharthriti/calendar/view.php?view=month":
//   Calender.classList.add('active')
//   break;
// case "https://yislms.com/yatharthriti/course/management.php":
//   ManageCourses.classList.add('active')
//   break;
// }
// console.log(currentUrl);





var barChartOptions = {
  series: [{
    data: [10, 8, 6, 4, 2],
    name: "Products",
  }],
  chart: {
    type: "bar",
    background: "transparent",
    height: 350,
    toolbar: {
      show: false,
    },
  },
  colors: [
    "#2962ff",
    "#d50000",
    "#2e7d32",
    "#ff6d00",
    "#583cb3",
  ],
  plotOptions: {
    bar: {
      distributed: true,
      borderRadius: 4,
      horizontal: false,
      columnWidth: "40%",
    }
  },
  dataLabels: {
    enabled: false,
  },
  fill: {
    opacity: 1,
  },
  grid: {
    borderColor: "#55596e",
    yaxis: {
      lines: {
        show: true,
      },
    },
    xaxis: {
      lines: {
        show: true,
      },
    },
  },
  legend: {
    labels: {
      colors: "#f5f7ff",
    },
    show: true,
    position: "top",
  },
  stroke: {
    colors: ["transparent"],
    show: true,
    width: 2
  },
  tooltip: {
    shared: true,
    intersect: false,
    theme: "dark",
  },
  xaxis: {
    categories: ["Laptop", "Phone", "Monitor", "Headphones", "Camera"],
    title: {
      style: {
        color: "#f5f7ff",
      },
    },
    axisBorder: {
      show: true,
      color: "#55596e",
    },
    axisTicks: {
      show: true,
      color: "#55596e",
    },
    labels: {
      style: {
        colors: "#f5f7ff",
      },
    },
  },
  yaxis: {
    title: {
      text: "Count",
      style: {
        color:  "#f5f7ff",
      },
    },
    axisBorder: {
      color: "#55596e",
      show: true,
    },
    axisTicks: {
      color: "#55596e",
      show: true,
    },
    labels: {
      style: {
        colors: "#f5f7ff",
      },
    },
  }
};

var barChart = new ApexCharts(document.querySelector("#bar-chart"), barChartOptions);
barChart.render();

var areaChartOptions = {
  series: [{
    name: "Purchase Orders",
    data: [31, 40, 28, 51, 42, 109, 100],
  }, {
    name: "Sales Orders",
    data: [11, 32, 45, 32, 34, 52, 41],
  }],
  chart: {
    type: "area",
    background: "transparent",
    height: 350,
    stacked: false,
    toolbar: {
      show: false,
    },
  },
  colors: ["#00ab57", "#d50000"],
  labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
  dataLabels: {
    enabled: false,
  },
  fill: {
    gradient: {
      opacityFrom: 0.4,
      opacityTo: 0.1,
      shadeIntensity: 1,
      stops: [0, 100],
      type: "vertical",
    },
    type: "gradient",
  },
  grid: {
    borderColor: "#55596e",
    yaxis: {
      lines: {
        show: true,
      },
    },
    xaxis: {
      lines: {
        show: true,
      },
    },
  },
  legend: {
    labels: {
      colors: "#f5f7ff",
    },
    show: true,
    position: "top",
  },
  markers: {
    size: 6,
    strokeColors: "#1b2635",
    strokeWidth: 3,
  },
  stroke: {
    curve: "smooth",
  },
  xaxis: {
    axisBorder: {
      color: "#55596e",
      show: true,
    },
    axisTicks: {
      color: "#55596e",
      show: true,
    },
    labels: {
      offsetY: 5,
      style: {
        colors: "#f5f7ff",
      },
    },
  },
  yaxis: 
  [
    {
      title: {
        text: "Purchase Orders",
        style: {
          color: "#f5f7ff",
        },
      },
      labels: {
        style: {
          colors: ["#f5f7ff"],
        },
      },
    },
    {
      opposite: true,
      title: {
        text: "Sales Orders",
        style: {
          color:  "#f5f7ff",
        },
      },
      labels: {
        style: {
          colors: ["#f5f7ff"],
        },
      },
    },
  ],
  tooltip: {
    shared: true,
    intersect: false,
    theme: "dark",
  }
};

var areaChart = new ApexCharts(document.querySelector("#area-chart"), areaChartOptions);
areaChart.render();

