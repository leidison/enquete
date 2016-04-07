/**
 * Created by Leidison Siqueira on 04/01/16.
 */
angular.module("enquete").constant('MESSAGES', {
    infinity: 0,
    //------------------------------------------------------------------------------
    // MENSAGENS DE ERRO
    //------------------------------------------------------------------------------
    error: "<strong>Eita!</strong> O sistema está com problemas. Tente novamente.",
    erroServidorOff: "<strong>Eita!</strong> O nosso servidor está fora do ar. Aguarde um tempo e tente novamente.",
    errorGetAccount: "<strong>Eita!</strong> Não conseguimos recurar os seus dados. Tente novamente.",

    //------------------------------------------------------------------------------
    // MENSAGENS DE ALERTA
    //------------------------------------------------------------------------------
    erroLogin: "<strong>Atenção:</strong> Usuário ou senha estão incorretos",
    dadosAcessosExistentes: "<strong>Atenção:</strong> O email já está sendo utilizado",

    //------------------------------------------------------------------------------
    // MENSAGENS DE SUCESSO
    //------------------------------------------------------------------------------
    contaRegistrada: "<strong> Parabéns!</strong> Sua conta foi registrada com sucesso. Você já pode começar a fazer perguntas",
    contaRegistradaSemLogin: "<strong> Parabéns!</strong> Sua conta foi registrada com sucesso. Digite seu login e senha para começar a perguntar",
    sucessoLogin: "<strong>Bem vindo!</strong> Mate suas dúvidas a vontade.",
    sucessoLogout: "<strong>Deslogado com sucesso!</strong> Até a próxima."
});