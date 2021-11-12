@extends('layouts.app-header')
@section('content') 
    <section class="error_sec_data">
      <div id="notfound">
        <span class="error_badge">404</span>
        <div class="notfound">
          <div class="notfound-404">            
            <h1>Oops!</h1>
            <h2 class="server-error">The page you request is does not exists.</h2>
          </div>
          <a href="/">Go To Homepage</a>
        </div>
      </div>
    </section>

@endsection
<style type="text/css">
      section.error_sec_data {
    background-color: #4fbbff;
    background-image: linear-gradient(#4fbbff, #74c9ff);
    padding: 50px 0px;
    color: #fff;
    margin-bottom: 50px;
}
#notfound {
  position: relative;
  height: calc(80vh - 10vh);
}

#notfound .notfound {
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
}

.notfound {
  max-width: 380px;
  width: 100%;
  line-height: 1.4;
  text-align: center;
}

.notfound .notfound-404 {
  position: relative;
  height: 200px;
  margin: 0px auto 20px;
  z-index: -1;
}

.notfound .notfound-404 h1 {
  font-size: 236px;
  font-weight: 200;
  margin: 0px;
  color: #fff;
  text-transform: uppercase;
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
}
.notfound .notfound-404 h2 {
  font-size: 20px;
  font-weight: 400;
  text-transform: uppercase;
  color: red;
  background: #fff;
  padding: 10px 5px;
  margin: auto;
  display: inline-block;
  position: absolute;
  bottom: 0px;
  left: 0;
  right: 0;
}
.notfound a {
    background: #43aff2;
    border: none;
    border-radius: 50px;
    padding: 14px 38px;
    text-transform: uppercase;
    font-weight: 600;
    font-size: 18px;
    outline: none !important;
    color: #fff;
    text-decoration: none;
    box-shadow: 0px 6px 0px rgba(0,0,0,0.3) !important;
    margin-top: 20px;
    display: inline-block;
    transition: all 0.5s ease-in-out 0s;
}
.notfound a:hover {
    background: #f25d53;
    border-color: #f25d53;
    color: #fff;
    box-shadow: 0px 6px 0px #d84036 !important;
}
span.error_badge {
    font-size: 260px;
    position: absolute;
    left: 50%;
    line-height: 80px;
    font-weight: 700;
    opacity: 0.1;
    top: 50%;
    transform: translate(-50%, -50%);
}

@media only screen and (max-width: 767px) {
  .notfound .notfound-404 h1 {
    font-size: 148px;
  }
  span.error_badge {
    font-size: 140px;
}
#notfound {
    position: relative;
    height: calc(50vh - 10vh);
}
section.error_sec_data {
    padding: 50px 30px;
}


}

@media only screen and (max-width: 480px) {
  .notfound .notfound-404 {
    height: 148px;
    margin: 0px auto 10px;
  }
  .notfound .notfound-404 h1 {
    font-size: 86px;
  }
  .notfound .notfound-404 h2 {
    font-size: 16px;
  }
  .notfound a {
    padding: 7px 15px;
    font-size: 14px;
  }
}
    </style>