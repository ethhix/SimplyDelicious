document.getElementById("searchBtn").addEventListener("click", function () {
  var searchInput = document.getElementById("searchInput");
  if (searchInput.style.width === "100%" || searchInput.style.opacity === "1") {
    searchInput.style.width = "0";
    searchInput.style.opacity = "0";
  } else {
    searchInput.style.visibility = "visible";
    searchInput.style.width = "100%";
    searchInput.style.opacity = "1";
    searchInput.focus();
  }
});

document
  .getElementById("searchInput")
  .addEventListener("blur", function (event) {
    if (!event.target.value.trim()) {
      event.target.style.width = "0";
      event.target.style.opacity = "0";
    }
  });

$(document).ready(function () {
  $("forgotUsernameAnchor").click(function () {
    $("#forgotUsernameModal").modal("show");
  });

  $("#forgotUsernameModal").submit(function (event) {
    event.preventDefault();
  });
});

$(document).ready(function () {
  $("forgotPasswordAnchor").click(function () {
    $("#forgotPasswordModal").modal("show");
  });

  $("#forgotPasswordModal").submit(function (event) {
    event.preventDefault();
  });
});

$(document).ready(function () {
  $("loginSignupButton").click(function () {
    $("#signupModal").modal("show");
  });
});

$(document).ready(function () {
  $(".btn-back").click(function () {
    var targetModal = $(this).data("target");
    $(this).closest(".modal").modal("hide");
    $(targetModal).modal("show");
  });
});

$("#forgotUsernameAnchor").click(function (event) {
  event.preventDefault();
  event.stopPropagation();
  $("#loginModal").modal("hide");
  setTimeout(function () {
    $("#forgotUsernameModal").modal("show");
  }, 100);
});

$("#forgotPasswordAnchor").click(function (event) {
  event.preventDefault();
  event.stopPropagation();
  $("#loginModal").modal("hide");
  setTimeout(function () {
    $("#forgotPasswordModal").modal("show");
  }, 100);
});

$("#loginSignupButton").click(function (event) {
  event.preventDefault();
  event.stopPropagation();
  $("#loginModal").modal("hide");
  setTimeout(function () {
    $("#signupModal").modal("show");
  }, 100);
});

$(document).ready(function () {
  // Handle clicks on recipe cards to navigate
  $(document).on("click", ".recipe-card", function () {
    var recipeId = $(this).data("recipe-id");
    window.location.href = `getRecipesPage.php?recipeID=${recipeId}`;
  });

  // Prevent voting buttons from triggering navigation
  $(document).on("click", ".voting-buttons button", function (event) {
    event.stopPropagation(); // Prevents the event from bubbling up to the .recipe-card click event
    var $button = $(this);
    var recipeID = $button.closest(".recipe-card").data("recipe-id");
    var voteType = $button.hasClass("upvote") ? 1 : -1;
    handleVote(recipeID, voteType);
    $button.toggleClass("active");
    $button.siblings().removeClass("active");
  });

  function handleVote(recipeID, voteType) {
    $.ajax({
      url: "handleVote.php",
      type: "POST",
      data: { recipeID: recipeID, voteType: voteType },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          var $recipeCard = $(
            '.recipe-card[data-recipe-id="' + recipeID + '"]'
          );
          $recipeCard.find(".upvote span").text(response.upvotes);
          $recipeCard.find(".downvote span").text(response.downvotes);
        } else {
          alert("Failed to record vote: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error for vote: ", error);
        alert("Failed to process vote, please try again.");
      },
    });
  }
});

document
  .getElementById("preparation-time-slider")
  .addEventListener("input", function () {
    document.getElementById("preparation-time-output").value =
      this.value + " min";
  });

document
  .getElementById("cooking-time-slider")
  .addEventListener("input", function () {
    document.getElementById("cooking-time-output").value = this.value + " min";
  });

let isFirstNameValid = false;
let isLastNameValid = false;
let isEmailValid = false;
let isUsernameValid = false;

document.getElementById("firstName").addEventListener("input", function (e) {
  var value = e.target.value;
  var errorElement = document.getElementById("nameError");
  if (value.includes(" ")) {
    errorElement.textContent = "Only enter one word!";
    isFirstNameValid = false;
  } else if (/^[a-zA-Z]*$/.test(value)) {
    errorElement.textContent = "";
    isFirstNameValid = true;
  } else {
    errorElement.textContent = "Only letters allowed!";
    isFirstNameValid = false;
  }
});

document.getElementById("lastName").addEventListener("input", function (e) {
  var value = e.target.value;
  var errorElement = document.getElementById("nameError");

  if (value.includes(" ")) {
    errorElement.textContent = "Only enter one word!";
    isLastNameValid = false;
  } else if (/^[a-zA-Z]*$/.test(value)) {
    errorElement.textContent = "";
    isLastNameValid = true;
  } else {
    errorElement.textContent = "Only letters allowed!";
    isLastNameValid = false;
  }
});

document.getElementById("signupEmail").addEventListener("input", function (e) {
  var value = e.target.value;
  var errorElement = document.getElementById("emailError");

  if (/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(value)) {
    errorElement.textContent = "";
    isEmailValid = true;
  } else {
    errorElement.textContent = "Invalid email!";
    isEmailValid = false;
  }
});

document
  .getElementById("signupUsername")
  .addEventListener("input", function (e) {
    var value = e.target.value;
    var errorElement = document.getElementById("usernameError");

    if (/^[a-zA-Z0-9]*$/.test(value)) {
      errorElement.textContent = "";
      isUsernameValid = true;
    } else {
      errorElement.textContent =
        "Only letters and numbers allowed within username";
      isUsernameValid = false;
    }
  });

document
  .getElementById("signupForm")
  .addEventListener("submit", function (event) {
    if (
      !isFirstNameValid ||
      !isLastNameValid ||
      !isEmailValid ||
      !isUsernameValid
    ) {
      event.preventDefault();
      alert("Please correct the errors before submitting.");
    }
  });

let signUpstate = false;
let password = document.getElementById("signupPassword");
let passwordStrength = document.getElementById("password-strength");
let lowUpperCase = document.querySelector(".low-upper-case i");
let number = document.querySelector(".one-number i");
let specialChar = document.querySelector(".one-special-char i");
let eightChar = document.querySelector(".eight-character i");

password.addEventListener("keyup", function () {
  let pass = document.getElementById("signupPassword").value;
  checkStrength(pass);
});

function toggle() {
  if (signUpstate) {
    document.getElementById("signupPassword").setAttribute("type", "password");
    signUpstate = false;
  } else {
    document.getElementById("signupPassword").setAttribute("type", "text");
    signUpstate = true;
  }
}

function myFunction(show) {
  show.classList.toggle("fa-eye-slash");
}

function checkStrength(password) {
  let strength = 0;

  if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
    strength += 1;
    lowUpperCase.classList.remove("fa-circle");
    lowUpperCase.classList.add("fa-check");
  } else {
    lowUpperCase.classList.add("fa-circle");
    lowUpperCase.classList.remove("fa-check");
  }
  //If password has numbers and characters
  if (password.match(/([0-9])/)) {
    strength += 1;
    number.classList.remove("fa-circle");
    number.classList.add("fa-check");
  } else {
    number.classList.add("fa-circle");
    number.classList.remove("fa-check");
  }
  //If password has one special character
  if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
    strength += 1;
    specialChar.classList.remove("fa-circle");
    specialChar.classList.add("fa-check");
  } else {
    specialChar.classList.add("fa-circle");
    specialChar.classList.remove("fa-check");
  }
  //If password is greater than 7
  if (password.length > 7) {
    strength += 1;
    eightChar.classList.remove("fa-circle");
    eightChar.classList.add("fa-check");
  } else {
    eightChar.classList.add("fa-circle");
    eightChar.classList.remove("fa-check");
  }

  //If strength is less than 2
  if (strength < 2) {
    passwordStrength.classList.remove("progress-bar-warning");
    passwordStrength.classList.remove("progress-bar-success");
    passwordStrength.classList.add("progress-bar-danger");
    passwordStrength.style = "width: 10%";
  } else if (strength == 3) {
    passwordStrength.classList.remove("progress-bar-success");
    passwordStrength.classList.remove("progress-bar-danger");
    passwordStrength.classList.add("progress-bar-warning");
    passwordStrength.style = "width: 60%";
  } else if (strength == 4) {
    passwordStrength.classList.remove("progress-bar-warning");
    passwordStrength.classList.remove("progress-bar-danger");
    passwordStrength.classList.add("progress-bar-success");
    passwordStrength.style = "width: 100%";
  }
}

document
  .getElementById("signupForm")
  .addEventListener("submit", function (event) {
    let pass = document.getElementById("signupPassword").value;
    if (!isPasswordStrong(pass)) {
      event.preventDefault();
    }
  });

function isPasswordStrong(password) {
  let strength = 0;

  if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
    strength += 1;
  }
  if (password.match(/([0-9])/)) {
    strength += 1;
  }
  if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
    strength += 1;
  }
  if (password.length > 7) {
    strength += 1;
  }

  return strength >= 4;
}

$(document).ready(function () {
  $("#loginForm").on("submit", function (e) {
    e.preventDefault();

    var login = $("#username").val();
    var password = $("#password").val();

    $.ajax({
      type: "POST",
      url: "login.php",
      data: { loginUsername: login, loginPassword: password },
      success: function (response) {
        if (response.error) {
          $("#loginError").text(response.errorMessage);
        } else {
          window.location.href = "public/recipesPage.php";
        }
      },
    });
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const allRanges = document.querySelectorAll(".form-group");

  allRanges.forEach((wrap) => {
    const range = wrap.querySelector(".form-control-range");
    const bubble = wrap.querySelector(".bubble");

    if (range && bubble) {
      range.addEventListener("input", () => {
        setBubble(range, bubble);
      });
      setBubble(range, bubble);
    }
  });

  function setBubble(range, bubble) {
    const val = range.value;
    const min = range.min || 0;
    const max = range.max || 100;
    const newVal = Number(((val - min) * 100) / (max - min));
    bubble.innerHTML = `${val} min`;

    bubble.style.left = `calc(${newVal}% + (${8 - newVal * 0.15}px))`;
  }
});

let currentGroupIndex = 0;

const loadMoreButton = document.querySelector(".load-more-cuisines-button");
const loadLessButton = document.querySelector(".load-less-cuisines-button");
const buttonsContainer = document.querySelector(".load-more-less-btns");

loadMoreButton.addEventListener("click", function () {
  const groupToShow = document.querySelector(
    '.filter-options .cuisine-group[data-index="' + currentGroupIndex + '"]'
  );
  if (groupToShow) {
    groupToShow.style.display = "flex";
    groupToShow.style.display = "flex";
    groupToShow.style.flexWrap = "wrap";
    groupToShow.style.gap = "10px";
    currentGroupIndex++;
    console.log("Current Group Index:", currentGroupIndex);
  }

  //After showing a group, ensure the load less button is visible
  if (currentGroupIndex > 0) {
    buttonsContainer.style.display = "flex";
    buttonsContainer.style.gap = "10px";
    loadLessButton.style.display = "flex";
  }

  //Hide load more button if no more groups to show
  if (currentGroupIndex === 2) {
    loadMoreButton.style.display = "none";
  }
});

loadLessButton.addEventListener("click", function () {
  currentGroupIndex--;

  const groupToHide = document.querySelector(
    '.filter-options .cuisine-group[data-index="' + currentGroupIndex + '"]'
  );
  if (groupToHide) {
    groupToHide.style.display = "none"; //hide the previously shown group
    console.log("Current Group Index:", currentGroupIndex);
  }

  loadMoreButton.style.display = "flex";

  if (currentGroupIndex === 0) {
    loadLessButton.style.display = "none";
  }
});

//Tracks currently displayed group
let currentCookingGroupIndex = 0;

const loadMoreCookingButton = document.querySelector(
  ".load-more-cooking-button"
);
const loadLessCookingButton = document.querySelector(
  ".load-less-cooking-button"
);
const cookingButtonsContainer = document.querySelector(
  ".cooking-btn-container"
);

loadMoreCookingButton.addEventListener("click", function () {
  const groupToShow = document.querySelector(
    '.filter-options .cooking-group[data-index="' +
      currentCookingGroupIndex +
      '"]'
  );
  if (groupToShow) {
    groupToShow.style.display = "flex";
    groupToShow.style.flexWrap = "wrap";
    groupToShow.style.gap = "10px";
    currentCookingGroupIndex++;
  }

  if (currentCookingGroupIndex > 0) {
    cookingButtonsContainer.style.display = "flex";
    cookingButtonsContainer.style.gap = "10px";
    loadLessCookingButton.style.display = "flex";
  }

  if (currentCookingGroupIndex === 2) {
    loadMoreCookingButton.style.display = "none";
  }
});

loadLessCookingButton.addEventListener("click", function () {
  currentCookingGroupIndex--;

  const groupToHide = document.querySelector(
    '.filter-options .cooking-group[data-index="' +
      currentCookingGroupIndex +
      '"]'
  );
  if (groupToHide) {
    groupToHide.style.display = "none";
  }

  loadMoreCookingButton.style.display = "flex";

  if (currentCookingGroupIndex === 0) {
    loadLessCookingButton.style.display = "none";
  }
});

//Arrays to track loaded and selected ingredients
let allLoadedIngredients = [];
let selectedIngredients = [];

function createIngredientButton(ingredient) {
  const button = document.createElement("button");
  button.className = "preference-tag";
  button.textContent = ingredient;
  button.setAttribute("data-ingredient", ingredient);
  return button;
}

function displayIngredients() {
  const container = document.getElementById("ingredients-container");
  const showLessButton = document.getElementById("showLessIngredients");

  container.innerHTML = "";

  selectedIngredients.forEach((ingredient) => {
    const button = createIngredientButton(ingredient);
    button.classList.add("selected");
    container.appendChild(button);
  });

  allLoadedIngredients.slice(0, displayCount).forEach((ingredient) => {
    if (!selectedIngredients.includes(ingredient)) {
      const button = createIngredientButton(ingredient);
      container.appendChild(button);
    }
  });

  showLessButton.style.display = displayCount > 10 ? "block" : "none";
}

//track offset and set displayCount
let currentOffset = 0;
let displayCount = 10;

function loadMoreIngredients() {
  let dataToSend = {
    offset: currentOffset,
    search: "",
  };

  fetch("getIngredients.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(dataToSend),
  })
    .then((response) => response.json())
    .then((data) => {
      const newIngredients = data.filter(
        (ingredient) => !allLoadedIngredients.includes(ingredient)
      );
      allLoadedIngredients = allLoadedIngredients.concat(newIngredients);

      currentOffset += data.length;
      displayCount = Math.min(
        allLoadedIngredients.length,
        displayCount + data.length
      );
      displayIngredients();
    })
    .catch((error) => {
      console.error("Error fetching ingredients:", error);
    });
}

document
  .getElementById("showLessIngredients")
  .addEventListener("click", function () {
    displayCount = Math.max(10, displayCount - 10);
    displayIngredients();
  });

document
  .getElementById("ingredientSearch")
  .addEventListener("input", function () {
    const searchValue = this.value.trim();
    if (searchValue) {
      let dataToSend = {
        offset: 0,
        search: searchValue,
      };
      fetch("getIngredients.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(dataToSend),
      })
        .then((response) => response.json())
        .then((data) => {
          allLoadedIngredients = data;
          displayIngredients();
        });
    } else {
      currentOffset = 0; //reset offset
      loadMoreIngredients();
    }
  });

document
  .getElementById("loadMoreIngredients")
  .addEventListener("click", function () {
    loadMoreIngredients(currentOffset);
  });

loadMoreIngredients(0);

function updateSelectedIngredients(ingredient, isSelected) {
  const index = selectedIngredients.indexOf(ingredient);
  if (isSelected) {
    if (index === -1) {
      selectedIngredients.push(ingredient);
    }
  } else {
    if (index !== -1) {
      selectedIngredients.splice(index, 1);
    }
  }
  console.log(selectedIngredients);
  displayIngredients();
}

document
  .querySelector("#ingredients-container")
  .addEventListener("click", function (event) {
    if (event.target && event.target.matches(".preference-tag")) {
      let selectedCount = this.querySelectorAll(
        ".preference-tag.selected"
      ).length;

      let isAlreadySelected = event.target.classList.contains("selected");
      if (isAlreadySelected || selectedCount < 5) {
        event.target.classList.toggle("selected");
        updateSelectedIngredients(
          event.target.textContent,
          event.target.classList.contains("selected")
        );
      } else if (!isAlreadySelected && selectedCount >= 5) {
        console.log("You can't select more than 5 ingredients");
      }
    }
  });

document
  .getElementById("ingredientSearch")
  .addEventListener("input", function () {
    const searchValue = this.value.trim();
    const container = document.getElementById("ingredients-container");

    if (searchValue) {
      container.innerHTML = "";
      fetch("getIngredients.php?search=" + encodeURIComponent(searchValue))
        .then((response) => response.json())
        .then((data) => {
          displayIngredients(data);
        });
    } else {
      container.innerHTML = "";
      loadMoreIngredients(0);
    }
  });

$(document).ready(function () {
  $(".recipe-interactions").on("click", ".upvote, .downvote", function (event) {
    event.preventDefault();
    event.stopPropagation();

    var $button = $(this);
    var recipeID = $button.closest(".recipe-card").data("recipe-id");
    var isUpvote = $button.hasClass("upvote");
    var voteType = isUpvote ? 1 : -1;

    // Determine the other button based on whether this is an upvote or downvote
    var $otherButton = isUpvote
      ? $button.siblings(".downvote")
      : $button.siblings(".upvote");

    // Toggle the active class on this button and remove it from the other button
    if ($button.hasClass("active")) {
      $button.removeClass("active");
      voteType = 0; // User is toggling off their vote
    } else {
      $button.addClass("active");
      $otherButton.removeClass("active");
    }

    handleVote(recipeID, voteType);
  });

  function handleVote(recipeID, voteType) {
    console.log(
      "Sending vote for recipe ID: " + recipeID + " with vote type: " + voteType
    );
    $.ajax({
      url: "handleVote.php",
      type: "POST",
      data: { recipeID: recipeID, voteType: voteType },
      dataType: "json",
      success: function (response) {
        console.log("Response received: ", response);
        if (response.status === "success") {
          var $recipeCard = $(
            '.recipe-card[data-recipe-id="' + recipeID + '"]'
          );
          $recipeCard.find(".upvote span").text(response.upvotes);
          $recipeCard.find(".downvote span").text(response.downvotes);
        } else {
          alert("Failed to record vote: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error for vote: ", error);
        alert("Failed to process vote, please try again.");
      },
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    updateEventListeners();
  });

  function updateEventListeners() {
    document
      .querySelectorAll(
        ".recipe-interactions .upvote, .recipe-interactions .downvote"
      )
      .forEach((button) => {
        button.addEventListener("click", function () {
          const recipeID = this.closest(".recipe-card").dataset.recipeId;
          const voteType = this.classList.contains("upvote") ? 1 : -1;
          handleVote(recipeID, voteType);
        });
      });
  }

  //Bookmark handling
  $(".ui-checkbox").click(function (event) {
    event.stopPropagation(); // Stop propagation to keep event from affecting parent elements
    event.preventDefault(); // Additional safeguard
    bookmarkRecipe(this);
  });

  function bookmarkRecipe(element) {
    var recipeCard = $(element).closest(".recipe-card");
    var recipeID = recipeCard.data("recipe-id");
    var checkbox = $(element).find('input[type="checkbox"]');
    var isChecked = checkbox.prop("checked"); // Current checked state

    $.ajax({
      url: "bookmarkRecipe.php",
      type: "POST",
      data: {
        recipeID: recipeID,
        isBookmarked: !isChecked, // Attempt to toggle state
      },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          // Only toggle checkbox on success
          checkbox.prop("checked", !isChecked);
        } else {
          // Important: Ensure checkbox doesn't change if not successful
          checkbox.prop("checked", isChecked);
          console.error(response.message);
          if (response.message === "User not logged in") {
            $("#loginModal").modal("show");
          } else {
            alert(response.message);
          }
        }
      },
      error: function (xhr, status, error) {
        console.error("Bookmarking failed:", error);
        // Revert checkbox to original state on failure
        checkbox.prop("checked", isChecked);
        alert("Bookmarking failed, please try again.");
      },
    });
  }

  var form = document.getElementById("all-filters-form");
  console.log("Form element:", form);
  form.addEventListener("submit", function (event) {
    console.log("Form submission intercepted!");
    event.preventDefault();
    event.stopPropagation();

    var formData = new FormData(form);

    fetch("filterRecipes.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        console.log("Received response");
        return response.json();
      })
      .then((data) => {
        console.log("Data received:", data);
        if (data.error) {
          console.error("Error fetching recipes:", data.error);
        } else {
          updateRecipeCards(data);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });

  function updateRecipeCards(data) {
    var recipesGrid = document.getElementById("recipes-grid");
    recipesGrid.innerHTML = "";

    data.data.forEach((recipe) => {
      var cardHtml = `
        <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="recipe-card" data-recipe-id="${recipe.recipeID}">
            <img src="${recipe.ImagePath}" alt="${recipe.recipe_title}">
            <label class="ui-checkbox">
              <input type="checkbox" aria-label="Bookmark this recipe" class="bookmark-checkbox">
              <i class="fa fa-bookmark bookmark-icon"></i>
            </label>
            <div class="recipe-info">
              <h3>${recipe.recipe_title}</h3>
              <div class="recipe-interactions">
                <span class="comments"><i class="fa fa-comment"></i> ${recipe.commentCount} comments</span>
                <div class="voting-buttons">
                  <button class="upvote" onclick="handleVote(${recipe.recipeID}, 1)">
                    <i class="fa-solid fa-up-long"></i> <span>${recipe.upvotes}</span>
                  </button>
                  <button class="downvote" onclick="handleVote(${recipe.recipeID}, -1)">
                    <i class="fa-solid fa-down-long"></i> <span>${recipe.downvotes}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
      recipesGrid.innerHTML += cardHtml;
    });
  }
});
