// Helper function to shuffle an array
function shuffleArray(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
}

window.onload = function () {
  let ratt = sessionStorage.getItem("ratt");
  if (!ratt) {
    sessionStorage.setItem("ratt", 0);
  }
  //console.log(ratt);

  var alternatives = document.getElementsByClassName('alternativ');
  var questionCounter = 0; // Initialize the question counter

  // Create an array of all the alternatives (including the correct answer)
  var allAlternatives = Array.from(alternatives);

  // Shuffle the array of all alternatives
  shuffleArray(allAlternatives);

  // Get the container element for the alternatives
  var alternativesContainer = document.getElementById('alternativ');

  // Clear the existing alternatives
  alternativesContainer.innerHTML = '';

}
// Clear the existing alternatives
alternativesContainer.innerHTML = '';


// Append the shuffled alternatives to the container
allAlternatives.forEach(function(alternative) {
  alternativesContainer.appendChild(alternative);
});

for (var i = 0; i < alternatives.length; i++) {
  var alternative = alternatives[i];
  alternative.addEventListener('click', function () {
    var selectedAnswer = this.textContent;
    var question = document.getElementById('bild')
    var qid = question.getAttribute('data-qId');

    // Clear previous selections
    for (var j = 0; j < alternatives.length; j++) {
      alternatives[j].classList.remove('selected', 'correct', 'wrong');
    }

    // Apply selected class to the clicked option
    this.classList.add('selected');

    var button = this;

    let FD = new FormData();
    FD.append("svar", selectedAnswer);
    FD.append("ID", qid);

    fetch('quiz_answer.php', {
      method: 'POST',
      body: FD
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        console.log(data);
        if (data.correct) {
          button.classList.add('correct');
          ratt++;
          sessionStorage.setItem("ratt",ratt);
        } else {
          button.classList.add('wrong');
        }
        button.disabled = true;

        setTimeout(function () {
        location.reload();
        }, 1500);
        
      })

  });

  var buttonClicked = false; // Flag to check if a button is already clicked

  for (var i = 0; i < alternatives.length; i++) {
    var alternative = alternatives[i];
    alternative.addEventListener('click', function () {
      if (!buttonClicked) {
        buttonClicked = true; // Set the flag to true to prevent further clicks

        var selectedAnswer = this.textContent;
        var question = document.getElementById('bild');
        var qid = question.getAttribute('data-qId');

        // Clear previous selections
        for (var j = 0; j < alternatives.length; j++) {
          alternatives[j].classList.remove('selected', 'correct', 'wrong');
          alternatives[j].classList.add('disabled'); // Add disabled class to all buttons
        }

        // Apply selected class to the clicked option
        this.classList.add('selected');

        var button = this;

        let FD = new FormData();
        FD.append("svar", selectedAnswer);
        FD.append("ID", qid);

        fetch('quiz_answer.php', {
          method: 'POST',
          body: FD
        })
          .then(function (response) {
            console.log(response); // Log the response from the server
            return response.json();
          })
          .then(function (data) {
            console.log(data);
            if (data.correct) {
              button.classList.add('correct');
              ratt++;
              sessionStorage.setItem("ratt", ratt);
            } else {
              button.classList.add('wrong');
            }
            button.disabled = true;

            setTimeout(function () {
              button.classList.remove('disabled'); // Remove disabled class from the clicked button
              buttonClicked = false; // Reset the flag after the 1.5-second timeout
              questionCounter++; // Increment the question counter
              console.log("Question Counter:", questionCounter); // Log the updated question counter
              location.reload();
            }, 1500);
          });
      }
    });
  }
};
