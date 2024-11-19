let preferenceCurrentGroupIndex = 0; //Start from the first hidden group
let allLoadedIngredients = []; //Array to track all loaded ingredients
let selectedDietaryPreference = []; //Variable to track selected dietary preference
let selectedIngredients = []; //Array to track selected ingredients
let selectedCuisine = []; //Variable to track selected cuisine preference
let selectedExperience = ""; //Variable to track selected cooking experience
let userProfilePic = ""; //Variable to track selected profile picture
let userBio = ""; //Variable to track selected bio

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

let currentOffset = 0;
let displayCount = 10;

function loadMoreIngredients() {
  let dataToSend = {
    offset: currentOffset,
    search: "",
  };

  fetch("../getIngredients.php", {
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
      fetch("../getIngredients.php", {
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
      currentOffset = 0;
      loadMoreIngredients();
    }
  });

document
  .getElementById("loadMoreIngredients")
  .addEventListener("click", function () {
    loadMoreIngredients(currentOffset);
  });

loadMoreIngredients(0);

document
  .getElementById("ingredientSearch")
  .addEventListener("input", function () {
    const searchValue = this.value.trim();
    const container = document.getElementById("ingredients-container");

    if (searchValue) {
      container.innerHTML = "";
      fetch("../getIngredients.php?search=" + encodeURIComponent(searchValue))
        .then((response) => response.json())
        .then((data) => {
          displayIngredients(data);
        });
    } else {
      container.innerHTML = "";
      loadMoreIngredients(0);
    }
  });

document
  .querySelector(".load-more-cuisines")
  .addEventListener("click", function () {
    const groupToShow = document.querySelector(
      '.cuisine-preferences .cuisine-group[data-index="' +
        preferenceCurrentGroupIndex +
        '"]'
    );
    if (groupToShow) {
      groupToShow.style.display = "block";
      preferenceCurrentGroupIndex++;
    } else {
      this.style.display = "none";
    }
  });

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

function updateCuisinePreference(tag, isSelected) {
  const preference = tag.textContent;
  if (isSelected) {
    if (!selectedCuisine.includes(preference)) {
      selectedCuisine.push(preference);
    }
  } else {
    const index = selectedCuisine.indexOf(preference);
    if (index > -1) {
      selectedCuisine.splice(index, 1);
    }
  }
  console.log("Cuisine Preferences:", selectedCuisine);
}

function updateCookingExperience(selectedTag) {
  selectedExperience = selectedTag.textContent;
  console.log(selectedExperience);
}

function handleSelection(clickedTag, maxSelections, container) {
  const selectedCount = container.querySelectorAll(
    ".preference-tag.selected"
  ).length;

  if (clickedTag.classList.contains("selected")) {
    clickedTag.classList.remove("selected");
  } else if (selectedCount < maxSelections) {
    clickedTag.classList.add("selected");
  }
}

document
  .querySelectorAll(".dietary-preferences .preference-tag")
  .forEach((tag) => {
    tag.addEventListener("click", function () {
      this.classList.toggle("selected");
      updateDietaryPreference(this, this.classList.contains("selected"));
    });
  });

function updateDietaryPreference(tag, isSelected) {
  const preference = tag.textContent;
  if (isSelected) {
    if (!selectedDietaryPreference.includes(preference)) {
      selectedDietaryPreference.push(preference);
    }
  } else {
    selectedDietaryPreference = selectedDietaryPreference.filter(
      (item) => item !== preference
    );
  }
  console.log("Dietary Preferences:", selectedDietaryPreference);
}

document
  .querySelectorAll(".cuisine-preferences .preference-tag")
  .forEach((tag) => {
    tag.addEventListener("click", function () {
      let selectedCuisines = document.querySelectorAll(
        ".cuisine-preferences .preference-tag.selected"
      );
      if (this.classList.contains("selected")) {
        this.classList.remove("selected");
        updateCuisinePreference(this, false);
      } else if (selectedCuisines.length < 3) {
        this.classList.add("selected");
        updateCuisinePreference(this, true);
      } else {
        console.log("You can't select more than 3 cuisines.");
      }
    });
  });

function updateCuisinePreference(tag, isSelected) {
  const preference = tag.textContent;
  if (isSelected) {
    if (!selectedCuisine.includes(preference)) {
      selectedCuisine.push(preference);
    }
  } else {
    selectedCuisine = selectedCuisine.filter((item) => item !== preference);
  }
  console.log("Cuisine Preferences:", selectedCuisine);
}

document
  .querySelectorAll(".cooking-experience .preference-tag")
  .forEach((tag) => {
    tag.addEventListener("click", function () {
      document
        .querySelectorAll(".cooking-experience .preference-tag.selected")
        .forEach((selectedTag) => {
          selectedTag.classList.remove("selected");
        });
      this.classList.add("selected");
      updateCookingExperience(this);
    });
  });

function handleExperienceSelection(clickedTag, container) {
  container.querySelectorAll(".preference-tag.selected").forEach((tag) => {
    tag.classList.remove("selected");
  });

  clickedTag.classList.add("selected");
}

const experienceContainer = document.querySelector(".cooking-experience");
experienceContainer.addEventListener("click", function (event) {
  if (event.target && event.target.matches(".preference-tag")) {
    handleExperienceSelection(event.target, experienceContainer);
  }
});

let ingredientContainer = document.querySelector("#ingredients-container");

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

const cuisineContainer = document.querySelector(".cuisine-preferences");

function checkAllPreferencesSelected() {
  const dietaryContainer = document.querySelector(".dietary-preferences");
  const dietarySelected =
    dietaryContainer.querySelector(".preference-tag.selected") !== null;
  const allCuisinesSelected =
    cuisineContainer.querySelectorAll(".preference-tag.selected").length === 3;
  const experienceSelected =
    experienceContainer.querySelector(".preference-tag.selected") !== null;
  const ingredientsSelected = selectedIngredients.length >= 5;

  const saveButton = document.getElementById("preferencesNextButton");
  if (
    allCuisinesSelected &&
    experienceSelected &&
    ingredientsSelected &&
    dietarySelected
  ) {
    saveButton.classList.add("all-selected");
    saveButton.disabled = false;
  } else {
    saveButton.classList.remove("all-selected");
    saveButton.disabled = true;
  }
}

document.querySelectorAll(".preference-category").forEach((category) => {
  category.addEventListener("click", function () {
    checkAllPreferencesSelected();
  });
});

checkAllPreferencesSelected();

document
  .getElementById("profilePicUpload")
  .addEventListener("change", function (event) {
    if (event.target.files && event.target.files[0]) {
      const reader = new FileReader();

      reader.onload = function (e) {
        document.getElementById("profilePicDisplay").src = e.target.result;
      };

      reader.readAsDataURL(event.target.files[0]);
    }
  });

document
  .getElementById("preferencesNextButton")
  .addEventListener("click", function () {
    const modalHeader = document.querySelector(".preference-modal-header");
    const modalContent = document.querySelector(".preference-modal-content");
    document
      .querySelectorAll(".preference-category")
      .forEach(function (category) {
        category.style.display = "none";
      });
    document.querySelector(".load-more-cuisines").style.display = "none";
    modalHeader.textContent = "Complete Your Profile";
    modalHeader.style.setProperty("font-size", "38px", "important");
    modalContent.style.setProperty("width", "550px");
    this.style.display = "none";

    document.getElementById("profilePicInput").style.display = "block";
    document.getElementById("bioInput").style.display = "block";

    document.getElementById("completeProfile").style.display = "block";
  });

$(document).ready(function () {
  $("#completeProfile").click(function () {
    var dietaryPreferences = selectedDietaryPreference;
    var cuisinePreferences = selectedCuisine;
    var preferredIngredients = selectedIngredients;
    var cookingExperience = selectedExperience;
    var userBio = document.getElementById("bio").value;
    var userProfilePic = document.getElementById("profilePicUpload").files[0];
    var userId = document.body.getAttribute("data-user-id");
    const modal = document.getElementById("preferenceModal");

    var formData = new FormData();
    formData.append("userId", userId);
    formData.append("bio", userBio);
    formData.append("profilePic", userProfilePic);
    formData.append("dietaryPreferences", JSON.stringify(dietaryPreferences));
    formData.append("cuisinePreferences", JSON.stringify(cuisinePreferences));
    formData.append(
      "ingredientPreferences",
      JSON.stringify(preferredIngredients)
    );
    formData.append("cookingExperience", cookingExperience);

    $.ajax({
      type: "POST",
      url: "../preferenceModalSubmissions.php",
      processData: false,
      contentType: false,
      data: formData,
      success: function (response) {
        console.log("Preferences saved: " + response);
        localStorage.setItem("preferencesNeeded", "false");
        localStorage.setItem("firstLogin", "false");
        modal.style.display = "none";
        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");
      },
    });
  });
});
