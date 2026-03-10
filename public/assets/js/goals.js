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
  //get edit goal modal
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

document.querySelectorAll(".btn--contribution").forEach((button) => {
  button.addEventListener("click", function () {
    const goalId = this.dataset.goalId;
    const goalName = this.dataset.goalName;

    document.getElementById("contribution-goal-id").value = goalId;

    document.getElementById("contribution-goal-name").textContent = goalName;

    document.getElementById("modal-add-contribution").style.display = "block";
  });
});
