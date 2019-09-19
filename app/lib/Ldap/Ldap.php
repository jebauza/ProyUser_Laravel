<?php
/**
 * Created by PhpStorm.
 * User: J.B
 * Date: 12/04/2015
 * Time: 1:22
 */



class Ldap
{
    private $ldapConex;
    private $dominio;
    private $bind;

    public function crearConex($dominio,$port)
    {
        $conex = @ldap_connect($dominio,$port);
        $resp = false;
        if($conex != false)
        {
            //echo "- LDAP conectado a: ".$dominio."";die;
            $this->ldapConex = $conex;
            $this->dominio = $dominio;
            $resp = true;
        }
        return $resp;
    }

    public function pru()
    {
        return "si pincha";
    }




    public function auntenticarUsuario($usuario,$pass)
    {
        if ($this->ldapConex)
        {
            ldap_set_option($this->ldapConex, LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_set_option($this->ldapConex, LDAP_OPT_REFERRALS,0);
            $this->bind = @ldap_bind($this->ldapConex, "$usuario@$this->dominio", $pass);
            if ($this->bind)
            {
                //echo "Usuario autenticado... ".$sUsuario;
                return true;
            }
            else
            {
                //echo "Usuario no autenticado...".ldap_error($ldapconn);
                return false;
            }
        }
    }

    public function datosDeUsuario($usuario)
    {
        //$dn = "OU=Dir Desarrollo Tecnologico,DC=emproy2,DC=com,DC=cu";
        $dn = "";
        $arrExpl = explode(".",$this->dominio);
        for($i=0;$i<count($arrExpl);$i++)
        {
            $dn = $dn."DC=".$arrExpl[$i];
            if($i+1 < count($arrExpl))
            {
                $dn = $dn.",";
            }
        }
        $arr = array("displayname","mail","samaccountname","telephonenumber","givenname");
        $filtro = "(samaccountname=$usuario)";
        $busquedaLDAP = ldap_search($this->ldapConex, $dn, $filtro, $arr);
        $arrInformUsuario = ldap_get_entries($this->ldapConex, $busquedaLDAP);
        $resp = false;
        if ($arrInformUsuario["count"] > 0)
        {
            $resp = array();
            $resp['nombre'] = $arrInformUsuario[0]['givenname'][0];
            $resp['nombreApell'] = $arrInformUsuario[0]['displayname'][0];
            $resp['usuario'] = $arrInformUsuario[0]['samaccountname'][0];
            $resp['infoemacion'] = $arrInformUsuario[0]['dn'];
        }
        return $resp;
        //print_r($entries);
    }


    public function cerrarConexion()
    {
        ldap_close($this->ldapConex);
    }

    public function prueba()
    {
        //echo "$this->domin y $this->puero";die;
    }

}