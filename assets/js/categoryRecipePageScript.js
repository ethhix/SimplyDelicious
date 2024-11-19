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
          window.location.href = "recipesPage.php";
        }
      },
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const recipeCards = document.querySelectorAll(".recipe-card");
  recipeCards.forEach((card) => {
    card.addEventListener("click", function () {
      const recipeId = this.getAttribute("data-recipe-id");
      window.location.href = `getRecipesPage.php?recipeID=${recipeId}`;
    });
  });
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
          $recipeCard.find(".upvote span").text(response.upvotes); // Update upvote count
          $recipeCard.find(".downvote span").text(response.downvotes); // Update downvote count
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
});
