<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestão de Exames</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .container {
      margin-top: 20px;
    }
    .popup {
      position: fixed;
      top: 20%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1050;
      display: none;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
      border-radius: 8px;
    }
    .popup.success {
      border-left: 5px solid green;
    }
    .popup.fail {
      border-left: 5px solid red;
    }
    .popup .message {
      font-size: 16px;
    }
    .popup .close {
      background: none;
      border: none;
      font-size: 18px;
      float: right;
      cursor: pointer;
    }
    @media (max-width: 768px) {
      .popup {
        width: 90%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="text-center">Gestão de Exames</h1>
    <form id="examForm">
      <div id="studentsContainer">
        <!-- Aluno inicial -->
        <div class="student" data-student-index="0">
          <div class="mb-3">
            <label for="bi-0" class="form-label">BI</label>
            <input type="text" class="form-control" id="bi-0" name="bi[]" required>
          </div>
          <div class="mb-3">
            <label for="numero_ordem-0" class="form-label">Número de Ordem</label>
            <input type="text" class="form-control" id="numero_ordem-0" name="numero_ordem[]" required>
          </div>
          <div class="discipline-container">
            <div class="mb-3">
              <label for="nome_disciplina-0-0" class="form-label">Nome da Disciplina</label>
              <select class="form-select" id="nome_disciplina-0-0" name="nome_disciplina[0][]" required>
                <option value="Matemática">Matemática</option>
                <option value="Português">Português</option>
                <option value="História">História</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="nota_fase1-0-0" class="form-label">Nota Fase 1</label>
              <input type="number" class="form-control" id="nota_fase1-0-0" name="nota_fase1[0][]" required>
            </div>
          </div>
          <button type="button" class="btn btn-primary add-discipline" data-student-index="0">Adicionar Outra Disciplina</button>
          <button type="button" class="btn btn-danger remove-discipline d-none" data-student-index="0">Remover Disciplina</button>
          <hr>
        </div>
      </div>
      <button type="button" class="btn btn-success add-student">Adicionar Outro Aluno</button>
      <button type="submit" class="btn btn-primary mt-3">Submeter</button>
    </form>
  </div>

  <div id="popup" class="popup">
    <button class="close" onclick="hidePopup()">×</button>
    <p class="message"></p>
  </div>

  <script>
    let studentCount = 1;

    // Show popup
    function showPopup(message, isSuccess = true) {
      const popup = document.getElementById('popup');
      popup.className = `popup ${isSuccess ? 'success' : 'fail'}`;
      popup.querySelector('.message').textContent = message;
      popup.style.display = 'block';
    }

    // Hide popup
    function hidePopup() {
      document.getElementById('popup').style.display = 'none';
    }

    // Add discipline
    document.addEventListener('click', function(event) {
      if (event.target.classList.contains('add-discipline')) {
        const studentIndex = event.target.getAttribute('data-student-index');
        const disciplineContainer = document.querySelector(`.student[data-student-index="${studentIndex}"] .discipline-container`);
        
        const newDisciplineHtml = `
          <div class="mb-3">
            <label for="nome_disciplina-${studentIndex}-1" class="form-label">Nome da Disciplina</label>
            <select class="form-select" id="nome_disciplina-${studentIndex}-1" name="nome_disciplina[${studentIndex}][]" required>
              <option value="Matemática">Matemática</option>
              <option value="Português">Português</option>
              <option value="História">História</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nota_fase1-${studentIndex}-1" class="form-label">Nota Fase 1</label>
            <input type="number" class="form-control" id="nota_fase1-${studentIndex}-1" name="nota_fase1[${studentIndex}][]" required>
          </div>
        `;
        disciplineContainer.insertAdjacentHTML('beforeend', newDisciplineHtml);
        event.target.classList.add('d-none');
        const removeButton = document.querySelector(`.student[data-student-index="${studentIndex}"] .remove-discipline`);
        removeButton.classList.remove('d-none');
      }
    });

    // Remove discipline
    document.addEventListener('click', function(event) {
      if (event.target.classList.contains('remove-discipline')) {
        const studentIndex = event.target.getAttribute('data-student-index');
        const disciplineContainer = document.querySelector(`.student[data-student-index="${studentIndex}"] .discipline-container`);
        
        disciplineContainer.innerHTML = '';
        const addButton = document.querySelector(`.student[data-student-index="${studentIndex}"] .add-discipline`);
        addButton.classList.remove('d-none');
        event.target.classList.add('d-none');
      }
    });

    // Add student
    document.querySelector('.add-student').addEventListener('click', function() {
      const newStudentHtml = `
        <div class="student" data-student-index="${studentCount}">
          <div class="mb-3">
            <label for="bi-${studentCount}" class="form-label">BI</label>
            <input type="text" class="form-control" id="bi-${studentCount}" name="bi[]" required>
          </div>
          <div class="mb-3">
            <label for="numero_ordem-${studentCount}" class="form-label">Número de Ordem</label>
            <input type="text" class="form-control" id="numero_ordem-${studentCount}" name="numero_ordem[]" required>
          </div>
          <div class="discipline-container">
            <div class="mb-3">
              <label for="nome_disciplina-${studentCount}-0" class="form-label">Nome da Disciplina</label>
              <select class="form-select" id="nome_disciplina-${studentCount}-0" name="nome_disciplina[${studentCount}][]" required>
                <option value="Matemática">Matemática</option>
                <option value="Português">Português</option>
                <option value="História">História</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="nota_fase1-${studentCount}-0" class="form-label">Nota Fase 1</label>
              <input type="number" class="form-control" id="nota_fase1-${studentCount}-0" name="nota_fase1[${studentCount}][]" required>
            </div>
          </div>
          <button type="button" class="btn btn-primary add-discipline" data-student-index="${studentCount}">Adicionar Outra Disciplina</button>
          <button type="button" class="btn btn-danger remove-discipline d-none" data-student-index="${studentCount}">Remover Disciplina</button>
          <button type="button" class="btn btn-danger remove-student" data-student-index="${studentCount}">Remover Aluno</button>
          <hr>
        </div>
      `;
      document.getElementById('studentsContainer').insertAdjacentHTML('beforeend', newStudentHtml);
      studentCount++;
    });

    // Remove student
    document.addEventListener('click', function(event) {
      if (event.target.classList.contains('remove-student')) {
        const studentIndex = event.target.getAttribute('data-student-index');
        const studentElement = document.querySelector(`.student[data-student-index="${studentIndex}"]`);
        studentElement.remove();
      }
    });

    // Form submission
    document.getElementById('examForm').addEventListener('submit', function(event) {
      event.preventDefault();
      
      // Example validation and simulation
      const isValid = true; // Simulate validation result
      if (isValid) {
        showPopup('Formulário submetido com sucesso!', true);
      } else {
        showPopup('Erro ao submeter o formulário.', false);
      }
    });
  </script>
</body>
</html>
