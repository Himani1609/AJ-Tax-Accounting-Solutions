document.addEventListener("DOMContentLoaded", function () {

  const consultationForm = document.getElementById("consultation-form");

  if (consultationForm) {
    // Remove any existing jQuery submit handlers just to be safe
    if (window.jQuery) {
      jQuery(consultationForm).off("submit");
    }

    // Now attach your own handler
    consultationForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(consultationForm);

      fetch(consultationForm.action, {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            alert(data.message);
            consultationForm.reset(); 
          } else {
            alert(data.message || "Something went wrong.");
          }
        })
        .catch((err) => {
          alert("An error occurred. Please try again.");
          console.error(err);
        });
    });
  }



  // Feedback Form
  const feedbackForm = document.getElementById("feedback-form");
  if (feedbackForm) {
    feedbackForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(feedbackForm);

      fetch(feedbackForm.action, {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            alert(data.message);
            feedbackForm.reset();
          } else {
            alert(data.message || "Something went wrong.");
          }
        })
        .catch((err) => {
          console.error("Feedback Form Error:", err);
          alert("An error occurred. Please try again.");
        });
    });
  }
});
