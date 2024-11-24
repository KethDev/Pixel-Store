<?php
// Exibe erros para depuração (apagar em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Conectar ao banco de dados MySQL
$host = 'localhost';
$dbname = 'cadastro_usuario';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão realizada com sucesso!<br>";
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// 2. Capturar os dados do formulário
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$sobrenome = filter_input(INPUT_POST, 'sobrenome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$rua = filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$numero = filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$complemento = filter_input(INPUT_POST, 'complemento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$bairro = filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cidade = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$termos_aceitos = isset($_POST['termos_aceitos']) ? 1 : 0; // Checkbox retorna 1 se marcado

// 3. Criptografar a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// 4. Inserir os dados no banco de dados
$sql = "INSERT INTO usuarios (email, nome, sobrenome, cep, rua, numero, complemento, bairro, cidade, estado, senha, termos_aceitos) 
        VALUES (:email, :nome, :sobrenome, :cep, :rua, :numero, :complemento, :bairro, :cidade, :estado, :senha, :termos_aceitos)";
$stmt = $conn->prepare($sql);

$stmt->bindParam(':email', $email);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':sobrenome', $sobrenome);
$stmt->bindParam(':cep', $cep);
$stmt->bindParam(':rua', $rua);
$stmt->bindParam(':numero', $numero);
$stmt->bindParam(':complemento', $complemento);
$stmt->bindParam(':bairro', $bairro);
$stmt->bindParam(':cidade', $cidade);
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':senha', $senhaHash); // Salva a senha criptografada
$stmt->bindParam(':termos_aceitos', $termos_aceitos, PDO::PARAM_INT);

try {
    if ($stmt->execute()) {
        echo "Usuário registrado com sucesso!";
    } else {
        echo "Erro ao registrar o usuário.";
    }
} catch (PDOException $e) {
    echo "Erro ao registrar: " . $e->getMessage();
}
?>