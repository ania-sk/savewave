// SIDENAV HIDE
// get menu button
const toggleArrow = document.querySelector(".arrow-toggle");

//Function to set sidebar state based on localStorage
const setSidebarStateFromStorage = function () {
  const sidebarState = localStorage.getItem("sidebarState");
  if (sidebarState === "hidden") {
    document.body.classList.add("nav-hide");
  } else {
    document.body.classList.remove("nav-hide");
  }
};

setSidebarStateFromStorage();

const toggleSidebar = function () {
  document.body.classList.toggle("nav-hide");

  const newState = document.body.classList.contains("nav-hide")
    ? "hidden"
    : "visible";
  localStorage.setItem("sidebarState", newState);
};

toggleArrow.addEventListener("click", toggleSidebar);

// MODALS
//get modals
const modalIncome = document.querySelector("#modal-income");
const modalExpense = document.querySelector("#modal-expense");

//get modal buttons
const btnModalIncome = document.querySelector("#btn-modal-income");
const btnModalExpense = document.querySelector("#btn-modal-expense");

//get x  (income and expens modal)
const xEls = document.querySelectorAll(".close");

btnModalIncome.onclick = function () {
  modalIncome.style.display = "block";

  setTimeout(() => {
    modalIncome.querySelector("#amount").focus();
  }, 10);
};
btnModalExpense.onclick = function () {
  modalExpense.style.display = "block";

  setTimeout(() => {
    modalExpense.querySelector("#expenseAmount").focus();
  }, 10);
};

for (let xEl of xEls) {
  xEl.onclick = function () {
    modalIncome.style.display = "none";
    modalExpense.style.display = "none";
  };
}

//get modal btn-icons
const btnIconModalIncome = document.querySelector("#icon-btn-modal-income");
const btnIconModalExpense = document.querySelector("#icon-btn-modal-expense");

btnIconModalIncome.onclick = function () {
  modalIncome.style.display = "block";
  setTimeout(() => {
    modalIncome.querySelector("#amount").focus();
  }, 10);
};
btnIconModalExpense.onclick = function () {
  modalExpense.style.display = "block";
  setTimeout(() => {
    modalExpense.querySelector("#expenseAmount").focus();
  }, 10);
};

//errors in income form
document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("modal-income-open")) {
    modalIncome.style.display = "block";
  }
});
//errors in expense form
document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("modal-expense-open")) {
    modalExpense.style.display = "block";
  }
});

//ADD CATEGORY MODALS
//incomes
const modalAddIncomeCategory = document.getElementById(
  "modal-add-income-category",
);

document.addEventListener("DOMContentLoaded", function () {
  const selects = [
    document.getElementById("incomeCategory"),
    document.getElementById("edit-income-category"),
  ];

  selects.forEach(function (select) {
    if (!select) return;

    if (select.value === "add_new") {
      openAddCategoryModal(select);
    }

    select.addEventListener("change", handleCategoryChange);

    function handleCategoryChange(e) {
      if (e.target.value === "add_new") {
        openAddCategoryModal(e.target);
      }
    }

    function openAddCategoryModal() {
      modalAddIncomeCategory.style.display = "block";
      setTimeout(() => {
        modalAddIncomeCategory.querySelector("#newCategoryName").focus();
      }, 10);
      select.value = "";
    }
  });
});

// AJAX INCOME CATEGORY
$(document).on("submit", "#form-add-income-category", function (e) {
  e.preventDefault();

  let form = $(this);

  $.ajax({
    url: "/api/addNewIncomeCategory",
    method: "POST",
    data: form.serialize(),
    dataType: "json",

    success: function (response) {
      let newId = response.id;
      let newName = response.name;

      let select;

      if ($("#modal-edit-income").css("display") === "block") {
        select = $("#edit-income-category");
      } else {
        select = $("#incomeCategory");
      }

      let newOption = new Option(newName, newId, true, true);
      select.append(newOption);
      select.val(newId);

      $("#income-category-error").remove();

      modalAddIncomeCategory.style.display = "none";
    },

    error: function (xhr) {
      console.log("AJAX ERROR:", xhr);

      if (xhr.status === 422) {
        let data = JSON.parse(xhr.responseText);

        $("#income-category-error").remove();

        $("#newCategoryName").after(`
          <div id="income-category-error" class="error-wrapper">
              <p class="error-text">${data.errors.newCategoryName[0]}</p>
              <ion-icon class="error-icon" name="alert"></ion-icon>
          </div>
        `);
      }

      return false;
    },
  });

  return false;
});

//expenses
const modalAddExpenseCategory = document.getElementById(
  "modal-add-expense-category",
);

$("#expenseCategory, #edit-expense-category").on(
  "select2:select",
  function (e) {
    const selectedValue = e.params.data.id;

    if (selectedValue === "add_new_expense_category") {
      $(this).val(null).trigger("change");

      modalAddExpenseCategory.style.display = "block";

      setTimeout(() => {
        document.querySelector("#newExpenseCategoryName").focus();
      }, 10);
    }
  },
);

//AJAX EXPENSE CATEGORY
$(document).on("submit", "#form-add-expense-category", function (e) {
  e.preventDefault();

  let form = $(this);

  $.ajax({
    url: "/api/addNewExpenseCategory",
    method: "POST",
    data: form.serialize(),
    dataType: "json",
    success: function (response) {
      let newId = response.id;
      let newName = response.name;

      let select;

      if ($("#modal-edit-expense").css("display") === "block") {
        select = $("#edit-expense-category");
      } else {
        select = $("#expenseCategory");
      }

      let newOption = new Option(newName, newId, true, true);
      select.append(newOption).trigger("change");

      $("#expense-category-error").remove();

      modalAddExpenseCategory.style.display = "none";
    },

    error: function (xhr) {
      if (xhr.status === 422) {
        let data = JSON.parse(xhr.responseText);

        $("#expense-category-error").remove();

        $("#newExpenseCategoryName").after(
          `
  <div id="expense-category-error" class="error-wrapper">
      <p class="error-text">${data.errors.newCategoryName[0]}</p>
      <ion-icon class="error-icon" name="alert"></ion-icon>
  </div>
`,
        );
      }
      return false;
    },
  });
  return false;
});

//get x  (add category)
const xAddCategory = document.querySelectorAll(".close-add-category");

xAddCategory.forEach(function (element) {
  element.onclick = function () {
    modalAddIncomeCategory.style.display = "none";
    modalAddExpenseCategory.style.display = "none";
  };
});

//errors in add category
document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("modal-add-income-category-open")) {
    modalAddIncomeCategory.style.display = "block";
  }
});

document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("modal-add-expense-category-open")) {
    modalAddExpenseCategory.style.display = "block";
  }
});

//expense category limit labels in modal
function initExpenseSelect2(selector) {
  $(selector).select2({
    width: "100%",
    placeholder: "Choose category",
    allowClear: true,
    templateResult: function (data) {
      if (!data.id) return data.text;

      const limit = $(data.element).data("limit");
      return $(`
        <div class="select2-option">
          <span class="category-name">${data.text}</span>
          ${limit ? `<span class="limit-label">${limit}</span>` : ""}
        </div>
      `);
    },
    templateSelection: function (data) {
      return data.text;
    },
  });
}

$(document).ready(function () {
  initExpenseSelect2("#expenseCategory");
  initExpenseSelect2("#edit-expense-category");
});

window.onclick = function (event) {
  if (event.target == modalIncome) {
    modalIncome.style.display = "none";
  }
  if (event.target == modalExpense) {
    modalExpense.style.display = "none";
  }
  if (event.target == modalAddIncomeCategory) {
    modalAddIncomeCategory.style.display = "none";
  }
  if (event.target == modalAddExpenseCategory) {
    modalAddExpenseCategory.style.display = "none";
  }
};

// HAMBURGER TOGGLE MENU FOR MOBILE
document.querySelector(".hamburger-menu").addEventListener("click", () => {
  document.querySelector("body").classList.toggle("nav-hide");
});
