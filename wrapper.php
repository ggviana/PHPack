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
    $nomes = $files['name'];
    $caminhos = $files['tmp_name'];
    $erros = $files['error'];
    
    foreach($erros as $indice=>$erro){
        if($indice['erro'] == 1) throw new exception("Arquivo $nomes[$indice] com erro!");
    }
    $arquivos = array();
    for($i = 0; $i<count($nomes); $i++){
        $arquivos[$nomes[$i]] = $caminhos[$i];
    }
    return $arquivos;
}

// Junta todas as classes
function concatenar($arquivos){
    $conteudo = '';
    foreach($arquivos as $nome=>$caminho){
        $conteudo .= file_get_contents($caminho);
    }
    file_put_contents("write.tmp",$conteudo);
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
    $arquivos = extrair($_FILES['arquivos']);
    print_r($_FILES['arquivos']);
    echo "=======================================\n";
    print_r($arquivos);
    echo "=======================================\n";
    $arquivos = concatenar($arquivos);
    echo $arquivos;
    echo "=======================================\n";
    echo "E a escrita foi: ";
    if(comprimir($arquivos, $nome_pacote)) echo "SUCESSO\n";
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
    <meta http_equiv="Content-Type" content="text/html" charset="UTF-8">
	<script type="text/javascript" language="Javascript">
        function adicionarEntrada(){
            var entrada = document.createElement("div");
            entrada.className="entrada";
			var conteudo = '<input id="file_input" type="file" name="arquivos[]" style="display:none;" onchange="renomear();">';
			conteudo += '<input id="nome_arquivo" type="text" onclick="abrirInput();" title="Arquivo de classe PHP" required>';
			conteudo += '<a class="botao" onclick="abrirInput();">Escolher</a>';
			conteudo += '<input id="remover_entrada" class="botao" type="button" value="x" onclick="removerEntrada(this);">';
            entrada.innerHTML = conteudo;
            document.getElementById("entradas").appendChild(entrada);
        }
        function removerEntrada(no){
            document.getElementById("entradas").removeChild(no.parentNode);
        }
		function renomear(){
			document.getElementById('nome_arquivo').value = document.getElementById('file_input').value;
		}
		function abrirInput(){
			document.getElementById('file_input').click();
		}
    </script>
    <style type="text/css">
        * {margin:0px;padding:0px;font:12px Verdana, Geneva;}

        input, .botao {
        	border: 0px none;border-radius: 0px 0px 0px 0px;outline: 0px none;background: none repeat scroll 0% 0% rgb(78, 104, 199);
        	box-shadow: 1px 0px 1px rgb(32, 56, 145), 0px 1px 1px rgb(56, 82, 177), 2px 1px 1px rgb(32, 56, 145), 1px 2px 1px rgb(56, 82, 177), 3px 2px 1px rgb(32, 56, 145), 2px 3px 1px rgb(56, 82, 177), 4px 3px 1px rgb(32, 56, 145), 3px 4px 1px rgb(56, 82, 177), 5px 4px 1px rgb(32, 56, 145), 4px 5px 1px rgb(56, 82, 177), 6px 5px 1px rgb(32, 56, 145);
        	color: white;
			white-space:nowrap;
			font-family: 'Gotham Rounded A','Gotham Rounded B',"proxima-nova-soft",sans-serif;
        	padding: 10px 5px;
			margin: 10px 3px;
			position:relative;
        }
        input:hover , .botao:hover {background: none repeat scroll 0% 0% rgb(61, 87, 180);}
		
		.botao{padding:10px 10px;min-width:30px;float:right;right:1%;}
        
        form{
            width:50%;
            margin:50px auto;
            background:rgba(200,200,200,0.9);
        }
		
        legend{font-size: 22px;}
        
		#entradas{border:5px ridge;}
		
        .entrada{background:rgba(200,200,200,0.8);margin:7px;padding:5px;border: 2px dotted #AAA;}
		.entrada:hover{background:rgba(230,230,230,0.8);}
        
		#rotulo{}
		
		#file_input{display:none;}
    </style>
</head>
<body>
    <form action="wrapper.php" method="post" enctype="multipart/form-data">
        <fieldset id="entradas">
            <legend>Classes para empacotar</legend>
            <label for="nome_pacote">Nome do pacote: 
                <input type="text" name="nome_pacote" required="required">
            </label>
            <input id="empacotar" class="botao" type="submit" name="envio" value="Pack">
            <input id="adicionar_entrada" class="botao" type="button" value="+" onclick="adicionarEntrada();">
            <div class="entrada">
				<input id="file_input" type="file" name="arquivos[]" style="display:none;" onchange="renomear();">
				<input id="nome_arquivo" type="text" onclick="abrirInput();" title="Arquivo de classe PHP" required>
				<a class="botao" onclick="abrirInput();">Escolher</a>
				<input id="remover_entrada" class="botao" type="button" value="x" onclick="removerEntrada(this);">
			</div>
        </fieldset>
    </form>
</body>
</html>