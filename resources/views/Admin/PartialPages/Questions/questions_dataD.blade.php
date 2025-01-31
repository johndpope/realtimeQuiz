@php $lang = App::getLocale(); @endphp
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs mb-3">
            @foreach($questions as $q)
            @if($q->questions->count() > 0)
            <li class="nav-item">
                <a href="#home{{$q->id}}" data-toggle="tab" aria-expanded="true" class="nav-link {{$loop->first?'active':''}}">
                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block">{{$lang=='gb'?$q->name:$q->bn_name}}</span>
                </a>
            </li>
            @endif
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($questions as $q)
            @if($q->questions->count() > 0)
            <div class="tab-pane {{$loop->first?'active':''}}" id="home{{$q->id}}">
                <div class="table-responsive" style="overflow-x: hidden">

                    <div class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12 pt-3">
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered dataTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 1%;">{{__('form.sl')}}</th>
                                                <th style="width: 2%;">{{__('form.created')}}</th>
                                                <th style="width: 4%;">{{__('form.file')}}</th>
                                                <th style="width: 30%;">{{__('form.question_en')}}</th>
                                                <th style="width: 30%;">{{__('form.question_bn')}}</th>
                                                <th style="width: 15%;">{{__('form.en_options')}}</th>
                                                <th style="width: 15%;">{{__('form.bn_options')}}</th>
                                                <th style="width: 3%;" class="text-center">{{__('form.action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($q->questions as $qs)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    @if($qs->role)
                                                        {{$lang=='gb'?$qs->role->role->role_name:$qs->role->role->bn_role_name}}
                                                    @else
                                                        <span>__</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($qs->question_file_link)
                                                    <img src="{{asset($qs->question_file_link)}}" alt="" width="30px" height="30px">
                                                    @else
                                                    <span>__</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$qs->question_text}}
                                                </td>
                                                <td>{{$qs->bd_question_text}}</td>
                                                <td class="text-center">
                                                    @foreach($qs->options as $qo)
                                                    <span class="btn btn-sm m-1" style="border: #5378e8 1px solid;">
                                                        <i class="{{$qo->correct?'fa fa-check':''}}" style="color:#5378e8"></i>
                                                        {{$qo->option}}
                                                    </span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($qs->options as $qo)
                                                    @if($qo->bd_option)
                                                    <span class="btn btn-sm m-1" style="border: #5378e8 1px solid;">
                                                        <i class="{{$qo->correct?'fa fa-check':''}}" style="color:#5378e8"></i>
                                                        {{$qo->bd_option}}
                                                    </span>
                                                    @endif
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    <a class="edit" style="cursor: pointer; color:black;" data-id="{{$qs->id}}" title="edit"><i class="fas fa-pencil-alt"></i></a>
                                                    <a class="delete" style="cursor: pointer;color:red;" data-id="{{$qs->id}}" title="Remove"><i class="fas fa-trash"></i></a>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>{{__('form.sl')}}</th>
                                                <th>{{__('form.created')}}</th>
                                                <th>{{__('form.file')}}</th>
                                                <th>{{__('form.question_en')}}</th>
                                                <th>{{__('form.question_bn')}}</th>
                                                <th>{{__('form.en_options')}}</th>
                                                <th>{{__('form.bn_options')}}</th>
                                                <th>{{__('form.action')}}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            @endif
            @endforeach

        </div>
    </div> <!-- end card-body-->
</div> <!-- end card-->
<script>
    var table;
    $('.dataTable').DataTable({
        responsive: true,
        "ordering": false,
    });
</script>
