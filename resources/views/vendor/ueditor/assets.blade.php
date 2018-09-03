<!-- 配置文件 -->
<script type="text/javascript" src="{{ asset('vendor/ueditor/ueditor.config.js') }}"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="{{ asset('vendor/ueditor/ueditor.all.js') }}"></script>

<script id="container" name="{{$field}}" type="text/plain"></script>

<script>
    window.UEDITOR_CONFIG.serverUrl = '{{ config('ueditor.route.name') }}'

    var ue = UE.getEditor('container',{
        toolbars: [
            ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft','justifycenter', 'justifyright',  'link', 'insertimage', 'fullscreen']
        ],

        //,initialFrameWidth:1000  //初始化编辑器宽度,默认1000
        initialFrameHeight:400  //初始化编辑器高度,默认320

    });
    ue.ready(function() {
        ue.execCommand('container', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
    });
</script>