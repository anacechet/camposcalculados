<!DOCTYPE html>
<?php 
   include_once "conf/default.inc.php";
   require_once "conf/Conexao.php";
   $title = "Carros";
   $procurar = isset($_POST["procurar"]) ? $_POST["procurar"] : ""; 
   $consulta = isset($_POST["consulta"]) ? $_POST["consulta"] : 1;
?>
<html>
<head>
    <meta charset="UTF-8">
    <title> <?php echo $title; ?> </title>
    <link rel="stylesheet" href= "css/css.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>

<div class="margem">
<?php include "menu.php"; ?>
    <form method="post">
    <fieldset>
       
        <legend>Procurar Carros</legend><br>

        <input type="text"   name="procurar" id="procurar" size="37" value="<?php echo $procurar;?>"><br><br>

        <input type="radio" name="consulta" value="1" <?php if($consulta==1) echo "checked";?>>Por Modelo<br>
        <input type="radio" name="consulta" value="2" <?php if($consulta==2) echo "checked";?>>Por Preço<br>
        <input type="radio" name="consulta" value="3" <?php if($consulta==3) echo "checked";?>>Quilometragem<br><br>

        <input type="submit" name="acao" id="acao">

    <br><br>    
    <table class="table table-hover">

    <tr>
        <td><b>Código</b></td>
        <td><b>Modelo</b></td> 
        <td><b>Preço Antigo</b></td>
        <td><b>Quilometragem</b></td>
        <td><b>Data de Fabricação</b></td>
        <td><b>Anos de Uso</b></td>
        <td><b>Média Km por Ano</b></td>
        <td><b>Preço com Ajuste</b></td>
    </tr>

    <?php
    
    $pdo = Conexao::getInstance(); 
    if
    ($consulta == 1) 
    $procura = $pdo->query("SELECT * FROM carro 
                            WHERE nome LIKE '$procurar%' 
                            ORDER BY nome");   
    elseif
    ($consulta == 2) 
    $procura = $pdo->query("SELECT * FROM carro 
                            WHERE valor<=  '$procurar%' 
                            ORDER BY valor");
    else 
    $procura = $pdo->query("SELECT * FROM carro 
                            WHERE km<=  '$procurar%' 
                            ORDER BY km");       
    
    while 
    ($linha = $procura->fetch(PDO::FETCH_ASSOC)){    
        
    $ano = date("Y");
    $fabricacao = date("Y", strtotime($linha['dataFabricacao']));
    $anosdeuso = $ano - $fabricacao;
    $mediakm = $linha['km'] / $anosdeuso; 
    $novovalor = 0;
    $cor = "black";

    if($linha['km'] >= 100000 && $anosdeuso <10){
       $desconto = $linha['valor'] * 0.10;
       $novovalor = $linha['valor'] - $desconto;
       $cor = "red";}

    elseif($linha['km'] >= 100000 && $anosdeuso >= 10){
           $desconto = $linha['valor'] * 0.20;
           $novovalor = $linha['valor'] - $desconto;
           $cor = "red";}

    elseif($linha['km'] < 100000 && $anosdeuso >= 10){
           $desconto = $linha['valor'] * 0.10;
           $novovalor = $linha['valor'] - $desconto; 
           $cor = "red";}
    
    else {$novovalor = $linha['valor'];}?>

    <tr>
        <td><?php echo $linha['id'];?></td>
        <td><?php echo $linha['nome'];?></td>   
        <td><?php echo number_format($linha['valor'], 1, ',', '.');?></td>
        <td><?php echo number_format($linha['km'], 1, ',', '.');?></td>  
        <td><?php echo date("d/m/Y",strtotime($linha['dataFabricacao']));?></td>  
        <td><?php echo $anosdeuso;?></td> 
        <td><?php echo number_format($mediakm, 2, ',', '.')?></td> 
        <td style = "color: <?php  echo  $cor ; ?>"><?php echo number_format($novovalor, 1, ',', '.')?></td>
    </tr>
    <?php  } ?>
           
    </table>
    </fieldset>
    </form>
</div>
</body>
</html>