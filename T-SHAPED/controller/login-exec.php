<?php

    require('../inc/class.TemplatePower.php');
    require('../model/Class.UsuariosDAOExt.php');
    require('../model/class.DbAdmin.php');

    $tpl = new TemplatePower("../view/_master.htm");

    $tpl->prepare();    
                    
    if (isset($_POST['usuario']) && !empty($_POST['usuario'])
            && isset($_POST['senha']) && !empty($_POST['senha']))
    {

            //pegar os parâmetros enviados por POST - ('or 1='1)
            $usu = $_POST['usuario'];
            $sen = md5($_POST['senha']);

            //conectar com BD e consultar pra ver se tá valendo através do DAO
            $DadosLogin = new usuariosDAOExt();

            //validação do usuário e senha
            $filtro = "email='$usu' and senha='$sen'";

            list($countReg, $vet) = UsuariosDAOExt::select($filtro);

            if ($countReg > 0) {

                //SEMPRE que for usar o vetor $_SESSION
                session_start();

                //pegar o nome do cidadão e colocar em uma variável "session"
                $idUsuario    = $vet[0]->getId();
                $email        = $vet[0]->getEmail();
                $nome         = $vet[0]->getNome();
                $foto         = $vet[0]->getFoto();

                $_SESSION['idUsuario']    = $idUsuario;
                $_SESSION['emailUsuario'] = $email;
                $_SESSION['nomeUsuario']  = $nome;
                $_SESSION['fotoUsuario']  = $foto;
				
                //direcionar para o m?dulo correto conforme o tipo
                //$destino = '../controller/qualificacoes-exec.php?op=Listar';
                $destino = '../controller/usuarios-exec.php?op=Listar';
				//$destino = '../view/usuarios.htm';
                
                header('location: '. $destino);
                exit;

            }
            else {

                    $msg = 'Usuário ou Senha Inválido.';
                    $tpl->assign("loginInvalido", $msg);                
            }
    }
    else
    {
            session_start();
			unset($_SESSION);
			session_destroy();
			header('location: ../controller/exec.php');
            exit;
    }

    $tpl->printToScreen(); 
?>
