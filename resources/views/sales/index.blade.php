@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Ventas realizadas</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
               href="{!! route('sales.create') !!}">Nueva venta</a>
        </h1>
    </section>
    <div class="content" id="appSales">
        <div class="clearfix"></div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('warning') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('info') }}
            </div>
        @endif

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('sales.search_form')
                @include('sales.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Función para manejar los reportes sin Vue.js template compilation
        function print_report(route) {
            var url = "{{ url('ruta') }}".replace('ruta', route);
            $("#frmSearch").attr("action", url).submit();
        }

        // Event listeners para los botones
        $(document).ready(function() {
            $('#btn-sales').on('click', function() {
                print_report('sales');
            });
            
            $('#btn-reporte-economico').on('click', function() {
                print_report('reporte_economico');
            });
        });

        // Mantener Vue.js solo para otras funcionalidades que no requieran template compilation
        appSales = new Vue({
            el: "#appSales",
            data:{
                isReport: false
            }
        });
    </script>
@endsection

