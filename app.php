<?php 

//classe dashboard

class Dashboard{

    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVdendas;
    public $cliente_ativo;


    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
        return $this;
    }
}

//conexao do banco de dados
class Conexao{

    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar(){
    //tentar
        try{
    //parametros de conexao
        
        $conexao = new PDO(
            "mysql:host=$this->host;dbname=$this->dbname",
            "$this->user",
            "$this->pass",

        );

        //utf 8 para padronizar os caracteres e evitar erros.
        $conexao->exec('set charset utf8');
        return $conexao;
            
    //em caso de erro.
        }catch(PDOException $e){
            echo '<p>'.$e->getMessege().'</p>';
        }
    }

}

//class (model)

class Bd{
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard){
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }


    public function getNumeroVendas(){
        
        $query = 'select
                    count(*) as numero_vendas
                from
                    tb_vendas
                where
                    data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        //o ->numero_vendas foi adicionado para exibir somente o valor do numero de vendas. 
        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }

    public function getTotalVendas(){
        
        $query = 'select
                    SUM(total) as total_vendas
                from
                    tb_vendas
                where
                    data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        //o ->numero_vendas foi adicionado para exibir somente o valor do numero de vendas. 
        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }

}

$dashboard = new Dashboard();
$conexao = new Conexao();
$bd = new Bd($conexao, $dashboard);

$competencia = explode('-', $_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

$dia_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

$dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
$dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dia_do_mes);

$dashboard-> __set('numeroVendas', $bd->getNumeroVendas());
$dashboard-> __set('totalVendas', $bd->getTotalVendas());

// utilizamos o json para criar um objeto literal, que pode ser acessado diretamente os seus atributos.
echo json_encode($dashboard);

//print_r($bd->getTotalVendas());

//print_r($_GET);
//print_r($competencia);
//print_r($mes);
//print_r($ano);
//print_r($ano.'/'.$mes.'/'.$dia_do_mes);


?>