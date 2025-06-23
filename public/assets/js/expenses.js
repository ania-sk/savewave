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
