<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript">
  var env     = "{{ env('APP_ENV') }}";
  var appUrl  = "{{ env('APP_URL') }}";
  var appName = "{{ env('APP_NAME') }}";
  
  var currUser = null;

  @if(Auth::check())
    currUser = {!! Auth::user() !!};
  @endif

  


  var currServerTimestamp = "{{ date('Y-m-d H:i:s', time()) }}";
  var server              = moment(currServerTimestamp, "YYYY-MM-DD HH:mm:ss")
  window.tdbsc            = moment().diff(server, "hours") * (moment() > server ? -1 : 1)

  var currMessage = null;
  @if(isset($message))
    currMessage = '{{ $message }}';
  @endif

  var config = @json(config(strtolower(env('APP_NAME'))));
</script>

@if(env('APP_ENV') == 'prod' || env('APP_ENV') == 'production')
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-2WQDM4GRPH"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-2WQDM4GRPH');
  </script>
@endif