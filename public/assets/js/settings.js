//EDIT CATEGORY MODAL

document.addEventListener("DOMContentLoaded", function () {
  //get edit category modal
  const editCategoryModal = document.getElementById("modal-edit-category");
  //get X button
  const closeButton = document.querySelector("#close-edit-category-modal");
  //get edit buttons
  const editButtons = document.getElementsByClassName("btn--edit");

  window.openEditCategoryModal = function (id, name, type) {
    editCategoryModal.style.display = "block";

    setTimeout(() => {
      document.querySelector("#edit-category-name").focus();
    }, 10);

    editCategoryModal.querySelector("input[name='categoryId']").value = id;
    editCategoryModal.querySelector("input[name='newCategoryName']").value =
      name;
    editCategoryModal.querySelector("input[name='categoryType']").value = type;
    editCategoryModal.querySelector("form").action = `/settings/${id}`;
  };

  closeButton.addEventListener("click", () => {
    editCategoryModal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === editCategoryModal) {
      editCategoryModal.style.display = "none";
    }
  });
});

//FLASH DIV
document.addEventListener("DOMContentLoaded", function () {
  //get flash div
  const flashDiv = document.querySelector(".flash");
  //get X button
  const closeButton = document.querySelector("#close-flash");

  closeButton.addEventListener("click", () => {
    flashDiv.style.display = "none";
  });
});

// ADD MONTHLY LIMIT MODAL
document.addEventListener("DOMContentLoaded", function () {
  //get add limit modal
  const limitModal = document.getElementById("modal-add-limit");
  //get close btn
  const closeButton = document.querySelector("#close-add-limit-modal");

  //global function to open modal with limit data
  window.openAddLimitModal = function (id, limit, type) {
    limitModal.style.display = "block";

    // set form action
    limitModal.querySelector("form").action = `/settings/${id}/limit`;

    // set category type
    limitModal.querySelector("input[name='categoryType']").value = type;

    // set limit value or placeholder
    const input = limitModal.querySelector("input[name='monthly_limit']");
    if (limit !== null) {
      input.value = limit;
      input.placeholder = "";
    } else {
      input.value = "";
      input.placeholder = "Enter monthly limit";
    }
  };

  // close modal on X click
  closeButton.addEventListener("click", () => {
    limitModal.style.display = "none";
  });

  // close modal on background click
  window.addEventListener("click", (event) => {
    if (event.target === limitModal) {
      limitModal.style.display = "none";
    }
  });
});
