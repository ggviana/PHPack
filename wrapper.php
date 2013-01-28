<?php
################################################################
#    PHPack                                                    #
#    Um script para comprimir suas classes PHP em um           #
#    pacote gzip e tornar sua aplicação mais portátil.         #
#                                                              #
#    Autor: Guilherme Guimarães Viana<ggviana@hotmail.com.br>  #
#    Licensa: GPLv3                                            #
################################################################

// Extrai os arquivos do formulario
function extrair($files){
	//verifica se algum arquivo foi interrompido na transmição
    foreach($files['error'] as $indice=>$erro){
        if($indice['erro'] == 1) throw new exception("Arquivo $nomes[$indice] com erro!");
    }
    //concatena o conteúdo
	foreach($files['tmp_name'] as $arquivo){
		$conteudo .= file_get_contents($arquivo);
	}
	return $conteudo;
}

// Comprime as classes
function comprimir($conteudo, $nome_final){
    $arquivo = gzopen("./{$nome_final}",'wb9');
    //$int = gzwrite($arquivo,file_get_contents("./write.tmp"));
    $int = gzwrite($arquivo,$conteudo);
    gzclose($arquivo);
    return $int;
}

function validar(){
    
}

if(isset($_POST['envio']) && isset($_POST['nome_pacote'])){
    echo '<pre><p>';
    $nome_pacote = $_POST['nome_pacote'];
    $conteudo = extrair($_FILES['arquivos']);
    print_r($_FILES['arquivos']);
    echo "=======================================\n";
    print_r($conteudo);
    echo "=======================================\n";
    echo "E a escrita foi: ";
    if(comprimir($conteudo, $nome_pacote)) echo "SUCESSO\n";
    else echo "FALHA\n";
    echo "=======================================\n";
    echo "E a leitura foi: ";
    $arquivos = file_get_contents("{$nome_pacote}");
    if($arquivos) echo "SUCESSO\n";
    else echo "FALHA\n";
    echo '</p></pre>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Package Wrapper</title>
	<meta charset="utf-8">
	<script type="text/javascript">
        function adicionarEntrada(){
            var entrada = document.createElement("div");
			var conteudo = '<input type="text" onclick="abrirInput(this);" title="Arquivo de classe PHP" required="required" id="nome_arquivo">';
			conteudo += '<input type="button" onclick="abrirInput(this);" value="Escolher">';
			conteudo += '<input type="file" name="arquivos[]" onchange="renomearEntrada(this);" id="file_input">';
			conteudo += '<input type="button" value="x" onclick="removerEntrada(this);">';
            entrada.innerHTML = conteudo;
            document.forms[0].appendChild(entrada);
        }
        function removerEntrada(no){
            document.forms[0].removeChild(no.parentNode);
        }
		function renomearEntrada(no){
			var pai = no.parentNode;
			pai.firstChild.value = pai.childNodes[2].value;
		}
		function abrirInput(no){
			document.getElementById('file_input').click();
		}
    </script>
	<style type="text/css">
		*, *:after, *:before {
		  -moz-box-sizing:border-box;
		  box-sizing:border-box;
		}
		*{
			font:"Lucida Sans Unicode", "Lucida Grande", sans-serif 15px;
			margin:0px;
			padding:0px;
		}
		form{
			background:rgb(180,190,240);
			border:1px solid;
			border-radius:10px;
			box-shadow:1px 1px 3px rgb(0, 0, 0);
			font-size:0px;
			padding:20px;
			margin:30px auto;
			width:400px;
		}
		form div{
			width:100%;
		}
		form div input{
			padding:10px;
			margin:5px 0px;
			font-size:14px;
			border:none;
			background:#FFF;
			box-shadow:1px 1px 3px rgb(0, 0, 0);
		}
		form div input:focus, form div input:hover{
			background:rgb(210,210,210);
		}
		form div :first-child{
			width:57%;
			padding-left:20px;
			border-top-left-radius:30px;
			border-bottom-left-radius:30px;
		}
		form div :nth-child(2){
			width:30%
		}
		form div :last-child{
			width:13%;
			border-top-right-radius:30px;
			border-bottom-right-radius:30px;
		}
		form div input[type=file]{
			display:none;
		}
		label.logo{
			width:100px;
			height:45px;
			text-align:center;
			line-height:1.8;
			color:#000;
			border:2px solid;
			border-radius:50%;
			box-shadow:1px 1px 3px rgb(0, 0, 0);
			margin-bottom:5px;
			display:block;
			font-family:Tahoma, Geneva, sans-serif;
			font-size:20px;
			font-style:italic;
		}
		label.logo:hover{
			content:"by ggviana";
			background:white;
			line-height:2.5;
			font-size:16px;
		}
	</style>
</head>
<body>
    <form action="wrapper.php" method="post" enctype="multipart/form-data">
		<label class="logo"><b>phpack</b></label>
		<div>
			<input type="text" name="nome_pacote" required="required">
			<input type="submit" name="envio" value="Empacotar">
			<input type="button" value="+" onclick="adicionarEntrada();">
		</div>
		<div>
			<input type="text" onclick="abrirInput(this)" title="Arquivo de classe PHP" required="required" id="nome_arquivo">
			<input type="button" onclick="abrirInput(this)" value="Escolher">
			<input type="file" name="arquivos[]" onchange="renomearEntrada(this);" id="file_input">
			<input type="button" value="x" onclick="removerEntrada(this);">
		</div>
    </form>
</body>
</html>