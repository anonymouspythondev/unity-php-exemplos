<?php
/**
 * BREVE EXPLICAÇÃO DO CÓDIGO
 * 
 * O CÓDIGO A SEGUIR, VERIFICA SE FOI "POSTADO" ALGO, SE FOI ENVIADO O HASH DA TRANSACAO
 * SE FOI ENVIADO, TENTA CONSULTAR O HASH NA UNITY BANK
 */
if ($_POST) {
    // DEFINE A VARIAVEL QUE VAI RECEBER A HASH COM BASE NO NAME DO INPUT USADO NO FORM
    $hash_unity = $_POST['hash_unity'];  

    // INICIA O REQUEST DO CURL
    $curl = curl_init();

    // DAS LINHAS 16 ATÉ 27 SERVER APENAS PARA EFETUAR O REQUEST USANDO CURL
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://services.maisbank.online/api/publica/consulta/transacao/$hash_unity",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Host: services.maisbank.online",
      ),
    ));
    
    // CAPTURA O RESULTADO DO CURL
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // ENCERRA O CURL PARA LIBERAR O SERVIDOR DO REQUEST
    curl_close($curl);

    // CONFIGURA UMA VARIAVEL PARA DEPOIS EXIBIR OU NÃO UMA RESPOSTA NO LAYOUT
    $var_sucesso = false;
    
    // VERIFICA SE HOUVE UM ERRO OU NÃO NO REQUEST
    if ($err) {
        // SE DER ALGUM ERRO, PODE EXIBIR UMA MENSAGEM OU TRATAR O ERRO AQUI DENTRO
        echo "Deu erro na validação da transação";
    } else {
        // SE ENCONTROU E OBTEVE UMA RESPOSTA COM SUCESSO, DEFINE AS VARIÁVEIS PARA USO
        $var_sucesso = true;

        // CONVERTE A RESPOSTA DO SERVIDOR DE JSON PARA ARRAY/OBJETO PHP
        $response = json_decode($response);

        // DEFINE AS VARIÁVEIS QUE VAI TER PARA TRABALHAR
        // O NÚMERO DAS CONTAS NÃO CONTEM O HÍFEN (-) OU SEJA, A CONTA 1234-123 RETORNARÁ DO SERVIDOR COMO 1234123
        $conta_origem_do_dinheiro = $response->wallet->user->numero_conta_maisbank;
        $conta_destino_do_dinheiro = $response->counter_part->user->numero_conta_maisbank;

        // POR PADRÃO O VALOR VEM NEGATIVO, USANDO *-1 CONVERTEMOS O VALOR PARA POSITIVO
        $valor_transferido = $response->value * -1;
        
        /**
         * RECOMENDAMOS FAZER ALGUMAS VALIDAÇÕES PARA GARANTIR QUE TUDO ESTÁ OK
         * 
         * A $conta_destino_do_dinheiro É A CONTA QUE VOCÊ ESPERA QUE SEJA?
         * O $valor_transferido É IGUAL AO VALOR QUE VOCÊ ESPERA QUE SEJA?
         * A $conta_origem_do_dinheiro É A MESMA CONTA DO USUÁRIO QUE DISSE QUE ENVIOU O DINHEIRO?
         * 
         * 
         * SE ALGUMA DAS VALIDAÇÕES ACIMA FOR NEGATIVA, RECOMENDAMOS QUE RECUSE O INFORME DE PAGAMENTO PARA 
         * EVITAR TENTATIVAS DE FRAUDES, INFELIZMENTE HÁ PESSOAS MAL INTENCIONADAS QUE VÃO TENTAR
         * 
         * OUTRO PONTO IMPORTANTE É QUE O VALOR VAI VIR DO SERVIDOR EM REAIS COM . (NO PADRÃO AMERICANO) NO TIPO FLOAT
         * 
         * EXEMPLOS DE VALORES
         * 1    = R$  1,00
         * 1.05 = R$  1,05
         * 1.5  = R$  1,50
         * 21   = R$ 21,00
         * 19.9 = R$ 19,90
         */
    }
}
?>

<!-- ESTE É APENAS UM FORMULÁRIO DEMONSTRATIVO DE COMO ENVIAR O HASH PRO SERVIDOR DA UNITY PARA VALIDAÇÃO -->
<form method="POST">
Hash da transação Unity: <input type="text" name="hash_unity" value="b61cb5b7-6262-4af6-bb58-41a803947edc"><br> <input type="submit" value="Validar">
</form>

<?php 
/**
 * SE FOI POSTADO ALGO E OBTEVE SUCESSO, EXIBE OS DADOS OBTIDOS ABAIXO, NÃO É IMPORTANTE ESTA PARTE, APENAS EXEMPLIFICA O USO
 */
if ($_POST && $var_sucesso == true) { ?>
A conta de origem é: <?=$conta_origem_do_dinheiro?> <br>
A conta de destino é: <?=$conta_destino_do_dinheiro?> <br>
O valor é: <?=$valor_transferido?> <br><br><br><br>
<?php } ?>
