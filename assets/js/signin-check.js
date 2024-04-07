document.getElementById("signin-form").addEventListener("submit", function (event) {
    // Detener el envío del formulario
    event.preventDefault();
    var minimum_age = 15;    // Obtener los valores de los campos
    var password = document.querySelector('input#password').value.trim();
    var password_2 = document.querySelector('input#password-2').value.trim();
    var birth_date = new Date(document.querySelector('input#date').value);
    var current_date = new Date();
    var age = current_date.getFullYear() - birth_date.getFullYear();
    var genre = document.querySelector('select#genre').value;

    if (age < minimum_age) {
        alert('No se cumple la edad mínima.');
        return;
    } else {
        console.log('Edad ✅');
    }
    // Realizar las validaciones
    if (password != password_2) {
        alert("Las contraseñas deben ser iguales.");
        return;
    }
    if (genre < 1) {
        alert('Elige género.');
        return;
    }
    // Si pasa las validaciones, enviar el formulario
    this.submit();
});
