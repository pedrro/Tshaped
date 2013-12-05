<?php
    //Criar Sessão
    session_start();
    
    //require('../inc/inc.autoload.php');
    require('../inc/class.TemplatePower.php');
    require('../model/class.UsuariosDAOExt.php');
    require('../model/class.DbAdmin.php');
    require('../inc/inc.util.php');

    $tpl = new TemplatePower("../view/usuarios.htm");
    $tpl->prepare();

    $tpl2 = new TemplatePower("../view/_master.htm");
    $tpl2->prepare();
    
    //Pega dados da Sessão
    $nomeUsuario  = $_SESSION['nomeUsuario'];
    $idUsuario    = $_SESSION['idUsuario'];
    $emailUsuario = $_SESSION['emailUsuario'];
//die($idUsuario);
//print_r($_SESSION);
//die();
    /******************************************************
     * Listar Qualificações
    ******************************************************/    
    if( isset($_REQUEST['op']) && ($_REQUEST['op'] == 'Listar') ){

        //Chama função para listar qualificacoes
        listaQualif($idUsuario, $tpl);
        
        // Verifica sessao ativa
        if (empty($_SESSION['nomeUsuario']))
        {
                header('location: ../controller/exec.php');
                exit;
        };

        $tpl->assignGlobal("nomeUsuario", $nomeUsuario);
    }

    /******************************************************
     * Insert de Usuários
    ******************************************************/
    if( isset($_GET['op']) && ($_GET['op'] == 'Inserir') ){
    
        $Dados = new usuariosDAOExt();

        //Pega informações do formulário enviadas por POST
        $nome           = $_POST['nome'];
        $sobreNome      = $_POST['sobrenome'];
        $email          = $_POST['email'];
        $confirmaEmail  = $_POST["conf_email"];
        $senha          = $_POST['password'];
        $dtNasc         = $_POST['dt_nascimento'];
        $sexo           = $_POST['sexoRadios'];

        //Faz validações
        if( empty($nome) ) {
            $msg = 'Informe o seu nome.';
        }

        if( empty($sobreNome) ) {
            $msg = 'Insira seu sobrenome';
        }

        if( empty($email) ){
            $msg = 'Informe seu e-mail.';
        }
        else{
            if($email != $confirmaEmail){
                $msg = 'e-mail invalido';
            }
        }

        $SenhaLen = strlen($senha);
        if (($SenhaLen < 6) || ($SenhaLen > 12)) {
            $msg = 'A senha deve ter entre 6 e 12 caracteres';
        }

        if( empty ($dtNasc)) {
            $msg = 'Insira data de nascimento';
        }
        
        if( empty($msg) ){
            
            //Seta os valores no objeto
            $Dados->setNome($nome);
            $Dados->setSobrenome($sobreNome);
            $Dados->setEmail($email);
            $Dados->setSenha(md5($senha));    
            $Dados->setDt_nasc(formatISO($dtNasc,'d/m/Y', "date") );
            $Dados->setSexo($sexo);

            //Insere registros
            list($codErro, $msgErro, $Dados) = UsuariosDAOExt::insert($Dados);
            
            if( isset($codErro) && ($codErro == '0') ){
                
      				// Setar os atributos do usuario na sessao
      				$_SESSION['idUsuario']    = $Dados->getId();
                      $_SESSION['emailUsuario'] = $email;
                      $_SESSION['nomeUsuario']  = $nome;
                      $_SESSION['fotoUsuario']  = $foto;
      				
      				//header('location: ../view/usuarios.htm');
      				header('location: ../controller/usuarios-exec.php?op=Listar');
      				exit;
            }
            else{
                $msg = 'Erro ao Inserir: '.$msgErro;
                
                $tpl2->newBlock("mensagem");
                $tpl2->assign("msg", $msg);                
            }
        
        }
        else{
            $tpl2->assign("nomeCadastro", $nome);
            $tpl2->assign("sobrenomeCadastro", $sobreNome);
            $tpl2->assign("emailCadastro", $email);
            $tpl2->assign("confEmailCad", $confirmaEmail);
            $tpl2->assign("senhaCadastro", $senha);
            $tpl2->assign("dtNascimento", $dtNasc);
            
            $tpl2->newBlock("mensagem");
            $tpl2->assign("msg", $msg);
            
        }
    }
    /******************************************************
     * Edição de Usuários
    ******************************************************/
    
    /******************************************************
     * Exclusão de Usuários
    ******************************************************/
    if( empty($msg) )
      $tpl->printToScreen();
    else
      $tpl2->printToScreen();

    /******************************************************
     * FUNÇÕES
    ******************************************************/       
    function listaQualif($idUsuario, $tpl){

        $filtro1 = ' id = '.$idUsuario;
        
        //Busca dados de usuarios
        list($countReg, $DadosUsuario) = usuariosDAOExt::select($filtro1);

        if($countReg > 0){
           foreach ($DadosUsuario as $i => $lstUsuario) {     
               $objUsuario = $lstUsuario;
           }   
           
           $cor_fundo_t = $objUsuario->getCor_fundo_t();

           $tpl->assignGlobal("cor_fundo_t", $cor_fundo_t);
           
        }
        
        $DadosQualifUsu = $objUsuario->getQualificacoes();

        $countReg = count($DadosQualifUsu);
  
        if($countReg > 0){

           foreach ($DadosQualifUsu as $i => $lstDadosQualifUsu) {     
  
               $objQualificacao  = $lstDadosQualifUsu->getQualificacao();
               $idQualificacao   = $objQualificacao->getId();
               $nomeQualificacao = $objQualificacao->getNome();
               //$tipoQualificacao = $objQualificacao->getTipo();
               
               $nivel_qualif       = $lstDadosQualifUsu->getNota();
               $cor_fundo_qualif   = $lstDadosQualifUsu->getCor_fundo_qualif();
               $cor_font_qualif    = $lstDadosQualifUsu->getCor_font_qualif();
               $font_qualif        = $lstDadosQualifUsu->getFont_qualif();
               $sizeQualif         = $lstDadosQualifUsu->getFont_qualif();
               $tipoQualificacao   = $lstDadosQualifUsu->getTipo();
               $imagemQualificacao = $lstDadosQualifUsu->getUrl_imagem();
               $posX               = $lstDadosQualifUsu->getPos_x();
               $posY               = $lstDadosQualifUsu->getPos_y();
              
               $objDePara = $lstDadosQualifUsu->getDePara();
               $sizeQualif = $objDePara->getPara();
               
               if( !empty($imagemQualificacao) ){

                    $lstDadosDePara = new de_paraDAOExt();
                    
                    //Buscar no Banco o para conforme a nota                    
                    list($countReg, $lstDadosDePara) = de_paraDAOExt::select("descricao = 'IMG_POSXY' and de = ".$nivel_qualif);        

                    //Se achou
                    if($countReg > 0){
                        $imgWidth  = $lstDadosDePara[0]->getPara();               
                        $imgHeight = $lstDadosDePara[1]->getPara();  
                    }else{
                        $imgWidth  = null;               
                        $imgHeight = null;  
                    }
               }           
               
               /*echo '<b>';
               print_r($objDePara);
               echo '</b>';
               die();*/
               //$objDePara = $objDePara[0];
                if($tipoQualificacao == 'I'){
                    $tpl->newBlock("interesses");
                }
                else{
                    $tpl->newBlock("especialidades");
                }
                $tpl->assign("nivel_qualif", $sizeQualif);
                $tpl->assign("top", $posY);
                $tpl->assign("left", $posX);
                $tpl->assign("idQualificacao", $idQualificacao);
                $tpl->assign("idQualificacaoUsuario", $lstDadosQualifUsu->getId());
               //die("==>".$nomeQualificacao. "  ==> ".$imagemQualificacao);
                if(!empty($imagemQualificacao)){
                    //$tpl->assign("nomeQualificacao", "<img src='".$imagemQualificacao."'/>");
                    $tpl->assign("nomeQualificacao", sprintf("<img style='width: %spx; height: %spx;' src='%s'/>", $imgWidth, $imgHeight, $imagemQualificacao));
                }
                else{
                    $tpl->assign("nomeQualificacao", $nomeQualificacao);
                }
                //$tpl->assign("nivel_qualif", $objDePara->getPara());

               $tpl->assign("cor_fundo_qualif", $cor_fundo_qualif);
               $tpl->assign("cor_font_qualif", $cor_font_qualif);
               $tpl->assign("font_qualif", $font_qualif);

               $tpl->newBlock("linkFonts");
               $googleFont = "<link href='http://fonts.googleapis.com/css?family=$font_qualif' rel='stylesheet' type='text/css'>";
               $tpl->assign("linkGoogleFont", $googleFont);
           }   
           //die();
        }        
        
        //$tpl->newBlock("formulario");
}  
?>
