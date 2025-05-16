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

//TOOLTIPS
// const icons = document.querySelectorAll(".nav-icon");

// icons.forEach((icon) => {
//   icon.addEventListener("mouseenter", function () {
//     const tooltip = document.createElement("div");
//     tooltip.className = "tooltip";
//     tooltip.innerText = icon.getAttribute("data-tooltip");

//     icon.appendChild(tooltip);

//     const iconRect = icon.getBoundingClientRect();
//     tooltip.style.left = `${iconRect.width + 10}px`;
//     tooltip.style.top = `${iconRect.height / 2 - tooltip.offsetHeight / 2}px`;
//   });

//   icon.addEventListener("mouseleave", function () {
//     const tooltip = icon.querySelector(".tooltip");
//     if (tooltip) {
//       tooltip.remove();
//     }
//   });
// });

// MODALS
//get modals
const modalIncome = document.querySelector("#modal-income");
const modalExpense = document.querySelector("#modal-expense");

//get modal buttons
const btnModalIncome = document.querySelector("#btn-modal-income");
const btnModalExpense = document.querySelector("#btn-modal-expense");

//get x
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
