//get modal btn-icons
const btnIconModalExpenseHeader = document.querySelector(
  "#header-icon-btn-modal-expense",
);

btnIconModalExpenseHeader.onclick = function () {
  modalExpense.style.display = "block";
  setTimeout(() => {
    const input = modalExpense.querySelector("#expenseAmount");
    if (input) input.focus();
  }, 10);
};

//EDIT EXPENSE MODAL
//get edit expense modal
const editExpenseModal = document.getElementById("modal-edit-expense");
//get X button
const closeButton = document.querySelector("#close-edit-expense-modal");

//get edit buttons
document
  .querySelector("#expensesTableBody")
  .addEventListener("click", function (event) {
    const editButton = event.target.closest(".btn--edit-expense");
    if (!editButton) return;

    const expenseId = editButton.dataset.expenseId;
    const expenseAmount = editButton.dataset.expenseAmount;
    const expenseCategoryId = editButton.dataset.expenseCategoryId;
    const expenseCategoryName = editButton.dataset.expenseCategoryName;
    const expenseCategoryActive = editButton.dataset.expenseCategoryActive;
    const expenseComment = editButton.dataset.expenseComment;
    const expenseDate = editButton.dataset.expenseDate;

    editExpenseModal.querySelector("#edit-expense-id").value = expenseId;
    editExpenseModal.querySelector("#edit-expense-amount").value =
      expenseAmount;
    editExpenseModal.querySelector("#edit-expense-comment").value =
      expenseComment;
    editExpenseModal.querySelector("#edit-expense-date").value = expenseDate;
    editExpenseModal.querySelector("#edit-expense-category").value =
      expenseCategoryId;

    if (expenseCategoryActive === "0") {
      const select = editExpenseModal.querySelector("#edit-expense-category");

      const option = document.createElement("option");
      option.value = expenseCategoryId;
      option.textContent = `(deleted) ${expenseCategoryName}`;
      option.selected = true;

      select.prepend(option);
    }

    editExpenseModal.style.display = "block";

    setTimeout(() => {
      editExpenseModal.querySelector("#edit-expense-amount").focus();
    }, 10);
  });

if (closeButton && editExpenseModal) {
  closeButton.onclick = function () {
    editExpenseModal.style.display = "none";
  };
}
// close modal
window.addEventListener("click", (event) => {
  if (event.target === editExpenseModal) {
    editExpenseModal.style.display = "none";
  }
});

//chart
document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("expensePieChart");
  if (!canvas) return;

  const labels = JSON.parse(canvas.dataset.expenseChartLabels);
  const data = JSON.parse(canvas.dataset.expenseChartData);

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

// pagination for the table
function loadPage(pageNumber) {
  const params = new URLSearchParams(window.location.search);
  params.set("page", pageNumber);

  fetch("/expenses?" + params.toString())
    .then((res) => res.text())
    .then((html) => {
      const doc = new DOMParser().parseFromString(html, "text/html");

      document.getElementById("expensesTableBody").innerHTML =
        doc.getElementById("expensesTableBody").innerHTML;

      document.getElementById("expensesTablePagination").innerHTML =
        doc.getElementById("expensesTablePagination").innerHTML;

      // scroll in the table
      document
        .querySelector(".transacrions-table-box")
        .scrollIntoView({ behavior: "smooth" });
    })
    .catch((err) => {
      console.error("Pagination error:", err);
    });
}
