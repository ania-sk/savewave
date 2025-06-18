//get modal btn-icon
const btnIconModalIncomeHeader = document.querySelector(
  "#header-icon-btn-modal-income"
);

btnIconModalIncomeHeader.onclick = function () {
  modalIncome.style.display = "block";
};

//EDIT INCOME MODAL

document.addEventListener("DOMContentLoaded", function () {
  //get edit income modal
  const editIncomeModal = document.getElementById("modal-edit-income");
  //get X button
  const closeButton = document.querySelector("#close-edit-income-modal");
  //get edit buttons
  const editButtons = document.getElementsByClassName("btn--edit");

  if (editIncomeModal && editButtons.length > 0) {
    Array.from(editButtons).forEach((button) => {
      button.addEventListener("click", function () {
        editIncomeModal.style.display = "block";
      });
    });
  }

  if (closeButton && editIncomeModal) {
    closeButton.onclick = function () {
      editIncomeModal.style.display = "none";
    };
  }
});
