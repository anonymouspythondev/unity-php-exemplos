<?php
/**
 * BREVE EXPLICAÇÃO DO CÓDIGO
 * 
 * O CÓDIGO A SEGUIR, VERIFICA SE FOI "POSTADO" ALGO, SE FOI ENVIADO O HASH DA TRANSACAO
 * SE FOI ENVIADO, TENTA CONSULTAR O HASH NA UNITY BANK
 */
if ($_POST) {
    $hash_unity = $_POST['hash_unity'];

    $curl = curl_init();
    
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
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);

    $var_sucesso = false;
    
    if ($err) {
        // SE DER ALGUM ERRO, PODE EXIBIR UMA MENSAGEM OU TRATAR O ERRO AQUI DENTRO
        echo "Deu erro na validação da transação";
    } else {
        // SE ENCONTROU E OBTEVE UMA RESPOSTA COM SUCESSO, DEFINE AS VARIÁVEIS
        $var_sucesso = true;

        $response = json_decode($response);

        // DEFINE AS VARIÁVEIS QUE VAI TER PARA TRABALHAR
        //O NÚMERO DAS CONTAS NÃO CONTEM O HÍFEN (-) OU SEJA, A CONTA 1234-123 RETORNARÁ DO SERVIDOR COMO 1234123
        $conta_origem_do_dinheiro = $response->wallet->user->numero_conta_maisbank;
        $conta_destino_do_dinheiro = $response->counter_part->user->numero_conta_maisbank;
        $valor_transferido = $response->value * -1;
    }
}
?>

<!-- ESTE É APENAS UM FORMULÁRIO DEMONSTRATIVO DE COMO ENVIAR O HASH PRO SERVIDOR DA UNITY PARA VALIDAÇÃO -->
<form method="POST">
Hash da transação Unity: <input type="text" name="hash_unity" value="4e14933a-948c-474c-9e8b-919e541003be"><br> <input type="submit" value="Validar">
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
