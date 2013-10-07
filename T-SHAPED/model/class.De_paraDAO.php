<?php
/*
 * Classe que representa a tabela "de_para" da base "T-shaped" 
 * e os comandos de acesso aos dados
 */

class de_paraDAO
{
    //atributos (variáveis) relacionadas as colunas da tabela
    protected $id;
    protected $descricao;
    protected $de;
    protected $para;

    //método construtor que já faz a conexão com o BD
    public function de_paraDAO() {}

    //métodos para obter e ajustar dados das variáveis (get e set)

    // -- SET id
    public function setId($id) {
            $this->id = $id;
    }
    // -- GET id
    public function getId() {
            return $this->id;
    }
    // -- SET descricao
    public function setDescricao($descricao) {
            $this->descricao = $descricao;
    }
    // -- GET descricao
    public function getDescricao() {
            return $this->descricao;
    }
    // -- SET de
    public function setDe($de) {
            $this->de = $de;
    }
    // -- GET de
    public function getDe() {
            return $this->de;
    }
    // -- SET para
    public function setPara($para) {
            $this->para = $para;
    }
    // -- GET para
    public function getPara() {
            return $this->para;
    }

    //método que faz a inserção de De_para no BD
    public static function insert($obj) {

        //pegar os dados do objeto
        $id                             = $obj->getId();
        $descricao                      = $obj->getDescricao();
        $de                             = $obj->getDe();
        $para                           = $obj->getPara();

        //montar o comando SQL
        $sql = "insert into De_para
                       (id
                       ,descricao
                       ,de
                       ,para)
                values
                       ('$id'
                       ,'$descricao'
                       ,'$de'
                       ,'$para')";

        $sql = str_replace("''", "null", $sql);
        $sql = str_replace("'now()'", "now()", $sql);

            // abre a conexão com o BD
            $dba = new DbAdmin();
            $dba->connectDefault();

            //executar o comando SQL
            $res = $dba->query($sql);

            $retorno = Array();

            if (!$res) {

                    $retorno[0] = mysql_errno();
                    $retorno[1] = mysql_error();
                    $retorno[2]  = "";

            }
            else {

        $id = mysql_insert_id();

        $sql = "select * from De_para where id = $id";

        $res = $dba->query($sql);
        $num = $dba->rows($res);

        if (!$res) {

                $retorno[0] = mysql_errno();
                $retorno[1] = mysql_error();
                $retorno[2] = "";

        }
        else {

                $id                             = $dba->result($res, 0, "id");
                $descricao                      = $dba->result($res, 0, "descricao");
                $de                             = $dba->result($res, 0, "de");
                $para                           = $dba->result($res, 0, "para");

                $retObj = new De_paraDAOExt();

                $retObj->setId($id);
                $retObj->setDescricao($descricao);
                $retObj->setDe($de);
                $retObj->setPara($para);

                $retorno[0] = 0;
                $retorno[1] = "";
                $retorno[2] = $retObj;

        } // if (!$res)...

    } // if (!$res)...

    return $retorno;

    }

    //método que faz a atualização de De_para no BD
    public static function update($obj) {

            //pegar os dados do objeto
            $id                             = $obj->getId();
            $descricao                      = $obj->getDescricao();
            $de                             = $obj->getDe();
            $para                           = $obj->getPara();

            //montar o comando SQL
            $sql = "update De_para
                    set
                        descricao                     = '$descricao'
                       ,de                            = '$de'
                       ,para                          = '$para'
                    where id = $id";

    $sql = str_replace("''", "null", $sql);
    $sql = str_replace("'now()'", "now()", $sql);

            // abre a conexão com o BD
            $dba = new DbAdmin();
            $dba->connectDefault();

            //executar o comando SQL
            $res = $dba->query($sql);

            $retorno = Array();

            if (!$res) {

                    $retorno[0] = mysql_errno();
                    $retorno[1] = mysql_error();
                    $retorno[2]  = "";

            }
            else {

        $sql = "select * from De_para where id = $id";

        $res = $dba->query($sql);

                if (!$res) {

                        $retorno[0] = mysql_errno();
                        $retorno[1] = mysql_error();
                        $retorno[2] = "";

                }
                else {


                        $id                             = $dba->result($res, 0, "id");
                        $descricao                      = $dba->result($res, 0, "descricao");
                        $de                             = $dba->result($res, 0, "de");
                        $para                           = $dba->result($res, 0, "para");

                        $retObj = new De_paraDAOExt();

                        $retObj->setId($id);
                        $retObj->setDescricao($descricao);
                        $retObj->setDe($de);
                        $retObj->setPara($para);

                        $retorno[0] = 0;
                        $retorno[1] = "";
                        $retorno[2] = $retObj;

                } // if (!$res)...

    } // if (!$res)...

            return $retorno;
    }

    //método que faz a exclusão de De_para no BD
    public static function delete($id) {

            //montar o comando SQL
            $sql = "delete from De_para where id = $id";

            // abre a conexão com o BD
            $dba = new DbAdmin();
            $dba->connectDefault();

            //executar o comando SQL
            $res = $dba->query($sql);

            $retorno = Array();

            if (!$res) {

                    $retorno[0] = mysql_errno();
                    $retorno[1] = mysql_error();

            }
            else {

                    $retorno[0] = "0";
                    $retorno[1] = "";

            }

            return $retorno;

    }

    /* Método estático que retorna os regsitros da tabela
     * De_para conforme o filtro informado. */
    public static function select($where="", $orderBy="", $limite=0, $offset=0) {

            $dba = new DbAdmin();
            $dba->connectDefault();

            if (!empty($where))
                    $where = "where $where";

            if (!empty($orderBy))
                    $where .= " order by $orderBy";

            $sql = "select * from De_para $where";

            if ($limite > 0)
                    $sql.= " limit " . $limite;

            if ($offset > 0)
                    $sql.= " offset " . $offset;

            $res = $dba->query($sql);
            $num = $dba->rows($res);

            $vet = array();

            for ($i=0; $i < $num; $i++) {

        $id                             = $dba->result($res, $i, "id");
        $descricao                      = $dba->result($res, $i, "descricao");
        $de                             = $dba->result($res, $i, "de");
        $para                           = $dba->result($res, $i, "para");

                    $vet[$i] = new De_paraDAOExt();

        $vet[$i]->setId($id);
        $vet[$i]->setDescricao($descricao);
        $vet[$i]->setDe($de);
        $vet[$i]->setPara($para);
            }

            $vetRetorno = Array(((!$num) ? 0 : $num), $vet);

            //matriz com os dados (linhas e colunas)
            return $vetRetorno;
    }

    /* Método estático que retorna um unico regsitro da tabela
     * De_para conforme chave informada. */
    public static function selectOne($id) {

            $dba = new DbAdmin();
            $dba->connectDefault();

            $sql = "select * from De_para where id = '$id'";

            $res = $dba->query($sql);
            $num = $dba->rows($res);

            $obj = new De_paraExt();

            if ($num > 0) {

        $id                             = $dba->result($res, 0, "id");
        $descricao                      = $dba->result($res, 0, "descricao");
        $de                             = $dba->result($res, 0, "de");
        $para                           = $dba->result($res, 0, "para");

        $obj->setId($id);
        $obj->setDescricao($descricao);
        $obj->setDe($de);
        $obj->setPara($para);
            }

            return $obj;
    }
}

?>