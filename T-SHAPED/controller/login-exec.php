<?php
/*reescrever...*/
/*	if (isset($_POST['usu']) && !empty($_POST['usu'])
		&& isset($_POST['sen']) && !empty($_POST['sen']))
	{

		//pegar os parâmetros enviados por POST - ('or 1='1)
		$usu = addslashes($_POST['usu']);
		$sen = addslashes($_POST['sen']);
		//$sen = sha1($sen);

		//referência ao arquivo de "auto load"
		require_once('../inc/inc.autoload.php');

		//conectar com BD e consultar pra ver se tá valendo através do DAO
		$dao = new UsuariosDAOExt();

		//validação do usuário e senha
		$filtro = "username='$usu' and senha='$sen'";
		
		list($countReg, $vet) = UsuariosDAOExt::select($filtro);

		if ($countReg > 0) {
			//SEMPRE que for usar o vetor $_SESSION
			session_start();

			//pegar o nome do cidadão e colocar em uma variável "session"
			$idUsuarios  = $vet[0]->getIdusuarios();
			$nomeUsuario = $vet[0]->getNome();
			$username    = $vet[0]->getUsername();
			$idPerfis    = $vet[0]->getIdperfis();

			$_SESSION['idUsuarios']   = $idUsuarios;
			$_SESSION['nomeUsuario']  = $nomeUsuario;
			$_SESSION['username']     = $username;

			//usa o método que retorna o menu em um vetor tridimensional
			$menu = $dao->listarMenu($idUsuarios, $idPerfis);
			//echo '<pre>'; print_r($menu); echo '</pre>'; exit;
			$_SESSION['menu'] = $menu;*/

			//direcionar para o m?dulo correto conforme o tipo
			$destino = '../view/usuarios.htm';

			header('location: '.$destino);
			/*exit;
		}
		else {
			$msg = md5('login123');
			header('location: ../pagina/login.php?msg='.$msg);
			exit;
		}
	}
	else
	{
		header('location: ../pagina/login.php');
		exit;
	}*/

?>
