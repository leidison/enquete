/**
 * Created by Leidison Siqueira on 04/01/16.
 */
angular.module("enquete").constant('MESSAGES', {
    infinity: 0,
    default: 5000,
    mostrarNaProximaPagina: {"class": "oneChanceToClose"},
    //------------------------------------------------------------------------------
    // MENSAGENS DE ERRO
    //------------------------------------------------------------------------------
    error: "<strong>Eita!</strong> O sistema está com problemas. Tente novamente.",
    erroServidorOff: "<strong>Eita!</strong> O nosso servidor está fora do ar. Aguarde um tempo e tente novamente.",
    errorGetAccount: "<strong>Eita!</strong> Não conseguimos recurar os seus dados. Tente novamente.",
    erroCadastroEnquete: "<strong>Eita!</strong> Ocorreram erros durante o cadastro da enquete.",
    erroEdicaoEnquete: "<strong>Eita!</strong> Ocorreram erros durante a edição da enquete.",
    erroListaMinhasEnquetes: "<strong>Eita!</strong> Não conseguimos retornar suas enquetes. Favor tente novamente mais tarde.",
    erroBuscaEnquete: "<strong>Eita!</strong> Não conseguimos retornar a enquete. Favor tente novamente mais tarde.",

    //------------------------------------------------------------------------------
    // MENSAGENS DE ALERTA
    //------------------------------------------------------------------------------
    erroLogin: "<strong>Atenção:</strong> Usuário ou senha estão incorretos",
    dadosAcessosExistentes: "<strong>Atenção:</strong> O email já está sendo utilizado",

    //------------------------------------------------------------------------------
    // MENSAGENS DE SUCESSO
    //------------------------------------------------------------------------------
    sucessoLogin: "<strong>Bem vindo!</strong> Mate suas dúvidas a vontade.",
    sucessoLogout: "<strong>Deslogado com sucesso!</strong> Até a próxima.",
    contaRegistrada: "<strong> Parabéns!</strong> Sua conta foi registrada com sucesso. Você já pode começar a fazer perguntas",
    contaRegistradaSemLogin: "<strong> Parabéns!</strong> Sua conta foi registrada com sucesso. Digite seu login e senha para começar a perguntar",
    sucessoEdicaoEnquete: "<strong> Sucesso.</strong> A enquete foi alterada.",
    sucessoCadastroEnquete: "<strong> Muito bom!</strong> Agora somente espere suas dúvidas serem respondidas."
});