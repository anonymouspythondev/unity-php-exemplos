# ARQUIVOS EXEMPLO DE INTEGRAÇÃO COM UNITY

Existem várias formas de integrar com a Unity Bank, mas a mais simples é utilizando um formulário e um único request na API pública de consulta de transação.
Dê uma olhada no arquivo exemplo 01:

https://github.com/anonymouspythondev/unity-php-exemplos/blob/master/exemplo-01-form-simples.php

**O fluxo basicamente é o seguinte**:
1. Exibe um form simples para informar a hash da transação e efetuar um `POST` para o mesmo arquivo do form
2. SE a requisição for um `POST` envia o hash recebido para o servidor da Unity Bank e consulta a transação
3. SE deu certo, executa qualquer ação que importe para a sua integração ou SE deu errado, exibe um erro

Pronto, só isso, é extremamente simples integrar assim.
