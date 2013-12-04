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

    $tpl->assign("nomeUsuario", $nomeUsuario); 
    /******************************************************
     * Listar Qualificações
    ******************************************************/    
    if( isset($_REQUEST['frmPesquisaQualif'])){
        $nomeQualif = $_REQUEST['nomeQualif'];
        
        $vet = UsuariosDAOExt::getUsuariosComQualif($nomeQualif);
        $countReg = count($vet);

        foreach ($vet as $i => $obj) {
          $idUsr      = $obj[0];
          $nomeUsr    = $obj[1];
          $nomeQualif = $obj[2];
          $notaQualif = $obj[3];

          $tpl->newBlock("number_row");
          $tpl->assign("qlf_id", $idUsr); 
          $tpl->assign("qlf_nome", $nomeUsr); 
          $tpl->assign("qlf_qualif", $nomeQualif); 
          $tpl->assign("qlf_nivel", $notaQualif); 
        }
    }
    

    if( isset($_REQUEST['frmPesquisaUsr'])){
        $nomeUsr = $_REQUEST['usrNome'];
        $sobrenomeUsr = $_REQUEST['usrSobrenome'];

        listaUsr($tpl, $nomeUsr, $sobrenomeUsr);
    }
    

    /******************************************************
     * FUNÇÕES
    ******************************************************/       
    function listaUsr($tpl, $usrNome, $usrSobrenome) {

    if( !empty($usrNome) ) {
      $where = "nome like '$usrNome%'";  
    } 

    if( !empty($usrSobrenome) ) {
      if( !empty($usrNome) )
        $where .= ' and ';
      $where .= "sobrenome like '$usrSobrenome%'";  
    }

     list($countReg, $vet) = UsuariosDAOExt::select($where);

      if ($countReg > 0) {
        foreach ($vet as $i => $usr) {
            $idUsr   = $usr->getId();
            $nomeUsr = $usr->getNome() . ' ' . $usr->getSobrenome();
            $emailUsr = $usr->getEmail();

            $tpl->newBlock("user_row");
            $tpl->assign("usr_id", $idUsr); 
            $tpl->assign("usr_nome", $nomeUsr); 
            $tpl->assign("usr_email", $emailUsr); 
          }
        }
    }

    $tpl->printToScreen(); 
?>
