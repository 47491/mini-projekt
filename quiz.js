window.addEventListener('DOMContentLoaded', function () {
  var bildContainer = document.getElementById('bild-container');
  var bild = document.getElementById('bild');
  var wrongAnswers = 0;
  var score = 0;

  function adjustImageSize() {
    var containerWidth = bildContainer.offsetWidth;
    var containerHeight = bildContainer.offsetHeight;
    var imageWidth = bild.naturalWidth;
    var imageHeight = bild.naturalHeight;

    if (imageWidth > containerWidth || imageHeight > containerHeight) {
      var containerAspectRatio = containerWidth / containerHeight;
      var imageAspectRatio = imageWidth / imageHeight;

      if (imageAspectRatio > containerAspectRatio) {
        bild.style.width = '100%';
        bild.style.height = 'auto';
      } else {
        bild.style.width = 'auto';
        bild.style.height = '100%';
      }
    }
  }

  window.addEventListener('load', adjustImageSize);
  window.addEventListener('resize', adjustImageSize);

  var alternativElements = document.getElementsByClassName('alternativ');
  var isAnswerSelected = false;
  var questionCounterElement = document.getElementById('fragorna');
  var maxQuestionCounter = document.getElementById('maxQuestionCounter').value;

  var currentQuestionCounter = parseInt(sessionStorage.getItem('questionCounter'));
  if (!currentQuestionCounter || currentQuestionCounter >= maxQuestionCounter || currentQuestionCounter <= 0) {
    currentQuestionCounter = 1;
    sessionStorage.setItem('questionCounter', currentQuestionCounter);
  } else {
    questionCounterElement.textContent = currentQuestionCounter + '/' + maxQuestionCounter;
  }

  function handleAnswerClick(element) {
    if (isAnswerSelected) {
      return;
    }

    isAnswerSelected = true;

    var correctAnswer = element.getAttribute('data-correct-answer');
    var selectedAnswer = element.innerHTML;

    if (selectedAnswer === correctAnswer) {
      element.style.backgroundColor = 'green';
      score++;
    } else {
      element.style.backgroundColor = 'red';
      wrongAnswers++;
    }

    setTimeout(function () {
      location.reload();
    }, 1500);

    currentQuestionCounter++;

    questionCounterElement.textContent = currentQuestionCounter + '/' + maxQuestionCounter;

    if (currentQuestionCounter >= maxQuestionCounter) {
      setTimeout(function () {
        window.location.href = 'quiz_slut.php';
      }, 1500);
    }

    sessionStorage.setItem('questionCounter', currentQuestionCounter);
  }

  for (var i = 0; i < alternativElements.length; i++) {
    alternativElements[i].addEventListener('click', function () {
      handleAnswerClick(this);
    });
  }

  window.addEventListener('beforeunload', function () {
    sessionStorage.setItem('wrongAnswers', wrongAnswers);
    sessionStorage.setItem('score', score);
  });
});
