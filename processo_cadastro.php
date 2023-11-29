<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "processo_cadastro";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para criar a tabela pessoas
function criarTabelaPessoas($conn) {
    $sql = "CREATE TABLE pessoas (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        idade INT(3) NOT NULL
    )";
    
    // Executar a query
    if ($conn->query($sql) === TRUE) {
        echo "Tabela 'pessoas' criada com sucesso<br>";
    } else {
        echo "Erro ao criar tabela 'pessoas': " . $conn->error . "<br>";
    }
}

// Função para criar a tabela enderecos
function criarTabelaEnderecos($conn) {
    $sql = "CREATE TABLE enderecos (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        rua VARCHAR(255) NOT NULL,
        cidade VARCHAR(100) NOT NULL,
        id_pessoa INT(6) UNSIGNED,
        FOREIGN KEY (id_pessoa) REFERENCES pessoas(id)
    )";
    
    // Executar a query
    if ($conn->query($sql) === TRUE) {
        echo "Tabela 'enderecos' criada com sucesso<br>";
    } else {
        echo "Erro ao criar tabela 'enderecos': " . $conn->error . "<br>";
    }
}

// Função para criar a tabela telefones
function criarTabelaTelefones($conn) {
    $sql = "CREATE TABLE telefones (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        numero VARCHAR(20) NOT NULL,
        tipo VARCHAR(50) NOT NULL,
        id_pessoa INT(6) UNSIGNED,
        FOREIGN KEY (id_pessoa) REFERENCES pessoas(id)
    )";
    
    // Executar a query
    if ($conn->query($sql) === TRUE) {
        echo "Tabela 'telefones' criada com sucesso<br>";
    } else {
        echo "Erro ao criar tabela 'telefones': " . $conn->error . "<br>";
    }
}

// Função para criar a tabela pedidos
function criarTabelaPedidos($conn) {
    $sql = "CREATE TABLE pedidos (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        descricao VARCHAR(255) NOT NULL,
        valor DECIMAL(10,2) NOT NULL,
        id_pessoa INT(6) UNSIGNED,
        FOREIGN KEY (id_pessoa) REFERENCES pessoas(id)
    )";
    
    // Executar a query
    if ($conn->query($sql) === TRUE) {
        echo "Tabela 'pedidos' criada com sucesso<br>";
    } else {
        echo "Erro ao criar tabela 'pedidos': " . $conn->error . "<br>";
    }
}

// Criar tabelas
criarTabelaPessoas($conn);
criarTabelaEnderecos($conn);
criarTabelaTelefones($conn);
criarTabelaPedidos($conn);

// Inserir dados complexos de exemplo
inserirDadosComplexos($conn);

// Obter detalhes de compras
getDetalhesCompras(1);

// Atualizar detalhes da pessoa
echo atualizarDetalhesPessoa(1, 'joao', 25, 'Rua Y, Cidade X', '9876543210') . "<br>";

// Excluir pessoa completa
echo excluirPessoaCompleta(1);

// Função para obter pessoas de uma cidade
function getPessoasCidade($cidade) {
    global $conn;
    $sql = "SELECT pessoas.nome, pessoas.idade, enderecos.rua, enderecos.cidade
            FROM pessoas
            LEFT JOIN enderecos ON pessoas.id = enderecos.id_pessoa
            WHERE enderecos.cidade = '$cidade'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $pessoas = [];
        while($row = $result->fetch_assoc()) {
            $pessoas[] = $row;
        }
        return $pessoas;
    } else {
        return "Nenhuma pessoa encontrada na cidade: $cidade";
    }
}

// Função para atualizar endereço de uma pessoa
function atualizarEnderecoPessoa($idPessoa, $novaRua, $novaCidade) {
    global $conn;

    // Atualizar endereço da pessoa
    $queryEndereco = "UPDATE enderecos SET rua = '$novaRua', cidade = '$novaCidade' WHERE id_pessoa = $idPessoa";
    if ($conn->query($queryEndereco) !== TRUE) {
        return "Erro ao atualizar endereço: " . $conn->error;
    }

    return "Endereço da pessoa atualizado com sucesso";
}



// Função para excluir telefone específico

function excluirTelefoneEspecifico($idTelefone) {
    global $conn;

    // Excluir telefone específico
    $conn->query("DELETE FROM telefones WHERE id = $idTelefone");

    return "Telefone excluído com sucesso";
}

// Fechar conexão
$conn->close();
?>
