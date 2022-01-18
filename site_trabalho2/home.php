<?php
include_once 'includes/header.php';
?>

<?php

// conexao com bd;
require_once 'bd_conectar.php';

// iniciar sessão
session_start();

// resetando o metodo post
$_SESSION['post'] = false;

// Verificar login
if(!isset($_SESSION['logado'])):
	header('Location: index.php');
endif;

$id = $_SESSION['id_usuario'];
$sql = "SELECT * FROM usuario WHERE CodigoUsu = '$id'";
$resultado = mysqli_query($connect, $sql);
$dados = mysqli_fetch_assoc($resultado);
?>
<header>
	<nav class="#fbc02d yellow darken-2" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="home.php" class="brand-logo">ComeCome</a>
      <ul class="right hide-on-med-and-down">
        <li><a href="post.php" class="btn-floating #f57f17 yellow darken-4"> <i class= "material-icons"> add_circle </i> </a> </li>
		<li><a href="perfil.php?id_usuario=<?php $meuperfil = true; echo $id.'&meuperfil='.$meuperfil;?>" class="btn-floating #f57f17 yellow darken-4"> <i class= "material-icons"> account_circle </i> </a> </li>
		<li><a href="logout.php" class="btn-floating #f57f17 yellow darken-4"> <i class= "material-icons"> stop </i> </a> </li>
      </ul>
    </div>
    </nav>
</header>

<main>
	 <div id="divpesquisa" class="nav-wrapper container z-depth-1">
	  
	  <?php
		if(!empty($_GET['cozinheiros'])):
			$cozinheiros = $_GET['cozinheiros'];
		else:
			$cozinheiros = false;
		endif;
	  
	  
		if($cozinheiros):
			$servidor = $_SERVER['PHP_SELF'];
			echo "<form action='$servidor' method='GET'>
					<div class='input-field'>
					  <input name='pesquisa' type='search' required>
					  <label class='label-icon' for='search'><i class='material-icons'>search</i></label>
					  <i class='material-icons'>close</i>
					</div>
					<input name='cozinheiros' type='hidden' value='true'>
				  </form>";
		else:
			$servidor = $_SERVER['PHP_SELF'];
			echo "<form action='$servidor' method='GET'>
					<div class='input-field'>
					  <input name='pesquisa' type='search' required>
					  <label class='label-icon' for='search'><i class='material-icons'>search</i></label>
					  <i class='material-icons'>close</i>
					</div>
					<input name='cozinheiros' type='hidden' value=''>
				  </form>";
		endif;
	  ?>
    </div>
	
	<div class="row container">
	<?php
	
	if(empty($_GET['pesquisa'])):

		if($cozinheiros): //TELA DE COZINHEIROS
		
			echo 
			"<div id='alternarpaginas' class='container collection'>
				<a href='home.php?' class='collection-item'>RECEITAS</a>
				<a href='home.php?cozinheiros=true' class='collection-item active'>COZINHEIROS</a>
			</div>";
			
			$sql = "SELECT DISTINCT fk_Usuario_CodigoUsu FROM receita ORDER BY data DESC";
			$resultado = mysqli_query($connect, $sql);
			$ids = Array();
			while ($row = mysqli_fetch_assoc($resultado)):
				$ids[] = $row['fk_Usuario_CodigoUsu'];
			endwhile;
			
			$n = 0;
			while($n < count($ids)):
				$id_donodopost = $ids[$n];

				if (mysqli_num_rows($resultado) > 0):
					$sql= "SELECT * FROM usuario WHERE CodigoUsu = '$id_donodopost'";
					$resultado = mysqli_query($connect, $sql);
					$dados_donodopost = mysqli_fetch_assoc($resultado);
					
					// atribuindo valores para melhor escrita e entendimento
					
					//valores do usuario dono de posts:
				
					$foto_donodopost = $dados_donodopost['foto'];
					$nome_donodopost = $dados_donodopost['nome'];
					
					echo "<div class='cozinheiros'>
							<div name='perfil'>
								<td> <a href='perfil.php?id_usuario=$id_donodopost&meuperfil=$meuperfil'><img alt='Foto de Perfil' class='circle' height='120px' width='120px' src='fotosperfil/$foto_donodopost'> </a></td>
								<br> <td> $nome_donodopost </td>
							</div>
						</div>";
				endif;
				
				$n = $n + 1;
			endwhile;
			
		else: //TELA DE RECEITAS
		
			echo 
			"<div id='alternarpaginas' class='container collection'>
				<a href='http://localhost:8080/site_trabalho2/home.php?cozinheiros=false' class='collection-item active'>RECEITAS</a>
				<a href='http://localhost:8080/site_trabalho2/home.php?cozinheiros=true' class='collection-item'>COZINHEIROS</a>
			</div>";

			$sql = "SELECT * FROM receita ORDER BY data DESC";
			$resultado = mysqli_query($connect, $sql);
			$receitas = Array();
			while ($row = mysqli_fetch_assoc($resultado)):
				$receitas[] = $row['CodReceita'];
			endwhile;
			$n = 0;
			
			while($n < count($receitas)):
				$receita = $receitas[$n];
				
				// selecionando os dados da receita
				$sql = "SELECT * FROM receita WHERE CodReceita = '$receita'";
				$resultado = mysqli_query($connect, $sql);
				$dados_receita = mysqli_fetch_assoc($resultado);
				
				// selecionando os dados do criador do post receita
				$donodopost = $dados_receita['fk_Usuario_CodigoUsu'];
				$sql= "SELECT * FROM usuario WHERE CodigoUsu = '$donodopost'";
				$resultado = mysqli_query($connect, $sql);
				$dados_donodopost = mysqli_fetch_assoc($resultado);
				
				// atribuindo valores para melhor escrita e entendimento
				
				//valores do usuario dono do post:
				$id_donodopost = $dados_donodopost['CodigoUsu'];
				$foto_donodopost = $dados_donodopost['foto'];
				$nome_donodopost = $dados_donodopost['nome'];
				
				//valores da receita:
				$id_receita = $dados_receita['CodReceita'];
				$imagem = $dados_receita['imagem'];
				$nome_receita = $dados_receita['NomeRec'];
				$descricao =  $dados_receita['descricao'];
				
				//verificando se id_donodopost == id_usuario: (para colocar no link da foto de perfil)
				if($id_donodopost != $id):
					$meuperfil = false;
				else:
					$meuperfil = true;
				endif;
				
				
				//exibindo as seleções na página home
				echo "<div class='row container postagem'>
							<div name='perfil'>
								<table>
								<tr id='perfil'>
									<td> <a href='perfil.php?id_usuario=$id_donodopost&meuperfil=$meuperfil'><img alt='Foto de Perfil' class='circle' height='90px' width='90px' src='fotosperfil/$foto_donodopost'> </a></td>
									<td> $nome_donodopost </td>
								</tr>
								</table>
							</div>
							
							<div>
							  <div class='card medium' id='post'>
								<div class='card-image'>
								  <img class='responsive-img postshome' alt='$nome_receita' src='arquivos/$imagem'>
								  <span class='card-title'>$nome_receita</span>
								</div>
								<div class='card-content'>
								  <p>Descrição:<br>$descricao</p>
								</div>
								<div class='card-action'>
								  <a href='receita.php?id_receita=$id_receita'>CLIQUE PARA CONFERIR</a>
								</div>
							  </div>
							</div>
					  </div>";
				$n = $n + 1;
			endwhile;	
			
		endif;
	//////////// BARRA DE PESQUISA ///////////////
	else:
		
		if($cozinheiros):
			// pesquisa cozinheiros

			$pesquisa = $_GET['pesquisa'];
			
			$sql = "SELECT * FROM usuario WHERE nome LIKE '%".$pesquisa."%'";
			$resultado = mysqli_query($connect, $sql);
			while ($row = mysqli_fetch_assoc($resultado)):
				$usuarios[] = $row['CodigoUsu'];
			endwhile;
			
			$n = 0;
			if(mysqli_num_rows($resultado) > 0):
				while($n < count($usuarios)):
					
					$id_donodopost = $usuarios[$n];
					
					//verificando se id_donodopost == id_usuario: (para colocar no link da foto de perfil)
					if($id_donodopost != $id):
						$meuperfil = false;
					else:
						$meuperfil = true;
					endif;
					
					$sql = "SELECT * FROM usuario WHERE CodigoUsu = $id_donodopost";
					$resultado = mysqli_query($connect, $sql);
					$dados_usuarios = mysqli_fetch_assoc($resultado);
					
					$foto_donodopost = $dados_usuarios['foto'];
					$nome_donodopost = $dados_usuarios['nome'];
					
					echo "<div class='row container'>
							<div name='perfil' class='col s6 offset-s5'>
								<td> <a href='perfil.php?id_usuario=$id_donodopost&meuperfil=$meuperfil'><img alt='Foto de Perfil' class='circle' height='120px' width='120px' src='fotosperfil/$foto_donodopost'> </a></td>
								<td> $nome_donodopost </td>
							</div>
					  </div>";
					
					$n = $n + 1;
				endwhile;
			else:
				echo "Nenhum resultado para a pesquisa";
			endif;
			

		else:
		
			$pesquisa = $_GET['pesquisa'];
			$resultado = mysqli_query($connect,"SELECT CodReceita FROM receita WHERE NomeRec like '%".$pesquisa."%' ORDER BY data");
			while ($row = mysqli_fetch_assoc($resultado)):
				$receitas[] = $row['CodReceita'];	
			endwhile;
			$n = 0;
			
			if(mysqli_num_rows($resultado)>0):
				while($n < count($receitas)):
					$receita = $receitas[$n];

					// selecionando os dados da receita
					$sql = "SELECT * FROM receita WHERE CodReceita = '$receita'";
					$resultado = mysqli_query($connect, $sql);
					$dados_receita = mysqli_fetch_assoc($resultado);

					// selecionando os dados do criador do post receita
					$donodopost = $dados_receita['fk_Usuario_CodigoUsu'];
					$sql= "SELECT * FROM usuario WHERE CodigoUsu = '$donodopost'";
					$resultado = mysqli_query($connect, $sql);
					$dados_donodopost = mysqli_fetch_assoc($resultado);

					// atribuindo valores para melhor escrita e entendimento

					//valores do usuario dono do post:
					$id_donodopost = $dados_donodopost['CodigoUsu'];
					$foto_donodopost = $dados_donodopost['foto'];
					$nome_donodopost = $dados_donodopost['nome'];

					//valores da receita:
					$id_receita = $dados_receita['CodReceita'];
					$imagem = $dados_receita['imagem'];
					$nome_receita = $dados_receita['NomeRec'];
					$descricao =  $dados_receita['descricao'];

					//verificando se id_donodopost == id_usuario: (para colocar no link da foto de perfil)
					if($id_donodopost != $id):
						$meuperfil = false;
					else:
						$meuperfil = true;
					endif;


					//exibindo as seleções na página home
					echo "<div class='row container postagem'>
								<div name='perfil'>
									<table>
									<tr id='perfil'>
										<td> <a href='perfil.php?id_usuario=$id_donodopost&meuperfil=$meuperfil'><img alt='Foto de Perfil' class='circle' height='90px' width='90px' src='fotosperfil/$foto_donodopost'> </a></td>
										<td> $nome_donodopost </td>
									</tr>
									</table>
								</div>
								
								<div>
								  <div class='card medium' id='post'>
									<div class='card-image'>
									  <img class='responsive-img postshome' alt='$nome_receita' src='arquivos/$imagem'>
									  <span class='card-title'>$nome_receita</span>
									</div>
									<div class='card-content'>
									  <p>Descrição:<br>$descricao</p>
									</div>
									<div class='card-action'>
									  <a href='receita.php?id_receita=$id_receita'>CLIQUE PARA CONFERIR</a>
									</div>
								  </div>
								</div>
						  </div>";
					$n = $n + 1;

				endwhile;
			else:
				echo "Nenhum resultado para a pesquisa";
			endif;
					
		endif;
		
	endif;
?>
	</div>
</main>


<?php
include_once 'includes/footer.php';
?>