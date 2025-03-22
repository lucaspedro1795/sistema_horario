document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); 

    let email = document.getElementById("email").value;
    let senha = document.getElementById("senha").value;
    let messageBox = document.getElementById("message");

    console.log(email, senha);

    fetch("./model/getUserLogin.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${encodeURIComponent(email)}&senha=${encodeURIComponent(senha)}`
    })
    .then(response => response.json())
    .then(data => {
        messageBox.textContent = data.message;
        messageBox.style.color = data.success ? "green" : "red";

        if (data.success) {
            setTimeout(() => {
                window.location.href = "./view/home.php"; // Redireciona após login bem-sucedido
            }, 1000);
        }
    })
    .catch(error => {
        messageBox.textContent = "Erro ao conectar ao servidor!";
        messageBox.style.color = "red";
    });
});



