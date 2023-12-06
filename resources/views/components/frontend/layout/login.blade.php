<div class="banner"  id="home">
       <div class="account-section mt-5">
          <div class="container">
             <div class="account-wrapper">
                <div class="account-body">
                   <h3 class="subtitle">Login</h3>
                   <form class="account-form" action="{{ route('login')}}" method="post" name="login">
                    @if ($errors->any())
                    <div class="alert alert-danger text-center">
                       <ul>
                           @foreach ($errors->all() as $error)
                               <li>{{ $error }}</li>
                           @endforeach
                       </ul>
                   </div>
                    @endif
                    @csrf
                      <div class="form-group text-center">
                         <label for="sign-up">Enter your email address:  </label>
						 <div class="input-group">
                         <input id="email" class="form-control @error('email') is-invalid @enderror" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" type="email"  name="email"  required autocomplete="off" autofocus>
						   <div class="input-group-append">
                        <span class="input-group-text pb-0 pt-0 input-icn"><i class="fa fa-user-circle"></i></span>
                   </div>
				     </div>
                      </div>
                      <div class="form-group text-center">

                     <label for="pass">Enter your password: </label>
					 <div class="input-group">
                         <input type="password" placeholder="" name="password" required  minlength="8" class="form-control @error('password') is-invalid @enderror" autocomplete="off" >
                         <div class="input-group-append">
                        <span class="input-group-text pb-0 pt-0 input-icn"><i class="fa fa-lock"></i></span>
                   </div>
                    </div>
                      </div> <div class="form-group mt-4" style="text-align:right">
                      <a href="userforgotpassword">Forget password?</a>
                      </div>
                      <div class="form-group text-center mt-4 mb-5">
                        <button type="submit" class="nav-link button-1 text-white btn button button-1 sub">Submit</button>
                      </div>

                   </form>
                </div>
                <div class="or">
                   <span>OR</span>
                </div>
                <div class="account-header">
                   <span class="d-block span-2">
                      Don't have an account?
                      <a href="{{ route('register')}}">Sign Up
                         Here
                      </a>
                   </span>
                </div>
             </div>
          </div>
       </div>
    </div>
