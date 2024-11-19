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
  $(".settings-body__content-link").click(function (e) {
    e.preventDefault();
    var targetId = $(this).data("target"); //get the target ID from the data-target attribute
    console.log("Target ID: " + targetId); //debugging

    $(".settings-section").hide(); //hide all sections
    $(targetId).show(); //show the targeted section
  });
});

$(document).ready(function () {
  $(".ui-checkbox").click(function (event) {
    event.preventDefault();
    event.stopPropagation();
    var checkbox = $(this).find('input[type="checkbox"]')[0];
    var recipeID = $(this).closest(".recipe-card").data("recipe-id");
    toggleBookmark(checkbox, recipeID);
  });
});

function toggleBookmark(checkbox, recipeID) {
  const isChecked = checkbox.checked;
  console.log("Recipe ID: " + recipeID + " is checked: " + isChecked);
  const recipeCard = $(checkbox).closest(".recipe-card");

  if (!isChecked) {
    recipeCard.fadeOut();
  }

  $.ajax({
    url: "../handleBookmarked.php",
    type: "POST",
    cache: false,
    data: {
      recipeID: recipeID,
      isBookmarked: isChecked,
    },
    dataType: "json",
    success: function (response) {
      console.log(response);
      if (response.status !== "success") {
        recipeCard.fadeIn();
        checkbox.checked = !isChecked;
        alert(response.message);
      } else {
        recipeCard.fadeOut();
      }
    },
    error: function () {
      recipeCard.fadeIn();
      checkbox.checked = !isChecked;
      alert("Failed to update bookmark status. Please try again.");
    },
  });
}

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
  $.ajax({
    url: "../updateProfile.php",
    dataType: "json",
    success: function (response) {
      if (!response.error) {
        $("#profilePicture").attr("src", response[0].ProfilePictureURL);
        $("#Bio").val(response[0].Bio).data("initial-value", response[0].Bio);
        $("#Username")
          .val(response[0].Username)
          .data("initial-value", response[0].Username);
        $("#Email")
          .val(response[0].Email)
          .data("initial-value", response[0].Email);
      } else {
        console.log("Error:", response.error);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data:", error);
    },
  });
});

const profilePicture = document.getElementById("profilePicture");
const bioField = document.getElementById("Bio");
const usernameField = document.getElementById("Username");
const emailField = document.getElementById("Email");

const fields = [bioField, usernameField, emailField];

function restoreInitialValue(element) {
  const initialValue = $(element).data("initial-value");
  $(element).val(initialValue);
}

function activateEditing(element) {
  $(element).on("click", function () {
    $(this).removeAttr("readonly");

    if (!$(this).data("buttons-created")) {
      createAndAppendButtons(this);
      $(this).data("buttons-created", true);
    } else {
      $(this).siblings(".btn.mt-2, .btn.btn-danger.mt-2").show();
    }
  });
}

function createAndAppendButtons(element) {
  const saveButton = document.createElement("button");
  saveButton.textContent = "Save Changes";
  saveButton.className = "btn mt-2";
  saveButton.id = "saveBtn";
  saveButton.style.display = "inline-block";

  const cancelButton = document.createElement("button");
  cancelButton.textContent = "Cancel";
  cancelButton.className = "btn btn-danger mt-2";
  cancelButton.id = "cancelBtn";
  cancelButton.style.display = "inline-block";

  saveButton.onclick = function () {
    $(element).attr("readonly", "readonly");
    $(saveButton).hide();
    $(cancelButton).hide();
  };

  cancelButton.onclick = function () {
    $(element).val($(element).data("initial-value"));
    $(element).attr("readonly", "readonly");
    $(saveButton).hide();
    $(cancelButton).hide();
  };

  $(element).after(cancelButton);
  $(element).after(saveButton);
}

fields.forEach(activateEditing);

$(document).on("click", "#saveBtn", function () {
  var element = $(this).closest("div").find("input, textarea");
  var updatedValue = element.val();
  var fieldName = element.attr("id");

  console.log(fieldName + " " + updatedValue);

  $.ajax({
    url: "../updateProfile.php",
    type: "POST",
    data: {
      field: fieldName,
      value: updatedValue,
    },
    success: function (response) {
      console.log("Update successful:", response);
      element.attr("readonly", "readonly");
      $(this).hide();
      $(this).next("#cancelBtn").hide();
    },
    error: function (xhr, status, error) {
      console.error("Error updating data:", error);
    },
  });
});

const preferenceBtn = document.getElementById("changePreferenceBtn");
const preferenceModal = document.getElementById("preferenceModal");

preferenceBtn.addEventListener("click", () => {
  preferenceModal.style.display = "flex";
});

const userProfileInput = document.getElementById("changeProfileImg");
const profileImage = document.getElementById("profilePicture");

userProfileInput.addEventListener("change", function () {
  var formData = new FormData();
  const profileInput = document.getElementById("changeProfileImg").files[0];
  formData.append("userImage", profileInput);

  $.ajax({
    url: "../updateProfile.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (data) {
      if (data.success) {
        profileImage.src = data.imageUrl;
      } else {
        console.log(data.error);
      }
    },
    error: function (xhr, status, error) {
      alert("An error occurred: " + error);
    },
  });
});

const changePasswordAnchor = document.getElementById("changePassword");
const forgotPasswordModal = document.getElementById("forgotPasswordModal");

changePasswordAnchor.addEventListener("click", function () {
  forgotPasswordModal.style.display = "block";
});

const forgotPasswordCloseButton = document.getElementById(
  "forgotPasswordCloseButton"
);

forgotPasswordCloseButton.addEventListener("click", function () {
  forgotPasswordModal.style.display = "none";
});

const deleteAccountButton = document.getElementById("deleteAccountBtn");
const confirmDeleteAccountButton = document.getElementById(
  "confirmDeleteAccountModalBtn"
);
const deleteAccountModal = document.getElementById("deleteAccountModal");
const deleteAccountModalCloseBtn = document.getElementById(
  "closeDeleteAccountModalBtn"
);
const deleteAccountModalCancelButton = document.getElementById(
  "cancelDeleteAccountModalBtn"
);

deleteAccountButton.addEventListener("click", function () {
  deleteAccountModal.style.display = "block";
});

deleteAccountModalCancelButton.addEventListener("click", function () {
  deleteAccountModal.style.display = "none";
});

deleteAccountModalCloseBtn.addEventListener("click", function () {
  deleteAccountModal.style.display = "none";
});

confirmDeleteAccountButton.addEventListener("click", function () {
  $.ajax({
    url: "deleteAccount.php",
    type: "POST",
    success: function (response) {
      const data = JSON.parse(response);
      if (data.success) {
        window.location.href = "logout.php";
      } else {
        console.log("Error:" + data.error);
      }
    },
    error: function (xhr, status, error) {
      console.log("An error occurred: " + error);
    },
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const dropdownLinks = document.querySelectorAll(".dropdown a[data-target]");
  const sections = document.querySelectorAll(".settings-section");

  function showSection(targetId) {
    sections.forEach((section) => {
      section.style.display = "none"; // Hide all sections
    });

    const targetSection = document.getElementById(targetId);
    if (targetSection) {
      targetSection.style.display = "block";
    }
  }

  dropdownLinks.forEach((link) => {
    link.addEventListener("click", function (event) {
      event.preventDefault();
      const targetId = this.getAttribute("data-target");
      showSection(targetId);
    });
  });
});
