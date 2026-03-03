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
};
btnModalExpense.onclick = function () {
  modalExpense.style.display = "block";
};

for (let xEl of xEls) {
  xEl.onclick = function () {
    modalIncome.style.display = "none";
    modalExpense.style.display = "none";
  };
}

window.onclick = function (event) {
  if (event.target == modalIncome) {
    modalIncome.style.display = "none";
  }
  if (event.target == modalExpense) {
    modalExpense.style.display = "none";
  }
};

//get modal btn-icons
const btnIconModalIncome = document.querySelector("#icon-btn-modal-income");
const btnIconModalExpense = document.querySelector("#icon-btn-modal-expense");

btnIconModalIncome.onclick = function () {
  modalIncome.style.display = "block";
};
btnIconModalExpense.onclick = function () {
  modalExpense.style.display = "block";
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
var addCategorySelectedInForm = document.getElementById("incomeCategory");
const modalAddIncomeCategory = document.getElementById(
  "modal-add-income-category"
);

document.addEventListener("DOMContentLoaded", function () {
  addCategorySelectedInForm.addEventListener("change", function () {
    if (this.value === "add_new") {
      modalAddIncomeCategory.style.display = "block";
      this.value = "";
    }
  });
});

//AJAX INCOME CATEGORY
$(document).on("submit", "#form-add-category", function (e) {
  e.preventDefault();
  // e.stopPropagation();

  let form = $(this);

  $.ajax({
    url: "/api/addNewIncomeCategory",
    method: "POST",
    data: form.serialize(),
    dataType: "json",
    success: function (response) {
      let newId = response.id;
      let newName = response.name;

      let select = $("#incomeCategory");

      // Dodaj kategorię
      select.append(new Option(newName, newId));

      let newOption = new Option(newName, newId, true, true);
      select.append(newOption).trigger("change");

      // Zamknij modal dodawania kategorii
      modalAddIncomeCategory.style.display = "none";
      console.log("RESPONSE:", response);
    },
  });
});

//expenses
var addExpenseCategorySelectedInForm =
  document.getElementById("expenseCategory");
const modalAddExpenseCategory = document.getElementById(
  "modal-add-expense-category"
);

document.addEventListener("DOMContentLoaded", function () {
  addExpenseCategorySelectedInForm.addEventListener("change", function () {
    if (this.value === "add_new_expense_category") {
      modalAddExpenseCategory.style.display = "block";
      this.value = "";
    }
  });
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
$(document).ready(function () {
  $("#expenseCategory").select2({
    width: "100%",
    placeholder: "Choose category",
    allowClear: true,
    templateResult: function (data) {
      if (!data.id) return data.text;

      const limit = $(data.element).data("limit");
      const $container = $(`
        <div class="select2-option">
          <span class="category-name">${data.text}</span>
          ${limit ? `<span class="limit-label">${limit}</span>` : ""}
        </div>
      `);
      return $container;
    },
    templateSelection: function (data) {
      return data.text;
    },
  });
});
