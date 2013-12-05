<?php
    //Criar Sessão
    session_start();
    
    //require('../inc/inc.autoload.php');
    require('../inc/class.TemplatePower.php');
    require('../model/class.UsuariosDAOExt.php');
    require('../model/class.DbAdmin.php');
    require('../inc/inc.util.php');

    $tpl = new TemplatePower("../view/mostraT.htm");

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
    if( isset($_REQUEST['usrId'])){
        $idUsr = $_REQUEST['usrId'];

        $where = "id = '$idUsr'";
        
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
}  
?>
