@extends('Admin.Layout.dashboard')
@section('css')
<style>
    .myadmin-dd .dd-list .dd-item .dd-handle-new {
        background: #fff;
        border: 1px solid rgba(120, 130, 140, .13);
        padding: 8px 16px;
        height: auto;
        font-family: Montserrat, sans-serif;
        font-weight: 400;
        border-radius: 0;
    }

    .dd-handle-new {
        display: block;
        height: 30px;
        margin: 5px 0;
        padding: 5px 10px;
        cursor: pointer;
        /* color: #000; */
        text-decoration: none;
        font-weight: 700;
        border: 1px solid #e5e5e5;
        background: #fafafa;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .activeli {
        background-color: #e9ecef !important;
    }
    .disabled {
        /* Make the disabled links grayish*/
        color: gray;
        /* And disable the pointer events */
        pointer-events: none;
    }
</style>
@endsection
@php $lang = App::getLocale(); @endphp
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">{{__('msg.questionsList')}} <a class="btn btn-success float-right" href="{{url('question/create')}}">{{__('msg.createQuestion')}}</a></h4>
                <hr>
                <div class="form-group row pb-3">
                    <label for="category" class="col-sm-3 text-right control-label col-form-label">{{__('form.topic')}} :</label>
                    <div class="col-sm-6">
                        <div class="myadmin-dd dd" id="nestable" style="width: 100% !important;">
                            <ol class="dd-list">
                                <li class="dd-item" id="parentdd">
                                    <div class="dd-handle-new">
                                        <strong class="selectedTopic">{{ $id ? $catName :__('form.select_topic') }}</strong>
                                    </div>
                                    <ol class="dd-list">
                                        @foreach($topic as $c)
                                        <li class="dd-item">
                                            <div class="dd-handle-new topicls" data-cid="{{$c->id}}">

{{--                                                @if(count($c->childs) == 0)--}}
{{--                                                <input type="checkbox" name="topic" value="{{$c->id}}" data-name="{{$lang=='gb'?$c->name:$c->bn_name}}" id="" class="programming">--}}
{{--                                                @endif--}}

                                                <span>
                                                    {{$lang == 'gb'?($c->name?$c->name:$c->bn_name):($c->bn_name?$c->bn_name:$c->name)}}

                                                </span>
                                            </div>
                                            @if(count($c->childs))
                                                @include('Admin.PartialPages.Questions._subtopic', ['category'=>$c->childs])
                                            @endif
                                        </li>
                                        @endforeach
                                    </ol>
                                </li>
                            </ol>
                        </div>

                    </div>

{{--                    <div class="col-sm-2 mt-1">--}}
{{--                        <a href="" class="btn btn-success smt">{{__('form.submit')}}</a>--}}
{{--                    </div>--}}

                </div>
                <div class="table-responsive" style="overflow-x: hidden">

                    <div id="zero_config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12 loading" style="display: none;">
                                <div class="text-center">
                                    <button class="btn btn-primary" type="button" disabled="">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                        {{__('form.loading')}}
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-12 pt-3" id="viewData">
                                <div class="container">
                                    <div class="row justify-content-md-center">
                                        <div class="alert alert-success text-center" role="alert" id="msg">
                                            <p class="pt-3">{{__('form.question_notify')}}.</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit-questions" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{__('form.question_update')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-material" method="POST" action="{{url('question/update')}}" autocomplete="off">
                    @csrf
                    <input type="hidden" id="uqid" name="qid">
                    <div id="quistion_view">
                    </div>
{{--                    <div class="text-center"><a href="" id="add_option">{{__('form.add_option')}}</a></div>--}}
                    <div class="modal-footer">
{{--                        <button type="submit" class="btn btn-info waves-effect">{{__('form.update')}}</button>--}}
                        <button type="button" class="btn btn-primary waves-effect " id="tet" >{{__('form.update')}}</button>
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{__('form.cancel')}}</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection
@section('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function() {
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: "GET",
                beforeSend: function() {
                    $('.loading').show();
                    $('#msg').hide();
                    // $('#viewData').hide();
                    console.log('BEFORE');
                },
                success: function(data) {
                    console.log(data);
                    if (data != '') {
                        $('#viewData').html(data);
                    } else {

                        $('#viewData').html(
                            `<div class="text-center">
                            <p>Questions not available.</p>
                            </div>`
                        );

                    }
                    console.log(data);
                },
                complete: function() {
                    $('.loading').hide();
                    $('#viewData').show();
                    console.log('COMPLETE');

                }
            })
            // window.history.pushState("", "", url);
        });



        $('.topicls input[name="topic"]').on('click',function (e){
            e.stopPropagation();

            var topics = $("input[name='topic']:checked").map(function() {
                return $(this).attr('data-name');
            }).get().join(', ');
            var topics_id = $("input[name='topic']:checked").map(function() {
                return $(this).val();
            }).get().join(',');

            // alert("My favourite programming languages are: " + programming);
            console.log(topics_id);
            $('.selectedTopic').html(topics);
            $('.smt').attr('data-tid',topics_id);
        })

        $('.smt').on('click',function (e){
            e.preventDefault();
            topicwithcategory($(this).attr('data-tid'));
        })




        var getId = "{{$id}}"
        if (getId != "") {
            topicwithcategory(getId);
        }
        $('.topic').on('change', function() {
            var id = $(this).val();
            if (id == 0) {
                $('#viewData').html(`<div class="container">
                                    <div class="row justify-content-md-center">
                                        <div class="alert alert-success text-center" role="alert" id="msg">
                                            <p class="pt-3">{{__('form.question_notify')}}</p>
                                        </div>
                                    </div>
                                </div>`);
            } else {
                topicwithcategory(id);

            }

        })
        $('.Qcategory').on('change', function() {
            var id = $(".topic option:selected").val();
            var cid = $(this).val();
            topicwithcategory(id, cid);

        })
        $(document).on('click', '.topicls', function() {

            // $(this).hasClass('activeli') ? $(this).removeClass('activeli') : [$('.topicls').removeClass('activeli'), $(this).addClass('activeli'), $('#selectedCid').val($(this).attr('data-cid')), $('#selectedTopic').html($(this).text())];

            if ($(this).hasClass('activeli')) {
                $(this).removeClass('activeli');
                $('#selectedCid').val('');
                $('.selectedTopic').html('Select Topic');
            } else {
                $('#parentdd').addClass('dd-collapsed').children('[data-action="collapse"]').hide();
                $('#parentdd').children('[data-action="expand"]').show();
                $('.topicls').removeClass('activeli');
                $(this).addClass('activeli');
                $('#selectedCid').val($(this).attr('data-cid'));
                $('.selectedTopic').html($(this).text());
                topicwithcategory($(this).attr('data-cid'));
            }

        })
        var updateOutput = function(e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };
        updateOutput($('#nestable').data('output', $('#nestable-output')));
        $('#nestable-menu').on('click', function(e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });

        $('#nestable-menu').nestable();
    })

    function topicwithcategory(id) {

        $.ajax({
            url: "{{url('question/getlist')}}/" + id,
            type: "GET",
            beforeSend: function() {
                $('.loading').show();
                $('#msg').hide();
                $('#viewData').hide();
                console.log('BEFORE');
            },
            success: function(data) {
                console.log('data'+data);
                if (data != '') {
                    $('#viewData').html(data);
                } else {

                    $('#viewData').html(
                        `<div class="text-center">
                            <p>{{__('form.no_data_found')}}</p>
                            </div>`
                    );

                }
                console.log(data);
            },
            complete: function() {
                $('.loading').hide();
                $('#viewData').show();
                console.log('COMPLETE');

            }
        })
    }
    $(document).on('click', '.edit', function() {
        var id = $(this).attr('data-id');
        // alert(id);
        $('#uqid').val(id);
        $.ajax({
            url: "{{url('question/edit')}}/" + id,
            type: 'GET',
            success: function(data) {
                $('#quistion_view').html(data);
                $('#edit-questions').modal('show');
            }
        })
    })

    $(document).on('click','.delete_q',function (){
        Swal.fire({
            title: "{{__('form.are_you_sure')}}",
            text: "{{__('form.no_revert')}}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{__('form.yes_delete_it')}}",
            cancelButtonText: "{{__('form.cancel')}}"
        }).then((result) => {
            if (result.value) {
                var $this = $(this);
                var id = $this.attr('data-id');
                $.ajax({
                    url: "{{url('deleteoption')}}/" + id,
                    type: "GET",
                    success: function(data) {
                        $('#op_'+ id).remove();
                    },
                    complete: function() {
                        toastr.success("{{__('form.delete_success')}}", {
                            "closeButton": true
                        });

                    }
                })

            }
        })

    })

    $(document).on('click', ".delete", function(e) {
        // e.preventDefault();
        Swal.fire({
            title: "{{__('form.are_you_sure')}}",
            text: "{{__('form.no_revert')}}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{__('form.yes_delete_it')}}",
            cancelButtonText: "{{__('form.cancel')}}"
        }).then((result) => {
            if (result.value) {
                var $this = $(this);
                var id = $this.attr('data-id');
                $.ajax({
                    url: "{{url('question/delete')}}/" + id,
                    type: "GET",
                    success: function(data) {
                        $this.closest("tr").remove();
                    },
                    complete: function() {
                        toastr.success("{{__('form.delete_success')}}", {
                            "closeButton": true
                        });

                    }
                })

            }
        })

    });
    {{--$('#add_option').on('click',function (e){--}}
    {{--    e.preventDefault();--}}
    {{--    var data = '';--}}
    {{--    data += `<div class="form-group row">--}}
    {{--                        <label for="option1" class="col-md-2 optionTitle">{{__('form.option')}} :</label>--}}
    {{--                        <div class="col-sm-4">--}}
    {{--                        <input name=oid[] class="oid" value='new' type='hidden' />--}}
    {{--                            <input type="text" class="form-control option" name="option[]" placeholder="{{__('form.option_en_placholder')}}">--}}
    {{--                        </div>--}}
    {{--                        <div class="col-sm-4">--}}
    {{--                            <input type="text" class="form-control bdoption" name="bdoption[]" placeholder="{{__('form.option_bn_placholder')}}">--}}
    {{--                        </div>--}}
    {{--                        <div class="col-sm-1 bt-switch">--}}
    {{--                            <input type="hidden" name="ans[]" class="hi ans" value="0">--}}
    {{--                            <input type="checkbox" class="chk" data-on-text="{{__('form.yes')}}" data-off-text="{{__('form.no')}}" data-size="normal" />--}}
    {{--                        </div>--}}
    {{--                        <div class="col-md-1">--}}
    {{--                            <a style="cursor: pointer" class="m-4 text-danger remove"><i class="fas fa-trash"></i></a>--}}
    {{--                        </div>--}}
    {{--                    </div>`;--}}
    {{--    $('#quistion_view').append(data);--}}
    {{--    $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();--}}
    {{--    numberSerial()--}}
    {{--})--}}

    $(document).on('click', '.remove', function() {
        $(this).closest('.row').remove();
        numberSerial()
    });
    var oid =[];
    var option =[];
    var optionImg =[];
    var bdoption =[];
    var ans =[];
    function updateQuestion(){
        // alert($('#questionUpdate').val() ? $('#questionUpdate').prop('files')[0]: 'null')
        // return
        // alert($('#questionUpdate').length)
        // return
        // console.log('file input', $('#questionUpdate'))
        var fd = new FormData();
        if($('#questionUpdate').length > 0){
            var files = $('#questionUpdate')[0].files;
            if(files.length > 0){
                // console.log('oid....', files[0].type.split('/')[0])
                fd.append('file', files[0]);
                fd.append('fileType', files[0].type.split('/')[0]);
            }
        }
        // console.log('type', $('.optipt'))
        // alert($('.optipt').length)
        // if($('.optipt').length > 0){
        //     $.each($('.optipt'), function(i, file) {
        //         // console.log('filesss.......',i,file.defaultValue)
        //         fd.append('optionimg[]', file.files[0]);
        //         fd.append('defaultoptionimg[]', file.defaultValue);
        //     });
        //     // fd.append('optImg[]', optionImg)
        // }
        fd.append('qid', $('#uqid').val());
        fd.append('cat_id', $('#ucat_id').val());
        fd.append('oid', oid);
        fd.append('option', option);
        fd.append('bdoption', bdoption);
        fd.append('ans', ans);
        fd.append('question', $('#uquestion').val());
        fd.append('bdquestion', $('#ubdquestion').val());
        fd.append('old_file_path', $('#ufile_path').val());
        $.ajax({
            url:"{{url('question-update')}}",
            type:"Post",
            {{--data:{--}}
            {{--    --}}{{--"_token": "{{ csrf_token() }}",--}}
            {{--    qid:$('#uqid').val(),--}}
            {{--    cat_id:$('#ucat_id').val(),--}}
            {{--    oid:oid,--}}
            {{--    option:option,--}}
            {{--    bdoption:bdoption,--}}
            {{--    ans:ans,--}}
            {{--    question:$('#uquestion').val(),--}}
            {{--    bdquestion:$('#ubdquestion').val(),--}}
            {{--    // fileUpdate:$('#questionUpdate')[0].files--}}
            {{--},--}}
            data: fd,
            contentType: false,
            processData: false,
            success:function (data){
                if(data.question_text != null){
                    $('#eq_'+$('#uqid').val()).html(data.question_text);
                }else{
                    $('#eq_'+$('#uqid').val()).html('-----');
                }
                if(data.bd_question_text != null){
                    $('#bq_'+$('#uqid').val()).html(data.bd_question_text);
                }else{
                    $('#bq_'+$('#uqid').val()).html('-----');
                }
                $('#eo_'+$('#uqid').val()).html('');
                $('#bo_'+$('#uqid').val()).html('');
                $.each(data.options, function(key, value) {
                    if(value.correct == 1){
                    console.log('english...', value.option);
                    console.log('bangla...', value.bd_option);
                    if (value.flag == 'img'){
                        // var img = document.createElement("img");
                        // img.className = 'file-upload-image'
                        // img.width = 30
                        // img.height = 100
                        // img.style = 'width:30px'
                        // img.src = '/'+ value.img_link
                        const srcImg = '/'+ value.img_link
                        $('#optImg_'+$('#uqid').val()).html(` <span class="btn btn-sm m-1" style="border: #5378e8 1px solid;">
                                                                <i class="fa fa-check" style="color:#5378e8"></i>
                                                               <img src="${srcImg}" alt="" width="30px">
                                                            </span>`)

                    }
                        if(!!value.option){
                            $('#eo_'+$('#uqid').val()).append(`<span class="btn btn-sm m-1" style="border: #5378e8 1px solid;">
                                                                <i class="fa fa-check" style="color:#5378e8"></i>
                                                                ${value.option}
                                                            </span>`)
                        }
                        else{
                            $('#eo_'+$('#uqid').val()).html('- - - -');
                        }

                        if(!!value.bd_option){
                            $('#bo_'+$('#uqid').val()).append(` <span class="btn btn-sm m-1" style="border: #5378e8 1px solid;">
                                                                <i class="fa fa-check" style="color:#5378e8"></i>
                                                                ${value.bd_option}
                                                            </span>`)
                        }
                        else{
                            $('#bo_'+$('#uqid').val()).html('- - - -');
                        }


                    }
                });
                if(data.fileType == 'image' || data.fileType == 'audio' || data.fileType == 'video'){
                    if (data.fileType == 'image') {
                        var img = document.createElement("img");
                        // img.className = 'file-upload-image'
                        img.width = 150
                        img.height = 100
                        img.src = '/'+ data.question_file_link
                        $('#fileavi_'+$('#uqid').val()).html(img)
                    } else if (data.fileType == 'video') {
                        var video = document.createElement('video');
                        video.src = '/'+ data.question_file_link
                        video.controls = true
                        // video.width = 400
                        video.width = 150
                        video.height = 100
                        $('#fileavi_'+$('#uqid').val()).html(video)
                    } else {
                        var audio = document.createElement('audio');
                        audio.style = 'width:150px'
                        audio.src = '/'+ data.question_file_link
                        audio.controls = true
                        $('#fileavi_'+$('#uqid').val()).html(audio)
                    }
                } else {
                    $('#fileavi_'+$('#uqid').val()).html('__')
                }
                $('#edit-questions').modal('hide');

                toastr.success("{{__('form.upload_notification_message')}}", {
                    "closeButton": true
                });
            }
        })
    }
    function getoid(){
        oid = [];
        $('.oid').each(function(i, obj) {
            oid.push($(this).val());
        });
       // return oid;
    }
    function geoption(){
        option = [];
        $('.option').each(function(i, obj) {
            option.push($(this).val());
        });
        // return oid;
    }
    function geoptionimg(){
        optionImg = [];
        $('.optipt').each(function(i, obj) {
            // console.log('object.....Img', obj.files)
            // var files = obj[0].files;
            optionImg.push(obj.files[0]);
        });
        // return oid;
    }
    function getbdoption(){
        bdoption = [];
        $('.bdoption').each(function(i, obj) {
            bdoption.push($(this).val());
        });
        // return oid;
    }
    function getans(){
        ans = [];
        $('.ans').each(function(i, obj) {
            ans.push($(this).val());
        });
        // return oid;
    }

    $('#tet').click(function (){
        // alert('hello');
        // geoptionimg();
        getoid();
        geoption();
        getbdoption();
        getans();
        updateQuestion();
        // console.log(oid,option,bdoption,ans);
    })
    function numberSerial() {
        {{--console.log('lang..', '{{$lang}}')--}}
        $('.optionTitle').each(function(idx, elem){
            if('{{$lang}}' == 'gb') {
                $(elem).text( 'Option ' + (idx+1));
            } else {
                $(elem).text( 'অপশন ' + q2bNumber(idx+1));
            }

        });
    }
    function q2bNumber(numb) {
        let numbString = numb.toString();
        let bn = ''
        let eb = {0: '০', 1: '১', 2: '২', 3: '৩', 4: '৪', 5: '৫', 6: '৬', 7: '৭', 8: '৮', 9: '৯'};
        [...numbString].forEach(n => bn += eb[n])
        return bn
    }
</script>
@endsection
