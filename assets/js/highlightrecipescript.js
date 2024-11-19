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
  // Setup modal interactions
  $(".modal-trigger").click(function (event) {
    event.preventDefault();
    var targetModal = $(this).data("target-modal");
    var hideModal = $(this).data("hide-modal");

    $(hideModal).modal("hide");
    setTimeout(() => $(targetModal).modal("show"), 100);
  });

  // Handle form submissions inside modals
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

$(document).ready(function () {
  // Delegate click event to the upvote and downvote buttons within the context of .voting-buttons
  $(".voting-buttons").on("click", ".upvote, .downvote", function (event) {
    event.preventDefault();
    event.stopPropagation();

    var $button = $(this);
    // This now correctly fetches the recipeID from the parent .voting-buttons element
    var recipeID = $button.closest(".voting-buttons").data("recipe-id");
    var isUpvote = $button.hasClass("upvote");
    var voteType = isUpvote ? 1 : -1;

    // Toggle classes for active state on buttons
    if ($button.hasClass("active")) {
      $button.removeClass("active");
      voteType = 0; // User is toggling off their vote
    } else {
      $button.addClass("active");
      $button.siblings(".upvote, .downvote").removeClass("active");
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
          // Find the closest voting-buttons container and update the votes count within it
          var $votingButtons = $(
            '.voting-buttons[data-recipe-id="' + recipeID + '"]'
          );
          $votingButtons.find(".upvote .votes-count").text(response.upvotes);
          $votingButtons
            .find(".downvote .votes-count")
            .text(response.downvotes);
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

document.addEventListener("DOMContentLoaded", function () {
  document.body.addEventListener("submit", function (event) {
    if (
      event.target.classList.contains("comment-form") ||
      event.target.classList.contains("reply-form")
    ) {
      event.preventDefault();
      submitCommentForm(event.target);
    }
  });
});

function submitCommentForm(form) {
  var formData = new FormData(form);

  fetch("submitComment.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        //call a function to add the comment to the page
        addCommentToPage(data.comment, formData.get("parentID"));
        form.reset(); //reset the form to clear the input fields
      } else {
        alert(data.message); //show an error message
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function addCommentToPage(comment, parentId) {
  let parentList;

  if (parentId && parentId !== "NULL") {
    //Locate the specific parent comment's list to append to
    let parentComment = document.querySelector(`#comment-${parentId}`);
    parentList = parentComment
      ? parentComment.querySelector(".comments-list")
      : null;
    //Create a new list if it does not exist
    if (!parentList) {
      parentList = document.createElement("ul");
      parentList.className = "comments-list";
      parentComment.appendChild(parentList);
    }
  } else {
    parentList = document.querySelector(".comments-list");
    if (!parentList) {
      parentList = document.createElement("ul");
      parentList.className = "comments-list";
      const commentsContainer = document.querySelector(
        "#comments .comments-container"
      );
      if (commentsContainer) {
        commentsContainer.appendChild(parentList);
      } else {
        console.error("No container found to append the comments list.");
        return;
      }
    }
  }

  const profilePicUrl =
    comment.profilePicUrl || "assets/images/profile-icon.jpg";

  const commentHTML = `
    <li class="comment" id="comment-${comment.id}">
      <div class="comment-header">
        <img src="${profilePicUrl}" alt="Profile Picture" class="profile-pic">
        <div class="comment-author-and-time">
          <h3 class="comment-author">${comment.username}</h3>
          <p class="comment-time">Just now</p>
        </div>
      </div>
      <p class="comment-text">${comment.text}</p>
      <div class="comment-actions">
        <a href="#" class="comment-reply" onclick="showReplyForm(${comment.id}, event)" style="font-size: 15px;">Reply (0)</a>
        <a href="javascript:void(0);" class="comment-likes" style="cursor: pointer; font-size: 15px;" onclick="toggleLike(${comment.id})">
          <i class="fas fa-heart"></i> 0
        </a>
        <button onclick="deleteComment(${comment.id})" class="btn btn-danger">Delete</button>
      </div>
      <form id="reply-form-${comment.id}" class="reply-form" style="display:none;" method="post" action="submitComment.php">
        <textarea name="commentText" required placeholder="Write your reply..."></textarea>
        <input type="hidden" name="recipeID" value="${comment.recipeID}">
        <input type="hidden" name="parentID" value="${comment.id}">
        <button type="submit">Submit Reply</button>
        <button type="button" onclick="hideReplyForm(${comment.id})">Cancel</button>
      </form>
      <ul class="comments-list"></ul>
    </li>
  `;

  // Append the new comment HTML to the appropriate list
  parentList.innerHTML += commentHTML;
}

function showReplyForm(commentId, event) {
  event.preventDefault(); // Stop the link from causing a page jump
  var form = document.getElementById("reply-form-" + commentId);
  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
  } else {
    form.style.display = "none";
  }
}

function hideReplyForm(commentId) {
  const replyForm = document.getElementById(`reply-form-${commentId}`);
  if (replyForm) {
    replyForm.style.display = "none"; // Hide the reply form
  }
}

function toggleLike(commentID) {
  fetch("handleCommentLike.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "commentID=" + commentID,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        console.error("Error:", data.error);
        return;
      }

      const likeButton = document.querySelector(
        '.comment-likes[onclick="toggleLike(' + commentID + ')"]'
      );
      let likeCount = parseInt(likeButton.textContent.match(/\d+/)[0]); //extract the number

      if (data.liked) {
        likeButton.style.color = "red";
        likeCount++;
      } else {
        likeButton.style.color = "";
        likeCount--;
      }
      likeButton.innerHTML = `<i class="fas fa-heart"></i> ${likeCount}`;
    })
    .catch((error) => console.error("Error:", error));
}

document.addEventListener("DOMContentLoaded", function () {
  document.body.addEventListener("click", function (event) {
    if (event.target.classList.contains("delete-comment")) {
      const commentID = event.target.getAttribute("data-comment-id");
      console.log("Attempting to delete comment with ID:", commentID); // Check if the ID is correctly fetched
      if (commentID) {
        deleteComment(commentID);
      } else {
        console.error("Comment ID is undefined or not fetched correctly.");
      }
    }
  });
});

function deleteComment(commentID) {
  if (confirm("Are you sure you want to delete this comment?")) {
    fetch(`deleteComment.php?commentID=${encodeURIComponent(commentID)}`, {
      method: "GET",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Server response for deletion:", data); // Log the server response
        if (data.status === "success") {
          const commentElement = document.getElementById(
            "comment-" + commentID
          );
          console.log("Looking for element:", "#comment-" + commentID); // Log the selector used
          if (commentElement) {
            commentElement.remove();
            console.log("Comment removed successfully from DOM.");
          } else {
            console.error(
              "Failed to locate the comment in the DOM with ID:",
              commentID
            );
          }
        } else {
          alert(data.message); // Display any server-side error messages
        }
      })
      .catch((error) => {
        console.error("Error during deletion:", error);
      });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var ingredientsList = document.querySelectorAll(".ingredientslist li");

  ingredientsList.forEach(function (li, index) {
    //Create a checkbox for each list item
    var checkbox = document.createElement("input");
    checkbox.setAttribute("type", "checkbox");
    checkbox.className = "ingredient-checkbox";
    checkbox.id = "ingredient-checkbox-" + index; //Unique ID for each checkbox

    var label = document.createElement("label");
    label.setAttribute("for", "ingredient-checkbox-" + index); //Link label to the corresponding checkbox
    label.className = "custom-checkbox-label";

    //Prepend the checkbox and label to the list item
    li.prepend(label);
    li.prepend(checkbox);

    //Add an event listener to handle the strikethrough
    checkbox.addEventListener("change", function () {
      if (checkbox.checked) {
        li.style.textDecoration = "line-through";
      } else {
        li.style.textDecoration = "none";
      }
    });
  });
});

let isFirstNameValid = false;
let isLastNameValid = false;
let isEmailValid = false;
let isUsernameValid = false;

//First name verification
document.getElementById("firstName").addEventListener("input", function (e) {
  var value = e.target.value;
  var errorElement = document.getElementById("nameError");
  if (value.includes(" ")) {
    //only allow a singular string input
    errorElement.textContent = "Only enter one word!";
    isFirstNameValid = false;
    //test value to only allow one or more characters
  } else if (/^[a-zA-Z]*$/.test(value)) {
    errorElement.textContent = ""; //if valid, clear content
    isFirstNameValid = true;
  } else {
    errorElement.textContent = "Only letters allowed!"; //error found
    isFirstNameValid = false; //not longer valid so set to false
  }
});

//Last name verification
document.getElementById("lastName").addEventListener("input", function (e) {
  var value = e.target.value;
  var errorElement = document.getElementById("nameError");
  if (value.includes(" ")) {
    //check if it includes more than one word
    errorElement.textContent = "Only enter one word!";
    isLastNameValid = false; //not valid
  } else if (/^[a-zA-Z]*$/.test(value)) {
    //test lastName validity
    errorElement.textContent = "";
    isLastNameValid = true;
  } else {
    errorElement.textContent = "Only letters allowed!";
    isLastNameValid = false;
  }
});

//Signup email verification
document.getElementById("signupEmail").addEventListener("input", function (e) {
  var value = e.target.value;
  var errorElement = document.getElementById("emailError");
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  //test users email input
  if (emailRegex.test(value)) {
    //if valid
    errorElement.textContent = "";
    isEmailValid = true;
  } else {
    errorElement.textContent = "Invalid email format!";
    isEmailValid = false;
  }
});

//Signup username verification
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

//verify flags
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
      alert("Please correct the errors before submitting."); //change this to some kind of popup
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

function changeEyeIcon(show) {
  show.classList.toggle("fa-eye-slash");
}

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
      url: "login.php", //Specify PHP script to handle data
      data: {
        loginUsername: login,
        loginPassword: password,
      },
      success: function (response) {
        if (response.error) {
          $("#loginError").text(response.errorMessage);
        } else {
          if (response.firstLogin) {
            //if this is the users first time logging in, set this in localstorage
            localStorage.setItem("firstLogin", true);
            window.location.href = "public/homepage.php";
          } else {
            localStorage.setItem("firstLogin", false);
            window.location.href = "public/homepage.php";
          }
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error:", status, error);
      },
      dataType: "json",
    });
  });
});
