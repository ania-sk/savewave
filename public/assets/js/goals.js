//ADD GOAL MODAL
document.addEventListener("DOMContentLoaded", function () {
  const modalGoal = document.querySelector("#modal-goal");
  const btnModalGoal = document.querySelector("#btn-modal-goal");
  const xButton = document.querySelector("#close-goal-modal");

  btnModalGoal.onclick = function () {
    modalGoal.style.display = "block";
  };

  if (xButton && modalGoal) {
    xButton.onclick = function () {
      modalGoal.style.display = "none";
    };
  }

  window.addEventListener("click", function (event) {
    if (event.target === modalGoal) {
      modalGoal.style.display = "none";
    }
  });
});
//EDIT GOAL MODAL
document.addEventListener("DOMContentLoaded", function () {
  //get edit goal modal
  const editGoalModal = document.getElementById("modal-edit-goal");
  //get X button
  const closeButton = document.querySelector("#close-edit-goal-modal");
  //get edit buttons
  const editButtons = document.getElementsByClassName("btn--edit");

  if (editGoalModal && editButtons.length > 0) {
    Array.from(editButtons).forEach((button) => {
      button.addEventListener("click", function () {
        editGoalModal.style.display = "block";
      });
    });
  }

  if (closeButton && editGoalModal) {
    closeButton.onclick = function () {
      editGoalModal.style.display = "none";
    };
  }

  window.addEventListener("click", function (event) {
    if (event.target === editGoalModal) {
      editGoalModal.style.display = "none";
    }
  });
});

//EDIT GOAL MODAL
document.querySelectorAll(".btn--edit").forEach((button) => {
  button.addEventListener("click", async function () {
    const id = this.dataset.id;
    const goalName = this.dataset.goalName;
    const goalDescription = this.dataset.goalDescription;
    const goalAmount = this.dataset.goalAmount;
    const deadline = this.dataset.deadline;

    document.getElementById("edit-goal-name").value = goalName;
    document.getElementById("edit-goal-description").value = goalDescription;
    document.getElementById("edit-goal-amount").value = goalAmount;
    document.getElementById("edit-goal-date").value = deadline;

    document.querySelector("input[name='goalId']").value = id;

    document.getElementById("modal-edit-goal").style.display = "block";

    // console.log({
    //   id,
    //   goalName,
    //   goalDescription,
    //   goalAmount,
    //   deadline,
    // });
  });
});

//CONTRIBUTIONS
document.addEventListener("DOMContentLoaded", function () {
  //get add contribution modal
  const contributionModal = document.getElementById("modal-add-contribution");
  //get X button
  const closeButton = document.querySelector("#close-contribution-modal");
  //get contribution buttons
  const contributionBtns = document.querySelectorAll(".btn--contribution");

  contributionBtns.forEach((btn) => {
    const spanAdd = btn.querySelector(".btn-contribution-span");
    const spanLack = btn.querySelector(".btn-contribution-span-lack");
    const icon = btn.querySelector(".contribution--icon");
    const lackIcon = btn.querySelector(".lack-of-funds--icon");

    const isDisabled = btn.disabled;

    btn.classList.toggle("disabled", isDisabled);
    spanAdd.style.display = isDisabled ? "none" : "block";
    spanLack.style.display = isDisabled ? "block" : "none";
    icon.style.display = isDisabled ? "none" : "block";
    lackIcon.style.display = isDisabled ? "block" : "none";
  });

  if (contributionModal && contributionBtns.length > 0) {
    Array.from(contributionBtns).forEach((button) => {
      button.addEventListener("click", function () {
        contributionModal.style.display = "block";
      });
    });
  }

  if (closeButton && contributionModal) {
    closeButton.onclick = function () {
      contributionModal.style.display = "none";
    };
  }

  window.addEventListener("click", function (event) {
    if (event.target === contributionModal) {
      contributionModal.style.display = "none";
    }
  });
});

//add contribution
document.querySelectorAll(".btn--contribution").forEach((button) => {
  button.addEventListener("click", function () {
    const goalId = this.dataset.goalId;
    const goalName = this.dataset.goalName;

    document.getElementById("contribution-goal-id").value = goalId;

    document.getElementById("contribution-goal-name").textContent = goalName;
    document.getElementById("contribution-goal-name-input").value = goalName;

    document.getElementById("modal-add-contribution").style.display = "block";
  });
});

// --- EDIT CONTRIBUTION MODAL ---
//get modal
const editContributionModal = document.getElementById(
  "modal-edit-contribution",
);
// get X button
const closeEditContributionModal = document.getElementById(
  "close-edit-contribution-modal",
);

// get edit buttons and open and fill modal
document.querySelectorAll(".btn--edit-contribution").forEach((button) => {
  button.addEventListener("click", function () {
    const contributionId = this.dataset.contributionId;
    const contributionAmount = this.dataset.contributionAmount;
    const contributionGoalName = this.dataset.contributionGoalName;

    document.getElementById("edit-contribution-id").value = contributionId;
    document.getElementById("edit-contribution-amount").value =
      contributionAmount;
    document.getElementById("edit-contribution-goal-name").textContent =
      contributionGoalName;
    document.getElementById("edit-contribution-goal-name-input").value =
      contributionGoalName;
    document.getElementById("old-contribution-amount").value =
      contributionAmount;

    editContributionModal.style.display = "block";
  });
});

// close modal with X
closeEditContributionModal.addEventListener("click", () => {
  editContributionModal.style.display = "none";
});

// close modal
window.addEventListener("click", (event) => {
  if (event.target === editContributionModal) {
    editContributionModal.style.display = "none";
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const balanceBoxEl = document.querySelector(".balance-box");
  const thumbDownIconEl = balanceBoxEl.querySelector(
    "ion-icon[name='thumbs-down-outline']",
  );
  if (thumbDownIconEl) {
    balanceBoxEl.style.borderColor = "red";
  }
});

//errors in add goal form
const modalGoal = document.querySelector("#modal-goal");
document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("modal-add-goal-open")) {
    modalGoal.style.display = "block";
  }
});
//errors in edit goal form
const modalEditGoal = document.querySelector("#modal-edit-goal");
document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("modal-edit-goal-open")) {
    modalEditGoal.style.display = "block";
  }
});
//errors in add contribution form
const modalContribution = document.querySelector("#modal-add-contribution");
document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("modal-add-contribution-open")) {
    modalContribution.style.display = "block";
  }
});

//errors in edit contribution form
const modalEditContribution = document.querySelector(
  "#modal-edit-contribution",
);
document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("modal-edit-contribution-open")) {
    modalEditContribution.style.display = "block";
  }
});
// /////////////////
// GOAL CARDS /////
// ///////////////
const panel = document.querySelector(".side-panel");
const overlay = document.querySelector(".overlay");

const els = {
  name: document.querySelector(".goal-name"),
  desc: document.querySelector(".goal-description"),
  saved: document.querySelector(".goal-saved"),
  target: document.querySelector(".goal-target"),
  remaind: document.querySelector(".goal-remaind"),
  deadline: document.querySelector(".goal-deadline"),
  progress: document.querySelector(".panel-progress"),
  percent: document.querySelector(".number-progress"),
  list: document.querySelector(".goal-contributions"),
  addContribution: document.querySelector(".btn-panel-contribution"),
  achivedBox: document.querySelector("#panel-goal-achieved-box"),
};

// Open / close panel
const openPanel = () => {
  panel.classList.add("active");
  overlay.classList.add("active");
};

const closePanel = () => {
  panel.classList.remove("active");
  overlay.classList.remove("active");
};

overlay.addEventListener("click", closePanel);
document.querySelector(".close-btn").addEventListener("click", closePanel);

// cards
document.querySelectorAll(".goal-card").forEach((card) => {
  card.addEventListener("click", (e) => {
    if (e.target.closest("button")) return;

    const d = card.dataset;
    const goalAchieved = d.achieved === "1";

    els.name.textContent = d.name;
    els.desc.textContent = d.description;
    els.saved.textContent = `${d.saved} zł`;
    els.target.textContent = `${d.target} zł`;
    els.remaind.textContent = `${d.remaind} zł`;
    els.deadline.textContent = d.deadline;
    els.progress.style.width = `${d.progress}%`;
    els.percent.textContent = `${d.progress}%`;

    if (goalAchieved) {
      els.addContribution.style.display = "none";
      els.achivedBox.style.display = "flex";
    } else {
      els.addContribution.style.display = "flex";
      els.achivedBox.style.display = "none";
    }

    // ŁADOWANIE SKŁADEK
    els.list.innerHTML = "Loading...";
    fetch(`/goals/${d.id}/contributions`)
      .then((r) => r.json())
      .then((items) => {
        els.list.innerHTML = "";

        if (!items || items.length === 0) {
          els.list.innerHTML = "<li>No contributions yet</li>";
        } else {
          const tpl = document.querySelector("#contribution-template");

          items.forEach((c) => {
            const li = tpl.content.cloneNode(true);
            li.querySelector(".contribution-text").textContent =
              `${c.amount} zł — ${c.date}`;
            els.list.appendChild(li);
          });
        }
      });

    // Add contribution
    els.addContribution.onclick = () => {
      closePanel();
      document.querySelector(`[data-goal-id="${d.id}"]`)?.click();
    };

    openPanel();
  });
});

// MENU ⋯
document.querySelectorAll(".menu-trigger").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    const menu = btn.nextElementSibling;

    document.querySelectorAll(".menu-dropdown").forEach((m) => {
      m.style.display =
        m === menu && m.style.display !== "flex" ? "flex" : "none";
    });
  });
});

// ZAMYKANIE MENU POZA
document.addEventListener("click", () => {
  document
    .querySelectorAll(".menu-dropdown")
    .forEach((m) => (m.style.display = "none"));
});
