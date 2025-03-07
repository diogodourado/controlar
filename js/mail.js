$(document).ready(function () {
    $("#meuFormulario").submit(function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário
        var form = this; // Armazena a referência ao formulário

        var botao = $("#meuBotao");
        var textoInicial = botao.text();


        botao.prop("disabled", true);
        botao.text("Processando...");

        grecaptcha.ready(function () {
            grecaptcha.execute('6Ld0C7wdAAAAAKqJH0NBADIk-Uf7eX6K4YD-CIMP', { action: 'submit' }).then(function (token) {
                document.getElementById('recaptchaToken').value = token;

                $.ajax({
                    type: "POST",
                    url: "_mailSender.php",
                    data: $(form).serialize(), // Serializa os dados do formulário usando a referência armazenada
                    success: function (response) {
                        if (response.success) {
                            $("#mensagemFeedback").html(response.message);
                            $("#meuFormulario")[0].reset();
                        } else {
                            $("#mensagemFeedback").html(response.message);
                        }

                        botao.prop("disabled", false);
                        botao.text(textoInicial);
                    },
                    error: function () {
                        $("#mensagemFeedback").html("Ocorreu um erro ao enviar a mensagem.");


                        botao.prop("disabled", false);
                        botao.text(textoInicial);
                    }
                });
            });
        });
    });
});