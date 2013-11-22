<?php/* * Classe que representa a tabela "usuarios" da base "T-shaped"  * e os comandos de acesso aos dados */class usuariosDAO{    //atributos (variáveis) relacionadas as colunas da tabela    protected $id;    protected $email;    protected $nome;    protected $sobrenome;    protected $senha;    protected $dt_nasc;    protected $sexo;    protected $dt_cadastro;    protected $foto;    protected $cor_fundo_t;    //método construtor que já faz a conexão com o BD    public function usuariosDAO() {}    //métodos para obter e ajustar dados das variáveis (get e set)    // -- id    public function setId($id) {            $this->id = $id;    }    public function getId() {            return $this->id;    }    // -- email    public function setEmail($email) {            $this->email = $email;    }    public function getEmail() {            return $this->email;    }    // -- nome    public function setNome($nome) {            $this->nome = $nome;    }    public function getNome() {            return $this->nome;    }    // -- sobrenome    public function setSobrenome($sobrenome) {            $this->sobrenome = $sobrenome;    }    public function getSobrenome() {            return $this->sobrenome;    }    // -- senha    public function setSenha($senha) {            $this->senha = $senha;    }    public function getSenha() {            return $this->senha;    }    // -- dt_nasc    public function setDt_nasc($dt_nasc) {            $this->dt_nasc = $dt_nasc;    }    public function getDt_nasc() {            return $this->dt_nasc;    }    // -- sexo    public function setSexo($sexo) {            $this->sexo = $sexo;    }    public function getSexo() {            return $this->sexo;    }    // -- dt_cadastro    public function setDt_cadastro($dt_cadastro) {            $this->dt_cadastro = $dt_cadastro;    }    public function getDt_cadastro() {            return $this->dt_cadastro;    }    // -- foto    public function setFoto($foto) {            $this->foto = $foto;    }    public function getFoto() {            return $this->foto;    }    // -- cor_fundo_t    public function setCor_fundo_t($cor_fundo_t) {            $this->cor_fundo_t = $cor_fundo_t;    }    public function getCor_fundo_t() {            return $this->cor_fundo_t;    }    //método que faz a inserção de Usuarios no BD    public static function insert($obj) {        //pegar os dados do objeto        $id                             = $obj->getId();        $email                          = $obj->getEmail();        $nome                           = $obj->getNome();        $sobrenome                      = $obj->getSobrenome();        $senha                          = $obj->getSenha();        $dt_nasc                        = $obj->getDt_nasc();        $sexo                           = $obj->getSexo();        $dt_cadastro                    = $obj->getDt_cadastro();        $foto                           = $obj->getFoto();        $cor_fundo_t                    = $obj->getCor_fundo_t();        if (empty($dt_cadastro))                $dt_cadastro = "now()";        if (empty($dt_atualizacao))                $dt_atualizacao = "now()";        if (empty($cor_fundo_t))                $cor_fundo_t = "#CCCCCC";//#C6CCB3";        //montar o comando SQL        $sql = "insert into Usuarios                       (id                       ,email                       ,nome                       ,sobrenome                       ,senha                       ,dt_nasc                       ,sexo                       ,dt_cadastro                       ,foto                       ,cor_fundo_t)                values                       ('$id'                       ,'$email'                       ,'$nome'                       ,'$sobrenome'                       ,'$senha'                       ,'$dt_nasc'                       ,'$sexo'                       ,'$dt_cadastro'                       ,'$foto'                       ,'$cor_fundo_t')";//die($sql);        $sql = str_replace("''", "null", $sql);        $sql = str_replace("'now()'", "now()", $sql);        // abre a conexão com o BD        $dba = new DbAdmin();        $dba->connectDefault();        //executar o comando SQL        $res = $dba->query($sql);        $retorno = Array();        if (!$res) {                $retorno[0] = mysql_errno();                $retorno[1] = mysql_error();                $retorno[2]  = "";        }        else {            $id = mysql_insert_id();            $sql = "select * from Usuarios where id = $id";            $res = $dba->query($sql);            $num = $dba->rows($res);            if (!$res) {                    $retorno[0] = mysql_errno();                    $retorno[1] = mysql_error();                    $retorno[2] = "";            }            else {                    $id                             = $dba->result($res, 0, "id");                    $email                          = $dba->result($res, 0, "email");                    $nome                           = $dba->result($res, 0, "nome");                    $sobrenome                      = $dba->result($res, 0, "sobrenome");                    $senha                          = $dba->result($res, 0, "senha");                    $dt_nasc                        = $dba->result($res, 0, "dt_nasc");                    $sexo                           = $dba->result($res, 0, "sexo");                    $dt_cadastro                    = $dba->result($res, 0, "dt_cadastro");                    $foto                           = $dba->result($res, 0, "foto");                    $cor_fundo_t                    = $dba->result($res, 0, "cor_fundo_t");                    $retObj = new UsuariosDAO();                    $retObj->setId($id);                    $retObj->setEmail($email);                    $retObj->setNome($nome);                    $retObj->setSobrenome($sobrenome);                    $retObj->setSenha($senha);                    $retObj->setDt_nasc($dt_nasc);                    $retObj->setSexo($sexo);                    $retObj->setDt_cadastro($dt_cadastro);                    $retObj->setFoto($foto);                    $retObj->setCor_fundo_t($cor_fundo_t);                                        $retorno[0] = 0;                    $retorno[1] = "";                    $retorno[2] = $retObj;            } // if (!$res)...        } // if (!$res)...	return $retorno;    }    //método que faz a atualização de Usuarios no BD    public static function update($obj) {        //pegar os dados do objeto        $id                             = $obj->getId();        $email                          = $obj->getEmail();        $nome                           = $obj->getNome();        $sobrenome                      = $obj->getSobrenome();        $senha                          = $obj->getSenha();        $dt_nasc                        = $obj->getDt_nasc();        $sexo                           = $obj->getSexo();        $dt_cadastro                    = $obj->getDt_cadastro();        $foto                           = $obj->getFoto();        $cor_fundo_t                    = $obj->getCor_fundo_t();        if (empty($dt_atualizacao))                $dt_atualizacao = "now()";        if (empty($cor_fundo_t))                $cor_fundo_t = "#CCCCCC";                //montar o comando SQL        $sql = "update Usuarios           set                email                         = '$email'               ,nome                          = '$nome'               ,sobrenome                     = '$sobrenome'               ,senha                         = '$senha'               ,dt_nasc                       = '$dt_nasc'               ,sexo                          = '$sexo'               ,dt_cadastro                   = '$dt_cadastro'               ,foto                          = '$foto'               ,cor_fundo_t                   = '$cor_fundo_t'           where id = $id";        $sql = str_replace("''", "null", $sql);        $sql = str_replace("'now()'", "now()", $sql);        // abre a conexão com o BD        $dba = new DbAdmin();        $dba->connectDefault();        //executar o comando SQL        $res = $dba->query($sql);        $retorno = Array();        if (!$res) {                $retorno[0] = mysql_errno();                $retorno[1] = mysql_error();                $retorno[2]  = "";        }        else {            $sql = "select * from Usuarios where id = $id";            $res = $dba->query($sql);            if (!$res) {                    $retorno[0] = mysql_errno();                    $retorno[1] = mysql_error();                    $retorno[2] = "";            }            else {                    $id                             = $dba->result($res, 0, "id");                    $email                          = $dba->result($res, 0, "email");                    $nome                           = $dba->result($res, 0, "nome");                    $sobrenome                      = $dba->result($res, 0, "sobrenome");                    $senha                          = $dba->result($res, 0, "senha");                    $dt_nasc                        = $dba->result($res, 0, "dt_nasc");                    $sexo                           = $dba->result($res, 0, "sexo");                    $dt_cadastro                    = $dba->result($res, 0, "dt_cadastro");                    $foto                           = $dba->result($res, 0, "foto");                    $cor_fundo_t                    = $dba->result($res, 0, "cor_fundo_t");                    $retObj = new UsuariosDAOExt();                    $retObj->setId($id);                    $retObj->setEmail($email);                    $retObj->setNome($nome);                    $retObj->setSobrenome($sobrenome);                    $retObj->setSenha($senha);                    $retObj->setDt_nasc($dt_nasc);                    $retObj->setSexo($sexo);                    $retObj->setDt_cadastro($dt_cadastro);                    $retObj->setFoto($foto);                    $retObj->setCor_fundo_t($cor_fundo_t);                    $retorno[0] = 0;                    $retorno[1] = "";                    $retorno[2] = $retObj;            } // if (!$res)...        } // if (!$res)...	return $retorno;    }    //método que faz a exclusão de Usuarios no BD    public static function delete($id) {        //montar o comando SQL        $sql = "delete from Usuarios where id = $id";        // abre a conexão com o BD        $dba = new DbAdmin();        $dba->connectDefault();        //executar o comando SQL        $res = $dba->query($sql);        $retorno = Array();        if (!$res) {                $retorno[0] = mysql_errno();                $retorno[1] = mysql_error();        }        else {                $retorno[0] = "0";                $retorno[1] = "";        }        return $retorno;    }    /* Método estático que retorna os regsitros da tabela     * Usuarios conforme o filtro informado. */    public static function select($where="", $orderBy="", $limite=0, $offset=0) {        $dba = new DbAdmin();        $dba->connectDefault();        if (!empty($where))                $where = "where $where";        if (!empty($orderBy))                $where .= " order by $orderBy";        $sql = "select * from Usuarios $where";//die($sql);        if ($limite > 0)                $sql.= " limit " . $limite;        if ($offset > 0)                $sql.= " offset " . $offset;        $res = $dba->query($sql);        $num = $dba->rows($res);        $vet = array();        for ($i=0; $i < $num; $i++) {            $id                             = $dba->result($res, $i, "id");            $email                          = $dba->result($res, $i, "email");            $nome                           = $dba->result($res, $i, "nome");            $sobrenome                      = $dba->result($res, $i, "sobrenome");            $senha                          = $dba->result($res, $i, "senha");            $dt_nasc                        = $dba->result($res, $i, "dt_nasc");            $sexo                           = $dba->result($res, $i, "sexo");            $dt_cadastro                    = $dba->result($res, $i, "dt_cadastro");            $foto                           = $dba->result($res, $i, "foto");            $cor_fundo_t                    = $dba->result($res, $i, "cor_fundo_t");			$vet[$i] = new UsuariosDAOExt();            $vet[$i]->setId($id);            $vet[$i]->setEmail($email);            $vet[$i]->setNome($nome);            $vet[$i]->setSobrenome($sobrenome);            $vet[$i]->setSenha($senha);            $vet[$i]->setDt_nasc($dt_nasc);            $vet[$i]->setSexo($sexo);            $vet[$i]->setDt_cadastro($dt_cadastro);            $vet[$i]->setFoto($foto);            $vet[$i]->setCor_fundo_t($cor_fundo_t);	}        $vetRetorno = Array(((!$num) ? 0 : $num), $vet);        //matriz com os dados (linhas e colunas)        return $vetRetorno;    }    /* Método estático que retorna um unico regsitro da tabela     * Usuarios conforme chave informada. */    public static function selectOne($id) {        $dba = new DbAdmin();        $dba->connectDefault();        $sql = "select * from Usuarios where id = '$id'";        $res = $dba->query($sql);        $num = $dba->rows($res);        $obj = new UsuariosDAOExt();        if ($num > 0) {            $id                             = $dba->result($res, 0, "id");            $email                          = $dba->result($res, 0, "email");            $nome                           = $dba->result($res, 0, "nome");            $sobrenome                      = $dba->result($res, 0, "sobrenome");            $senha                          = $dba->result($res, 0, "senha");            $dt_nasc                        = $dba->result($res, 0, "dt_nasc");            $sexo                           = $dba->result($res, 0, "sexo");            $dt_cadastro                    = $dba->result($res, 0, "dt_cadastro");            $foto                           = $dba->result($res, 0, "foto");            $cor_fundo_t                    = $dba->result($res, 0, "cor_fundo_t");            $obj->setId($id);            $obj->setEmail($email);            $obj->setNome($nome);            $obj->setSobrenome($sobrenome);            $obj->setSenha($senha);            $obj->setDt_nasc($dt_nasc);            $obj->setSexo($sexo);            $obj->setDt_cadastro($dt_cadastro);            $obj->setFoto($foto);            $obj->setCor_fundo_t($cor_fundo_t);       }       return $obj;    }}?>