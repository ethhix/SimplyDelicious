document.addEventListener("DOMContentLoaded", function () {
  document.body.addEventListener("submit", function (event) {
    if (event.target.matches(".reply-form")) {
      event.preventDefault(); // Prevent the default form submission

      const form = event.target;
      const formData = new FormData(form);

      fetch(form.action, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            console.log("Reply added:", data);
            addCommentToPage(data.comment, data.comment.parentId); //assumes comment is structured correctly
            form.style.display = "none"; //hide form after submission
            form.reset(); //reset the form fields
          } else {
            console.error("Failed to add reply:", data.message);
          }
        })
        .catch((error) =>
          console.error("Error during form submission:", error)
        );
    }
  });
});

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
    //Locate the main list for comments, or create it if it doesn't exist
    parentList = document.querySelector(".comments-list");
    if (!parentList) {
      parentList = document.createElement("ul");
      parentList.className = "comments-list";
      const commentsContainer = document.querySelector(".comments-container"); // Adjust selector as necessary
      if (commentsContainer) {
        commentsContainer.appendChild(parentList);
      } else {
        console.error("No container found to append the comments list.");
        return;
      }
    }
  }

  const profilePicUrl = comment.profilePicUrl || "profile-icon.jpg";

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
          <a href="javascript:void(0);" class="comment-reply" onclick="showReplyForm(${comment.id}, event)" style="font-size: 15px;">Reply (0)</a>
          <a href="javascript:void(0);" class="comment-likes" style="cursor: pointer; font-size: 15px;" onclick="toggleLike(${comment.id})">
            <i class="fas fa-heart"></i> 0
          </a>
          <button onclick="deleteComment(${comment.id})" class="btn btn-danger">Delete</button>
        </div>
        <form id="reply-form-${comment.id}" class="reply-form" style="display:none;" method="post" action="submitDiscussionComment.php">
          <textarea name="commentText" required placeholder="Write your reply..."></textarea>
          <input type="hidden" name="postID" value="${comment.postID}">
          <input type="hidden" name="parentID" value="${comment.id}">
          <button type="submit">Submit Reply</button>
          <button type="button" onclick="hideReplyForm(${comment.id})">Cancel</button>
        </form>
        <ul class="comments-list"></ul>
      </li>
    `;

  //Append the new comment HTML to the appropriate list
  parentList.innerHTML += commentHTML;
}

function showReplyForm(commentId, event) {
  event.preventDefault(); //stop the link from causing a page jump
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
    replyForm.style.display = "none"; //Hide the reply form
  }
}

function toggleLike(commentID) {
  fetch("handleDiscussionCommentLike.php", {
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
        `#comment-${commentID} .comment-likes`
      );
      let likeCount = parseInt(likeButton.textContent.match(/\d+/)[0]); //extract the number

      if (data.liked) {
        likeButton.classList.add("liked");
        likeCount++;
      } else {
        likeButton.classList.remove("liked");
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
      console.log(
        "Attempting to delete discussion comment with ID:",
        commentID
      ); //Check if the ID is correctly fetched
      if (commentID) {
        deleteDiscussionComment(commentID);
      } else {
        console.error("Comment ID is undefined or not fetched correctly.");
      }
    }
  });
});

function deleteDiscussionComment(commentID) {
  if (confirm("Are you sure you want to delete this discussion comment?")) {
    fetch(
      `deleteDiscussionComments.php?commentID=${encodeURIComponent(commentID)}`,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
      }
    )
      .then((response) => response.json())
      .then((data) => {
        console.log(
          "Server response for deletion of discussion comment:",
          data
        ); //Log the server response
        if (data.status === "success") {
          const commentElement = document.getElementById(
            "comment-" + commentID
          );
          console.log(
            "Looking for element to remove:",
            "#comment-" + commentID
          ); //Log the selector used
          if (commentElement) {
            commentElement.remove();
            console.log("Discussion comment removed successfully from DOM.");
          } else {
            console.error(
              "Failed to locate the discussion comment in the DOM with ID:",
              commentID
            );
          }
        } else {
          alert(data.message); //display any server-side error messages
        }
      })
      .catch((error) => {
        console.error("Error during deletion of discussion comment:", error);
      });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("discussion-comment-form");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(form);
    fetch(form.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          console.log("Comment added:", data);
          addCommentToPage(data.comment, data.comment.parentId);
          form.reset(); //reset form
        } else {
          console.error("Failed to add comment:", data.message);
        }
      })
      .catch((error) => console.error("Error during form submission:", error));
  });
});
