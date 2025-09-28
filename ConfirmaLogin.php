<?php
// Inclui a conexão com o banco de dados
include "Bd.php";

// Inicia a sessão
session_start();

// 1. Pega os dados do formulário
$NomeUsuario = $_POST['Login'];
$SenhaDigitada = $_POST['Senha'];

// 2. Busca o utilizador pelo NOME
$sql = "SELECT * FROM usuarios WHERE nome = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $NomeUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

// 3. Verifica se o utilizador foi encontrado
if ($resultado->num_rows === 1) {
    
    $usuario = $resultado->fetch_assoc();
    
    // 4. VERIFICA SE A SENHA DIGITADA CORRESPONDE AO HASH DO BANCO
    if ($SenhaDigitada == $usuario['senha']) {
        
        // A senha está correta! O Login é um SUCESSO!
        // (A verificação do status_conta foi removida)
        
        // Guarda as informações na sessão
        $_SESSION['Nome'] = $usuario['nome'];
        $_SESSION['Senha'] = $usuario['senha'];
        $_SESSION['id'] = $usuario['id_usuario']; // Ajuste o nome da coluna do ID se for diferente

        // Redireciona para o painel principal
        header('Location: dashboard-mobile.html');
        exit();

    } else {
        // A senha está INCORRETA.
        header('Location: login.php?erro=1');
        exit();
    }

} else {
    // O nome de utilizador NÃO FOI ENCONTRADO.
    header('Location: login.php?erro=2');
    exit();
}

$stmt->close();
$con->close();
?>