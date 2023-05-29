window.addEventListener('DOMContentLoaded', function () {
  var bildContainer = document.getElementById('bild-container');
  var bild = document.getElementById('bild');
  var wrongAnswers = 0;
  var score = 0;

  function adjustImageSize() {
    // ... your existing code for adjusting image size
  }

  adjustImageSize();

  bild.addEventListener('load', adjustImageSize);

  var alternatives = document.getElementsByClassName('alternativ');

  for (var i = 0; i < alternatives.length; i++) {
    var alternative = alternatives[i];
    alternative.addEventListener('click', function () {
      var selectedAnswer = this.textContent;
      var correctAnswer = this.getAttribute('value');

      if (selectedAnswer === correctAnswer) {
        this.classList.add('correct');
        score++;
      } else {
        this.classList.add('wrong');
        wrongAnswers++;
      }

      setTimeout(function () {
        location.reload();
      }, 1500);

      if (currentQuestionCounter >= maxQuestionCounter) {
        setTimeout(function () {
          window.location.href = 'quiz_slut.php';
        }, 1500);
      }

      // Fetch request to quiz_answer.php
      fetch('quiz_answer.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          ratt: selectedAnswer,
          ID: currentQuestionID // Replace with the appropriate question ID
        })
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          // Handle the response data
          console.log(data);
          // Example: Update UI based on the response
          if (data.utskrift === 1) {
            // Correct answer
          } else {
            // Wrong answer
          }
        })
        .catch(function (error) {
          // Handle any errors
          console.error(error);
        });
    });
  }
});
