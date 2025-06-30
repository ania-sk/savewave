//get modal btn-icons
const btnIconModalExpenseHeader = document.querySelector(
  "#header-icon-btn-modal-expense"
);

btnIconModalExpenseHeader.onclick = function () {
  modalExpense.style.display = "block";
};

//EDIT EXPENSE MODAL

document.addEventListener("DOMContentLoaded", function () {
  //get edit expense modal
  const editExpenseModal = document.getElementById("modal-edit-expense");
  //get X button
  const closeButton = document.querySelector("#close-edit-expense-modal");
  //get edit buttons
  const editButtons = document.getElementsByClassName("btn--edit");

  if (editExpenseModal && editButtons.length > 0) {
    Array.from(editButtons).forEach((button) => {
      button.addEventListener("click", function () {
        editExpenseModal.style.display = "block";
      });
    });
  }

  if (closeButton && editExpenseModal) {
    closeButton.onclick = function () {
      editExpenseModal.style.display = "none";
    };
  }
});

//chart
document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("incomePieChart");
  if (!canvas) return;

  const labels = JSON.parse(canvas.dataset.chartLabels);
  const data = JSON.parse(canvas.dataset.chartData);

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
          formatter: (val, ctx) =>
            `${val} (${(
              (val / ctx.dataset.data.reduce((a, b) => a + b, 0)) *
              100
            ).toFixed(1)}%)`,
        },
      },
    },
  });
});
