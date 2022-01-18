<?php
include_once 'includes/header.php';
?>

<?php

// iniciar sessão
session_start();

// conexao com bd;
require_once 'bd_conectar.php';

// Verificar login
if(!isset($_SESSION['logado'])):
	header('Location: index.php');
endif;

// dados usuário
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT * FROM usuario WHERE CodigoUsu = '$id_usuario'";
$resultado = mysqli_query($connect, $sql);
$dados = mysqli_fetch_assoc($resultado);

// dados receita
if ($_SESSION['post']):
	$id_receita = $_SESSION['id_receita'];
	$sql = "SELECT * FROM receita WHERE CodReceita = '$id_receita'";
	$resultado = mysqli_query($connect, $sql);
	$dados_receita =mysqli_fetch_assoc($resultado);
else:
	$id_receita = $_GET['id_receita'];
	$sql = "SELECT * FROM receita WHERE CodReceita = '$id_receita'";
	$resultado = mysqli_query($connect, $sql);
	$dados_receita = mysqli_fetch_assoc($resultado);
endif;
?>

<body>
	
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
		<div class="row container">
			<?php
			$servidor = $_SERVER['PHP_SELF'];
			
			$sql = "SELECT fk_Usuario_CodigoUsu FROM receita WHERE CodReceita = '$id_receita'";
			$resultado = mysqli_query($connect, $sql);
			$array = mysqli_fetch_assoc($resultado);
			$id_donodareceita = $array['fk_Usuario_CodigoUsu'];
			
			if($id_usuario == $id_donodareceita ):
				echo "<div class='fotonome'>
						<ul>
							<a href='editar.php?id_receita=$id_receita' class='btn-floating #f57f17 yellow darken-4'> <i class= 'material-icons'> create </i> </a>
							<a href='delete.php?id_receita=$id_receita' class='btn-floating red'> <i class= 'material-icons'> delete </i> </a>
						</ul>
					</div>";
			endif;
			
			?>
			<div id="contentreceita">
			  <div class="card s12">
				<div class="card-image fotonome">
				  <img class='responsive-img' src="arquivos/<?php echo $dados_receita['imagem']; ?>">
				  <h3><?php echo $dados_receita['NomeRec'];?></h3>
				</div>
				<div class='card-content'>
					<div class="divider"></div>
					<div class="section">
						<h5>Descrição:<br><?php echo $dados_receita['descricao']; ?></h5>
					</div>
				</div>
				<div class='card-content'>
					<div class="divider"></div>
					<div class="section">
						<h5>Ingredientes:<br><?php echo $dados_receita['ingredientes']; ?></h5>
					</div>
				</div>
				<div class='card-content'>
					<div class="divider"></div>
					<div class="section">
						<h5>Modo de preparo:<br><?php echo $dados_receita['Preparo']; ?></h5>
					</div>
				</div>
			  </div>
			</div>
		</div>
	</main>


<?php
include_once 'includes/footer.php';
?>