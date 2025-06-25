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
    editCategoryModal.querySelector("input[name='categoryId']").value = id;
    editCategoryModal.querySelector("input[name='categoryName']").value = name;
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
