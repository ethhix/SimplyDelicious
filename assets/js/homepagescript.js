document.getElementById("searchBtn").addEventListener("click", function () {
  var searchInput = document.getElementById("searchInput");
  if (searchInput.style.width === "100%" || searchInput.style.opacity === "1") {
    searchInput.style.width = "0"; //Contract input
    searchInput.style.opacity = "0"; //No longer visible
  } else {
    searchInput.style.visibility = "visible";
    searchInput.style.width = "100%"; //Expand input
    searchInput.style.opacity = "1"; //Search input is visible again
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
  // Setup modal interactions
  $(".modal-trigger").click(function (event) {
    event.preventDefault();
    var targetModal = $(this).data("target-modal");
    var hideModal = $(this).data("hide-modal");

    $(hideModal).modal("hide");
    setTimeout(() => $(targetModal).modal("show"), 100);
  });

  //Handle form submissions inside modals
  $(".modal-form").submit(function (event) {
    event.preventDefault(); // Prevent default form submission
  });

  $(".btn-back").click(function () {
    var targetModal = $(this).data("target");
    //Close the current modal
    $(this).closest(".modal").modal("hide");
    //Open the target modal
    $(targetModal).modal("show");
  });
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

function changeEyeIcon(show) {
  show.classList.toggle("fa-eye-slash");
}

document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const sectionToShow = urlParams.get("section");

  function showSection(targetId) {
    // Hide all sections
    document.querySelectorAll(".settings-section").forEach((section) => {
      section.style.display = "none";
    });

    //Show the targeted section
    const targetSection = document.getElementById(targetId);
    if (targetSection) {
      targetSection.style.display = "block";
    } else {
      console.error("No section found with ID:", targetId);
    }
  }

  // Show the section based on URL parameter
  if (sectionToShow) {
    showSection(sectionToShow);
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

    //Determine the other button based on whether this is an upvote or downvote
    var $otherButton = isUpvote
      ? $button.siblings(".downvote")
      : $button.siblings(".upvote");

    //Toggle the active class on this button and remove it from the other button
    if ($button.hasClass("active")) {
      $button.removeClass("active");
      voteType = 0; //user is toggling off their vote
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
      url: "../handleVote.php",
      type: "POST",
      data: { recipeID: recipeID, voteType: voteType },
      dataType: "json",
      success: function (response) {
        console.log("Response received: ", response);
        if (response.status === "success") {
          var $recipeCard = $(
            '.recipe-card[data-recipe-id="' + recipeID + '"]'
          );
          $recipeCard.find(".upvote span").text(response.upvotes); //update upvote count
          $recipeCard.find(".downvote span").text(response.downvotes); //update downvote count
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
    event.stopPropagation();
    event.preventDefault();
    bookmarkRecipe(this);
  });

  function bookmarkRecipe(element) {
    var recipeCard = $(element).closest(".recipe-card");
    var recipeID = recipeCard.data("recipe-id");
    var checkbox = $(element).find('input[type="checkbox"]');
    var isChecked = checkbox.prop("checked"); //current checked state

    $.ajax({
      url: "../bookmarkRecipe.php",
      type: "POST",
      data: {
        recipeID: recipeID,
        isBookmarked: !isChecked, //attempt to toggle state
      },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          //Only toggle checkbox on success
          checkbox.prop("checked", !isChecked);
        } else {
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
        //Revert checkbox to original state on failure
        checkbox.prop("checked", isChecked);
        alert("Bookmarking failed, please try again.");
      },
    });
  }
});

//Navigation to recipe details page
document.addEventListener("DOMContentLoaded", function () {
  const recipeCards = document.querySelectorAll(".recipe-card");
  recipeCards.forEach((card) => {
    card.addEventListener("click", function () {
      const recipeId = this.getAttribute("data-recipe-id");
      window.location.href = `getRecipesPage.php?recipeID=${recipeId}`;
    });
  });
});

// Signup modal verification flags
let isFirstNameValid = false;
let isLastNameValid = false;
let isEmailValid = false;
let isUsernameValid = false;

// First name verification
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
  console.log("First name valid:", isFirstNameValid);
});

// Last name verification
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
  console.log("Last name valid:", isLastNameValid);
});

// Signup email verification
document.getElementById("signupEmail").addEventListener("input", function (e) {
  var value = e.target.value;
  var errorElement = document.getElementById("emailError");
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (emailRegex.test(value)) {
    errorElement.textContent = "";
    isEmailValid = true;
  } else {
    errorElement.textContent = "Invalid email format!";
    isEmailValid = false;
  }
  console.log("Email valid:", isEmailValid);
});

// Signup username verification
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
    console.log("Username valid:", isUsernameValid);
  });

// Verify flags
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
      console.log(
        isEmailValid,
        isUsernameValid,
        isFirstNameValid,
        isLastNameValid
      );
      alert("Please correct the errors before submitting."); // change this to some kind of popup
    } else {
      console.log("Form submitted successfully");
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

//checks password strength
function checkStrength(password) {
  let strength = 0;

  //If password contains both lower and uppercase characters
  if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
    strength += 1;
    lowUpperCase.classList.remove("fa-circle");
    lowUpperCase.classList.add("fa-check");
  } else {
    lowUpperCase.classList.add("fa-circle");
    lowUpperCase.classList.remove("fa-check");
  }
  //If it has numbers and characters
  if (password.match(/([0-9])/)) {
    strength += 1;
    number.classList.remove("fa-circle");
    number.classList.add("fa-check");
  } else {
    number.classList.add("fa-circle");
    number.classList.remove("fa-check");
  }
  //If it has one special character
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

  //If value is less than 2
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
      //if password is weak, prevent signing up
      event.preventDefault();
    }
  });

//Check if password is strong enough
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
//When user submits valid login information
$(document).ready(function () {
  $("#loginForm").on("submit", function (e) {
    e.preventDefault();

    var login = $("#username").val();
    var password = $("#password").val();

    $.ajax({
      type: "POST",
      url: "../login.php",
      data: {
        loginUsername: login,
        loginPassword: password,
      },
      success: function (response) {
        if (response.error) {
          $("#loginError").text(response.errorMessage);
        } else {
          console.log("User ID after login:", response.userId);
          // Update UI based on login status
          $(".auth-buttons").hide();
          $(".profile-container").show();
          $("#uploadRecipeButton").show();

          // Handle first login logic
          if (response.firstLogin) {
            localStorage.setItem("firstLogin", true);
            $("#preferenceModal").modal({
              backdrop: "static",
              keyboard: false,
            });
            $("#preferenceModal").modal("show");
            localStorage.setItem("preferencesNeeded", "true");
          } else {
            localStorage.setItem("firstLogin", false);
          }

          // Optionally redirect or update the page
          window.location.href = "./homepage.php";
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error:", status, error);
        console.log("Response:", xhr.responseText);
      },
      dataType: "json",
    });
  });

  // Prevent viewing highlighted recipe without signing up
  var recipeLinks = document.querySelectorAll(".recipe-link");
  recipeLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
      if (!localStorage.getItem("isLoggedIn")) {
        event.preventDefault(); // Prevent the default link behavior
        $("#loginModal").modal("show"); // Show login modal
      } else {
        // Navigate to the recipe page if logged in
        window.location.href = link.getAttribute("data-recipe-url");
      }
    });
  });
});

$(document).ready(function () {
  $("#signupForm").on("submit", function (event) {
    event.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      type: "POST",
      url: "../signup.php",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          // Close the signup modal
          $("#signupModal").modal("hide");

          // Show the success modal
          $("#accountCreatedSuccessModal").modal("show");
        } else {
          // Display error messages
          if (response.errors) {
            $("#nameError").text(response.errors.nameError || "");
            $("#emailError").text(response.errors.emailError || "");
            $("#usernameError").text(response.errors.usernameError || "");
            $("#passwordError").text(response.errors.passwordError || "");
          } else {
            alert(response.message || "An error occurred during signup.");
          }
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error:", status, error);
        alert("An error occurred. Please try again.");
      },
    });
  });
});

//check if user has completed preferenceModal or not
$(document).ready(function () {
  if (localStorage.getItem("preferencesNeeded") === "true") {
    $("#preferenceModal").show();
  }
});

$(document).ready(function () {
  var usernameValid = false; //validation variables
  var emailValid = false;
  var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

  //function to update the state of the signup button based on validity
  function updateSignupButtonState() {
    $(".signupButton").prop("disabled", !(usernameValid && emailValid));
  }

  //signup username validation
  $("#signupUsername").on("keyup blur", function () {
    var username = $(this).val();
    if (username) {
      $.ajax({
        url: "../checkUsername.php",
        method: "POST",
        data: { username: username },
        success: function (data) {
          $("#usernameError").text(data);
          usernameValid = data !== "Username already taken";
          updateSignupButtonState();
        },
      });
    } else {
      usernameValid = false;
      updateSignupButtonState();
    }
  });

  //email validation
  $("#signupEmail").on("input blur", function () {
    var email = $(this).val();
    if (emailRegex.test(email)) {
      $.ajax({
        url: "../checkEmail.php",
        method: "POST",
        data: { email: email },
        success: function (data) {
          $("#emailError").text(data);
          emailValid = data !== "Email already taken";
          updateSignupButtonState();
        },
      });
    } else {
      $("#emailError").text(email ? "Invalid email format" : "");
      emailValid = false;
      updateSignupButtonState();
    }
  });
});
