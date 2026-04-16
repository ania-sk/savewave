document.addEventListener("DOMContentLoaded", () => {
  const createDoughnutChart = (canvasId, labelsAttr, dataAttr) => {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    try {
      const labels = JSON.parse(canvas.dataset[labelsAttr]);
      const data = JSON.parse(canvas.dataset[dataAttr]);

      new Chart(canvas, {
        type: "doughnut",
        data: {
          labels: labels,
          datasets: [
            {
              data: data,
              backgroundColor: [
                "#4e73df",
                "#1cc88a",
                "#36b9cc",
                "#f6c23e",
                "#e74a3b",
                "#858796",
              ],
            },
          ],
        },
        options: {
          cutout: "50%",
          plugins: {
            legend: { position: "bottom" },
            datalabels: {
              color: "#fff",
              formatter: (val, ctx) => {
                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                return `${val} (${((val / total) * 100).toFixed(1)}%)`;
              },
            },
          },
        },
      });
    } catch (err) {
      console.error(`Błąd parsowania danych dla ${canvasId}:`, err);
    }
  };

  createDoughnutChart("incomePieChart", "incomeChartLabels", "incomeChartData");

  createDoughnutChart(
    "expensePieChart",
    "expenseChartLabels",
    "expenseChartData",
  );

  createDoughnutChart("goalPieChart", "goalChartLabels", "goalChartData");
});

document.addEventListener("DOMContentLoaded", () => {
  const balanceBoxEl = document.querySelector(".balance-box");
  const thumbDownIconEl = balanceBoxEl.querySelector(
    "ion-icon[name='thumbs-down-outline']",
  );
  if (thumbDownIconEl) {
    balanceBoxEl.style.borderColor = "red";
  }
});

// pagination for the table
function loadPage(pageNumber) {
  const params = new URLSearchParams(window.location.search);
  params.set("page", pageNumber);

  fetch("/balance?" + params.toString())
    .then((res) => res.text())
    .then((html) => {
      const doc = new DOMParser().parseFromString(html, "text/html");

      document.getElementById("transactionsTableBody").innerHTML =
        doc.getElementById("transactionsTableBody").innerHTML;

      document.getElementById("balanceTablePagination").innerHTML =
        doc.getElementById("balanceTablePagination").innerHTML;

      // scroll in the table
      document
        .querySelector(".transacrions-table-box")
        .scrollIntoView({ behavior: "smooth" });
    })
    .catch((err) => {
      console.error("Pagination error:", err);
    });
}
