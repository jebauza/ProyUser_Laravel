<?php



class MarcacionesController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/


    public function index()
    {
        $ci = Auth::usuario()->get()->ci;
        $annoMes = date("Y")."-".date("m");
        if(Input::server("REQUEST_METHOD") == "POST")
        {
            $annoMes = Input::get("_anno")."-".Input::get("_mes");
        }
        return View::make('marcaciones.calendario', $this->buscarCalendario($annoMes,$ci));
    }

    public function busquedaPorMesAnno()
    {
        $peticion = $this->getRequest();
        $ci = Auth::usuario()->get()->ci;
        if ($peticion->getMethod() == 'POST')
        {
            $annoMes=$peticion->get('_anno').$peticion->get('_mes');
            return $this->render('RmsMarcacionesBundle:Default:calendario.html.twig', $this->buscarCalendarioAction($annoMes,$targetaReloj));
        }
        else
        {
            $annoMes = date("Y").date("m");
            return $this->render('RmsMarcacionesBundle:Default:calendario.html.twig', $this->buscarCalendarioAction($annoMes,$targetaReloj));
        }
    }

    public function buscarCalendario($annoMes,$ci)
    {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre" );
        $arrDiasFeriados = array('0101'=>true,'0102'=>true,'0501'=>true,'0725'=>true,'0726'=>true,'0727'=>true,'1010'=>true,'1225'=>true,'1231'=>true);
        $conexion = DB::connection('mysql');
        $marcacionesPersonal = $conexion->table('tb_reloj_marcaciones')
            ->where('tb_reloj_marcaciones.id_ci', '=', $ci)
            ->where('tb_reloj_marcaciones.fecha', 'LIKE', "%$annoMes%")
            ->orderby('tb_reloj_marcaciones.fecha','asc')
            ->orderby('tb_reloj_marcaciones.hora','asc')
            ->get();
        //print_r($marcacionesPersonal);die;
        $annoMes = substr($annoMes,0,4).substr($annoMes,5,2);
        $cantDiasMes = date("t",mktime(0, 0, 0, substr($annoMes,4,2), 1, substr($annoMes,0,4)));
        $diaSemanaPrimerDiaDelMes = date("N",mktime(0, 0, 0, substr($annoMes,4,2), 1, substr($annoMes,0,4)));
        $arrDatosAnnoMes = array('anno'=>substr($annoMes,0,4),'mes'=>array('numero'=>substr($annoMes,4,2),'nombre'=>$meses[substr($annoMes,4,2)-1]),'codigo'=>$annoMes,'cant_dias_mes'=>$cantDiasMes,'diaSemanaPrimerDiaDelMes'=>$diaSemanaPrimerDiaDelMes);
        $arrMarcPorFecha = null;
        $arrDatosInformativos = array('dias_trabajados'=>0,'Viatico_cobrar'=>0,'Impuntualidades'=>0);
        //echo count($marcacionesPersonal);die;
        //$fecha = new DateTime($marcacionesPersonal[0]->fecha." ".$marcacionesPersonal[0]->hora);
        //echo $fecha->format('h:i a');die;
        //echo $fecha->format('Ymd');die;
        //echo $fechaIniMarcado;die;


        if(count($marcacionesPersonal)>0)
        {
            $arrMarcPorFecha = array();
            for($i=0;$i<count($marcacionesPersonal);$i++)
            {
                $fecha = new DateTime($marcacionesPersonal[$i]->fecha." ".$marcacionesPersonal[$i]->hora);
                //AQUI AGREGO EN EL ARREGLO CON EL PREFEJI DEL DIA ej(20150401) LA INFORMACION DE ESE DIA ES DECIR AL CONVOCAR ESE DIA VA A CONTENER VARIOS ARREGLOS DE INFORMACION POR CADA MARCACION DED ESE DIA
                if(isset($arrMarcPorFecha[$fecha->format('Ymd')]))
                {
                    $arrMarcPorFecha[$fecha->format('Ymd')]['marcaciones'][] = array('hora'=>$fecha->format('h:i a'), 't_card'=>$fecha->format('His'));
                }
                else
                {
                    $arrMarcPorFecha[$fecha->format('Ymd')] = array();
                    $arrMarcPorFecha[$fecha->format('Ymd')]['marcaciones'][] = array('hora'=>$fecha->format('h:i a'), 't_card'=>$fecha->format('His'));
                }
            }
            $arrDatosInformativos['dias_trabajados'] = count($arrMarcPorFecha);
            $arrDatosInformativos['Viatico_cobrar'] = $arrDatosInformativos['dias_trabajados']*0.6;
        }
        $arrDatosInformativos['dias_laborables']=0;
        $arrDatosInformativos['dias_feriados']=0;
        $arrDatosInformativos['ausencias']=0;

        for($n=1;$n<=$cantDiasMes;$n++)
        {
            $diaMktime=mktime(0, 0, 0, substr($annoMes,4,2), $n, substr($annoMes,0,4));
            switch($diaMktime)
            {
                //TIENE MARCACIONES EN ESE DIA
                case isset($arrMarcPorFecha[date("Ymd",$diaMktime)]):
                    if(empty($arrMarcPorFecha[date("Ymd",$diaMktime)]['tipoCasilla']))
                    {
                        $arrMarcPorFecha[date("Ymd",$diaMktime)]['tipoCasilla']="conMarcacionesOK";
                        $horarioUser_Ent_Sal = array('entrada'=>"080059",'salida'=>170000);
                        /*if($this->get('security.context')->getToken()->getUser()->getHoraEntradSalid()!= null)
                        {
                            $horaEnSa = explode("-",$this->get('security.context')->getToken()->getUser()->getHoraEntradSalid());
                            $horarioUser_Ent_Sal['entrada'] = substr($horaEnSa[0],0,1).substr($horaEnSa[0],2,2)."59";
                            $horarioUser_Ent_Sal['salida'] = substr($horaEnSa[1],0,1).substr($horaEnSa[1],2,2)."00" + 120000;
                        }*/
                        if($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'][0]['t_card'] > $horarioUser_Ent_Sal['entrada'] && (($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'][0]['t_card'] < $horarioUser_Ent_Sal['salida'] && date("N",$diaMktime)<5) || ($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'][0]['t_card'] < $horarioUser_Ent_Sal['salida']-10000 && date("N",$diaMktime)==5)))
                        {
                            $arrMarcPorFecha[date("Ymd",$diaMktime)]['tipoCasilla']="conMarcacionesERROR";
                            $arrDatosInformativos['Impuntualidades']++;
                        }
                        else
                        {
                            if($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'][count($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'])-1]['t_card'] > $horarioUser_Ent_Sal['entrada'] && (($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'][count($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'])-1]['t_card'] < $horarioUser_Ent_Sal['salida'] && date("N",$diaMktime)<5) || ($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'][count($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'])-1]['t_card'] < $horarioUser_Ent_Sal['salida']-10000 && date("N",$diaMktime)==5)))
                            {
                                $arrMarcPorFecha[date("Ymd",$diaMktime)]['tipoCasilla']="conMarcacionesERROR";
                                $arrDatosInformativos['Impuntualidades']++;
                            }
                            else
                            {
                                $horas = $arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'][count($arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'])-1]['t_card']-$arrMarcPorFecha[date("Ymd",$diaMktime)]['marcaciones'][0]['t_card'];
                                if(($horas<89941 && date("N",$diaMktime)<5) || $horas<79941)
                                {
                                    $arrMarcPorFecha[date("Ymd",$diaMktime)]['tipoCasilla']="conMarcacionesERROR";
                                    $arrDatosInformativos['Impuntualidades']++;
                                }
                            }
                        }
                    }
                    break;

                //ES UN SABADO O DOMINGO
                case date("N",$diaMktime)==7||date("N",$diaMktime)==6:
                    $arrMarcPorFecha[date("Ymd",$diaMktime)] = array();
                    $arrMarcPorFecha[date("Ymd",$diaMktime)]['tipoCasilla']="sabado_domingo";
                    break;

                //DIA FERIADO
                case isset($arrDiasFeriados[date("md",$diaMktime)]):
                    $arrMarcPorFecha[date("Ymd",$diaMktime)] = array();
                    $arrMarcPorFecha[date("Ymd",$diaMktime)]['tipoCasilla']="feriado";
                    $arrDatosInformativos['dias_laborables']--;
                    $arrDatosInformativos['dias_feriados']++;
                    break;

                //AUSENCIA
                case empty($arrMarcPorFecha[date("Ymd",$diaMktime)])&& date("Ymd")>date("Ymd",$diaMktime):
                    $arrMarcPorFecha[date("Ymd",$diaMktime)] = array();
                    $arrMarcPorFecha[date("Ymd",$diaMktime)]['tipoCasilla']="ausencia";
                    $arrDatosInformativos['ausencias']++;
                    break;
            }
            if(date("N",$diaMktime)<6)
            {
                $arrDatosInformativos['dias_laborables']++;
            }
        }
        $fechaIniMarcado = $conexion->table('tb_reloj_marcaciones')
            ->where('tb_reloj_marcaciones.id_ci', '=', $ci)
            ->min('fecha');
        $fechaIniMarcado = substr($fechaIniMarcado,0,4).substr($fechaIniMarcado,5,2).substr($fechaIniMarcado,8,2);
        $arrDatosInformativos['datosAnnoMarcacion']= $this->buscarArrAnno($fechaIniMarcado,$annoMes);
        $arrResp = array('arrMarcPorFecha'=>$arrMarcPorFecha,'arrDatosInformativos'=>$arrDatosInformativos,'arrDatosAnnoMes'=>$arrDatosAnnoMes);
        return $arrResp;


    }

    public function buscarArrAnno($fechaIniMarcacion,$annoMes)
    {
        $mesIniMarcaciones = substr($fechaIniMarcacion,4,2);
        $mesIniMarcaciones =  $mesIniMarcaciones*1;
        $mes_inicial = 1;
        $mes_final = 12;
        $annoEscojido = substr($annoMes,0,4);
        switch($annoEscojido)
        {
            case $annoEscojido==substr($fechaIniMarcacion,0,4):
                $mes_inicial = substr($fechaIniMarcacion,4,2)*1;
                if($annoEscojido==date("Y"))
                {
                    $mes_final = date("n");
                }
                break;

            case $annoEscojido==date("Y"):
                $mes_final = date("n");
                break;
        }
        $arrResp = array('annoEscojido'=>substr($annoMes,0,4),'mesEscojido'=>substr($annoMes,4,2)*1,'meses'=>array('ini'=>$mes_inicial,'fin'=>$mes_final),'annoIni'=>substr($fechaIniMarcacion,0,4),'textoPeriodoMarcaciones'=>$this->textoPeriodoMarcaciones(substr($fechaIniMarcacion,0,4),$mesIniMarcaciones,date("Y"),date("n")));
        return $arrResp;
    }

    public function textoPeriodoMarcaciones($annoIniMarcaciones,$mesIniMarcaciones,$annoFin,$mesFin)
    {
        $resp = "";
        for($i=$annoIniMarcaciones;$i<=$annoFin;$i++)
        {
            if($i==$annoIniMarcaciones)
            {
                $resp=$resp.$i."-$mesIniMarcaciones-12";
            }
            else
            {
                if($i==date("Y"))
                {
                    $resp="$resp/$i-1-".date("n");
                }
                else
                {
                    $resp="$resp/$i-1-12";
                }
            }
        }
        return $resp;
    }

}
