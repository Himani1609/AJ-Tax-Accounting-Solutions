document.addEventListener("DOMContentLoaded", function () {
  // Consultation Form
  const consultationForm = document.getElementById("consultation-form");
  if (consultationForm) {
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
          console.error("Consultation Form Error:", err);
          alert("An error occurred. Please try again.");
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
