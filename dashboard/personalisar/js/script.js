// SIDEBAR TOGGLE

let sidebarOpen = false;
const sidebar = document.getElementById('sidebar');

function openSidebar() {
  if (!sidebarOpen) {
    sidebar.classList.add('sidebar-responsive');
    sidebarOpen = true;
  }
}

function closeSidebar() {
  if (sidebarOpen) {
    sidebar.classList.remove('sidebar-responsive');
    sidebarOpen = false;
  }
}

// Load specific page content into the main container
function loadPage(page) {
  fetch(page)
    .then((response) => response.text())
    .then((data) => {
      document.getElementById('main-content').innerHTML = data;
    })
    .catch((error) => console.error('Error loading page:', error));
}

// Initialize charts after page load
document.addEventListener("DOMContentLoaded", function() {
  // BAR CHART
  const barChartOptions = {
    // chart options...
  };
  const barChart = new ApexCharts(document.querySelector('#bar-chart'), barChartOptions);
  barChart.render();

  // AREA CHART
  const areaChartOptions = {
    // chart options...
  };
  const areaChart = new ApexCharts(document.querySelector('#area-chart'), areaChartOptions);
  areaChart.render();
});
