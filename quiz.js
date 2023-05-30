// Helper function to shuffle an array
function shuffleArray(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
}

var alternatives = document.getElementsByClassName('alternativ');

// Create an array of all the alternatives (including the correct answer)
var allAlternatives = Array.from(alternatives);

// Shuffle the array of all alternatives
shuffleArray(allAlternatives);

// Get the container element for the alternatives
var alternativesContainer = document.getElementById('alternativ');

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
    var answerId = this.getAttribute('id');

    // Clear previous selections
    for (var j = 0; j < alternatives.length; j++) {
      alternatives[j].classList.remove('selected', 'correct', 'wrong');
    }

    // Apply selected class to the clicked option
    this.classList.add('selected');

    var button = this;

    fetch('quiz_answer.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        answer: selectedAnswer,
        ID: answerId
      })
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        console.log(data);
        if (data.correct) {
          button.classList.add('correct');
        } else {
          button.classList.add('wrong');
          var correctButton = document.querySelector('#alternativ .correct');
          correctButton.classList.add('correct');
        }
        button.disabled = true;

        var countdown = 3;
        var countdownInterval = setInterval(function () {
          button.textContent = countdown;
          countdown--;

          if (countdown < 0) {
            clearInterval(countdownInterval);
            location.reload();
          }
        }, 1000);
      })
      .catch(function (error) {
        console.error(error);
      });
  });
}
