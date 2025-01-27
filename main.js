const studentNumberForm = document.querySelector("#studentNumberForm");
const studentNumberInput = document.querySelector("#studentNumber");
const submitButton = document.querySelector("#submitStudentNumber");
submitButton.addEventListener("click", function () {
    const studentNumber = studentNumberInput.value.trim();
    if (studentNumber) {
        window.location.href = `LandingPageFiltry.html?studentNumber=${studentNumber}`;
    } else {
        alert("Proszę podać numer albumu.");
    }
});