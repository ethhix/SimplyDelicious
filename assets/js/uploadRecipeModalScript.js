let allIngredientsLists = [{ subtitle: "", ingredients: [] }];
let allInstructions = [];
let uploadedImageData = null;

document.addEventListener("DOMContentLoaded", function () {
  const firstSectionBtn = document.getElementById("firstSectionBtn");
  const fileInput = document.getElementById("profilePicUpload");

  firstSectionBtn.addEventListener("click", function () {
    const validationResult = validateFirstSection();
    if (validationResult.isValid) {
      document.getElementById("firstSection").style.display = "none";
      document.getElementById("secondSection").style.display = "block";
    } else {
      alert(validationResult.message); // Alert user to the specific issue
    }
  });

  bindAddRemoveButtons();
  setupImageUploadHandlers();
  setupIngredientHandlers();

  function validateFirstSection() {
    const titleValidation = validateRecipeTitle();
    if (!titleValidation.isValid) {
      return titleValidation;
    }

    const imageValidation = validateImageUpload();
    if (!imageValidation.isValid) {
      return imageValidation;
    }

    const ingredientValidation = validateIngredients();
    if (!ingredientValidation.isValid) {
      return ingredientValidation;
    }

    return { isValid: true };
  }

  function validateRecipeTitle() {
    const recipeTitle = document.getElementById("recipeTitle").value.trim();
    if (!recipeTitle) {
      return { isValid: false, message: "Please enter a recipe title." };
    }
    return { isValid: true };
  }

  function validateImageUpload() {
    const fileInput = document.getElementById("profilePicUpload");
    if (fileInput.files.length === 0) {
      return { isValid: false, message: "Please upload an image." };
    }
    return { isValid: true };
  }

  function setupImageUploadHandlers() {
    const uploadButton = document.getElementById("uploadImageBtn");
    uploadButton.addEventListener("click", () => fileInput.click());
    fileInput.addEventListener("change", handleFileUpload);
  }

  function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        placeImageInSlot(e.target.result);
      };
      reader.readAsDataURL(file);
    }
  }

  function placeImageInSlot(imageSrc) {
    const imageSlot = document.querySelector(".image-slot");
    const uploadButton = document.getElementById("uploadImageBtn");

    // Check if an image is already present
    if (imageSlot.querySelector("img")) {
      imageSlot.querySelector("img").src = imageSrc;
    } else {
      const img = document.createElement("img");
      img.src = imageSrc;
      img.style.width = "100%";
      img.style.height = "100%";
      img.style.objectFit = "cover";
      imageSlot.appendChild(img);

      const removeButton = createRemoveButton();
      imageSlot.appendChild(removeButton);

      // Hide the upload button when the image is successfully loaded
      uploadButton.style.display = "none";
    }
  }
});

function setupIngredientHandlers() {
  document
    .querySelectorAll(".ingredient-textarea")
    .forEach((textArea, index) => {
      textArea.addEventListener("input", (event) =>
        handleIngredientInput(event, index)
      );
    });
}

function handleIngredientInput(event, index) {
  const inputElement = event.target;
  const lines = inputElement.value.split("\n");
  let newIngredients = lines.map((line, lineIndex) => {
    let existingIngredient =
      allIngredientsLists[index].ingredients[lineIndex] || {};
    return parseIngredientLine(line, existingIngredient);
  });

  allIngredientsLists[index].ingredients = newIngredients;
  updateDropdown(inputElement, lines.length - 1, index);
}

function updateDropdown(inputElement, lineIndex, index) {
  const currentLineText = getLineText(inputElement, lineIndex);
  if (currentLineText.length > 1) {
    fetchIngredients(currentLineText.trim(), inputElement, lineIndex, index);
  }
}

function fetchIngredients(search, inputElement, lineIndex, index) {
  fetch("http://localhost/SimplyDelicious Website/getRecipeIngredients.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ search }),
  })
    .then((response) => response.json())
    .then((data) => displayDropdown(data, inputElement, lineIndex, index))
    .catch((error) => console.error("Fetch error:", error));
}

function extractQuantity(lineText) {
  const quantityRegex = /(\d+\s*[a-zA-Z]*)$/;
  const match = lineText.match(quantityRegex);
  return match ? match[0] : "";
}

function updateLineText(inputElement, lineIndex, newText) {
  let lines = inputElement.value.split("\n");
  lines[lineIndex] = newText;
  inputElement.value = lines.join("\n");
}

function saveOrUpdateIngredient(ingredient, lineIndex, index) {
  let ingredientsSection = allIngredientsLists[index];

  // Ensure that there's an ingredients array ready to be updated
  if (!ingredientsSection.ingredients) {
    ingredientsSection.ingredients = [];
  }

  // Check if an existing entry is being updated or if a new entry is being added
  if (lineIndex < ingredientsSection.ingredients.length) {
    ingredientsSection.ingredients[lineIndex] = ingredient; // update existing
  } else {
    ingredientsSection.ingredients.push(ingredient); // push new
  }

  console.log(
    "Updated Ingredients List for textarea " + index + ":",
    JSON.stringify(allIngredientsLists[index])
  );
}

function setupImageUploadHandlers() {
  const uploadButton = document.getElementById("uploadImageBtn");
  const fileInput = document.getElementById("hiddenFileInput");

  uploadButton.addEventListener("click", () => fileInput.click());
  fileInput.addEventListener("change", function (event) {
    handleFileUpload(event);
  });
}

function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      placeImageInSlot(e.target.result);
      uploadedImageData = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function updateUploadControls() {
  const slots = document.querySelectorAll(".image-slot");
  let foundEmpty = false;
  const uploadButton = document.getElementById("uploadImageBtn");
  const fileInput = document.getElementById("hiddenFileInput");
  for (let i = 0; i < slots.length; i++) {
    if (!slots[i].querySelector("img")) {
      slots[i].appendChild(uploadButton);
      slots[i].appendChild(fileInput);
      uploadButton.style.display = "block";
      fileInput.style.display = "block";
      foundEmpty = true;
      break;
    }
  }
  if (!foundEmpty) {
    uploadButton.style.display = "none";
    fileInput.style.display = "none";
  }
}

function createRemoveButton() {
  const removeButton = document.createElement("button");
  removeButton.className = "remove-image-btn";
  removeButton.textContent = "×";
  removeButton.addEventListener("click", function () {
    const imageSlot = document.querySelector(".image-slot");
    const img = imageSlot.querySelector("img");
    if (img) {
      img.remove();
      this.remove();
      const fileInput = document.getElementById("hiddenFileInput");
      fileInput.value = ""; // Clear the file input to allow the same file to be selected again

      // Show the upload button again when the image is removed
      const uploadButton = document.getElementById("uploadImageBtn");
      uploadButton.style.display = "block";

      // Re-validate or update validation state
      const validationResponse = validateImageUpload();
      if (!validationResponse.isValid) {
        alert(validationResponse.message); // Optionally alert the user immediately
      }
    }
  });
  return removeButton;
}

function validateIngredients() {
  const isValid = allIngredientsLists.every((section) => {
    return (
      section.subtitle.trim() !== "" &&
      section.ingredients.every(
        (ing) => ing.name.trim() !== "" && ing.quantity.trim() !== ""
      )
    );
  });

  if (!isValid) {
    return {
      isValid: false,
      message:
        "Please check that all ingredient sections are filled out correctly.",
    };
  }
  return { isValid: true };
}

// --------------------------------------------------------------------- //

// document.addEventListener("DOMContentLoaded", function () {
//   var modal = $("#createRecipeModal");
//   var closeBtn = document.getElementsByClassName("close")[0];
//   var secondCloseBtn = document.getElementById("secondClose");

//   console.log(
//     "Initial state of allIngredientsLists:",
//     JSON.stringify(allIngredientsLists)
//   );

//   function closeModal() {
//     modal.modal("hide");
//     resetModal();
//   }

//   function resetModal() {
//     // Reset modal sections
//     document.getElementById("firstSection").style.display = "block";
//     document.getElementById("secondSection").style.display = "none";
//     document.getElementById("thirdSection").style.display = "none";

//     // Reset form fields
//     document.getElementById("recipeTitle").value = "";
//     allIngredientsLists = [{ subtitle: "", ingredients: [] }];
//     allInstructions = [];
//     const ingredientsContainer = document.getElementById(
//       "ingredientsContainer"
//     );
//     const instructionsContainer = document.getElementById(
//       "instructionsContainer"
//     );

//     while (ingredientsContainer.children.length > 1) {
//       ingredientsContainer.removeChild(ingredientsContainer.lastChild);
//     }
//     while (instructionsContainer.children.length > 1) {
//       instructionsContainer.removeChild(instructionsContainer.lastChild);
//     }

//     const initialIngredientTextarea = ingredientsContainer.querySelector(
//       ".ingredient-textarea"
//     );
//     const initialInstructionTextarea = instructionsContainer.querySelector(
//       ".instructions-textarea"
//     );
//     const initialIngredientSubtitleInput = ingredientsContainer.querySelector(
//       ".ingredient-subtitle-input"
//     );
//     const initialInstructionInput = instructionsContainer.querySelector(
//       ".instructions-input"
//     );

//     if (initialIngredientTextarea) initialIngredientTextarea.value = "";
//     if (initialInstructionTextarea) initialInstructionTextarea.value = "";
//     if (initialIngredientSubtitleInput)
//       initialIngredientSubtitleInput.value = "";
//     if (initialInstructionInput) initialInstructionInput.value = "";

//     if (initialIngredientTextarea)
//       initialIngredientTextarea.style.display = "block";
//     if (initialInstructionTextarea)
//       initialInstructionTextarea.style.display = "block";

//     //Reset selected options
//     selectedDietaryOptions = [];
//     selectedCuisines = [];
//     selectedMealTypes = [];
//     selectedRecipeDifficulty = "";
//     selectedPreparationTime = 0;
//     selectedCookingTime = 0;
//     selectedCookingMethods = [];
//     nutritionFacts = {
//       calories: { value: 0, unit: "g", valid: true },
//       totalFat: { value: 0, unit: "g", valid: true },
//       saturatedFat: { value: 0, unit: "g", valid: true },
//       cholesterol: { value: 0, unit: "mg", valid: true },
//       sodium: { value: 0, unit: "mg", valid: true },
//       potassium: { value: 0, unit: "mg", valid: true },
//       totalCarbohydrate: { value: 0, unit: "g", valid: true },
//       sugars: { value: 0, unit: "g", valid: true },
//       protein: { value: 0, unit: "g", valid: true },
//     };

//     //Reset image upload
//     uploadedImageData = null;
//     const imageSlot = document.querySelector(".image-slot");
//     if (imageSlot) {
//       imageSlot.innerHTML = "";
//     }

//     const uploadButton = document.getElementById("uploadImageBtn");
//     if (uploadButton) {
//       uploadButton.style.display = "block";
//     }

//     const hiddenFileInput = document.getElementById("hiddenFileInput");
//     if (hiddenFileInput) {
//       hiddenFileInput.value = "";
//     }
//   }

//   function initializeExistingSections() {
//     const container = document.getElementById("instructionsContainer");
//     const existingSections = container.querySelectorAll(
//       ".new-instructions-section-subtitle"
//     );
//     existingSections.forEach((section, index) => {
//       const subtitleInput = section.querySelector(".instructions-input");
//       const textArea = section.querySelector(".instructions-textarea");
//       setupInstructionSection(subtitleInput, textArea, index);
//       updateInstructions(subtitleInput, textArea, index);
//     });
//   }

//   function setupInstructionSection(subtitleInput, textArea, index) {
//     subtitleInput.addEventListener("input", () => {
//       updateInstructions(subtitleInput, textArea, index);
//     });

//     textArea.addEventListener("input", () => {
//       updateInstructions(subtitleInput, textArea, index);
//     });
//   }

//   function updateInstructions(subtitleInput, textArea, index) {
//     const subtitle = subtitleInput.value.trim();
//     const instructions = textArea.value
//       .trim()
//       .split("\n")
//       .filter((line) => line);
//     allInstructions[index] = { subtitle, instructions };
//     console.log(
//       `Updated Instructions for section index ${index}:`,
//       allInstructions[index]
//     );
//   }

//   function createInstructionSection() {
//     let groupDiv = document.createElement("div");
//     groupDiv.className = "new-instructions-section-subtitle";

//     let newInput = document.createElement("input");
//     newInput.type = "text";
//     newInput.className = "instructions-input";
//     newInput.placeholder = "Subtitle for this section...";

//     let newTextArea = document.createElement("textarea");
//     newTextArea.className = "instructions-textarea";
//     newTextArea.placeholder = "Detail the instruction step...";
//     newTextArea.rows = 5;

//     groupDiv.appendChild(newInput);
//     groupDiv.appendChild(newTextArea);
//     container.appendChild(groupDiv);

//     setupInstructionSection(
//       newInput,
//       newTextArea,
//       container.children.length - 1
//     );
//   }

//   closeBtn.addEventListener("click", (event) => {
//     event.stopPropagation();
//     closeModal();
//   });

//   secondCloseBtn.addEventListener("click", (event) => {
//     event.stopPropagation();
//     closeModal();
//   });

//   //Other initialization functions
//   initializeTextAreas();
//   bindAddRemoveButtons();
//   setupImageUploadHandlers();

//   document
//     .getElementById("addInstructionBtn")
//     .addEventListener("click", function () {
//       createInstructionSection();
//     });

//   document
//     .getElementById("minusInstructionBtn")
//     .addEventListener("click", function () {
//       const container = document.getElementById("instructionsContainer");
//       if (container.children.length > 1) {
//         container.removeChild(container.lastChild);
//         allInstructions.pop();
//         console.log("Section removed. Current sections:", allInstructions);
//       }
//     });
//   initializeExistingSections();
// });

function initializeTextAreas() {
  const textAreas = document.querySelectorAll(".ingredient-textarea");
  textAreas.forEach((textArea, index) => setupTextArea(textArea, index));
}

function setupTextArea(textArea, index) {
  textArea.addEventListener("input", function (event) {
    handleIngredientInput(event, index);
  });
  //Adding change listener to subtitle inputs specifically if they are not being updated
  const subtitleInput = document.querySelectorAll(".ingredient-subtitle-input")[
    index
  ];
  if (subtitleInput) {
    subtitleInput.addEventListener("input", () => {
      allIngredientsLists[index].subtitle = subtitleInput.value.trim();
      console.log(
        "Updated subtitle for section " + index + ": " + subtitleInput.value
      );
    });
  }
}

function bindAddRemoveButtons() {
  document
    .getElementById("addIngredientBtn")
    .addEventListener("click", function () {
      const container = document.getElementById("ingredientsContainer");
      const newTextArea = createIngredientSection(container);
      allIngredientsLists.push({ subtitle: "", ingredients: [] });
      setupTextArea(newTextArea, allIngredientsLists.length - 1);
    });

  document
    .getElementById("minusIngredientBtn")
    .addEventListener("click", function () {
      const container = document.getElementById("ingredientsContainer");
      if (container.children.length > 1) {
        container.removeChild(container.lastChild);
        allIngredientsLists.pop();
      }
    });
}

function createIngredientSection(container) {
  let groupDiv = document.createElement("div");
  groupDiv.className = "ingredient-section";

  let newSubtitleInput = document.createElement("input");
  newSubtitleInput.type = "text";
  newSubtitleInput.className = "ingredient-subtitle-input";
  newSubtitleInput.placeholder = "Enter subtitle for ingredients...";
  newSubtitleInput.style.marginTop = "10px";

  let newTextArea = document.createElement("textarea");
  newTextArea.className = "ingredient-textarea";
  newTextArea.placeholder = "Enter each ingredient on a new line...";

  groupDiv.appendChild(newSubtitleInput);
  groupDiv.appendChild(newTextArea);
  container.appendChild(groupDiv);
  return newTextArea;
}

function handleIngredientInput(event, index) {
  const inputElement = event.target;
  const lines = inputElement.value.split("\n");

  let newIngredients = lines.map((line, lineIndex) => {
    let existingIngredient =
      allIngredientsLists[index].ingredients[lineIndex] || {};
    return parseIngredientLine(line, existingIngredient);
  });

  allIngredientsLists[index].ingredients = newIngredients;
  updateDropdown(inputElement, lines.length - 1, index);
  console.log(
    "Updated Ingredients List for textarea " + index + ":",
    JSON.stringify(allIngredientsLists[index])
  );
}

function parseIngredientLine(line, existingIngredient = {}) {
  const parts = line.trim().split(" ");
  const quantity = parts.pop();
  const name = parts.join(" ");

  return {
    id: existingIngredient.id,
    name: name,
    quantity: quantity,
  };
}

function updateDropdown(inputElement, lineIndex, index) {
  const currentLineText = getLineText(inputElement, lineIndex);
  if (currentLineText.length > 1) {
    fetchIngredients(currentLineText.trim(), inputElement, lineIndex, index);
  }
}

function getLineText(inputElement, lineIndex) {
  const lines = inputElement.value.split("\n");
  return lines[lineIndex] || "";
}

function fetchIngredients(search, inputElement, lineIndex, index) {
  fetch("http://localhost/SimplyDelicious Website/getRecipeIngredients.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ search }),
  })
    .then((response) => response.json())
    .then((data) => {
      displayDropdown(data, inputElement, lineIndex, index);
    })
    .catch((error) => {
      console.error("Fetch error:", error);
    });
}

function displayDropdown(ingredients, inputElement, lineIndex, index) {
  let existingDropdown = document.querySelector(".ingredientsDropdown");
  if (existingDropdown) {
    existingDropdown.remove();
  }

  let dropdown = document.createElement("select");
  dropdown.className = "ingredientsDropdown";
  dropdown.style.width = "100%";
  dropdown.innerHTML =
    `<option value="" disabled selected>Select an ingredient</option>` +
    ingredients
      .map(
        (ingredient) =>
          `<option value="${ingredient.id}">${ingredient.name}</option>`
      )
      .join("");
  dropdown.size = Math.max(ingredients.length, 1) + 1;

  inputElement.parentNode.insertBefore(dropdown, inputElement.nextSibling);

  dropdown.onchange = function () {
    if (!this.value) return; //exit if no valid ingredient is selected
    let selectedIngredient = ingredients.find(
      (ing) => ing.id.toString() === this.value
    );
    if (selectedIngredient) {
      let currentIngredients = allIngredientsLists[index].ingredients;
      let existingIngredient = currentIngredients[lineIndex];

      let newText = `${selectedIngredient.name} ${extractQuantity(
        getLineText(inputElement, lineIndex)
      )}`;

      updateLineText(inputElement, lineIndex, newText);

      // Update or create a new ingredient object
      let updatedIngredient = {
        id: selectedIngredient.id,
        name: selectedIngredient.name,
        quantity: extractQuantity(newText),
      };

      // Preserve existing ID if already set and not changing ingredient
      if (
        existingIngredient &&
        existingIngredient.name === selectedIngredient.name
      ) {
        updatedIngredient.id = existingIngredient.id;
      }

      currentIngredients[lineIndex] = updatedIngredient;

      console.log(
        "Updated Ingredients List for textarea " + index + ":",
        JSON.stringify(allIngredientsLists[index])
      );
      this.remove(); // remove dropdown after selection
    }
  };
}

function extractQuantity(lineText) {
  const quantityRegex = /(\d+\s*[a-zA-Z]*)$/;
  const match = lineText.match(quantityRegex);
  return match ? match[0] : "";
}

function updateLineText(inputElement, lineIndex, newText) {
  let lines = inputElement.value.split("\n");
  lines[lineIndex] = newText;
  inputElement.value = lines.join("\n");
}

function saveOrUpdateIngredient(ingredient, lineIndex, index) {
  let ingredientsSection = allIngredientsLists[index];

  // Ensure that there's an ingredients array ready to be updated
  if (!ingredientsSection.ingredients) {
    ingredientsSection.ingredients = [];
  }

  // Check if an existing entry is being updated or if a new entry is being added
  if (lineIndex < ingredientsSection.ingredients.length) {
    ingredientsSection.ingredients[lineIndex] = ingredient; // update existing
  } else {
    ingredientsSection.ingredients.push(ingredient); // push new
  }

  console.log(
    "Updated Ingredients List for textarea " + index + ":",
    JSON.stringify(allIngredientsLists[index])
  );
}

function setupImageUploadHandlers() {
  const uploadButton = document.getElementById("uploadImageBtn");
  const fileInput = document.getElementById("hiddenFileInput");

  uploadButton.addEventListener("click", () => fileInput.click());
  fileInput.addEventListener("change", function (event) {
    handleFileUpload(event);
  });
}

function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      placeImageInSlot(e.target.result);
      uploadedImageData = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const ingredientsContainer = document.getElementById("ingredientsContainer");

  // Listen for input events on the container holding all ingredients sections
  ingredientsContainer.addEventListener("input", function (event) {
    // Check if the event is triggered from a subtitle input field
    if (
      event.target.classList.contains("ingredient-subtitle-input") ||
      event.target.classList.contains("ingredient-input")
    ) {
      // Find the index of the subtitle input within its class collection
      const subtitleInputs = document.querySelectorAll(
        ".ingredient-subtitle-input, .ingredient-input"
      );
      const sectionIndex = Array.from(subtitleInputs).indexOf(event.target);

      // Update the subtitle in the corresponding section of allIngredientsLists
      if (sectionIndex !== -1) {
        allIngredientsLists[sectionIndex].subtitle = event.target.value.trim();
        console.log(
          "Subtitle updated for section " +
            sectionIndex +
            ": " +
            event.target.value.trim()
        );
      }
    }
  });
});

const fileInput = document.getElementById("profilePicUpload");
const imageDisplay = document.getElementById("profilePicDisplay");
const firstSectionButton = document.getElementById("modalNextButton1");
const lastSectionBtn = document.getElementById("thirdSectionBtn");
const secondSection = document.getElementById("secondSection");
const thirdSection = document.getElementById("thirdSection");
const container = document.getElementById("instructionsContainer");

// firstSectionButton.addEventListener("click", function () {
//   console.log(
//     "All ingredients lists at submission:",
//     JSON.stringify(allIngredientsLists)
//   );
//   const recipeTitle = document.getElementById("recipeTitle").value.trim();
//   console.log("Recipe Title:", recipeTitle);

//   if (!recipeTitle) {
//     alert("Please enter a recipe title.");
//     document.getElementById("recipeTitle").focus();
//     return;
//   }

//   // Delay the file check slightly to ensure the DOM is fully updated
//   setTimeout(() => {
//     const fileInput = document.getElementById("profilePicUpload");
//     console.log("Files selected: ", fileInput.files.length);
//     if (fileInput.files.length === 0) {
//       alert("Please upload an image.");
//       return;
//     }

//     document.getElementById("firstSection").style.display = "none";
//     document.getElementById("secondSection").style.display = "block";
//   }, 100);
// });

// lastSectionBtn.addEventListener("click", () => {
//   let allSectionsFilled = true;

//   allInstructions.forEach((section) => {
//     if (section.subtitle === "" || section.instructions.length === 0) {
//       allSectionsFilled = false;
//     }
//   });

//   if (!allSectionsFilled) {
//     alert("Please fill in all subtitles and instructions before proceeding.");
//   } else {
//     document.getElementById("secondSection").style.display = "none";
//     document.getElementById("thirdSection").style.display = "block";
//   }
// });

// let nutritionFacts = {
//   calories: { value: 0, unit: "g", valid: true },
//   totalFat: { value: 0, unit: "g", valid: true },
//   saturatedFat: { value: 0, unit: "g", valid: true },
//   cholesterol: { value: 0, unit: "mg", valid: true },
//   sodium: { value: 0, unit: "mg", valid: true },
//   potassium: { value: 0, unit: "mg", valid: true },
//   totalCarbohydrate: { value: 0, unit: "g", valid: true },
//   sugars: { value: 0, unit: "g", valid: true },
//   protein: { value: 0, unit: "g", valid: true },
// };

// document.addEventListener("DOMContentLoaded", function () {
//   function updateNutritionFact(nutrientType, newValue) {
//     const validFormat = /^\d+(\.\d+)?$/;
//     if (validFormat.test(newValue)) {
//       const value = parseFloat(newValue);
//       nutritionFacts[nutrientType].value = isNaN(value) ? 0 : value;
//       nutritionFacts[nutrientType].valid = true;
//     } else {
//       nutritionFacts[nutrientType].valid = false;
//     }
//   }

//   const nutritionInputs = document.querySelectorAll(".nutrition-value");
//   nutritionInputs.forEach((input) => {
//     const nutrientType = input.id;
//     input.value = nutritionFacts[nutrientType].value;
//   });

//   nutritionInputs.forEach((input) => {
//     input.addEventListener("input", function () {
//       const nutrientType = this.id;
//       const newValue = this.value;
//       if (!newValue.match(/^\d+(\.\d+)?$/)) {
//         this.value = newValue.replace(/[^\d.]/g, "");
//       }
//       updateNutritionFact(nutrientType, this.value);
//     });
//   });

//   document.querySelectorAll(".edit-icon").forEach((icon) => {
//     icon.addEventListener("click", function () {
//       let currentlyEditingInput =
//         this.closest(".value-container").querySelector(".nutrition-value");
//       currentlyEditingInput.readOnly = false;
//       currentlyEditingInput.focus();
//       document.getElementById("buttonContainer").style.display = "flex";
//     });
//   });

//   document.getElementById("saveButton").addEventListener("click", function () {
//     nutritionInputs.forEach((input) => {
//       const nutrientType = input.id;
//       const newValue = input.value;
//       updateNutritionFact(nutrientType, newValue);
//     });
//     document.getElementById("buttonContainer").style.display = "none";
//   });

//   document
//     .getElementById("cancelButton")
//     .addEventListener("click", function () {
//       nutritionInputs.forEach((input) => {
//         input.readOnly = true;
//       });
//       document.getElementById("buttonContainer").style.display = "none";
//     });
// });

let selectedDietaryOptions = [];
let selectedCuisines = [];
let selectedMealTypes = [];
let selectedRecipeDifficulty = "";
let selectedPreparationTime = 0;
let selectedCookingTime = 0;
let selectedCookingMethods = [];

const modal = document.getElementById("createRecipeModal");
const successModal = document.getElementById("successModal");

const firstSection = document.getElementById("firstSection");

const nextToThirdBtn = document.getElementById("thirdSectionBtn");
const backButtonSecond = document.getElementById("backButton");
const backButtonThird = document.getElementById("backButtonThird");

function showSuccessModal() {
  successModal.style.display = "block";
}

function closeSuccessModal() {
  successModal.style.display = "none";
}

nextToThirdBtn.addEventListener("click", () => {
  let allSectionsFilled = true;

  allInstructions.forEach((section) => {
    if (section.subtitle === "" || section.instructions.length === 0) {
      allSectionsFilled = false;
    }
  });

  if (allSectionsFilled) {
    firstSection.style.display = "none";
    secondSection.style.display = "none";
    thirdSection.style.display = "block";
    attachUploadButtonListener();
  }
});

backButtonSecond.addEventListener("click", () => {
  firstSection.style.display = "block";
  secondSection.style.display = "none";
  thirdSection.style.display = "none";
});

backButtonThird.addEventListener("click", () => {
  firstSection.style.display = "none";
  secondSection.style.display = "block";
  thirdSection.style.display = "none";
});

function attachUploadButtonListener() {
  const uploadButton = document.getElementById("uploadRecipeBtn");
  if (uploadButton) {
    uploadButton.addEventListener("click", handleUpload);
  } else {
    console.error("Upload button not found");
  }
}

function handleUpload() {
  const recipeData = {
    recipeTitle: document.getElementById("recipeTitle").value.trim(),
    preparationTime: selectedPreparationTime,
    cookingTime: selectedCookingTime,
    recipeDifficulty: selectedRecipeDifficulty,
    cookingMethods: selectedCookingMethods.join(", "),
    dietaryOptions: selectedDietaryOptions.join(", "),
    cuisines: selectedCuisines.join(", "),
    mealTypes: selectedMealTypes.join(", "),
    ingredientsList: allIngredientsLists,
    instructionsList: allInstructions,
    nutritionValues: nutritionFacts,
  };
  console.log("Recipe Data:", recipeData);

  fetch("createRecipe.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(recipeData),
  })
    .then((response) => response.text())
    .then((text) => {
      console.log("Raw response:", text);
      try {
        return JSON.parse(text);
      } catch (error) {
        console.error("Error parsing JSON:", error, "\nResponse:", text);
        throw error;
      }
    })
    .then((data) => {
      if (data.status === "success") {
        console.log("Recipe created successfully", data.recipeID);
        modal.style.display = "none";
        showSuccessModal();
        uploadImage(data.recipeID);
        resetModal();
      } else {
        alert("Failed to create recipe: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error in response or parsing JSON", error);
      alert("Failed to process response: " + error.message);
    });
}

function uploadImage(recipeID) {
  const fileInput = document.getElementById("hiddenFileInput");
  if (fileInput.files.length > 0) {
    const formData = new FormData();
    formData.append("imageUpload", fileInput.files[0]);
    formData.append("recipeID", recipeID);

    fetch("uploadRecipeImage.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          console.log("Image uploaded successfully: ", data);
        } else {
          throw new Error(data.message);
        }
      })
      .catch((error) => {
        console.error("Error uploading image", error);
        alert("Failed to upload image: " + error.message);
      });
  } else {
    alert("Please select an image to upload.");
  }
}

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

    bubble.style.left = `calc(${newVal}% + (${8 - newVal * 0.16}px))`;
  }
});

const loadMoreButton = document.querySelector(".load-more-cuisines-button");
const loadLessButton = document.querySelector(".load-less-cuisines-button");
let currentGroupIndex = 0;

loadMoreButton.addEventListener("click", function () {
  const allGroups = document.querySelectorAll(".cuisine-group");
  const groupToShow = allGroups[currentGroupIndex];

  if (groupToShow) {
    groupToShow.style.display = "flex";
    groupToShow.style.flexWrap = "wrap";
    groupToShow.style.gap = "10px";
    currentGroupIndex++;
  }

  if (currentGroupIndex > 0) {
    loadLessButton.style.display = "block";
  }

  if (currentGroupIndex >= allGroups.length) {
    loadMoreButton.style.display = "none";
  }
});

loadLessButton.addEventListener("click", function () {
  if (currentGroupIndex > 0) {
    currentGroupIndex--;
    const groupToHide =
      document.querySelectorAll(".cuisine-group")[currentGroupIndex];
    groupToHide.style.display = "none";
  }

  loadMoreButton.style.display = "block";

  if (currentGroupIndex === 0) {
    loadLessButton.style.display = "none";
  }
});

document.addEventListener("DOMContentLoaded", function () {
  function toggleSelection(array, value) {
    const index = array.indexOf(value);
    if (index > -1) {
      array.splice(index, 1);
    } else {
      array.push(value);
    }
  }

  document
    .querySelectorAll(".dietary-option, .cuisine-option, .meal-type-option")
    .forEach((checkbox) => {
      checkbox.addEventListener("change", function () {
        let selectionArray = checkbox.classList.contains("dietary-option")
          ? selectedDietaryOptions
          : checkbox.classList.contains("cuisine-option")
          ? selectedCuisines
          : selectedMealTypes;
        toggleSelection(selectionArray, checkbox.id);
        console.log(selectionArray);
      });
    });

  document.querySelectorAll(".recipe-difficulty").forEach((radio) => {
    radio.addEventListener("change", function () {
      if (radio.checked) {
        selectedRecipeDifficulty = radio.id;
        console.log("Difficulty:", selectedRecipeDifficulty);
      }
    });
  });

  document
    .getElementById("modalPrepTime")
    .addEventListener("input", function (event) {
      selectedPreparationTime = parseInt(event.target.value);
      document.getElementById(
        "modalPrepTimeDisplay"
      ).textContent = `${event.target.value} min`;
      console.log("Preparation Time Updated:", selectedPreparationTime);
    });

  document
    .getElementById("modalCookTime")
    .addEventListener("input", function (event) {
      selectedCookingTime = parseInt(event.target.value);
      document.getElementById(
        "modalCookTimeDisplay"
      ).textContent = `${event.target.value} min`;
      console.log("Cooking Time Updated:", selectedCookingTime);
    });

  document
    .querySelectorAll("#modal-cooking-method-options .custom-control-input")
    .forEach((checkbox) => {
      checkbox.addEventListener("change", function () {
        toggleSelection(selectedCookingMethods, this.id);
        console.log("Cooking Methods:", selectedCookingMethods);
      });
    });
});
