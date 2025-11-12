document.addEventListener("DOMContentLoaded", () => {
  const limitValueEl = document.querySelector("#limitValue");
  const limitSpentEl = document.querySelector("#limitSpent");
  const limitLeftEl = document.querySelector("#limitLeft");
  const categorySelect = document.querySelector("#expenseCategory");
  const amountInput = document.querySelector("#expenseAmount");

  // box limits reset
  function resetLimitBox() {
    limitValueEl.textContent = "No limit set";
    limitSpentEl.textContent = "0";
    limitLeftEl.textContent = "NL";
    limitLeftEl.style.color = "";
    limitSpentEl.style.color = "";
  }

  // Updating the value of the limit box
  function updateLimitBox(data) {
    if (!data || data.error || !data.hasLimit) {
      resetLimitBox();
      return;
    }

    const limit = data.limit ?? 0;
    const spent = data.currentTotalExpense ?? 0;
    const newExpense = parseFloat(amountInput?.value) || 0;
    const left = Math.max(limit - spent - newExpense, 0);

    limitValueEl.textContent = `${limit.toFixed(2)} PLN`;
    limitSpentEl.textContent = `${(spent + newExpense).toFixed(2)} PLN`;
    limitLeftEl.textContent = `${left.toFixed(2)} PLN`;

    limitLeftEl.style.color =
      data.level === "danger"
        ? "red"
        : data.level === "warning"
        ? "orange"
        : "green";

    limitSpentEl.style.color =
      data.status === "exceeded"
        ? "red"
        : data.status === "warning"
        ? "orange"
        : "green";
  }

  // Fetching category limit via AJAX
  async function fetchCategoryLimit(categoryId, amount = 0) {
    if (
      !categoryId ||
      categoryId === "" ||
      categoryId === "add_new_expense_category"
    ) {
      resetLimitBox();
      return;
    }

    try {
      const res = await fetch(
        `/api/checkCategoryLimit?category_id=${categoryId}&amount=${amount}`,
        { headers: { "X-Requested-With": "XMLHttpRequest" } }
      );
      const data = await res.json();
      updateLimitBox(data);
    } catch (error) {
      console.error("Error fetching limit:", error);
      resetLimitBox();
    }
  }

  //Handle category change
  function handleCategoryChange(categoryId) {
    const amount = parseFloat(amountInput?.value) || 0;

    if (
      !categoryId ||
      categoryId === "" ||
      categoryId === "add_new_expense_category"
    ) {
      resetLimitBox();
      return;
    }

    fetchCategoryLimit(categoryId, amount);
  }

  // Select2 version
  if (typeof $ !== "undefined" && $.fn.select2) {
    $(categorySelect).on("select2:select select2:clear", function (e) {
      // `select2:clear` starts when click X or placeholder
      const categoryId = e.params?.data?.id || "";
      handleCategoryChange(categoryId);
    });

    //Extra security - react when placeholder appears
    $(categorySelect).on("change", function (e) {
      const value = $(this).val();
      handleCategoryChange(value);
    });
  } else if (categorySelect) {
    //Regular select (fallback)
    categorySelect.addEventListener("change", (e) => {
      handleCategoryChange(e.target.value);
    });
  }

  // Limit update when amount changes
  document.addEventListener("input", (e) => {
    if (e.target && e.target.id === "expenseAmount") {
      const amount = parseFloat(e.target.value) || 0;
      const categoryId = $(categorySelect).val();

      if (
        !categoryId ||
        categoryId === "" ||
        categoryId === "add_new_expense_category"
      ) {
        resetLimitBox();
        return;
      }

      fetchCategoryLimit(categoryId, amount);
    }
  });
});
