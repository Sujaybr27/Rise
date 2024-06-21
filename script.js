// Function to toggle dropdown menu visibility
function toggleDropdown() {
  const dropdownMenu = document.getElementById("dropdown-menu");
  dropdownMenu.classList.toggle("show");
}

// Function to fetch student details and update the webpage
window.onload = function () {
  // Check if we are on the student details page
  if (window.location.pathname.endsWith("index.html")) {
    fetch("welcome.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        if (data.error) {
          console.error(data.error);
          return;
        }

        // Update Student Details section
        document.getElementById("student-name").textContent = data.name;
        document.getElementById("student-email").textContent = data.email;
        document.getElementById("student-enrollment").textContent =
          data.enrollment;
        document.getElementById("student-semester").textContent = data.semester;
        document.getElementById("student-backlogs").textContent = data.backlogs;
        document.getElementById("student-phone").textContent = data.phone;
        document.getElementById("student-cgpa").textContent = data.cgpa;

        // Update Placement Status section
        document.getElementById("placement-status-text").textContent =
          data.status;
        document.getElementById("company-name").textContent = data.company;
        document.getElementById("position-name").textContent = data.position;
        document.getElementById("package").textContent = data.package;
        document.getElementById("recruited-date").textContent =
          data.recruitedDate;

        // Update Internship/Job History section
        const jobHistoryList = document.getElementById("job-history-list");

        if (data.jobHistory.length > 0) {
          data.jobHistory.forEach((job) => {
            const li = document.createElement("li");
            li.innerHTML = `
              <p>Company: ${job.company}</p>
              <p>Role: ${job.role}</p>
              <p>Term: ${job.term} months</p>
            `;
            jobHistoryList.appendChild(li);
          });
        } else {
          const li = document.createElement("li");
          li.textContent = "No job history available";
          jobHistoryList.appendChild(li);
        }
      })
      .catch((error) => {
        console.error("Error fetching student details:", error);
      });
  }

  // Check if we are on the login page
  if (window.location.pathname.endsWith("login.html")) {
    document
      .getElementById("login-form")
      .addEventListener("submit", function (event) {
        // Prevent the form from submitting the traditional way
        event.preventDefault();

        // Create a FormData object
        const formData = new FormData(this);

        // Send a POST request with the form data
        fetch("login.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => {
            if (response.redirected) {
              // Redirect to the redirected URL
              window.location.href = response.url;
            } else {
              return response.json();
            }
          })
          .then((data) => {
            if (data && data.error) {
              // Display error message
              document.getElementById("error-message").innerText = data.error;
            }
          })
          .catch((error) => {
            console.error("Error during login:", error);
            document.getElementById("error-message").innerText =
              "An error occurred. Please try again.";
          });
      });
  }
};
