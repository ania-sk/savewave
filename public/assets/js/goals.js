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
  //get edit buttons
  const contributionBtns = document.getElementsByClassName("btn--contribution");

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
