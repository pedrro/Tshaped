<?php
    //Criar Sessão
    session_start();
    
    //require('../inc/inc.autoload.php');
    require('../inc/class.TemplatePower.php');
    require('../model/class.UsuariosDAOExt.php');
    require('../model/class.DbAdmin.php');
    require('../inc/inc.util.php');

    $tpl = new TemplatePower("../view/pesquisa.htm");

    $tpl->prepare();
    
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
    if( isset($_REQUEST['usr'])){
        $nomeUsr = $_REQUEST['usr'];

        $where = "nome like '$nomeUsr%'";
        
        list($countReg, $vet) = UsuariosDAOExt::select($where, null, 1);

        if ($countReg > 0) {
            $idUsr        = $vet[0]->getId();
            $nomeUsuario  = $vet[0]->getNome();
            //Chama função para listar qualificacoes
            listaQualif($idUsr, $tpl);
        }
        else {
            echo "ERRO no SQL = $vet[0] - $vet[1] - $vet[2]";
            exit();
        }
        
        $tpl->assignGlobal("nomeUsuario", $nomeUsuario);
    }

    
    $tpl->printToScreen(); 

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
        
        /*echo '<pre>';
        print_r($DadosQualifUsu);
        echo '</pre>';
        die("entrei".$countReg);*/
        
        $countReg = count($DadosQualifUsu);
  
        if($countReg > 0){

           foreach ($DadosQualifUsu as $i => $lstDadosQualifUsu) {     
  
               $objQualificacao  = $lstDadosQualifUsu->getQualificacao();
               $idQualificacao   = $objQualificacao->getId();
               $nomeQualificacao = $objQualificacao->getNome();
               //$tipoQualificacao = $objQualificacao->getTipo();
               
               $nivel_qualif     = $lstDadosQualifUsu->getNota();
               $cor_fundo_qualif = $lstDadosQualifUsu->getCor_fundo_qualif();
               $font_qualif      = $lstDadosQualifUsu->getFont_qualif();
               $sizeQualif       = $lstDadosQualifUsu->getFont_qualif();
               $tipoQualificacao = $lstDadosQualifUsu->getTipo();
               $corFontQualif    = $lstDadosQualifUsu->getCor_font_qualif();
               
               $objDePara = $lstDadosQualifUsu->getDePara();
               $sizeQualif = $objDePara->getPara();
               //echo '<b>';
               //print_r($objDePara);
               //echo '</b>';
               //$objDePara = $objDePara[0];
                if($tipoQualificacao == 'I'){
                    $tpl->newBlock("interesses");
                }
                else{
                    $tpl->newBlock("especialidades");
                }
                $tpl->assign("nivel_qualif", $sizeQualif);
                $tpl->assign("idQualificacao", $idQualificacao);
                $tpl->assign("nomeQualificacao", $nomeQualificacao);
                //  $tpl->assignGlobal("nivel_qualif", $sizeQualif);
               $tpl->assign("cor_fundo_qualif", $cor_fundo_qualif);
               $tpl->assign("font_qualif", $font_qualif);
               $tpl->assign("corFontQualif", $corFontQualif);

               $tpl->newBlock("linkFonts");
               $googleFont = "<link href='http://fonts.googleapis.com/css?family=$font_qualif' rel='stylesheet' type='text/css'>";
               $tpl->assign("linkGoogleFont", $googleFont);
           }   
           //die();
        }        
        
        //$tpl->newBlock("formulario");
}  
?>
