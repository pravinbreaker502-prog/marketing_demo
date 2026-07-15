<!DOCTYPE html>
<html lang="en">
  <head>
  {!! $datas['headerlinks'] !!}
    <style>
    .login-main {
         box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }
    </style>
  </head>
  <body>
    <!-- login page start-->
    <div class="container-fluid p-0">
    @if(session('success'))
    <script>
      // Show a Toastr notification for success
      toastr.success("{!! session('success') !!}");
    </script>
    @endif
    @if(session('error'))
    <script>
      // Show a Toastr notification for each error
      toastr.error("{!! session('error') !!}");
    </script>
    @endif
      <div class="row m-0">
        <div class="col-12 p-0">    
          <div class="login-card login-dark">
            <div>
              <div>
                <!-- <a class="logo" href="index.html"><img class="img-fluid for-dark" src="../assets/images/logo/logo.png" alt="looginpage"><img class="img-fluid for-light" src="../assets/images/logo/logo_dark.png" alt="looginpage"></a></div> -->
              <div class="login-main"> 
                <form class="theme-form" method="post" action="{{ route('user-check') }}">
                @csrf
                  <h2 style="text-align: center; margin-bottom: 21px;">Marketing Login </h2>
                  
                  <div class="form-group">
                    <label class="col-form-label">User Name</label>
                    <input class="form-control" type="text" name="username" id="username" required="" placeholder="User Name" >
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Password </label>
                    <div class="form-input position-relative">
                      <input class="form-control" type="password" name="login[password]" id="password" required="" placeholder="*********">
                      <div class="show-hide"> <span class="show"></span></div>
                    </div>
                  </div>
                  <div class="form-group mb-0">
                    <div class="checkbox p-0">
                      <input id="checkbox1" type="checkbox">
                      <label class="text-muted" for="checkbox1">Remember password</label>
                    </div><a class="link" href="javascript:;">Forgot password?</a>
                    <div class="text-end mt-3">
                      <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                    </div>
                  </div>
                  
                  <!-- <p class="mt-4 mb-0 text-center">Don't have account?<a class="ms-2" href="javascript:;">Create Account</a></p> -->
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      {!! $datas['scriptlinks'] !!}
    </div>
  </body>
</html>