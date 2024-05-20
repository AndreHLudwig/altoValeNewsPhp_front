<?php
include_once("templates/header.php");
?>
<main class="contact-page">
    <section class="contact-info">
        <h1>Entre em contato</h1>
        <p>Entre em contato conosco através do formulário abaixo:</p>
        <div class="info">
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <span>(47) 91234-5678</span>
            </div>
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <span>contato@altovalenews.com.br</span>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>Rua Ponto Chic, 1001, Ibirama - SC</span>
            </div>
        </div>
    </section>
    <section class="contact-form">
        <h2>Envie uma Mensagem</h2>
        <form>
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Mensagem</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button onclick="contactar()" class="btn btn-primary">Enviar</button>
        </form>
    </section>
    <section class="map">
        <h2>Localização</h2>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item"
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d113701.98471047379!2d-49.5296759!3d-27.055844!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dfb20d8f598fab%3A0x8871264e819c7ac2!2sUDESC%20-%20Alto%20Vale%20-%20CEAVI!5e0!3m2!1spt-BR!2sus!4v1716161699391!5m2!1spt-BR!2sus"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
</main>
<script>
    function contactar() {
        // Obtém o texto do comentário do usuário
        var message = document.getElementById("message").value.trim();
        var name = document.getElementById("name").value.trim();
        var email = document.getElementById("email").value.trim();

        // Verifica se o campo de comentário está vazio
        if (message === "") {
            alert("Por favor, escreva algo antes de enviar a mensagem.");
            return;
        }

        // Cria o objeto JSON com os dados do comentário
        var data = {
            "nome": nome,
            "email": email,
            "data": new Date().toISOString().split('T')[0],
            "message": message
        };

        // Envia o comentário para o servidor
        fetch('http://localhost:8080/contato', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Erro ao enviar a mensagem!');
            })
            .then(data => {
                // Mensagem enviado com sucesso
                console.log('Mensagem enviada!:', data);
                location.reload();
            })
            .catch(error => {
                console.error('Erro:', error);
            });
    }
</script>
<?php
include_once("templates/footer.php");
?>
