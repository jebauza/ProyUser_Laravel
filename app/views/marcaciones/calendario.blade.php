@extends('layouts.principal')

@section('title')
| Marcación
@stop

@section('css')
{{ HTML::style('assets/marcaciones/css/marcaciones.css'); }}
@stop

@section('activeMarcaciones')class="active"@stop

@section('body')
<?php
$anno = $arrDatosInformativos['datosAnnoMarcacion']['annoEscojido'];
$annIniMarc = $arrDatosInformativos['datosAnnoMarcacion']['annoIni'];
$mesEscojido = $arrDatosInformativos['datosAnnoMarcacion']['mesEscojido'];
$mesI = $arrDatosInformativos['datosAnnoMarcacion']['meses']['ini'];
$mesF = $arrDatosInformativos['datosAnnoMarcacion']['meses']['fin'];
$arrMeses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
?>
    <div class="bs-docs-section container">


    <div class="row">
    <div class="col-xs-3" id="lateral">
        <div id="filtro" class="panel col-xs-12">
            <div class="panel-heading "><span class="glyphicon glyphicon-search"></span>Filtro</div>

            {{ Form::open(array('url' => URL::route("marcaciones"),'class'=>"form-horizontal",'method'=>"post",'role'=>"form")) }}
                <div class="form-group ">

                    <input type="hidden" id="meses" value="{{ $arrDatosInformativos['datosAnnoMarcacion']['textoPeriodoMarcaciones'] }}" />
                    <label for="sel2 " class="col-sm-3">Año:</label>
                    <select class="form-control col-sm-9 input-sm" id="sel2" name="_anno">
                       <option value="{{ $anno }}">{{ $anno }}</option>
                       @for($i=date('Y');$i>=$annIniMarc;$i--)
                         @if($i!=$anno)
                          <option value="{{ $i }}">{{ $i }}</option>
                         @endif
                       @endfor
                    </select>

                    <td> <label class="col-sm-3"  for="sel1">Mes:</label>
                        <select class="form-control  col-sm-9 input-sm" id="sel1" name="_mes">
                               <option value="{{ $mesEscojido }}">{{ $arrMeses[$mesEscojido - 1] }}</option>

                               @for($j=$mesI;$j<=$mesF;$j++)
                                 @if($j!=$mesEscojido*1)
                                      <?php
                                      $mesNum = $j*1;
                                      if($mesNum<10)
                                      {
                                        $mesNum = '0'.$j*1;
                                      }
                                      ?>
                                      <option value="{{ $mesNum }}">{{ $arrMeses[($j*1)-1] }}</option>
                                 @endif
                               @endfor


                        </select></td>

                </div>
                <button type="submit" class="btn btn-default right">Buscar</button>
        {{ Form::close() }}
        </div>

        <div id="reporte" class="panel col-xs-12 ">
                    <div class="panel-heading "><span class="glyphicon glyphicon-stats"></span>Reporte</div>
                    <dl class="dl-horizontal">
                        <dt>Dias laborables:</dt>
                        <dd><em>{{ $arrDatosInformativos['dias_laborables'] }}</em></dd>
                        <dt>Dias trabajados</dt>
                        <dd><em>{{ $arrDatosInformativos['dias_trabajados'] }}</em></dd>
                        <dt>Dias feriados:</dt>
                        <dd>{{ $arrDatosInformativos['dias_feriados'] }}</dd>
                        <dt>Ausencias:</dt>
                        <dd>{{ $arrDatosInformativos['ausencias'] }}</dd>
                        <dt>Impuntualidades:</dt>
                        <dd><em>{{ $arrDatosInformativos['Impuntualidades'] }}</em></dd>
                        <dt>Viatico total:</dt>
                        <dd><em>{{ $arrDatosInformativos['dias_laborables']*0.6 }} cuc</em></dd>
                        <dt>Viatico a cobrar:</dt>
                        <dd class="text-danger"> <em>{{ $arrDatosInformativos['dias_trabajados']*0.6 }} cuc</em></dd>
                    </dl>
        </div>

    </div>


        <div class="col-xs-9 " >
            <div class="panel col-xs-12 ">
                <div class="panel-heading ">Reporte de marcaciones del mes de {{$arrMeses[$mesEscojido - 1]}} del {{$anno}}</div>
                <div class="calendario">
                    <table class="table table-bordered  ">

                        <thead>
                        <tr>
                            <th>Sem</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miecoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                            <th>Sabado</th>
                            <th>Domingo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $arrFeriados = array('0101'=> true,'0102'=> true,'0501'=> true,'0725'=> true,'0726'=> true,'0727'=> true,'1010'=> true,'1225'=> true,'1231'=> true);
                        $numDiasdibujado = 0;
                        $diaSemanaPrimerDiaDelMes = $arrDatosAnnoMes['diaSemanaPrimerDiaDelMes'];
                        $cantDiasMes = $arrDatosAnnoMes['cant_dias_mes'];
                        $cantDiasMarcados = $arrDatosInformativos['dias_trabajados'];
                        $fecha = '';
                        ?>
                        @for($i=1;$i<=6 && $numDiasdibujado < $cantDiasMes;$i++)
                            <tr class="" >
                                <td>{{ $i }}</td>
                                @for($j=1;$j<=7;$j++)
                                   @if(($j<$diaSemanaPrimerDiaDelMes && $i==1)||($numDiasdibujado >= $cantDiasMes))
                                       <td></td>
                                   @else

                                     <?php
                                     $numDiasdibujado=$numDiasdibujado+1;
                                     $fecha = $arrDatosAnnoMes['codigo'].$numDiasdibujado;
                                     if($numDiasdibujado<10)
                                     {
                                       $fecha = $arrDatosAnnoMes['codigo']."0".$numDiasdibujado;
                                     }
                                     ?>


                                  @if(isset($arrMarcPorFecha[$fecha]['tipoCasilla']))
                                       @if($arrMarcPorFecha[$fecha]['tipoCasilla']=="conMarcacionesERROR")
                                           <td class="alert alert-danger " ><p   class="dia"><em >{{ $numDiasdibujado }}</em></p><p>{{ $arrMarcPorFecha[$fecha]['marcaciones'][0]['hora']}}<br/>{{ $arrMarcPorFecha[$fecha]['marcaciones'][count($arrMarcPorFecha[$fecha]['marcaciones'])- 1]['hora'] }}<span class="pull-right glyphicon glyphicon-bell"></span></p></td>
                                       @elseif($arrMarcPorFecha[$fecha]['tipoCasilla']=="conMarcacionesOK")
                                           <td><p  class="dia"><em >{{ $numDiasdibujado }}</em></p><p>{{ $arrMarcPorFecha[$fecha]['marcaciones'][0]['hora']}}<br/>{{ $arrMarcPorFecha[$fecha]['marcaciones'][count($arrMarcPorFecha[$fecha]['marcaciones'])-1]['hora'] }}</p></td>
                                       @elseif($arrMarcPorFecha[$fecha]['tipoCasilla']=="sabado_domingo")
                                           <td><p  class="dia"><em >{{ $numDiasdibujado }}</em></p></td>
                                       @elseif($arrMarcPorFecha[$fecha]['tipoCasilla']=="feriado")
                                           <td class="alert alert-info"><p  class="dia"><em>{{ $numDiasdibujado }}</em></p><p>Feriado</p></td>
                                       @elseif($arrMarcPorFecha[$fecha]['tipoCasilla']=="ausencia")
                                           <td class="alert alert-warning"><p  class="dia"><em >{{ $numDiasdibujado }}</em></p><p>Ausencia</p></td>
                                       @else
                                           <td><p  class="dia"><em >{{ $numDiasdibujado }}</em></p></td>
                                       @endif
                                  @else
                                      <td><p  class="dia"><em >{{ $numDiasdibujado }}</em></p></td>
                                  @endif





                                   @endif

                                @endfor
                            </tr>
                        @endfor
                        </tbody>
                    </table>

                </div>
                <div id="leyenda">
                    <ul class="list-inline">
                        <li>Hoy<div class="alert-success"></div> </li>
                        <li>Feriado<div class="alert-info"></div></li>
                        <li>Impuntualidades<div class="alert-danger"></div></li>
                        <li>Ausencias<div class="alert-warning"></div></li>
                    </ul>
                </div>
            </div>

        </div>



    </div>
    </div>
@stop

@section('js')
    {{ HTML::script('assets/marcaciones/js/marcaciones.js'); }}
@stop
